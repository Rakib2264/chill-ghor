<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([AdminUserSeeder::class]);

        if (Category::count() > 0) return;

        $categories = [
            ['slug' => 'rice',    'name' => 'ভাত ও বিরিয়ানি', 'emoji' => '🍛'],
            ['slug' => 'meat',    'name' => 'মাংস',            'emoji' => '🍖'],
            ['slug' => 'fish',    'name' => 'মাছ',             'emoji' => '🐟'],
            ['slug' => 'bread',   'name' => 'রুটি ও পরোটা',   'emoji' => '🫓'],
            ['slug' => 'snack',   'name' => 'নাস্তা',          'emoji' => '🥟'],
            ['slug' => 'drink',   'name' => 'পানীয়',          'emoji' => '🥤'],
            ['slug' => 'dessert', 'name' => 'ডেজার্ট',        'emoji' => '🍮'],
        ];

        foreach ($categories as $i => $c) {
            Category::create($c + ['sort_order' => $i]);
        }

        $cat = fn($slug) => Category::where('slug', $slug)->first()->id;

        $products = [
            ['slug' => 'kacchi-biryani', 'name' => 'কাচ্চি বিরিয়ানি', 'category_id' => $cat('rice'), 'price' => 320, 'old_price' => 380, 'image' => 'images/food/food-biryani.jpg', 'description' => 'খাঁটি ঘি ও মসলায় রান্না করা স্পেশাল কাচ্চি।', 'long_description' => 'ঢাকাইয়া স্টাইলে তৈরি কাচ্চি বিরিয়ানি — খাঁটি ঘি, জাফরান, এলাচ ও দারুচিনির ঘ্রাণে ভরপুর। সাথে পরিবেশন করা হয় বোরহানি ও সালাদ।', 'popular' => true, 'spicy' => true],
            ['slug' => 'chicken-biryani', 'name' => 'চিকেন বিরিয়ানি', 'category_id' => $cat('rice'), 'price' => 220, 'image' => 'images/food/food-biryani.jpg', 'description' => 'নরম মুরগি ও সুগন্ধি বাসমতী চাল।', 'long_description' => 'টেন্ডার মুরগির মাংস ও বাসমতী চাল দিয়ে তৈরি ক্লাসিক বিরিয়ানি।', 'popular' => true],
            ['slug' => 'beef-bhuna', 'name' => 'গরুর মাংস ভুনা', 'category_id' => $cat('meat'), 'price' => 280, 'image' => 'images/food/food-beef.jpg', 'description' => 'ঘরোয়া স্বাদে রান্না করা মসলাদার গরুর মাংস।', 'long_description' => 'দেশি মসলায় ধীরে রান্না করা গরুর মাংস ভুনা।', 'spicy' => true],
            ['slug' => 'ilish-shorshe', 'name' => 'ইলিশ সরিষা', 'category_id' => $cat('fish'), 'price' => 420, 'image' => 'images/food/food-hilsa.jpg', 'description' => 'পদ্মার ইলিশ ও খাঁটি সরিষার ঝোল।', 'long_description' => 'তাজা পদ্মার ইলিশ মাছ সরিষা বাটায় রান্না।', 'popular' => true],
            ['slug' => 'paratha', 'name' => 'পরোটা', 'category_id' => $cat('bread'), 'price' => 25, 'image' => 'images/food/food-paratha.jpg', 'description' => 'নরম, খাস্তা হাতে তৈরি পরোটা।', 'long_description' => 'ঘি ভাজা নরম খাস্তা পরোটা।'],
            ['slug' => 'chicken-kebab', 'name' => 'চিকেন কাবাব', 'category_id' => $cat('meat'), 'price' => 180, 'image' => 'images/food/food-kebab.jpg', 'description' => 'তন্দুরে গ্রিল করা মসলাদার কাবাব।', 'long_description' => 'দই ও মসলায় ম্যারিনেট করা চিকেন তন্দুর কাবাব।', 'popular' => true],
            ['slug' => 'mango-lassi', 'name' => 'ম্যাঙ্গো লাচ্ছি', 'category_id' => $cat('drink'), 'price' => 90, 'image' => 'images/food/food-lassi.jpg', 'description' => 'তাজা আম ও দইয়ের ঠান্ডা পানীয়।', 'long_description' => 'মৌসুমি পাকা আম ও খাঁটি দই দিয়ে তৈরি ক্রিমি লাচ্ছি।'],
            ['slug' => 'rosogolla', 'name' => 'রসগোল্লা', 'category_id' => $cat('dessert'), 'price' => 30, 'image' => 'images/food/food-rosogolla.jpg', 'description' => 'বাংলার ঐতিহ্যবাহী মিষ্টি (২ পিস)।', 'long_description' => 'নরম তুলতুলে দুধের ছানা ও রসে ডুবানো রসগোল্লা।'],
            ['slug' => 'fuchka', 'name' => 'ফুচকা', 'category_id' => $cat('snack'), 'price' => 80, 'image' => 'images/food/food-fuchka.jpg', 'description' => 'টক-ঝাল-মিষ্টি স্ট্রিট ফুড (১০ পিস)।', 'long_description' => 'মুচমুচে ফুচকার ভিতরে আলু-ছোলার পুর।', 'popular' => true, 'spicy' => true],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
