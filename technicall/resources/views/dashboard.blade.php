<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">New Orders (Pending)</h3>
                        <p class="text-3xl font-semibold text-indigo-600">{{ $newOrders }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Revenue</h3>
                        <p class="text-3xl font-semibold text-green-600">{{ number_format($totalRevenue, 2) }} RON</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Students</h3>
                        <p class="text-3xl font-semibold text-blue-600">{{ $totalStudents }}</p>
                    </div>
                </div>

            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Recent Orders</h3>
                    <div class="mt-4 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Order ID</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Student</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                <span class="sr-only">View</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @forelse ($recentOrders as $order)
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-indigo-600 sm:pl-0">
                                                    <a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a>
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->student->name ?? 'N/A' }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ number_format($order->total_price, 2) }} RON</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $order->created_at->format('Y-m-d') }}</td>
                                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                    <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">No orders yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>