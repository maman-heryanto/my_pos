@extends('layouts.app')

@section('content')
<h1>Edit Data Pembelian</h1>
<div class="alert alert-warning">
    <strong>Perhatian:</strong> Halaman ini hanya untuk mengedit data Supplier dan Tanggal. Untuk mengubah detail barang, silakan hapus transaksi dan input ulang demi menjaga keakuratan stok.
</div>

<form action="{{ route('purchases.update', $purchase) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Supplier</label>
        <select name="supplier_id" class="form-control">
            @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                {{ $supplier->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="date" class="form-control" value="{{ $purchase->date }}" required>
    </div>
    
    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
