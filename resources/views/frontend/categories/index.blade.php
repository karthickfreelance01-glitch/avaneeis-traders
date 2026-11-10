@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'Categories | AVANEEIS TRADERS')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="text-primary fw-semibold">Categories</span>
        <h1 class="section-title">Explore product categories</h1>
    </div>

    <div class="row g-4">
        @foreach ($categories as $category)
            <div class="col-md-6 col-lg-4">
                <div class="card category-card h-100">
                    <img src="{{ $imageUrl($category->image) }}" alt="{{ $category->name }}">
                    <div class="card-body">
                        <h4 class="fw-bold">{{ $category->name }}</h4>
                        <p class="text-muted">{{ Str::limit($category->description, 100) }}</p>
                        <p class="small text-primary mb-3">{{ $category->products()->count() }} products</p>
                        <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-primary">View Products</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
