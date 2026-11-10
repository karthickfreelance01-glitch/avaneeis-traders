<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\GalleryItem;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Throwable;

class HomeController extends Controller
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
        $banners = $this->tableExists('banners') ? Banner::where('status', true)->orderBy('display_order')->get() : collect();
        $categories = $this->tableExists('categories') ? Category::where('status', true)->orderBy('display_order')->get() : collect();
        $featuredProducts = $this->tableExists('products') ? Product::with('category', 'subcategory')->where('status', true)->where('featured', true)->orderBy('display_order')->limit(4)->get() : collect();
        $gallery = $this->tableExists('gallery') ? GalleryItem::where('status', true)->orderBy('display_order')->limit(6)->get() : collect();
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.home', compact('banners', 'categories', 'featuredProducts', 'gallery', 'settings'));
    }

    public function about()
    {
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.about', compact('settings'));
    }

    public function services()
    {
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.services', compact('settings'));
    }

    public function gallery()
    {
        $gallery = $this->tableExists('gallery') ? GalleryItem::where('status', true)->orderBy('display_order')->get() : collect();
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.gallery', compact('gallery', 'settings'));
    }

    public function contact()
    {
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.contact', compact('settings'));
    }
}
