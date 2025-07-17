@extends('layout.admin')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-6xl mx-auto bg-white rounded shadow-md p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Danh sách người dùng</h2>

        <table class="min-w-full table-auto text-sm text-left border border-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Tên</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Quyền</th>
                    <th class="px-4 py-2 border text-center" colspan="3">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $user->id }}</td>
                    <td class="px-4 py-2 border">{{ $user->name }}</td>
                    <td class="px-4 py-2 border">{{ $user->email }}</td>
                    <td class="px-4 py-2 border">{{ $user->roles->pluck('name')->implode(', ') }}</td>
                    <td class="px-4 py-2 border text-center">
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            Sửa
                        </a>
                    </td>
                    <td class="px-4 py-2 border text-center">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-green-600 hover:text-green-800 font-medium">
                            Xem
                        </a>
                    </td>
                    <td class="border p-3">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xoá người dùng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Xoá</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Không có người dùng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $users->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
