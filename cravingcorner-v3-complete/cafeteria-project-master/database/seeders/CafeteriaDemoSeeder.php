<?php

namespace Database\Seeders;

use App\Models\Beverage;
use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Database\Seeder;

class CafeteriaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $pizza = Category::create(['name' => 'Pizza', 'type' => 'food', 'description' => 'Wood-fired pizzas.']);
        $sandwiches = Category::create(['name' => 'Sandwiches', 'type' => 'food', 'description' => 'Fresh sandwiches.']);
        $desserts = Category::create(['name' => 'Desserts', 'type' => 'food', 'description' => 'Sweet treats.']);

        $coffee = Category::create(['name' => 'Coffee', 'type' => 'beverage', 'description' => 'Hot and cold coffee.']);
        $juices = Category::create(['name' => 'Juices', 'type' => 'beverage', 'description' => 'Fresh juices.']);

        FoodItem::create(['category_id' => $pizza->id, 'name' => 'Margherita Pizza', 'description' => 'Classic tomato and mozzarella.', 'price' => 150, 'ingredients' => 'Tomato, mozzarella, basil', 'calories' => 800, 'spicy_level' => 0, 'available_quantity' => 20, 'preparation_time' => 15, 'status' => 'active']);
        FoodItem::create(['category_id' => $pizza->id, 'name' => 'Spicy Chicken Pizza', 'description' => 'Grilled chicken with chili flakes.', 'price' => 180, 'ingredients' => 'Chicken, mozzarella, chili, onions', 'calories' => 950, 'spicy_level' => 4, 'available_quantity' => 15, 'preparation_time' => 18, 'status' => 'active']);
        FoodItem::create(['category_id' => $sandwiches->id, 'name' => 'Chicken Sandwich', 'description' => 'Grilled chicken breast sandwich.', 'price' => 120, 'ingredients' => 'Chicken, lettuce, mayo, bread', 'calories' => 600, 'spicy_level' => 1, 'available_quantity' => 25, 'preparation_time' => 10, 'status' => 'active']);
        FoodItem::create(['category_id' => $sandwiches->id, 'name' => 'Beef Burger', 'description' => 'Juicy beef patty with cheese.', 'price' => 160, 'ingredients' => 'Beef, cheese, lettuce, tomato, bun', 'calories' => 850, 'spicy_level' => 1, 'available_quantity' => 18, 'preparation_time' => 12, 'status' => 'active']);
        FoodItem::create(['category_id' => $desserts->id, 'name' => 'Chocolate Cake', 'description' => 'Rich chocolate layer cake.', 'price' => 90, 'ingredients' => 'Chocolate, flour, sugar, eggs', 'calories' => 450, 'spicy_level' => 0, 'available_quantity' => 12, 'preparation_time' => 5, 'status' => 'active']);
        FoodItem::create(['category_id' => $desserts->id, 'name' => 'Cheesecake', 'description' => 'Creamy baked cheesecake.', 'price' => 100, 'ingredients' => 'Cream cheese, biscuit, sugar', 'calories' => 500, 'spicy_level' => 0, 'available_quantity' => 10, 'preparation_time' => 5, 'status' => 'active']);

        Beverage::create(['category_id' => $coffee->id, 'name' => 'Cappuccino', 'description' => 'Espresso with steamed milk foam.', 'price' => 80, 'size' => 'Regular', 'ingredients' => 'Espresso, milk', 'calories' => 120, 'temperature' => 'hot', 'available_quantity' => 30, 'status' => 'active']);
        Beverage::create(['category_id' => $coffee->id, 'name' => 'Iced Latte', 'description' => 'Espresso with cold milk over ice.', 'price' => 85, 'size' => 'Large', 'ingredients' => 'Espresso, milk, ice', 'calories' => 150, 'temperature' => 'cold', 'available_quantity' => 25, 'status' => 'active']);
        Beverage::create(['category_id' => $juices->id, 'name' => 'Fresh Orange Juice', 'description' => 'Freshly squeezed oranges.', 'price' => 60, 'size' => 'Regular', 'ingredients' => 'Orange', 'calories' => 110, 'temperature' => 'cold', 'available_quantity' => 20, 'status' => 'active']);
        Beverage::create(['category_id' => $juices->id, 'name' => 'Mango Juice', 'description' => 'Sweet mango blend.', 'price' => 65, 'size' => 'Regular', 'ingredients' => 'Mango, sugar', 'calories' => 140, 'temperature' => 'cold', 'available_quantity' => 20, 'status' => 'active']);
    }
}
