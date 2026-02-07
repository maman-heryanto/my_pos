@extends('layouts.app')

@section('content')
<h1>Edit Pelanggan</h1>
<form action="{{ route('customers.update', $customer) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ $customer->name }}" required>
    </div>
    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="address" class="form-control">{{ $customer->address }}</textarea>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
@endsection
