<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;

class UserBlogController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::whereHas('blogs')->get();
        $tagIds = $request->input('tags', []);

        $blogs = Blog::with('tags')
            ->when($tagIds, function ($query) use ($tagIds) {
                $query->whereHas('tags', function ($q) use ($tagIds) {
                    $q->whereIn('tags.id', $tagIds);
                });
            })
            ->latest()
            ->paginate(6)
            ->appends(['tags' => $tagIds]); // giữ lại query string khi phân trang

        return view('user.blogs.index', compact('blogs', 'tags', 'tagIds'));
    }


    // Chi tiết blog
    public function show($slug)
    {
        // Lấy bài blog
        $blog = Blog::where('slug', $slug)
            ->where('status', 1)
            ->with('tags')
            ->firstOrFail();

        // Lấy bình luận đã duyệt và phân trang
        $comments = $blog->comments()
            ->where('approved', 'approved') // chỉ lấy bình luận đã duyệt
            ->with('user') // lấy tên user nếu cần
            ->orderByDesc('created_at')
            ->paginate(5) // số bình luận mỗi trang
            ->withQueryString(); // giữ nguyên query khi phân trang

        // Lấy bài viết liên quan (cùng tag)
        $relatedBlogs = Blog::where('status', 1)
            ->where('id', '!=', $blog->id) // loại bài hiện tại
            ->whereHas('tags', function ($q) use ($blog) {
                $q->whereIn('tags.id', $blog->tags->pluck('id'));
            })
            ->with('tags')
            ->latest()
            ->take(5)
            ->get();
        return view('user.blogs.show', compact('blog', 'comments', 'relatedBlogs'));
    }
}
