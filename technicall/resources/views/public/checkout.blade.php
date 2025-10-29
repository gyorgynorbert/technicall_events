<x-public-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 text-center">Rendelés összegzése</h1>
        <p class="text-center text-gray-600 dark:text-gray-300 mt-2">2. lépés: Add meg az adataidat</p>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative my-6" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('order.submit') }}" method="POST" class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-12">
            @csrf

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm space-y-4">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Elérhetőség</h2>

                <div>
                    <x-input-label for="name" :value="__('Név (Name)')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email Cím')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <div>
                    <x-input-label for="phone_number" :value="__('Telefonszám')" />
                    <x-text-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number" :value="old('phone_number')" placeholder="0712345678" pattern="^0\d{9}$" required />
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm ">
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Kosár tartalma</h2>

                <div class="mt-4 space-y-4">
                    @foreach ($cart['items'] as $item)
                        <div class="flex items-center space-x-4 pb-2">
                            <img src="{{ $item['photo_url'] }}" alt="{{ $item['photo_label'] }}" class="w-16 h-16 rounded-md object-cover">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-600 dark:text-gray-300">{{ $item['product_name'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Kép: {{ $item['photo_label'] }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Mennyiség: {{ $item['quantity'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-600 dark:text-gray-300">{{ number_format($item['subtotal'], 2) }} LEI</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 border-t pt-4">
                    <p class="text-xl font-bold text-gray-900 dark:text-gray-100 flex justify-between">
                        <span>Végösszeg:</span>
                        <span>{{ number_format($cart['total_price'], 2) }} LEI</span>
                    </p>
                </div>

                <div class="mt-6">
                    <x-primary-button class="w-full text-lg justify-center py-3">
                        {{ __('Rendelés véglegesítése') }}
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-public-layout>