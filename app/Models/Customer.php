<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getDebtAttribute()
    {
        return $this->sales->where('payment_status', 'debt')->sum(function($sale) {
            return $sale->total_amount - $sale->paid_amount;
        });
    }
}
