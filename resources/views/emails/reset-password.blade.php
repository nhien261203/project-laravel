<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khôi phục mật khẩu</title>
</head>
<body>
    <h3>Xin chào {{ $email }}</h3>

    <p>Bạn vừa yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>

    <p>Vui lòng nhấn vào nút bên dưới để tiến hành đặt lại mật khẩu:</p>

    <p>
        <a href="{{ url('/reset-password?token=' . $token . '&email=' . urlencode($email)) }}"
           style="display: inline-block; padding: 10px 20px; background-color: #3490dc; color: #fff; text-decoration: none; border-radius: 5px;">
            Đặt lại mật khẩu
        </a>
    </p>

    <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>

    <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
</body>
</html>
