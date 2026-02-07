@extends('layouts.app')

@section('content')
<h1>Tambah Pelanggan</h1>
<form action="{{ route('customers.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="phone" class="form-control">
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="address" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
</form>
@endsection
