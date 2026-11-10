@extends('admin.layouts.app')

@section('content')
<div class="card p-4">
    <h3 class="fw-bold mb-4">{{ $item->exists ? 'Edit Gallery Item' : 'Add Gallery Item' }}</h3>
    <form method="POST" action="{{ $item->exists ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Display Order</label>
                <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $item->display_order) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" @selected(old('status', $item->status ?? true))>Active</option>
                    <option value="0" @selected(!old('status', $item->status ?? true))>Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $item->description) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if ($item->image)
                    <img src="{{ \Illuminate\Support\Str::startsWith($item->image, ['http://', 'https://']) ? $item->image : Storage::url($item->image) }}" alt="" class="img-fluid mt-3" style="max-width:220px;">
                @endif
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
