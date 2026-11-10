@extends('admin.layouts.app')

@section('content')
@php $img = fn ($p) => empty($p) ? null : (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://']) ? $p : Storage::url($p)); @endphp

<div class="page-head">
    <div>
        <h3>Categories <span class="chip ms-1">{{ $categories->count() }}</span></h3>
        <p>Main groups shown on the website menu and home page</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="search" class="form-control" placeholder="Search categories…" data-table-search="#catTable"></div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add Category</a>
    </div>
</div>

<div class="panel p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle" id="catTable">
            <thead><tr><th>Category</th><th>Subcategories</th><th>Products</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($img($category->image))
                                    <img src="{{ $img($category->image) }}" class="thumb" alt="">
                                @else
                                    <div class="thumb-ph"><i class="fa-solid fa-tags"></i></div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $category->name }}</div>
                                    <small class="text-muted">/{{ $category->slug }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="chip">{{ $category->subcategories_count ?? 0 }}</span></td>
                        <td><span class="chip">{{ $category->products_count ?? 0 }}</span></td>
                        <td><span class="pill-soft {{ $category->status ? 'pill-green' : 'pill-grey' }}">{{ $category->status ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('categories.show', $category->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary btn-ic" title="View on website"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary btn-ic" title="Edit"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline" onsubmit="return confirm('Delete this category? Its products will also be removed.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-ic" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fa-solid fa-tags fs-2 d-block mb-2 opacity-50"></i>No categories yet. <a href="{{ route('admin.categories.create') }}">Add one</a></td></tr>
                @endforelse
            </tbody>
        </table>
        <div id="noResults" class="d-none text-center text-muted py-4">No categories match your search.</div>
    </div>
</div>
@endsection
