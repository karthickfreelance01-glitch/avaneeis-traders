<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $hasSource = Schema::hasColumn('enquiries', 'source');

        $stats = [
            'categories' => Category::count(),
            'subcategories' => Subcategory::count(),
            'products' => Product::count(),
            'featured_products' => Product::where('featured', true)->count(),
            'total_enquiries' => Enquiry::count(),
            'new_enquiries' => Enquiry::where('status', 'New')->count(),
            'today_enquiries' => Enquiry::whereDate('created_at', today())->count(),
            'qr_enquiries' => $hasSource ? Enquiry::where('source', 'qr')->count() : 0,
            'converted' => Enquiry::where('status', 'Converted')->count(),
            'active_banners' => Banner::where('status', true)->count(),
        ];
        $stats['conversion_rate'] = $stats['total_enquiries'] ? round($stats['converted'] / $stats['total_enquiries'] * 100) : 0;

        // Enquiries per day for the last 14 days (website vs QR)
        $from = today()->subDays(13);
        $rows = Enquiry::where('created_at', '>=', $from)
            ->selectRaw('DATE(created_at) as d'.($hasSource ? ", SUM(source = 'qr') as qr" : ', 0 as qr').', COUNT(*) as total')
            ->groupBy('d')
            ->get()
            ->keyBy('d');

        $trend = [];
        for ($i = 13; $i >= 0; $i--) {
            $day = today()->subDays($i);
            $r = $rows[$day->toDateString()] ?? null;
            $trend[] = [
                'label' => $day->format('d'),
                'day' => $day->format('D, d M'),
                'total' => (int) ($r->total ?? 0),
                'qr' => (int) ($r->qr ?? 0),
            ];
        }

        $statusCounts = Enquiry::selectRaw('status, COUNT(*) as c')->groupBy('status')->pluck('c', 'status')->toArray();

        $recentEnquiries = Enquiry::with('product', 'category')->latest()->limit(6)->get();
        $latestProducts = Product::with('category', 'subcategory')->latest()->limit(5)->get();
        $categoryStats = Category::withCount('products')->orderBy('products_count', 'desc')->get();

        return view('admin.dashboard', compact('stats', 'trend', 'statusCounts', 'recentEnquiries', 'latestProducts', 'categoryStats'));
    }
}
