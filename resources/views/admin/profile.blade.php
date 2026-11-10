@extends('admin.layouts.app')

@section('content')
@php $ini = collect(explode(' ', $admin->name ?? 'Admin'))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode(''); @endphp
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="panel p-0 overflow-hidden">
            <div class="welcome rounded-0" style="padding: 40px 28px 70px;"></div>
            <div class="px-4 pb-4" style="margin-top:-46px; position: relative;">
                <div class="d-flex align-items-end gap-3 mb-4">
                    <div class="av-sm" style="width:92px;height:92px;border-radius:24px;font-size:2rem;background:var(--red);border:5px solid #fff;">{{ $ini ?: 'A' }}</div>
                    <div class="pb-2">
                        <h4 class="fw-bold mb-0">{{ $admin->name }}</h4>
                        <span class="pill-soft pill-navy">Administrator</span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" value="{{ $admin->name }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" value="{{ $admin->email }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Member since</label>
                        <input type="text" class="form-control" value="{{ $admin->created_at?->format('d M Y') }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
