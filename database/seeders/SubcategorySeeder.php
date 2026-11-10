<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'automatic-pan-feeding-systems' => [
                'Broiler Feeding Systems',
                'Layer Feeding Systems',
                'Breeder Feeding Systems',
                'Feed Pans & Spare Parts',
            ],
            'drinking-systems' => [
                'Nipple Drinking Systems',
                'Bell Drinkers',
                'Water Regulators',
                'Water Filters & Medication Dispensers',
            ],
            'feed-silos-conveying-systems' => [
                'Feed Storage Silos',
                'Feed Conveyors',
                'Auger Systems',
                'Feed Bin Accessories',
            ],
            'ventilation-cooling-systems' => [
                'Exhaust Fans',
                'Circulation Fans',
                'Cooling Pads',
                'Honeycomb Cooling Pads',
            ],
            'climate-controllers-automation' => [
                'EI1000C Controllers',
                'Mobile Monitoring',
                'Temperature Controllers',
                'Humidity Monitoring',
            ],
            'poultry-heaters' => [
                'Brooders',
                'Diesel Heaters',
                'Heating Accessories',
            ],
            'cage-slat-flooring-systems' => [
                'Layer Cage Systems',
                'Broiler Cage Systems',
                'Plastic Slat Flooring',
            ],
            'manure-cleaning-systems' => [
                'Automatic Manure Scrapers',
                'Cleaning Equipment',
                'Spare Parts',
            ],
        ];

        foreach ($data as $categorySlug => $subcategories) {
            $category = Category::where('slug', $categorySlug)->first();

            if (! $category) {
                continue;
            }

            foreach ($subcategories as $index => $name) {
                Subcategory::updateOrCreate(
                    ['category_id' => $category->id, 'slug' => Str::slug($name)],
                    [
                        'name' => $name,
                        'description' => 'Efficient and durable '.$name.' for poultry operations.',
                        'image' => 'https://images.unsplash.com/photo-1518770660439-463ac7f5f3d6?auto=format&fit=crop&w=900&q=80',
                        'status' => true,
                        'display_order' => $index + 1,
                    ]
                );
            }
        }
    }
}
