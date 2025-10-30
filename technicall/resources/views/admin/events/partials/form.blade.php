<div>
    <x-input-label for="name" :value="__('Event Name')" />
    <x-text-input
        id="name"
        class="block mt-1 w-full"
        type="text"
        name="name"
        :value="old('name', $event->name ?? '')"
        required
        autofocus
    />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="event_date" :value="__('Event Date')" />
    <x-text-input
        id="event_date"
        class="block mt-1 w-full"
        type="date"
        name="event_date"
        :value="old('event_date', $event->event_date ? $event->event_date->format('Y-m-d') : '')"
        required
    />
    <x-input-error :messages="$errors->get('event_date')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" />
    <textarea
        id="description"
        name="description"
        rows="4"
        class="block mt-1 w-full border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >{{ old('description', $event->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="school_ids" :value="__('Assign to Schools (Optional)')" />
    <select
        name="school_ids[]"
        id="school_ids"
        multiple
        class="block w-full h-48 border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        @isset($schools)
            @foreach($schools as $school)
                <option
                    value="{{ $school->id }}"
                    @if(old('school_ids') ? in_array($school->id, old('school_ids')) : (isset($event) && $event->schools->contains($school->id)))
                        selected
                    @endif
                >
                    {{ $school->name }}
                </option>
            @endforeach
        @endisset
    </select>
    <x-input-error :messages="$errors->get('school_ids')" class="mt-2" />
    <x-input-error :messages="$errors->get('school_ids.*')" class="mt-2" />
</div>


<div class="flex items-center justify-end mt-4">
    <a href="{{ route('events.index') }}"
       class="text-gray-600 hover:text-gray-900 dark:text-gray-400 mr-4">
        {{ __('Cancel') }}
    </a>
    <x-primary-button>
        {{ __('Save') }}
    </x-primary-button>
</div>