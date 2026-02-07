@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Laporan Penjualan</h1>
    <form action="{{ route('sales.index') }}" method="GET" class="d-flex">
        <select name="payment_status" class="form-control mr-2" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
            <option value="debt" {{ request('payment_status') == 'debt' ? 'selected' : '' }}>Hutang</option>
        </select>
        <a href="{{ route('sales.create') }}" class="btn btn-primary text-nowrap">Transaksi Baru</a>
    </form>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>No. Nota</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Dibayar</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
        <tr>
            <td>#{{ $sale->id }}</td>
            <td>{{ date('d-m-Y H:i', strtotime($sale->created_at)) }}</td>
            <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
            <td>{{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            <td>{{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
            <td>
                <span class="badge badge-{{ $sale->payment_status == 'paid' ? 'success' : 'danger' }}">
                    {{ ucfirst($sale->payment_status == 'paid' ? 'Lunas' : 'Hutang') }}
                </span>
            </td>
            <td>
                <a href="{{ route('sales.print', $sale) }}" class="btn btn-sm btn-secondary" target="_blank">Print</a>
                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-warning">Edit</a>
                <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info">Lihat</a>
                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus? Stok akan dikembalikan.')">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
