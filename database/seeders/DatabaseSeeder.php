<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Suppliers
        $supplier1 = \App\Models\Supplier::create([
            'name' => 'PT. Sumber Rezeki',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 1, Jakarta',
        ]);
        
        $supplier2 = \App\Models\Supplier::create([
            'name' => 'CV. Tani Makmur',
            'phone' => '089876543210',
            'address' => 'Jl. Raya Bogor KM 20',
        ]);

        // Customers
        $customer1 = \App\Models\Customer::create([
            'name' => 'Budi Santoso',
            'phone' => '08111222333',
            'address' => 'Perum Indah Blok A1',
        ]);

        $customer2 = \App\Models\Customer::create([
            'name' => 'Siti Aminah',
            'phone' => '08222333444',
            'address' => 'Cluster Melati No. 5',
        ]);

        // Products
        $product1 = \App\Models\Product::create([
            'code' => 'P001',
            'name' => 'Beras Pandan Wangi',
            'price' => 15000,
            'stock' => 0,
            'unit' => 'kg',
        ]);

        $product2 = \App\Models\Product::create([
            'code' => 'P002',
            'name' => 'Gula Pasir',
            'price' => 12500,
            'stock' => 0,
            'unit' => 'kg',
        ]);

        $product3 = \App\Models\Product::create([
            'code' => 'P003',
            'name' => 'Minyak Goreng',
            'price' => 18000,
            'stock' => 0,
            'unit' => 'liter',
        ]);

        // Purchase (Barang Masuk)
        // Purchase 1: Lunas
        $purchase1 = \App\Models\Purchase::create([
            'supplier_id' => $supplier1->id,
            'date' => now()->subDays(5),
            'total_amount' => 1500000,
            'paid_amount' => 1500000,
            'status' => 'paid',
        ]);
        
        \App\Models\PurchaseDetail::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $product1->id,
            'quantity' => 100, // 100 kg
            'price' => 12000, // Harga beli
            'subtotal' => 1200000,
        ]);
        $product1->increment('stock', 100);

        \App\Models\PurchaseDetail::create([
            'purchase_id' => $purchase1->id,
            'product_id' => $product2->id,
            'quantity' => 30, // 30 kg
            'price' => 10000, // Harga beli
            'subtotal' => 300000,
        ]);
        $product2->increment('stock', 30);

        // Purchase 2: Hutang (Unpaid)
        $purchase2 = \App\Models\Purchase::create([
            'supplier_id' => $supplier2->id,
            'date' => now()->subDays(2),
            'total_amount' => 900000,
            'paid_amount' => 0,
            'status' => 'unpaid',
        ]);
        
        \App\Models\PurchaseDetail::create([
            'purchase_id' => $purchase2->id,
            'product_id' => $product3->id,
            'quantity' => 60, // 60 liter
            'price' => 15000, // Harga beli
            'subtotal' => 900000,
        ]);
        $product3->increment('stock', 60);

        // Sales (Penjualan)
        // Sale 1: Tunai
        $sale1 = \App\Models\Sale::create([
            'customer_id' => null, // Walk-in
            'date' => now()->subDays(1),
            'total_amount' => 37500,
            'paid_amount' => 50000,
            'change_amount' => 12500,
            'payment_status' => 'paid',
        ]);

        \App\Models\SaleDetail::create([
            'sale_id' => $sale1->id,
            'product_id' => $product1->id,
            'quantity' => 2.5, // 2.5 kg
            'price' => 15000,
            'subtotal' => 37500,
        ]);
        $product1->decrement('stock', 2.5);

        // Sale 2: Hutang
        $sale2 = \App\Models\Sale::create([
            'customer_id' => $customer1->id,
            'date' => now(),
            'total_amount' => 180000,
            'paid_amount' => 50000,
            'change_amount' => 0,
            'payment_status' => 'debt',
        ]);

        \App\Models\SaleDetail::create([
            'sale_id' => $sale2->id,
            'product_id' => $product3->id,
            'quantity' => 10, // 10 liter
            'price' => 18000,
            'subtotal' => 180000,
        ]);
        $product3->decrement('stock', 10);
    }
}
