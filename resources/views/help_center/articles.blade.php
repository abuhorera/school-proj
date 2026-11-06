@extends('layouts.app')

@section('content')
<style>
    .category-strip {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-top: 12px;
        padding-bottom: 4px;
        white-space: nowrap;
    }
    .tpl-pill {
        display: inline-flex;
        align-items: center;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #475569;
        text-decoration: none !important;
        transition: all 0.2s;
        cursor: pointer;
    }
    .tpl-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .tpl-pill.active {
        background: #0284c7;
        border-color: #0284c7;
        color: #ffffff !important;
    }
    .tpl-pill-count {
        background: rgba(0, 0, 0, 0.08);
        border-radius: 10px;
        padding: 1px 6px;
        font-size: 0.72rem;
        margin-left: 6px;
        font-weight: 600;
    }
    .tpl-pill.active .tpl-pill-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }
</style>

<div class="container-fluid px-4 py-3">
    @include('help_center.nav')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px;">
            <i class="fas fa-check-circle mr-2 fa-lg"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close" style="outline:none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="tpl-card no-hover tpl-form mb-4 p-4">
        <form action="{{ route('school.help-center.articles') }}" method="GET">
            <div class="form-row align-items-end">
                <div class="col-md-5 form-group mb-2">
                    <label for="global-search" class="font-weight-600 text-dark text-sm">Search</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="global-search" name="search" value="{{ request('search') }}" placeholder="Search articles, guides, FAQs, error codes…">
                        <div class="input-group-append">
                            <button class="btn btn-kb-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 form-group mb-2">
                    <label for="filter-category" class="font-weight-600 text-dark text-sm">Category</label>
                    <select class="form-control" id="filter-category" name="category_id" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 form-group mb-2">
                    <label for="filter-status" class="font-weight-600 text-dark text-sm">Status</label>
                    <select class="form-control" id="filter-status" name="status" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
            </div>

            <!-- Horizontal Category Strip with Quick Filter Pills -->
            <div class="category-strip mt-2">
                <a href="{{ route('school.help-center.articles') }}" class="tpl-pill {{ !request('category_id') ? 'active' : '' }}">
                    <i class="fas fa-th-list mr-1"></i> All
                    <span class="tpl-pill-count">{{ $articles->total() }}</span>
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('school.help-center.articles', array_merge(request()->query(), ['category_id' => $cat->id])) }}" class="tpl-pill {{ request('category_id') == $cat->id ? 'active' : '' }}">
                        <i class="{{ $cat->icon ?: 'fas fa-book' }} mr-1"></i>
                        {{ $cat->name }}
                        <span class="tpl-pill-count">{{ $cat->articles_count ?? $cat->articles->count() }}</span>
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    <!-- Articles Table Card -->
    <div class="tpl-card no-hover">
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center">
                <i class="fas fa-book-open mr-2 text-sky-600"></i> Articles & Documentation
            </h5>
            <div class="text-muted text-sm">
                Showing <strong>{{ $articles->firstItem() ?? 0 }} - {{ $articles->lastItem() ?? 0 }}</strong> of <strong>{{ $articles->total() }}</strong> guides
            </div>
        </div>

        <div class="table-responsive">
            <table class="table tpl-table mb-0" id="kb-table">
                <thead>
                    <tr>
                        <th style="min-width: 320px;">Article Title</th>
                        <th>Category</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Views</th>
                        <th class="text-right" width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $art)
                        <tr>
                            <td>
                                <div class="d-flex align-items-start">
                                    @if($art->is_pinned)
                                        <span class="badge badge-warning text-white mr-2 mt-1 px-1.5 py-1" title="Pinned to top" style="border-radius:4px;">
                                            <i class="fas fa-thumbtack"></i>
                                        </span>
                                    @endif
                                    <div>
                                        <a href="{{ route('school.help-center.articles.show', $art->id) }}" class="font-weight-600 text-sky-700 hover:text-sky-900 d-block" style="font-size: 0.95rem;">
                                            {{ $art->title }}
                                        </a>
                                        @if($art->excerpt)
                                            <p class="text-muted text-sm mb-0 mt-0.5" style="max-width: 500px;">
                                                {{ Str::limit($art->excerpt, 110) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($art->category)
                                    <span class="tpl-chip is-blue">
                                        <i class="{{ $art->category->icon ?: 'fas fa-folder' }} mr-1 text-xs"></i>
                                        {{ $art->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted font-italic text-sm">Uncategorized</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($art->status == 'published')
                                    <span class="tpl-chip is-emerald">Published</span>
                                @elseif($art->status == 'draft')
                                    <span class="tpl-chip is-amber">Draft</span>
                                @else
                                    <span class="tpl-chip is-indigo">Archived</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-light border text-muted px-2 py-1" style="border-radius:6px;">
                                    School Internal
                                </span>
                            </td>
                            <td class="text-center font-weight-500 text-slate-600">
                                <i class="far fa-eye mr-1 text-slate-400"></i> {{ $art->views_count }}
                            </td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('school.help-center.articles.show', $art->id) }}" class="tpl-icon-btn mr-1" title="View article">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('school.help-center.articles.edit', $art->id) }}" class="tpl-icon-btn mr-1" title="Edit article">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('school.help-center.articles.destroy', $art->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tpl-icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-5 text-center">
                                <div class="py-4">
                                    <div class="text-slate-300 mb-3">
                                        <i class="fas fa-search fa-3x"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-slate-700">No articles matched your filter</h6>
                                    <p class="text-muted text-sm mb-3">Try adjusting your search keywords or clear the category filter to view all guides.</p>
                                    <a href="{{ route('school.help-center.articles.create') }}" class="btn-kb-primary">
                                        <i class="fas fa-plus mr-1"></i> Create First Article
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

