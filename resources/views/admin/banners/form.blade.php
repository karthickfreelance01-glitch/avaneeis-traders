@extends('admin.layouts.app')

@section('content')
<div class="card p-4">
    <h3 class="fw-bold mb-4">{{ $banner->exists ? 'Edit Banner' : 'Add Banner' }}</h3>
    <form method="POST" action="{{ $banner->exists ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($banner->exists)
            @method('PUT')
        @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Button Text</label>
                <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $banner->button_text) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $banner->description) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Button URL</label>
                <input type="text" name="button_url" class="form-control" value="{{ old('button_url', $banner->button_url) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Display Order</label>
                <input type="number" name="display_order" class="form-control" value="{{ old('display_order', $banner->display_order) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" @selected(old('status', $banner->status ?? true))>Active</option>
                    <option value="0" @selected(!old('status', $banner->status ?? true))>Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if ($banner->image)
                    <img src="{{ \Illuminate\Support\Str::startsWith($banner->image, ['http://', 'https://']) ? $banner->image : Storage::url($banner->image) }}" alt="" class="img-fluid mt-3" style="max-width:200px;">
                @endif
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
