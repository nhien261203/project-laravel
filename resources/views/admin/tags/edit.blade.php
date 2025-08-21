@extends('layout.admin')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Sửa Tag</h2>

    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-2">Tên Tag</label>
            <input type="text" name="name" class="border p-2 w-full" value="{{ old('name', $tag->name) }}">
            @error('name') <p class="text-red-500">{{ $message }}</p> @enderror
        </div>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Cập nhật</button>
    </form>
</div>
@endsection
