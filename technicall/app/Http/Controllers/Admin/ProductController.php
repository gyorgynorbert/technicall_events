<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        try {
            Product::create($validated);

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
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        try {
            $product->update($validated);

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
            $product->delete();

            toast()->success("Product '{$productName}' deleted successfully.")->push();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                Log::warning("Failed to delete product (ID: {$product->id}) due to existing orders.");
                toast()->danger("Cannot delete '{$product->name}'. It is linked to existing orders.")->push();
            } else {
                Log::error("Product deletion failed (ID: {$product->id}): ".$e->getMessage());
                toast()->danger('An unknown database error occurred.')->push();
            }
        } catch (\Exception $e) {
            Log::error("Product deletion failed (ID: {$product->id}): ".$e->getMessage());
            toast()->danger('An error occurred while deleting the product.')->push();
        }

        return redirect()->route('products.index');
    }
}
