<x-public-layout>
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-center">
        {{-- Success Icon --}}
        <div class="flex justify-center mb-6">
            <div class="rounded-full bg-green-100 dark:bg-green-900 p-6">
                <svg class="w-16 h-16 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
            Sikeres rendelés!
        </h1>
        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">
            Köszönjük a rendelésed. Hamarosan felvesszük veled a kapcsolatot a megadott telefonszámon.
        </p>
        <p class="mt-8">
            <a href="/" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Vissza a főoldalra
            </a>
        </p>
    </div>
</x-public-layout>