<div>
    <x-input-label for="name" :value="__('Event Name')" class="dark:text-gray-200" />
    <x-text-input
        id="name"
        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100
               focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition"
        type="text"
        name="name"
        :value="old('name', $event->name ?? '')"
        required
        autofocus
    />
    <x-input-error :messages="$errors->get('name')" class="mt-2 dark:text-red-400" />
</div>

<div class="mt-4">
    <x-input-label for="event_date" :value="__('Event Date')" class="dark:text-gray-200" />
    <x-text-input
        id="event_date"
        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100
               focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition"
        type="date"
        name="event_date"
        :value="old('event_date', $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : '')"
    />
    <x-input-error :messages="$errors->get('event_date')" class="mt-2 dark:text-red-400" />
</div>

<div class="mt-4">
    <x-input-label for="description" :value="__('Description')" class="dark:text-gray-200" />
    <textarea
        id="description"
        name="description"
        rows="4"
        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100
               focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition"
    >{{ old('description', $event->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2 dark:text-red-400" />
</div>

<div class="flex items-center justify-end mt-4">
    <a href="{{ route('events.index') }}"
       class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 mr-4 transition">
        {{ __('Cancel') }}
    </a>
    <x-primary-button class="dark:bg-indigo-600 dark:hover:bg-indigo-500 dark:text-white transition">
        {{ __('Save') }}
    </x-primary-button>
</div>
