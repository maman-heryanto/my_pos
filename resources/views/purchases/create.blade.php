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
        <input type="number" step="0.01" name="paid_amount" class="form-control" required>
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
                <td><input type="number" step="0.001" name="details[${rowIdx}][quantity]" class="form-control qty-input" oninput="calculateRow(${rowIdx})" required></td>
                <td><input type="number" step="1" name="details[${rowIdx}][price]" class="form-control price-input" oninput="calculateRow(${rowIdx})" required></td>
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
        let priceInput = document.querySelector(`#row_${idx} .price-input`);
        let price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
        // priceInput.value = price.toFixed(0); // If valid
        // calculateRow(idx);
    }

    function calculateRow(idx) {
        let qty = parseFloat(document.querySelector(`#row_${idx} .qty-input`).value) || 0;
        let price = parseFloat(document.querySelector(`#row_${idx} .price-input`).value) || 0;
        let subtotal = qty * price;
        document.querySelector(`#row_${idx} .subtotal`).innerText = subtotal.toFixed(0);
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            total += parseFloat(el.innerText) || 0;
        });
        document.getElementById('total_amount').innerText = total.toFixed(0);
    }

    // Add one row by default
    addItem();
</script>
@endpush
@endsection
