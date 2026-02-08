@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif !important;
        background-color: #f4f6f9;
    }
    .main-card {
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: none;
        background: #fff;
        overflow: hidden;
        margin-top: 20px;
        transition: all 0.3s ease;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-bottom: none;
    }
    .card-title {
        font-weight: 700;
        font-size: 1.4rem;
        margin: 0;
        letter-spacing: 0.5px;
    }
    .table-hover tbody tr:hover {
        background-color: #fcfcfc;
        transform: scale(1.002);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        z-index: 10;
        position: relative;
        transition: all 0.2s ease;
    }
    .btn-action {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .badge-custom {
        padding: 8px 15px;
        border-radius: 30px;
        font-weight: 500;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .badge-paid {
        background-color: #e6fffa;
        color: #2c7a7b;
    }
    .badge-debt {
        background-color: #fff5f5;
        color: #c53030;
    }
    .search-input {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        font-size: 0.95rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .search-input:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-3">
    <div class="main-card">
        <div class="card-header-gradient d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title"><i class="fas fa-file-invoice-dollar mr-2"></i> Laporan Penjualan</h3>
                <p class="mb-0 text-white-50 mt-1" style="font-size: 0.9rem;">Kelola riwayat transaksi penjualan Anda</p>
            </div>
            <div>
                <a href="{{ route('sales.create') }}" class="btn btn-light text-primary font-weight-bold shadow-sm" style="border-radius: 12px; padding: 10px 20px;">
                    <i class="fas fa-plus mr-2"></i> Transaksi Baru
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <form action="{{ route('sales.index') }}" method="GET" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label class="font-weight-bold text-muted small">Status Pembayaran</label>
                        <select name="payment_status" class="form-control search-input" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                            <option value="debt" {{ request('payment_status') == 'debt' ? 'selected' : '' }}>Hutang (Unpaid)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold text-muted small">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control search-input" value="{{ request('start_date') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                        <label class="font-weight-bold text-muted small">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control search-input" value="{{ request('end_date') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-3">
                         @if(request('payment_status') || request('start_date') || request('end_date'))
                        <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-block search-input" style="border-radius: 12px; padding-top: 10px;">
                            <i class="fas fa-undo mr-2"></i> Reset Filter
                        </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover border-bottom mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="border-0 font-weight-bold py-3 pl-4">No. Nota</th>
                            <th class="border-0 font-weight-bold py-3">Tanggal & Waktu</th>
                            <th class="border-0 font-weight-bold py-3">Pelanggan</th>
                            <th class="border-0 font-weight-bold py-3 text-right">Total</th>
                            <th class="border-0 font-weight-bold py-3 text-right">Dibayar</th>
                            <th class="border-0 font-weight-bold py-3 text-center">Status</th>
                            <th class="border-0 font-weight-bold py-3 text-center pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td class="align-middle pl-4 font-weight-bold text-primary">#{{ $sale->id }}</td>
                            <td class="align-middle text-muted">
                                <i class="far fa-calendar-alt mr-1"></i> {{ date('d M Y', strtotime($sale->created_at)) }} <br>
                                <small class="text-xs text-muted"><i class="far fa-clock mr-1"></i> {{ date('H:i', strtotime($sale->created_at)) }}</small>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-secondary"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark">{{ $sale->customer ? $sale->customer->name : 'Walk-in Customer' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle text-right font-weight-bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            <td class="align-middle text-right text-muted">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
                            <td class="align-middle text-center">
                                <span class="badge badge-custom {{ $sale->payment_status == 'paid' ? 'badge-paid' : 'badge-debt' }}">
                                    {{ $sale->payment_status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                </span>
                            </td>
                            <td class="align-middle text-center pr-4">
                                <a href="{{ route('sales.print', $sale) }}" class="btn btn-action btn-light text-dark" target="_blank" title="Print Struk">
                                    <i class="fas fa-print"></i>
                                </a>
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-action btn-info text-white" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-danger text-white ml-1" onclick="return confirm('Yakin ingin menghapus data ini? Stok akan dikembalikan.')" title="Hapus Transaksi">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <img src="https://img.icons8.com/clouds/100/000000/nothing-found.png" alt="No data" class="mb-3 opacity-50"><br>
                                Belum ada data penjualan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination (if needed) -->
            <div class="mt-4 px-4">
                 {{-- $sales->links() --}}
            </div>
        </div>
    </div>
</div>
@endsection
