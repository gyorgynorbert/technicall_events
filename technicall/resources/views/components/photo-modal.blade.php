@props(['photo', 'label' => null])

<div x-data="{ open: false }"
     x-init="$watch('open', value => {
        if (value) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
     })"
     class="inline-block">
    {{-- Thumbnail (clickable) --}}
    <div @click="open = true" class="cursor-pointer hover:opacity-75 transition-opacity">
        {{ $slot }}
    </div>

    {{-- Modal --}}
    <div x-show="open"
         @click.self="open = false"
         @keydown.escape.window="open = false"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 md:p-8 bg-black/90 backdrop-blur-sm"
         style="display: none;">

        <div class="relative w-full h-full flex flex-col items-center justify-center">
            {{-- Header with label and close button --}}
            <div class="flex justify-between items-center w-full max-w-7xl mb-4">
                @if($label)
                    <h3 class="text-white text-lg sm:text-xl font-medium truncate">
                        {{ $label }}
                    </h3>
                @else
                    <div></div>
                @endif

                <button type="button"
                        @click.prevent="open = false"
                        class="flex-shrink-0 ml-4 p-2 rounded-full hover:bg-white/10 text-white hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Image container with max viewport sizing --}}
            <div class="w-full h-full max-w-7xl flex items-center justify-center">
                <img src="{{ $photo }}"
                     alt="{{ $label ?? 'Photo' }}"
                     class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                     @click.stop>
            </div>
        </div>
    </div>
</div>
