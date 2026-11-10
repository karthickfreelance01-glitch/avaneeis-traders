@extends('frontend.layouts.app')

@section('title', 'Request a Quote | AVANEEIS TRADERS')

@section('content')
<section class="page-header">
    <div class="container text-center">
        @if (($source ?? 'website') === 'qr')
            <img src="{{ asset('assets/images/logo-icon.jpg') }}" alt="" class="rounded-3 bg-white p-1 mb-3" style="width:64px;">
            <span class="eyebrow d-block">Thanks for scanning!</span>
            <h1 class="fw-bold mb-2">Quick Enquiry</h1>
            <p class="mb-0 opacity-75">Fill in 5 details — our team will call you back shortly.</p>
        @else
            <span class="eyebrow">Enquiry Form</span>
            <h1 class="fw-bold mb-2">Request a Quotation</h1>
            <p class="mb-0 opacity-75">Tell us what you need — our team will call you back shortly.</p>
        @endif
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card enquiry-card p-4 p-md-5">
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('enquiry.store') }}" novalidate>
                    @csrf
                    <input type="hidden" name="source" value="{{ old('source', $source ?? 'website') }}">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="Your full name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="98xxx xxxxx" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Location <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-location-dot"></i></span>
                                <input type="text" name="location" class="form-control" value="{{ old('location') }}" placeholder="Village / Town, District" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">How did you find us? <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-bullhorn"></i></span>
                                <select name="how_found" class="form-select" required>
                                    <option value="">Select an option</option>
                                    @foreach (\App\Models\Enquiry::HOW_FOUND_OPTIONS as $option)
                                        <option value="{{ $option }}" @selected(old('how_found') === $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-box"></i></span>
                                <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $product?->name) }}" placeholder="Product you are interested in" maxlength="255" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="fa-solid fa-paper-plane me-2"></i>Submit Enquiry
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
