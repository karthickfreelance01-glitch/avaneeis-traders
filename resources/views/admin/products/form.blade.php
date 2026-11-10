@extends('admin.layouts.app')

@section('content')
<div class="card p-4" style="max-width: 900px;">
    <h3 class="fw-bold mb-4">{{ $product->exists ? 'Edit Product' : 'Add Product' }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($product->exists)
            @method('PUT')
        @endif
        <input type="hidden" name="display_order" value="{{ $product->display_order ?? 0 }}">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" id="categorySelect" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Subcategory</label>
                <select name="subcategory_id" id="subcategorySelect" class="form-select"
                        data-selected="{{ old('subcategory_id', $product->subcategory_id) }}">
                    <option value="">Select Subcategory</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Description</label>
                <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if ($product->image)
                    <img src="{{ \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : Storage::url($product->image) }}" alt="" class="img-thumbnail mt-3" style="max-width:160px;">
                @endif
            </div>
            <div class="col-md-3">
                <label class="form-label">Featured</label>
                <select name="featured" class="form-select">
                    <option value="0" @selected(!old('featured', $product->featured ?? false))>No</option>
                    <option value="1" @selected(old('featured', $product->featured ?? false))>Yes (show on home)</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1" @selected(old('status', $product->status ?? true))>Active</option>
                    <option value="0" @selected(!old('status', $product->status ?? true))>Inactive</option>
                </select>
            </div>
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary px-4">Save Product</button>
            </div>
        </div>
    </form>
</div>

@php
    $subcategoryData = $subcategories->map(fn ($s) => ['id' => $s->id, 'name' => $s->name, 'category_id' => $s->category_id])->values();
@endphp
<script>
    (function () {
        const subcategories = {!! json_encode($subcategoryData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) !!};
        const cat = document.getElementById('categorySelect');
        const sub = document.getElementById('subcategorySelect');
        let selected = sub.dataset.selected;

        function fill() {
            sub.innerHTML = '<option value="">Select Subcategory</option>';
            subcategories.filter(s => String(s.category_id) === cat.value).forEach(s => {
                const o = new Option(s.name, s.id);
                if (String(s.id) === String(selected)) o.selected = true;
                sub.add(o);
            });
            selected = '';
        }
        cat.addEventListener('change', fill);
        fill();
    })();
</script>
@endsection
