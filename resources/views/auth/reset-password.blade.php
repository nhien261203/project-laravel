<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="email" value="{{ $email }}">

    <input type="password" name="password" placeholder="Mật khẩu mới" required>
    <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
    <button type="submit">Đặt lại mật khẩu</button>
</form>
