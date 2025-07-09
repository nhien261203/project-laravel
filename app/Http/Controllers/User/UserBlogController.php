<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class UserBlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = Blog::where('status', 1)
            ->latest()
            ->with('tags')
            ->paginate(6);

        return view('user.blogs.index', compact('blogs'));
    }

    // Chi tiết blog
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 1)
            ->with('tags')
            ->firstOrFail();

        return view('user.blogs.show', compact('blog'));
    }
}
