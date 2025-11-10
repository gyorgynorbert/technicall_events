<x-public-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-8 sm:mb-12">
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-gray-100">Rendelés összegzése</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm sm:text-base">2. lépés: Add meg az adataidat</p>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="max-w-3xl mx-auto mb-6">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('order.submit') }}" method="POST" class="max-w-7xl mx-auto">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                    {{-- Left Column: Contact Information --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 sm:p-8">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Elérhetőség</h2>

                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="name" :value="__('Név (Name)')" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="email" :value="__('Email Cím')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="phone_number" :value="__('Telefonszám')" />
                                    <x-text-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number" :value="old('phone_number')" placeholder="0712345678" pattern="^0\d{9}$" required />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Formátum: 0712345678 (10 számjegy, 0-val kezdve)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Order Summary (Sticky on Desktop) --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 sm:p-8 lg:sticky lg:top-8">
                            <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">Kosár tartalma</h2>

                            {{-- Cart Items --}}
                            <div class="space-y-4 max-h-[400px] lg:max-h-[500px] overflow-y-auto pr-2">
                                @foreach ($cart['items'] as $item)
                                    <div class="flex items-start space-x-3 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                        <x-photo-modal :photo="$item['photo_url']" :label="$item['photo_label']">
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 flex-shrink-0 bg-gray-100 dark:bg-gray-900 rounded-md overflow-hidden flex items-center justify-center">
                                                <img src="{{ $item['photo_url'] }}" alt="{{ $item['photo_label'] }}" class="max-w-full max-h-full object-contain">
                                            </div>
                                        </x-photo-modal>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm sm:text-base truncate">{{ $item['product_name'] }}</p>
                                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 truncate">{{ $item['photo_label'] }}</p>
                                            <div class="flex justify-between items-center mt-1">
                                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Mennyiség: {{ $item['quantity'] }}</p>
                                                <p class="font-semibold text-gray-800 dark:text-gray-100 text-sm sm:text-base">{{ number_format($item['subtotal'], 2) }} LEI</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total --}}
                            <div class="mt-6 pt-6 border-t-2 border-gray-300 dark:border-gray-600">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100">Végösszeg:</span>
                                    <span class="text-xl sm:text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ number_format($cart['total_price'], 2) }} LEI</span>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="mt-6">
                                <x-primary-button type="submit" class="w-full text-base sm:text-lg justify-center py-3 sm:py-4">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Rendelés véglegesítése') }}
                                </x-primary-button>
                            </div>

                            {{-- Security Note --}}
                            <p class="mt-4 text-xs text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Biztonságos rendelés
                            </p>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</x-public-layout>