@extends('layouts.app')

@section('content')
<h1>Payment Details</h1>
<div class="card">
    <div class="card-body">
        <p><strong>Date:</strong> {{ $payment->date }}</p>
        <p><strong>Amount:</strong> {{ number_format($payment->amount, 0, ',', '.') }}</p>
        <p><strong>Note:</strong> {{ $payment->note }}</p>
        @if($payment->sale_id)
            <p><strong>Related Sale:</strong> <a href="{{ route('sales.show', $payment->sale_id) }}">#{{ $payment->sale_id }}</a></p>
        @elseif($payment->purchase_id)
            <p><strong>Related Purchase:</strong> <a href="{{ route('purchases.show', $payment->purchase_id) }}">#{{ $payment->purchase_id }}</a></p>
        @endif
    </div>
</div>
<br>
<a href="{{ route('payments.index') }}" class="btn btn-secondary">Back</a>
@endsection
