@extends('admin.layouts.app')

@section('content')
@php $img = fn ($p) => empty($p) ? null : (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://']) ? $p : Storage::url($p)); @endphp

<div class="page-head">
    <div>
        <h3>Gallery <span class="chip ms-1">{{ $gallery->count() }}</span></h3>
        <p>Photos of your installations and products shown on the website</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="search" class="form-control" placeholder="Search photos…" data-table-search="#galleryGrid"></div>
        <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add Photo</a>
    </div>
</div>

<div class="row g-4" id="galleryGrid">
    @forelse ($gallery as $item)
        <div class="col-6 col-md-4 col-xl-3" data-search-item>
            <div class="g-tile">
                <img src="{{ $img($item->image) ?? 'https://placehold.co/600x400/0E1471/FFFFFF?text=Photo' }}" alt="{{ $item->title }}" loading="lazy">
                <div class="acts">
                    <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-sm btn-ic" title="Edit"><i class="fa-solid fa-pen"></i></a>
                    <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Delete this photo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-ic text-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </div>
                <div class="p-3 d-flex justify-content-between align-items-center gap-2">
                    <div class="fw-semibold small text-truncate">{{ $item->title }}</div>
                    <span class="pill-soft {{ $item->status ? 'pill-green' : 'pill-grey' }}">{{ $item->status ? 'Live' : 'Hidden' }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="panel text-center text-muted py-5"><i class="fa-solid fa-images fs-2 d-block mb-2 opacity-50"></i>No photos yet. <a href="{{ route('admin.gallery.create') }}">Upload one</a></div></div>
    @endforelse
</div>
<div id="noResults" class="d-none text-center text-muted py-4">No photos match your search.</div>
@endsection
