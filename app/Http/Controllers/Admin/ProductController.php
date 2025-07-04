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
        $filters = $request->only(['keyword', 'category_id', 'brand_id','status']);
        $products = $this->productRepo->getAll($filters, 10);
        $categories = Category::all();
        $brands = Brand::all();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
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
        $categories = Category::all();
        $brands = Brand::all();

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

        $this->productRepo->update($id, $data);

        //ghi log
        AdminLogHelper::log('update_product', "Cập nhật sản phẩm: {$product->name}");

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = $this->productRepo->find($id);
        $this->productRepo->delete($id);

        // 📝 Ghi log xoá
        AdminLogHelper::log('delete_product', "Xóa sản phẩm: {$product->name}");
        return redirect()->back()->with('success', 'Xóa sản phẩm thành công!');
    }

    public function show($id)
    {
        $product = $this->productRepo->find($id);
        return view('admin.products.show', compact('product'));
    }
}
