@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Riwayat Pembayaran</h1>
    <div>
        <button type="button" class="btn btn-info mr-2" data-toggle="modal" data-target="#debtModal">
            <i class="fas fa-list"></i> Lihat Hutang Pelanggan
        </button>
        <a href="{{ route('payments.create') }}" class="btn btn-primary">Tambah Pembayaran</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('payments.index') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <label>Filter Pelanggan</label>
                <select name="customer_id" class="form-control">
                    <option value="">Semua Pelanggan</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label>Filter Supplier</label>
                <select name="supplier_id" class="form-control">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- Customer Payments -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">Pembayaran dari Pelanggan</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Jml (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customerPayments as $payment)
                        <tr>
                            <td>{{ date('d/m/y', strtotime($payment->date)) }}</td>
                            <td>
                                <small>
                                    {{ $payment->sale->customer ? $payment->sale->customer->name : 'General' }} <br>
                                    <span class="text-muted">#{{ $payment->sale_id }}</span>
                                </small>
                            </td>
                            <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-0" onclick="return confirm('Hapus?')">X</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Supplier Payments -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark">Pembayaran ke Supplier</div>
            <div class="card-body p-0">
                <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Supplier</th>
                            <th>Jml (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supplierPayments as $payment)
                        <tr>
                            <td>{{ date('d/m/y', strtotime($payment->date)) }}</td>
                            <td>
                                <small>
                                    {{ $payment->purchase->supplier->name }} <br>
                                    <span class="text-muted">#{{ $payment->purchase_id }}</span>
                                </small>
                            </td>
                            <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger py-0" onclick="return confirm('Hapus?')">X</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal Daftar Hutang -->
<div class="modal fade" id="debtModal" tabindex="-1" role="dialog" aria-labelledby="debtModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="debtModalLabel">Daftar Hutang Pelanggan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($customersWithDebt->isEmpty())
                    <p class="text-center">Tidak ada pelanggan dengan hutang.</p>
                @else
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th class="text-right">Sisa Hutang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalDebt = 0; @endphp
                            @foreach($customersWithDebt as $customer)
                            @php $totalDebt += $customer->debt; @endphp
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td class="text-right text-danger font-weight-bold">
                                    {{ number_format($customer->debt, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                            <tr class="bg-light font-weight-bold">
                                <td>Total Piutang</td>
                                <td class="text-right text-danger">{{ number_format($totalDebt, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">Buat Pembayaran</a>
            </div>
        </div>
    </div>
</div>
