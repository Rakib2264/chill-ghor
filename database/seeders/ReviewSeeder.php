<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return;

        $samples = [
            ['rating' => 5, 'comment' => 'অসাধারণ স্বাদ! আবার অর্ডার করব।'],
            ['rating' => 4, 'comment' => 'খুব ভালো ছিল, মশলাটা পারফেক্ট।'],
            ['rating' => 5, 'comment' => 'একদম ঘরোয়া স্বাদ পেয়েছি।'],
        ];

        foreach (Product::take(6)->get() as $i => $product) {
            Review::updateOrCreate(
                ['product_id' => $product->id, 'user_id' => $user->id],
                array_merge($samples[$i % count($samples)], ['is_approved' => true])
            );
        }
    }
}
