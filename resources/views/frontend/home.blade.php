@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/0E1471/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'AVANEEIS TRADERS | Poultry Equipment Supplier')

@section('content')
@php
    $settings = $settings ?? App\Models\CompanySetting::allValues();
@endphp

{{-- ================= HERO ================= --}}
<section class="hero-modern">
    <span class="blob blob-1"></span>
    <span class="blob blob-2"></span>
    <span class="blob blob-3"></span>
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="hero-chip anim-up" style="--d:.1s"><i class="fa-solid fa-award me-2"></i>Trusted since 2018</span>
                <h1 class="hero-title anim-up" style="--d:.25s">
                    Complete <span class="text-gradient">Poultry Farm</span><br>Equipment Solutions
                </h1>
                <p class="hero-sub anim-up" style="--d:.4s">
                    Broiler <span class="dot"></span> Layer <span class="dot"></span> Breeder Farms
                </p>
                <p class="hero-text anim-up" style="--d:.5s">
                    Quality equipment, practical automation, reliable installation and after-sales service — everything your farm needs to grow.
                </p>
                <div class="d-flex gap-3 flex-wrap anim-up" style="--d:.65s">
                    <a href="{{ route('enquiry.form') }}" class="btn btn-danger btn-lg btn-glow"><i class="fa-solid fa-paper-plane me-2"></i>Request a Quote</a>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-light btn-lg">Explore Products <i class="fa-solid fa-arrow-right ms-2"></i></a>
                </div>
                <div class="hero-stats anim-up" style="--d:.8s">
                    <div><b data-count="2018" data-plain="1">2018</b><span>Established</span></div>
                    <div><b data-count="500" data-suffix="+">500+</b><span>Happy farmers</span></div>
                    <div><b data-count="1000" data-suffix="+">1000+</b><span>Installations</span></div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-visual anim-zoom" style="--d:.3s">
                    <div class="ring"></div>
                    <img src="{{ asset('assets/images/logo-chicken-hero.jpg') }}" alt="AVANEEIS TRADERS">
                    <div class="float-card fc-1"><i class="fa-solid fa-droplet"></i> Drinking Systems</div>
                    <div class="float-card fc-2"><i class="fa-solid fa-wheat-awn"></i> Feeding Systems</div>
                    <div class="float-card fc-3"><i class="fa-solid fa-fan"></i> Climate Control</div>
                </div>
            </div>
        </div>
    </div>
    <svg class="hero-wave" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
        <path d="M0,64 C240,120 480,0 720,48 C960,96 1200,24 1440,64 L1440,120 L0,120 Z"></path>
    </svg>
</section>

{{-- ================= MARQUEE ================= --}}
@if ($categories->count())
<div class="marquee">
    <div class="marquee-track">
        @for ($i = 0; $i < 2; $i++)
            @foreach ($categories as $category)
                <span><i class="fa-solid fa-circle-check me-2"></i>{{ $category->name }}</span>
            @endforeach
        @endfor
    </div>
</div>
@endif

{{-- ================= FEATURES ================= --}}
<section class="py-5">
    <div class="container py-lg-4">
        <div class="row g-4">
            @foreach ([
                ['icon'=>'fa-solid fa-shield-halved', 'title'=>'Quality Assured', 'text'=>'Premium equipment built to last on Indian farms.'],
                ['icon'=>'fa-solid fa-screwdriver-wrench', 'title'=>'Installation Support', 'text'=>'Turnkey support from delivery to setup.'],
                ['icon'=>'fa-solid fa-truck-fast', 'title'=>'Fast Delivery', 'text'=>'Timely supply from our Tamil Nadu warehouse.'],
                ['icon'=>'fa-solid fa-headset', 'title'=>'Expert Guidance', 'text'=>'We help you choose the right farm solutions.'],
            ] as $feature)
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card h-100" data-reveal>
                        <div class="feature-icon"><i class="{{ $feature['icon'] }}"></i></div>
                        <h5 class="fw-bold">{{ $feature['title'] }}</h5>
                        <p class="text-muted mb-0">{{ $feature['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ================= ABOUT ================= --}}
<section class="py-5 bg-white">
    <div class="container py-lg-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-5" data-reveal="left">
                <div class="about-visual">
                    <img src="{{ asset('assets/images/logo-chicken.jpg') }}" alt="AVANEEIS TRADERS">
                    <div class="about-badge"><b data-count="{{ max(1, date('Y') - 2018) }}" data-suffix="+">{{ date('Y') - 2018 }}+</b><span>Years of service</span></div>
                </div>
            </div>
            <div class="col-lg-7" data-reveal="right">
                <span class="eyebrow-dark">About Us</span>
                <h2 class="section-title text-start">Reliable equipment for profitable poultry farming</h2>
                <p class="text-muted">{{ $settings['about_us'] ?? 'We supply poultry farm equipment that supports productivity and farm efficiency.' }}</p>
                <p class="text-muted">{{ $settings['company_description'] ?? 'AVANEEIS TRADERS stands with farmers to improve performance with trusted technology.' }}</p>
                <ul class="check-list">
                    <li>Broiler, layer and breeder farm equipment</li>
                    <li>Installation and after-sales service</li>
                    <li>Offices in Kanchipuram &amp; Coimbatore</li>
                </ul>
                <a href="{{ route('about') }}" class="btn btn-primary mt-2">Know More <i class="fa-solid fa-arrow-right ms-2"></i></a>
            </div>
        </div>
    </div>
</section>

{{-- ================= CATEGORIES ================= --}}
@if ($categories->count())
<section class="py-5">
    <div class="container py-lg-4">
        <div class="text-center mb-5" data-reveal>
            <span class="eyebrow-dark">Categories</span>
            <h2 class="section-title">Our Product Categories</h2>
        </div>
        <div class="row g-4">
            @foreach ($categories as $category)
                <div class="col-md-6 col-lg-3">
                    <a href="{{ route('categories.show', $category->slug) }}" class="cat-tile" data-reveal>
                        <img src="{{ $imageUrl($category->image) }}" alt="{{ $category->name }}" loading="lazy">
                        <div class="cat-tile-body">
                            <h5>{{ $category->name }}</h5>
                            <span>View products <i class="fa-solid fa-arrow-right ms-1"></i></span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= FEATURED PRODUCTS ================= --}}
@if ($featuredProducts->count())
<section class="py-5 bg-white">
    <div class="container py-lg-4">
        <div class="text-center mb-5" data-reveal>
            <span class="eyebrow-dark">Featured Products</span>
            <h2 class="section-title">Popular equipment for profitable farms</h2>
        </div>
        <div class="row g-4">
            @foreach ($featuredProducts as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="card product-card h-100" data-reveal>
                        <div class="img-zoom">
                            <img src="{{ $imageUrl($product->image) }}" alt="{{ $product->name }}" loading="lazy">
                            <span class="tag">{{ $product->category->name ?? 'Product' }}</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                            <p class="text-muted small flex-grow-1">{{ Str::limit($product->short_description ?: strip_tags($product->description), 90) }}</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm flex-fill">Details</a>
                                <a href="{{ route('enquiry.form', $product->id) }}" class="btn btn-outline-danger btn-sm flex-fill">Enquire</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5" data-reveal>
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>
@endif

{{-- ================= STATS ================= --}}
<section class="stats-band">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3" data-reveal><b data-count="2018" data-plain="1">2018</b><span>Established</span></div>
            <div class="col-6 col-md-3" data-reveal><b data-count="500" data-suffix="+">500+</b><span>Happy Customers</span></div>
            <div class="col-6 col-md-3" data-reveal><b data-count="3">3</b><span>Locations in TN</span></div>
            <div class="col-6 col-md-3" data-reveal><b data-count="100" data-suffix="%">100%</b><span>Service Support</span></div>
        </div>
    </div>
</section>

{{-- ================= GALLERY ================= --}}
@if ($gallery->count())
<section class="py-5">
    <div class="container py-lg-4">
        <div class="text-center mb-5" data-reveal>
            <span class="eyebrow-dark">Our Work</span>
            <h2 class="section-title">Gallery</h2>
        </div>
        <div class="row g-4">
            @foreach ($gallery as $item)
                <div class="col-md-6 col-lg-4">
                    <div class="gallery-tile" data-reveal>
                        <img src="{{ $imageUrl($item->image) }}" alt="{{ $item->title }}" loading="lazy">
                        <div class="gallery-cap">{{ $item->title }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ================= CTA ================= --}}
<section class="py-5">
    <div class="container">
        <div class="cta-box" data-reveal>
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-2">Need feeders, drinkers, climate control or a full farm upgrade?</h3>
                    <p class="mb-0 opacity-75">Tell us what you need — our team will call you back with the right solution.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('enquiry.form') }}" class="btn btn-light btn-lg fw-semibold text-brand-red">Request a Quote <i class="fa-solid fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
