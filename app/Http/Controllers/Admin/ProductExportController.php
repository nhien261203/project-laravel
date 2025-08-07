<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use PDF;


class ProductExportController extends Controller
{
    public function exportToTxt()
    {
        $products = Product::with(['brand', 'category', 'variants'])->get();
        $content = '';

        foreach ($products as $product) {
            $content .= "Sản phẩm: {$product->name}\n";
            $content .= "Thương hiệu: " . ($product->brand->name ?? 'Không có') . "\n";
            $content .= "Danh mục: " . ($product->category->name ?? 'Không có') . "\n";
            // $content .= "Mô tả: {$product->description}\n";
            $content .= "Biến thể:\n";

            foreach ($product->variants as $variant) {
                $content .= "- Màu: {$variant->color}, Bộ nhớ: {$variant->storage}, RAM: {$variant->ram}, Chip: {$variant->chip}, "
                    . "Giá bán: " . number_format($variant->price) . " VND, "
                    . "Số lượng: {$variant->quantity}, Đã bán: {$variant->sold}\n";
            }

            $content .= "\n------------------------\n\n";
        }

        $filename = 'products_export.txt';
        File::put(public_path($filename), $content);

        return response()->download(public_path($filename))->deleteFileAfterSend();
    }

    public function exportToPdf()
    {
        $products = Product::with(['brand', 'category', 'variants'])->get();

        $pdf = PDF::loadView('admin.exports.products', compact('products'));
        return $pdf->download('products_export.pdf');
    }
}
