<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PhotoController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::get('/order-success', function () {
    return view('public.success');
})->name('order.success');
Route::post('/order/add-to-cart', [OrderController::class, 'storeCart'])->name('order.cart.store');
Route::post('/order/submit', [OrderController::class, 'storeOrder'])->name('order.submit');
Route::get('/', function () {
    return view('welcome');
});

Route::get('/order/{access_key}', [OrderController::class, 'show'])->name('order.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

Route::middleware(['auth', 'admin', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::resource('events', EventController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('grades', GradeController::class);
    Route::resource('students', StudentController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);

    Route::post('students/{student}/photos', [PhotoController::class, 'store'])->name('students.photos.store');
    Route::delete('photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
