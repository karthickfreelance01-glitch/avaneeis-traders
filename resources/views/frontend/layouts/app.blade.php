<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'AVANEEIS TRADERS – Poultry farm equipment supplier')">
    <title>@yield('title', 'AVANEEIS TRADERS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/site.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
<div id="preloader" aria-hidden="true">
    <div class="preloader-inner">
        <img src="{{ asset('assets/images/logo-chicken.jpg') }}" alt="AVANEEIS TRADERS">
        <div class="preloader-bar"><span></span></div>
    </div>
</div>
@php
    $siteSettings = $settings ?? App\Models\CompanySetting::allValues();
    $companyName = $siteSettings['company_name'] ?? 'AVANEEIS TRADERS';
    $companyPhone = $siteSettings['contact_phone_1'] ?? '98423 24074';
    $companyWhatsApp = $siteSettings['whatsapp_number'] ?? '94999 53094';
    $companyEmail = $siteSettings['email'] ?? 'avaneeistraders@gmail.com';
    $placeholder = 'https://placehold.co/600x400/0E1471/FFFFFF?text=Avaneeis';
    $imageUrl = function ($path) use ($placeholder) {
        if (empty($path)) {
            return $placeholder;
        }
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return Storage::url($path);
    };
@endphp

<div id="scrollProgress"></div>
<div class="topbar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex gap-4">
            <a href="tel:{{ preg_replace('/[^0-9]/', '', $companyPhone) }}"><i class="fa-solid fa-phone me-2"></i>{{ $companyPhone }}</a>
            <a href="mailto:{{ $companyEmail }}"><i class="fa-solid fa-envelope me-2"></i>{{ $companyEmail }}</a>
        </div>
        <span class="d-none d-lg-inline">{{ $siteSettings['company_tagline'] ?? 'The Complete Poultry Solutions' }}</span>
    </div>
</div>

<nav class="navbar navbar-expand-lg sticky-top main-nav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/logo-full.jpg') }}" alt="{{ $companyName }}" class="brand-logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('home')) active @endif" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('about')) active @endif" href="{{ route('about') }}">About Us</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('categories.*')) active @endif" href="{{ route('categories.index') }}">Categories</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('products.*')) active @endif" href="{{ route('products.index') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('services')) active @endif" href="{{ route('services') }}">Services</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('gallery')) active @endif" href="{{ route('gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link @if(request()->routeIs('contact')) active @endif" href="{{ route('contact') }}">Contact</a></li>
                <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                    <a class="btn btn-danger fw-semibold px-4" href="{{ route('enquiry.form') }}"><i class="fa-solid fa-file-invoice me-2"></i>Request Quote</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main>
@yield('content')
</main>

<footer class="footer mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-logo mb-3"><img src="{{ asset('assets/images/logo-icon.jpg') }}" alt=""><span><b class="text-white">AVANEEIS</b> <b class="text-brand-red">TRADERS</b></span></div>
                <p>{{ $siteSettings['company_description'] ?? 'Poultry farm equipment solutions' }}</p>
                <p><i class="fa-solid fa-phone me-2"></i>{{ $companyPhone }}</p>
                <p><i class="fa-solid fa-envelope me-2"></i>{{ $companyEmail }}</p>
            </div>
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('about') }}" class="text-white-50 text-decoration-none">About Us</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-white-50 text-decoration-none">Products</a></li>
                    <li><a href="{{ route('categories.index') }}" class="text-white-50 text-decoration-none">Categories</a></li>
                    <li><a href="{{ route('contact') }}" class="text-white-50 text-decoration-none">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Head Office</h5>
                <p>{{ $siteSettings['head_office'] ?? '' }}</p>
                <div class="d-flex gap-3 fs-5">
                    <a href="tel:{{ preg_replace('/[^0-9]/', '', $companyPhone) }}" class="text-white"><i class="fa-solid fa-phone"></i></a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $companyWhatsApp) }}" class="text-white" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="mailto:{{ $companyEmail }}" class="text-white"><i class="fa-solid fa-envelope"></i></a>
                </div>
            </div>
        </div>
        <div class="border-top border-light mt-4 pt-3 text-center text-white-50 small">
            © {{ date('Y') }} Macnus Solutions. All rights reserved.
        </div>
    </div>
</footer>

<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $companyWhatsApp) }}" class="btn btn-success rounded-circle position-fixed bottom-0 end-0 m-4 shadow whatsapp-float" style="width:58px;height:58px;display:flex;align-items:center;justify-content:center;font-size:1.7rem;" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>

<button id="toTop" type="button" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        const hide = () => document.getElementById('preloader')?.classList.add('done');
        window.addEventListener('load', () => setTimeout(hide, 600));
        setTimeout(hide, 4000); // safety: never block the page for long

        /* Navbar shadow on scroll + back-to-top */
        const nav = document.querySelector('.main-nav');
        const toTop = document.getElementById('toTop');
        const onScroll = () => {
            const y = window.scrollY;
            nav?.classList.toggle('scrolled', y > 40);
            toTop?.classList.toggle('show', y > 500);
            const h = document.documentElement;
            const bar = document.getElementById('scrollProgress');
            if (bar) bar.style.width = (y / (h.scrollHeight - h.clientHeight) * 100) + '%';
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
        toTop?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        /* Auto-reveal common blocks on every page */
        document.querySelectorAll('main .card, main .section-title, main .stat-box, main form, main .gallery-tile')
            .forEach(el => { if (!el.closest('[data-reveal]')) el.setAttribute('data-reveal', ''); });

        /* Stagger siblings in the same row */
        document.querySelectorAll('.row').forEach(row => {
            [...row.querySelectorAll(':scope > * > [data-reveal], :scope > [data-reveal]')]
                .forEach((el, i) => el.style.setProperty('--delay', (i % 4) * 0.1 + 's'));
        });

        /* Animated counters */
        const animateCount = el => {
            const end = parseInt(el.dataset.count, 10);
            const suffix = el.dataset.suffix || '';
            const start = el.dataset.plain ? Math.max(0, end - 30) : 0;
            const t0 = performance.now(), dur = 1400;
            const step = t => {
                const p = Math.min(1, (t - t0) / dur);
                const eased = 1 - Math.pow(1 - p, 3);
                el.textContent = Math.round(start + (end - start) * eased) + suffix;
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };

        if ('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
            const io = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (!e.isIntersecting) return;
                    e.target.classList.add('in-view');
                    e.target.querySelectorAll?.('[data-count]').forEach(animateCount);
                    if (e.target.dataset.count) animateCount(e.target);
                    io.unobserve(e.target);
                });
            }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
            document.querySelectorAll('[data-reveal], .hero-stats').forEach(el => io.observe(el));
        } else {
            document.querySelectorAll('[data-reveal]').forEach(el => el.classList.add('in-view'));
        }
    })();
</script>
</body>
</html>
