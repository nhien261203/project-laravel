<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->paginate(10);
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
            'title' => 'required|string|max:255',
            'image_desk' => 'nullable|image',
            'image_mobile' => 'nullable|image',
            'position' => 'required|string|max:255',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('image_desk')) {
            Storage::disk('public')->delete($banner->image_desk);
            $data['image_desk'] = $request->file('image_desk')->store('banners', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            Storage::disk('public')->delete($banner->image_mobile);
            $data['image_mobile'] = $request->file('image_mobile')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Đã cập nhật banner.');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete([$banner->image_desk, $banner->image_mobile]);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Đã xoá banner.');
    }
}
