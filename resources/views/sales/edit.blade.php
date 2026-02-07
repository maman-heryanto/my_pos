@extends('layouts.app')

@section('content')
<h1>Edit Data Penjualan</h1>
<div class="alert alert-warning">
    <strong>Perhatian:</strong> Halaman ini hanya untuk mengedit data Customer dan Tanggal. Untuk mengubah detail barang, silakan hapus transaksi dan input ulang demi menjaga keakuratan stok.
</div>

<form action="{{ route('sales.update', $sale) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Pelanggan</label>
        <select name="customer_id" class="form-control">
            <option value="">Pelanggan Umum (Walk-in)</option>
            @foreach($customers as $customer)
            <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                {{ $customer->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="date" class="form-control" value="{{ $sale->date }}" required>
    </div>
    
    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
