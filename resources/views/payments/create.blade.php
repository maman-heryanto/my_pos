@extends('layouts.app')

@section('content')
<h1>Tambah Pembayaran</h1>
<form action="{{ route('payments.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Tipe Pembayaran</label>
        <select name="type" id="type" class="form-control" onchange="toggleType()" required>
            <option value="sale">Terima Pembayaran dari Pelanggan (Pelunasan Hutang)</option>
            <option value="purchase">Bayar ke Supplier (Lunas Hutang)</option>
        </select>
    </div>

    <div class="mb-3" id="sale_group">
        <label>Pilih Penjualan (Hutang)</label>
        <select name="sale_id" id="sale_id" class="form-control @error('sale_id') is-invalid @enderror" onchange="updateAmount('sale')">
            <option value="" data-debt="0">Pilih Penjualan</option>
            @foreach($sales as $sale)
            <option value="{{ $sale->id }}" data-debt="{{ $sale->total_amount - $sale->paid_amount }}" {{ old('sale_id') == $sale->id ? 'selected' : '' }}>
                #{{ $sale->id }} - {{ $sale->customer ? $sale->customer->name : 'Walk-in' }} 
                (Total: {{ number_format($sale->total_amount, 0, ',', '.') }}, Bayar: {{ number_format($sale->paid_amount, 0, ',', '.') }}, Sisa: {{ number_format($sale->total_amount - $sale->paid_amount, 0, ',', '.') }})
            </option>
            @endforeach
        </select>
        @error('sale_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="mt-2 text-danger font-weight-bold" id="sale_debt_display" style="display:none;">
            Sisa Hutang: Rp <span id="sale_debt_amount">0</span>
        </div>
    </div>

    <div class="mb-3 d-none" id="purchase_group">
        <label>Pilih Pembelian (Hutang)</label>
        <select name="purchase_id" id="purchase_id" class="form-control @error('purchase_id') is-invalid @enderror" onchange="updateAmount('purchase')">
            <option value="" data-debt="0">Pilih Pembelian</option>
            @foreach($purchases as $purchase)
            <option value="{{ $purchase->id }}" data-debt="{{ $purchase->total_amount - $purchase->paid_amount }}" {{ old('purchase_id') == $purchase->id ? 'selected' : '' }}>
                #{{ $purchase->id }} - {{ $purchase->supplier->name }}
                (Total: {{ number_format($purchase->total_amount, 0, ',', '.') }}, Bayar: {{ number_format($purchase->paid_amount, 0, ',', '.') }}, Sisa: {{ number_format($purchase->total_amount - $purchase->paid_amount, 0, ',', '.') }})
            </option>
            @endforeach
        </select>
        @error('purchase_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="mt-2 text-danger font-weight-bold" id="purchase_debt_display" style="display:none;">
            Sisa Hutang: Rp <span id="purchase_debt_amount">0</span>
        </div>
    </div>

    <div class="mb-3">
        <label>Jumlah Bayar</label>
        <input type="text" id="amount_display" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" oninput="formatAmount()" required>
        <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">
        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
    </div>

    <div class="mb-3">
        <label>Catatan</label>
        <textarea name="note" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-success">Simpan Pembayaran</button>
</form>

@push('scripts')
<script>
    function toggleType() {
        let type = document.getElementById('type').value;
        if (type == 'sale') {
            document.getElementById('sale_group').classList.remove('d-none');
            document.getElementById('purchase_group').classList.add('d-none');
        } else {
            document.getElementById('sale_group').classList.add('d-none');
            document.getElementById('purchase_group').classList.remove('d-none');
        }
        // Reset amount
        document.getElementById('amount').value = '';
    }

    function updateAmount(type) {
        let select = document.getElementById(type + '_id');
        let selectedOption = select.options[select.selectedIndex];
        let debt = parseFloat(selectedOption.getAttribute('data-debt')) || 0;
        
        // Show remaining debt
        let display = document.getElementById(type + '_debt_display');
        let amountEl = document.getElementById(type + '_debt_amount');
        
        if (debt > 0) {
            display.style.display = 'block';
            amountEl.innerText = new Intl.NumberFormat('id-ID').format(debt);
            // Autofill amount
            document.getElementById('amount').value = debt;
            document.getElementById('amount_display').value = new Intl.NumberFormat('id-ID').format(debt);
        } else {
            display.style.display = 'none';
            document.getElementById('amount').value = '';
            document.getElementById('amount_display').value = '';
        }
    }

    function formatAmount() {
        let display = document.getElementById('amount_display');
        let hidden = document.getElementById('amount');
        
        let raw = display.value.replace(/\./g, '').replace(/[^0-9]/g, '');
        let val = parseFloat(raw) || 0;
        
        hidden.value = val;
        
        if (raw) {
            display.value = new Intl.NumberFormat('id-ID').format(val);
        } else {
             display.value = '';
        }
    }
</script>
@endpush
@endsection
