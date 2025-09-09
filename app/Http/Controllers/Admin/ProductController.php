<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminLogHelper;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'brand_id', 'status']);

        // Nếu có chọn category_id, lấy cả danh mục con
        if ($request->filled('category_id')) {
            $categoryIds = Category::where('id', $request->category_id)
                ->orWhere('parent_id', $request->category_id)
                ->pluck('id')
                ->toArray();

            $filters['category_ids'] = $categoryIds;
        }

        // Lấy sản phẩm theo filter (bao gồm category_ids nếu có)
        $products = $this->productRepo->getAll($filters, 10);

        // Dữ liệu dropdown
        // $categories = Category::all();
        $categories = Category::where('status', 1)
            ->get();

        $brands = Brand::where('status', 1)
            ->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }


    public function create()
    {
        $categories = Category::where('status', 1)
            ->get();
        $brands = Brand::where('status', 1)
            ->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $product = $this->productRepo->create($data);

        //Ghi log thêm
        AdminLogHelper::log('create_product', "Thêm sản phẩm: {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit($id)
    {
        $product = $this->productRepo->find($id);
        $categories = Category::where('status', 1)
            ->get();
        $brands = Brand::where('status', 1)
            ->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $product = $this->productRepo->find($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'status' => 'required|boolean'
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $changes = [];

        foreach ($data as $field => $newValue) {
            $oldValue = $product->$field;

            if ($oldValue != $newValue) {
                if ($field === 'category_id') {
                    $oldName = \App\Models\Category::find($oldValue)?->name ?? 'Không xác định';
                    $newName = \App\Models\Category::find($newValue)?->name ?? 'Không xác định';
                    $changes[] = "Danh mục thay đổi từ '{$oldName}' sang '{$newName}'";
                } elseif ($field === 'brand_id') {
                    $oldName = \App\Models\Brand::find($oldValue)?->name ?? 'Không xác định';
                    $newName = \App\Models\Brand::find($newValue)?->name ?? 'Không xác định';
                    $changes[] = "Thương hiệu thay đổi từ '{$oldName}' sang '{$newName}'";
                } else {
                    $changes[] = ucfirst($field) . " thay đổi từ '{$oldValue}' sang '{$newValue}'";
                }
            }
        }

        $this->productRepo->update($id, $data);

        // Ghi log chi tiết
        if (!empty($changes)) {
            $desc = "Cập nhật sản phẩm {$product->name}: " . implode('; ', $changes);
            AdminLogHelper::log('update_product', $desc);
        } else {
            AdminLogHelper::log('update_product', "Cập nhật sản phẩm {$product->name} nhưng không thay đổi dữ liệu");
        }
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = $this->productRepo->find($id);
        $this->productRepo->delete($id);

        // Ghi log xoá
        AdminLogHelper::log('delete_product', "Xóa sản phẩm: {$product->name}");
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công!');
    }

    public function show($id)
    {
        $product = $this->productRepo->find($id);
        return view('admin.products.show', compact('product'));
    }
}
