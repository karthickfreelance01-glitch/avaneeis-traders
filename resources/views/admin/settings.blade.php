@extends('admin.layouts.app')

@section('content')
@php
    $sections = [
        'company' => ['Company', 'fa-building', [
            'company_name' => ['Company Name', 1], 'company_tagline' => ['Tagline', 1], 'gstin' => ['GSTIN', 1],
            'company_description' => ['Company Description', 3],
        ]],
        'contact' => ['Contact', 'fa-phone', [
            'contact_phone_1' => ['Phone 1', 1], 'contact_phone_2' => ['Phone 2', 1], 'whatsapp_number' => ['WhatsApp Number', 1], 'email' => ['Email', 1],
        ]],
        'address' => ['Addresses', 'fa-location-dot', [
            'head_office' => ['Head Office', 3], 'branch_office' => ['Branch Office', 3], 'warehouse_address' => ['Warehouse', 3],
        ]],
        'social' => ['Social Media', 'fa-share-nodes', [
            'facebook_url' => ['Facebook URL', 1], 'instagram_url' => ['Instagram URL', 1], 'linkedin_url' => ['LinkedIn URL', 1],
        ]],
        'about' => ['About & Footer', 'fa-circle-info', [
            'about_us' => ['About Us', 3], 'mission' => ['Mission', 3], 'vision' => ['Vision', 3], 'footer_content' => ['Footer Text', 2],
        ]],
    ];
@endphp

<div class="page-head">
    <div><h3>Company Settings</h3><p>These details appear across your website</p></div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')
    <div class="row g-4">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="panel set-nav position-sticky" style="top: 90px;">
                @foreach ($sections as $id => [$title, $icon])
                    <a href="#set-{{ $id }}"><i class="fa-solid {{ $icon }}" style="width:18px"></i>{{ $title }}</a>
                @endforeach
                <hr>
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-floppy-disk me-2"></i>Save Settings</button>
            </div>
        </div>
        <div class="col-lg-9">
            @foreach ($sections as $id => [$title, $icon, $fields])
                <div class="panel mb-4" id="set-{{ $id }}" style="scroll-margin-top: 90px;">
                    <div class="panel-head justify-content-start gap-2">
                        <span class="thumb-ph" style="width:38px;height:38px;border-radius:10px"><i class="fa-solid {{ $icon }}"></i></span>
                        <h5 class="panel-title">{{ $title }}</h5>
                    </div>
                    <div class="row g-3">
                        @foreach ($fields as $key => [$label, $rows])
                            <div class="{{ $rows > 1 ? 'col-12' : 'col-md-6' }}">
                                <label class="form-label">{{ $label }}</label>
                                @if ($rows > 1)
                                    <textarea name="{{ $key }}" class="form-control" rows="{{ $rows }}">{{ $settings[$key] ?? '' }}</textarea>
                                @else
                                    <input type="text" name="{{ $key }}" class="form-control" value="{{ $settings[$key] ?? '' }}">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <div class="text-end d-lg-none">
                <button type="submit" class="btn btn-primary btn-lg w-100"><i class="fa-solid fa-floppy-disk me-2"></i>Save Settings</button>
            </div>
        </div>
    </div>
</form>
@endsection
