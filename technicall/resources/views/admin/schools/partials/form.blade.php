<div>
    <x-input-label for="name" :value="__('School Name')" />
    <x-text-input
        id="name"
        class="block mt-1 w-full"
        type="text"
        name="name"
        :value="old('name', $school->name ?? '')"
        required
        autofocus
    />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="location" :value="__('Location (Optional)')" />
    <x-text-input
        id="location"
        class="block mt-1 w-full"
        type="text"
        name="location"
        :value="old('location', $school->location ?? '')"
    />
    <x-input-error :messages="$errors->get('location')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="event_ids" :value="__('Assign to Events (Optional)')" />
    <select
        name="event_ids[]"
        id="event_ids"
        multiple
        class="block w-full h-48 border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >
        @isset($events)
            @foreach($events as $event)
                <option
                    value="{{ $event->id }}"
                    @if(old('event_ids') ? in_array($event->id, old('event_ids')) : (isset($school) && $school->events->contains($event->id)))
                        selected
                    @endif
                >
                    {{ $event->name }} ({{ $event->event_date->format('Y-m-d') }})
                </option>
            @endforeach
        @endisset
    </select>
    <x-input-error :messages="$errors->get('event_ids')" class="mt-2" />
    <x-input-error :messages="$errors->get('event_ids.*')" class_="mt-2" />
</div>


<div class="flex items-center justify-end mt-4">
    <a href="{{ route('schools.index') }}"
       class="text-gray-600 hover:text-gray-900 dark:text-gray-400 mr-4">
        {{ __('Cancel') }}
    </a>
    <x-primary-button>
        {{ __('Save') }}
    </x-primary-button>
</div>