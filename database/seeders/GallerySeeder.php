<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $gallery = [
            [
                'title' => 'Farm setup',
                'description' => 'Poultry equipment installation',
                'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=1200&q=80',
                'status' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Ventilation solution',
                'description' => 'Cooling and ventilation environment',
                'image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=1200&q=80',
                'status' => true,
                'display_order' => 2,
            ],
            [
                'title' => 'Layer house equipment',
                'description' => 'Modern feeding and drinking systems',
                'image' => 'https://images.unsplash.com/photo-1600417924205-9f19d1fc0d17?auto=format&fit=crop&w=1200&q=80',
                'status' => true,
                'display_order' => 3,
            ],
        ];

        foreach ($gallery as $item) {
            GalleryItem::updateOrCreate(['title' => $item['title']], $item);
        }
    }
}
