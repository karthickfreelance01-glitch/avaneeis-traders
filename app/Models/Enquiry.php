<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'product_id',
        'customer_name',
        'company_name',
        'phone',
        'whatsapp',
        'email',
        'state',
        'city',
        'product_category',
        'interested_product',
        'quantity',
        'message',
        'enquiry_reference',
        'status',
        'source',
        'notes',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeFromQr($query)
    {
        return $query->where('source', 'qr');
    }

    public function notesList()
    {
        return $this->hasMany(EnquiryNote::class)->orderByDesc('created_at');
    }
}
