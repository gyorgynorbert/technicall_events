<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StoreCartRequest;
use App\Http\Requests\Admin\StoreOrderRequest;
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

    public function storeOrder(StoreOrderRequest $request)
    {
        $cart = session('cart');
        if (empty($cart)) {
            return redirect()->route('order.show', ['access_key' => session('student_access_key', '')])
                ->withErrors(['products' => 'A munkamenet lejárt. Kérjük, kezdje újra a rendelést.']);
        }

        $validated = $request->validated();

        // Find the student
        $student = Student::find($cart['student_id']);
        if (! $student) {
            return back()->withInput()->withErrors(['cart' => 'Érvénytelen diák adatok. Kérjük, kezdje újra a rendelést.']);
        }

        // SECURITY: Recalculate total price from database to prevent price manipulation
        $productIds = collect($cart['items'])->pluck('product_id')->unique();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $actualTotalPrice = 0;
        $orderItemsData = [];

        foreach ($cart['items'] as $item) {
            $product = $products->get($item['product_id']);

            if (!$product) {
                return back()->withInput()->withErrors(['cart' => 'Érvénytelen termék a kosárban. Kérjük, kezdje újra a rendelést.']);
            }

            // Use ACTUAL price from database, not session
            $actualPrice = $product->price;
            $actualTotalPrice += $actualPrice * $item['quantity'];

            $orderItemsData[] = [
                'product_id' => $item['product_id'],
                'photo_id' => $item['photo_id'],
                'quantity' => $item['quantity'],
                'price_at_purchase' => $actualPrice, // Use database price
            ];
        }

        // 3. Use a Database Transaction to save everything
        try {
            DB::beginTransaction();

            $order = Order::create([
                'student_id' => $student->id,
                'parent_name' => $validated['name'],
                'parent_email' => $validated['email'],
                'parent_phone_number' => $validated['phone_number'],
                'total_price' => $actualTotalPrice, // Use recalculated price
                'status' => 'pending',
            ]);

            $order->orderItems()->createMany($orderItemsData);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: '.$e->getMessage());

            return back()->withInput()->withErrors(['cart' => 'Nem sikerült elmenteni a rendelést. Kérjük, próbálja újra.']);
        }

        // 6. Send email notification to admin (don't fail order if email fails)
        try {
            $admin = User::where('is_admin', true)->first();
            if ($admin) {
                Mail::to($admin)->send(new OrderReceived($order->load('student', 'orderItems.product', 'orderItems.photo')));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order notification email: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
            // Don't fail the order, just log the error
        }

        // 7. Clear the cart and redirect
        session()->forget('cart');
        session()->forget('student_access_key');

        return redirect()->route('order.success');
    }

    public function checkout()
    {
        $cart = session('cart');

        if (empty($cart) || empty($cart['items'])) {
            return redirect()->route('order.show', ['access_key' => session('student_access_key', 'missing')])
                ->withErrors(['products' => 'A kosár üres. Kérjük, válasszon termékeket.']);
        }

        return view('public.checkout', compact('cart'));
    }

    public function storeCart(StoreCartRequest $request)
    {
        // 1. Get validated data
        $validated = $request->validated();

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
                    return back()->withInput()->withErrors(['products' => 'Érvénytelen kép került kiválasztásra.']);
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
            return back()->withInput()->withErrors(['products' => 'Legalább egy terméket meg kell rendelnie mennyiséggel.']);
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
