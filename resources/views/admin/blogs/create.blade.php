@extends('layout.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">➕ Tạo bài viết mới</h2>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="font-semibold">Tiêu đề</label>
            <input type="text" name="title" value="{{ old('title') }}" 
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Slug (bỏ trống để tự tạo)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" 
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
        </div>



        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <!-- Tags -->
            <div>
                <label for="tags" class="block text-md font-semibold text-gray-700 mb-1">🏷️ Tags</label>
                <select name="tags[]" id="tags" multiple
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-100 bg-white h-48 overflow-y-auto text-sm">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ collect(old('tags', $selectedTags ?? []))->contains($tag->id) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    Giữ <kbd class="px-1 py-0.5 bg-gray-100 border rounded text-gray-700">Ctrl</kbd> (hoặc <kbd>Cmd</kbd> trên Mac) để chọn nhiều thẻ.
                </p>
                @error('tags')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Trạng thái -->
            <div>
                <label class="block text-md font-semibold text-gray-700 mb-1">📢 Trạng thái</label>
                <div class="flex items-center gap-6 mt-2">
                    <label class="inline-flex items-center text-sm">
                        <input type="radio" name="status" value="0" {{ old('status', 0) == 0 ? 'checked' : '' }}
                            class="text-blue-600 focus:ring focus:ring-blue-200 mr-2">
                        Nháp
                    </label>
                    <label class="inline-flex items-center text-sm">
                        <input type="radio" name="status" value="1" {{ old('status') == 1 ? 'checked' : '' }}
                            class="text-blue-600 focus:ring focus:ring-blue-200 mr-2">
                        Công khai
                    </label>
                </div>
            </div>
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

        <div class="mb-4">
            <label class="font-semibold block mb-1">Ảnh đại diện (thumbnail)</label>
            <input type="file" name="thumbnail" accept="image/*"
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                    file:rounded file:border-0 file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />

            @error('thumbnail')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror
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
