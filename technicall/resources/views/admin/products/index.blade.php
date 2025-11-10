<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('products.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                Create Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Desktop Table View (hidden on mobile) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price (RON)</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Edit</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $product->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($product->price, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition">
                                            Edit
                                        </a>

                                        <button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'delete-product-{{ $product->id }}')"
                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition"
                                        >{{ __('Delete') }}</button>

                                        <x-confirm-delete-modal
                                            :name="'delete-product-' . $product->id"
                                            :actionUrl="route('products.destroy', $product)"
                                            :title="'Delete Product: ' . $product->name"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No products found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>

                    {{-- Mobile Card View (visible only on mobile) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($products as $product)
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Code: {{ $product->code }}
                                    </p>
                                    <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400 mt-2">
                                        {{ number_format($product->price, 2) }} RON
                                    </p>
                                </div>

                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600 flex gap-2">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                        Edit
                                    </a>
                                    <button
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'delete-product-{{ $product->id }}')"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                                        Delete
                                    </button>

                                    <x-confirm-delete-modal
                                        :name="'delete-product-' . $product->id"
                                        :actionUrl="route('products.destroy', $product)"
                                        :title="'Delete Product: ' . $product->name"
                                    />
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No products found.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>