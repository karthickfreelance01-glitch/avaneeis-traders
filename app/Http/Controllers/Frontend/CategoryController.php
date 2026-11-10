<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Throwable;

class CategoryController extends Controller
{
    protected function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function index()
    {
        $categories = $this->tableExists('categories') ? Category::with('subcategories')->where('status', true)->orderBy('display_order')->get() : collect();
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.categories.index', compact('categories', 'settings'));
    }

    public function show($slug)
    {
        if (! $this->tableExists('categories') || ! $this->tableExists('products')) {
            abort(404);
        }

        $category = Category::with('subcategories')->where('slug', $slug)->where('status', true)->firstOrFail();
        $products = Product::with('category', 'subcategory')->where('category_id', $category->id)->where('status', true)->orderBy('display_order')->paginate(12);
        $settings = CompanySetting::allValues();

        return view('frontend.categories.show', compact('category', 'products', 'settings'));
    }
}
