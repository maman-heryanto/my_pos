@extends('layouts.app')

@section('content')
<h1>Tambah Produk</h1>
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Kode</label>
        <input type="text" name="code" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" step="1" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Stok Awal</label>
        <input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" name="stock" class="form-control" placeholder="0" required>
        <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal. Contoh: 1.5</small>
    </div>
    <div class="mb-3">
        <label>Satuan</label>
        <input type="text" name="unit" class="form-control" placeholder="e.g. kg, pcs" value="kg" required>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@endsection
