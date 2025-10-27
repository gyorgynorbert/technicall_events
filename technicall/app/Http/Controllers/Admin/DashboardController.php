<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Get new pending orders
        $newOrders = Order::where('status', 'pending')->count();

        // 2. Get total revenue (assuming 'completed' is a future status)
        // For now, let's sum all orders.
        $totalRevenue = Order::sum('total_price');

        // 3. Get total students
        $totalStudents = Student::count();

        // 4. Get most recent 5 orders
        $recentOrders = Order::with('student')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Pass all stats to the view
        return view('dashboard', compact(
            'newOrders',
            'totalRevenue',
            'totalStudents',
            'recentOrders'
        ));
    }
}
