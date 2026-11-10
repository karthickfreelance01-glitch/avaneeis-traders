@extends('admin.layouts.app')

@section('content')
@php $img = fn ($p) => empty($p) ? null : (\Illuminate\Support\Str::startsWith($p, ['http://', 'https://']) ? $p : Storage::url($p)); @endphp

<div class="page-head">
    <div>
        <h3>Subcategories <span class="chip ms-1">{{ $subcategories->count() }}</span></h3>
        <p>Smaller groups inside each category</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i><input type="search" class="form-control" placeholder="Search subcategories…" data-table-search="#subTable"></div>
        <a href="{{ route('admin.subcategories.create') }}" class="btn btn-primary"><i class="fa-solid fa-plus me-2"></i>Add Subcategory</a>
    </div>
</div>

<div class="panel p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle" id="subTable">
            <thead><tr><th>Subcategory</th><th>Category</th><th>Products</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse ($subcategories as $subcategory)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if ($img($subcategory->image))
                                    <img src="{{ $img($subcategory->image) }}" class="thumb" alt="">
                                @else
                                    <div class="thumb-ph"><i class="fa-solid fa-folder-tree"></i></div>
                                @endif
                                <div class="fw-semibold">{{ $subcategory->name }}</div>
                            </div>
                        </td>
                        <td><span class="chip">{{ $subcategory->category->name ?? '—' }}</span></td>
                        <td>{{ $subcategory->products_count ?? 0 }}</td>
                        <td><span class="pill-soft {{ $subcategory->status ? 'pill-green' : 'pill-grey' }}">{{ $subcategory->status ? 'Active' : 'Inactive' }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.subcategories.edit', $subcategory) }}" class="btn btn-sm btn-outline-primary btn-ic" title="Edit"><i class="fa-solid fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.subcategories.destroy', $subcategory) }}" class="d-inline" onsubmit="return confirm('Delete this subcategory?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-ic" title="Delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fa-solid fa-folder-tree fs-2 d-block mb-2 opacity-50"></i>No subcategories yet. <a href="{{ route('admin.subcategories.create') }}">Add one</a></td></tr>
                @endforelse
            </tbody>
        </table>
        <div id="noResults" class="d-none text-center text-muted py-4">No subcategories match your search.</div>
    </div>
</div>
@endsection
