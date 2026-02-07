@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Laporan Pembelian (Stok Masuk)</h1>
    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Tambah Pembelian</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th>Total</th>
            <th>Dibayar</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchases as $purchase)
        <tr>
            <td>{{ $purchase->date }}</td>
            <td>{{ $purchase->supplier->name }}</td>
            <td>{{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
            <td>{{ number_format($purchase->paid_amount, 0, ',', '.') }}</td>
            <td>
                <span class="badge bg-{{ $purchase->status == 'paid' ? 'success' : ($purchase->status == 'partial' ? 'warning' : 'danger') }}">
                    {{ ucfirst($purchase->status == 'paid' ? 'Lunas' : ($purchase->status == 'partial' ? 'Sebagian' : 'Belum Lunas')) }}
                </span>
            </td>
            <td>
                <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning">Edit</a>
                <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info">Lihat</a>
                <form action="{{ route('purchases.destroy', $purchase) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus? Stok akan dikurangi kembali.')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
