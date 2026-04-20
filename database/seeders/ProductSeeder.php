<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cat = fn($slug) => Category::where('slug', $slug)->first()?->id;

        $products = [
            [
                'slug' => 'kacchi-biryani',
                'name' => 'কাচ্চি বিরিয়ানি',
                'category_id' => $cat('rice'),
                'price' => 320,
                'image' => 'images/food/food-biryani.jpg',
                'description' => 'খাঁটি ঘি ও মসলায় রান্না করা কাচ্চি বিরিয়ানি',
                'long_description' => 'ঢাকাইয়া স্টাইল কাচ্চি বিরিয়ানি, সাথে বোরহানি ও সালাদ পরিবেশন করা হয়',
                'popular' => true,
                'spicy' => true,
                'active' => true,
            ],
            [
                'slug' => 'beef-bhuna',
                'name' => 'গরুর মাংস ভুনা',
                'category_id' => $cat('meat'),
                'price' => 280,
                'image' => 'images/food/food-beef.jpg',
                'description' => 'দেশি মসলায় রান্না করা গরুর মাংস ভুনা',
                'long_description' => 'ধীরে রান্না করা মসলাদার বিফ ভুনা',
                'spicy' => true,
                'active' => true,
            ],
            [
                'slug' => 'ilish-shorshe',
                'name' => 'ইলিশ সরিষা',
                'category_id' => $cat('fish'),
                'price' => 420,
                'image' => 'images/food/food-hilsa.jpg',
                'description' => 'সরিষা বাটায় রান্না করা ইলিশ মাছ',
                'long_description' => 'তাজা পদ্মার ইলিশ মাছ সরিষা দিয়ে রান্না',
                'popular' => true,
                'active' => true,
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
