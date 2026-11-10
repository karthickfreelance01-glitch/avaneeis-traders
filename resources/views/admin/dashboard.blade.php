@extends('admin.layouts.app')

@section('content')
@php
    $img = fn ($p) => empty($p) ? null : (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://']) ? $p : Storage::url($p));
    $statusColors = ['New' => '#C90C0C', 'Contacted' => '#2563EB', 'Follow-up' => '#D97706', 'Quotation Sent' => '#7C3AED', 'Converted' => '#0B8A2A', 'Closed' => '#9CA3AF'];
    $statusPill = ['New' => 'pill-red', 'Contacted' => 'pill-blue', 'Follow-up' => 'pill-amber', 'Quotation Sent' => 'pill-violet', 'Converted' => 'pill-green', 'Closed' => 'pill-grey'];
    $totalStatus = max(1, array_sum($statusCounts));
    $acc = 0; $segments = [];
    foreach ($statusColors as $st => $col) {
        $n = $statusCounts[$st] ?? 0;
        if (!$n) continue;
        $start = $acc / $totalStatus * 360; $acc += $n; $end = $acc / $totalStatus * 360;
        $segments[] = "$col {$start}deg {$end}deg";
    }
    $donut = $segments ? 'conic-gradient('.implode(', ', $segments).')' : 'conic-gradient(#EEF0FB 0deg 360deg)';
    $maxTrend = max(1, max(array_column($trend, 'total')));
    $maxCat = max(1, $categoryStats->max('products_count') ?? 1);
    $hour = (int) now()->format('G');
    $greet = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

{{-- Welcome --}}
<div class="welcome mb-4">
    <div class="position-relative" style="z-index:1; max-width: 640px;">
        <div class="small opacity-75 mb-1">{{ now()->format('l, d F Y') }}</div>
        <h3 class="fw-bold mb-2">{{ $greet }}, {{ \Illuminate\Support\Str::before(auth('admin')->user()->name ?? 'Admin', ' ') }} 👋</h3>
        <p class="mb-3 opacity-75">
            @if ($stats['new_enquiries'])
                You have <b class="text-white">{{ $stats['new_enquiries'] }} new {{ \Illuminate\Support\Str::plural('enquiry', $stats['new_enquiries']) }}</b> waiting for a call back.
            @else
                No pending enquiries — great job staying on top of leads!
            @endif
        </p>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.enquiries.index') }}" class="btn btn-light fw-semibold" style="color:var(--navy)"><i class="fa-solid fa-envelope-open-text me-2"></i>View enquiries</a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-outline-light"><i class="fa-solid fa-plus me-2"></i>Add product</a>
        </div>
    </div>
    <img src="{{ asset('assets/images/logo-chicken-hero.jpg') }}" class="mascot" alt="">
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    @foreach ([
        ['New enquiries', $stats['new_enquiries'], 'fa-bolt', 'linear-gradient(135deg,#C90C0C,#ff4d4d)', route('admin.enquiries.index')],
        ['Enquiries today', $stats['today_enquiries'], 'fa-calendar-day', 'linear-gradient(135deg,#D97706,#fbbf24)', route('admin.enquiries.index')],
        ['Total enquiries', $stats['total_enquiries'], 'fa-inbox', 'linear-gradient(135deg,#0E1471,#3340c9)', route('admin.enquiries.index')],
        ['QR scan leads', $stats['qr_enquiries'], 'fa-qrcode', 'linear-gradient(135deg,#7C3AED,#a78bfa)', route('admin.enquiries.qr')],
        ['Converted · '.$stats['conversion_rate'].'%', $stats['converted'], 'fa-trophy', 'linear-gradient(135deg,#0B8A2A,#22c55e)', route('admin.enquiries.index')],
        ['Products', $stats['products'], 'fa-box-open', 'linear-gradient(135deg,#0891B2,#22d3ee)', route('admin.products.index')],
        ['Categories', $stats['categories'].' / '.$stats['subcategories'], 'fa-tags', 'linear-gradient(135deg,#BE185D,#f472b6)', route('admin.categories.index')],
        ['Featured products', $stats['featured_products'], 'fa-star', 'linear-gradient(135deg,#CA8A04,#facc15)', route('admin.products.index')],
    ] as $i => [$label, $num, $icon, $bg, $link])
        <div class="col-6 col-lg-3">
            <a href="{{ $link }}" class="kpi" style="animation-delay: {{ $i * 0.05 }}s">
                <div class="ic" style="background:{{ $bg }}"><i class="fa-solid {{ $icon }}"></i></div>
                <div><div class="num">{{ $num }}</div><div class="lbl">{{ $label }}</div></div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    {{-- Trend --}}
    <div class="col-xl-8">
        <div class="panel h-100">
            <div class="panel-head">
                <div>
                    <h5 class="panel-title">Enquiries — last 14 days</h5>
                    <small class="text-muted">{{ array_sum(array_column($trend, 'total')) }} enquiries in this period</small>
                </div>
                <div class="d-flex gap-3 small">
                    <span><i class="fa-solid fa-square me-1" style="color:#0E1471"></i>Website</span>
                    <span><i class="fa-solid fa-square me-1" style="color:#C90C0C"></i>QR scan</span>
                </div>
            </div>
            <div class="bars">
                @foreach ($trend as $i => $d)
                    <div class="b">
                        <div class="d-flex flex-column justify-content-end w-100 align-items-center" style="height: 100%; max-width: 26px;">
                            @php $web = $d['total'] - $d['qr']; @endphp
                            @if ($d['qr'])
                                <div class="f qr" data-v="{{ $d['qr'] }} QR" style="height: {{ $d['qr'] / $maxTrend * 100 }}%; border-radius: {{ $web ? '7px 7px 0 0' : '7px 7px 3px 3px' }}; animation-delay: {{ $i * 0.04 }}s"></div>
                            @endif
                            <div class="f" data-v="{{ $d['day'] }}: {{ $d['total'] }}" style="height: {{ $d['total'] ? max(2, $web / $maxTrend * 100) : 2 }}%; {{ $d['qr'] ? 'border-radius: 0 0 3px 3px;' : '' }} {{ !$d['total'] ? 'opacity:.35;' : '' }} animation-delay: {{ $i * 0.04 }}s"></div>
                        </div>
                        <small class="{{ $loop->last ? 'fw-bold text-danger' : '' }}">{{ $loop->last ? 'Today' : $d['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Status donut --}}
    <div class="col-xl-4">
        <div class="panel h-100">
            <div class="panel-head"><h5 class="panel-title">Enquiry status</h5></div>
            <div class="row align-items-center g-3">
                <div class="col-sm-6 col-xl-12">
                    <div class="donut" style="background: {{ $donut }}">
                        <div class="mid"><div><b>{{ array_sum($statusCounts) }}</b><span>Total</span></div></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-12">
                    <ul class="legend">
                        @foreach ($statusColors as $st => $col)
                            <li><i style="background:{{ $col }}"></i>{{ $st }}<b>{{ $statusCounts[$st] ?? 0 }}</b></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Recent enquiries --}}
    <div class="col-xl-7">
        <div class="panel h-100">
            <div class="panel-head">
                <h5 class="panel-title">Recent enquiries</h5>
                <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
            </div>
            @forelse ($recentEnquiries as $enquiry)
                @php
                    $ini = collect(explode(' ', trim($enquiry->customer_name)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                    $hue = crc32($enquiry->customer_name) % 360;
                @endphp
                <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="list-row">
                    <div class="av-sm" style="background:hsl({{ $hue }},55%,45%)">{{ $ini ?: '?' }}</div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold text-truncate">
                            {{ $enquiry->customer_name }}
                            @if (($enquiry->source ?? '') === 'qr')<i class="fa-solid fa-qrcode text-danger ms-1" title="QR scan"></i>@endif
                        </div>
                        <small class="text-muted text-truncate d-block">{{ $enquiry->interested_product ?? $enquiry->product->name ?? 'General enquiry' }} · {{ $enquiry->city ?? $enquiry->phone }}</small>
                    </div>
                    <div class="text-end">
                        <span class="pill-soft {{ $statusPill[$enquiry->status] ?? 'pill-navy' }}">{{ $enquiry->status }}</span>
                        <small class="d-block text-muted mt-1">{{ $enquiry->created_at?->diffForHumans(null, true) }}</small>
                    </div>
                </a>
            @empty
                <div class="text-center text-muted py-5"><i class="fa-regular fa-envelope fs-2 d-block mb-2 opacity-50"></i>No enquiries yet</div>
            @endforelse
        </div>
    </div>

    {{-- Quick actions + categories --}}
    <div class="col-xl-5">
        <div class="panel mb-4">
            <div class="panel-head"><h5 class="panel-title">Quick actions</h5></div>
            <div class="row g-2 quick">
                <div class="col-4"><a href="{{ route('admin.products.create') }}"><i class="fa-solid fa-box-open"></i>Add product</a></div>
                <div class="col-4"><a href="{{ route('admin.categories.create') }}"><i class="fa-solid fa-tags"></i>Add category</a></div>
                <div class="col-4"><a href="{{ route('admin.enquiries.qr') }}"><i class="fa-solid fa-qrcode"></i>QR poster</a></div>
                <div class="col-4"><a href="{{ route('admin.gallery.create') }}"><i class="fa-solid fa-image"></i>Add photo</a></div>
                <div class="col-4"><a href="{{ route('admin.banners.create') }}"><i class="fa-solid fa-panorama"></i>Add banner</a></div>
                <div class="col-4"><a href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-sliders"></i>Settings</a></div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-head">
                <h5 class="panel-title">Products by category</h5>
                <a href="{{ route('admin.categories.index') }}" class="small text-decoration-none">Manage</a>
            </div>
            @forelse ($categoryStats->take(6) as $category)
                <div class="mb-3">
                    <div class="d-flex justify-content-between small mb-1"><span class="fw-medium">{{ $category->name }}</span><b>{{ $category->products_count }}</b></div>
                    <div class="progress-thin"><span style="width: {{ $category->products_count / $maxCat * 100 }}%; animation-delay: {{ $loop->index * 0.08 }}s"></span></div>
                </div>
            @empty
                <p class="text-muted small mb-0">No categories yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Latest products --}}
<div class="panel">
    <div class="panel-head">
        <h5 class="panel-title">Latest products</h5>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">All products</a>
    </div>
    <div class="row g-3">
        @forelse ($latestProducts as $product)
            <div class="col-sm-6 col-lg">
                <a href="{{ route('admin.products.edit', $product) }}" class="list-row border rounded-4 h-100" style="border-color: var(--line) !important; border-top-style: solid !important;">
                    @if ($img($product->image))
                        <img src="{{ $img($product->image) }}" class="thumb" alt="">
                    @else
                        <div class="thumb-ph"><i class="fa-solid fa-box"></i></div>
                    @endif
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-truncate">{{ $product->name }}</div>
                        <span class="chip">{{ $product->category->name ?? '—' }}</span>
                    </div>
                </a>
            </div>
        @empty
            <div class="col text-muted">No products yet.</div>
        @endforelse
    </div>
</div>
@endsection
