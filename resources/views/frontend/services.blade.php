@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'Services | AVANEEIS TRADERS')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="text-primary fw-semibold">Services</span>
        <h1 class="section-title">Complete farm solution support</h1>
    </div>

    <div class="row g-4">
        @foreach([
            'Farm equipment consultation',
            'Project planning for poultry houses',
            'Equipment supply and installation',
            'Climate control and automation support',
            'Maintenance and after-sales service',
            'Dealer and distributor support',
        ] as $service)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 p-4 text-center">
                    <div class="display-6 text-primary mb-3"><i class="fa-solid fa-circle-check"></i></div>
                    <h5 class="fw-bold">{{ $service }}</h5>
                    <p class="text-muted mb-0">Professional support for modern poultry operations and farm productivity.</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
