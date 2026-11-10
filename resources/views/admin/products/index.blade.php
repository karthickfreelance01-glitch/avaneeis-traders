@extends('admin.layouts.app')

@section('content')
@php $img = fn ($p) => empty($p) ? null : (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://']) ? $p : Storage::url($p)); @endphp

<div class="page-head">
    <div>
        <h3>All Products <span class="chip ms-1">{{ $products->count() }}</span></h3>
        <p>{{ $products->where('status', true)->count() }} active · {{ $products->where('featured', true)->count() }} featured on the home page</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="search" class="form-control" placeholder="Search products…" data-table-search="#productsTable"></div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add Product</a>
    </div>
</div>

<div class="panel p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle" id="productsTable">
            <thead>
                <tr><th>Product</th><th>Category</th><th>Featured</th><th>Status</th><th class="text-end">Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($img($product->image))
                                    <img src="{{ $img($product->image) }}" class="thumb" alt="">
                                @else
                                    <div class="thumb-ph"><i class="fa-solid fa-box"></i></div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <small class="text-muted">Added {{ $product->created_at?->format('d M Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="chip">{{ $product->category->name ?? '—' }}</span>
                            @if ($product->subcategory)<div class="small text-muted mt-1">{{ $product->subcategory->name }}</div>@endif
                        </td>
                        <td>
                            @if ($product->featured)
                                <span class="pill-soft pill-amber"><i class="fa-solid fa-star"></i> Featured</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td><span class="pill-soft {{ $product->status ? 'pill-green' : 'pill-grey' }}">{{ $product->status ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-ic" title="View on website"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary btn-ic" title="Edit"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-ic" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fa-solid fa-box-open fs-2 d-block mb-2 opacity-50"></i>No products yet. <a href="{{ route('admin.products.create') }}">Add your first product</a></td></tr>
                @endforelse
            </tbody>
        </table>
        <div id="noResults" class="d-none text-center text-muted py-4">No products match your search.</div>
    </div>
</div>
@endsection
