<div>
    <x-input-label for="event_id" :value="__('Event')" />
    <select id="event_id" name="event_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">{{ __('Select an Event') }}</option>
        @foreach ($events as $event)
            <option value="{{ $event->id }}" 
                @selected(old('event_id', $school->event_id ?? '') == $event->id)
            >
                {{ $event->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('event_id')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="name" :value="__('School Name')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $school->name ?? '')" required />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="location" :value="__('Location (e.g., City)')" />
    <x-text-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $school->location ?? '')" />
    <x-input-error :messages="$errors->get('location')" class="mt-2" />
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('schools.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
        {{ __('Cancel') }}
    </a>
    <x-primary-button>
        {{ __('Save') }}
    </x-primary-button>
</div>