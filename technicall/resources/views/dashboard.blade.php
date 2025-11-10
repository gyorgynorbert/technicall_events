<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <span class="text-sm text-gray-600 dark:text-gray-400">Last updated: {{ now()->format('Y-m-d H:i') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Primary KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- Pending Orders -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900 dark:to-yellow-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Pending Orders</h3>
                                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $pendingOrders }}</p>
                            </div>
                            <div class="text-yellow-400 opacity-20">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000 2H3a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V6a1 1 0 00-1-1h-3a1 1 0 000-2 2 2 0 00-2-2H6a2 2 0 00-2 2zM7 8a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">Need attention</p>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Revenue</h3>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($totalRevenue, 0) }} RON</p>
                            </div>
                            <div class="text-green-400 opacity-20">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.16 2.75a.75.75 0 00-1.08.6v5.69H2.75a.75.75 0 00-.6 1.08l8.5 11.5a.75.75 0 001.2 0l8.5-11.5a.75.75 0 00-.6-1.08h-4.33V3.35a.75.75 0 00-.75-.6h-.59z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">Week: {{ number_format($weekRevenue, 0) }} RON</p>
                    </div>
                </div>

                <!-- Total Students -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Students</h3>
                                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">{{ $totalStudents }}</p>
                            </div>
                            <div class="text-blue-400 opacity-20">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5m-11-4v3.5m5.75-3.5v3.5M3.5 9.5h13"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">Active learners</p>
                    </div>
                </div>

                <!-- Completion Rate -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Completion Rate</h3>
                                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0 }}%</p>
                            </div>
                            <div class="text-purple-400 opacity-20">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">{{ $completedOrders }} of {{ $totalOrders }}</p>
                    </div>
                </div>

            </div>

            <!-- Secondary Metrics Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Average Order Value -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Average Order Value</h3>
                        <p class="text-2xl font-semibold text-indigo-600 dark:text-indigo-400 mt-2">{{ number_format($averageOrderValue, 2) }} RON</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">Based on {{ $totalOrders }} orders</p>
                    </div>
                </div>

                <!-- Top Product -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Top Product</h3>
                        @if($topProduct)
                            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 mt-2 truncate">{{ $topProduct->name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">{{ $topProduct->order_items_count }} orders</p>
                        @else
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">No sales yet</p>
                        @endif
                    </div>
                </div>

                <!-- Top Student -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Most Active Student</h3>
                        @if($studentWithMostOrders)
                            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 mt-2 truncate">{{ $studentWithMostOrders->name }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">{{ $studentWithMostOrders->orders_count }} orders</p>
                        @else
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">No students yet</p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Orders</h3>
                        <a href="{{ route('orders.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400">View All →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Order</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">School</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">View</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($recentOrders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium">#{{ $order->id }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $order->student?->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->student?->grade?->school?->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span @class([
                                                'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $order->status == App\Models\Order::STATUS_PENDING,
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' => $order->status == App\Models\Order::STATUS_PROCESSING,
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $order->status == App\Models\Order::STATUS_COMPLETED,
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => $order->status == App\Models\Order::STATUS_CANCELLED,
                                            ])>
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ number_format($order->total_price, 2) }} RON
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                No orders yet. Start by creating a new order!
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>