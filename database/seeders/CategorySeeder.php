<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Automatic Pan Feeding Systems',
            'Drinking Systems',
            'Feed Silos & Conveying Systems',
            'Ventilation & Cooling Systems',
            'Climate Controllers & Automation',
            'Poultry Heaters',
            'Cage & Slat Flooring Systems',
            'Manure Cleaning Systems',
        ];

        foreach ($categories as $index => $categoryName) {
            Category::updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                [
                    'name' => $categoryName,
                    'description' => 'Premium poultry equipment for modern farms.',
                    'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=900&q=80',
                    'meta_title' => $categoryName,
                    'meta_description' => 'Premium poultry equipment and service solutions from AVANEEIS TRADERS.',
                    'status' => true,
                    'display_order' => $index + 1,
                ]
            );
        }
    }
}
