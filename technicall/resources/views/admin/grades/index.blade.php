<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grades') }}
            </h2>
            <a href="{{ route('grades.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Create New Grade') }}
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Edit</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($grades as $grade)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $grade->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $grade->school->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('grades.edit', $grade) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                        <form action="{{ route('grades.destroy', $grade) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No grades found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $grades->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>