@extends('admin.layouts.app')

@section('content')
<div class="card p-4" style="max-width: 720px;">
    <h3 class="fw-bold mb-4">{{ $category->exists ? 'Edit Category' : 'Add Category' }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($category->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="display_order" value="{{ $category->display_order ?? 0 }}">

        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" @selected(old('status', $category->status ?? true))>Active</option>
                    <option value="0" @selected(!old('status', $category->status ?? true))>Inactive</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Category Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if ($category->image)
                    <img src="{{ \Illuminate\Support\Str::startsWith($category->image, ['http://', 'https://']) ? $category->image : Storage::url($category->image) }}" alt="" class="img-thumbnail mt-3" style="max-width:160px;">
                @endif
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
