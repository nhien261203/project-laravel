@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between mt-6">
        {{-- Kết quả hiện tại --}}
        <div class="text-sm text-gray-600">
            Hiển thị
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            đến
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            trong
            <span class="font-medium">{{ $paginator->total() }}</span>
            kết quả
        </div>

        {{-- Nút phân trang --}}
        <div class="flex items-center space-x-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 rounded border text-gray-400">«</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1 rounded border hover:bg-gray-100 transition">«</a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-1 text-gray-500">...</span>
                @endif

                {{-- Array of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="px-3 py-1 rounded bg-blue-600 text-white font-semibold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                                class="px-3 py-1 rounded border hover:bg-gray-100 transition">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1 rounded border hover:bg-gray-100 transition">»</a>
            @else
                <span class="px-3 py-1 rounded border text-gray-400">»</span>
            @endif
        </div>
    </nav>
@endif
