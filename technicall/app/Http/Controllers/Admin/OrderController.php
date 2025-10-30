<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Make sure this is present
use App\Models\Order;                // Make sure this is present
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This query is perfect
        $orders = Order::with('student.grade.school')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // This eager-load is perfect
        $order->load('student.grade.school', 'orderitems.product');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified order's status.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        // 1. Validate the incoming request
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                Rule::in([
                    Order::STATUS_PENDING,
                    Order::STATUS_PROCESSING,
                    Order::STATUS_COMPLETED,
                    Order::STATUS_CANCELLED,
                ]),
            ],
        ]);

        try {
            // 2. Update the order's status
            $order->update([
                'status' => $validated['status'],
            ]);

            // 3. Give success feedback
            toast()->success('Order status updated successfully.')->push();

        } catch (\Exception $e) {
            // 4. Catch any DB errors
            Log::error("Order update failed (ID: {$order->id}): ".$e->getMessage());
            toast()->danger('Error updating order status. Please try again.')->push();
        }

        // 5. Redirect back to the order details page
        return redirect()->route('orders.show', $order);
    }

    /**
     * Remove the specified resource from storage.
     * Added to allow admins to delete test/fraudulent orders.
     */
    public function destroy(Order $order)
    {
        try {
            $orderId = $order->id;

            // The 'onDelete(cascade)' migration on the 'order_items' table
            // will automatically clean up the child items. This is safe.
            $order->delete();

            toast()->success("Order #{$orderId} deleted successfully.")->push();
        } catch (\Exception $e) {
            Log::error("Order deletion failed (ID: {$order->id}): ".$e->getMessage());
            toast()->danger('Error deleting order. Please try again.')->push();
        }

        // Redirect to the index page as the 'show' page no longer exists
        return redirect()->route('orders.index');
    }
}
