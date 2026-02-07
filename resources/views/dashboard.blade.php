@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1>Dashboard POS</h1>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Penjualan Hari Ini</div>
            <div class="card-body">
                <h4 class="card-title">Rp {{ number_format($todaySales, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Pembelian Hari Ini</div>
            <div class="card-body">
                <h4 class="card-title">Rp {{ number_format($todayPurchases, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
            <div class="card-header">Piutang (Pelanggan)</div>
            <div class="card-body">
                <h4 class="card-title">Rp {{ number_format($receivables, 0, ',', '.') }}</h4>
                <small>Uang yang belum dibayar pelanggan</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header text-dark">Hutang (Supplier)</div>
            <div class="card-body text-dark">
                <h4 class="card-title">Rp {{ number_format($payables, 0, ',', '.') }}</h4>
                <small>Uang yang harus kita bayar</small>
            </div>
        </div>
    </div>
</div>

<!-- Chart Row -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Grafik Penjualan 7 Hari Terakhir</div>
            <div class="card-body">
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Cards -->
<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">POS (Kasir)</h5>
                <p class="card-text">Buat transaksi penjualan baru.</p>
                <a href="{{ route('sales.create') }}" class="btn btn-light">Go to POS</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Barang Masuk</h5>
                <p class="card-text">Input stok dari supplier.</p>
                <a href="{{ route('purchases.create') }}" class="btn btn-light">Add Purchase</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Produk</h5>
                <p class="card-text">Kelola data produk dan stok.</p>
                <a href="{{ route('products.index') }}" class="btn btn-light">Manage Products</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Laporan Penjualan</h5>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">View Sales</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Laporan Pembelian</h5>
                <a href="{{ route('purchases.index') }}" class="btn btn-secondary">View Purchases</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title">Pembayaran / Hutang</h5>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">Manage Payments</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: @json($chartData),
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush
@endsection
