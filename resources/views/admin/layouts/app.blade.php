<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $titles = [
            'admin.dashboard' => ['Dashboard', 'Overview of your business'],
            'admin.products.*' => ['Products', 'Manage your product catalogue'],
            'admin.categories.*' => ['Categories', 'Group your products'],
            'admin.subcategories.*' => ['Subcategories', 'Organise products inside categories'],
            'admin.enquiries.qr' => ['QR Enquiries', 'Leads from QR code scans'],
            'admin.enquiries.*' => ['Enquiries', 'Quote requests from customers'],
            'admin.banners.*' => ['Banners', 'Home page highlight cards'],
            'admin.gallery.*' => ['Gallery', 'Photos shown on the website'],
            'admin.settings.*' => ['Settings', 'Company details used on the website'],
            'admin.profile' => ['Profile', 'Your admin account'],
        ];
        $pageTitle = 'Admin'; $pageSub = '';
        foreach ($titles as $pattern => [$t, $s]) {
            if (request()->routeIs($pattern)) { $pageTitle = $t; $pageSub = $s; break; }
        }
        $isForm = request()->routeIs('*.create') || request()->routeIs('*.edit');
        $admin = auth('admin')->user();
        $adminInitials = collect(explode(' ', $admin->name ?? 'Admin'))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');

        try {
            $newEnquiries = \App\Models\Enquiry::where('status', 'New')->count();
            $latestNew = \App\Models\Enquiry::where('status', 'New')->latest()->limit(5)->get();
        } catch (\Throwable $e) { $newEnquiries = 0; $latestNew = collect(); }
        try { $qrNew = \App\Models\Enquiry::where('source', 'qr')->where('status', 'New')->count(); } catch (\Throwable $e) { $qrNew = 0; }

        $nav = [
            'Main' => [
                ['admin.dashboard', 'admin.dashboard', 'fa-gauge-high', 'Dashboard', null],
            ],
            'Leads' => [
                ['admin.enquiries.index', 'admin.enquiries.index|admin.enquiries.show', 'fa-envelope-open-text', 'Enquiries', $newEnquiries],
                ['admin.enquiries.qr', 'admin.enquiries.qr', 'fa-qrcode', 'QR Enquiries', $qrNew],
            ],
            'Catalogue' => [
                ['admin.products.index', 'admin.products.*', 'fa-box-open', 'Products', null],
                ['admin.categories.index', 'admin.categories.*', 'fa-tags', 'Categories', null],
                ['admin.subcategories.index', 'admin.subcategories.*', 'fa-folder-tree', 'Subcategories', null],
            ],
            'Website' => [
                ['admin.banners.index', 'admin.banners.*', 'fa-panorama', 'Banners', null],
                ['admin.gallery.index', 'admin.gallery.*', 'fa-images', 'Gallery', null],
                ['admin.settings.index', 'admin.settings.*', 'fa-sliders', 'Settings', null],
            ],
        ];
    @endphp
    <title>{{ $pageTitle }} | AVANEEIS Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/admin.css') }}" rel="stylesheet">
</head>
<body class="admin-body">

<aside class="adm-sidebar" id="admSidebar">
    <a href="{{ route('admin.dashboard') }}" class="adm-brand">
        <img src="{{ asset('assets/images/logo-icon.jpg') }}" alt="">
        <span><b>AVANEEIS</b><small>TRADERS · ADMIN</small></span>
    </a>

    <nav class="adm-nav">
        @foreach ($nav as $group => $items)
            <div class="grp">{{ $group }}</div>
            @foreach ($items as [$route, $match, $icon, $label, $count])
                @php $active = collect(explode('|', $match))->contains(fn ($m) => request()->routeIs($m)); @endphp
                <a href="{{ route($route) }}" class="{{ $active ? 'active' : '' }}">
                    <i class="fa-solid {{ $icon }}"></i>{{ $label }}
                    @if ($count)<span class="pill">{{ $count }}</span>@endif
                </a>
            @endforeach
        @endforeach
    </nav>

    <div class="adm-user">
        <a href="{{ route('admin.profile') }}" class="av text-decoration-none">{{ $adminInitials ?: 'A' }}</a>
        <div class="overflow-hidden">
            <div class="nm">{{ $admin->name ?? 'Admin' }}</div>
            <div class="em">{{ $admin->email ?? '' }}</div>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST" class="ms-auto">
            @csrf
            <button type="submit" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></button>
        </form>
    </div>
</aside>
<div class="adm-backdrop" id="admBackdrop"></div>

<div class="adm-main">
    <header class="adm-top" id="admTop">
        <button class="adm-iconbtn adm-menu-btn" id="admMenu" type="button" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
        <div class="me-auto">
            <div class="crumb">
                <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a>
                <i class="fa-solid fa-chevron-right mx-1" style="font-size:.6rem"></i>{{ $pageTitle }}
                @if ($isForm)<i class="fa-solid fa-chevron-right mx-1" style="font-size:.6rem"></i>{{ request()->routeIs('*.create') ? 'Add new' : 'Edit' }}@endif
            </div>
            <h1 class="ttl">{{ $pageTitle }}</h1>
        </div>

        <a href="{{ route('home') }}" target="_blank" class="adm-iconbtn d-none d-sm-inline-grid" title="View website"><i class="fa-solid fa-globe"></i></a>

        <div class="dropdown">
            <button class="adm-iconbtn" data-bs-toggle="dropdown" aria-label="Notifications" type="button">
                <i class="fa-solid fa-bell"></i>
                @if ($newEnquiries)<span class="dot pulse">{{ $newEnquiries > 99 ? '99+' : $newEnquiries }}</span>@endif
            </button>
            <div class="dropdown-menu dropdown-menu-end notif-menu">
                <div class="d-flex justify-content-between align-items-center px-2 py-1 mb-1">
                    <b>New enquiries</b>
                    <span class="chip">{{ $newEnquiries }}</span>
                </div>
                @forelse ($latestNew as $n)
                    <a class="dropdown-item notif-item" href="{{ route('admin.enquiries.show', $n) }}">
                        <span class="ic"><i class="fa-solid {{ ($n->source ?? '') === 'qr' ? 'fa-qrcode' : 'fa-envelope' }}"></i></span>
                        <span>
                            <b class="d-block">{{ $n->customer_name }}</b>
                            <small class="text-muted">{{ $n->interested_product ?? 'General enquiry' }} · {{ $n->created_at?->diffForHumans() }}</small>
                        </span>
                    </a>
                @empty
                    <div class="text-center text-muted small py-3"><i class="fa-regular fa-bell-slash d-block fs-4 mb-1"></i>You're all caught up</div>
                @endforelse
                <hr class="my-1">
                <a class="dropdown-item text-center small fw-semibold" href="{{ route('admin.enquiries.index') }}">View all enquiries</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="adm-iconbtn fw-bold" data-bs-toggle="dropdown" type="button" style="background:var(--navy);color:#fff;border-color:var(--navy)">{{ $adminInitials ?: 'A' }}</button>
            <div class="dropdown-menu dropdown-menu-end">
                <div class="px-3 py-2"><b class="d-block">{{ $admin->name ?? 'Admin' }}</b><small class="text-muted">{{ $admin->email ?? '' }}</small></div>
                <hr class="my-1">
                <a class="dropdown-item" href="{{ route('admin.profile') }}"><i class="fa-regular fa-user me-2"></i>Profile</a>
                <a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-sliders me-2"></i>Settings</a>
                <a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="fa-solid fa-globe me-2"></i>View website</a>
                <hr class="my-1">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button class="dropdown-item text-danger" type="submit"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="adm-content">
        @yield('content')
    </div>

    <footer class="adm-foot d-flex justify-content-between flex-wrap gap-2">
        <span>© {{ date('Y') }} AVANEEIS TRADERS</span>
        <span>The Complete Poultry Solutions</span>
    </footer>
</div>

@if (session('success') || session('error') || $errors->any())
    <div class="adm-toast {{ session('success') ? '' : 'err' }}" id="admToast" role="status">
        <span class="ic"><i class="fa-solid {{ session('success') ? 'fa-check' : 'fa-xmark' }}"></i></span>
        <div class="small fw-medium">{{ session('success') ?? session('error') ?? $errors->first() }}</div>
        <button type="button" class="btn-close btn-sm ms-2" onclick="this.parentElement.classList.add('hide')" aria-label="Close"></button>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const body = document.body;
    document.getElementById('admMenu')?.addEventListener('click', () => body.classList.toggle('sb-open'));
    document.getElementById('admBackdrop')?.addEventListener('click', () => body.classList.remove('sb-open'));

    const top = document.getElementById('admTop');
    const onScroll = () => top.classList.toggle('scrolled', window.scrollY > 8);
    window.addEventListener('scroll', onScroll, { passive: true }); onScroll();

    const toast = document.getElementById('admToast');
    if (toast) setTimeout(() => toast.classList.add('hide'), 4500);

    /* Instant search for any list:  <input data-table-search="#tableId"> */
    document.querySelectorAll('[data-table-search]').forEach(input => {
        const target = document.querySelector(input.dataset.tableSearch);
        if (!target) return;
        const rows = () => target.querySelectorAll('tbody tr, [data-search-item]');
        input.addEventListener('input', () => {
            const q = input.value.trim().toLowerCase();
            let shown = 0;
            rows().forEach(r => {
                const hit = !q || r.textContent.toLowerCase().includes(q);
                r.style.display = hit ? '' : 'none';
                if (hit) shown++;
            });
            const empty = document.querySelector(input.dataset.empty || '#noResults');
            if (empty) empty.classList.toggle('d-none', shown > 0);
        });
    });
})();
</script>
</body>
</html>
