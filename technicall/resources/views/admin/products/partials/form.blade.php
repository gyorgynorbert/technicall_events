<div>
    <x-input-label for="name" :value="__('Product Name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name ?? '')" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="code" :value="__('Product Code')" />
    <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" :value="old('code', $product->code ?? '')" required />
    <p class="mt-1 text-sm text-gray-600">{{ __("This is a unique identifier, e.g., 'fenykep_a4' or 'bogre'. No spaces.") }}</p>
    <x-input-error :messages="$errors->get('code')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="price" :value="__('Price (RON)')" />
    <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price', $product->price ?? '')" required step="0.01" />
    <x-input-error :messages="$errors->get('price')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $product->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium mr-4">
        {{ __('Cancel') }}
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ __('Save') }}
    </button>
</div>