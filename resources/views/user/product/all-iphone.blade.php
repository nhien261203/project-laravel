@extends('layout.user')

@section('content')
<div class="container mx-auto pt-20 pb-10 overflow-x-hidden">
    {{-- Breadcrumb --}}
    <div class="flex items-center text-sm text-gray-600 space-x-2 mb-4">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a>
        <span class="text-gray-400">›</span>
        <span class="text-gray-800 font-medium">iPhone</span>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            
            @if ($rams->count())
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-3">RAM</h3>
                    <div class="flex flex-wrap gap-2 text-sm">
                        @foreach($rams as $ram)
                            <button type="button"
                                class="btn-filter px-3 py-2 rounded-full border transition
                                    {{ in_array($ram, request('rams', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                                data-name="rams[]"
                                data-value="{{ $ram }}">
                                {{ $ram }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Bộ nhớ (Storage) --}}
            @if ($storages->count())
                <div>
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Bộ nhớ</h3>
                    <div class="flex flex-wrap gap-2 text-sm">
                        @foreach($storages as $storage)
                            <button type="button"
                                class="btn-filter px-3 py-2 rounded-full border transition
                                    {{ in_array($storage, request('storages', [])) ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-100' }}"
                                data-name="storages[]"
                                data-value="{{ $storage }}">
                                {{ $storage }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Lọc theo giá --}}
            <div>
                <h3 class="text-base font-semibold text-gray-800 mb-3">Lọc theo giá</h3>
                <div class="flex flex-wrap gap-2 text-sm">
                    @foreach([ 'from_10_to_20' => '10 – 20 triệu', 'over_20' => 'Trên 20 triệu'] as $key => $label)
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
        </div>
    </div>

    {{-- Danh sách sản phẩm iPhone --}}
    <div id="productListContainer" class="bg-white p-6 rounded-xl shadow space-y-6 overflow-hidden">
        @include('components.Iphone_list', ['products' => $iphoneProducts])
    </div>

    <button onclick="goToComparePage('{{ request()->segment(1) }}')" 
            class="fixed bottom-[165px] right-5 px-4 py-2 bg-blue-600 text-white rounded shadow-lg z-50 hidden">
        So sánh (<span id="compareCount">0</span>)
    </button>
</div>
@endsection

@push('scripts')
<script>
    function updateCompareCount() {
        const category = "{{ request()->segment(1) }}";
        const compareList = JSON.parse(localStorage.getItem('compare_products')) || {};
        const selected = compareList[category] || [];
        const countEl = document.getElementById('compareCount');
        const btnCompare = document.querySelector('[onclick^="goToComparePage"]');

        if (countEl) countEl.textContent = selected.length;

        // Ẩn hoặc hiện nút "So sánh"
        if (btnCompare) {
            btnCompare.style.display = selected.length > 0 ? 'block' : 'none';
        }
    }

    function addToCompare(productId, categorySlug) {
        const compare = JSON.parse(localStorage.getItem("compare_products")) || {};
        compare[categorySlug] = compare[categorySlug] || [];

        if (compare[categorySlug].includes(productId)) {
            Swal.fire({
                icon: 'info',
                title: 'Đã có trong danh sách so sánh',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        if (compare[categorySlug].length >= 4) {
            Swal.fire({
                icon: 'warning',
                title: 'Chỉ được so sánh tối đa 4 sản phẩm',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        compare[categorySlug].push(productId);
        localStorage.setItem("compare_products", JSON.stringify(compare));
        updateCompareCount();

        Swal.fire({
            icon: 'success',
            title: 'Đã thêm vào danh sách so sánh!',
            toast: true,
            position: 'top-end',
            timer: 2000,
            showConfirmButton: false
        });
    }

    function goToComparePage(categorySlug) {
        const compare = JSON.parse(localStorage.getItem("compare_products")) || {};
        const ids = compare[categorySlug] || [];

        if (ids.length < 2) {
            Swal.fire({
                icon: 'warning',
                title: 'Bạn cần chọn ít nhất 2 sản phẩm để so sánh.',
                toast: true,
                position: 'top-end',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        const query = ids.map(id => `ids[]=${id}`).join('&');
        window.location.href = `/so-sanh/${categorySlug}?${query}`;
    }


</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    updateCompareCount();
    const productContainer = document.getElementById('productListContainer');

    // Hàm fetch sản phẩm theo URL
    async function fetchProducts(url) {
        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            productContainer.innerHTML = html;

            // Cập nhật URL mà không reload
            window.history.pushState({}, "", url);


        } catch (error) {
            console.error('Lỗi khi load sản phẩm:', error);
        }
    }

    // Event delegation cho filter buttons
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-filter');
        if (!btn) return;

        e.preventDefault();

        const params = new URLSearchParams(window.location.search);
        const name = btn.dataset.name;
        const value = btn.dataset.value;

        if (name.endsWith('[]')) {
            // Multi-select
            let values = params.getAll(name);
            params.delete(name);

            if (values.includes(value)) {
                // bỏ chọn
                values = values.filter(v => v !== value);
                values.forEach(v => params.append(name, v));
                btn.classList.remove('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
                btn.classList.add('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
            } else {
                // chọn
                values.push(value);
                values.forEach(v => params.append(name, v));
                btn.classList.remove('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
                btn.classList.add('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
            }
        } else {
            // Single-select
            if (params.get(name) === value) {
                params.delete(name);
                btn.classList.remove('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
                btn.classList.add('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
            } else {
                // Reset tất cả nút cùng name
                document.querySelectorAll(`.btn-filter[data-name="${name}"]`).forEach(b => {
                    b.classList.remove('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
                    b.classList.add('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
                });
                params.set(name, value);
                btn.classList.remove('bg-white','border-gray-300','text-gray-700','hover:bg-gray-100');
                btn.classList.add('bg-blue-500','text-white','border-blue-500','hover:bg-blue-600');
            }
        }

        // Reset page về 1 khi filter
        params.delete('page');

        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        fetchProducts(newUrl);
    });

    // Event delegation cho pagination
    document.addEventListener('click', function (e) {
        const link = e.target.closest('#productListContainer .pagination a');
        if (!link) return;

        e.preventDefault();
        fetchProducts(link.href);
    });

    // Handle back/forward browser button
    window.addEventListener('popstate', function () {
        fetchProducts(window.location.href);
    });
});
</script>
@endpush


