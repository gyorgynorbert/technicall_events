<x-public-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 text-center">
            Rendelés: {{ $student->name }}
        </h1>
        <p class="text-center text-gray-600 dark:text-gray-300 mt-2">Válassz a képekből és termékekből.</p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative my-6" role="alert">
                <strong class="font-bold">Hiba!</strong>
                <span class="block sm:inline">A rendelést nem sikerült leadni.</span>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-10">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Választható Képek</h2>
            @if ($photos->isEmpty())
                <p class="mt-4 text-gray-500 dark:text-gray-400">Ehhez a diákhoz még nem töltöttek fel képeket.</p>
            @else
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($photos as $photo)
                        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm">
                            <img src="{{ $photo->url }}" alt="{{ $photo->label }}" class="w-full h-64 object-cover">
                            <div class="p-4 bg-gray-100 dark:bg-gray-800">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ $photo->label }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if ($photos->isNotEmpty())
        <form action="{{ route('order.cart.store') }}" method="POST" id="orderForm" class="mt-10 space-y-8" novalidate>
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->id }}">
            
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Termékek</h2>
                <div class="mt-4 space-y-6">
                    @foreach ($products as $product)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $product->name }}</h3>
                            <p class="text-md text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                            <p class="text-md font-bold text-gray-800 dark:text-gray-100 mt-2">{{ number_format($product->price, 2) }} LEI</p>
                            
                            <div class="mt-4">
                                <h4 class="text-md font-medium text-gray-600 dark:text-gray-300">Válasszon képet és mennyiséget:</h4>
                                <div class="product-option-template">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-1">
                                            <label class="text-sm text-gray-600 dark:text-gray-300">Kép:</label>
                                            <select name="products[{{ $product->id }}][photo][]" class="block w-full border-gray-300 rounded-md shadow-sm">
                                                <option value="">Válasszon egy képet...</option>
                                                @foreach ($photos as $photo)
                                                    <option value="{{ $photo->id }}">{{ $photo->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1">
                                            <label class="text-sm text-gray-600 dark:text-gray-300">Mennyiség:</label>
                                            <input type="number" name="products[{{ $product->id }}][quantity][]" min="0" value="0" class="product-quantity block w-full border-gray-300 rounded-md shadow-sm">
                                        </div>
                                    </div>
                                </div>
                                </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end">
                <x-primary-button class="text-lg px-8 py-3">
                    {{ __('Tovább a 2. lépéshez') }} &rarr;
                </x-primary-button>
            </div>
        </form>
        @endif

    </div>
</x-public-layout>