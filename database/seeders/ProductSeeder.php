<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'automatic-pan-feeding-systems',
                'subcategory' => 'broiler-feeding-systems',
                'name' => 'Automatic Pan Feeding System',
                'sku' => 'APFS-101',
                'short_description' => 'High-efficiency feeding solution designed for broiler houses.',
                'description' => 'A robust feeding system with smooth feed flow, strong structure and consistent feed delivery for large-scale poultry farms.',
                'image' => 'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&w=900&q=80',
                'featured' => true,
            ],
            [
                'category' => 'drinking-systems',
                'subcategory' => 'nipple-drinking-systems',
                'name' => 'Nipple Drinking System',
                'sku' => 'NDS-205',
                'short_description' => 'Reliable nipple line drinking unit for efficient water management.',
                'description' => 'This premium nipple drinking system helps reduce water wastage and supports clean drinking lines for high-density poultry houses.',
                'image' => 'https://images.unsplash.com/photo-1600417924205-9f19d1fc0d17?auto=format&fit=crop&w=900&q=80',
                'featured' => true,
            ],
            [
                'category' => 'feed-silos-conveying-systems',
                'subcategory' => 'feed-storage-silos',
                'name' => 'Feed Storage Silo',
                'sku' => 'FSS-330',
                'short_description' => 'Durable, hygienic feed storage solution for modern farms.',
                'description' => 'Made to preserve feed quality and organize poultry feed storage with strong construction and practical access.',
                'image' => 'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=900&q=80',
                'featured' => false,
            ],
            [
                'category' => 'ventilation-cooling-systems',
                'subcategory' => 'exhaust-fans',
                'name' => 'Exhaust Fan System',
                'sku' => 'EFS-440',
                'short_description' => 'High airflow ventilation for healthy poultry environments.',
                'description' => 'Built for consistent ventilation and heat reduction, helping maintain thermal comfort in poultry houses.',
                'image' => 'https://images.unsplash.com/photo-1559526324-4b87b5e36e44?auto=format&fit=crop&w=900&q=80',
                'featured' => true,
            ],
            [
                'category' => 'climate-controllers-automation',
                'subcategory' => 'ei1000c-controllers',
                'name' => 'EI1000C Controller',
                'sku' => 'EI-1000C',
                'short_description' => 'Smart environmental control for poultry climate monitoring.',
                'description' => 'A practical automation controller that helps monitor temperature, humidity and system performance in one dashboard.',
                'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
                'featured' => true,
            ],
            [
                'category' => 'poultry-heaters',
                'subcategory' => 'brooders',
                'name' => 'Poultry Brooder Heater',
                'sku' => 'PBH-77',
                'short_description' => 'Reliable heater for early chick growth and uniform warmth.',
                'description' => 'An efficient heating solution for precise temperature management and warm chick rearing conditions.',
                'image' => 'https://images.unsplash.com/photo-1592838377078-46a6fa94e7f1?auto=format&fit=crop&w=900&q=80',
                'featured' => false,
            ],
        ];

        foreach ($products as $index => $productData) {
            $category = Category::where('slug', $productData['category'])->first();
            $subcategory = Subcategory::where('slug', $productData['subcategory'])->first();

            if (! $category) {
                continue;
            }

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($productData['name'])],
                [
                    'category_id' => $category->id,
                    'subcategory_id' => $subcategory?->id,
                    'name' => $productData['name'],
                    'sku' => $productData['sku'],
                    'short_description' => $productData['short_description'],
                    'description' => $productData['description'],
                    'image' => $productData['image'],
                    'featured' => $productData['featured'],
                    'status' => true,
                    'display_order' => $index + 1,
                    'meta_title' => $productData['name'],
                    'meta_description' => $productData['short_description'],
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'image' => $productData['image']],
                ['is_primary' => true, 'sort_order' => 1]
            );

            ProductSpecification::insert([
                ['product_id' => $product->id, 'name' => 'Material', 'value' => 'Industrial Grade'],
                ['product_id' => $product->id, 'name' => 'Application', 'value' => 'Broiler / Layer / Breeder'],
            ]);

            ProductFeature::insert([
                ['product_id' => $product->id, 'name' => 'High durability'],
                ['product_id' => $product->id, 'name' => 'Easy maintenance'],
                ['product_id' => $product->id, 'name' => 'Consistent performance'],
            ]);
        }
    }
}
