<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

class ProductExportController extends Controller
{
    public function exportToTxt()
    {
        $products = Product::with(['brand', 'category', 'variants'])->get();
        $content = '';

        foreach ($products as $product) {
            $totalQuantity = $product->variants->sum('quantity');
            $totalSold = $product->variants->sum('sold');
            $totalRemaining = $totalQuantity - $totalSold;

            $content .= "Sản phẩm: {$product->name}\n";
            $content .= "Thương hiệu: " . ($product->brand->name ?? 'Không có') . "\n";
            $content .= "Danh mục: " . ($product->category->name ?? 'Không có') . "\n";
            $content .= "Mô tả: " . strip_tags($product->description) . "\n";



            // Tổng kết sản phẩm
            // $content .= "Tổng số lượng nhập: {$totalQuantity}\n";
            $content .= "Tổng đã bán: {$totalSold}\n";
            $content .= "Cửa hàng hiện có: {$totalRemaining}\n";

            $content .= "Biến thể:\n";

            foreach ($product->variants as $variant) {
                $remaining = $variant->quantity - $variant->sold;
                $content .= "- Màu: {$variant->color}, Bộ nhớ: {$variant->storage}, RAM: {$variant->ram}, Chip: {$variant->chip}, "
                    . "Giá bán: " . number_format($variant->price) . " VND, Đã bán: {$variant->sold}, Cửa hàng còn: {$remaining}\n";
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
