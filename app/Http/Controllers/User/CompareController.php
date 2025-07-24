<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    // UserProductController.php
    public function compare(Request $request, $slug)
    {
        $productIds = $request->input('ids', []);
        $products = Product::with(['variants.images'])->whereIn('id', $productIds)->get();

        return view('user.product.compare', compact('products'));
    }
}
