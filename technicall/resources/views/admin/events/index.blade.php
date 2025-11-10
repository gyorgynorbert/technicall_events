<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Events') }}
            </h2>
            <a href="{{ route('events.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                Create Event
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Desktop Table View (hidden on mobile) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Schools</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Edit</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($events as $event)
                                @php
                                    $deleteModalId = 'deleteModal-' . $event->id;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($event->schools->count() > 0)
                                            {{ $event->schools->take(2)->pluck('name')->join(', ') }}
                                            @if($event->schools->count() > 2)
                                                <span class="text-gray-400">+{{ $event->schools->count() - 2 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">No schools</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('events.edit', $event) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition">
                                            Edit
                                        </a>
                                        <button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'deleteEvent-{{ $event->id }}')"
                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition"
                                        >
                                            {{ __('Delete') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No events found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Delete Modals -->
                    @foreach ($events as $event)
                        <x-confirm-delete-modal
                            :name="'deleteEvent-' . $event->id"
                            title="{{ __('Delete Event') }}"
                            :actionUrl="route('events.destroy', $event)"
                        />
                    @endforeach
                    </div>

                    {{-- Mobile Card View (visible only on mobile) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($events as $event)
                            @php
                                $deleteModalId = 'deleteModal-mobile-' . $event->id;
                            @endphp
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $event->name }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : 'No date' }}
                                    </p>
                                    <div class="mt-2 text-sm">
                                        <p class="text-gray-500 dark:text-gray-400">Schools:</p>
                                        @if($event->schools->count() > 0)
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $event->schools->take(2)->pluck('name')->join(', ') }}
                                                @if($event->schools->count() > 2)
                                                    <span class="text-gray-500">+{{ $event->schools->count() - 2 }} more</span>
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400">No schools assigned</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="pt-2 border-t border-gray-200 dark:border-gray-600 flex justify-center items-center gap-2">
                                    <button href="{{ route('events.edit', $event) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                        Edit
                                    </button>
                                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'deleteEvent-{{ $event->id }}')"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">No events found.</div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>