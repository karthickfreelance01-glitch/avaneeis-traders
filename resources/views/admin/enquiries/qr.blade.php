@extends('admin.layouts.app')

@section('content')
@php
    $statusStyle = [
        'New' => ['#C90C0C', 'fa-bolt'],
        'Contacted' => ['#2563EB', 'fa-phone'],
        'Follow-up' => ['#D97706', 'fa-clock-rotate-left'],
        'Quotation Sent' => ['#7C3AED', 'fa-file-invoice'],
        'Converted' => ['#0B8A2A', 'fa-circle-check'],
        'Closed' => ['#6B7280', 'fa-box-archive'],
    ];
    $maxTrend = max(1, max(array_column($trend, 'count')));
    $phone = \App\Models\CompanySetting::valueFor('contact_phone_1', '98423 24074');
@endphp

<style>
    .qrp-hero { position: relative; overflow: hidden; border-radius: 22px; padding: 28px 30px; color: #fff;
        background: linear-gradient(120deg, #080C4A, #0E1471 55%, #C90C0C); background-size: 200% 200%; animation: qrpShift 14s ease infinite; }
    .qrp-hero::after { content: ''; position: absolute; width: 320px; height: 320px; border-radius: 50%; right: -90px; top: -140px; background: rgba(255,255,255,.08); }
    .qrp-hero h3 { font-weight: 700; margin: 0; }
    .qrp-hero p { opacity: .8; margin: 4px 0 0; }
    .qrp-hero .btn-light { color: #0E1471; font-weight: 600; }
    @keyframes qrpShift { 0%,100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

    .qrp-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 30px rgba(14,20,113,.07); padding: 24px; height: 100%; }
    .qrp-card h6 { font-weight: 700; color: #0E1471; }

    .qrp-poster { border-radius: 18px; padding: 20px 18px 16px; text-align: center; background: #fff; border: 1px solid #e7e9f5; position: relative; }
    .qrp-poster::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 6px; border-radius: 18px 18px 0 0; background: linear-gradient(90deg, #0E1471 50%, #C90C0C 50%); }
    .qrp-poster .logo { height: 40px; margin-bottom: 10px; }
    .qrp-poster .scan { font-weight: 700; color: #0E1471; letter-spacing: .02em; margin-bottom: 12px; }
    .qrp-poster #qrcode { display: inline-block; padding: 12px; border-radius: 14px; border: 3px solid #0E1471; position: relative; }
    .qrp-poster #qrcode img, .qrp-poster #qrcode canvas { display: block; }
    .qrp-poster .scanline { position: absolute; left: 12px; right: 12px; height: 2px; background: #C90C0C; box-shadow: 0 0 12px #C90C0C; top: 12px; animation: qrpScan 2.6s ease-in-out infinite; }
    @keyframes qrpScan { 0%,100% { top: 12px; } 50% { top: calc(100% - 14px); } }
    .qrp-poster .call { color: #C90C0C; font-weight: 600; margin-top: 10px; font-size: .9rem; }

    .qrp-link { background: #F4F6FB; border-radius: 12px; padding: 4px 4px 4px 12px; display: flex; align-items: center; gap: 6px; }
    .qrp-link input { border: 0; background: transparent; flex: 1; font-size: .85rem; color: #0E1471; min-width: 0; }
    .qrp-link input:focus { outline: none; }
    .qrp-link .btn { border-radius: 9px; }

    .qrp-stat { background: #fff; border-radius: 18px; padding: 18px; box-shadow: 0 10px 30px rgba(14,20,113,.07); display: flex; gap: 14px; align-items: center; height: 100%; transition: transform .25s ease; }
    .qrp-stat:hover { transform: translateY(-3px); }
    .qrp-stat .ic { width: 50px; height: 50px; border-radius: 14px; display: grid; place-items: center; font-size: 1.25rem; color: #fff; flex-shrink: 0; }
    .qrp-stat .num { font-size: 1.7rem; font-weight: 800; line-height: 1; color: #1B1D2E; }
    .qrp-stat .lbl { font-size: .8rem; color: #6B7080; margin-top: 4px; }

    .qrp-bars { display: flex; align-items: flex-end; gap: 10px; height: 130px; padding-top: 10px; }
    .qrp-bars .bar { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; gap: 6px; }
    .qrp-bars .fill { width: 100%; max-width: 34px; border-radius: 8px 8px 4px 4px; background: linear-gradient(180deg, #3340c9, #0E1471); min-height: 4px;
        transform-origin: bottom; animation: qrpGrow .9s cubic-bezier(.2,.7,.2,1) both; position: relative; }
    .qrp-bars .bar:last-child .fill { background: linear-gradient(180deg, #ff4d4d, #C90C0C); }
    .qrp-bars .fill span { position: absolute; top: -20px; left: 50%; transform: translateX(-50%); font-size: .72rem; font-weight: 700; color: #0E1471; }
    .qrp-bars small { color: #6B7080; font-size: .72rem; }
    @keyframes qrpGrow { from { transform: scaleY(0); } to { transform: scaleY(1); } }

    .qrp-tabs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; }
    .qrp-tabs a { white-space: nowrap; text-decoration: none; padding: 8px 14px; border-radius: 30px; background: #fff; color: #1B1D2E; font-size: .85rem; font-weight: 500;
        border: 1px solid #e7e9f5; transition: all .2s ease; }
    .qrp-tabs a .c { display: inline-block; min-width: 22px; padding: 0 6px; margin-left: 6px; border-radius: 20px; background: #F4F6FB; font-size: .75rem; text-align: center; }
    .qrp-tabs a:hover { border-color: #0E1471; }
    .qrp-tabs a.active { background: #0E1471; color: #fff; border-color: #0E1471; }
    .qrp-tabs a.active .c { background: rgba(255,255,255,.2); }

    .qrp-search { position: relative; }
    .qrp-search i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9aa0b4; }
    .qrp-search input { padding-left: 40px; border-radius: 12px; height: 44px; }

    .qrp-filter { padding: 18px 20px; height: auto; }
    .qrp-filter .form-label { color: #6B7080; font-weight: 500; }
    .qrp-input { border-radius: 12px; height: 44px; }
    .qrp-filter .btn { border-radius: 12px; height: 44px; display: inline-flex; align-items: center; }
    .qrp-quick .btn { border-radius: 0; }
    .qrp-quick .btn:first-child { border-radius: 12px 0 0 12px; }
    .qrp-quick .btn:last-child { border-radius: 0 12px 12px 0; }

    .qrp-table { border-collapse: separate; border-spacing: 0 8px; }
    .qrp-table thead th { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; color: #6B7080; border: 0; font-weight: 600; padding: 4px 14px; }
    .qrp-table tbody tr { background: #fff; box-shadow: 0 4px 14px rgba(14,20,113,.05); transition: transform .2s ease, box-shadow .2s ease; animation: qrpRow .5s ease both; }
    .qrp-table tbody tr:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(14,20,113,.1); }
    .qrp-table td { border: 0; padding: 14px; vertical-align: middle; }
    .qrp-table td:first-child { border-radius: 14px 0 0 14px; }
    .qrp-table td:last-child { border-radius: 0 14px 14px 0; }
    @keyframes qrpRow { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

    .qrp-avatar { width: 42px; height: 42px; border-radius: 12px; display: grid; place-items: center; font-weight: 700; color: #fff; flex-shrink: 0; }
    .qrp-ref { font-size: .75rem; color: #9aa0b4; }
    .qrp-chip { display: inline-block; font-size: .72rem; padding: 2px 8px; border-radius: 20px; background: #EEF0FB; color: #0E1471; }
    .qrp-icbtn { width: 34px; height: 34px; border-radius: 10px; display: inline-grid; place-items: center; border: 1px solid #e7e9f5; background: #fff; color: #0E1471; text-decoration: none; transition: all .2s ease; }
    .qrp-icbtn:hover { background: #0E1471; color: #fff; border-color: #0E1471; }
    .qrp-icbtn.wa { color: #16a34a; } .qrp-icbtn.wa:hover { background: #16a34a; color: #fff; border-color: #16a34a; }
    .qrp-icbtn.del { color: #C90C0C; } .qrp-icbtn.del:hover { background: #C90C0C; color: #fff; border-color: #C90C0C; }

    .qrp-status { border: 0; border-radius: 30px; font-size: .78rem; font-weight: 600; padding: 6px 30px 6px 12px; color: #fff; cursor: pointer;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat; background-position: right 10px center; background-size: 10px; appearance: none; }
    .qrp-status option { color: #1B1D2E; background: #fff; }

    .qrp-empty { text-align: center; padding: 60px 20px; }
    .qrp-empty .ic { width: 90px; height: 90px; border-radius: 26px; margin: 0 auto 16px; display: grid; place-items: center; font-size: 2.4rem; color: #0E1471; background: #EEF0FB; animation: qrpBob 3s ease-in-out infinite; }
    @keyframes qrpBob { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }

    /* Mobile: rows become cards */
    @media (max-width: 991px) {
        .qrp-table thead { display: none; }
        .qrp-table, .qrp-table tbody, .qrp-table tr, .qrp-table td { display: block; width: 100%; }
        .qrp-table tr { border-radius: 16px; margin-bottom: 12px; padding: 6px 0; }
        .qrp-table td { padding: 6px 16px; border-radius: 0 !important; }
        .qrp-table td[data-label]::before { content: attr(data-label); display: block; font-size: .7rem; text-transform: uppercase; letter-spacing: .05em; color: #9aa0b4; }
    }
    @media (prefers-reduced-motion: reduce) { .qrp-hero, .qrp-bars .fill, .qrp-table tbody tr, .scanline, .qrp-empty .ic { animation: none !important; } }
</style>

{{-- ============ HEADER ============ --}}
<div class="qrp-hero mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index:1">
        <div>
            <h3><i class="fa-solid fa-qrcode me-2"></i>QR Enquiries</h3>
            <p>Leads from customers who scanned your QR code. Call them while they're interested.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-light" type="button" id="qrDownload"><i class="fa-solid fa-download me-2"></i>Download Poster</button>
            <button class="btn btn-outline-light" type="button" id="qrPrint"><i class="fa-solid fa-print me-2"></i>Print</button>
        </div>
    </div>
</div>

@unless ($hasSource)
    <div class="alert alert-warning d-flex align-items-center gap-2">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div><b>One-time setup:</b> run <code>php artisan migrate</code> in the project folder so QR enquiries can be tracked.</div>
    </div>
@endunless

{{-- ============ STATS ============ --}}
<div class="row g-3 mb-4">
    @foreach ([
        ['Total QR leads', $stats['total'], 'fa-users', 'linear-gradient(135deg,#0E1471,#3340c9)'],
        ['New — call now', $stats['new'], 'fa-bolt', 'linear-gradient(135deg,#C90C0C,#ff4d4d)'],
        ['Received today', $stats['today'], 'fa-calendar-day', 'linear-gradient(135deg,#D97706,#fbbf24)'],
        ['Converted ('.$stats['rate'].'%)', $stats['converted'], 'fa-trophy', 'linear-gradient(135deg,#0B8A2A,#22c55e)'],
    ] as [$label, $num, $icon, $bg])
        <div class="col-6 col-xl-3">
            <div class="qrp-stat">
                <div class="ic" style="background:{{ $bg }}"><i class="fa-solid {{ $icon }}"></i></div>
                <div><div class="num">{{ $num }}</div><div class="lbl">{{ $label }}</div></div>
            </div>
        </div>
    @endforeach
</div>

{{-- ============ QR + TREND ============ --}}
<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="qrp-card">
            <div class="row g-3 align-items-center">
                <div class="col-sm-6">
                    <div class="qrp-poster">
                        <img src="{{ asset('assets/images/logo-full.jpg') }}" class="logo" alt="">
                        <div class="scan">SCAN TO ENQUIRE</div>
                        <div id="qrcode"><span class="scanline"></span></div>
                        <div class="call"><i class="fa-solid fa-phone me-1"></i>{{ $phone }}</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h6 class="mb-1">How it works</h6>
                    <ol class="small text-muted ps-3 mb-3">
                        <li>Print the poster for your shop, van or stall</li>
                        <li>Customer scans it with their phone camera</li>
                        <li>They fill the 5-field form</li>
                        <li>The lead shows up here instantly</li>
                    </ol>
                    <label class="form-label small mb-1">Link inside the QR</label>
                    <div class="qrp-link">
                        <input type="text" id="qrLink" value="{{ $qrUrl }}" aria-label="QR link">
                        <button class="btn btn-sm btn-primary" type="button" id="qrApply" title="Update QR"><i class="fa-solid fa-rotate"></i></button>
                        <button class="btn btn-sm btn-outline-primary" type="button" id="qrCopy" title="Copy link"><i class="fa-regular fa-copy"></i></button>
                        <a class="btn btn-sm btn-outline-primary" id="qrOpen" href="{{ $qrUrl }}" target="_blank" title="Open form"><i class="fa-solid fa-up-right-from-square"></i></a>
                    </div>
                    <div id="localWarn" class="small text-danger mt-2 d-none">
                        <i class="fa-solid fa-circle-exclamation me-1"></i>Phones can't open <b>127.0.0.1</b>. Put your live website address (or your PC's Wi-Fi IP) here and press <i class="fa-solid fa-rotate"></i>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="qrp-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-0">Last 7 days</h6>
                    <small class="text-muted">{{ $stats['week'] }} {{ \Illuminate\Support\Str::plural('scan enquiry', $stats['week']) }} this week</small>
                </div>
                <span class="qrp-chip"><i class="fa-solid fa-chart-column me-1"></i>Daily</span>
            </div>
            <div class="qrp-bars mt-3">
                @foreach ($trend as $i => $day)
                    <div class="bar">
                        <div class="fill" style="height: {{ max(3, $day['count'] / $maxTrend * 100) }}%; animation-delay: {{ $i * 0.07 }}s;">
                            @if ($day['count'])<span>{{ $day['count'] }}</span>@endif
                        </div>
                        <small>{{ $loop->last ? 'Today' : $day['label'] }}</small>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- ============ FILTERS ============ --}}
@php
    $keep = array_filter(request()->only(['search', 'how_found', 'product', 'location', 'from', 'to']), fn ($v) => $v !== null && $v !== '');
    $exportQuery = array_filter(request()->only(['search', 'status', 'how_found', 'product', 'location', 'from', 'to']), fn ($v) => $v !== null && $v !== '');
@endphp
<div class="qrp-card qrp-filter mb-3">
    <form method="GET" action="{{ route('admin.enquiries.qr') }}" id="qrFilterForm">
        @if (request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="form-label small mb-1">Search</label>
                <div class="qrp-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name, phone, place, product…">
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label class="form-label small mb-1">From date</label>
                <input type="date" name="from" value="{{ request('from') }}" class="form-control qrp-input">
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <label class="form-label small mb-1">To date</label>
                <input type="date" name="to" value="{{ request('to') }}" class="form-control qrp-input">
            </div>
            <div class="col-lg-4 col-md-6">
                <label class="form-label small mb-1">How found us</label>
                <select name="how_found" class="form-select qrp-input" @disabled(! $hasHowFound)>
                    <option value="">All sources</option>
                    @foreach ($howFoundOptions as $option)
                        <option value="{{ $option }}" @selected(request('how_found') === $option)>{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label small mb-1">Product</label>
                <input type="text" name="product" value="{{ request('product') }}" class="form-control qrp-input" placeholder="Product name">
            </div>
            <div class="col-lg-3 col-md-6">
                <label class="form-label small mb-1">Location</label>
                <input type="text" name="location" value="{{ request('location') }}" class="form-control qrp-input" placeholder="Town / District">
            </div>
            <div class="col-lg-6">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <div class="btn-group qrp-quick" role="group" aria-label="Quick date range">
                        <button type="button" class="btn btn-outline-primary" data-range="0">Today</button>
                        <button type="button" class="btn btn-outline-primary" data-range="6">7 days</button>
                        <button type="button" class="btn btn-outline-primary" data-range="29">30 days</button>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i>Apply</button>
                    @if ($filterCount || request('status'))
                        <a href="{{ route('admin.enquiries.qr') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark me-1"></i>Reset</a>
                    @endif
                    <a href="{{ route('admin.enquiries.qr.excel', $exportQuery) }}" class="btn btn-success"><i class="fa-solid fa-file-excel me-1"></i>Excel</a>
                    <a href="{{ route('admin.enquiries.qr.pdf', $exportQuery) }}" target="_blank" class="btn btn-danger"><i class="fa-solid fa-file-pdf me-1"></i>PDF</a>
                </div>
            </div>
        </div>
    </form>
    @unless ($hasHowFound)
        <div class="small text-danger mt-2"><i class="fa-solid fa-circle-exclamation me-1"></i>Run <code>php artisan migrate</code> to enable the "How found us" field.</div>
    @endunless
</div>

<div class="d-flex flex-column flex-lg-row gap-2 justify-content-between align-items-lg-center mb-2">
    <div class="qrp-tabs">
        <a href="{{ route('admin.enquiries.qr', $keep) }}" class="{{ request('status') ? '' : 'active' }}">All<span class="c">{{ $stats['total'] }}</span></a>
        @foreach ($statuses as $st)
            <a href="{{ route('admin.enquiries.qr', array_merge($keep, ['status' => $st])) }}" class="{{ request('status') === $st ? 'active' : '' }}">{{ $st }}<span class="c">{{ $statusCounts[$st] }}</span></a>
        @endforeach
    </div>
    @if ($filterCount)
        <small class="text-muted text-nowrap"><i class="fa-solid fa-filter me-1"></i>{{ $enquiries->total() }} {{ \Illuminate\Support\Str::plural('result', $enquiries->total()) }} for current filters</small>
    @endif
</div>

{{-- ============ LIST ============ --}}
@if ($enquiries->count())
    <div class="table-responsive">
        <table class="table qrp-table mb-0">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Interested in</th>
                    <th>Found us via</th>
                    <th>Received</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($enquiries as $enquiry)
                    @php
                        $digits = preg_replace('/[^0-9]/', '', $enquiry->phone);
                        $initials = collect(explode(' ', trim($enquiry->customer_name)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                        $hue = crc32($enquiry->customer_name) % 360;
                        [$color] = $statusStyle[$enquiry->status] ?? ['#0E1471'];
                    @endphp
                    <tr style="animation-delay: {{ $loop->index * 0.04 }}s">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="qrp-avatar" style="background: hsl({{ $hue }}, 55%, 45%)">{{ $initials ?: '?' }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $enquiry->customer_name }}</div>
                                    <div class="qrp-ref">{{ $enquiry->enquiry_reference }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Contact">
                            <div class="fw-medium mb-1">{{ $enquiry->phone }}</div>
                            <a href="tel:{{ $digits }}" class="qrp-icbtn" title="Call"><i class="fa-solid fa-phone"></i></a>
                            <a href="https://wa.me/{{ strlen($digits) === 10 ? '91'.$digits : $digits }}" target="_blank" class="qrp-icbtn wa" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                        </td>
                        <td data-label="Location"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $enquiry->city ?? '—' }}</td>
                        <td data-label="Interested in">
                            <div class="fw-medium">{{ $enquiry->interested_product ?? $enquiry->product->name ?? '—' }}</div>
                            <span class="qrp-chip">{{ $enquiry->product_category ?? $enquiry->category->name ?? 'General' }}</span>
                        </td>
                        <td data-label="Found us via">
                            @if ($enquiry->how_found)
                                <span class="qrp-chip"><i class="fa-solid fa-bullhorn me-1"></i>{{ $enquiry->how_found }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td data-label="Received" class="text-nowrap">
                            <div>{{ $enquiry->created_at?->diffForHumans() }}</div>
                            <small class="text-muted">{{ $enquiry->created_at?->format('d M, h:i A') }}</small>
                        </td>
                        <td data-label="Status">
                            <form method="POST" action="{{ route('admin.enquiries.updateStatus', $enquiry) }}">
                                @csrf
                                <select name="status" class="qrp-status" style="background-color: {{ $color }}" onchange="this.form.submit()" aria-label="Change status">
                                    @foreach ($statuses as $st)
                                        <option value="{{ $st }}" @selected($enquiry->status === $st)>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="qrp-icbtn" title="View details"><i class="fa-solid fa-eye"></i></a>
                            <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" class="d-inline" onsubmit="return confirm('Delete this enquiry?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="qrp-icbtn del" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <small class="text-muted">Showing {{ $enquiries->firstItem() }}–{{ $enquiries->lastItem() }} of {{ $enquiries->total() }}</small>
        {{ $enquiries->links('pagination::bootstrap-5') }}
    </div>
@else
    <div class="qrp-card qrp-empty">
        <div class="ic"><i class="fa-solid fa-qrcode"></i></div>
        @if ($filterCount || request('status'))
            <h5 class="fw-bold">No matching enquiries</h5>
            <p class="text-muted">Try a different search, date range or status.</p>
            <a href="{{ route('admin.enquiries.qr') }}" class="btn btn-outline-primary">Clear filters</a>
        @else
            <h5 class="fw-bold">No QR enquiries yet</h5>
            <p class="text-muted mb-3">Download the poster, print it, and place it where customers can scan it.</p>
            <button class="btn btn-danger" type="button" onclick="document.getElementById('qrDownload').click()"><i class="fa-solid fa-download me-2"></i>Download Poster</button>
        @endif
    </div>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
(function () {
    /* Quick date range buttons */
    const f = document.getElementById('qrFilterForm');
    const iso = d => new Date(d.getTime() - d.getTimezoneOffset() * 60000).toISOString().slice(0, 10);
    f?.querySelectorAll('[data-range]').forEach(btn => btn.addEventListener('click', () => {
        const to = new Date(), from = new Date();
        from.setDate(to.getDate() - Number(btn.dataset.range));
        f.elements.from.value = iso(from);
        f.elements.to.value = iso(to);
        f.submit();
    }));
})();
</script>
<script>
(function () {
    const box = document.getElementById('qrcode');
    const input = document.getElementById('qrLink');
    const warn = document.getElementById('localWarn');
    const openBtn = document.getElementById('qrOpen');
    const logoSrc = @json(asset('assets/images/logo-full.jpg'));
    const phone = {!! json_encode($phone) !!};

    function render() {
        const url = input.value.trim();
        box.querySelectorAll('canvas, img').forEach(n => n.remove());
        box.removeAttribute('title');
        new QRCode(box, { text: url, width: 150, height: 150, colorDark: '#0E1471', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H });
        box.removeAttribute('title');
        openBtn.href = url;
        warn.classList.toggle('d-none', !/\/\/(127\.0\.0\.1|localhost)/i.test(url));
    }
    document.getElementById('qrApply').addEventListener('click', render);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); render(); } });
    render();

    document.getElementById('qrCopy').addEventListener('click', e => {
        const btn = e.currentTarget;
        navigator.clipboard?.writeText(input.value.trim()).then(() => {
            btn.innerHTML = '<i class="fa-solid fa-check"></i>';
            setTimeout(() => btn.innerHTML = '<i class="fa-regular fa-copy"></i>', 1500);
        });
    });

    /* Build a high-resolution QR just for the poster */
    function bigQr() {
        const tmp = document.createElement('div');
        new QRCode(tmp, { text: input.value.trim(), width: 920, height: 920, colorDark: '#0E1471', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.H });
        return tmp.querySelector('canvas');
    }

    function buildPoster(cb) {
        const W = 1240, H = 1754, c = document.createElement('canvas'); // A5-ish @ 150dpi
        c.width = W; c.height = H;
        const x = c.getContext('2d');
        x.fillStyle = '#fff'; x.fillRect(0, 0, W, H);
        x.fillStyle = '#0E1471'; x.fillRect(0, 0, W / 2, 26);
        x.fillStyle = '#C90C0C'; x.fillRect(W / 2, 0, W / 2, 26);
        const logo = new Image();
        logo.onload = () => {
            const lw = 800, lh = lw * logo.height / logo.width;
            x.drawImage(logo, (W - lw) / 2, 90, lw, lh);
            let y = 90 + lh + 80;
            x.textAlign = 'center';
            x.fillStyle = '#0E1471'; x.font = 'bold 76px Poppins, Arial'; x.fillText('SCAN TO ENQUIRE', W / 2, y);
            x.fillStyle = '#555'; x.font = '38px Poppins, Arial'; x.fillText('Get a quote for poultry farm equipment', W / 2, y + 66);
            const size = 760, qx = (W - size) / 2, qy = y + 120;
            x.strokeStyle = '#0E1471'; x.lineWidth = 12;
            if (x.roundRect) { x.beginPath(); x.roundRect(qx - 40, qy - 40, size + 80, size + 80, 40); x.stroke(); }
            else x.strokeRect(qx - 40, qy - 40, size + 80, size + 80);
            x.drawImage(bigQr(), qx, qy, size, size);
            const by = qy + size + 90;
            x.fillStyle = '#C90C0C';
            if (x.roundRect) { x.beginPath(); x.roundRect(W / 2 - 330, by, 660, 100, 50); x.fill(); } else x.fillRect(W / 2 - 330, by, 660, 100);
            x.fillStyle = '#fff'; x.font = 'bold 46px Poppins, Arial'; x.fillText('Call: ' + phone, W / 2, by + 66);
            x.fillStyle = '#0E1471'; x.fillRect(0, H - 26, W / 2, 26);
            x.fillStyle = '#C90C0C'; x.fillRect(W / 2, H - 26, W / 2, 26);
            cb(c);
        };
        logo.onerror = () => cb(bigQr());
        logo.src = logoSrc;
    }

    document.getElementById('qrDownload').addEventListener('click', () => buildPoster(c => {
        const a = document.createElement('a');
        a.download = 'avaneeis-enquiry-qr.png';
        a.href = c.toDataURL('image/png');
        a.click();
    }));

    document.getElementById('qrPrint').addEventListener('click', () => buildPoster(c => {
        const w = window.open('', '_blank');
        if (!w) return;
        w.document.write('<html><head><title>Enquiry QR</title><style>@page{margin:10mm}body{margin:0;display:flex;justify-content:center}img{max-width:100%;max-height:100vh}</style></head><body><img src="' + c.toDataURL('image/png') + '" onload="setTimeout(()=>window.print(),200)"></body></html>');
        w.document.close();
    }));
})();
</script>
@endsection
