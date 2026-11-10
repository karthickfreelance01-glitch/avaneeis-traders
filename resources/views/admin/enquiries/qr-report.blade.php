<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR Enquiry Report - {{ now()->format('d M Y') }}</title>
    <style>
        @page { size: A4 landscape; margin: 12mm 10mm; }
        * { box-sizing: border-box; }
        body { font-family: {{ $forPdf ? "'DejaVu Sans', sans-serif" : "'Poppins', 'Segoe UI', Arial, sans-serif" }}; color: #1B1D2E; font-size: {{ $forPdf ? '9px' : '12px' }}; margin: 0; background: #fff; }
        .page { max-width: 1200px; margin: 0 auto; padding: {{ $forPdf ? '0' : '24px' }}; }
        .bar { height: 6px; background: #0E1471; }
        .bar span { display: block; width: 50%; height: 6px; background: #C90C0C; margin-left: 50%; }
        table.head { width: 100%; border-collapse: collapse; margin: 14px 0 10px; }
        table.head td { vertical-align: middle; }
        .company { font-size: 20px; font-weight: bold; color: #0E1471; letter-spacing: .02em; }
        .sub { color: #6B7080; margin-top: 2px; }
        .title { font-size: 15px; font-weight: bold; color: #C90C0C; text-align: right; }
        .meta { color: #6B7080; text-align: right; margin-top: 2px; }
        .filters { background: #F4F6FB; border-radius: 6px; padding: 8px 10px; margin-bottom: 10px; }
        .filters b { color: #0E1471; }
        .filters .f { display: inline-block; margin-right: 14px; }
        table.sum { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 0 -6px 12px; }
        table.sum td { background: #fff; border: 1px solid #e1e4f2; border-radius: 6px; padding: 8px 10px; vertical-align: top; }
        .sum .n { font-size: 18px; font-weight: bold; color: #0E1471; }
        .sum .l { color: #6B7080; font-size: .9em; }
        .sum .row { white-space: nowrap; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #0E1471; color: #fff; text-align: left; padding: 6px 6px; font-size: .92em; font-weight: bold; }
        table.data td { padding: 5px 6px; border-bottom: 1px solid #e7e9f5; vertical-align: top; }
        table.data tr:nth-child(even) td { background: #F8F9FD; }
        table.data thead { display: table-header-group; }
        table.data tr { page-break-inside: avoid; }
        .st { display: inline-block; padding: 1px 6px; border-radius: 8px; color: #fff; font-size: .9em; white-space: nowrap; }
        .muted { color: #9aa0b4; }
        .empty { text-align: center; padding: 30px; color: #6B7080; }
        .foot { margin-top: 12px; color: #9aa0b4; font-size: .9em; text-align: center; }
        .toolbar { position: sticky; top: 0; background: #0E1471; color: #fff; padding: 10px 24px; display: flex; gap: 10px; align-items: center; justify-content: space-between; z-index: 5; }
        .toolbar button, .toolbar a { border: 0; border-radius: 8px; padding: 8px 14px; font: inherit; font-weight: 600; cursor: pointer; text-decoration: none; }
        .toolbar .p { background: #C90C0C; color: #fff; }
        .toolbar .b { background: rgba(255,255,255,.15); color: #fff; }
        @media print {
            .toolbar { display: none; }
            .page { padding: 0; }
            body { font-size: 10px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
@php
    $statusColor = ['New' => '#C90C0C', 'Contacted' => '#2563EB', 'Follow-up' => '#D97706', 'Quotation Sent' => '#7C3AED', 'Converted' => '#0B8A2A', 'Closed' => '#6B7280'];
@endphp

@unless ($forPdf)
    <div class="toolbar">
        <div><b>QR Enquiry Report</b> &nbsp;·&nbsp; {{ $summary['total'] }} {{ \Illuminate\Support\Str::plural('enquiry', $summary['total']) }}</div>
        <div style="display:flex; gap:8px">
            <a class="b" href="{{ route('admin.enquiries.qr', request()->query()) }}">&larr; Back</a>
            <button class="p" type="button" onclick="window.print()">Save as PDF / Print</button>
        </div>
    </div>
@endunless

<div class="page">
    <div class="bar"><span></span></div>

    <table class="head">
        <tr>
            <td>
                <div class="company">{{ $company }}</div>
                <div class="sub">Phone: {{ $phone }}</div>
            </td>
            <td>
                <div class="title">QR ENQUIRY REPORT</div>
                <div class="meta">Generated on {{ now()->format('d M Y, h:i A') }}</div>
            </td>
        </tr>
    </table>

    <div class="filters">
        <b>Filters:</b>
        @forelse ($filters as $label => $value)
            <span class="f">{{ $label }}: <b>{{ $value }}</b></span>
        @empty
            <span class="f">All QR enquiries</span>
        @endforelse
    </div>

    <table class="sum">
        <tr>
            <td style="width:16%">
                <div class="n">{{ $summary['total'] }}</div>
                <div class="l">Total enquiries</div>
            </td>
            <td style="width:34%">
                <div class="l" style="margin-bottom:3px">By status</div>
                @forelse ($summary['byStatus'] as $st => $c)
                    <span class="row" style="margin-right:8px"><span class="st" style="background: {{ $statusColor[$st] ?? '#0E1471' }}">{{ $st }}</span> {{ $c }}</span>
                @empty
                    <span class="muted">—</span>
                @endforelse
            </td>
            <td style="width:50%">
                <div class="l" style="margin-bottom:3px">How customers found us</div>
                @forelse ($summary['byHowFound'] as $src => $c)
                    <span class="row" style="margin-right:10px">{{ $src }}: <b>{{ $c }}</b></span>
                @empty
                    <span class="muted">—</span>
                @endforelse
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width:3%">#</th>
                <th style="width:9%">Reference</th>
                <th style="width:10%">Date</th>
                <th style="width:15%">Customer</th>
                <th style="width:10%">Phone</th>
                <th style="width:14%">Location</th>
                <th style="width:13%">How found us</th>
                <th style="width:16%">Product</th>
                <th style="width:10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($enquiries as $e)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $e->enquiry_reference }}</td>
                    <td>{{ $e->created_at?->format('d M Y') }}<br><span class="muted">{{ $e->created_at?->format('h:i A') }}</span></td>
                    <td><b>{{ $e->customer_name }}</b></td>
                    <td>{{ $e->phone }}</td>
                    <td>{{ $e->city ?? '—' }}</td>
                    <td>{{ $e->how_found ?? '—' }}</td>
                    <td>
                        {{ $e->interested_product ?? $e->product?->name ?? '—' }}
                        @if ($e->product_category ?? $e->category?->name)
                            <br><span class="muted">{{ $e->product_category ?? $e->category?->name }}</span>
                        @endif
                    </td>
                    <td><span class="st" style="background: {{ $statusColor[$e->status] ?? '#0E1471' }}">{{ $e->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="9" class="empty">No QR enquiries match these filters.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="foot">{{ $company }} · QR Enquiry Report · {{ now()->format('d-m-Y') }}</div>
</div>

@unless ($forPdf)
    <script>
        window.addEventListener('load', function () { setTimeout(function () { window.print(); }, 400); });
    </script>
@endunless
</body>
</html>
