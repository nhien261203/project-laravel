<div class="my-10 bg-white rounded-2xl shadow-md p-6">
    <h2 class="text-xl font-semibold mb-4">Danh mục nổi bật</h2>

    {{-- Desktop --}}
    <div class="hidden md:flex flex-wrap justify-center gap-6">
        @foreach($categories as $category)
            {{-- Cha --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
                <a href="{{ $category->route_url }}" class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="w-24 h-24 md:w-28 md:h-28 flex items-center justify-center mb-2">
                        <img src="{{ $category->logo ? asset('storage/' . $category->logo) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                             alt="{{ $category->name }}" 
                             class="object-contain w-full h-full transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
                    </div>
                    <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">{{ $category->name }}</p>
                </a>
            </div>

            {{-- Con --}}
            @if($category->children && $category->children->count())
                @foreach($category->children as $child)
                    <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
                        <a href="{{ $child->route_url }}" class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            <div class="w-24 h-24 md:w-28 md:h-28 flex items-center justify-center mb-2">
                                <img src="{{ $child->logo ? asset('storage/' . $child->logo) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                                     alt="{{ $child->name }}" 
                                     class="object-contain w-full h-full transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
                            </div>
                            <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">{{ $child->name }}</p>
                        </a>
                    </div>
                @endforeach
            @endif
        @endforeach

        {{-- Phụ kiện --}}
        @if($accessory)
            {{-- Cha --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
                <a href="{{ $accessory->route_url }}" class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <div class="w-24 h-24 md:w-28 md:h-28 flex items-center justify-center mb-2">
                        <img src="{{ $accessory->logo ? asset('storage/' . $accessory->logo) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                             alt="{{ $accessory->name }}" 
                             class="object-contain w-full h-full transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
                    </div>
                    <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">{{ $accessory->name }}</p>
                </a>
            </div>

            {{-- Con --}}
            @if($accessory->children && $accessory->children->count())
                @foreach($accessory->children as $child)
                    <div class="flex-1 flex flex-col items-center justify-center text-center cursor-pointer">
                        <a href="{{ $child->route_url }}" class="group block p-4 rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-lg">
                            <div class="w-24 h-24 md:w-28 md:h-28 flex items-center justify-center mb-2">
                                <img src="{{ $child->logo ? asset('storage/' . $child->logo) : 'https://via.placeholder.com/100x100?text=No+Image' }}" 
                                     alt="{{ $child->name }}" 
                                     class="object-contain w-full h-full transition-transform duration-300 group-hover:rotate-3 group-hover:scale-110">
                            </div>
                            <p class="text-sm font-medium transition-colors duration-300 group-hover:text-blue-600">{{ $child->name }}</p>
                        </a>
                    </div>
                @endforeach
            @endif
        @endif
    </div>

    {{-- Mobile --}}
    <div class="md:hidden">
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach($categories as $category)
                    {{-- Cha --}}
                    <div class="swiper-slide flex flex-col items-center justify-center text-center cursor-pointer">
                        <a href="{{ $category->route_url }}" class="flex flex-col items-center">
                            <div class="w-20 h-20 flex items-center justify-center mb-1">
                                <img src="{{ $category->logo ? asset('storage/' . $category->logo) : 'https://via.placeholder.com/80x80?text=No+Image' }}" 
                                     alt="{{ $category->name }}" 
                                     class="object-contain w-full h-full">
                            </div>
                            <p class="text-sm font-medium">{{ $category->name }}</p>
                        </a>
                    </div>

                    {{-- Con --}}
                    @if($category->children && $category->children->count())
                        @foreach($category->children as $child)
                            <div class="swiper-slide flex flex-col items-center justify-center text-center cursor-pointer">
                                <a href="{{ $child->route_url }}" class="flex flex-col items-center">
                                    <div class="w-20 h-20 flex items-center justify-center mb-1">
                                        <img src="{{ $child->logo ? asset('storage/' . $child->logo) : 'https://via.placeholder.com/80x80?text=No+Image' }}" 
                                             alt="{{ $child->name }}" 
                                             class="object-contain w-full h-full">
                                    </div>
                                    <p class="text-sm font-medium">{{ $child->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    @endif
                @endforeach

                {{-- Phụ kiện --}}
                @if($accessory)
                    {{-- Cha --}}
                    <div class="swiper-slide flex flex-col items-center justify-center text-center cursor-pointer">
                        <a href="{{ $accessory->route_url }}" class="flex flex-col items-center">
                            <div class="w-20 h-20 flex items-center justify-center mb-1">
                                <img src="{{ $accessory->logo ? asset('storage/' . $accessory->logo) : 'https://via.placeholder.com/80x80?text=No+Image' }}" 
                                     alt="{{ $accessory->name }}" 
                                     class="object-contain w-full h-full">
                            </div>
                            <p class="text-sm font-medium">{{ $accessory->name }}</p>
                        </a>
                    </div>

                    {{-- Con --}}
                    @if($accessory->children && $accessory->children->count())
                        @foreach($accessory->children as $child)
                            <div class="swiper-slide flex flex-col items-center justify-center text-center cursor-pointer">
                                <a href="{{ $child->route_url }}" class="flex flex-col items-center">
                                    <div class="w-20 h-20 flex items-center justify-center mb-1">
                                        <img src="{{ $child->logo ? asset('storage/' . $child->logo) : 'https://via.placeholder.com/80x80?text=No+Image' }}" 
                                             alt="{{ $child->name }}" 
                                             class="object-contain w-full h-full">
                                    </div>
                                    <p class="text-sm font-medium">{{ $child->name }}</p>
                                </a>
                            </div>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
