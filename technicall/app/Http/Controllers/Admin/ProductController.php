<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request; // Added for file deletion
use Illuminate\Support\Facades\Log;      // Added for logging
use Illuminate\Support\Facades\Storage;  // Added to catch specific SQL errors

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $path = $request->file('image')->store('products', 'public');

            Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'image_path' => $path,
            ]);

            toast()->success('Product created successfully.')->push();

            return redirect()->route('products.index');
        } catch (\Exception $e) {
            Log::error('Product creation failed: '.$e->getMessage());
            toast()->danger('Error creating product. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image is optional on update
        ]);

        try {
            $data = $validated;

            if ($request->hasFile('image')) {
                // 1. Delete old image
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                // 2. Store new image
                $data['image_path'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            toast()->success('Product updated successfully.')->push();

            return redirect()->route('products.index');
        } catch (\Exception $e) {
            Log::error("Product update failed (ID: {$product->id}): ".$e->getMessage());
            toast()->danger('Error updating product. Please try again.')->push();

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $productName = $product->name;
            $imagePath = $product->image_path;

            // 1. Delete from database
            $product->delete();

            // 2. If DB delete succeeded, delete file
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            toast()->success("Product '{$productName}' deleted successfully.")->push();
        } catch (QueryException $e) {
            // Check for foreign key constraint violation (error code 1451)
            if ($e->errorInfo[1] == 1451) {
                Log::warning("Failed to delete product (ID: {$product->id}) due to existing orders.");
                toast()->danger("Cannot delete '{$product->name}'. It is linked to existing orders.")->push();
            } else {
                Log::error("Product deletion failed (ID: {$product->id}): ".$e->getMessage());
                toast()->danger('An unknown database error occurred.')->push();
            }
        } catch (\Exception $e) {
            // Catch other errors (e.g., file system)
            Log::error("Product deletion failed (ID: {$product->id}): ".$e->getMessage());
            toast()->danger('An error occurred while deleting the product.')->push();
        }

        return redirect()->route('products.index');
    }
}
