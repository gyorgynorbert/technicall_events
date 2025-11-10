<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manage Photos for') }}: {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">{{ __('Upload New Photo') }}</h3>
                    <form action="{{ route('students.photos.store', $student) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="label" :value="__('Photo Label (Optional)')" />
                            <x-text-input id="label" class="block mt-1 w-full" type="text" name="label" :value="old('label')" />
                            <p class="mt-1 text-sm text-gray-600">{{ __("e.g., 'Cover Photo' or 'Photo 1'. If blank, the filename will be used.") }}</p>
                        </div>
                        <div>
                            <x-input-label for="photo" :value="__('Photo File')" />
                            <input id="photo" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" type="file" name="photo" required>
                            <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            {{ __('Upload') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">{{ __('Existing Photos') }} ({{ $student->photos->count() }})</h3>

                    @if ($student->photos->isEmpty())
                        <p class="mt-4 text-gray-500 dark:text-gray-400">{{ __('No photos uploaded for this student yet.') }}</p>
                    @else
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($student->photos as $photo)
        @php
            $photoModalId = 'photo-modal-' . $photo->id;
            $deleteModalId = 'delete-photo-modal-' . $photo->id;
        @endphp
        <div class="border rounded-lg overflow-hidden shadow-sm dark:border-gray-700">
            <img 
                src="{{ $photo->url }}" 
                alt="{{ $photo->label }}" 
                class="w-full h-48 object-cover cursor-pointer hover:opacity-80 transition"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', '{{ $photoModalId }}')"
            >
            <div class="p-4">
                <p class="font-medium text-sm truncate">{{ $photo->label }}</p>
                
                <button
                    type="button"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', '{{ $deleteModalId }}')"
                    class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition mt-2"
                >
                    {{ __('Delete') }}
                </button>
            </div>
        </div>
        
        <x-modal :name="$photoModalId" max-width="4xl">
            <div class="p-6 bg-white dark:bg-gray-800">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $photo->label }}
                    </h3>
                    <button 
                        type="button" 
                        x-on:click="$dispatch('close')"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <img 
                    src="{{ $photo->url }}" 
                    alt="{{ $photo->label }}" 
                    class="w-full h-auto object-contain max-h-[70vh] rounded"
                >
            </div>
        </x-modal>

        <x-modal :name="$deleteModalId" focusable>
            <form method="POST" action="{{ route('photos.destroy', $photo) }}" class="p-6 dark:bg-gray-800">
                @csrf
                @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Delete Photo') }}
                </h2>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                    Are you sure you want to delete this photo? This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-danger-button class="ms-3">
                        {{ __('Delete') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>