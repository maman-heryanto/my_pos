@extends('layouts.app')

@section('title', 'Tambah Pembelian')

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
    .table-custom th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .total-display {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        padding: 20px;
        border-radius: 15px;
        text-align: right;
        box-shadow: 0 4px 15px rgba(44, 62, 80, 0.3);
    }
</style>
@endpush

@section('content')
<div class="container-fluid pt-3">
    <div class="main-card">
        <div class="card-header-gradient">
            <h4 class="mb-0 font-weight-bold"><i class="fas fa-cart-plus mr-2"></i> Input Pembelian Baru</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('purchases.store') }}" method="POST">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Supplier</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;"><i class="fas fa-truck text-muted"></i></span>
                                </div>
                                <select name="supplier_id" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" required>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold text-muted">Tanggal Transaksi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;"><i class="far fa-calendar-alt text-muted"></i></span>
                                </div>
                                <input type="date" name="date" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="font-weight-bold text-dark mb-3"><i class="fas fa-list mr-2"></i> Daftar Barang</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-custom table-hover" id="items_table">
                        <thead>
                            <tr>
                                <th style="width: 35%;">Produk</th>
                                <th style="width: 15%;">Qty</th>
                                <th style="width: 20%;">Harga Beli</th>
                                <th style="width: 20%;">Subtotal</th>
                                <th style="width: 10%;"><button type="button" class="btn btn-sm btn-primary btn-block rounded-pill shadow-sm" onclick="addItem()"><i class="fas fa-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="total-display mb-4">
                            <small class="text-white-50 text-uppercase font-weight-bold">Total Pembelian</small>
                            <h2 class="mb-0 font-weight-bold" id="total_display_text">Rp 0</h2>
                            <p class="mb-0 mt-1 text-white-50" style="font-size: 0.9rem;">Total yang harus dibayar ke supplier</p>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted">Jumlah Dibayar (DP/Lunas)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; font-weight: bold;">Rp</span>
                                </div>
                                <input type="text" id="paid_amount_display" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0; font-weight: bold; color: #2ecc71;" oninput="formatPaidAmount()" placeholder="0" required>
                                <input type="hidden" name="paid_amount" id="paid_amount" value="0">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-gradient btn-block btn-lg shadow-lg">
                            <i class="fas fa-save mr-2"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let products = @json($products);
    let rowIdx = 0;

    function addItem() {
        let options = products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} (${p.unit})</option>`).join('');
        let html = `
            <tr id="row_${rowIdx}">
                <td>
                    <select name="details[${rowIdx}][product_id]" class="form-control product-select shadow-sm border-0" onchange="updatePrice(${rowIdx})" required style="border-radius: 8px;">
                        <option value="">Pilih Produk</option>
                        ${options}
                    </select>
                </td>
                <td><input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" name="details[${rowIdx}][quantity]" class="form-control qty-input shadow-sm border-0" oninput="calculateRow(${rowIdx})" placeholder="0" required style="border-radius: 8px;"></td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text border-0 bg-transparent text-muted" style="font-size: 0.8rem;">Rp</span></div>
                        <input type="text" class="form-control price-input shadow-sm border-0" oninput="calculateRow(${rowIdx})" onchange="formatPriceInput(this)" required style="border-radius: 8px;">
                        <input type="hidden" name="details[${rowIdx}][price]" class="price-hidden">
                    </div>
                </td>
                <td class="align-middle font-weight-bold text-dark subtotal" style="font-size: 1.1rem;">0</td>
                <td><button type="button" class="btn btn-sm btn-danger rounded-circle shadow-sm" onclick="removeRow(${rowIdx})" style="width: 32px; height: 32px;"><i class="fas fa-trash"></i></button></td>
            </tr>
        `;
        document.querySelector('#items_table tbody').insertAdjacentHTML('beforeend', html);
        rowIdx++;
    }

    function removeRow(idx) {
        document.getElementById(`row_${idx}`).remove();
        calculateTotal();
    }

    function updatePrice(idx) {
        let select = document.querySelector(`#row_${idx} .product-select`);
        let price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
        
        let priceInput = document.querySelector(`#row_${idx} .price-input`);
        let priceHidden = document.querySelector(`#row_${idx} .price-hidden`);
        
        priceInput.value = new Intl.NumberFormat('id-ID').format(price); 
        priceHidden.value = price;
        calculateRow(idx);
    }

    function calculateRow(idx) {
        let qtyVal = document.querySelector(`#row_${idx} .qty-input`).value;
        let qty = parseFloat(qtyVal.replace(',', '.')) || 0;
        
        let priceInput = document.querySelector(`#row_${idx} .price-input`); 
        let priceHidden = document.querySelector(`#row_${idx} .price-hidden`);
        
        let rawPrice = priceInput.value.replace(/\./g, '').replace(',', '.');
        let price = parseFloat(rawPrice) || 0;
        
        priceHidden.value = price;
        
        let subtotal = qty * price;
        document.querySelector(`#row_${idx} .subtotal`).innerText = new Intl.NumberFormat('id-ID').format(subtotal);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
             let row = input.closest('tr');
             
             let qtyVal = row.querySelector('.qty-input').value;
             let qty = parseFloat(qtyVal.replace(',', '.')) || 0;
             
             let priceInput = row.querySelector('.price-input');
             let rawPrice = priceInput.value.replace(/\./g, '').replace(',', '.');
             let price = parseFloat(rawPrice) || 0;
             
             total += qty * price;
        });
        document.getElementById('total_display_text').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    function formatPaidAmount() {
        let display = document.getElementById('paid_amount_display');
        let hidden = document.getElementById('paid_amount');
        
        let raw = display.value.replace(/\./g, '').replace(/[^0-9]/g, '');
        let val = parseFloat(raw) || 0;
        
        hidden.value = val;
        
        if (raw) {
            display.value = new Intl.NumberFormat('id-ID').format(val);
        } else {
             display.value = '';
        }
    }

    function formatPriceInput(input) {
        let raw = input.value.replace(/\./g, '').replace(/[^0-9]/g, '');
        let val = parseFloat(raw) || 0;
        if (val > 0) {
            input.value = new Intl.NumberFormat('id-ID').format(val);
        }
    }

    // Add one row by default
    addItem();
</script>
@endpush
@endsection
