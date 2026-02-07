<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchases = \App\Models\Purchase::with('supplier')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = \App\Models\Supplier::all();
        $products = \App\Models\Product::all()->map(function($product) {
            $product->stock = (float) $product->stock;
            return $product;
        });
        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|numeric|min:0.001',
            'details.*.price' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $totalAmount = 0;
            foreach ($request->details as $detail) {
                $totalAmount += $detail['quantity'] * $detail['price'];
            }

            $status = 'unpaid';
            if ($request->paid_amount >= $totalAmount) {
                $status = 'paid';
            } elseif ($request->paid_amount > 0) {
                $status = 'partial';
            }

            $purchase = \App\Models\Purchase::create([
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'total_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount,
                'status' => $status,
            ]);

            foreach ($request->details as $detail) {
                $subtotal = $detail['quantity'] * $detail['price'];
                \App\Models\PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'subtotal' => $subtotal,
                ]);

                // Update Stock (Increase)
                $product = \App\Models\Product::find($detail['product_id']);
                $product->increment('stock', $detail['quantity']);
            }
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded successfully.');
    }

    public function show(\App\Models\Purchase $purchase)
    {
        $purchase->load(['supplier', 'details.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(\App\Models\Purchase $purchase)
    {
        $suppliers = \App\Models\Supplier::all();
        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    public function update(Request $request, \App\Models\Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
        ]);

        $purchase->update([
            'supplier_id' => $request->supplier_id,
            'date' => $request->date,
        ]);

        return redirect()->route('purchases.index')->with('success', 'Data Pembelian berhasil diperbarui (Hanya Header).');
    }

    public function destroy(\App\Models\Purchase $purchase)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($purchase) {
            foreach ($purchase->details as $detail) {
                // Reverse Stock (Decrease)
                $product = \App\Models\Product::find($detail->product_id);
                if ($product) {
                    $product->decrement('stock', $detail->quantity);
                }
            }
            $purchase->delete();
        });
        
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}
