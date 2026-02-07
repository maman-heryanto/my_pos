<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class FishProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'code' => 'IKAN-001',
                'name' => 'Ikan Lele',
                'price' => 25000,
                'stock' => 0,
                'unit' => 'kg',
            ],
            [
                'code' => 'IKAN-002',
                'name' => 'Ikan Nila',
                'price' => 35000,
                'stock' => 0,
                'unit' => 'kg',
            ],
            [
                'code' => 'IKAN-003',
                'name' => 'Ikan Mas',
                'price' => 40000,
                'stock' => 0,
                'unit' => 'kg',
            ],
            [
                'code' => 'IKAN-004',
                'name' => 'Belut',
                'price' => 80000,
                'stock' => 0,
                'unit' => 'kg',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['code' => $product['code']],
                $product
            );
        }
    }
}
