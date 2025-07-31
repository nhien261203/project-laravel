@extends('layout.admin')
@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Chi tiết liên hệ</h1>

    <div class="space-y-3 text-gray-700">
        <p><strong>Họ tên:</strong> {{ $contact->name }}</p>
        <p><strong>Email:</strong> {{ $contact->email }}</p>
        @if ($contact->phone)
            <p><strong>Số điện thoại:</strong> {{ $contact->phone }}</p>
        @endif
        <p><strong>Thời gian gửi:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>

        <div>
            <strong>Nội dung:</strong>
            <div class="bg-gray-50 p-4 mt-1 rounded border text-sm whitespace-pre-line">{{ $contact->message }}</div>
        </div>

        <p>
            <strong>Trạng thái:</strong>
            @if ($contact->is_replied)
                <span class="text-green-600 font-semibold">Đã phản hồi</span>
            @else
                <span class="text-red-600 font-semibold">Chưa phản hồi</span>
            @endif
        </p>

        @unless ($contact->is_replied)
        <form method="POST" action="{{ route('admin.contacts.markReplied', $contact->id) }}">
            @csrf @method('PATCH')
            <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Đánh dấu đã phản hồi</button>
        </form>
        @endunless

        <div class="mt-6">
            <a href="{{ route('admin.contacts.index') }}" class="text-blue-600 hover:underline">← Quay lại danh sách</a>
        </div>
    </div>
</div>
@endsection
