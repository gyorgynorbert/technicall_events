<div>
    <x-input-label for="grade_id" :value="__('Grade')" />
    <select id="grade_id" name="grade_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        <option value="">{{ __('Select a Grade') }}</option>
        @foreach ($grades as $grade)
            <option value="{{ $grade->id }}" 
                @selected(old('grade_id', $student->grade_id ?? '') == $grade->id)
            >
                {{ $grade->school->name ?? 'No School' }} - {{ $grade->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('grade_id')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="name" :value="__('Student Name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $student->name ?? '')" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        {{ __('Cancel') }}
    </a>
    <x-primary-button>
        {{ __('Save') }}
    </x-primary-button>
</div>