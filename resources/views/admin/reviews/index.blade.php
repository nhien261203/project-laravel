@extends('layout.admin')

@section('content')

    <div class="p-6 bg-white rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.comments.index') }}" class="text-2xl font-bold mb-4">Quản lý bình luận blog</a>
            <a href="{{ route('admin.reviews.index') }}" class="text-2xl font-bold mb-4">Quản lý bình đánh giá sản phẩm</a>
        </div>
        <h2 class="text-xl font-bold mb-4">Đánh giá đang chờ duyệt</h2>

        @forelse ($pendingReviews as $review)
            <div class="border-b py-3">
                <p><strong>{{ $review->user->name }}</strong> đánh giá <strong>{{ $review->product->name }}</strong></p>
                <p>
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                </p>
                <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>

                <div class="mt-2 flex gap-2">
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded text-sm">Duyệt</button>
                    </form>
                    <form action="{{ route('admin.reviews.reject', $review) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm">Từ chối</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Không có đánh giá chờ duyệt.</p>
        @endforelse

        <h2 class="text-xl font-bold mt-8 mb-4">Đánh giá đã duyệt</h2>

        @forelse ($approvedReviews as $review)
            <div class="border-b py-3">
                <p><strong>{{ $review->user->name }}</strong> đánh giá <strong>{{ $review->product->name }}</strong></p>
                <p>
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                </p>
                <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
            </div>
        @empty
            <p class="text-gray-500">Không có đánh giá đã duyệt.</p>
        @endforelse

        <h2 class="text-xl font-bold mt-8 mb-4">Đánh giá bị từ chối</h2>

        @forelse ($rejectedReviews as $review)
            <div class="border-b py-3">
                <p><strong>{{ $review->user->name }}</strong> đánh giá <strong>{{ $review->product->name }}</strong></p>
                <p>
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                    @endfor
                </p>
                <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
            </div>
        @empty
            <p class="text-gray-500">Không có đánh giá bị từ chối.</p>
        @endforelse
    </div>
@endsection
