<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }}: #{{ $order->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="md:col-span-1">
                        <h3 class="text-lg font-medium text-gray-900">Customer Details</h3>
                        <div class="mt-4 space-y-2">
                            <p><strong>Student:</strong> {{ $order->student->name }}</p>
                            <p><strong>Parent:</strong> {{ $order->parent_name }}</p>
                            <p><strong>Email:</strong> {{ $order->parent_email }}</p>
                            <p><strong>Phone:</strong> {{ $order->parent_phone_number }}</p>
                            <p><strong>Order Date:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                            <p><strong>Status:</strong> 
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                            <p class="text-2xl font-bold mt-4">
                                Total: {{ number_format($order->total_price, 2) }} RON
                            </p>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                        <div class="mt-4 space-y-4">
                            @foreach($order->orderItems as $item)
                                <div class="flex items-center space-x-4 border-b pb-4">
                                    <img src="{{ $item->photo->url }}" alt="{{ $item->photo->label }}" class="w-24 h-24 rounded-md object-cover">
                                    <div class="flex-1">
                                        <p class="font-semibold text-lg">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-600"><strong>Photo:</strong> {{ $item->photo->label }}</p>
                                        <p class="text-sm text-gray-600"><strong>SKU:</strong> {{ $item->product->code }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p>{{ number_format($item->price_at_purchase, 2) }} RON x {{ $item->quantity }}</p>
                                        <p class="font-bold text-lg">{{ number_format($item->price_at_purchase * $item->quantity, 2) }} RON</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>