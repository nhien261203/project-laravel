<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repositories\ProductVariant\ProductVariantRepositoryInterface;

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
            'sku' => 'nullable|string|max:255|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->except('_token');
        $data['images'] = $request->file('images');
        $data['primary_image_id'] = $request->input('primary_image_id'); // 💡 Thêm dòng này để lấy ảnh chính

        // Tự sinh SKU nếu không có
        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-P' . $productId . '-' . strtoupper(uniqid());
        }

        $this->variantRepo->create($productId, $data);

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
            'sku' => 'nullable|string|max:255|unique:product_variants,sku,' . $variantId,
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'images.*' => 'image|max:2048',
        ]);

        $data = $request->except(['_token', '_method']);
        $data['images'] = $request->file('images');
        $data['primary_image_id'] = $request->input('primary_image_id');

        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-P' . $productId . '-' . strtoupper(uniqid());
        }

        $this->variantRepo->update($variantId, $data);

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Cập nhật biến thể thành công');
    }

    public function destroy($productId, $variantId)
    {
        $this->variantRepo->delete($variantId);

        return redirect()->route('admin.products.variants.index', $productId)
            ->with('success', 'Xoá biến thể thành công');
    }


    public function deleteImage($productId, $imageId)
    {
        $this->variantRepo->deleteImage($imageId);

        return response()->json([
            'message' => 'Xoá ảnh thành công',
            'redirect' => url()->previous(), // Gửi lại URL để redirect về đúng trang edit
        ]);
    }

    public function show($productId, $variantId)
    {
        $variant = $this->variantRepo->find($variantId);
        return view('admin.products.variants.show', compact('variant'));
    }
}
