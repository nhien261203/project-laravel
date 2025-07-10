<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'blog']);

        // Lọc theo trạng thái duyệt
        if ($request->filled('approved')) {
            $query->where('approved', $request->approved);
        }

        // Tìm kiếm theo tên người dùng hoặc nội dung
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('content', 'like', "%$keyword%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%$keyword%"));
            });
        }

        $comments = $query->latest()->paginate(10)->appends($request->all());

        return view('admin.comments.index', compact('comments'));
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->approved = true;
        $comment->save();

        return redirect()->back()->with('success', 'Bình luận đã được duyệt.');
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Đã xóa bình luận.');
    }
}
