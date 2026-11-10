@extends('admin.layouts.app')

@section('content')
<div class="card p-4" style="max-width: 720px;">
    <h3 class="fw-bold mb-4">{{ $subcategory->exists ? 'Edit Subcategory' : 'Add Subcategory' }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $subcategory->exists ? route('admin.subcategories.update', $subcategory) : route('admin.subcategories.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($subcategory->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="display_order" value="{{ $subcategory->display_order ?? 0 }}">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $subcategory->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Subcategory Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $subcategory->name) }}" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if ($subcategory->image)
                    <img src="{{ \Illuminate\Support\Str::startsWith($subcategory->image, ['http://', 'https://']) ? $subcategory->image : Storage::url($subcategory->image) }}" alt="" class="img-thumbnail mt-3" style="max-width:160px;">
                @endif
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" @selected(old('status', $subcategory->status ?? true))>Active</option>
                    <option value="0" @selected(!old('status', $subcategory->status ?? true))>Inactive</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.subcategories.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
