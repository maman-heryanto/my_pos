@extends('layouts.app')

@section('title', 'Daftar Produk')

@push('styles')
<style>
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
    .badge-stock {
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    .text-price {
        color: #2ecc71;
        font-weight: 700;
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-3">
    <div class="main-card">
        <div class="card-header-gradient d-flex justify-content-between align-items-center">
            <div>
                <h3 class="card-title"><i class="fas fa-box mr-2"></i> Daftar Produk</h3>
                <p class="mb-0 text-white-50 mt-1" style="font-size: 0.9rem;">Kelola inventaris dan stok produk</p>
            </div>
            <div>
                <a href="{{ route('products.create') }}" class="btn btn-light text-primary font-weight-bold shadow-sm" style="border-radius: 12px; padding: 10px 20px;">
                    <i class="fas fa-plus mr-2"></i> Tambah Produk
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover border-bottom mb-0">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="border-0 font-weight-bold py-3 pl-4">Kode / Nama Produk</th>
                            <th class="border-0 font-weight-bold py-3">Harga Jual</th>
                            <th class="border-0 font-weight-bold py-3 text-center">Stok Saat Ini</th>
                            <th class="border-0 font-weight-bold py-3 text-center pr-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="align-middle pl-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 45px; height: 45px;">
                                        <i class="fas fa-cube text-secondary" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark" style="font-size: 1.05rem;">{{ $product->name }}</div>
                                        <div class="small text-muted"><i class="fas fa-barcode mr-1"></i> {{ $product->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="text-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </td>
                            <td class="align-middle text-center">
                                <span class="badge badge-light border badge-stock">
                                    {{ $product->stock + 0 }} <span class="text-muted small ml-1">{{ $product->unit }}</span>
                                </span>
                            </td>
                            <td class="align-middle text-center pr-4">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-action btn-warning text-white" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-danger text-white ml-1" onclick="return confirm('Hapus Produk?')" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <img src="https://img.icons8.com/clouds/100/000000/nothing-found.png" alt="No data" class="mb-3 opacity-50"><br>
                                Belum ada data produk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
