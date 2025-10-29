<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Usernotnull\Toast\Concerns\WireToast;

class OrderController extends Controller
{
    use WireToast;

    /**
     * Display a listing of all orders.
     */
    public function index()
    {
        $orders = Order::with('student') // Eager load the student
            ->orderBy('created_at', 'desc') // Show newest orders first
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        // Load all relationships needed for the detail view
        $order->load('student', 'orderItems.product', 'orderItems.photo');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                // Use the constants from the model for validation
                Rule::in([
                    Order::STATUS_PENDING,
                    Order::STATUS_PROCESSING,
                    Order::STATUS_COMPLETED,
                    Order::STATUS_CANCELLED,
                ]),
            ],
        ]);

        $order->update($validated);

        toast()->success('Order status updated successfully.')->push();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
