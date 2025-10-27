<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('events.store') }}" method="POST">
                        @csrf
                        @include('admin.events.partials.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
