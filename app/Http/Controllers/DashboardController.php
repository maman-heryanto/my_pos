<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stats Hari Ini
        $today = Carbon::today();
        
        $todaySales = Sale::whereDate('created_at', $today)->sum('total_amount');
        $todayPurchases = Purchase::whereDate('created_at', $today)->sum('total_amount');

        // 2. Keuangan (Hutang Piutang)
        // Piutang: Uang kita di luar (Pelanggan belum bayar)
        // Hitung manual sisa hutang (Total - Bayar) dari transaksi status 'debt'
        $receivables = Sale::where('payment_status', 'debt')->get()->sum(function($sale) {
            return $sale->total_amount - $sale->paid_amount;
        });

        // Hutang: Uang kita yang harus dibayar ke Supplier
        $payables = Purchase::where('status', '!=', 'paid')->get()->sum(function($purchase) {
            return $purchase->total_amount - $purchase->paid_amount;
        });

        // 3. Chart Data (7 Hari Terakhir)
        $chartData = [];
        $chartLabels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d/m');
            
            $total = Sale::whereDate('created_at', $date)->sum('total_amount');
            $chartData[] = $total;
        }

        return view('dashboard', compact(
            'todaySales', 
            'todayPurchases', 
            'receivables', 
            'payables',
            'chartLabels',
            'chartData'
        ));
    }
}
