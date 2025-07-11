<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
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
                throw new Exception("Giỏ hàng trống.");
            }

            $order = Order::create([
                'user_id' => $userId,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'total_amount' => $cart->totalPrice(),
                'total_quantity' => $cart->totalQuantity(),
                'code' => 'DH' . now()->format('YmdHis') . rand(100, 999),

                // Thông tin từ người dùng
                'customer_name' => $formData['customer_name'],
                'customer_phone' => $formData['customer_phone'],
                'customer_email' => $formData['customer_email'] ?? null,
                'customer_address' => $formData['customer_address'],
                'note' => $formData['note'] ?? null,
            ]);

            //dd($order->toArray()); 


            //Tạo các item đơn hàng
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,

                    'price' => $item->snapshot_price,
                    'original_price' => $item->snapshot_original_price,
                    'cost_price' => $item->snapshot_cost_price,

                    'product_name' => $item->snapshot_product_name,
                    'product_slug' => $item->snapshot_product_slug,
                    'color' => $item->snapshot_color,
                    'storage' => $item->snapshot_storage,
                    'ram' => $item->snapshot_ram,
                    'chip' => $item->snapshot_chip,
                    'screen' => $item->snapshot_screen,
                    'battery' => $item->snapshot_battery,
                    'os' => $item->snapshot_os,
                    'weight' => $item->snapshot_weight,
                    'description' => $item->snapshot_description,
                    'image' => $item->snapshot_image,
                    'sale_percent' => $item->snapshot_sale_percent,
                ]);
            }

            // Xoá giỏ hàng sau khi đặt
            $cart->items()->delete();

            return $order;
        });
    }
}
