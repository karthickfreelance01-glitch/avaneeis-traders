@extends('admin.layouts.app')

@section('content')
@php
    $statuses = ['New', 'Contacted', 'Follow-up', 'Quotation Sent', 'Converted', 'Closed'];
    $statusPill = ['New' => 'pill-red', 'Contacted' => 'pill-blue', 'Follow-up' => 'pill-amber', 'Quotation Sent' => 'pill-violet', 'Converted' => 'pill-green', 'Closed' => 'pill-grey'];
    $counts = $enquiries->countBy('status');
@endphp

<div class="page-head">
    <div>
        <h3>Enquiries <span class="chip ms-1">{{ $enquiries->count() }}</span></h3>
        <p>{{ $counts['New'] ?? 0 }} new · {{ $enquiries->where('created_at', '>=', today())->count() }} today</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="search" class="form-control" placeholder="Search name, phone, product…" data-table-search="#enqTable"></div>
        <a href="{{ route('admin.enquiries.qr') }}" class="btn btn-outline-primary"><i class="fa-solid fa-qrcode me-2"></i>QR Enquiries</a>
    </div>
</div>

<div class="d-flex gap-2 flex-wrap mb-3" id="statusTabs">
    <button type="button" class="btn btn-sm btn-primary" data-status="">All <span class="ms-1 opacity-75">{{ $enquiries->count() }}</span></button>
    @foreach ($statuses as $st)
        <button type="button" class="btn btn-sm btn-outline-primary" data-status="{{ $st }}">{{ $st }} <span class="ms-1 opacity-75">{{ $counts[$st] ?? 0 }}</span></button>
    @endforeach
</div>

<div class="panel p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle" id="enqTable">
            <thead><tr><th>Customer</th><th>Interested in</th><th>Location</th><th>Source</th><th>Received</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse ($enquiries as $enquiry)
                    @php
                        $ini = collect(explode(' ', trim($enquiry->customer_name)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
                        $hue = crc32($enquiry->customer_name) % 360;
                        $digits = preg_replace('/[^0-9]/', '', $enquiry->phone);
                    @endphp
                    <tr data-status="{{ $enquiry->status }}" class="{{ $enquiry->status === 'New' ? 'fw-medium' : '' }}">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="av-sm" style="background:hsl({{ $hue }},55%,45%)">{{ $ini ?: '?' }}</div>
                                <div>
                                    <div class="fw-semibold">{{ $enquiry->customer_name }}</div>
                                    <small class="text-muted">{{ $enquiry->phone }} · {{ $enquiry->enquiry_reference }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>{{ $enquiry->interested_product ?? $enquiry->product->name ?? '—' }}</div>
                            @if ($enquiry->product_category)<span class="chip">{{ $enquiry->product_category }}</span>@endif
                        </td>
                        <td><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $enquiry->city ?? '—' }}</td>
                        <td>
                            @if (($enquiry->source ?? 'website') === 'qr')
                                <span class="pill-soft pill-red"><i class="fa-solid fa-qrcode"></i> QR</span>
                            @else
                                <span class="pill-soft pill-navy">Website</span>
                            @endif
                        </td>
                        <td class="text-nowrap"><div>{{ $enquiry->created_at?->diffForHumans() }}</div><small class="text-muted">{{ $enquiry->created_at?->format('d M, h:i A') }}</small></td>
                        <td><span class="pill-soft {{ $statusPill[$enquiry->status] ?? 'pill-navy' }}">{{ $enquiry->status }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="tel:{{ $digits }}" class="btn btn-sm btn-outline-primary btn-ic" title="Call"><i class="fa-solid fa-phone"></i></a>
                            <a href="https://wa.me/{{ strlen($digits) === 10 ? '91'.$digits : $digits }}" target="_blank" class="btn btn-sm btn-outline-success btn-ic" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-sm btn-outline-primary btn-ic" title="View"><i class="fa-solid fa-eye"></i></a>
                            <form method="POST" action="{{ route('admin.enquiries.destroy', $enquiry) }}" class="d-inline" onsubmit="return confirm('Delete this enquiry?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-ic" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fa-regular fa-envelope fs-2 d-block mb-2 opacity-50"></i>No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div id="noResults" class="d-none text-center text-muted py-4">No enquiries match.</div>
    </div>
</div>

<script>
document.querySelectorAll('#statusTabs [data-status]').forEach(btn => btn.addEventListener('click', () => {
    document.querySelectorAll('#statusTabs [data-status]').forEach(b => { b.classList.remove('btn-primary'); b.classList.add('btn-outline-primary'); });
    btn.classList.add('btn-primary'); btn.classList.remove('btn-outline-primary');
    const st = btn.dataset.status; let shown = 0;
    document.querySelectorAll('#enqTable tbody tr[data-status]').forEach(r => {
        const hit = !st || r.dataset.status === st; r.style.display = hit ? '' : 'none'; if (hit) shown++;
    });
    document.getElementById('noResults').classList.toggle('d-none', shown > 0);
}));
</script>
@endsection
