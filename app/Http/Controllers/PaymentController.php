<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Customer Payments (Sales)
        $queryCustomer = \App\Models\Payment::whereNotNull('sale_id')->with('sale.customer')->orderBy('date', 'desc');
        if ($request->customer_id) {
             $queryCustomer->whereHas('sale', function($q) use ($request) {
                 $q->where('customer_id', $request->customer_id);
             });
        }
        $customerPayments = $queryCustomer->get();

        // Supplier Payments (Purchases)
        $querySupplier = \App\Models\Payment::whereNotNull('purchase_id')->with('purchase.supplier')->orderBy('date', 'desc');
        if ($request->supplier_id) {
             $querySupplier->whereHas('purchase', function($q) use ($request) {
                 $q->where('supplier_id', $request->supplier_id);
             });
        }
        $supplierPayments = $querySupplier->get();

        $customers = \App\Models\Customer::with('sales')->get();
        $customersWithDebt = $customers->filter(function($customer) {
            return $customer->debt > 0;
        });

        $suppliers = \App\Models\Supplier::all();

        return view('payments.index', compact('customerPayments', 'supplierPayments', 'customers', 'suppliers', 'customersWithDebt'));
    }

    public function create()
    {
        $sales = \App\Models\Sale::where('payment_status', 'debt')->get();
        $purchases = \App\Models\Purchase::where('status', '!=', 'paid')->get();
        return view('payments.create', compact('sales', 'purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sale,purchase',
            'sale_id' => 'nullable|required_if:type,sale|exists:sales,id',
            'purchase_id' => 'nullable|required_if:type,purchase|exists:purchases,id',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            
            \App\Models\Payment::create([
                'sale_id' => $request->type == 'sale' ? $request->sale_id : null,
                'purchase_id' => $request->type == 'purchase' ? $request->purchase_id : null,
                'amount' => $request->amount,
                'date' => $request->date,
                'note' => $request->note,
            ]);

            if ($request->type == 'sale') {
                $sale = \App\Models\Sale::lockForUpdate()->find($request->sale_id);
                $sale->paid_amount += $request->amount;
                // Always update change_amount (negative means debt)
                $sale->change_amount = $sale->paid_amount - $sale->total_amount;

                if ($sale->paid_amount >= $sale->total_amount) {
                    $sale->payment_status = 'paid';
                }
                $sale->save();
            } elseif ($request->type == 'purchase') {
                $purchase = \App\Models\Purchase::lockForUpdate()->find($request->purchase_id);
                $purchase->paid_amount += $request->amount;
                if ($purchase->paid_amount >= $purchase->total_amount) {
                    $purchase->status = 'paid';
                } else {
                    $purchase->status = 'partial';
                }
                $purchase->save();
            }
        });

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(\App\Models\Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function edit(\App\Models\Payment $payment)
    {
        // Not implemented
    }

    public function update(Request $request, \App\Models\Payment $payment)
    {
        // Not implemented
    }

    public function destroy(\App\Models\Payment $payment)
    {
        // Should reverse the payment on Sale/Purchase?
        // Implementation left as exercise or if requested.
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
