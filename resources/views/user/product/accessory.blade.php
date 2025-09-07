@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10 overflow-x-hidden">
    <div class="flex items-center text-sm text-gray-600 space-x-2 p-1">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        
        {{-- Breadcrumb cho danh mục cha nếu có --}}
        @if ($parentCategory)
            <a href="{{ route('product.category', $parentCategory->slug) }}" class="hover:text-blue-600">
                {{ $parentCategory->name }}
            </a>
            <span class="text-gray-400">›</span>
        @endif
        
        {{-- Breadcrumb cho danh mục hiện tại --}}
        <span class="text-gray-800 font-medium">{{ $category->name }}</span>
    </div>

    <div id="loadingOverlay"
        class="fixed inset-0 bg-black/30 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="bg-white p-4 rounded shadow flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            {{-- <span>Đang tải...</span> --}}
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow mb-8">
        <form method="GET" id="filterForm"></form>

        <div class="grid grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-6 items-start">
            {{-- Thương hiệu --}}
            @if ($brands->count())
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Thương hiệu</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($brands as $brand)
                            <button type="button"
                                class="btn-filter px-3 py-2 rounded-full border text-sm flex items-center gap-2 transition
                                    {{ in_array($brand->id, request('brand_ids', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                                data-name="brand_ids[]"
                                data-value="{{ $brand->id }}">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-12 h-4 object-contain">
                                @else
                                    {{ $brand->name }}
                                @endif
                            </button>
                        @endforeach

                    </div>
                </div>
            @endif

            {{-- Lọc theo giá --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-3">Lọc theo giá</h3>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach(['under_1' => 'Dưới 1 triệu', 'from_1_to_5' => '1 – 5 triệu', 'over_5' => 'Trên 5 triệu'] as $key => $label)
                        <button type="button"
                            class="btn-filter px-3 py-2 rounded-full border transition
                                {{ in_array($key, request('price_ranges', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                            data-name="price_ranges[]"
                            data-value="{{ $key }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Sắp xếp --}}
            {{-- <div>
                <h3 class="text-base font-semibold text-gray-800 mb-3">Sắp xếp</h3>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach(['price_asc' => 'Giá thấp → cao', 'price_desc' => 'Giá cao → thấp'] as $sortValue => $label)
                        <button type="button"
                            class="btn-filter px-3 py-2 rounded-full border transition
                                {{ request('sort') === $sortValue ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                            data-name="sort"
                            data-value="{{ $sortValue }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </div>
    <div id="accessory-list" >
        @include('components.accessory_list', ['accessories' => $accessories])
    </div>
    


    
</div>
@endsection
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const accessoryList = document.getElementById('accessory-list');

    async function loadAccessories(url) {
        try {
            const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await res.text();
            accessoryList.innerHTML = html;
            window.history.pushState({}, '', url);
            // window.scrollTo({
            //     top: 0,
            //     behavior: 'smooth'
            // });
        } catch (err) {
            console.error(err);
        }
    }

    // Filter click
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-filter');
        if (!btn) return;

        const params = new URLSearchParams(window.location.search);
        const name = btn.dataset.name;
        const value = btn.dataset.value;

        if (name.endsWith('[]')) {
            const values = params.getAll(name);
            params.delete(name);

            if (values.includes(value)) {
                values.filter(v => v !== value).forEach(v => params.append(name, v));
                btn.classList.remove('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
                btn.classList.add('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
            } else {
                values.push(value);
                values.forEach(v => params.append(name, v));
                btn.classList.remove('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
                btn.classList.add('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
            }
        } else {
            if (params.get(name) === value) {
                params.delete(name);
                btn.classList.remove('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
                btn.classList.add('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
            } else {
                document.querySelectorAll(`.btn-filter[data-name="${name}"]`).forEach(b => {
                    b.classList.remove('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
                    b.classList.add('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
                });
                params.set(name, value);
                btn.classList.remove('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
                btn.classList.add('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
            }
        }

        params.delete('page'); // reset page
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        loadAccessories(newUrl);
    });

    // Pagination click
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.pagination a');
        if (!link) return;
        e.preventDefault();
        loadAccessories(link.href);
    });
});

</script>
@endpush

