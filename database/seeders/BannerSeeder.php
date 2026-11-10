<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Complete Poultry Farm Equipment Solutions',
                'description' => 'Reliable systems for broiler, layer and breeder houses.',
                'button_text' => 'Explore products',
                'button_url' => '/products',
                'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=1400&q=80',
                'display_order' => 1,
                'status' => true,
            ],
            [
                'title' => 'Modern climate and automation systems',
                'description' => 'Keep your farm efficient, safe and fully monitored.',
                'button_text' => 'Learn more',
                'button_url' => '/about-us',
                'image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=1400&q=80',
                'display_order' => 2,
                'status' => true,
            ],
            [
                'title' => 'Trusted by poultry farmers',
                'description' => 'Quality equipment and service support for lasting performance.',
                'button_text' => 'Request quote',
                'button_url' => '/contact-us',
                'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=1400&q=80',
                'display_order' => 3,
                'status' => true,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::updateOrCreate(
                ['title' => $banner['title']],
                $banner
            );
        }
    }
}
