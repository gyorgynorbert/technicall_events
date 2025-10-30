<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Added for date queries

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request) // Or public function index(Request $request)
    {
        // Set default values in case queries fail
        $totalRevenue = 0;
        $totalOrders = 0;
        $totalStudents = 0;
        $totalSchools = 0;
        $newOrders = 0; // <-- ADD THIS DEFAULT
        $recentOrders = collect(); // <-- ADD THIS DEFAULT

        try {
            // Calculate total revenue from all orders
            $totalRevenue = Order::sum('total_price');
            // Count all orders
            $totalOrders = Order::count();
            // Count all students
            $totalStudents = Student::count();
            // Count all schools
            $totalSchools = School::count();

            // --- ADDED THESE QUERIES ---
            $oneWeekAgo = Carbon::now()->subDays(7);

            // Get count of new orders
            $newOrders = Order::where('created_at', '>=', $oneWeekAgo)->count();

            // Get the 5 most recent orders for the list
            $recentOrders = Order::with('student')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            // --- END ADDED QUERIES ---

        } catch (\Exception $e) {
            // Log the real error
            Log::error('Dashboard statistics failed to load: '.$e->getMessage());

            // Inform the admin, but don't crash the page.
            toast()->danger('Could not load dashboard statistics. (Database Error)')->push();
        }

        // The view is always returned, even if queries fail,
        // passing either the real data or the default values.
        return view('dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'totalStudents' => $totalStudents,
            'totalSchools' => $totalSchools,
            'newOrders' => $newOrders,       // <-- PASS THE VARIABLE
            'recentOrders' => $recentOrders, // <-- PASS THE VARIABLE (I assume the view needs this too)
        ]);
    }
}
