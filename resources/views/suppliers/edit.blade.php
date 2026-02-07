@extends('layouts.app')

@section('content')
<h1>Edit Supplier</h1>
<form action="{{ route('suppliers.update', $supplier) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ $supplier->name }}" required>
    </div>
    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="phone" class="form-control" value="{{ $supplier->phone }}">
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="address" class="form-control">{{ $supplier->address }}</textarea>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
@endsection
