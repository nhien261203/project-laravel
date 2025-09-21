@extends('layout.admin')

@section('content')
<div class="p-4">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Danh sách người dùng</h2>
        @role('admin')
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
            Thêm người dùng mới
        </a>
        @endrole
    </div>

    {{-- Form lọc --}}
    <form method="GET" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            {{-- Ô tìm kiếm --}}
            <div>
                <label for="keyword" class="block text-sm font-medium text-gray-700 mb-1">Tìm kiếm</label>
                <input 
                    type="text" 
                    name="keyword" 
                    id="keyword"
                    value="{{ request('keyword') }}" 
                    placeholder="🔍 Tên hoặc email..." 
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
            </div>

            {{-- Dropdown quyền --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Quyền</label>
                <select 
                    name="role" 
                    id="role"
                    class="w-full px-4 py-2 border rounded bg-white focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                    <option value="">-- Tất cả quyền --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nút lọc --}}
            <div class="flex flex-col sm:flex-row gap-2">
                {{-- Nút lọc --}}
                <button 
                    type="submit" 
                    class="w-full sm:w-auto px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                >
                    Lọc
                </button>

                {{-- Nút reset --}}
                <a 
                    href="{{ route('admin.users.index') }}" 
                    class="w-full sm:w-auto text-center px-6 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition"
                >
                    Reset
                </a>
            </div>

        </div>
    </form>

    {{-- Bảng danh sách --}}
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">STT</th>
                    <th class="border p-2">Tên</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Quyền</th>
                    <th class="border p-2">Trạng thái</th>
                    <th class="border p-2">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td class="border p-2">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                    
                    <td class="border p-2">{{ $user->name }}</td>
                    <td class="border p-2">{{ $user->email }}</td>
                    <td class="border p-2">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                    <td class="border p-2 ">
                        @if ($user->active)
                            <span class="text-green-600 font-medium">Hoạt động</span>
                        @else
                            <span class="text-red-600 font-medium">Vô hiệu hóa</span>
                        @endif
                    </td>
                    <td class="border p-2 space-x-2 ">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-500 hover:underline">xem</a>

                        @if (auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-green-500 hover:underline">sửa</a>

                            {{-- <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận xóa?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Xóa</button>
                            </form> --}}
                            {{-- Nút toggle bật/tắt trạng thái (nếu không phải admin) --}}
                            {{-- @if (!$user->hasRole('admin'))
                                <form action="{{ route('admin.users.toggle-active', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Xác nhận thay đổi trạng thái?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-sm {{ $user->active ? 'text-red-500' : 'text-green-500' }} hover:underline">
                                        {{ $user->active ? 'Vô hiệu hóa' : 'Kích hoạt' }}
                                    </button>
                                </form>
                            @endif --}}

                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4">Không có người dùng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-4">
        {{ $users->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
