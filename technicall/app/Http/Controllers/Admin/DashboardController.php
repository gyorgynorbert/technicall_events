<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Set default values in case queries fail
        $totalRevenue = 0;
        $totalOrders = 0;
        $totalStudents = 0;
        $totalSchools = 0;
        $newOrders = 0;
        $recentOrders = collect();
        $weekRevenue = 0;
        $monthRevenue = 0;
        $completedOrders = 0;
        $pendingOrders = 0;
        $processingOrders = 0;
        $averageOrderValue = 0;
        $topProduct = null;
        $studentWithMostOrders = null;

        try {
            // Timeline calculations
            $oneWeekAgo = Carbon::now()->subDays(7);
            $oneMonthAgo = Carbon::now()->subDays(30);

            // Core metrics
            $totalRevenue = Order::sum('total_price');
            $totalOrders = Order::count();
            $totalStudents = Student::count();
            $totalSchools = School::count();

            // Time-based metrics
            $newOrders = Order::where('created_at', '>=', $oneWeekAgo)->count();
            $weekRevenue = Order::where('created_at', '>=', $oneWeekAgo)->sum('total_price');
            $monthRevenue = Order::where('created_at', '>=', $oneMonthAgo)->sum('total_price');

            // Status breakdown
            $completedOrders = Order::where('status', 'completed')->count();
            $pendingOrders = Order::where('status', 'pending')->count();
            $processingOrders = Order::where('status', 'processing')->count();

            // Average order value
            $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            // Top product
            $topProduct = Product::withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->first();

            // Student with most orders
            $studentWithMostOrders = Student::withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->first();

            // Recent orders with student details
            $recentOrders = Order::with(['student' => function ($query) {
                $query->with('grade.school');
            }])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

        } catch (\Exception $e) {
            Log::error('Dashboard statistics failed to load: '.$e->getMessage());
            toast()->danger('Could not load dashboard statistics. (Database Error)')->push();
        }

        return view('dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalStudents' => $totalStudents,
            'totalSchools' => $totalSchools,
            'newOrders' => $newOrders,
            'recentOrders' => $recentOrders,
            'weekRevenue' => $weekRevenue,
            'monthRevenue' => $monthRevenue,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'averageOrderValue' => $averageOrderValue,
            'topProduct' => $topProduct,
            'studentWithMostOrders' => $studentWithMostOrders,
        ]);
    }
}
