<style>
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 14px;
        line-height: 1.5;
    }

    h2, h3 {
        font-weight: bold;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    th, td {
        border: 1px solid #000;
        padding: 6px;
        vertical-align: top;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    hr {
        margin: 40px 0;
        border: 0;
        border-top: 1px solid #999;
    }
</style>

<h2 style="text-align: center;">BÁO CÁO ĐƠN HÀNG</h2>

@foreach ($orders as $i => $order)
    <h3>Đơn hàng #{{ $order->code }}</h3>
    <p><strong>Khách hàng:</strong> {{ $order->user->name ?? 'Khách' }}</p>
    <p><strong>Email:</strong> {{ $order->user->email ?? 'Không có' }}</p>
    <p><strong>Điện thoại:</strong> {{ $order->user->phone ?? 'Không có' }}</p>
    <p><strong>Địa chỉ:</strong> {{ $order->address ?? 'Không có' }}</p>
    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
    <p><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>

    <p><strong>Mã giảm giá:</strong>
        {{ $order->voucherUser?->voucher?->code ?? 'Không áp dụng' }}
    </p>

    <p><strong>Ghi chú:</strong> {{ $order->note ?? '-' }}</p>

    <table width="100%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th>Biến thể</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $j => $item)
                <tr>
                    <td>{{ $j + 1 }}</td>
                    <td>{{ $item->variant->product->name ?? 'SP đã xoá' }}</td>
                    <td>
                        {{ $item->variant->color ?? '' }} /
                        {{ $item->variant->ram ?? '' }} /
                        {{ $item->variant->storage ?? '' }}
                    </td>
                    <td>{{ number_format($item->price) }}₫</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price * $item->quantity) }}₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Tạm tính:</strong> {{ number_format($order->subtotal ?? 0) }}₫</p>
    <p><strong>Giảm giá:</strong> {{ number_format($order->discount_amount ?? 0) }}₫</p>
    <p><strong>Tổng thanh toán:</strong> {{ number_format($order->total_amount) }}₫</p>
    <hr>
@endforeach
