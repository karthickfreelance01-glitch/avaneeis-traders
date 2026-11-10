<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFeature;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'subcategory')->orderBy('display_order')->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        $subcategories = Subcategory::orderBy('name')->get();

        return view('admin.products.form', ['product' => new Product(), 'categories' => $categories, 'subcategories' => $subcategories]);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Product::uniqueSlug($request->name);
        $data['status'] = $request->boolean('status');
        $data['featured'] = $request->boolean('featured');
        $data['display_order'] = $request->input('display_order', 0);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            $sort = 1;
            foreach ($request->file('images') as $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => $sort === 1,
                    'sort_order' => $sort,
                ]);
                $sort++;
            }
        }

        if ($request->filled('specifications')) {
            foreach ($request->input('specifications') as $spec) {
                if (! empty($spec['name']) || ! empty($spec['value'])) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'name' => $spec['name'] ?? '',
                        'value' => $spec['value'] ?? '',
                    ]);
                }
            }
        }

        if ($request->filled('features')) {
            foreach ($request->input('features') as $feature) {
                if (! empty($feature)) {
                    ProductFeature::create([
                        'product_id' => $product->id,
                        'name' => $feature,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product saved successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $subcategories = Subcategory::orderBy('name')->get();

        return view('admin.products.form', compact('product', 'categories', 'subcategories'));
    }

    public function update(StoreProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['slug'] = Product::uniqueSlug($request->name, $product->id);
        $data['status'] = $request->boolean('status');
        $data['featured'] = $request->boolean('featured');
        $data['display_order'] = $request->input('display_order', 0);

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        if ($request->hasFile('images')) {
            $sort = ProductImage::where('product_id', $product->id)->max('sort_order') + 1;
            foreach ($request->file('images') as $file) {
                $path = $file->store('products/gallery', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => false,
                    'sort_order' => $sort,
                ]);
                $sort++;
            }
        }

        if ($request->filled('specifications')) {
            $product->specifications()->delete();
            foreach ($request->input('specifications') as $spec) {
                if (! empty($spec['name']) || ! empty($spec['value'])) {
                    ProductSpecification::create([
                        'product_id' => $product->id,
                        'name' => $spec['name'] ?? '',
                        'value' => $spec['value'] ?? '',
                    ]);
                }
            }
        }

        if ($request->filled('features')) {
            $product->features()->delete();
            foreach ($request->input('features') as $feature) {
                if (! empty($feature)) {
                    ProductFeature::create([
                        'product_id' => $product->id,
                        'name' => $feature,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        foreach ($product->images as $image) {
            if ($image->image && Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
        }
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
