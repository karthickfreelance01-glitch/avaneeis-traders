@extends('admin.layouts.app')

@section('content')
@php $img = fn ($p) => empty($p) ? null : (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://']) ? $p : Storage::url($p)); @endphp

<div class="page-head">
    <div>
        <h3>Banners <span class="chip ms-1">{{ $banners->count() }}</span></h3>
        <p>Highlight cards shown on the home page</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add Banner</a>
</div>

<div class="row g-4">
    @forelse ($banners as $banner)
        <div class="col-md-6 col-xl-4">
            <div class="g-tile h-100">
                @if ($img($banner->image))
                    <img src="{{ $img($banner->image) }}" alt="">
                @else
                    <div class="d-grid place-items-center text-muted" style="height:200px;background:#EEF0FB;display:grid;place-items:center"><i class="fa-solid fa-panorama fs-1 opacity-50"></i></div>
                @endif
                <div class="acts">
                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-ic" title="Edit"><i class="fa-solid fa-pen"></i></a>
                    <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Delete this banner?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-ic text-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                        <h6 class="fw-bold mb-0">{{ $banner->title }}</h6>
                        <span class="pill-soft {{ $banner->status ? 'pill-green' : 'pill-grey' }}">{{ $banner->status ? 'Active' : 'Hidden' }}</span>
                    </div>
                    <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($banner->description, 90) }}</p>
                    @if ($banner->button_text)<span class="chip"><i class="fa-solid fa-link me-1"></i>{{ $banner->button_text }}</span>@endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="panel text-center text-muted py-5"><i class="fa-solid fa-panorama fs-2 d-block mb-2 opacity-50"></i>No banners yet. <a href="{{ route('admin.banners.create') }}">Add one</a></div></div>
    @endforelse
</div>
@endsection
