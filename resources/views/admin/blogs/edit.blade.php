@extends('layout.admin')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Chỉnh sửa bài viết</h2>

    <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="font-semibold">Tiêu đề</label>
            <input type="text" name="title" value="{{ old('title', $blog->title) }}" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $blog->slug) }}" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring focus:ring-blue-100">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Tags -->
            <div>
                <label for="tags" class="block text-sm font-semibold text-gray-700 mb-1">🏷️ Tags</label>
                <select name="tags[]" id="tags" multiple
                    class="w-full h-48 px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring focus:ring-blue-100 text-sm overflow-y-auto">
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}" {{ $blog->tags->contains($tag->id) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    Giữ <kbd class="px-1 py-0.5 bg-gray-100 border rounded text-gray-700">Ctrl</kbd> (hoặc <kbd>Cmd</kbd> trên Mac) để chọn nhiều thẻ.
                </p>
            </div>

            <!-- Trạng thái -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">📢 Trạng thái</label>
                <div class="flex items-center gap-6 mt-2">
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="radio" name="status" value="0" {{ $blog->status == 0 ? 'checked' : '' }}
                            class="text-blue-600 focus:ring focus:ring-blue-200 mr-2">
                        Nháp
                    </label>
                    <label class="inline-flex items-center text-sm text-gray-700">
                        <input type="radio" name="status" value="1" {{ $blog->status == 1 ? 'checked' : '' }}
                            class="text-blue-600 focus:ring focus:ring-blue-200 mr-2">
                        Công khai
                    </label>
                </div>
            </div>
        </div>


        <div class="mb-4">
            <label class="font-semibold">Nội dung</label>
            <textarea name="content" id="summernote">{{ old('content', $blog->content) }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow">Cập nhật bài viết</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
            callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0]);
                }
            }
        });

        function uploadImage(file) {
            let data = new FormData();
            data.append("file", file);
            data.append("_token", '{{ csrf_token() }}');

            $.ajax({
                url: "{{ route('admin.blogs.upload') }}",
                method: "POST",
                data: data,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $('#summernote').summernote('insertImage', resp.url);
                },
                error: function () {
                    alert("Upload ảnh thất bại");
                }
            });
        }
    });
</script>
@endpush
