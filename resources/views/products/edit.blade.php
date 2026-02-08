@extends('layouts.app')

@section('title', 'Edit Produk')

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
</style>
@endpush

@section('content')
<div class="container-fluid pt-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="main-card">
                <div class="card-header-gradient">
                    <h4 class="mb-0 font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Data Produk</h4>
                </div>
                <div class="card-body p-5">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4" style="border-radius: 12px;">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.update', $product) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted">Kode Produk <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-barcode text-muted"></i></span>
                                        </div>
                                        <input type="text" name="code" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" placeholder="Kode unik" value="{{ old('code', $product->code) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted">Nama Produk <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-cube text-muted"></i></span>
                                        </div>
                                        <input type="text" name="name" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" placeholder="Contoh: Beras Premium 5kg" value="{{ old('name', $product->name) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted">Harga Jual <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0; font-weight: bold;">Rp</span>
                                        </div>
                                        <input type="number" step="1" name="price" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" placeholder="0" value="{{ old('price', (int)$product->price) }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted">Stok Saat Ini <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-layer-group text-muted"></i></span>
                                        </div>
                                        <input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" name="stock" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" placeholder="0" value="{{ old('stock', $product->stock + 0) }}" required>
                                    </div>
                                    <small class="text-muted ml-2">Gunakan titik (.) untuk desimal. Contoh: 1.5</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted">Satuan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-ruler text-muted"></i></span>
                                        </div>
                                        <input type="text" name="unit" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" placeholder="Contoh: kg, pcs, sak" value="{{ old('unit', $product->unit) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-light text-muted font-weight-bold py-3 px-4" style="border-radius: 12px;">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-gradient px-5">
                                <i class="fas fa-save mr-2"></i> Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
