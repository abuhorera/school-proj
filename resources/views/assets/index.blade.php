@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="ast-card">
        <div class="ast-card-header flex-wrap" style="gap: 10px;">
            <h4 class="ast-card-title">
                <i class="fas fa-cubes text-primary"></i> All Assets
            </h4>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('school.assets.assets.tags.print', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-qrcode mr-1"></i> Print Tags
                </a>
                <a href="{{ route('school.assets.assets.create') }}" class="btn btn-sm btn-ast-primary">
                    <i class="fas fa-plus mr-1"></i> Register Asset
                </a>
            </div>
        </div>

        <div class="ast-card-body">
            {{-- Filter Form --}}
            <form method="GET" action="{{ route('school.assets.assets') }}" class="mb-4">
                <div class="row" style="row-gap: 10px;">
                    <div class="col-md-3 col-sm-6">
                        <input type="text" name="q" class="form-control form-control-sm" placeholder="Search name, tag, serial..." value="{{ request('q') }}">
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="category" class="form-control form-control-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Statuses</option>
                            <option value="in_store" {{ request('status') == 'in_store' ? 'selected' : '' }}>In Store</option>
                            <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                            <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                            <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                            <option value="disposed" {{ request('status') == 'disposed' ? 'selected' : '' }}>Disposed</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <select name="condition" class="form-control form-control-sm">
                            <option value="">All Conditions</option>
                            <option value="New" {{ request('condition') == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Good" {{ request('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ request('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                            <option value="Poor" {{ request('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                            <option value="Damaged" {{ request('condition') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-12 d-flex" style="gap: 6px;">
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1" style="background: #0284c7; border-color: #0284c7;">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('school.assets.assets') }}" class="btn btn-sm btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="ast-table">
                    <thead>
                        <tr>
                            <th>Tag</th>
                            <th>Asset</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Condition</th>
                            <th>Location</th>
                            <th class="text-right">Book Value</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assets as $asset)
                            <tr>
                                <td>
                                    <a href="{{ route('school.assets.assets.show', $asset->id) }}" class="ast-code" style="text-decoration: none;">
                                        {{ $asset->asset_tag }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('school.assets.assets.show', $asset->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                        {{ $asset->name }}
                                    </a>
                                    @if($asset->brand || $asset->model_number)
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ $asset->brand }} {{ $asset->model_number }}
                                            @if($asset->serial_number) · SN: {{ $asset->serial_number }} @endif
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $asset->category->name ?? '—' }}</td>
                                <td>
                                    <span class="ast-badge ast-badge-{{ $asset->status }}">
                                        {{ $asset->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $asset->condition }}</span>
                                </td>
                                <td>{{ $asset->location ?? '—' }}</td>
                                <td class="text-right font-weight-bold">
                                    ₹{{ number_format($asset->current_book_value, 2) }}
                                </td>
                                <td class="text-right text-nowrap">
                                    <a href="{{ route('school.assets.assets.show', $asset->id) }}" class="btn btn-xs btn-outline-info" title="View Dossier">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('school.assets.assets.tag', $asset->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="Tag Sticker">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <a href="{{ route('school.assets.assets.edit', $asset->id) }}" class="btn btn-xs btn-outline-primary" title="Edit Asset">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('school.assets.assets.destroy', $asset->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this asset? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-outline-danger" title="Delete Asset">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                                    No assets found matching the filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($assets->hasPages())
                <div class="mt-3 d-flex justify-content-end">
                    {{ $assets->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

