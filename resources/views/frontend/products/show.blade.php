@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card p-3">
                <img src="{{ $imageUrl($product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-4" style="height:450px; object-fit:cover; width:100%;">
            </div>
            <div class="row g-3 mt-2">
                @foreach ($product->images as $image)
                    <div class="col-3">
                        <img src="{{ $imageUrl($image->image) }}" alt="{{ $product->name }}" class="img-fluid rounded-3" style="height:120px; object-fit:cover; width:100%;">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-6">
            <span class="badge bg-light text-primary mb-2">{{ $product->category->name ?? 'Product Category' }}</span>
            <h1 class="section-title mb-3">{{ $product->name }}</h1>
            <p class="text-muted mb-4">{{ $product->short_description }}</p>
            <div class="mb-4">
                <h5 class="fw-bold">Product Overview</h5>
                <div>{!! nl2br(e($product->description)) !!}</div>
            </div>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('enquiry.form', $product->id) }}" class="btn btn-danger">Enquire Now</a>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '9499953094') }}?text={{ urlencode('I need information about '.$product->name) }}" class="btn btn-success" target="_blank">WhatsApp Enquiry</a>
                <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings['contact_phone_1'] ?? '9842324074') }}" class="btn btn-outline-primary">Call Now</a>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-lg-6">
            <div class="card h-100 p-4">
                <h4 class="fw-bold mb-3">Technical Specifications</h4>
                <table class="table table-striped">
                    <tbody>
                        @foreach ($product->specifications as $spec)
                            <tr>
                                <th>{{ $spec->name }}</th>
                                <td>{{ $spec->value }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100 p-4">
                <h4 class="fw-bold mb-3">Features</h4>
                <ul class="list-unstyled mb-0">
                    @foreach ($product->features as $feature)
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i>{{ $feature->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="card p-4 mt-4">
        <h4 class="fw-bold mb-3">Applications</h4>
        <p class="mb-0 text-muted">Suitable for broiler, layer, breeder farms and organized poultry operations where efficient feeding, climate control, ventilation and water management are essential.</p>
    </div>

    <div class="mt-5">
        <h3 class="section-title">Related Products</h3>
        <div class="row g-4">
            @foreach ($relatedProducts as $related)
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100">
                        <img src="{{ $imageUrl($related->image) }}" alt="{{ $related->name }}">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $related->name }}</h5>
                            <a href="{{ route('products.show', $related->slug) }}" class="btn btn-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
