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
    </style>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>

    @foreach($products as $product)
        <h2>{{ $product->name }}</h2>
        <p><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'Không có' }}</p>
        <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'Không có' }}</p>
        {{-- <p><strong>Mô tả:</strong> {{ $product->description }}</p> --}}

        <table>
            <thead>
                <tr>
                    <th>Màu</th>
                    <th>Bộ nhớ</th>
                    <th>RAM</th>
                    <th>Chip</th>
                    <th>Giá bán</th>
                    {{-- <th>Giá nhập</th> --}}
                    <th>Số lượng nhập</th>
                    <th>Đã bán</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $variant)
                    <tr>
                        <td>{{ $variant->color }}</td>
                        <td>{{ $variant->storage }}</td>
                        <td>{{ $variant->ram }}</td>
                        <td>{{ $variant->chip }}</td>
                        <td>{{ number_format($variant->price) }} VND</td>
                        {{-- <td>{{ number_format($variant->import_price) }} VND</td> --}}
                        <td>{{ $variant->quantity }}</td>
                        <td>{{ $variant->sold }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

</body>
</html>
