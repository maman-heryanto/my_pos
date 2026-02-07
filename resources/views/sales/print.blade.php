<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan #{{ $sale->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 58mm; /* Standard thermal paper width */
            margin: 0;
            padding: 5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .border-bottom { border-bottom: 1px dashed #000; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 2px 0; }
        .bold { font-weight: bold; }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="text-center">
        <h3 style="margin: 0;">R3 Jaya</h3>
        <p style="margin: 0;">LELE BOHAY</p>
        <p style="margin: 0;">Pasar Purwadadi </p>
        <p style="margin: 0;">Subang Jawa Barat</p>
        <p style="margin: 0;">Telp: -</p>
    </div>
    <br>
    <div class="border-bottom"></div>
    <table>
        <tr>
            <td>No. Nota</td>
            <td class="text-right">#{{ $sale->id }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="text-right">{{ date('d/m/Y H:i', strtotime($sale->created_at)) }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td class="text-right">{{ $sale->customer ? $sale->customer->name : 'Umum' }}</td>
        </tr>
    </table>
    <div class="border-bottom"></div>
    <br>
    <table>
        @foreach($sale->details as $detail)
        <tr>
            <td colspan="2">{{ $detail->product->name }}</td>
        </tr>
        <tr>
            <td>{{ $detail->quantity + 0 }} {{ $detail->product->unit }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>
    <br>
    <div class="border-bottom"></div>
    <table>
        <tr>
            <td class="bold">Subtotal</td>
            <td class="text-right">{{ number_format($sale->total_amount + $sale->discount, 0, ',', '.') }}</td>
        </tr>
        @if($sale->discount > 0)
        <tr>
            <td>Diskon</td>
            <td class="text-right">-{{ number_format($sale->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="bold">Total</td>
            <td class="text-right bold">{{ number_format($sale->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="text-right">{{ number_format($sale->paid_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>{{ $sale->payment_status == 'debt' ? 'Kurang Bayar' : 'Kembali' }}</td>
            <td class="text-right">{{ number_format(abs($sale->paid_amount - $sale->total_amount), 0, ',', '.') }}</td>
        </tr>
    </table>
    <br>
    <div class="text-center">
        <p>Terima Kasih atas Kunjungan Anda</p>
    </div>

    <button class="no-print" onclick="window.print()" style="margin-top: 20px; width: 100%; padding: 10px;">Print Struk</button>
    <a href="{{ route('sales.create') }}" class="no-print" style="display: block; text-align: center; margin-top: 10px;">Kembali ke Kasir</a>
</body>
</html>
