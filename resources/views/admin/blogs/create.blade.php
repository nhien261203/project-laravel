@extends('layout.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">🌟 Tạo bài viết mới</h2>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Tiêu đề</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full mt-1 border-gray-300 rounded" required>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Slug (bỏ trống để tự tạo)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full mt-1 border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Tags</label>
            <select name="tags[]" multiple class="w-full mt-1 border-gray-300 rounded">
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ collect(old('tags'))->contains($tag->id) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Trạng thái</label>
            <div class="flex gap-4 mt-2">
                <label><input type="radio" name="status" value="0" {{ old('status', 0) == 0 ? 'checked' : '' }}> Nháp</label>
                <label><input type="radio" name="status" value="1" {{ old('status') == 1 ? 'checked' : '' }}> Công khai</label>
            </div>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Ảnh đại diện</label>
            <input type="file" name="thumbnail" class="mt-1">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Nội dung</label>
            <textarea name="content" id="editor" class="hidden">{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">Đăng bài</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor.create(document.querySelector('#editor'), {
        simpleUpload: {
            uploadUrl: '{{ route("admin.blogs.upload") }}',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }
    }).catch(error => console.error(error));
</script>
@endpush
