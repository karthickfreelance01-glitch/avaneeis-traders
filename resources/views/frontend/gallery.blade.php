@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'Gallery | AVANEEIS TRADERS')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="text-primary fw-semibold">Gallery</span>
        <h1 class="section-title">Our work in poultry farm solutions</h1>
    </div>

    <div class="row g-4">
        @foreach ($gallery as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 overflow-hidden">
                    <img src="{{ $imageUrl($item->image) }}" class="img-fluid" style="height:240px; object-fit:cover; width:100%;" alt="{{ $item->title }}">
                    <div class="card-body">
                        <h5 class="fw-bold">{{ $item->title }}</h5>
                        <p class="text-muted mb-0">{{ $item->description }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
