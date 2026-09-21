<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'ingredients',
        'calories', 'spicy_level', 'available_quantity', 'preparation_time',
        'image', 'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->morphMany(OrderItem::class, 'itemable');
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'itemable');
    }
    public function reviews()
{
    return $this->morphMany(\App\Models\Review::class, 'itemable');
}

}
