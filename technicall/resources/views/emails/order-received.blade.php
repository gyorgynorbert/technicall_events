<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #2c3e50; }
        h2 { border-bottom: 2px solid #eee; padding-bottom: 5px; }
        .order-details, .item-list { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .order-details th, .order-details td { text-align: left; padding: 8px; border: 1px solid #ddd; }
        .order-details th { background-color: #f4f4f4; }
        .item-list img { max-width: 80px; height: auto; border-radius: 4px; vertical-align: middle; margin-right: 10px; }
        .item-list td { vertical-align: middle; }
        .total { font-size: 1.2em; font-weight: bold; text-align: right; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Order Received!</h1>
        <p>You have a new order from the parent of <strong>{{ $order->student->name }}</strong>.</p>

        <h2>Order Summary</h2>
        <table class="order-details">
            <tr>
                <th>Order ID</th>
                <td>#{{ $order->id }}</td>
            </tr>
            <tr>
                <th>Student</th>
                <td>{{ $order->student->name }}</td>
            </tr>
            <tr>
                <th>Parent's Phone</th>
                <td>{{ $order->parent_phone_number }}</td>
            </tr>
            <tr>
                <th>Total Price</th>
                <td>{{ number_format($order->total_price, 2) }} RON</td>
            </tr>
        </table>

        <h2>Ordered Items</h2>
        <table class="order-details item-list">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                    <tr>
                        <td>
                            <img src="{{ url($item->photo->url) }}" alt="{{ $item->photo->label }}">
                            <span>{{ $item->photo->label }}</span>
                        </td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price_at_purchase * $item->quantity, 2) }} RON</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">
            Total: {{ number_format($order->total_price, 2) }} RON
        </p>
    </div>
</body>
</html>