@extends('layout.user')

@section('content')
<style>
/* Float icon (ảnh) */
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Fade in */
@keyframes fade-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fade-in {
    animation: fade-in 0.5s ease-out;
}

/* Sticky cột thuộc tính */
.sticky-col {
    position: sticky;
    left: 0;
    background: #f9fafb;
    z-index: 1;
    box-shadow: 2px 0 5px -2px rgba(0,0,0,0.05);
}

/* Marquee */
.marquee-container {
    position: relative;
    height: 2rem;
}
.marquee {
    position: absolute;
    white-space: nowrap;
    will-change: transform;
    animation: marquee-linear 15s linear infinite;
}
@keyframes marquee-linear {
    0%   { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}
</style>

<div class="container mx-auto py-10 pt-20">
    {{-- <h1 class="text-3xl font-bold mb-8 text-gray-800">So sánh sản phẩm</h1> --}}

    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">So sánh sản phẩm</span>
    </div>

    @if($products->count() >= 2)
    <div class="overflow-auto bg-white rounded-xl shadow-xl ring-1 ring-gray-200">
        <table class="table-auto min-w-[900px] w-full text-sm text-gray-800 border-separate border-spacing-0">
            <thead class="sticky top-0 z-10 bg-blue-50 text-xs uppercase text-gray-600">
                <tr>
                    <th class="p-4 border-r sticky-col font-bold text-left bg-blue-100">Thuộc tính</th>
                    @foreach ($products as $p)
                        <th class="p-4 text-center relative group align-top min-w-[220px] bg-white">
                            <div class="flex flex-col items-center">
                                @php
                                    $img = optional($p->variants->first()?->images->first())->image_path;
                                @endphp
                                @if ($img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="{{ $p->name }}"
                                         class="h-28 object-contain rounded-xl shadow mb-2">
                                @else
                                    <div class="w-full h-28 flex items-center justify-center text-gray-400 text-sm italic bg-gray-50 rounded mb-2">
                                        Không có ảnh
                                    </div>
                                @endif

                                <div class="text-base font-semibold text-gray-800 mb-1 text-center truncate max-w-[180px]">{{ $p->name }}</div>
                            </div>

                            <button onclick="removeFromCompare({{ $p->id }})"
                                class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center bg-red-500 text-white text-sm rounded-full hover:bg-red-600 transition-all transform hover:scale-110 shadow-md"
                                title="Xoá sản phẩm">
                                &times;
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="text-sm">
                @php
                    $rows = [
                        'Giá' => fn($p) => $p->variants->pluck('price')->filter()->unique()
                            ->map(fn($v) => number_format($v, 0, ',', '.') . '₫')->implode(' / ') ?: 'N/A',
                        'Bộ nhớ' => fn($p) => $p->variants->pluck('storage')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'RAM' => fn($p) => $p->variants->pluck('ram')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'Màu sắc' => fn($p) => $p->variants->pluck('color')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'Màn hình' => fn($p) => $p->variants->pluck('screen_size')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'Trọng lượng' => fn($p) => $p->variants->pluck('weight')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'Pin' => fn($p) => $p->variants->pluck('battery')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'Chip' => fn($p) => $p->variants->pluck('chip')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'Hệ điều hành' => fn($p) => $p->variants->pluck('operating_system')->filter()->unique()->implode(' / ') ?: 'N/A',
                        'Đã bán' => fn($p) => $p->variants->sum('sold') ?: 0,
                    ];
                @endphp

                @foreach ($rows as $label => $callback)
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-700 sticky-col border-r">{{ $label }}</td>
                        @foreach ($products as $p)
                            <td class="p-4 text-center align-middle">{!! $callback($p) !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-gray-500 text-center py-12 bg-white rounded-xl shadow flex flex-col items-center space-y-5 animate-fade-in">
        <img src="https://cdn-icons-png.flaticon.com/512/747/747376.png" alt="So sánh sản phẩm"
            class="w-24 h-24 opacity-80 animate-float">

        <div class="text-lg font-semibold">
            Bạn cần chọn ít nhất <strong class="text-blue-600">2 sản phẩm</strong> để so sánh.
        </div>

        <div class="marquee-container overflow-hidden w-full max-w-md mx-auto border-t border-b border-gray-200">
            <div class="marquee whitespace-nowrap text-sm text-gray-400 py-1">
                Hãy quay lại và chọn thêm sản phẩm để trải nghiệm tính năng so sánh tuyệt vời!
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function removeFromCompare(productId) {
    const category = "{{ request()->segment(2) }}";
    const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
    const updated = (compareList[category] || []).filter(id => id !== productId);
    compareList[category] = updated;
    localStorage.setItem('compare_products', JSON.stringify(compareList));

    const newQuery = updated.map(id => 'ids[]=' + id).join('&');
    window.location.href = `/so-sanh/${category}?` + newQuery;
}
</script>
@endpush
