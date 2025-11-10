<div>
    <x-input-label for="grade_id" :value="__('Grade')" />
    <select id="grade_id" name="grade_id" class="block mt-1 w-full border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
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

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('students.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium mr-4">
        {{ __('Cancel') }}
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ __('Save') }}
    </button>
</div>