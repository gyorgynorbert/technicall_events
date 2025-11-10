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
    <div class="mt-2 p-4 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-800 max-h-60 overflow-y-auto">
        @isset($events)
            @forelse($events as $event)
                <label class="flex items-center py-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 px-2 rounded">
                    <input
                        type="checkbox"
                        name="event_ids[]"
                        value="{{ $event->id }}"
                        @if(old('event_ids') ? in_array($event->id, old('event_ids')) : (isset($school) && $school->events->contains($event->id)))
                            checked
                        @endif
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700"
                    >
                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $event->name }} ({{ $event->event_date->format('Y-m-d') }})</span>
                </label>
            @empty
                <p class="text-sm text-gray-500 dark:text-gray-400">No events available</p>
            @endforelse
        @endisset
    </div>
    <x-input-error :messages="$errors->get('event_ids')" class="mt-2" />
    <x-input-error :messages="$errors->get('event_ids.*')" class="mt-2" />
</div>


<div class="flex items-center justify-end mt-6">
    <a href="{{ route('schools.index') }}"
       class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium mr-4">
        {{ __('Cancel') }}
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ __('Save') }}
    </button>
</div>