<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Using raw SQL for MySQL to avoid doctrine/dbal dependency for column change
        DB::statement('ALTER TABLE products MODIFY price BIGINT');
        
        DB::statement('ALTER TABLE purchases MODIFY total_amount BIGINT');
        DB::statement('ALTER TABLE purchases MODIFY paid_amount BIGINT');
        
        DB::statement('ALTER TABLE purchase_details MODIFY price BIGINT');
        DB::statement('ALTER TABLE purchase_details MODIFY subtotal BIGINT');
        
        DB::statement('ALTER TABLE sales MODIFY total_amount BIGINT');
        DB::statement('ALTER TABLE sales MODIFY paid_amount BIGINT');
        DB::statement('ALTER TABLE sales MODIFY change_amount BIGINT');
        
        DB::statement('ALTER TABLE sale_details MODIFY price BIGINT');
        DB::statement('ALTER TABLE sale_details MODIFY subtotal BIGINT');
        
        DB::statement('ALTER TABLE payments MODIFY amount BIGINT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to decimal(15,2)
        DB::statement('ALTER TABLE products MODIFY price DECIMAL(15,2)');
        
        DB::statement('ALTER TABLE purchases MODIFY total_amount DECIMAL(15,2)');
        DB::statement('ALTER TABLE purchases MODIFY paid_amount DECIMAL(15,2)');
        
        DB::statement('ALTER TABLE purchase_details MODIFY price DECIMAL(15,2)');
        DB::statement('ALTER TABLE purchase_details MODIFY subtotal DECIMAL(15,2)');
        
        DB::statement('ALTER TABLE sales MODIFY total_amount DECIMAL(15,2)');
        DB::statement('ALTER TABLE sales MODIFY paid_amount DECIMAL(15,2)');
        DB::statement('ALTER TABLE sales MODIFY change_amount DECIMAL(15,2)');
        
        DB::statement('ALTER TABLE sale_details MODIFY price DECIMAL(15,2)');
        DB::statement('ALTER TABLE sale_details MODIFY subtotal DECIMAL(15,2)');
        
        DB::statement('ALTER TABLE payments MODIFY amount DECIMAL(15,2)');
    }
};
