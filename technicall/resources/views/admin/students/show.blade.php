<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Photos for') }}: {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
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
                        <x-primary-button>
                            {{ __('Upload') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">{{ __('Existing Photos') }} ({{ $student->photos->count() }})</h3>

                    @if ($student->photos->isEmpty())
                        <p class="mt-4 text-gray-500">{{ __('No photos uploaded for this student yet.') }}</p>
                    @else
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach ($student->photos as $photo)
                                <div classD="border rounded-lg overflow-hidden shadow-sm">
                                    <img src="{{ $photo->url }}" alt="{{ $photo->label }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $photo->label }}</p>
                                        <form action="{{ route('photos.destroy', $photo) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to delete this photo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button typeA="submit" class="text-xs text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>