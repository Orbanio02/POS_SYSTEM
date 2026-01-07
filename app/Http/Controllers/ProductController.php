<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(15);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'sku'                 => 'nullable|string|max:255|unique:products,sku',
            'category'            => 'nullable|string|max:1000',
            'price'               => 'required|numeric|min:0',
            'stock_quantity'      => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'image'               => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'sku'                 => 'nullable|string|max:255|unique:products,sku,' . $product->id,
            'category'            => 'nullable|string|max:1000',
            'price'               => 'required|numeric|min:0',
            'stock_quantity'      => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'image'               => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Remove old image (only if it exists)
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // (No change requested here—keeping as-is)
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
