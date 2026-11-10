<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUniqueSlug;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasUniqueSlug;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'image',
        'featured',
        'status',
        'display_order',
        'meta_title',
        'meta_description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }
}
