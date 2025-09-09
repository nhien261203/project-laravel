<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AdminLogHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductVariant\ProductVariantRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProductVariantController extends Controller
{
    protected $variantRepo;

    public function __construct(ProductVariantRepositoryInterface $variantRepo)
    {
        $this->variantRepo = $variantRepo;
    }

    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $variants = $this->variantRepo->getByProduct($productId);

        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(Request $request, $productId)
    {
        $request->validate([
            'original_price' => 'required|numeric|min:0',
            'sale_percent' => 'required|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->except('_token');

        // Tính giá bán
        $data['price'] = round($data['original_price'] * (1 - ($data['sale_percent'] / 100)), 2);

        // Nếu không nhập SKU, tự sinh
        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-P' . $productId . '-' . strtoupper(uniqid());
        }

        $variant=$this->variantRepo->create($productId, $data);

        AdminLogHelper::log('create_variant', "Thêm biến thể [{$variant->sku}] cho sản phẩm ID {$productId}");

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Tạo biến thể thành công');
    }

    public function edit($productId, $variantId)
    {
        $product = Product::findOrFail($productId);
        $variant = $this->variantRepo->find($variantId);

        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, $productId, $variantId)
    {
        $request->validate([
            'original_price' => 'required|numeric|min:0',
            'sale_percent' => 'required|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->all();

        // Tính giá bán lại nếu có thay đổi
        $data['price'] = round($data['original_price'] * (1 - ($data['sale_percent'] / 100)), 2);

        $variant = $this->variantRepo->find($variantId);

        
        $this->variantRepo->update($variantId, $data);

        AdminLogHelper::log('update_variant', "Cập nhật biến thể [{$variant->sku}] của sản phẩm ID {$productId}");

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Cập nhật biến thể thành công');
    }

    public function destroy($productId, $variantId)
    {
        $variant = $this->variantRepo->find($variantId);
        $this->variantRepo->delete($variantId);

        AdminLogHelper::log('delete_variant', "Xoá biến thể [{$variant->sku}] của sản phẩm ID {$productId}");

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Xoá biến thể thành công');
    }

    public function deleteImage($productId, $imageId)
    {
        $this->variantRepo->deleteImage($imageId);

        return response()->json([
            'message' => 'Xoá ảnh thành công',
            'redirect' => url()->previous(),
        ]);
    }

    public function show($productId, $variantId)
    {
        $variant = $this->variantRepo->find($variantId);
        return view('admin.products.variants.show', compact('variant'));
    }
}
