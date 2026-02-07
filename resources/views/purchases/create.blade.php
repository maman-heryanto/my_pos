@extends('layouts.app')

@section('content')
<h1>Add Purchase</h1>
<form action="{{ route('purchases.store') }}" method="POST">
    @csrf
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control" required>
                @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label>Date</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>
    </div>

    <h4>Items</h4>
    <table class="table table-bordered" id="items_table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
                <th><button type="button" class="btn btn-sm btn-primary" onclick="addItem()">+</button></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th id="total_amount">0.00</th>
                <th></th>
            </tr>
        </tfoot>
    </table>

    <div class="mb-3">
        <label>Paid Amount</label>
        <input type="text" id="paid_amount_display" class="form-control" oninput="formatPaidAmount()" required>
        <input type="hidden" name="paid_amount" id="paid_amount" value="0">
    </div>

    <button type="submit" class="btn btn-success">Save Purchase</button>
</form>

@push('scripts')
<script>
    let products = @json($products);
    let rowIdx = 0;

    function addItem() {
        let options = products.map(p => `<option value="${p.id}" data-price="${p.price}">${p.name} (${p.unit})</option>`).join('');
        let html = `
            <tr id="row_${rowIdx}">
                <td>
                    <select name="details[${rowIdx}][product_id]" class="form-control product-select" onchange="updatePrice(${rowIdx})" required>
                        <option value="">Select Product</option>
                        ${options}
                    </select>
                </td>
                <td><input type="text" inputmode="decimal" pattern="[0-9]*[.,]?[0-9]*" name="details[${rowIdx}][quantity]" class="form-control qty-input" oninput="calculateRow(${rowIdx})" placeholder="0" required></td>
                <td><input type="text" class="form-control price-input" oninput="calculateRow(${rowIdx})" onchange="formatPriceInput(this)" required>
                    <input type="hidden" name="details[${rowIdx}][price]" class="price-hidden">
                </td>
                <td class="subtotal">0</td>
                <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(${rowIdx})">X</button></td>
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
        
        // For purchase, price is editable, so we just set default but allow change
        // We need to manage the display vs hidden value if we want formatted input
        // But for editable inputs, auto-formatting on type is complex.
        // Simplified approach: Just format standard price on load, but input remains raw number for editing ease?
        // User asked "samakan format". If input is number, it shows 150000. 
        // If I make it text, user has to type 150.000 or I auto-format.
        // Let's stick to simple text input for now, but user might type raw.
        // Better: Use number input for editable price, but user wanted "formatted". 
        // Showing formatted in editable input requires masking. 
        // Let's use the logic:
        
        let priceInput = document.querySelector(`#row_${idx} .price-input`);
        let priceHidden = document.querySelector(`#row_${idx} .price-hidden`);
        
        // Initial set formatted
        priceInput.value = new Intl.NumberFormat('id-ID').format(price); 
        priceHidden.value = price;
        calculateRow(idx);
    }

    function calculateRow(idx) {
        // Handle editable price input being text/number
        let qtyVal = document.querySelector(`#row_${idx} .qty-input`).value;
        let qty = parseFloat(qtyVal.replace(',', '.')) || 0;
        
        let priceInput = document.querySelector(`#row_${idx} .price-input`); // Readable input
        let priceHidden = document.querySelector(`#row_${idx} .price-hidden`);
        
        // If user manually types, we need to parse it. 
        // If user types "150.000", parse to 150000.
        // If user types "150000", parse to 150000.
        let rawPrice = priceInput.value.replace(/\./g, '').replace(',', '.');
        let price = parseFloat(rawPrice) || 0;
        
        // Update hidden for submission
        priceHidden.value = price;
        
        let subtotal = qty * price;
        document.querySelector(`#row_${idx} .subtotal`).innerText = new Intl.NumberFormat('id-ID').format(subtotal);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.qty-input').forEach(input => {
             // Iterate rows by finding parent tr
             let row = input.closest('tr');
             let idx = row.id.replace('row_', '');
             
             let qtyVal = row.querySelector('.qty-input').value;
             let qty = parseFloat(qtyVal.replace(',', '.')) || 0;
             
             // Get price from hidden or parsed input
             let priceInput = row.querySelector('.price-input');
             let rawPrice = priceInput.value.replace(/\./g, '').replace(',', '.');
             let price = parseFloat(rawPrice) || 0;
             
             total += qty * price;
        });
        document.getElementById('total_amount').innerText = new Intl.NumberFormat('id-ID').format(total);
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
