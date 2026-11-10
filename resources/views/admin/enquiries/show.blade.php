@extends('admin.layouts.app')

@section('content')
<div class="card p-4">
    <h3 class="fw-bold mb-4">Enquiry Details</h3>
    <div class="row g-4">
        <div class="col-lg-6">
            <table class="table table-borderless">
                <tr><th>Reference</th><td>{{ $enquiry->enquiry_reference }}</td></tr>
                <tr><th>Name</th><td>{{ $enquiry->customer_name }}</td></tr>
                <tr><th>Company</th><td>{{ $enquiry->company_name ?? 'N/A' }}</td></tr>
                <tr><th>Phone</th><td>{{ $enquiry->phone }}</td></tr>
                <tr><th>Email</th><td>{{ $enquiry->email ?? 'N/A' }}</td></tr>
                <tr><th>Location</th><td>{{ $enquiry->city ?? 'N/A' }}</td></tr>
            </table>
        </div>
        <div class="col-lg-6">
            <table class="table table-borderless">
                <tr><th>Product</th><td>{{ $enquiry->interested_product ?? $enquiry->product->name ?? 'N/A' }}</td></tr>
                <tr><th>Category</th><td>{{ $enquiry->product_category ?? $enquiry->category->name ?? 'N/A' }}</td></tr>
                <tr><th>Quantity</th><td>{{ $enquiry->quantity ?? 'N/A' }}</td></tr>
                <tr><th>Source</th><td>@if(($enquiry->source ?? 'website') === 'qr')<span class="badge badge-source-qr"><i class="fa-solid fa-qrcode me-1"></i>QR Scan</span>@else<span class="badge badge-source-website">Website</span>@endif</td></tr>
                <tr><th>Date</th><td>{{ $enquiry->created_at?->format('d M Y, h:i A') }}</td></tr>
                <tr><th>Status</th><td><span class="badge bg-primary">{{ $enquiry->status }}</span></td></tr>
                <tr><th>Message</th><td>{{ $enquiry->message }}</td></tr>
            </table>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.enquiries.updateStatus', $enquiry) }}" class="mt-4">
        @csrf
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Update Status</label>
                <select name="status" class="form-select">
                    @foreach(['New', 'Contacted', 'Follow-up', 'Quotation Sent', 'Converted', 'Closed'] as $status)
                        <option value="{{ $status }}" @selected($enquiry->status == $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Internal Note</label>
                <input type="text" name="note" class="form-control">
            </div>
            <div class="col-md-2 text-end">
                <button type="submit" class="btn btn-primary w-100">Update</button>
            </div>
        </div>
    </form>

    <div class="mt-4">
        <h5 class="fw-bold">Notes</h5>
        @if ($enquiry->notesList->count())
            @foreach ($enquiry->notesList as $note)
                <div class="border rounded p-2 mb-2">{{ $note->note }}</div>
            @endforeach
        @else
            <p class="text-muted">No notes yet.</p>
        @endif
    </div>
</div>
@endsection
