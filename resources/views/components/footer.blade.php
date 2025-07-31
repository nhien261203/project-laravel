<footer class="bg-[#515154] text-white pt-10 pb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Logo và mô tả --}}
        <div class="flex flex-col md:flex-row md:justify-between gap-8">
            <div class="md:w-1/3">
                <h2 class="text-2xl font-bold">Nexus shop</h2>
                <p class="mt-3 text-gray-400">
                    Giải pháp mua sắm thiết bị điện tử tiện lợi, hiện đại và tiết kiệm thời gian.
                </p>
            </div>

            {{-- Các cột liên kết --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 w-full">
                {{-- Thông tin --}}
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Thông tin</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white">Về chúng tôi</a></li>
                        <li><a href="{{ route('blogs.index') }}" class="text-gray-400 hover:text-white">New feed</a></li>
                        {{-- <li><a href="#" class="text-gray-400 hover:text-white">Phương thức thanh toán</a></li>
                        <li><a href="{{ route('user.orders.index') }}" class="text-gray-400 hover:text-white">Tra cứu đơn hàng</a></li> --}}
                        <li><a href="{{ route('contact.show') }}" class="text-gray-400 hover:text-white">Liên hệ</a></li>
                    </ul>
                </div>

                {{-- Chính sách --}}
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Chính sách</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Chính sách giao hàng</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Bảo mật thông tin</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Đổi trả</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Hướng dẫn thanh toán qua VNPay</a></li>
                    </ul>
                </div>

                {{-- Thông tin khác --}}
                <div>
                    <h3 class="text-sm font-semibold text-white uppercase tracking-wider">Thông tin khác</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white">Voucher - Khuyễn mãi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Nội quy cửa hàng</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Câu hỏi thường gặp</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white">Lịch sử mua hàng</a></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Đường kẻ và bản quyền --}}
        <div class="mt-10 border-t border-gray-700 pt-6 text-center text-sm text-gray-400">
            © 2025 YourCompany. All rights reserved.
        </div>
    </div>
</footer>
