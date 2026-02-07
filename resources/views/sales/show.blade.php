@extends('layouts.app')

@section('content')
<h1>Sale Details</h1>
<div class="card mb-3">
    <div class="card-body">
        <p><strong>Date:</strong> {{ $sale->date }}</p>
        <p><strong>Customer:</strong> {{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</p>
        <p><strong>Total Amount:</strong> {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
        <p><strong>Paid Amount:</strong> {{ number_format($sale->paid_amount, 0, ',', '.') }}</p>
        <p><strong>Change:</strong> {{ number_format($sale->paid_amount - $sale->total_amount, 0, ',', '.') }}</p>
        <p><strong>Payment Status:</strong> {{ ucfirst($sale->payment_status) }}</p>
    </div>
</div>

<h3>Items</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->details as $detail)
        <tr>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->quantity + 0 }} {{ $detail->product->unit }}</td>
            <td>{{ number_format($detail->price, 0, ',', '.') }}</td>
            <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('sales.index') }}" class="btn btn-secondary">Back</a>
<a href="{{ route('sales.print', $sale) }}" class="btn btn-primary" target="_blank">Print Struk</a>
@endsection
