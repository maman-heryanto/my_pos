@extends('layouts.app')

@section('title', 'Edit Pelanggan')

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
        <div class="col-md-8">
            <div class="main-card">
                <div class="card-header-gradient">
                    <h4 class="mb-0 font-weight-bold"><i class="fas fa-user-edit mr-2"></i> Edit Data Pelanggan</h4>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('customers.update', $customer) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-user text-muted"></i></span>
                                </div>
                                <input type="text" name="name" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" placeholder="Masukkan nama pelanggan" value="{{ $customer->name }}" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Nomor Telepon / WhatsApp</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-phone text-muted"></i></span>
                                </div>
                                <input type="number" name="phone" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" placeholder="Contoh: 08123456789" value="{{ $customer->phone }}">
                            </div>
                        </div>

                        <div class="form-group mb-5">
                            <label class="font-weight-bold text-muted">Alamat Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0; height: auto;"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                </div>
                                <textarea name="address" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0; height: 100px; padding-top: 15px;" placeholder="Masukkan alamat lengkap pelanggan">{{ $customer->address }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customers.index') }}" class="btn btn-light text-muted font-weight-bold py-3 px-4" style="border-radius: 12px;">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-gradient px-5">
                                <i class="fas fa-save mr-2"></i> Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
