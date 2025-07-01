<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Brand\BrandRepositoryInterface;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    protected $brandRepo;

    public function __construct(BrandRepositoryInterface $brandRepo)
    {
        $this->brandRepo = $brandRepo;
    }

    public function index(Request $request)
    {
        $brands = $this->brandRepo->all($request);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'nullable|string|max:100',
            'status' => 'required|boolean',
        ]);

        // Tự sinh slug nếu người dùng không nhập
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $this->brandRepo->create($validated);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand created successfully.');
    }

    public function edit($id)
    {
        $brand = $this->brandRepo->find($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $id,
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'country' => 'nullable|string|max:100',
            'status' => 'required|boolean',
        ]);

        // Tự sinh slug nếu không nhập
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $this->brandRepo->update($id, $validated);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy($id)
    {
        $this->brandRepo->delete($id);
        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'Brand deleted successfully.');
    }

    public function show($id)
    {
        $brand = $this->brandRepo->find($id);
        return view('admin.brands.show', compact('brand'));
    }
}
