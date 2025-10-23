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
        // Chỉ lấy sản phẩm có status = 1
        $products = Product::with([
            'brand',
            'category',
            'variants' => function ($q) {
                $q->where('status', 1); // chỉ lấy các biến thể trạng thái = 1
            }
        ])
            ->where('status', 1) // chỉ lấy sản phẩm trạng thái = 1
            ->get();

        $content = '';

        foreach ($products as $product) {
            $content .= "Sản phẩm: {$product->name}\n";
            $content .= "Thương hiệu: " . ($product->brand->name ?? 'Không có') . "\n";
            $content .= "Quốc gia: " . ($product->brand->country ?? 'Không có') . "\n";
            $content .= "Danh mục: " . ($product->category->name ?? 'Không có') . "\n";

            $content .= "Biến thể:\n";

            foreach ($product->variants as $variant) {
                $originalPrice = $variant->original_price ?? null;

                $salePrice = null;
                if ($originalPrice !== null && !empty($variant->sale_percent) && $variant->sale_percent > 0) {
                    $salePrice = $originalPrice - ($originalPrice * $variant->sale_percent / 100);
                }

                $line = "- Màu: {$variant->color}, Bộ nhớ: {$variant->storage}, RAM: {$variant->ram}, Chip: {$variant->chip}, ";

                if ($originalPrice !== null) {
                    $line .= "Giá chưa giảm: " . number_format($originalPrice) . " VND";
                } else {
                    $line .= "Giá chưa giảm: Không có";
                }

                $line .= ", Giảm: " . (isset($variant->sale_percent) ? $variant->sale_percent . "%" : "Không có");

                if ($salePrice !== null) {
                    $line .= ", Giá khuyến mãi: " . number_format($salePrice) . " VND";
                }

                $content .= $line . "\n";
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
