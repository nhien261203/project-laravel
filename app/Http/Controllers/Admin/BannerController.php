<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $query = Banner::query();

        if (isset($request->keyword)) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        if (isset($request->status) && in_array($request->status, ['0', '1'])) {
            $query->where('status', $request->status);
        }

        $banners = $query->latest()->paginate(8)->withQueryString();

        return view('admin.banners.index', compact('banners'));
    }



    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image_desk' => 'required|image',
            'image_mobile' => 'nullable|image',
            'position' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        // Upload ảnh
        $data['image_desk'] = $request->file('image_desk')->store('banners', 'public');

        if ($request->hasFile('image_mobile')) {
            $data['image_mobile'] = $request->file('image_mobile')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Đã thêm banner.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'position'      => 'required|string|max:100',
            'status'        => 'required|boolean',
            'image_desk'    => 'nullable|image|max:2048',
            'image_mobile'  => 'nullable|image|max:2048',
        ]);

        // Xử lý ảnh desktop nếu có upload mới
        if ($request->hasFile('image_desk')) {
            if ($banner->image_desk) {
                Storage::disk('public')->delete($banner->image_desk);
            }
            $data['image_desk'] = $request->file('image_desk')->store('banners', 'public');
        }

        // Xử lý ảnh mobile nếu có upload mới
        if ($request->hasFile('image_mobile')) {
            if ($banner->image_mobile) {
                Storage::disk('public')->delete($banner->image_mobile);
            }
            $data['image_mobile'] = $request->file('image_mobile')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Đã cập nhật banner.');
    }


    public function destroy(Banner $banner)
    {
        // Xoá ảnh nếu có
        if ($banner->image_desk) {
            Storage::disk('public')->delete($banner->image_desk);
        }

        if ($banner->image_mobile) {
            Storage::disk('public')->delete($banner->image_mobile);
        }

        // Xoá record
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Đã xoá banner.');
    }

    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }
}
