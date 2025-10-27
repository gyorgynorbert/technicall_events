<div>
    <x-input-label for="school_id" :value="__('School')" />
    <select id="school_id" name="school_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="">{{ __('Select a School') }}</option>
        @foreach ($schools as $school)
            <option value="{{ $school->id }}" 
                @selected(old('school_id', $grade->school_id ?? '') == $school->id)
            >
                {{ $school->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="name" :value="__('Grade Name (e.g., Class 1A)')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $grade->name ?? '')" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('grades.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        {{ __('Cancel') }}
    </a>
    <x-primary-button>
        {{ __('Save') }}
    </x-primary-button>
</div>