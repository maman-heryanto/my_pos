@extends('layouts.app')

@section('title', 'Detail Penjualan')

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
    .info-label {
        color: #8898aa;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #32325d;
    }
    .table-custom th {
        background-color: #f6f9fc;
        color: #8898aa;
        text-transform: uppercase;
        font-size: 0.8rem;
        border-top: none;
        border-bottom: 1px solid #e9ecef;
        padding: 15px;
    }
    .table-custom td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }
    .badge-status {
        padding: 8px 15px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .bg-gradient-info {
        background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;
    }
    .total-section {
        background-color: #f6f9fc;
        border-radius: 15px;
        padding: 20px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        <!-- Invoice Info -->
        <div class="col-lg-8">
            <div class="main-card mb-4">
                <div class="card-header-gradient d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title">Nota #{{ $sale->id }}</h3>
                        <p class="mb-0 text-white-50">{{ date('d F Y, H:i', strtotime($sale->created_at)) }}</p>
                    </div>
                     <span class="badge badge-status {{ $sale->payment_status == 'paid' ? 'badge-light text-success' : 'badge-light text-danger' }}">
                        {{ $sale->payment_status == 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom mb-0">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->details as $detail)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $detail->product->name }}</div>
                                        <small class="text-muted">{{ $detail->product->code }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ $detail->quantity + 0 }} {{ $detail->product->unit }}
                                    </td>
                                    <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                                    <td class="text-right font-weight-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary & Actions -->
        <div class="col-lg-4">
            <div class="main-card mb-4">
                <div class="card-body p-4">
                    <h5 class="font-weight-bold mb-4">Informasi Pelanggan</h5>
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-user text-primary fa-lg"></i>
                        </div>
                        <div>
                            <div class="info-label">Nama Pelanggan</div>
                            <div class="info-value">{{ $sale->customer ? $sale->customer->name : 'Walk-in Customer' }}</div>
                        </div>
                    </div>
                    
                    <hr class="my-4" style="border-top: 1px dashed #e9ecef;">
                    
                    <h5 class="font-weight-bold mb-3">Ringkasan Pembayaran</h5>
                    <div class="total-section">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Tagihan</span>
                            <span class="font-weight-bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Dibayar</span>
                            <span class="font-weight-bold text-success">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="font-weight-bold text-dark">Kembalian / Sisa</span>
                            <span class="font-weight-bold {{ ($sale->paid_amount - $sale->total_amount) < 0 ? 'text-danger' : 'text-primary' }}" style="font-size: 1.2rem;">
                                Rp {{ number_format($sale->paid_amount - $sale->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('sales.print', $sale) }}" target="_blank" class="btn btn-primary btn-block shadow-sm py-3 mb-2" style="border-radius: 12px;">
                            <i class="fas fa-print mr-2"></i> Cetak Struk
                        </a>
                        <a href="{{ route('sales.index') }}" class="btn btn-light btn-block text-muted py-3" style="border-radius: 12px;">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
