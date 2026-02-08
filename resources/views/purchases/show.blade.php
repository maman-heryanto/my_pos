@extends('layouts.app')

@section('title', 'Detail Pembelian')

@push('styles')
<style>
    .main-card {
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: none;
        background: #fff;
        overflow: hidden;
        margin-top: 20px;
    }
    .card-header-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
    }
    .info-label {
        font-weight: 600;
        color: #7f8c8d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .info-value {
        font-weight: 700;
        color: #2c3e50;
        font-size: 1.1rem;
    }
    .table-custom th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .amount-display {
        font-weight: 700;
        color: #2ecc71;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-3">
    <div class="main-card">
        <div class="card-header-gradient d-flex justify-content-between align-items-center">
            <h4 class="mb-0 font-weight-bold"><i class="fas fa-file-invoice mr-2"></i> Detail Transaksi Pembelian</h4>
            <span class="badge badge-light text-primary px-3 py-2 shadow-sm" style="font-size: 1rem;">
                {{ date('d M Y', strtotime($purchase->date)) }}
            </span>
        </div>
        <div class="card-body p-5">
            <div class="row mb-5">
                <div class="col-md-4">
                    <div class="p-4 bg-light rounded shadow-sm h-100">
                        <div class="info-label"><i class="fas fa-truck mr-2"></i> Supplier</div>
                        <div class="info-value mb-3">{{ $purchase->supplier->name }}</div>
                        
                        <div class="info-label"><i class="fas fa-map-marker-alt mr-2"></i> Alamat</div>
                        <div class="text-muted">{{ $purchase->supplier->address ?? '-' }}</div>
                        <div class="text-muted mt-1"><i class="fas fa-phone mr-1"></i> {{ $purchase->supplier->phone ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Spacer or additional info -->
                </div>
                <div class="col-md-4 text-md-right">
                    <div class="info-label mb-2">Status Pembayaran</div>
                    <span class="badge trigger-tooltip bg-{{ $purchase->status == 'paid' ? 'success' : ($purchase->status == 'partial' ? 'warning' : 'danger') }} {{ $purchase->status == 'partial' ? 'text-dark' : 'text-white' }} px-3 py-2 mb-3" style="font-size: 1rem;">
                        {{ ucfirst($purchase->status == 'paid' ? 'Lunas' : ($purchase->status == 'partial' ? 'Sebagian' : 'Belum Lunas')) }}
                    </span>
                    
                    <div class="mt-4">
                        <div class="info-label">Total Tagihan</div>
                        <div class="display-4 font-weight-bold text-dark" style="font-size: 2rem;">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="mt-2">
                        <span class="text-muted">Sudah Dibayar: </span>
                        <span class="font-weight-bold text-success">Rp {{ number_format($purchase->paid_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-boxes mr-2"></i> Rincian Barang</h5>
            <div class="table-responsive">
                <table class="table table-custom table-hover">
                    <thead>
                        <tr>
                            <th class="pl-4 py-3">Produk</th>
                            <th class="text-center py-3">Jumlah</th>
                            <th class="text-right py-3">Harga Satuan</th>
                            <th class="text-right py-3 pr-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchase->details as $detail)
                        <tr>
                            <td class="pl-4 align-middle">
                                <div class="font-weight-bold">{{ $detail->product->name }}</div>
                                <div class="small text-muted">{{ $detail->product->code }}</div>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge badge-light border">{{ $detail->quantity + 0 }} {{ $detail->product->unit }}</span>
                            </td>
                            <td class="text-right align-middle">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                            <td class="text-right align-middle pr-4 font-weight-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold py-3 text-uppercase text-muted">Total Akhir</td>
                            <td class="text-right font-weight-bold py-3 pr-4" style="font-size: 1.1rem;">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="d-flex justify-content-between mt-5">
                <a href="{{ route('purchases.index') }}" class="btn btn-light text-muted font-weight-bold py-3 px-4 shadow-sm" style="border-radius: 12px;">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
                
                @if($purchase->status != 'paid')
                <button type="button" class="btn btn-gradient px-5 shadow-lg" disabled title="Fitur pelunasan belum tersedia">
                    <i class="fas fa-money-bill-wave mr-2"></i> Lunasi Sekarang
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
