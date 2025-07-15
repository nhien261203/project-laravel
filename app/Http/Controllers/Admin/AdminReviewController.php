<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        $pendingReviews = Review::with(['user', 'product'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $approvedReviews = Review::with(['user', 'product'])
            ->where('status', 'approved')
            ->latest()
            ->get();

        $rejectedReviews = Review::with(['user', 'product'])
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('admin.reviews.index', compact('pendingReviews', 'approvedReviews', 'rejectedReviews'));
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
}
