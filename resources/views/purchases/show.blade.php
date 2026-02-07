@extends('layouts.app')

@section('content')
<h1>Purchase Details</h1>
<div class="card mb-3">
    <div class="card-body">
        <p><strong>Date:</strong> {{ $purchase->date }}</p>
        <p><strong>Supplier:</strong> {{ $purchase->supplier->name }}</p>
        <p><strong>Total Amount:</strong> {{ number_format($purchase->total_amount, 0, ',', '.') }}</p>
        <p><strong>Paid Amount:</strong> {{ number_format($purchase->paid_amount, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($purchase->status) }}</p>
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
        @foreach($purchase->details as $detail)
        <tr>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->quantity + 0 }} {{ $detail->product->unit }}</td>
            <td>{{ number_format($detail->price, 0, ',', '.') }}</td>
            <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back</a>
@endsection
