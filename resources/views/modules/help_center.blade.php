@extends('layouts.app')

@section('title', 'Help Center')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-question-circle text-primary mr-2"></i> Help Center &amp; Knowledge Base</h3>
            <p class="text-muted mb-0 small">Student, parent and staff self-service support portal, FAQ articles, and integrated AI assistant.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addArticleModal">
                <i class="fas fa-plus-circle mr-1"></i> New KB Article
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'categories' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.help-center', ['tab' => 'categories']) }}">
                <i class="fas fa-folder mr-1"></i> Manage Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'articles' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.help-center', ['tab' => 'articles']) }}">
                <i class="fas fa-file-alt mr-1"></i> Browse Articles
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'chatbot' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.help-center', ['tab' => 'chatbot']) }}">
                <i class="fas fa-robot mr-1"></i> AI Support Chatbot
            </a>
        </li>
    </ul>

    @if($tab === 'chatbot')
        {{-- AI Chatbot View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-robot text-primary mr-2"></i> Multi-School AI Campus Assistant</h5>
                <span class="badge badge-success px-3 py-1"><i class="fas fa-circle text-xs mr-1 animate-pulse"></i> Gemini AI Live</span>
            </div>
            <div class="card-body">
                <div class="p-4 border rounded bg-light mb-3" style="min-height: 250px;">
                    <div class="d-flex align-items-start mb-3">
                        <div class="rounded-circle p-2 bg-primary text-white mr-2"><i class="fas fa-robot"></i></div>
                        <div class="bg-white p-3 rounded shadow-sm border" style="max-width: 75%;">
                            <p class="mb-0 small">Hello! I am your AI Campus Guide. You can ask me questions about fee due dates, examination schedules, bus route timings, or syllabus guidelines.</p>
                        </div>
                    </div>
                </div>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Ask AI assistant anything about school operations...">
                    <div class="input-group-append">
                        <button class="btn btn-primary font-weight-bold" type="button"><i class="fas fa-paper-plane mr-1"></i> Send</button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($tab === 'articles')
        {{-- Articles View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-file-alt text-primary mr-2"></i> Published Knowledge Base Articles</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Article Title</th>
                                <th>Category</th>
                                <th>Content Excerpt</th>
                                <th>Views</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $art)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-book-reader text-muted mr-1"></i> {{ $art->title }}</td>
                                    <td><span class="badge badge-light border">{{ $art->category_name }}</span></td>
                                    <td class="text-muted small">{{ Str::limit($art->content, 60) }}</td>
                                    <td><span class="badge badge-secondary">{{ $art->views_count }} views</span></td>
                                    <td><span class="badge badge-success px-2 py-1">Published</span></td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Edit</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No articles found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Categories View --}}
        <div class="row">
            @forelse($categories as $cat)
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="card shadow-sm border-0 h-100 bg-white">
                        <div class="card-body p-4 text-center">
                            <div class="rounded-circle p-3 mx-auto mb-3 bg-light text-primary" style="width: 60px; height: 60px;">
                                <i class="fas {{ $cat->icon }} fa-lg"></i>
                            </div>
                            <h6 class="font-weight-bold text-dark mb-1">{{ $cat->name }}</h6>
                            <small class="text-muted d-block mb-3">{{ $articles->where('category_id', $cat->id)->count() }} help articles</small>
                            <a href="{{ route('modules.help-center', ['tab' => 'articles']) }}" class="btn btn-outline-primary btn-sm px-3">Browse Category</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4 text-muted">No categories configured.</div>
            @endforelse
        </div>
    @endif

</div>

{{-- Add Article Modal --}}
<div class="modal fade" id="addArticleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-file-alt text-primary mr-1"></i> New Help Article</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Article Title</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. How to apply for student leave">
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Category</label>
                    <select class="form-control form-control-sm">
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Article Content (Markdown)</label>
                    <textarea class="form-control form-control-sm" rows="4" placeholder="Detailed help instructions..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Publish Article</button>
            </div>
        </div>
    </div>
</div>
@endsection

