<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (Category::count() > 0) return;

        $categories = [
            ['slug' => 'rice', 'name' => 'ভাত ও বিরিয়ানি', 'emoji' => '🍛'],
            ['slug' => 'meat', 'name' => 'মাংস', 'emoji' => '🍖'],
            ['slug' => 'fish', 'name' => 'মাছ', 'emoji' => '🐟'],
            ['slug' => 'bread', 'name' => 'রুটি ও পরোটা', 'emoji' => '🫓'],
            ['slug' => 'snack', 'name' => 'নাস্তা', 'emoji' => '🥟'],
            ['slug' => 'drink', 'name' => 'পানীয়', 'emoji' => '🥤'],
        ];

        foreach ($categories as $i => $c) {
            Category::create($c + ['sort_order' => $i]);
        }
    }
}
