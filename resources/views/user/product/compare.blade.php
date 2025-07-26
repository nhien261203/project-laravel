@extends('layout.user')

@section('content')
<div class="container mx-auto py-10 pt-20">
    <h1 class="text-3xl font-bold mb-8 text-gray-800">🔍 So sánh sản phẩm</h1>

    @if($products->count() >= 2)
    <div class="overflow-x-auto bg-white shadow-xl rounded-xl ring-1 ring-gray-200">
        <table class="table-auto min-w-[800px] w-full text-sm text-gray-700 border-separate border-spacing-0">
            <thead class="sticky top-0 z-10 bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="p-4 border-r font-bold text-left bg-gray-200">Thuộc tính</th>
                    @foreach ($products as $p)
                        <th class="p-4 border-r text-center bg-white relative group align-top min-w-[200px]">
                            <div class="text-base font-semibold text-gray-800 mb-2">{{ $p->name }}</div>
                            <button onclick="removeFromCompare({{ $p->id }})"
                                class="absolute top-2 right-2 w-6 h-6 flex items-center justify-center bg-red-500 text-white text-sm rounded-full hover:bg-red-600 transition group-hover:scale-110">
                                &times;
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="text-sm">
                @php
                    $rows = [
                        'Ảnh' => fn($p) => optional($p->variants->first()?->images->first())->image_path
                            ? '<img src="' . asset('storage/' . optional($p->variants->first()?->images->first())->image_path) . '" class="h-28 mx-auto object-contain rounded shadow-md" />'
                            : '<div class="text-gray-400 italic">Không có ảnh</div>',

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
                    <tr class="border-t">
                        <td class="p-4 bg-gray-50 font-medium text-gray-700 border-r whitespace-nowrap">{{ $label }}</td>
                        @foreach ($products as $p)
                            <td class="p-4 text-center">{!! $callback($p) !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="text-gray-500 text-center py-8 bg-white rounded-lg shadow">
            Bạn cần chọn ít nhất <strong>2 sản phẩm</strong> để so sánh.
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
