<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khôi phục mật khẩu</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <h2 style="color: #3490dc;">Xin chào {{ $email }}</h2>

        <p>Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.</p>

        <p>Vui lòng nhấn vào nút bên dưới để tiến hành đặt lại mật khẩu:</p>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ $resetUrl }}"
               style="display: inline-block; padding: 12px 25px; background-color: #3490dc; color: #fff; text-decoration: none; border-radius: 6px; font-size: 16px;">
                Đặt lại mật khẩu
            </a>
        </p>

        <p>Nếu nút không hoạt động, bạn có thể sao chép và dán liên kết sau vào trình duyệt của mình:</p>
        <p><a href="{{ $resetUrl }}" style="color: #3490dc;">{{ $resetUrl }}</a></p>

        <hr style="margin: 30px 0;">

        {{-- <p style="font-size: 14px; color: #777;">Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này. Liên kết sẽ hết hạn sau 60 phút.</p> --}}

        <p style="margin-top: 20px;">Trân trọng,<br><strong>Đội ngũ hỗ trợ</strong></p>
    </div>
</body>
</html>
