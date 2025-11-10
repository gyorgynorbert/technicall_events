<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Schools') }}
            </h2>
            <a href="{{ route('schools.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                Create School
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Events</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Edit</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($schools as $school)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $school->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $school->location }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($school->events->count() > 0)
                                            {{ $school->events->take(2)->pluck('name')->join(', ') }}
                                            @if($school->events->count() > 2)
                                                <span class="text-gray-400">+{{ $school->events->count() - 2 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">No events</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition">
                                            Edit
                                        </a>
                                        <button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'deleteSchool-{{ $school->id }}')"
                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No schools found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>

                    {{-- Mobile Card View (visible only on mobile) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($schools as $school)
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $school->name }}
                                    </h3>
                                    @if($school->location)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $school->location }}
                                        </p>
                                    @endif
                                    <div class="mt-2 text-sm">
                                        <p class="text-gray-500 dark:text-gray-400">Events:</p>
                                        @if($school->events->count() > 0)
                                            <p class="text-gray-900 dark:text-gray-100">
                                                {{ $school->events->take(2)->pluck('name')->join(', ') }}
                                                @if($school->events->count() > 2)
                                                    <span class="text-gray-500">+{{ $school->events->count() - 2 }} more</span>
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-gray-500 dark:text-gray-400">No events assigned</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600 flex gap-2">
                                    <a href="{{ route('schools.edit', $school) }}"
                                       class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                        Edit
                                    </a>
                                    <button
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'deleteSchool-{{ $school->id }}')"
                                        class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No schools found.
                            </div>
                        @endforelse
                    </div>

                    <!-- Delete Modals -->
                    @foreach ($schools as $school)
                        <x-confirm-delete-modal
                            :name="'deleteSchool-' . $school->id"
                            title="{{ __('Delete School') }}"
                            :actionUrl="route('schools.destroy', $school)"
                        />
                    @endforeach

                    <div class="mt-6">
                        {{ $schools->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>