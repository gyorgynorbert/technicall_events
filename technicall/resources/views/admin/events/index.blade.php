<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Events') }}
            </h2>
            <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create New Event') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schools</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($events as $event)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $event->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('Y-m-d') : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event->schools->count() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('events.edit', $event) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </td>
                                    <td>
                                        <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No events found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>