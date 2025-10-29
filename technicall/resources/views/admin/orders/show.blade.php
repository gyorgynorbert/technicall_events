<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Order #{{ $order->id }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Order placed on {{ $order->created_at->format('F j, Y \a\t g:ia') }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span @class([
                    'px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' => $order->status == App\Models\Order::STATUS_PENDING,
                    'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' => $order->status == App\Models\Order::STATUS_PROCESSING,
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $order->status == App\Models\Order::STATUS_COMPLETED,
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => $order->status == App\Models\Order::STATUS_CANCELLED,
                ])>
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Order Items</h3>
                            <div class="mt-6 space-y-6">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ $item->photo->url }}" alt="{{ $item->photo->label }}" 
                                             class="w-24 h-24 rounded-md object-cover shadow cursor-pointer hover:opacity-80 transition"
                                             @click="$dispatch('open-modal', { src: '{{ $item->photo->url }}' })">
                                        
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Photo:</strong> {{ $item->photo->label }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400"><strong>SKU:</strong> {{ $item->product->code }}</p>
                                        </div>
                                        
                                        <div class="text-right text-gray-700 dark:text-gray-300">
                                            <p>{{ number_format($item->price_at_purchase, 2) }} RON &times; {{ $item->quantity }}</p>
                                            <p class="font-bold text-gray-900 dark:text-gray-100">{{ number_format($item->price_at_purchase * $item->quantity, 2) }} RON</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 text-right">
                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                    Total: {{ number_format($order->total_price, 2) }} RON
                                </p>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Actions</h3>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @php
                                    $statuses = [
                                        App\Models\Order::STATUS_PENDING,
                                        App\Models\Order::STATUS_PROCESSING,
                                        App\Models\Order::STATUS_COMPLETED,
                                        App\Models\Order::STATUS_CANCELLED,
                                    ];
                                @endphp
                                @foreach($statuses as $status)
                                    @if($order->status !== $status)
                                        @php
                                            $statusModalId = 'statusModal-' . $order->id . '-' . $status;
                                        @endphp
                                        
                                        <button 
                                            type="button"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', '{{ $statusModalId }}')"
                                            @class([
                                                'inline-flex items-center px-3 py-1 rounded-md text-xs font-medium transition',
                                                'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:text-yellow-300 dark:hover:bg-yellow-700' => $status === App\Models\Order::STATUS_PENDING,
                                                'bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900 dark:text-blue-300 dark:hover:bg-blue-700' => $status === App\Models\Order::STATUS_PROCESSING,
                                                'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-700' => $status === App\Models\Order::STATUS_COMPLETED,
                                                'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-700' => $status === App\Models\Order::STATUS_CANCELLED,
                                            ])>
                                            Mark as {{ ucfirst($status) }}
                                        </button>

                                        <x-modal :name="$statusModalId" focusable>
                                            <form method="POST" action="{{ route('orders.update', $order) }}" class="p-6 dark:bg-gray-800">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="{{ $status }}" />

                                                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('Change Status') }}
                                                </h2>
                                                <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                                                    Are you sure you want to change this order's status to "{{ ucfirst($status) }}"?
                                                </p>
                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-secondary-button>
                                                    <x-primary-button class="ms-3">
                                                        {{ __('Confirm') }}
                                                    </x-primary-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Customer</h3>
                            <dl class="mt-4 space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $order->parent_name }}</dd>
                                
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $order->parent_email }}</dd>
                                
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $order->parent_phone_number }}</dd>
                            </dl>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Student</h3>
                            <dl class="mt-4 space-y-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $order->student->name }}</dd>
                                
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Grade</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $order->student->grade->name ?? 'N/A' }}</dd>
                                
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">School</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $order->student->grade->school->name ?? 'N/A' }}</dd>
                            </dl>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>