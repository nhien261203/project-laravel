@extends('layout.admin')
@section('title', 'Liên hệ')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Danh sách liên hệ</h1>

    <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex flex-col md:flex-row items-center gap-4 mb-6">
    <input type="text" name="keyword" placeholder="Tìm tên, email, nội dung..." value="{{ request('keyword') }}"
           class="border rounded px-3 py-1">

    <select name="is_replied" class="border rounded px-3 py-1">
        <option value="">-- Tất cả --</option>
        <option value="1" {{ request('is_replied') == '1' ? 'selected' : '' }}>Đã phản hồi</option>
        <option value="0" {{ request('is_replied') == '0' ? 'selected' : '' }}>Chưa phản hồi</option>
    </select>

    <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Lọc</button>
    <a href="{{ route('admin.contacts.index') }}" class="text-gray-600 underline px-2">Reset</a>
</form>


    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3 border-b">#</th>
                    <th class="p-3 border-b">Họ tên</th>
                    <th class="p-3 border-b">Email</th>
                    <th class="p-3 border-b">Ngày gửi</th>
                    <th class="p-3 border-b">Trạng thái</th>
                    <th class="p-3 border-b">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $index => $contact)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">{{ ($contacts->currentPage() - 1) * $contacts->perPage() + $index + 1 }}</td>
                        <td class="p-3">{{ $contact->name }}</td>
                        <td class="p-3">{{ $contact->email }}</td>
                        <td class="p-3">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3">
                            @if ($contact->is_replied)
                                <span class="text-green-600 font-medium">Đã phản hồi</span>
                            @else
                                <span class="text-red-600 font-medium">Chưa phản hồi</span>
                            @endif
                        </td>
                        <td class="p-3 flex space-x-2">
                            <a href="{{ route('admin.contacts.show', $contact->id) }}" class="text-blue-600 hover:underline">Xem</a>

                            <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" onsubmit="return confirm('Xoá liên hệ này?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-center">Không có liên hệ nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contacts->links('pagination.custom-tailwind') }}
    </div>
</div>
@endsection
