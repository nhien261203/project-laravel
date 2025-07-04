@extends('layout.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">➕ Tạo bài viết mới</h2>

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
            <label class="font-semibold">Nội dung</label>
            <textarea name="content" id="summernote">{{ old('content') }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">
            Đăng bài
        </button>
    </form>
</div>
@endsection

@push('scripts')
<!-- Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function () {
        $('#summernote').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function (files) {
                    uploadImage(files[0]);
                }
            }
        });

        function uploadImage(file) {
            let data = new FormData();
            data.append("file", file);
            data.append("_token", '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route("admin.blogs.upload") }}',
                type: 'POST',
                data: data,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $('#summernote').summernote('insertImage', resp.url);
                },
                error: function () {
                    alert('Tải ảnh thất bại!');
                }
            });
        }
    });
</script>
@endpush
