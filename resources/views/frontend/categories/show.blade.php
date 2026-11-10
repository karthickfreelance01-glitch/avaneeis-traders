@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', $category->meta_title ?: $category->name)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="text-center mb-5">
        <h1 class="section-title">{{ $category->name }}</h1>
        <p class="text-muted">{{ $category->description }}</p>
    </div>

    @if($category->subcategories->count())
        <div class="mb-5">
            <h4 class="fw-bold mb-3">Subcategories</h4>
            <div class="row g-3">
                @foreach($category->subcategories as $subcategory)
                    <div class="col-md-3">
                        <div class="card h-100 p-3">
                            <span class="fw-bold">{{ $subcategory->name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row g-4">
        @foreach ($products as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <img src="{{ $imageUrl($product->image) }}" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $product->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($product->short_description, 90) }}</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm">View Details</a>
                            <a href="{{ route('enquiry.form', $product->id) }}" class="btn btn-outline-danger btn-sm">Enquire Now</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection
