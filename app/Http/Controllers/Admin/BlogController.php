<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with('tags');

        if (isset($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if (isset($request->status)) {
            $query->where('status', $request->status);
        }


        $blogs = $query->latest()->paginate(6)->withQueryString();

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('admin.blogs.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'thumbnail' => 'nullable|image|max:2048', // max ~2MB
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Xử lý ảnh
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $path = $file->store('uploads/blog', 'public');
            $data['thumbnail'] = $path;
        }

        $blog = Blog::create($data);

        if (!empty($data['tags'])) {
            $blog->tags()->attach($data['tags']);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'Thêm blog thành công!');
    }

    public function edit(Blog $blog)
    {
        $tags = Tag::all();
        return view('admin.blogs.edit', compact('blog', 'tags'));
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Nếu người dùng upload ảnh mới, xóa ảnh cũ
        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('uploads/blog', 'public');
        }

        $blog->update($data);
        $blog->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.blogs.index')->with('success', 'Cập nhật blog thành công!');
    }

    public function destroy(Blog $blog)
    {
        // Xoá ảnh nếu có
        if ($blog->thumbnail && Storage::disk('public')->exists($blog->thumbnail)) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->tags()->detach();
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('success', 'Xoá blog thành công!');
    }

    public function show(Blog $blog)
    {
        $blog->load('tags');
        return view('admin.blogs.show', compact('blog'));
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/blog', $filename, 'public');

            return response()->json([
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Upload failed.'], 400);
    }
}
