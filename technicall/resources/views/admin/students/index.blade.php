<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Students') }}
            </h2>
            <a href="{{ route('students.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                Create Student
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('students.index') }}" method="GET" class="mb-6">
                        <div class="flex gap-2">
                            <x-text-input type="text" name="search" class="flex-1"
                                          placeholder="Search for a student by name..."
                                          value="{{ request()->input('search') }}" />
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                                {{ __('Search') }}
                            </button>
                            @if(request()->filled('search'))
                                <a href="{{ route('students.index') }}"
                                   class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                    {{-- Desktop Table View (hidden on mobile) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">School</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Edit</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($students as $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $student->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $student->grade->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $student->grade->school->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                        <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition" title="Manage Photos">
                                            Photos
                                        </a>
                                        <button onclick="
                                            @if($student->photos_count > 0)
                                                navigator.clipboard.writeText('{{ route('order.show', $student->access_key) }}');
                                                Toast.success('Link copied to clipboard!');
                                            @else
                                                Toast.warning('Cannot copy: No photos uploaded for this student');
                                            @endif
                                        " class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition {{ $student->photos_count == 0 ? 'opacity-50' : '' }}">
                                            Link
                                        </button>
                                        <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition">
                                            Edit
                                        </a>
                                        <button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'deleteStudent-{{ $student->id }}')"
                                            class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md transition"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>

                    {{-- Mobile Card View (visible only on mobile) --}}
                    <div class="md:hidden space-y-4">
                        @forelse ($students as $student)
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $student->name }}</h3>
                                    <div class="mt-2 space-y-1 text-sm">
                                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span>{{ $student->grade->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            <span>{{ $student->grade->school->name ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600 flex flex-col gap-2">
                                    <a href="{{ route('students.show', $student) }}" class="w-full inline-flex items-center justify-center px-3 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">
                                        Manage Photos
                                    </a>
                                    <button onclick="
                                        @if($student->photos_count > 0)
                                            navigator.clipboard.writeText('{{ route('order.show', $student->access_key) }}');
                                            Toast.success('Link copied to clipboard!');
                                        @else
                                            Toast.warning('Cannot copy: No photos uploaded for this student');
                                        @endif
                                    " class="w-full inline-flex items-center justify-center px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition {{ $student->photos_count == 0 ? 'opacity-50' : '' }}">
                                        Copy Link
                                    </button>
                                    <div class="flex gap-2">
                                        <a href="{{ route('students.edit', $student) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                            Edit
                                        </a>
                                        <button
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'deleteStudent-{{ $student->id }}')"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No students found.
                            </div>
                        @endforelse
                    </div>

                    <!-- Delete Modals -->
                    @foreach ($students as $student)
                        <x-confirm-delete-modal
                            :name="'deleteStudent-' . $student->id"
                            title="{{ __('Delete Student') }}"
                            :actionUrl="route('students.destroy', $student)"
                        />
                    @endforeach

                    <div class="mt-6">
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>