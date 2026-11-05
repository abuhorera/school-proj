@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="osm-title-area">
                <h3 class="font-weight-bold text-dark mb-0">Digital Evaluation</h3>
                <p class="text-muted small mb-0">On-screen marking of scanned answer sheets — upload, evaluate question-by-question, and report.</p>
            </div>
            <div>
                <a class="btn btn-osm-primary" href="{{ route('osm.sessions.create') }}">
                    <i class="fas fa-plus mr-1"></i> <span class="d-none d-md-inline">New Session</span>
                </a>
            </div>
        </div>

        @include('osm.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <form class="form-row align-items-end" method="GET" action="{{ route('osm.sessions') }}">
                    <div class="col-md-4 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Status</label>
                        <select class="form-control form-control-sm" name="status">
                            <option value="All">All statuses</option>
                            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Uploading" {{ request('status') == 'Uploading' ? 'selected' : '' }}>Uploading</option>
                            <option value="Ready" {{ request('status') == 'Ready' ? 'selected' : '' }}>Ready</option>
                            <option value="Evaluating" {{ request('status') == 'Evaluating' ? 'selected' : '' }}>Evaluating</option>
                            <option value="Moderation" {{ request('status') == 'Moderation' ? 'selected' : '' }}>Moderation</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Archived" {{ request('status') == 'Archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Class</label>
                        <select class="form-control form-control-sm" name="class_id">
                            <option value="All">All classes</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-2 text-right">
                        <button class="btn btn-sm btn-dark px-3" type="submit">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0 text-dark">
                    <i class="fas fa-layer-group text-primary mr-1"></i> All Evaluation Sessions
                </h6>
                <span class="badge badge-light border">{{ $sessions->total() }} sessions</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Session</th>
                            <th>Class / Subject</th>
                            <th class="text-center">Sheets</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sessions as $sess)
                            @php
                                $total = $sess->total_sheets ?: 1;
                                $done = $sess->evaluated_sheets ?: 0;
                                $pct = min(100, round(($done / $total) * 100));
                            @endphp
                            <tr>
                                <td>
                                    <div class="font-weight-bold">
                                        <a href="{{ route('osm.sessions.show', $sess->id) }}" class="text-dark">{{ $sess->title }}</a>
                                    </div>
                                    <span class="badge badge-secondary" style="font-size: 0.72rem;">Max {{ (int)$sess->total_marks }}</span>
                                    <small class="text-muted ml-1">&middot; Created {{ $sess->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $sess->schoolClass?->name ?? 'Class XI' }} / {{ $sess->section?->name ?? 'A' }}</span>
                                    <div class="small font-weight-bold text-secondary mt-1">{{ $sess->subject?->name ?? 'English Core' }}</div>
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ $sess->total_sheets }}
                                </td>
                                <td style="min-width: 140px;">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>{{ $done }}/{{ $sess->total_sheets }}</span>
                                        <span class="font-weight-bold">{{ $pct }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar {{ $pct == 100 ? 'bg-success' : 'bg-info' }}" style="width: {{ $pct }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($sess->status == 'Completed')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                                    @elseif($sess->status == 'Evaluating')
                                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Evaluating</span>
                                    @elseif($sess->status == 'Moderation')
                                        <span class="badge badge-primary px-2 py-1"><i class="fas fa-balance-scale mr-1"></i> Moderation</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">{{ $sess->status }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('osm.sessions.show', $sess->id) }}" class="btn btn-outline-primary" title="View Workbench">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        <a href="{{ route('osm.sessions.edit', $sess->id) }}" class="btn btn-outline-secondary" title="Edit Session">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('osm.sessions.destroy', $sess->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this evaluation session?');">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-2 text-muted"></i>
                                    <p class="mb-0">No evaluation sessions found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($sessions->hasPages())
                <div class="card-footer bg-white py-2">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

