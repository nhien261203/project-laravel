<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <input type="email" name="email" placeholder="Nhập email của bạn" required>
    <button type="submit">Gửi liên kết đặt lại mật khẩu</button>
</form>
