<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Student') }}: {{ $student->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('students.update', $student) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.students.partials.form', ['grades' => $grades, 'student' => $student])
                    </form>

                    <div class="mt-6">
                        <h3 class="font-semibold">{{ __('Parent Access Link') }}</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ __('This is the unique, private link for the student\'s parents.') }}</p>
                        <input type="text" class="block mt-2 w-full border-gray-300 rounded-md shadow-sm bg-gray-100 dark:bg-gray-900" 
                               value="{{ route('order.show', $student->access_key) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>