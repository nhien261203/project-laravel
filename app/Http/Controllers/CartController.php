<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\Voucher;
use App\Models\VoucherUser;
use Illuminate\Http\Request;
use App\Repositories\Cart\CartRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected $repo;

    public function __construct(CartRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $cart = $this->repo->getUserCart(Auth::id(), $request->session()->getId());

        // Lấy voucher còn hạn và đang active
        $vouchers = Voucher::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->latest()->take(5)->get(); // lấy tối đa 5 mã

        return view('cart.index', compact('cart', 'vouchers'));
    }

    // public function add(Request $request)
    // {
    //     $data = $request->validate([
    //         'variant_id' => 'required|exists:product_variants,id',
    //         'quantity' => 'required|integer|min:1'
    //     ]);

    //     $this->repo->addToCart(Auth::id(), $request->session()->getId(), $data['variant_id'], $data['quantity']);

    //     if ($request->ajax() || $request->expectsJson()) {
    //         return response()->json(['message' => 'Đã thêm vào giỏ']);
    //     }

    //     return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    // }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $variant = ProductVariant::findOrFail($data['variant_id']);

        // Kiểm tra tồn kho
        if ($data['quantity'] > $variant->quantity) {
            $message = 'Hiện tại cửa hàng chỉ còn ' . $variant->quantity . ' sản phẩm.';

            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['error' => $message], 400);
            }

            return redirect()->back()->with('error', $message);
        }

        $this->repo->addToCart(
            Auth::id(),
            $request->session()->getId(),
            $data['variant_id'],
            $data['quantity']
        );

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['message' => 'Đã thêm vào giỏ']);
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    public function remove(Request $request, $variantId)
    {
        $this->repo->removeFromCart(Auth::id(), $request->session()->getId(), $variantId);
        return redirect()->route('cart.index')->with('success', 'Đã xoá sản phẩm khỏi giỏ');
    }

    public function update(Request $request, $variantId)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $this->repo->updateQuantity(Auth::id(), $request->session()->getId(), $variantId, $data['quantity']);
        return redirect()->route('cart.index')->with('success', 'Đã cập nhật số lượng');
    }

    public function applyVoucher(Request $request)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Bạn cần đăng nhập để sử dụng voucher và đặt hàng.');
        }

        if ($request->session()->has('applied_voucher')) {
            return back()->with(['error' => 'Bạn đã áp dụng một mã giảm giá. Vui lòng bỏ mã hiện tại trước khi áp dụng mã mới.']);
        }
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        $code = $request->voucher_code;
        $user = Auth::user();

        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return back()->with(['error' => ' Mã không hợp lệ hoặc đã hết hạn.']);
        }

        // Kiểm tra người dùng đã dùng het so lan mã này chưa
        $usageCount = VoucherUser::where('user_id', $user->id)
            ->where('voucher_id', $voucher->id)
            ->count();

        if ($voucher->max_usage_per_user && $usageCount >= $voucher->max_usage_per_user) {
            return back()->with(['error' => 'Bạn đã sử dụng mã này tối đa ' . $voucher->max_usage_per_user . ' lần.']);
        }


        // Kiểm tra điều kiện đơn tối thiểu
        $cart = $this->repo->getUserCart($user->id, $request->session()->getId());
        $subtotal = $cart->items->sum(fn($i) => $i->snapshot_price * $i->quantity);

        if ($voucher->min_order_amount && $subtotal < $voucher->min_order_amount) {
            return back()->with(['error' => ' Đơn hàng cần tối thiểu ' . number_format($voucher->min_order_amount, 0, ',', '.') . '₫ để dùng mã này.']);
        }

        // Kiểm tra chỉ dành cho người mới
        if ($voucher->only_for_new_user && $user->orders()->exists()) {
            return back()->with(['error' => ' Mã chỉ áp dụng cho khách hàng mới.']);
        }

        // Lưu vào session
        $request->session()->put('applied_voucher', $voucher->toArray());

        return back()->with('success', 'Đã áp dụng mã giảm giá: ' . $voucher->code);
    }

    public function removeVoucher()
    {
        session()->forget('applied_voucher');
        return back()->with('success', ' Đã bỏ mã giảm giá.');
    }
}
