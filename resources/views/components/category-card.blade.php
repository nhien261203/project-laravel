{{-- <a class="block group transition-all duration-300">
    <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
        <div class="relative aspect-[4/3]">
            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                class="w-full h-full object-cover transition-transform duration-500  group-hover:scale-105"

            >

            <div class="absolute bottom-4 right-4">
                <div class="bg-white rounded-full p-2 shadow-md transition-all duration-300 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 text-gray-800"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-lg font-semibold text-gray-900 group-hover:text-black transition">
            {{ strtoupper($title) }}
        </div>
    </div>
</a> --}}
<a class="block group transition-all duration-300">
    <div class="rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition duration-300">
        <div class="relative aspect-[4/3]">
            <img
                src="{{ $image }}"
                alt="{{ $title }}"
                
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            >

            <div class="absolute bottom-4 right-4">
                <div class="bg-white rounded-full p-2 shadow-md transition-all duration-300 group-hover:scale-110">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 text-gray-800"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 text-lg font-semibold text-gray-900 group-hover:text-black transition">
            {{ strtoupper($title) }}
        </div>
    </div>
</a>
