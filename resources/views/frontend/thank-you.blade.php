@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'Enquiry Submitted | AVANEEIS TRADERS')

@section('content')
<div class="container py-5">
    <div class="card p-5 text-center">
        <div class="display-5 text-success mb-3"><i class="fa-solid fa-circle-check"></i></div>
        <h1 class="section-title mb-3">Thank you for your enquiry</h1>
        <p class="text-muted">Your request has been submitted successfully. Our team will contact you soon.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
    </div>
</div>
@endsection
