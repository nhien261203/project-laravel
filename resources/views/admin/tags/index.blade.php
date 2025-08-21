@extends('layout.admin')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Danh sách Tags</h2>

    <a href="{{ route('admin.tags.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Thêm Tag</a>

    {{-- @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 my-3 rounded">
            {{ session('success') }}
        </div>
    @endif --}}

    <table class="w-full mt-4 border">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Tên Tag</th>
                <th class="border px-4 py-2">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tags as $tag)
                <tr>
                    <td class="border px-4 py-2">{{ $tag->id }}</td>
                    <td class="border px-4 py-2">{{ $tag->name }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="bg-yellow-400 text-white px-2 py-1 rounded">Sửa</a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $tags->links() }}
    </div>
</div>
@endsection
