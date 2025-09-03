@extends('layout.admin')
@section('title', 'Liên hệ')

@section('content')
<div class="p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4">Danh sách liên hệ</h1>

    {{-- Form lọc --}}
    <form method="GET" action="{{ route('admin.contacts.index') }}" class="flex flex-col md:flex-row items-center gap-4 mb-6">
        <input type="text" name="keyword" placeholder="Tìm tên, email, nội dung..." value="{{ request('keyword') }}"
               class="border rounded px-3 py-1 w-full md:w-auto">

        <select name="is_replied" class="border rounded px-3 py-1">
            <option value="">-- Tất cả --</option>
            <option value="1" {{ request('is_replied') == '1' ? 'selected' : '' }}>Đã phản hồi</option>
            <option value="0" {{ request('is_replied') == '0' ? 'selected' : '' }}>Chưa phản hồi</option>
        </select>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Lọc</button>
        <a href="{{ route('admin.contacts.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">Reset</a>
    </form>

    {{-- Flash message --}}
    {{-- @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif --}}

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3 border-b">#</th>
                    <th class="p-3 border-b">Họ tên</th>
                    <th class="p-3 border-b">Email</th>
                    <th class="p-3 border-b">Ngày gửi</th>
                    <th class="p-3 border-b">Nội dung</th>
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

                        {{-- Message với tooltip --}}
                        {{-- <td class="p-3 max-w-xs truncate" title="{{ $contact->message }}">
                            {{ Str::limit($contact->message, 50) }}
                        </td> --}}
                        <td class="px-4 py-2 border text-gray-700 relative group">
                            <span class="cursor-pointer">
                                {{ Str::limit($contact->message, 50) }}
                            </span>
                            <div class="absolute left-0 bottom-full mb-2 w-64 max-w-xs hidden group-hover:block bg-gray-800 text-white text-xs rounded p-2 shadow-lg z-10 break-words">
                                {{ $contact->message }}
                            </div>
                        </td>

                        {{-- Trạng thái --}}
                        <td class="p-3">
                            @if ($contact->is_replied)
                                <span class="text-green-600 font-medium">Đã phản hồi</span>
                            @else
                                <span class="text-red-600 font-medium">Chưa phản hồi</span>
                            @endif
                        </td>

                        {{-- Hành động --}}
                        <td class="p-3">
                            <select onchange="handleContactAction(this, {{ $contact->id }})" class="border rounded px-2 py-1">
                                <option value="">Chọn hành động</option>
                                @if (!$contact->is_replied)
                                    <option value="mark-replied">Đã phản hồi</option>
                                    <option value="delete">Xoá</option>
                                @else
                                    <option value="mark-unreplied">Đặt lại chưa phản hồi</option>
                                    <option value="delete">Xoá</option>
                                @endif
                            </select>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-4 text-center">Không có liên hệ nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $contacts->links('pagination.custom-tailwind') }}
    </div>
</div>

{{-- JS xử lý hành động --}}
<script>
function handleContactAction(select, contactId) {
    const action = select.value;
    if (!action) return;

    let url = '';
    let method = '';

    if (action === 'mark-replied') {
        url = `/admin/contacts/${contactId}/mark-replied`;
        method = 'PATCH';
    } else if (action === 'mark-unreplied') {
        url = `/admin/contacts/${contactId}/mark-unreplied`;
        method = 'PATCH';
    } else if (action === 'delete') {
        if (!confirm('Xoá liên hệ này?')) return;
        url = `/admin/contacts/${contactId}`;
        method = 'DELETE';
    }

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    }).then(res => location.reload());
}

</script>
@endsection
