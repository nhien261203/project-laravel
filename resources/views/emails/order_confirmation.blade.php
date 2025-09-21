<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận Đặt hàng thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2c3e50;
        }
        .content {
            padding: 20px 0;
        }
        .order-summary {
            background-color: #f9f9f9;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 5px;
        }
        .order-summary h3 {
            color: #2980b9;
            margin-top: 0;
        }
        .product-list {
            list-style: none;
            padding: 0;
            margin-top: 10px;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #ddd;
            padding: 10px 0;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            color: #777;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cảm ơn bạn đã đặt hàng!</h1>
            <p>Đơn hàng của bạn đã được tiếp nhận và đang chờ xử lý.</p>
        </div>

        <div class="content">
            <p>Xin chào {{ $user->name }},</p>
            <p>Chúng tôi xin xác nhận bạn đã đặt hàng thành công tại cửa hàng. Dưới đây là thông tin chi tiết về đơn hàng của bạn:</p>

            <div class="order-summary">
                <h3>Chi tiết đơn hàng của bạn</h3>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                {{-- Dựa vào phương thức helper statusLabel() đã có --}}
                <p><strong>Trạng thái đơn hàng:</strong> {{ $order->statusLabel() }}</p> 
                <p><strong>Trạng thái thanh toán:</strong> {{ $order->payment_status === 'unpaid' ? 'Chưa thanh toán' : 'Đã thanh toán' }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} VNĐ</p>
            </div>

            <div class="customer-info">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Tên:</strong> {{ $order->customer_name }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                <p><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address_detail }}, {{ $order->ward_name }}, {{ $order->district_name }}, {{ $order->province_name }}</p>
            </div>

            <div class="product-info">
                <h3>Sản phẩm đã đặt</h3>
                <ul class="product-list">
                    {{-- Lặp qua các OrderItem đã được tải --}}
                    @foreach($order->items as $item)
                        <li class="product-item ">
                            <span>{{ $item->quantity }} x {{ $item->product_name }} | </span>
                            
                            <span>{{ number_format($item->price) }} VNĐ</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- <p style="text-align: center; margin-top: 20px;">
                <a href="{{ route('user.orders.show', $order->id) }}" class="button">Xem chi tiết đơn hàng</a>
            </p> --}}
        </div>

        <div class="footer">
            <p>Xin cảm ơn bạn đã tin tưởng và ủng hộ chúng tôi!</p>
        </div>
    </div>
</body>
</html>
