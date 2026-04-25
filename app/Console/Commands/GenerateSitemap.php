<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.xml file in public/';

    public function handle()
    {
        $this->info('Sitemap generate হচ্ছে...');

        $sitemap = Sitemap::create();

        // Static pages
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/menu')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/about')->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        $sitemap->add(Url::create('/contact')->setPriority(0.6)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // Dynamic: Categories
        Category::all()->each(function ($cat) use ($sitemap) {
            $sitemap->add(
                Url::create("/menu?category={$cat->slug}")
                    ->setLastModificationDate($cat->updated_at)
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        // Dynamic: Products
        Product::where('active', true)->each(function ($product) use ($sitemap) {
            $sitemap->add(
                Url::create("/menu/{$product->slug}")
                    ->setLastModificationDate($product->updated_at)
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            );
        });

        // ✅ public/sitemap.xml এ save করুন
        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ sitemap.xml সফলভাবে তৈরি হয়েছে → public/sitemap.xml');
    }
}
