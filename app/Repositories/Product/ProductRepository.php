<?php

namespace App\Repositories\Product;

use App\Models\Category;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 8)
    {
        $query = Product::with(['category', 'brand']);

        if (!empty($filters['keyword'])) {
            $query->where('name', 'like', '%' . $filters['keyword'] . '%');
        }

        if (!empty($filters['category_ids'])) {
            $query->whereIn('category_id', $filters['category_ids']);
        }


        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', (int) $filters['status']);
        }

        return $query->latest()->paginate($perPage)->withQueryString();
    }

    public function find($id)
    {
        return Product::with(['category', 'brand', 'variants.images'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = $this->find($id);
        return $product->update($data);
    }

    public function delete($id)
    {
        $product = $this->find($id);
        return $product->delete();
    }

    // lay 5 sản phẩm iPhone cho home page
    public function getIphoneProducts(int $limit = 5)
    {
        // inStock goi tu phuong thuc scope ben Model ProductVariant
        return Product::with([
            'variants' => fn($q) => $q->inStock()->with('images')

        ])
            ->where('status', 1)
            ->whereHas('variants', fn($q) => $q->inStock())

            ->whereHas('category', fn($q) => $q->where('name', 'like', '%Điện thoại%'))
            ->whereHas(
                'brand',
                fn($q) =>
                $q->where('name', 'like', '%Apple%')->orWhere('name', 'like', '%iPhone%')
            )
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    // Lấy tất cả sản phẩm iPhone để cho xem tất cả trên home page
    // public function getAllIphoneProducts()
    // {
    //     $products = Product::with(['variants.images'])
    //         ->where('status', 1)
    //         ->whereHas('category', fn($q) => $q->where('name', 'like', '%Điện thoại%'))
    //         ->whereHas('brand', fn($q) =>
    //             $q->where('name', 'like', '%Apple%')->orWhere('name', 'like', '%iPhone%'))
    //         ->latest('id')
    //         ->get();

    //     return $this->appendProductExtras($products);
    // }

    public function getAllIphoneProducts()
    {
        $query = Product::with([
            'variants' => fn($q) => $q->inStock()->with('images')

        ])
            ->whereHas('variants', fn($q) => $q->inStock())

            ->where('status', 1)
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%Điện thoại%'))
            ->whereHas('brand', fn($q) =>
            $q->where('name', 'like', '%Apple%')->orWhere('name', 'like', '%iPhone%'));

        $query = $this->applyProductFilters($query);

        return $this->appendProductExtras($query->latest('id')->get());
    }


    public function getLaptopProducts(int $limit = 5)
    {
        return Product::with([
            'variants' => fn($q) => $q->inStock()->with('images')

        ])
            ->where('status', 1)
            ->whereHas('variants', fn($q) => $q->inStock())
            ->whereHas('category', fn($q) => $q->where('name', 'like', '%Laptop%'))
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function getAccessoryProducts(int $limit = 5)
    {
        // Nếu đã có ID trong session thì lấy ra
        $productIds = session('random_accessory_ids');

        if (!$productIds) {
            $rootCategory = Category::where('name', 'like', '%phụ kiện%')->first();

            if (!$rootCategory) {
                return collect(); // Không có danh mục, trả về collection rỗng
            }

            // Lấy ID danh mục gốc và các danh mục con (1 cấp)
            $categoryIds = Category::where('id', $rootCategory->id)
                ->orWhere('parent_id', $rootCategory->id)
                ->pluck('id');

            // Lấy ngẫu nhiên các ID sản phẩm
            $productIds = Product::where('status', 1)
                ->whereIn('category_id', $categoryIds)
                ->whereHas('variants', fn($q) => $q->inStock())
                ->inRandomOrder()
                ->limit($limit)
                ->pluck('id')
                ->toArray();

            // Lưu ID vào session
            session(['random_accessory_ids' => $productIds]);
        }

        // Truy vấn lại chi tiết sản phẩm từ ID
        return Product::with([
            'variants' => fn($q) => $q->inStock()->with('images')

        ])
            ->whereHas('variants', fn($q) => $q->inStock())
            ->whereIn('id', $productIds)
            ->get();
    }


    // public function getProductsByCategorySlug(string $slug)
    // {
    //     $category = Category::where('slug', $slug)->where('status', 1)->firstOrFail();

    //     $categoryIds = Category::where('id', $category->id)
    //         ->orWhere('parent_id', $category->id)
    //         ->pluck('id');

    //     $query = Product::with(['variants' => fn($q) => $q->orderBy('price'), 'variants.images', 'category'])
    //         ->whereIn('category_id', $categoryIds)
    //         ->where('status', 1)
    //         ->when(request('brand_ids'), function ($q) {
    //             $q->whereIn('brand_id', request('brand_ids'));
    //         });


    //     // Lọc giá
    //     if ($priceRanges = request('price_ranges')) {
    //         $query->whereHas('variants', function ($q) use ($priceRanges) {
    //             $q->where(function ($q2) use ($priceRanges) {
    //                 foreach ($priceRanges as $range) {
    //                     $q2->orWhere(function ($sub) use ($range) {
    //                         if ($range === 'under_1') {
    //                             $sub->where('price', '<', 1000000);
    //                         } elseif ($range === 'from_1_to_5') {
    //                             $sub->whereBetween('price', [1000000, 5000000]);
    //                         } elseif ($range === 'over_5') {
    //                             $sub->where('price', '>', 5000000);
    //                         } elseif ($range === 'under_10') {
    //                             $sub->where('price', '<', 10000000);
    //                         } elseif ($range === 'from_10_to_20') {
    //                             $sub->whereBetween('price', [10000000, 20000000]);
    //                         } elseif ($range === 'over_20') {
    //                             $sub->where('price', '>', 20000000);
    //                         } elseif ($range === 'under_15') {
    //                             $sub->where('price', '<', 15000000);
    //                         } elseif ($range === 'from_15_to_30') {
    //                             $sub->whereBetween('price', [15000000, 30000000]);
    //                         } elseif ($range === 'over_30') {
    //                             $sub->where('price', '>', 30000000);
    //                         }
    //                     });
    //                 }
    //             });
    //         });
    //     }

    //     // Sắp xếp
    //     if ($sort = request('sort')) {
    //         if ($sort === 'price_asc') {
    //             $query->withMin('variants', 'price')->orderBy('variants_min_price');
    //         } elseif ($sort === 'price_desc') {
    //             $query->withMax('variants', 'price')->orderByDesc('variants_max_price');
    //         }
    //     }

    //     $products = $query->latest('id')->get();

    //     return $this->appendProductExtras($products);
    // }


    // search cho header
    public function searchProducts(string $keyword)
    {

        $query= Product::with([
            'variants' => fn($q) => $q->inStock()->with('images'),
            'brand',
            'category'
        ])
            ->whereHas('variants', fn($q) => $q->inStock())


            ->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhereHas('variants', function ($q2) use ($keyword) {
                        $q2->where('color', 'like', "%{$keyword}%")
                            ->orWhere('storage', 'like', "%{$keyword}%")
                            ->orWhere('chip', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('brand', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', "%{$keyword}%");
                    })
                    ->orWhereHas('category', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', "%{$keyword}%");
                    });
            })
            ->where('status', 1)
            ->latest();

        $products = $query->get();

        return $this->appendProductExtras($products);
    }

    public function getProductsByCategorySlug(string $slug)
    {
        $category = Category::where('slug', $slug)->where('status', 1)->firstOrFail();

        $categoryIds = Category::where('id', $category->id)
            ->orWhere('parent_id', $category->id)
            ->pluck('id');

        $query = Product::with(['variants' => fn($q) => $q->inStock()->orderBy('price')->with('images'), 'category'])

            ->whereIn('category_id', $categoryIds)
            ->where('status', 1)
            ->whereHas('variants', fn($q) => $q->inStock())
            ->when(request('brand_ids'), function ($q) {
                $q->whereIn('brand_id', request('brand_ids'));
            });

        $query = $this->applyProductFilters($query);

        return $this->appendProductExtras($query->latest('id')->paginate(8)->withQueryString());
    }


    protected function appendProductExtras($products)
    {
        foreach ($products as $product) {
            $firstVariant = $product->variants->first();
            $product->all_storages = $product->variants->pluck('storage')->unique()->filter()->implode(' / ');
            $product->sale_percent = $firstVariant?->sale_percent ?? 0;
        }

        return $products;
    }

    // bộ lọc sản phẩm
    protected function applyProductFilters($query)
    {
        if ($priceRanges = request('price_ranges')) {
            $query->whereHas('variants', function ($q) use ($priceRanges) {
                $q->where(function ($q2) use ($priceRanges) {
                    foreach ($priceRanges as $range) {
                        $q2->orWhere(function ($sub) use ($range) {
                            if ($range === 'under_1') {
                                $sub->where('price', '<', 1000000);
                            } elseif ($range === 'from_1_to_5') {
                                $sub->whereBetween('price', [1000000, 5000000]);
                            } elseif ($range === 'over_5') {
                                $sub->where('price', '>', 5000000);
                            } elseif ($range === 'under_10') {
                                $sub->where('price', '<', 10000000);
                            } elseif ($range === 'from_10_to_20') {
                                $sub->whereBetween('price', [10000000, 20000000]);
                            } elseif ($range === 'over_20') {
                                $sub->where('price', '>', 20000000);
                            } elseif ($range === 'under_15') {
                                $sub->where('price', '<', 15000000);
                            } elseif ($range === 'from_15_to_30') {
                                $sub->whereBetween('price', [15000000, 30000000]);
                            } elseif ($range === 'over_30') {
                                $sub->where('price', '>', 30000000);
                            }
                        });
                    }
                });
            });
        }

        if ($sort = request('sort')) {
            if ($sort === 'price_asc') {
                $query->withMin('variants', 'price')->orderBy('variants_min_price');
            } elseif ($sort === 'price_desc') {
                $query->withMax('variants', 'price')->orderByDesc('variants_max_price');
            }
        }

        return $query;
    }
}
