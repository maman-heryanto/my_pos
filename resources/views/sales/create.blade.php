@extends('layouts.app')

@section('content')
<h1>Penjualan Baru (POS)</h1>
<form action="{{ route('sales.store') }}" method="POST" id="pos-form">
    @csrf
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Pelanggan</label>
            <select name="customer_id" id="customer_select" class="form-control" onchange="showDebt()">
                <option value="" data-debt="0">Pelanggan Umum (Walk-in)</option>
                @foreach($customers as $customer)
                <option value="{{ $customer->id }}" data-debt="{{ $customer->debt }}">{{ $customer->name }}</option>
                @endforeach
            </select>
            <small id="debt_display" class="text-danger font-weight-bold" style="display:none;">Hutang saat ini: Rp <span id="debt_amount">0</span></small>
        </div>
        <div class="col-md-6">
            <label>Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
    </div>

    <h4>Keranjang Belanja</h4>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered" id="cart_table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total</th>
                        <th id="grand_total">0.00</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <button type="button" class="btn btn-primary" onclick="addRow()">+ Tambah Item</button>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Pembayaran</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Subtotal</label>
                        <input type="text" id="display_subtotal" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Potongan / Diskon (Rp)</label>
                        <input type="number" name="discount" id="discount" class="form-control" value="0" min="0" oninput="calculateGrandTotal()">
                    </div>
                    <div class="mb-3">
                        <label>Total Tagihan (Setelah Diskon)</label>
                        <input type="text" id="display_total" class="form-control font-weight-bold text-primary" readonly style="font-size: 1.2rem;">
                        <span id="grand_total" style="display:none;">0</span> 
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Bayar</label>
                        <input type="number" step="1" name="paid_amount" id="paid_amount" class="form-control" oninput="calculateChange()" required>
                    </div>
                    <div class="mb-3">
                        <label for="change_amount">Kembalian / Hutang</label>
                        <input type="text" id="change_amount" class="form-control" readonly>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Selesaikan Transaksi</button>
                </div>
            </div>
        </div>
    </div>
</form>

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
                    <td><input type="number" step="1" class="form-control price-input" readonly>
                        <input type="hidden" name="details[${rowIdx}][price]" class="price-hidden">
                    </td>
                    <td><span class="stock-display">0</span></td>
                    <td><input type="number" step="0.001" name="details[${rowIdx}][quantity]" class="form-control qty-input" oninput="calculateRow(${rowIdx})" required></td>
                    <td class="subtotal">0</td>
                    <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(${rowIdx})">X</button></td>
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
        let stock = selectedOption.getAttribute('data-stock') || 0;

        document.querySelector(`#row_${idx} .price-input`).value = price.toFixed(0);
        document.querySelector(`#row_${idx} .price-hidden`).value = price;
        document.querySelector(`#row_${idx} .stock-display`).innerText = stock;
        
        calculateRow(idx);
    }

    function calculateRow(idx) {
        let qty = parseFloat(document.querySelector(`#row_${idx} .qty-input`).value) || 0;
        let price = parseFloat(document.querySelector(`#row_${idx} .price-hidden`).value) || 0;
        let subtotal = qty * price;
        document.querySelector(`#row_${idx} .subtotal`).innerText = subtotal.toFixed(0);
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let subtotal = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            subtotal += parseFloat(el.innerText) || 0;
        });
        
        document.getElementById('display_subtotal').value = new Intl.NumberFormat('id-ID').format(subtotal);
        
        let discount = parseFloat(document.getElementById('discount').value) || 0;
        
        // Prevent discount > subtotal
        if (discount > subtotal) {
            discount = subtotal;
            document.getElementById('discount').value = subtotal;
        }

        let total = subtotal - discount;

        document.getElementById('grand_total').innerText = total.toFixed(0);
        document.getElementById('display_total').value = new Intl.NumberFormat('id-ID').format(total);
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
        
        document.getElementById('change_amount').value = change.toFixed(0);
        
        // Visual feedback
        if (change < 0) {
            document.getElementById('change_amount').classList.add('text-danger');
            document.getElementById('change_amount').classList.remove('text-success');
        } else {
            document.getElementById('change_amount').classList.add('text-success');
            document.getElementById('change_amount').classList.remove('text-danger');
        }
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
@endsection
