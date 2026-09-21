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
        FoodItem::query()->delete();
        Beverage::query()->delete();
        Category::query()->delete();
        $pizza = Category::create(['name' => 'Pizza', 'type' => 'food', 'description' => 'Wood-fired pizzas.']);
        $sandwiches = Category::create(['name' => 'Sandwiches', 'type' => 'food', 'description' => 'Fresh sandwiches.']);
        $desserts = Category::create(['name' => 'Desserts', 'type' => 'food', 'description' => 'Sweet treats.']);
        $appetizers = Category::create(['name' => 'Appetizers', 'type' => 'food', 'description' => 'Crispy starters.']);
        $pasta = Category::create(['name' => 'Pasta', 'type' => 'food', 'description' => 'Italian pasta dishes.']);

        $coffee = Category::create(['name' => 'Coffee', 'type' => 'beverage', 'description' => 'Hot and cold coffee.']);
        $juices = Category::create(['name' => 'Juices', 'type' => 'beverage', 'description' => 'Fresh juices.']);
        $tea = Category::create(['name' => 'Tea', 'type' => 'beverage', 'description' => 'Herbal and flavored tea.']);
        $mocktails = Category::create(['name' => 'Mocktails', 'type' => 'beverage', 'description' => 'Refreshing iced mocktails.']);
        $foodItems = [
            ['category_id' => $pizza->id, 'name' => 'Margherita Pizza', 'description' => 'Classic tomato and mozzarella.', 'price' => 150, 'ingredients' => 'Tomato, mozzarella, basil', 'calories' => 800, 'spicy_level' => 0, 'available_quantity' => 20, 'preparation_time' => 15, 'status' => 'active'],
            ['category_id' => $pizza->id, 'name' => 'Spicy Chicken Pizza', 'description' => 'Grilled chicken with chili flakes.', 'price' => 180, 'ingredients' => 'Chicken, mozzarella, chili, onions', 'calories' => 950, 'spicy_level' => 4, 'available_quantity' => 15, 'preparation_time' => 18, 'status' => 'active'],
            ['category_id' => $sandwiches->id, 'name' => 'Chicken Sandwich', 'description' => 'Grilled chicken breast sandwich.', 'price' => 120, 'ingredients' => 'Chicken, lettuce, mayo, bread', 'calories' => 600, 'spicy_level' => 1, 'available_quantity' => 25, 'preparation_time' => 10, 'status' => 'active'],
            ['category_id' => $sandwiches->id, 'name' => 'Beef Burger', 'description' => 'Juicy beef patty with cheese.', 'price' => 160, 'ingredients' => 'Beef, cheese, lettuce, tomato, bun', 'calories' => 850, 'spicy_level' => 1, 'available_quantity' => 18, 'preparation_time' => 12, 'status' => 'active'],
            ['category_id' => $desserts->id, 'name' => 'Chocolate Cake', 'description' => 'Rich chocolate layer cake.', 'price' => 90, 'ingredients' => 'Chocolate, flour, sugar, eggs', 'calories' => 450, 'spicy_level' => 0, 'available_quantity' => 12, 'preparation_time' => 5, 'status' => 'active'],
            ['category_id' => $desserts->id, 'name' => 'Cheesecake', 'description' => 'Creamy baked cheesecake.', 'price' => 100, 'ingredients' => 'Cream cheese, biscuit, sugar', 'calories' => 500, 'spicy_level' => 0, 'available_quantity' => 10, 'preparation_time' => 5, 'status' => 'active'],
            ['category_id' => $pizza->id, 'name' => 'Ranch Chicken Pizza', 'description' => 'Pizza with creamy ranch sauce and chicken.', 'price' => 185, 'ingredients' => 'Chicken, ranch sauce, mozzarella, bell peppers', 'calories' => 920, 'spicy_level' => 1, 'available_quantity' => 14, 'preparation_time' => 16, 'status' => 'active'],
            ['category_id' => $pizza->id, 'name' => 'BBQ Meat Supreme', 'description' => 'Loaded with beef and smoky BBQ sauce.', 'price' => 195, 'ingredients' => 'Beef strips, BBQ sauce, mozzarella, onions', 'calories' => 980, 'spicy_level' => 2, 'available_quantity' => 12, 'preparation_time' => 20, 'status' => 'active'],
            ['category_id' => $sandwiches->id, 'name' => 'Zinger Supreme Sub', 'description' => 'Crispy spicy chicken strips in sub bread.', 'price' => 135, 'ingredients' => 'Crispy chicken, lettuce, spicy mayo, cheese', 'calories' => 750, 'spicy_level' => 3, 'available_quantity' => 22, 'preparation_time' => 12, 'status' => 'active'],
            ['category_id' => $sandwiches->id, 'name' => 'Philly Cheesesteak', 'description' => 'Sliced beef with melted cheese and mushrooms.', 'price' => 170, 'ingredients' => 'Beef slices, mushrooms, melted cheese, bell pepper', 'calories' => 820, 'spicy_level' => 1, 'available_quantity' => 15, 'preparation_time' => 15, 'status' => 'active'],
            ['category_id' => $pasta->id, 'name' => 'Fettuccine Alfredo', 'description' => 'Pasta in rich white cream sauce with mushrooms.', 'price' => 140, 'ingredients' => 'Pasta, cream, mushrooms, parmesan cheese', 'calories' => 700, 'spicy_level' => 0, 'available_quantity' => 20, 'preparation_time' => 14, 'status' => 'active'],
            ['category_id' => $pasta->id, 'name' => 'Spicy Arrabiata Pasta', 'description' => 'Pennette pasta in spicy tomato sauce.', 'price' => 115, 'ingredients' => 'Pasta, spicy tomato sauce, garlic, olives', 'calories' => 580, 'spicy_level' => 3, 'available_quantity' => 18, 'preparation_time' => 12, 'status' => 'active'],
            ['category_id' => $desserts->id, 'name' => 'Molten Chocolate Lava Cake', 'description' => 'Warm cake with flowing chocolate center.', 'price' => 110, 'ingredients' => 'Dark chocolate, butter, eggs, flour, cocoa', 'calories' => 620, 'spicy_level' => 0, 'available_quantity' => 15, 'preparation_time' => 8, 'status' => 'active'],
            ['category_id' => $appetizers->id, 'name' => 'Mozzarella Cheese Sticks', 'description' => 'Crispy fried cheese sticks with marinara.', 'price' => 75, 'ingredients' => 'Mozzarella, breadcrumbs, herbs, marinara dip', 'calories' => 480, 'spicy_level' => 0, 'available_quantity' => 25, 'preparation_time' => 8, 'status' => 'active'],
            ['category_id' => $appetizers->id, 'name' => 'Crispy French Fries', 'description' => 'Golden salted french fries.', 'price' => 50, 'ingredients' => 'Potatoes, salt, oil', 'calories' => 380, 'spicy_level' => 0, 'available_quantity' => 35, 'preparation_time' => 6, 'status' => 'active'],
        ];

        foreach ($foodItems as $item) {
            FoodItem::create($item);
        }

        $beverages = [
            ['category_id' => $coffee->id, 'name' => 'Cappuccino', 'description' => 'Espresso with steamed milk foam.', 'price' => 80, 'size' => 'Regular', 'ingredients' => 'Espresso, milk', 'calories' => 120, 'temperature' => 'hot', 'available_quantity' => 30, 'status' => 'active'],
            ['category_id' => $coffee->id, 'name' => 'Iced Latte', 'description' => 'Espresso with cold milk over ice.', 'price' => 85, 'size' => 'Large', 'ingredients' => 'Espresso, milk, ice', 'calories' => 150, 'temperature' => 'cold', 'available_quantity' => 25, 'status' => 'active'],
            ['category_id' => $juices->id, 'name' => 'Fresh Orange Juice', 'description' => 'Freshly squeezed oranges.', 'price' => 60, 'size' => 'Regular', 'ingredients' => 'Orange', 'calories' => 110, 'temperature' => 'cold', 'available_quantity' => 20, 'status' => 'active'],
            ['category_id' => $juices->id, 'name' => 'Mango Juice', 'description' => 'Sweet mango blend.', 'price' => 65, 'size' => 'Regular', 'ingredients' => 'Mango, sugar', 'calories' => 140, 'temperature' => 'cold', 'available_quantity' => 20, 'status' => 'active'],
            ['category_id' => $coffee->id, 'name' => 'Espresso Shot', 'description' => 'Strong single shot of concentrated coffee.', 'price' => 45, 'size' => 'Small', 'ingredients' => 'Coffee beans', 'calories' => 10, 'temperature' => 'hot', 'available_quantity' => 40, 'status' => 'active'],
            ['category_id' => $coffee->id, 'name' => 'Hot Spanish Latte', 'description' => 'Espresso with steamed milk and condensed milk.', 'price' => 95, 'size' => 'Regular', 'ingredients' => 'Espresso, milk, condensed milk', 'calories' => 220, 'temperature' => 'hot', 'available_quantity' => 25, 'status' => 'active'],
            ['category_id' => $coffee->id, 'name' => 'Iced Caramel Macchiato', 'description' => 'Vanilla flavored drink with espresso and caramel.', 'price' => 95, 'size' => 'Large', 'ingredients' => 'Espresso, milk, vanilla, caramel syrup, ice', 'calories' => 240, 'temperature' => 'cold', 'available_quantity' => 22, 'status' => 'active'],
            ['category_id' => $coffee->id, 'name' => 'French Coffee', 'description' => 'Smooth coffee blended with milk.', 'price' => 70, 'size' => 'Regular', 'ingredients' => 'Coffee, milk powder, French blend', 'calories' => 130, 'temperature' => 'hot', 'available_quantity' => 30, 'status' => 'active'],
            ['category_id' => $juices->id, 'name' => 'Strawberry Smoothie', 'description' => 'Blended fresh strawberries with milk and ice.', 'price' => 75, 'size' => 'Large', 'ingredients' => 'Strawberry, milk, sugar, ice', 'calories' => 180, 'temperature' => 'cold', 'available_quantity' => 18, 'status' => 'active'],
            ['category_id' => $juices->id, 'name' => 'Lemon Mint Juice', 'description' => 'Refreshing crushed lemon with fresh mint leaves.', 'price' => 60, 'size' => 'Regular', 'ingredients' => 'Lemon, fresh mint, water, sugar', 'calories' => 90, 'temperature' => 'cold', 'available_quantity' => 30, 'status' => 'active'],
            ['category_id' => $juices->id, 'name' => 'Avocado Honey Blend', 'description' => 'Rich avocado juice blended with pure honey and nuts.', 'price' => 105, 'size' => 'Large', 'ingredients' => 'Avocado, honey, milk, nuts', 'calories' => 310, 'temperature' => 'cold', 'available_quantity' => 15, 'status' => 'active'],
            ['category_id' => $tea->id, 'name' => 'Green Tea with Mint', 'description' => 'Healthy antioxidant green tea leaves with mint.', 'price' => 40, 'size' => 'Regular', 'ingredients' => 'Green tea leaves, fresh mint', 'calories' => 5, 'temperature' => 'hot', 'available_quantity' => 40, 'status' => 'active'],
            ['category_id' => $tea->id, 'name' => 'Peach Iced Tea', 'description' => 'Cold black tea infused with peach flavor.', 'price' => 65, 'size' => 'Large', 'ingredients' => 'Black tea, peach syrup, ice', 'calories' => 95, 'temperature' => 'cold', 'available_quantity' => 25, 'status' => 'active'],
            ['category_id' => $mocktails->id, 'name' => 'Blueberry Mojito', 'description' => 'Sparkling soda with blueberry puree, mint, and lime.', 'price' => 85, 'size' => 'Large', 'ingredients' => 'Soda, blueberry flavor, mint, lime, ice', 'calories' => 160, 'temperature' => 'cold', 'available_quantity' => 20, 'status' => 'active'],
            ['category_id' => $mocktails->id, 'name' => 'Passion Fruit Cooler', 'description' => 'Tropical passion fruit mixed with sprite and ice.', 'price' => 90, 'size' => 'Large', 'ingredients' => 'Passion fruit syrup, sprite, mint, ice', 'calories' => 175, 'temperature' => 'cold', 'available_quantity' => 18, 'status' => 'active'],
        ];

        foreach ($beverages as $bev) {
            Beverage::create($bev);
        }
    }
}