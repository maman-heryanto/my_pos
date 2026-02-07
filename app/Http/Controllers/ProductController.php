<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = \App\Models\Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:products',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'unit' => 'required',
        ]);
        
        \App\Models\Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show(\App\Models\Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(\App\Models\Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, \App\Models\Product $product)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:products,code,'.$product->id,
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'unit' => 'required',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(\App\Models\Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
