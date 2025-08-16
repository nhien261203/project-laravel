<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        // Lọc theo trạng thái nếu có
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Tìm kiếm theo nội dung comment hoặc tên người dùng
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('comment', 'like', "%$keyword%")
                    ->orWhereHas('user', fn($user) => $user->where('name', 'like', "%$keyword%"));
            });
        }

        // Phân trang kết quả (10 đánh giá mỗi trang)
        $reviews = $query->paginate(10)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    
    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Đã duyệt đánh giá.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Đã từ chối đánh giá.');
    }

    public function unapprove(Review $review)
    {
        if ($review->status === 'approved') {
            $review->update(['status' => 'pending']);
            return back()->with('success', 'Đã bỏ duyệt đánh giá, trạng thái trở về chờ duyệt.');
        }

        return back()->with('error', 'Chỉ có thể bỏ duyệt đánh giá đang được duyệt.');
    }


    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Đã xoá đánh giá.');
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
        ]);

        $review->update(['status' => $request->status]);

        return back()->with('success', 'Cập nhật trạng thái đánh giá thành công.');
    }
}
