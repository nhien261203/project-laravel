<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = auth()->user();
        $productId = $request->input('product_id');

        // Kiểm tra đã mua hàng
        $hasPurchased = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed'])
            ->whereHas('items.variant', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();


        if (!$hasPurchased) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá khi đã mua sản phẩm.');
        }

        // Kiểm tra đã đánh giá chưa
        $hasReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($hasReviewed) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá. Đánh giá sẽ được duyệt sớm!');
    }
}
