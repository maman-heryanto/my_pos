@extends('layouts.app')

@section('content')
<h1>Edit Produk</h1>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Kode</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $product->code) }}" required>
            </div>
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" step="1" name="price" class="form-control" value="{{ old('price', (int)$product->price) }}" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" name="stock" class="form-control" value="{{ old('stock', $product->stock + 0) }}" required>
                <small class="form-text text-muted">Gunakan titik (.) sebagai pemisah desimal. Contoh: 1.5</small>
            </div>
            <div class="form-group">
                <label>Satuan</label>
                <input type="text" name="unit" class="form-control" value="{{ old('unit', $product->unit) }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
