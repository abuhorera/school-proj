@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Surveys & Feedback</h1>
                <p class="text-muted small mb-0">Create surveys, polls and feedback forms — then publish and track responses.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('survey.templates') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-layer-group mr-1"></i> Templates
                </a>
                <a href="{{ route('survey.create', ['kind' => 'poll']) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-poll text-amber-500 mr-1"></i> Quick poll
                </a>
                <a href="{{ route('survey.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-plus mr-1"></i> New survey
                </a>
            </div>
        </div>
    </div>

    <section class="content px-3">
        @include('surveys.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form action="{{ route('survey.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-4 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Search</label>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-left-0" placeholder="Title…">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Kind</label>
                        <select name="kind" class="form-control form-control-sm">
                            <option value="All" {{ request('kind') == 'All' ? 'selected' : '' }}>All</option>
                            <option value="survey" {{ request('kind') == 'survey' ? 'selected' : '' }}>Survey</option>
                            <option value="poll" {{ request('kind') == 'poll' ? 'selected' : '' }}>Poll</option>
                            <option value="feedback_form" {{ request('kind') == 'feedback_form' ? 'selected' : '' }}>Feedback form</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6 d-flex align-items-end gap-1 mt-auto">
                        <button type="submit" class="btn btn-secondary btn-sm flex-grow-1" style="margin-top: 24px;">Filter</button>
                        <a href="{{ route('survey.index') }}" class="btn btn-light btn-sm border" style="margin-top: 24px;" title="Reset"><i class="fas fa-undo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Surveys Ledger Table Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">Surveys</h5>
                    <span class="text-muted small ml-2">{{ $surveys->total() }} surveys registered</span>
                </div>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary btn-xs active">Showing this session</button>
                    <button class="btn btn-outline-secondary btn-xs">View all sessions</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3" style="width: 40px;">#</th>
                            <th class="py-2.5">Title</th>
                            <th class="py-2.5">Kind</th>
                            <th class="py-2.5 text-center">Questions</th>
                            <th class="py-2.5 text-center">Sent</th>
                            <th class="py-2.5 text-center">Responses</th>
                            <th class="py-2.5">Status</th>
                            <th class="py-2.5 text-right pr-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($surveys as $idx => $survey)
                            <tr>
                                <td class="py-3 pl-3 text-muted">{{ $idx + 1 + ($surveys->currentPage() - 1) * $surveys->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('survey.show', $survey->id) }}" class="font-weight-bold text-dark text-decoration-none hover-text-primary mr-2">
                                            {{ $survey->title }}
                                        </a>
                                        @if($survey->is_anonymous)
                                            <span class="badge badge-light border text-muted" style="font-size: 10px;">Anonymous</span>
                                        @endif
                                    </div>
                                    @if($survey->description)
                                        <div class="text-muted small text-truncate" style="max-width: 320px;">{{ $survey->description }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-light border px-2 py-1 text-secondary" style="font-size: 11px;">
                                        {{ $survey->kind_label }}
                                    </span>
                                </td>
                                <td class="text-center font-weight-bold">{{ $survey->questions->count() }}</td>
                                <td class="text-center font-weight-bold text-muted">{{ $survey->invited_count }}</td>
                                <td class="text-center">
                                    <span class="font-weight-bold text-dark">{{ $survey->responses_count }}</span>
                                    @if($survey->invited_count > 0)
                                        <div class="progress mt-1" style="height: 4px; width: 60px; margin: 0 auto;">
                                            <div class="progress-bar bg-success" style="width: {{ min(100, $survey->response_rate) }}%"></div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $survey->status_badge }} px-2 py-1 text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                                        {{ $survey->status }}
                                    </span>
                                </td>
                                <td class="text-right pr-3">
                                    <div class="btn-group btn-group-xs">
                                        <a href="{{ route('survey.show', $survey->id) }}" class="btn btn-outline-primary btn-xs px-2 py-1" title="Open Overview">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('survey.edit', $survey->id) }}" class="btn btn-outline-secondary btn-xs px-2 py-1" title="Edit Survey">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <a href="{{ route('survey.report', $survey->id) }}" class="btn btn-outline-info btn-xs px-2 py-1" title="Analytics & Report">
                                            <i class="fas fa-chart-bar"></i>
                                        </a>
                                        @if($survey->status === 'draft')
                                            <form action="{{ route('survey.publish', $survey->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-success btn-xs px-2 py-1" title="Publish">
                                                    <i class="fas fa-upload"></i>
                                                </button>
                                            </form>
                                        @elseif($survey->status === 'published')
                                            <form action="{{ route('survey.close', $survey->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-warning btn-xs px-2 py-1" title="Close Survey">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('survey.destroy', $survey->id) }}" method="POST" onsubmit="return confirm('Delete survey [{{ $survey->title }}]?')" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-xs px-2 py-1" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No surveys found matching your query.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($surveys->hasPages())
                <div class="card-footer bg-white border-top py-2">
                    {{ $surveys->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

