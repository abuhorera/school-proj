@extends('layouts.app')

@section('title', 'Library Management')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-book text-primary mr-2"></i> Library Management</h3>
            <p class="text-muted mb-0 small">Catalog management, barcode circulation desk, book issues &amp; returns, and overdue fines tracking.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addBookModal">
                <i class="fas fa-plus-circle mr-1"></i> Add New Book
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.library', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Library Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'issue-return' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.library', ['tab' => 'issue-return']) }}">
                <i class="fas fa-exchange-alt mr-1"></i> Issue / Return Desk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'books' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.library', ['tab' => 'books']) }}">
                <i class="fas fa-book-open mr-1"></i> Manage Books Catalog
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'categories' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.library', ['tab' => 'categories']) }}">
                <i class="fas fa-tags mr-1"></i> Book Categories
            </a>
        </li>
    </ul>

    {{-- Stats Row --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-book fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ $books->sum('quantity') }}</div>
                        <small class="text-muted">Total Physical Copies</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-check-circle fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ $books->sum('available_quantity') }}</div>
                        <small class="text-muted">Available on Shelf</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-hand-holding fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($issues->where('status', 'Issued')) }}</div>
                        <small class="text-muted">Currently Issued</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-danger"><i class="fas fa-exclamation-triangle fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">0</div>
                        <small class="text-muted">Overdue Penalties</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'issue-return')
        {{-- Issue Return View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-exchange-alt text-primary mr-2"></i> Book Circulation Ledger</h5>
                <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#issueBookModal"><i class="fas fa-plus mr-1"></i> Issue Book</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Book Title</th>
                                <th>Borrower (Member)</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issues as $iss)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $iss->book_title }}</td>
                                    <td>
                                        <div class="font-weight-bold">{{ $iss->member_name }}</div>
                                        <small class="badge badge-light border">{{ $iss->member_type }}</small>
                                    </td>
                                    <td>{{ $iss->issue_date }}</td>
                                    <td>{{ $iss->due_date }}</td>
                                    <td>
                                        @if($iss->status == 'Returned')
                                            <span class="badge badge-success px-2 py-1">Returned</span>
                                        @else
                                            <span class="badge badge-warning px-2 py-1">Issued</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($iss->status == 'Issued')
                                            <button class="btn btn-outline-success btn-sm px-2" onclick="alert('Book returned successfully!')">Return Book</button>
                                        @else
                                            <span class="text-muted small">&mdash;</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No circulation records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($tab === 'categories')
        {{-- Categories View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-tags text-primary mr-2"></i> Book Classification Categories</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Category Name</th>
                                <th>Classification Code</th>
                                <th>Description</th>
                                <th class="text-right">Total Books</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $c)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $c->name }}</td>
                                    <td><span class="badge badge-light border font-monospace">{{ $c->code }}</span></td>
                                    <td class="text-muted small">{{ $c->description }}</td>
                                    <td class="text-right font-weight-bold text-primary">{{ $books->where('category_id', $c->id)->count() }} titles</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Books Catalog View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-book-open text-primary mr-2"></i> Master Book Catalog</h5>
                <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($books) }} Titles</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Book Title</th>
                                <th>Author &amp; Publisher</th>
                                <th>ISBN / Code</th>
                                <th>Category</th>
                                <th>Rack Location</th>
                                <th>Copies</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $b)
                                <tr>
                                    <td class="font-weight-bold text-dark">
                                        <i class="fas fa-book text-muted mr-1"></i> {{ $b->title }}
                                    </td>
                                    <td>
                                        <div>{{ $b->author }}</div>
                                        <small class="text-muted">{{ $b->publisher }}</small>
                                    </td>
                                    <td><span class="badge badge-light border font-monospace">{{ $b->isbn_no ?? $b->book_no }}</span></td>
                                    <td><span class="badge badge-info px-2 py-1">{{ $b->category_name ?? 'General' }}</span></td>
                                    <td><span class="badge badge-secondary">{{ $b->rack_no ?? 'A-1' }}</span></td>
                                    <td>
                                        <span class="font-weight-bold text-success">{{ $b->available_quantity }}</span> / {{ $b->quantity }}
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-barcode mr-1"></i> Barcode</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No books in catalog.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Add Book Modal --}}
<div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle text-primary mr-1"></i> Add Book to Catalog</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Book Title</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Modern Indian History">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Author</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Author name">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">ISBN / Book No</label>
                        <input type="text" class="form-control form-control-sm" placeholder="978-...">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Total Copies</label>
                        <input type="number" class="form-control form-control-sm" value="5">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Rack Shelf</label>
                        <input type="text" class="form-control form-control-sm" placeholder="e.g. Rack B-3">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Book</button>
            </div>
        </div>
    </div>
</div>
@endsection
