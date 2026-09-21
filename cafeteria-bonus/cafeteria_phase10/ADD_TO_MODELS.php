<?php
// Add this method inside BOTH app/Models/FoodItem.php AND app/Models/Beverage.php
// (they already have a similar favorites() morphMany — add this right next to it):

public function reviews()
{
    return $this->morphMany(\App\Models\Review::class, 'itemable');
}
