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

{{-- ================= HERO SLIDER ================= --}}
@php
    $heroSlides = [
        ['img' => 'assets/images/hero/hero-cleaning-robot.jpg', 'alt' => 'X6 Livestock Manure Channels Cleaning Robot', 'link' => route('products.index')],
        ['img' => 'assets/images/hero/hero-climate-control.jpg', 'alt' => 'Climate Control Solutions for Modern Poultry Farms', 'link' => route('products.index')],
    ];
@endphp
<style>
    .hs { position: relative; overflow: hidden; background: #080C4A; padding: 16px 0 16px; isolation: isolate; }
    .hs-bg { position: absolute; inset: -40px; z-index: -2; background-size: cover; background-position: center; filter: blur(38px) saturate(1.2) brightness(.55);
        opacity: 0; transition: opacity 1.2s ease; }
    .hs-bg.is-on { opacity: 1; }
    .hs::after { content: ''; position: absolute; inset: 0; z-index: -1; background: radial-gradient(ellipse at center, transparent 40%, rgba(8,12,74,.75) 100%); }
    .hs-stage { position: relative; margin: 0 auto; width: min(80%, 1440px); aspect-ratio: 3 / 2;
        border-radius: 14px; overflow: hidden; box-shadow: 0 30px 70px rgba(0,0,0,.45), 0 0 0 1px rgba(255,255,255,.08); background: #0E1471; }
    .hs-slide { position: absolute; inset: 0; opacity: 0; visibility: hidden; transition: opacity 1s ease, visibility 0s linear 1s; }
    .hs-slide.is-active { opacity: 1; visibility: visible; transition: opacity 1s ease; z-index: 1; }
    .hs-slide a { display: block; width: 100%; height: 100%; }
    .hs-slide img { width: 100%; height: 100%; object-fit: contain; display: block; transform: scale(1.08) translateY(10px); filter: blur(4px); }
    .hs-slide.is-active img { animation: hsIn 1.4s cubic-bezier(.2,.7,.2,1) forwards; }
    @keyframes hsIn { to { transform: none; filter: none; } }
    /* light sweep across the poster when it appears */
    .hs-slide::after { content: ''; position: absolute; top: 0; bottom: 0; width: 35%; left: -45%; pointer-events: none;
        background: linear-gradient(105deg, transparent, rgba(255,255,255,.28), transparent); transform: skewX(-12deg); }
    .hs-slide.is-active::after { animation: hsShine 1.6s ease .9s forwards; }
    @keyframes hsShine { to { left: 120%; } }

    .hs-nav { position: absolute; top: 50%; transform: translateY(-50%); z-index: 3; width: 46px; height: 46px; border-radius: 50%; border: 0;
        background: rgba(255,255,255,.88); color: #0E1471; display: grid; place-items: center; box-shadow: 0 8px 20px rgba(0,0,0,.25);
        opacity: 0; transition: opacity .3s ease, transform .3s ease, background .2s; }
    .hs-stage:hover .hs-nav, .hs-nav:focus-visible { opacity: 1; }
    .hs-nav:hover { background: #C90C0C; color: #fff; }
    .hs-prev { left: 14px; } .hs-next { right: 14px; }

    .hs-dots { display: flex; justify-content: center; gap: 10px; margin-top: 14px; }
    .hs-dot { position: relative; width: 56px; height: 5px; border: 0; padding: 0; border-radius: 5px; background: rgba(255,255,255,.25); overflow: hidden; cursor: pointer; }
    .hs-dot span { position: absolute; inset: 0; width: 0; background: linear-gradient(90deg, #fff, #ff4d4d); border-radius: 5px; }
    .hs-dot.is-active span { animation: hsProg var(--hs-time, 6s) linear forwards; }
    .hs.is-paused .hs-dot.is-active span { animation-play-state: paused; }
    @keyframes hsProg { to { width: 100%; } }

    @media (max-width: 767px) {
        .hs { padding: 10px 0 12px; }
        .hs-stage { width: calc(100% - 20px); border-radius: 10px; }
        .hs-nav { display: none; }
        .hs-dot { width: 36px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .hs-slide img, .hs-slide.is-active img { animation: none; transform: none; filter: none; }
        .hs-slide::after { display: none; }
    }
</style>

<section class="hs" id="heroSlider" aria-roledescription="carousel" aria-label="Featured products">
    @foreach ($heroSlides as $i => $slide)
        <div class="hs-bg {{ $i === 0 ? 'is-on' : '' }}" style="background-image: url('{{ asset($slide['img']) }}')"></div>
    @endforeach

    <div class="hs-stage">
        @foreach ($heroSlides as $i => $slide)
            <div class="hs-slide {{ $i === 0 ? 'is-active' : '' }}" role="group" aria-roledescription="slide" aria-label="{{ $i + 1 }} of {{ count($heroSlides) }}">
                <a href="{{ $slide['link'] }}">
                    <img src="{{ asset($slide['img']) }}" alt="{{ $slide['alt'] }}" width="1536" height="1024" @if ($i) loading="lazy" @else fetchpriority="high" @endif>
                </a>
            </div>
        @endforeach
        @if (count($heroSlides) > 1)
            <button class="hs-nav hs-prev" type="button" aria-label="Previous slide"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="hs-nav hs-next" type="button" aria-label="Next slide"><i class="fa-solid fa-chevron-right"></i></button>
        @endif
    </div>

    @if (count($heroSlides) > 1)
        <div class="hs-dots">
            @foreach ($heroSlides as $i => $slide)
                <button class="hs-dot {{ $i === 0 ? 'is-active' : '' }}" type="button" aria-label="Go to slide {{ $i + 1 }}"><span></span></button>
            @endforeach
        </div>
    @endif
</section>

<script>
(function () {
    const root = document.getElementById('heroSlider');
    const slides = root.querySelectorAll('.hs-slide');
    if (slides.length < 2) return;
    const bgs = root.querySelectorAll('.hs-bg');
    const dots = root.querySelectorAll('.hs-dot');
    const TIME = 6000;
    root.style.setProperty('--hs-time', TIME + 'ms');
    let index = 0, timer = null, remaining = TIME, startedAt = 0;

    function go(n) {
        slides[index].classList.remove('is-active'); bgs[index].classList.remove('is-on'); dots[index].classList.remove('is-active');
        index = (n + slides.length) % slides.length;
        // restart the CSS animations
        [slides[index], dots[index]].forEach(el => { void el.offsetWidth; });
        slides[index].classList.add('is-active'); bgs[index].classList.add('is-on'); dots[index].classList.add('is-active');
        remaining = TIME; schedule();
    }
    function schedule() { clearTimeout(timer); startedAt = Date.now(); timer = setTimeout(() => go(index + 1), remaining); }
    function pause() { if (root.classList.contains('is-paused')) return; clearTimeout(timer); remaining -= Date.now() - startedAt; root.classList.add('is-paused'); }
    function resume() { if (!root.classList.contains('is-paused')) return; root.classList.remove('is-paused'); schedule(); }

    root.querySelector('.hs-prev').addEventListener('click', () => go(index - 1));
    root.querySelector('.hs-next').addEventListener('click', () => go(index + 1));
    dots.forEach((d, i) => d.addEventListener('click', () => i !== index && go(i)));
    const stage = root.querySelector('.hs-stage');
    stage.addEventListener('mouseenter', pause);
    stage.addEventListener('mouseleave', resume);
    document.addEventListener('visibilitychange', () => document.hidden ? pause() : resume());

    // swipe on phones
    let x0 = null;
    stage.addEventListener('touchstart', e => { x0 = e.touches[0].clientX; }, { passive: true });
    stage.addEventListener('touchend', e => {
        if (x0 === null) return;
        const dx = e.changedTouches[0].clientX - x0; x0 = null;
        if (Math.abs(dx) > 40) go(index + (dx < 0 ? 1 : -1));
    });

    schedule();
})();
</script>

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
