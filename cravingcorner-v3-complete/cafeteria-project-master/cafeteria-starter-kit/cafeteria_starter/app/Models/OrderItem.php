<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'itemable_id', 'itemable_type', 'quantity', 'price', 'subtotal'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Resolves to either a FoodItem or a Beverage automatically.
    public function itemable()
    {
        return $this->morphTo();
    }
}
