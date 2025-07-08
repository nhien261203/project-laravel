<?php

namespace App\Http\Controllers;

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
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $this->repo->addToCart(Auth::id(), $request->session()->getId(), $data['variant_id'], $data['quantity']);

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
}
