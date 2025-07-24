@extends('layout.user')

@section('content')
<div class="container mx-auto py-10 pt-20">
    <h1 class="text-2xl font-bold mb-6 ">So sánh sản phẩm</h1>

    @if($products->count() >= 2)
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="table-auto w-full text-sm border-collapse border rounded overflow-hidden">
            <thead>
                <tr>
                    <th class="border p-3 bg-gray-100 text-left font-semibold">Thuộc tính</th>
                    @foreach ($products as $p)
                        <th class="border p-3 bg-white text-center relative align-top">
                            <div class="font-semibold">{{ $p->name }}</div>
                            <button onclick="removeFromCompare({{ $p->id }})"
                                    class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full hover:bg-red-600 transition">
                                &times;
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $rows = [
                        'Ảnh' => fn($p) => optional($p->variants->first()?->images->first())->image_path
                            ? '<img src="' . asset('storage/' . optional($p->variants->first()?->images->first())->image_path) . '" class="h-20 mx-auto object-contain" />'
                            : '<span class="text-gray-400">Không có ảnh</span>',

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
                    <tr>
                        <td class="border p-3 bg-gray-50 font-medium">{{ $label }}</td>
                        @foreach ($products as $p)
                            <td class="border p-3 text-center">{!! $callback($p) !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-gray-500">Bạn cần chọn ít nhất 2 sản phẩm để so sánh.</p>
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
