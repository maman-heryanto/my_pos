<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Sale::with('customer')->latest();
        
        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->start_date) {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        $sales = $query->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = \App\Models\Customer::with('sales')->get();
        $products = \App\Models\Product::where('stock', '>', 0)->get()->map(function($product) {
            $product->stock = (float) $product->stock;
            return $product;
        });
        return view('sales.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'date' => 'required|date',
            'details' => 'required|array',
            'details.*.product_id' => 'required|exists:products,id',
            'details.*.quantity' => 'required|numeric|min:0.001',
            'details.*.price' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
                $subtotalAmount = 0;
                
                // First pass: Calculate total and check stock
                foreach ($request->details as $detail) {
                    $subtotalAmount += $detail['quantity'] * $detail['price'];
                    
                    $product = \App\Models\Product::lockForUpdate()->find($detail['product_id']);
                    if ($product->stock < $detail['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}");
                    }
                }

                $discount = $request->discount ?? 0;
                if ($discount > $subtotalAmount) {
                     throw new \Exception("Discount cannot be greater than total amount.");
                }

                $totalAmount = $subtotalAmount - $discount;

                $changeAmount = $request->paid_amount - $totalAmount;
                $paymentStatus = ($request->paid_amount >= $totalAmount) ? 'paid' : 'debt';

                $sale = \App\Models\Sale::create([
                    'customer_id' => $request->customer_id,
                    'date' => $request->date,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $request->paid_amount,
                    'change_amount' => $changeAmount,
                    'payment_status' => $paymentStatus,
                    'discount' => $discount,
                ]);

                foreach ($request->details as $detail) {
                    $subtotal = $detail['quantity'] * $detail['price'];
                    \App\Models\SaleDetail::create([
                        'sale_id' => $sale->id,
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'price' => $detail['price'],
                        'subtotal' => $subtotal,
                    ]);

                    // Update Stock (Decrease)
                    $product = \App\Models\Product::find($detail['product_id']);
                    $product->decrement('stock', $detail['quantity']);
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully.');
    }

    public function show(\App\Models\Sale $sale)
    {
        $sale->load(['customer', 'details.product']);
        return view('sales.show', compact('sale'));
    }

    public function edit(\App\Models\Sale $sale)
    {
        $customers = \App\Models\Customer::with('sales')->get();
        return view('sales.edit', compact('sale', 'customers'));
    }

    public function update(Request $request, \App\Models\Sale $sale)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'date' => 'required|date',
            'note' => 'nullable|string' // Assuming we might add a note field later, or just simple fields
        ]);

        $sale->update([
            'customer_id' => $request->customer_id,
            'date' => $request->date,
        ]);

        return redirect()->route('sales.index')->with('success', 'Data Penjualan berhasil diperbarui (Hanya Header).');
    }

    public function destroy(\App\Models\Sale $sale)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($sale) {
            foreach ($sale->details as $detail) {
                // Reverse Stock (Increase)
                $product = \App\Models\Product::find($detail->product_id);
                if ($product) {
                    $product->increment('stock', $detail->quantity);
                }
            }
            $sale->delete();
        });
        
        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }

    public function print(\App\Models\Sale $sale)
    {
        return view('sales.print', compact('sale'));
    }
}
