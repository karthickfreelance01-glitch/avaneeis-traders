<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Concerns\HasUniqueSlug;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasUniqueSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'meta_title',
        'meta_description',
        'status',
        'display_order',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class)->orderBy('display_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('display_order');
    }
}
