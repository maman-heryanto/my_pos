@extends('layouts.app')

@section('title', 'Edit Pembelian')

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
        padding: 20px 25px;
    }
    .form-control-lg-custom {
        height: 50px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding: 0 20px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    .form-control-lg-custom:focus {
        border-color: #764ba2;
        box-shadow: 0 0 0 4px rgba(118, 75, 162, 0.1);
    }
    .btn-gradient {
        background: linear-gradient(to right, #667eea, #764ba2);
        border: none;
        color: white;
        border-radius: 12px;
        font-weight: 600;
        padding: 12px 30px;
        box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        background: linear-gradient(to right, #764ba2, #667eea);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(118, 75, 162, 0.6);
        color: white;
    }
    .alert-custom-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
        border-radius: 12px;
        border-left: 5px solid #ffc107;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="main-card">
                <div class="card-header-gradient">
                    <h4 class="mb-0 font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Data Pembelian</h4>
                </div>
                <div class="card-body p-5">
                    
                    <div class="alert alert-custom-warning mb-4 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle fa-2x mr-3 text-warning"></i>
                            <div>
                                <h5 class="alert-heading font-weight-bold mb-1">Perhatian</h5>
                                <p class="mb-0">Halaman ini hanya untuk mengedit data <strong>Supplier</strong> dan <strong>Tanggal</strong>. Untuk mengubah detail barang, silakan hapus transaksi ini dan input ulang demi menjaga keakuratan stok.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('purchases.update', $purchase) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Supplier</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-truck text-muted"></i></span>
                                </div>
                                <select name="supplier_id" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;">
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-5">
                            <label class="font-weight-bold text-muted">Tanggal Transaksi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="far fa-calendar-alt text-muted"></i></span>
                                </div>
                                <input type="date" name="date" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" value="{{ $purchase->date }}" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('purchases.index') }}" class="btn btn-light text-muted font-weight-bold py-3 px-4" style="border-radius: 12px;">
                                <i class="fas fa-arrow-left mr-2"></i> Batal / Kembali
                            </a>
                            <button type="submit" class="btn btn-gradient px-5">
                                <i class="fas fa-save mr-2"></i> Update Informasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
