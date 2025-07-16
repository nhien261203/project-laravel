<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\VoucherUser;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderRepository implements OrderRepositoryInterface
{
    public function getAll()
    {
        return Order::with('items')->latest()->get();
    }

    public function find($id)
    {
        return Order::with('items')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function update($id, array $data)
    {
        $order = $this->find($id);

        // Nếu đơn từ trạng thái khác sang completed
        if (
            isset($data['status']) &&
            $data['status'] === 'completed' &&
            $order->status !== 'completed'
        ) {
            // Trừ số lượng tồn kho
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $item->variant->deductStock($item->quantity);
                }
            }
        }

        $order->update($data);
        return $order;
    }


    public function delete($id)
    {
        $order = $this->find($id);
        return $order->delete();
    }

    /**
     * Tạo đơn hàng từ giỏ hàng người dùng
     */
    public function createOrderFromCart(int $userId, array $formData)
    {
        return DB::transaction(function () use ($userId, $formData) {
            $cart = Cart::where('user_id', $userId)->with('items')->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw new \Exception("Giỏ hàng trống.");
            }

            $subtotal = $cart->totalPrice();
            $voucher = session('applied_voucher');
            $discount = 0;

            // Tính giảm giá nếu có voucher
            if ($voucher) {
                if ($voucher['type'] === 'percent') {
                    $discount = floor($subtotal * $voucher['value'] / 100);
                } else {
                    $discount = $voucher['value'];
                }

                // Giới hạn giảm tối đa
                if (!empty($voucher['max_discount']) && $discount > $voucher['max_discount']) {
                    $discount = $voucher['max_discount'];
                }
            }

            $total = $subtotal - $discount;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id'         => $userId,
                'status'          => 'pending',
                'payment_status'  => 'unpaid',
                'subtotal'        => $subtotal,
                'discount_amount' => $discount,
                'total_amount'    => $total,
                'voucher_code'    => $voucher['code'] ?? null,
                'code'            => 'DH' . now()->format('YmdHis') . rand(100, 999),

                // Thông tin từ form
                'customer_name'   => $formData['customer_name'],
                'customer_phone'  => $formData['customer_phone'],
                'customer_email'  => $formData['customer_email'] ?? null,
                // 'customer_address' => $formData['customer_address'],
                'note'            => $formData['note'] ?? null,
                'total_quantity'  => $cart->totalQuantity(),

// Thông tin địa chỉ nhận hàng
                'province_code'    => $formData['province_code'] ?? null,
                'province_name'    => $formData['province_name'] ?? null,
                'district_code'    => $formData['district_code'] ?? null,
                'district_name'    => $formData['district_name'] ?? null,
                'ward_code'        => $formData['ward_code'] ?? null,
                'ward_name'        => $formData['ward_name'] ?? null,
                'address_detail'   => $formData['address_detail'] ?? null,

            ]);

            // Tạo các item đơn hàng
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_variant_id'   => $item->product_variant_id,
                    'quantity'             => $item->quantity,
                    'price'                => $item->snapshot_price,
                    'original_price'       => $item->snapshot_original_price,
                    'cost_price'           => $item->snapshot_cost_price,
                    'product_name'         => $item->snapshot_product_name,
                    'product_slug'         => $item->snapshot_product_slug,
                    'color'                => $item->snapshot_color,
                    'storage'              => $item->snapshot_storage,
                    'ram'                  => $item->snapshot_ram,
                    'chip'                 => $item->snapshot_chip,
                    'screen'               => $item->snapshot_screen,
                    'battery'              => $item->snapshot_battery,
                    'os'                   => $item->snapshot_os,
                    'weight'               => $item->snapshot_weight,
                    'description'          => $item->snapshot_description,
                    'image'                => $item->snapshot_image,
                    'sale_percent'         => $item->snapshot_sale_percent,
                ]);
            }

            // Xoá giỏ hàng
            $cart->items()->delete();

            // Nếu có voucher → lưu vào bảng trung gian (nếu bạn dùng bảng voucher_user)
            if ($voucher) {
                VoucherUser::create([
                    'user_id'    => $userId,
                    'voucher_id' => $voucher['id'],
                    'order_id'   => $order->id,
                    'used_at'    => now(),
                ]);

                // Xoá voucher khỏi session
                session()->forget('applied_voucher');
            }

            return $order;
        });
    }
}
