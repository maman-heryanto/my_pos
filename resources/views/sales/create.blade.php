@extends('layouts.app')

@section('title', 'Kasir Point of Sale')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif !important;
    }
    .content-wrapper {
        background: #f4f6f9;
    }
    .pos-card {
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: none;
        background: #fff;
        overflow: hidden;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    .pos-card:hover {
        box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .pos-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 20px 20px 0 0;
    }
    .pos-header h3 {
        margin: 0;
        font-weight: 600;
        font-size: 1.2rem;
    }
    .btn-gradient {
        background: linear-gradient(to right, #667eea, #764ba2);
        border: none;
        color: white;
        border-radius: 12px;
        font-weight: 600;
        padding: 12px 25px;
        box-shadow: 0 4px 15px rgba(118, 75, 162, 0.4);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        background: linear-gradient(to right, #764ba2, #667eea);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(118, 75, 162, 0.6);
        color: white;
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
    .total-display {
        background: linear-gradient(135deg, #2c3e50 0%, #000000 100%);
        color: #fff;
        padding: 25px;
        border-radius: 15px;
        text-align: right;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .total-label {
        font-size: 0.9rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .total-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 5px 0 0 0;
    }
    .table-custom th {
        background-color: #f8f9fa;
        border-top: none;
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .table-custom td {
        vertical-align: middle;
    }
    .badge-debt {
        background: #ffe3e3;
        color: #ff4757;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
<div class="row pt-3">
    <!-- Left Column: Cart -->
    <div class="col-lg-8">
        <div class="pos-card">
            <div class="pos-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-shopping-cart mr-2"></i> Transaksi Baru</h3>
                <span class="badge badge-light text-primary" style="font-size: 0.9rem;">{{ date('d F Y') }}</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('sales.store') }}" method="POST" id="pos-form">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-7">
                            <label class="text-muted mb-2">Pilih Pelanggan</label>
                            <select name="customer_id" id="customer_select" class="form-control form-control-lg-custom" onchange="showDebt()">
                                <option value="" data-debt="0">Pelanggan Umum (Walk-in)</option>
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" data-debt="{{ $customer->debt }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <div id="debt_display" class="mt-2" style="display:none;">
                                <span class="badge-debt"><i class="fas fa-exclamation-circle mr-1"></i> Hutang: Rp <span id="debt_amount">0</span></span>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="text-muted mb-2">Tanggal Transaksi</label>
                            <input type="date" name="date" class="form-control form-control-lg-custom" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-custom table-hover" id="cart_table">
                            <thead>
                                <tr>
                                    <th style="width: 35%;">Produk</th>
                                    <th style="width: 20%;">Harga</th>
                                    <th style="width: 10%;">Stok</th>
                                    <th style="width: 15%;">Qty</th>
                                    <th style="width: 15%;">Subtotal</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-outline-primary btn-block p-3 mt-3" style="border-radius: 12px; border-style: dashed; border-width: 2px;" onclick="addRow()">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Baris Produk
                    </button>
            </div>
        </div>
    </div>

    <!-- Right Column: Payment -->
    <div class="col-lg-4">
        <div class="pos-card">
            <div class="card-body p-4">
                <div class="total-display">
                    <div class="total-label">Total Tagihan</div>
                    <div class="total-value">Rp <span id="grand_total_display">0</span></div>
                    <span id="grand_total" style="display:none;">0</span> 
                </div>

                <div class="form-group mb-4">
                    <label class="text-muted font-weight-bold">Subtotal</label>
                    <input type="text" id="display_subtotal" class="form-control form-control-lg-custom" readonly style="background: #fff; font-weight: 600;">
                </div>

                <div class="form-group mb-4">
                    <label class="text-muted font-weight-bold">Potongan / Diskon</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-tag text-muted"></i></span>
                        </div>
                        <input type="number" name="discount" id="discount" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0;" value="0" min="0" oninput="calculateGrandTotal()">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="text-muted font-weight-bold">Jumlah Bayar</label>
                     <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0" style="border-radius: 12px 0 0 12px; border-color: #e0e0e0;"><i class="fas fa-wallet text-muted"></i></span>
                        </div>
                        <input type="text" id="paid_amount_display" class="form-control form-control-lg-custom border-left-0" style="border-radius: 0 12px 12px 0; font-size: 1.2rem; font-weight: bold; color: #2ecc71;" oninput="formatPaidAmount()" required placeholder="0">
                        <input type="hidden" name="paid_amount" id="paid_amount" value="0">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="change_amount" class="text-muted font-weight-bold">Kembalian / Menjadi Hutang</label>
                    <input type="text" id="change_amount" class="form-control form-control-lg-custom" readonly style="background: #f8f9fa; font-weight: 700;">
                </div>

                <button type="submit" class="btn btn-gradient btn-block py-3">
                    <i class="fas fa-check-circle mr-2"></i> SELESAIKAN TRANSAKSI
                </button>
                </form> <!-- Close Form -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    let products = @json($products);
    let rowIdx = 0;
    // ... existing scripts ...

    // Debugging
    console.log('Products loaded:', products);
    
    if (!Array.isArray(products) || products.length === 0) {
        // alert('Peringatan...'); // SweetAlert is better
    }

    document.getElementById('pos-form').addEventListener('submit', function(e) {
        let customerId = document.getElementById('customer_select').value;
        let total = parseFloat(document.getElementById('grand_total').innerText) || 0;
        let paid = parseFloat(document.getElementById('paid_amount').value) || 0;

        // If Walk-in (empty customer_id) AND Debt (Paid < Total)
        if (!customerId && paid < total) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Pelanggan Umum Tidak Boleh Hutang',
                text: "Silakan masukkan nama pelanggan untuk mencatat hutang ini.",
                input: 'text',
                inputPlaceholder: 'Nama Pelanggan Baru',
                showCancelButton: true,
                confirmButtonText: 'Simpan & Lanjutkan',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama pelanggan harus diisi!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    let newName = result.value;
                    createCustomerAndSubmit(newName);
                }
            });
        }
    });

    function createCustomerAndSubmit(name) {
        fetch("{{ route('customers.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ name: name })
        })
        .then(response => response.json())
        .then(data => {
            if (data.id) {
                // Add to select and select it
                let select = document.getElementById('customer_select');
                let option = new Option(data.name, data.id, true, true);
                option.setAttribute('data-debt', 0);
                select.add(option, undefined); // Add to end
                select.value = data.id;
                
                // Show success toast
                Swal.fire({
                    icon: 'success',
                    title: 'Pelanggan Dibuat',
                    text: 'Melanjutkan transaksi...',
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => {
                    document.getElementById('pos-form').submit();
                });
            } else {
                Swal.fire('Error', 'Gagal membuat pelanggan', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        });
    }

    // ... existing functions (addRow, etc) ...


    function addRow() {
        try {
            let options = products.map(p => `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">${p.name} (${p.unit}) - Stok: ${p.stock}</option>`).join('');
            
            let html = `
                <tr id="row_${rowIdx}">
                    <td>
                        <select name="details[${rowIdx}][product_id]" class="form-control product-select" onchange="updateProduct(${rowIdx})" required>
                            <option value="">Pilih Produk</option>
                            ${options}
                        </select>
                    </td>
                    <td><input type="text" class="form-control price-input" readonly style="background-color: #f8f9fa;">
                        <input type="hidden" name="details[${rowIdx}][price]" class="price-hidden">
                    </td>
                    <td><span class="stock-display badge badge-info">0</span></td>
                    <td><input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" name="details[${rowIdx}][quantity]" class="form-control qty-input text-center font-weight-bold" oninput="calculateRow(${rowIdx})" placeholder="0" required></td>
                    <td class="subtotal font-weight-bold text-right">0</td>
                    <td><button type="button" class="btn btn-sm btn-outline-danger btn-block" onclick="removeRow(${rowIdx})"><i class="fas fa-trash"></i></button></td>
                </tr>
            `;
            document.querySelector('#cart_table tbody').insertAdjacentHTML('beforeend', html);
            rowIdx++;
        } catch (e) {
            console.error('Error adding row:', e);
            alert('Gagal menambah baris: ' + e.message);
        }
    }

    function removeRow(idx) {
        document.getElementById(`row_${idx}`).remove();
        calculateGrandTotal();
    }

    function updateProduct(idx) {
        let select = document.querySelector(`#row_${idx} .product-select`);
        let selectedOption = select.options[select.selectedIndex];
        let price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        let stock = parseFloat(selectedOption.getAttribute('data-stock')) || 0; // Parse float to remove trailing zeros

        document.querySelector(`#row_${idx} .price-input`).value = new Intl.NumberFormat('id-ID').format(price);
        document.querySelector(`#row_${idx} .price-hidden`).value = price;
        document.querySelector(`#row_${idx} .stock-display`).innerText = stock; // Display clean number
        
        calculateRow(idx);
    }

    function calculateRow(idx) {
        let qtyVal = document.querySelector(`#row_${idx} .qty-input`).value;
        let qty = parseFloat(qtyVal.replace(',', '.')) || 0;
        let price = parseFloat(document.querySelector(`#row_${idx} .price-hidden`).value) || 0;
        let subtotal = qty * price;
        document.querySelector(`#row_${idx} .subtotal`).innerText = new Intl.NumberFormat('id-ID').format(subtotal);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let subtotal = 0;
        document.querySelectorAll(`#cart_table tbody tr`).forEach(row => {
             // Re-calculate based on hidden values to be safe, or parse the formatted subtotal text
             // Better to recalculate from inputs to avoid parsing errors
             let rowId = row.id.replace('row_', '');
             let qtyVal = row.querySelector('.qty-input').value;
             let qty = parseFloat(qtyVal.replace(',', '.')) || 0;
             let price = parseFloat(row.querySelector('.price-hidden').value) || 0;
             subtotal += qty * price;
        });
        
        document.getElementById('display_subtotal').value = new Intl.NumberFormat('id-ID').format(subtotal);
        
        let discount = parseFloat(document.getElementById('discount').value) || 0;
        
        // Prevent discount > subtotal
        if (discount > subtotal) {
            discount = subtotal;
            document.getElementById('discount').value = subtotal;
        }

        let total = subtotal - discount;

        document.getElementById('grand_total').innerText = total; // Keep raw for calculation
        document.getElementById('grand_total_display').innerText = new Intl.NumberFormat('id-ID').format(total); // Display formatted
        calculateChange();
    }

    function calculateChange() {
        let total = parseFloat(document.getElementById('grand_total').innerText) || 0;
        let paid = parseFloat(document.getElementById('paid_amount').value) || 0;
        let change = paid - total;
        
        let label = change >= 0 ? "Kembalian" : "Hutang";
        let labelEl = document.querySelector('label[for="change_amount"]');
        if (labelEl) {
            labelEl.innerText = label; 
        } 
        // Or just update the input
        
        document.getElementById('change_amount').value = new Intl.NumberFormat('id-ID').format(change);
        
        // Visual feedback
        if (change < 0) {
            document.getElementById('change_amount').classList.add('text-danger');
            document.getElementById('change_amount').classList.remove('text-success');
        } else {
            document.getElementById('change_amount').classList.add('text-success');
            document.getElementById('change_amount').classList.remove('text-danger');
        }
    }

    function formatPaidAmount() {
        let display = document.getElementById('paid_amount_display');
        let hidden = document.getElementById('paid_amount');
        
        // Remove non-numeric except comma/dot? actually just keep numbers
        // ID format: 150.000
        let raw = display.value.replace(/\./g, '').replace(/[^0-9]/g, '');
        let val = parseFloat(raw) || 0;
        
        hidden.value = val;
        
        // Only format if there is a value to avoid weird cursor if clearing
        if (raw) {
            display.value = new Intl.NumberFormat('id-ID').format(val);
        } else {
             display.value = '';
        }

        calculateChange();
    }

    // Initialize with one row
    addRow();

    function showDebt() {
        let select = document.getElementById('customer_select');
        let selectedOption = select.options[select.selectedIndex];
        let debt = parseFloat(selectedOption.getAttribute('data-debt')) || 0;
        
        let display = document.getElementById('debt_display');
        let amount = document.getElementById('debt_amount');
        
        if (debt > 0) {
            display.style.display = 'block';
            amount.innerText = new Intl.NumberFormat('id-ID').format(debt);
        } else {
            display.style.display = 'none';
        }
    }
</script>
@endpush

