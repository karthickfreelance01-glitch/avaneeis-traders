@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'Products | AVANEEIS TRADERS')

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <span class="text-primary fw-semibold">Products</span>
        <h1 class="section-title">Find the right equipment for your farm</h1>
    </div>

    <div class="card p-3 mb-4">
        <form method="GET" action="{{ route('products.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search product name">
            </div>
            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Sort</label>
                <select name="sort" class="form-select">
                    <option value="">Default</option>
                    <option value="latest" @selected(request('sort') === 'latest')>Latest</option>
                    <option value="featured" @selected(request('sort') === 'featured')>Featured</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>

    <div class="row g-4">
        @foreach ($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <img src="{{ $imageUrl($product->image) }}" alt="{{ $product->name }}">
                    <div class="card-body">
                        <span class="badge bg-light text-primary mb-2">{{ $product->category->name ?? 'Product' }}</span>
                        <h5 class="fw-bold">{{ $product->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($product->short_description, 90) }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm">View Details</a>
                            <a href="{{ route('enquiry.form', $product->id) }}" class="btn btn-outline-danger btn-sm">Enquire Now</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
