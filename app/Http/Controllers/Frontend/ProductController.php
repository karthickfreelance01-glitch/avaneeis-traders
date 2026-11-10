<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnquiryRequest;
use App\Models\Category;
use App\Models\CompanySetting;
use App\Models\Enquiry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class ProductController extends Controller
{
    protected function tableExists(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function index(Request $request)
    {
        if (! $this->tableExists('products')) {
            return view('frontend.products.index', [
                'products' => \Illuminate\Pagination\LengthAwarePaginator::make([], 0, 12),
                'categories' => collect(),
                'settings' => $this->tableExists('company_settings') ? CompanySetting::allValues() : [],
            ]);
        }

        $query = Product::with('category', 'subcategory')->where('status', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'latest') {
                $query->latest();
            } elseif ($request->sort === 'featured') {
                $query->orderByDesc('featured');
            }
        }

        $products = $query->orderBy('display_order')->paginate(12);
        $categories = $this->tableExists('categories') ? Category::where('status', true)->orderBy('display_order')->get() : collect();
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.products.index', compact('products', 'categories', 'settings'));
    }

    public function show($slug)
    {
        if (! $this->tableExists('products')) {
            abort(404);
        }

        $product = Product::with('category', 'subcategory', 'images', 'specifications', 'features')->where('slug', $slug)->where('status', true)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->where('status', true)->limit(4)->get();
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.products.show', compact('product', 'relatedProducts', 'settings'));
    }

    public function enquiryForm(?Product $product = null)
    {
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];
        $categories = $this->tableExists('categories')
            ? Category::where('status', true)
                ->orderBy('display_order')
                ->with(['products' => fn ($q) => $q->where('status', true)->select('id', 'name', 'category_id', 'display_order')])
                ->get(['id', 'name'])
            : collect();

        $source = 'website';

        return view('frontend.enquiry', compact('product', 'settings', 'categories', 'source'));
    }

    /**
     * Landing page opened when a customer scans the QR code.
     */
    public function qrEnquiryForm()
    {
        $view = $this->enquiryForm(null);

        return $view->with('source', 'qr');
    }

    public function storeEnquiry(StoreEnquiryRequest $request)
    {
        if (! $this->tableExists('enquiries')) {
            return redirect()->route('home');
        }

        $data = $request->validated();

        // Store the "Location" field in the existing city column
        $data['city'] = $data['location'];
        unset($data['location']);

        // Website and QR forms now share the same fields
        if (isset($data['product_name'])) {
            return $this->storeQrEnquiry($data);
        }

        $category = Category::find($data['category_id']);
        $product = Product::where('id', $data['product_id'])->where('category_id', $data['category_id'])->first();

        if (! $product) {
            return back()->withInput()->withErrors(['product_id' => 'Please choose a product from the selected category.']);
        }

        // Remember where the enquiry came from (needs the 2026_11_07 migration)
        $source = $data['source'] ?? 'website';
        unset($data['source']);
        if (Schema::hasColumn('enquiries', 'source')) {
            $data['source'] = $source;
        }

        $data['product_category'] = $category?->name;
        $data['interested_product'] = $product->name;
        $data['subcategory_id'] = $product->subcategory_id;
        $data['enquiry_reference'] = 'AV-'.strtoupper(Str::random(8));
        $data['status'] = 'New';

        Enquiry::create($data);

        return redirect()->route('enquiry.success', $source === 'qr' ? ['via' => 'qr'] : [])->with('success', 'Your enquiry has been submitted successfully.');
    }

    /**
     * Website & QR enquiry: product is typed by the customer, plus "How did you find us?".
     */
    protected function storeQrEnquiry(array $data)
    {
        $productName = trim($data['product_name']);
        $howFound = $data['how_found'];
        $source = ($data['source'] ?? 'website') === 'qr' ? 'qr' : 'website';
        unset($data['product_name'], $data['how_found'], $data['source']);

        // Link to a catalogue product when the typed name matches one exactly
        $product = Product::whereRaw('LOWER(name) = ?', [mb_strtolower($productName)])->first();

        $data['interested_product'] = $productName;
        $data['product_id'] = $product?->id;
        $data['category_id'] = $product?->category_id;
        $data['subcategory_id'] = $product?->subcategory_id;
        $data['product_category'] = $product?->category?->name;

        if (Schema::hasColumn('enquiries', 'source')) {
            $data['source'] = $source;
        }
        if (Schema::hasColumn('enquiries', 'how_found')) {
            $data['how_found'] = $howFound;
        } else {
            // Migration not run yet: keep the answer so it is not lost
            $data['message'] = 'How found us: '.$howFound;
        }

        $data['enquiry_reference'] = 'AV-'.strtoupper(Str::random(8));
        $data['status'] = 'New';

        Enquiry::create($data);

        return redirect()->route('enquiry.success', $source === 'qr' ? ['via' => 'qr'] : [])->with('success', 'Your enquiry has been submitted successfully.');
    }

    public function success()
    {
        $settings = $this->tableExists('company_settings') ? CompanySetting::allValues() : [];

        return view('frontend.thank-you', compact('settings'));
    }
}
