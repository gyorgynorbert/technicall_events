<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Grade') }}: {{ $grade->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div classB="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('grades.update', $grade) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('admin.grades.partials.form', ['schools' => $schools, 'grade' => $grade])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>