<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Order;
use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('student.grade.school')->orderBy('created_at', 'desc');

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('parent_name', 'like', "%{$search}%")
                  ->orWhere('parent_email', 'like', "%{$search}%");
            });
        }

        // Apply filters if provided
        if ($request->filled('school_id')) {
            $query->whereHas('student.grade.school', function ($q) use ($request) {
                $q->where('id', $request->school_id);
            });
        }

        if ($request->filled('grade_id')) {
            $query->whereHas('student.grade', function ($q) use ($request) {
                $q->where('id', $request->grade_id);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->appends($request->query());

        // Get unique schools that have orders - prevent showing schools with no orders
        $schoolIds = Order::with('student.grade.school')
            ->get()
            ->pluck('student.grade.school.id')
            ->filter()
            ->unique();

        $schools = School::whereIn('id', $schoolIds)
            ->orderBy('name')
            ->get();

        // Get unique grades that have orders - prevent duplication
        $gradeIds = Order::distinct()->pluck('student_id')
            ->map(function ($studentId) {
                return \App\Models\Student::find($studentId)?->grade_id;
            })
            ->filter()
            ->unique();

        $grades = Grade::with('school')
            ->whereIn('id', $gradeIds)
            ->orderBy('name')
            ->get();

        return view('admin.orders.index', compact('orders', 'schools', 'grades'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Eager load all necessary relationships to prevent N+1 queries
        $order->load('student.grade.school', 'orderItems.product', 'orderItems.photo');

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

    /**
     * Export orders to Excel/CSV with optional filters
     */
    public function export(Request $request)
    {
        $request->validate([
            'school_id' => 'nullable|exists:schools,id',
            'grade_id' => 'nullable|exists:grades,id',
            'status' => ['nullable', 'string', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ])],
            'format' => 'required|in:xlsx,csv',
        ]);

        $schoolId = $request->input('school_id');
        $gradeId = $request->input('grade_id');
        $status = $request->input('status');
        $format = $request->input('format', 'xlsx');

        $filename = 'orders_' . now()->format('Y-m-d_His') . '.' . $format;

        return Excel::download(
            new OrdersExport($schoolId, $gradeId, $status),
            $filename
        );
    }
}
