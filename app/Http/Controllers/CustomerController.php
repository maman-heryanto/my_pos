<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = \App\Models\Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);
        
        $customer = \App\Models\Customer::create($request->all());

        if ($request->wantsJson()) {
            return response()->json($customer);
        }

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(\App\Models\Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(\App\Models\Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, \App\Models\Customer $customer)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        $customer->update($request->all());
        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(\App\Models\Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
