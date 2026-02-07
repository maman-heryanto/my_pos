@extends('layouts.app')

@section('content')
<h1>Edit Produk</h1>
<form action="{{ route('products.update', $product) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Kode</label>
        <input type="text" name="code" class="form-control" value="{{ $product->code }}" required>
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" step="1" name="price" class="form-control" value="{{ (int)$product->price }}" required>
    </div>
    <div class="mb-3">
        <label>Stok</label>
        <input type="number" step="0.001" name="stock" class="form-control" value="{{ $product->stock }}" required>
    </div>
    <div class="mb-3">
        <label>Satuan</label>
        <input type="text" name="unit" class="form-control" value="{{ $product->unit }}" required>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
@endsection
