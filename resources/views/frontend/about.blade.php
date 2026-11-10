@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'About Us | AVANEEIS TRADERS')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-6">
            <span class="text-primary fw-semibold">About AVANEEIS TRADERS</span>
            <h1 class="section-title mt-2">Built for better poultry performance</h1>
            <p class="text-muted">{{ $settings['about_us'] ?? 'We provide trusted equipment and solutions for poultry farms.' }}</p>
            <p class="text-muted">{{ $settings['company_description'] ?? 'Our goal is to support farmers with efficient systems that improve productivity.' }}</p>
        </div>
        <div class="col-lg-6">
            <img src="https://images.unsplash.com/photo-1518770660439-463ac7f5f3d6?auto=format&fit=crop&w=1200&q=80" class="img-fluid rounded-4 shadow" alt="AVANEEIS TRADERS">
        </div>
    </div>

    <div class="row g-4 mt-3">
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4">
                <h5 class="fw-bold">Mission</h5>
                <p class="text-muted mb-0">{{ $settings['mission'] ?? 'To equip poultry farms with dependable, practical solutions.' }}</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4">
                <h5 class="fw-bold">Vision</h5>
                <p class="text-muted mb-0">{{ $settings['vision'] ?? 'To become a trusted partner for poultry farm modernization.' }}</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4">
                <h5 class="fw-bold">Experience</h5>
                <p class="text-muted mb-0">Our team brings practical knowledge in poultry equipment, installation and support.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 p-4">
                <h5 class="fw-bold">Quality Commitment</h5>
                <p class="text-muted mb-0">We prioritize durable products, transparent guidance and dependable service.</p>
            </div>
        </div>
    </div>
</div>
@endsection
