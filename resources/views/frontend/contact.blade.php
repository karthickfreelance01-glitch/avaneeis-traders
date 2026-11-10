@php
    $imageUrl = function ($path) {
        $placeholder = 'https://placehold.co/600x400/071D50/FFFFFF?text=Avaneeis';
        if (empty($path)) return $placeholder;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    };
@endphp

@extends('frontend.layouts.app')

@section('title', 'Contact Us | AVANEEIS TRADERS')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="text-primary fw-semibold">Contact Us</span>
        <h1 class="section-title">We are ready to help</h1>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card h-100 p-4">
                <h4 class="fw-bold mb-3">Get in touch</h4>
                <p><i class="fa-solid fa-phone me-2 text-primary"></i><a href="tel:{{ preg_replace('/[^0-9]/', '', $settings['contact_phone_1'] ?? '9842324074') }}" class="text-decoration-none text-dark">{{ $settings['contact_phone_1'] ?? '98423 24074' }}</a></p>
                <p><i class="fa-solid fa-phone me-2 text-primary"></i><a href="tel:{{ preg_replace('/[^0-9]/', '', $settings['contact_phone_2'] ?? '9840637950') }}" class="text-decoration-none text-dark">{{ $settings['contact_phone_2'] ?? '98406 37950' }}</a></p>
                <p><i class="fa-brands fa-whatsapp me-2 text-primary"></i><a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number'] ?? '9499953094') }}" target="_blank" class="text-decoration-none text-dark">{{ $settings['whatsapp_number'] ?? '94999 53094' }}</a></p>
                <p><i class="fa-solid fa-envelope me-2 text-primary"></i><a href="mailto:{{ $settings['email'] ?? 'avaneeistraders@gmail.com' }}" class="text-decoration-none text-dark">{{ $settings['email'] ?? 'avaneeistraders@gmail.com' }}</a></p>
                <p><i class="fa-solid fa-location-dot me-2 text-primary"></i>{{ $settings['head_office'] ?? 'Head office details' }}</p>
                <p><i class="fa-solid fa-building me-2 text-primary"></i>{{ $settings['branch_office'] ?? 'Branch office details' }}</p>
                <p><i class="fa-solid fa-warehouse me-2 text-primary"></i>{{ $settings['warehouse_address'] ?? 'Warehouse details' }}</p>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card p-4">
                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="5" class="form-control" required></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
