<x-modal :name="$name" focusable>
    <form method="post" action="{{ $actionUrl }}" class="p-6 text-left" 
          x-data="{ confirmInput: '', confirmText: '{{ $confirmText }}' }">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $title }}
        </h2>

        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once this item is deleted, all of its resources and data will be permanently deleted.') }}
        </p>

        @if ($slot->isNotEmpty())
            <div class="mt-4 p-4 bg-red-100 dark:bg-red-900/40 border border-red-300 dark:border-red-700 rounded-md text-red-700 dark:text-red-300">
                <p class="font-medium">{{ __('This will also permanently delete the following:') }}</p>
                <ul class="list-disc list-inside space-y-1 mt-2">
                    {{ $slot }}
                </ul>
            </div>
        @endif

        @if ($confirmText)
            <div class="mt-6">
                <x-input-label 
                    for="confirm_input_{{ $name }}" 
                    :value="__('To confirm deletion, type \'' . $confirmText . '\' in the box below.')" 
                />
                <x-text-input
                    id="confirm_input_{{ $name }}"
                    class="mt-1 block w-3/4"
                    type="text"
                    x-model="confirmInput"
                />
            </div>
        @endif

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button 
                class="ms-3"
                ::disabled="confirmText && confirmInput !== confirmText"
            >
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>