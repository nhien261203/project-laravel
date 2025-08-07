<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Export Sản phẩm</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1, h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .summary {
            margin-bottom: 20px;
            font-size: 14px;
        }
        .summary p {
            margin: 4px 0;
        }
    </style>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>

    @foreach($products as $product)
        @php
            $totalQty = $product->variants->sum('quantity');
            $totalSold = $product->variants->sum('sold');
            $totalRemaining = $totalQty - $totalSold;
            $totalRevenue = $product->variants->sum(fn($v) => $v->sold * $v->price);
            $totalStockValue = $product->variants->sum(fn($v) => ($v->quantity - $v->sold) * $v->price);
        @endphp

        <h2>{{ $product->name }}</h2>
        <p><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'Không có' }}</p>
        <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Không có' }}</p>

        <div class="summary">
            <p><strong>Tổng số lượng nhập:</strong> {{ $totalQty }}</p>
            <p><strong>Tổng đã bán:</strong> {{ $totalSold }}</p>
            <p><strong>Tồn kho còn lại:</strong> {{ $totalRemaining }}</p>
            <p><strong>Doanh thu ước tính:</strong> {{ number_format($totalRevenue) }} VND</p>
            <p><strong>Giá trị tồn kho:</strong> {{ number_format($totalStockValue) }} VND</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Màu</th>
                    <th>Bộ nhớ</th>
                    <th>RAM</th>
                    <th>Chip</th>
                    <th>Giá bán</th>
                    <th>Số lượng nhập</th>
                    <th>Đã bán</th>
                    <th>Còn lại</th>
                    <th>Doanh thu biến thể</th>
                    <th>Giá trị tồn kho</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $variant)
                    @php
                        $remaining = $variant->quantity - $variant->sold;
                        $revenue = $variant->sold * $variant->price;
                        $stockValue = $remaining * $variant->price;
                    @endphp
                    <tr>
                        <td>{{ $variant->color }}</td>
                        <td>{{ $variant->storage }}</td>
                        <td>{{ $variant->ram }}</td>
                        <td>{{ $variant->chip }}</td>
                        <td>{{ number_format($variant->price) }} VND</td>
                        <td>{{ $variant->quantity }}</td>
                        <td>{{ $variant->sold }}</td>
                        <td>{{ $remaining }}</td>
                        <td>{{ number_format($revenue) }} VND</td>
                        <td>{{ number_format($stockValue) }} VND</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
