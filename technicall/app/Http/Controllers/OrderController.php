<?php

namespace App\Http\Controllers;

use App\Mail\OrderReceived;
use App\Models\Order;
use App\Models\Photo;
use App\Models\Product;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Show the public order form for a specific student.
     */
    public function show(string $access_key)
    {
        $student = Student::where('access_key', $access_key)->firstOrFail();

        // Store the key in the session
        session(['student_access_key' => $access_key]);

        $photos = $student->photos;
        $products = Product::orderBy('name')->get();

        return view('public.order-form', compact('student', 'photos', 'products'));
    }

    public function storeOrder(Request $request)
    {
        $cart = session('cart');
        if (empty($cart)) {
            return redirect()->route('order.show', ['access_key' => session('student_access_key', '')])
                ->withErrors(['products' => 'Your session expired. Please start over.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|regex:/^0\d{9}$/',
        ]);

        // Find the student
        $student = Student::find($cart['student_id']);
        if (! $student) {
            return back()->withInput()->withErrors(['cart' => 'Student not found.']);
        }

        // 3. Use a Database Transaction to save everything
        try {
            DB::beginTransaction();

            $order = Order::create([
                'student_id' => $student->id,
                // Add the new fields to the fillable array in Order model
                'parent_name' => $validated['name'],
                'parent_email' => $validated['email'],
                'parent_phone_number' => $validated['phone_number'],
                'total_price' => $cart['total_price'],
                'status' => 'pending',
            ]);

            // Prepare the order items from the cart data
            $orderItemsData = [];
            foreach ($cart['items'] as $item) {
                $orderItemsData[] = [
                    'product_id' => $item['product_id'],
                    'photo_id' => $item['photo_id'],
                    'quantity' => $item['quantity'],
                    'price_at_purchase' => $item['price'],
                ];
            }

            $order->orderItems()->createMany($orderItemsData);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['cart' => 'Could not save your order. Please try again.']);
        }

        $admin = User::where('is_admin', true)->first();
        if ($admin) {
            Mail::to($admin)->send(new OrderReceived($order->load('student', 'orderItems.product', 'orderItems.photo')));
        }

        // 6. Clear the cart and redirect
        session()->forget('cart');
        session()->forget('student_access_key');

        return redirect()->route('order.success');
    }

    public function checkout()
    {
        \Log::info('Checkout accessed', [
            'has_cart' => session()->has('cart'),
            'cart' => session('cart'),
            'access_key' => session('student_access_key'),
        ]);

        $cart = session('cart');

        if (empty($cart) || empty($cart['items'])) {
            return redirect()->route('order.show', ['access_key' => session('student_access_key', 'missing')])
                ->withErrors(['products' => 'Your cart is empty.']);
        }

        return view('public.checkout', compact('cart'));
    }

    public function storeCart(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.photo' => 'required|array',
            'products.*.quantity' => 'required|array',
            'student_id' => 'required|exists:students,id', // We'll add this to the form
        ]);

        // Find all the products and photos at once
        $allProducts = Product::find(array_keys($validated['products']));
        $studentPhotoIds = Photo::where('student_id', $validated['student_id'])->pluck('id');

        $cartItems = [];
        $totalPrice = 0;

        foreach ($validated['products'] as $product_id => $details) {
            $product = $allProducts->find($product_id);
            if (! $product) {
                continue;
            }

            foreach ($details['photo'] as $index => $photo_id) {
                $quantity = (int) ($details['quantity'][$index] ?? 0);
                if ($quantity <= 0) {
                    continue;
                }

                // Validate the photo belongs to the student
                if (! $studentPhotoIds->contains($photo_id)) {
                    return back()->withInput()->withErrors(['products' => 'Invalid photo selected.']);
                }

                // Get the actual photo model for its URL and label
                $photo = Photo::find($photo_id);

                $totalPrice += $product->price * $quantity;
                $cartItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'photo_id' => $photo->id,
                    'photo_label' => $photo->label,
                    'photo_url' => $photo->url,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'subtotal' => $product->price * $quantity,
                ];
            }
        }

        if (empty($cartItems)) {
            return back()->withInput()->withErrors(['products' => 'You must order at least one item.']);
        }

        session([
            'cart' => [
                'items' => $cartItems,
                'total_price' => $totalPrice,
                'student_id' => $validated['student_id'],
            ],
        ]);

        return redirect()->route('order.checkout');
    }
}
