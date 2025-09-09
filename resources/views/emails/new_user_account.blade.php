
<html>
<head>
    <title>Thông tin tài khoản mới</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
        <h2 style="color: #0056b3;">Xin chào {{ $name }},</h2>

        <p>Tài khoản của bạn trên hệ thống đã được tạo thành công. Dưới đây là thông tin đăng nhập của bạn:</p>

        <ul style="list-style-type: none; padding: 0;">
            <li><strong>Email:</strong> {{ $email }}</li>
            <li><strong>Mật khẩu tạm thời:</strong> {{ $password }}</li>
        </ul>

        <p>Để đảm bảo an toàn, vui lòng đăng nhập và thay đổi mật khẩu ngay lập tức.</p>

        <p style="margin-top: 20px; text-align: center;">
            <a href="{{ url('/') }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">Truy cập trang web</a>
        </p>

        <p style="margin-top: 30px; font-size: 12px; color: #666;">
            Đây là email tự động, vui lòng không trả lời.
        </p>
    </div>

</body>
</html>
