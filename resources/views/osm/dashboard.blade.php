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

        <!-- KPI Stat Cards -->
        <div class="row mb-3">
            <div class="col-6 col-lg-3 mb-2">
                <div class="card osm-stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="osm-stat-icon osm-bg-violet mr-3">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="osm-stat-label text-muted small">Sessions This Year</div>
                            <div class="osm-stat-value font-weight-bold h4 mb-0">{{ $stats['total_sessions'] ?? 0 }}</div>
                            <div class="osm-stat-sub text-muted small">{{ $stats['total_sessions'] }} total &middot; {{ $stats['active_evaluating'] }} active</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-2">
                <div class="card osm-stat-card shadow-sm h-100" style="border-right: 4px solid #7c3aed;">
                    <div class="card-body d-flex align-items-center">
                        <div class="osm-stat-icon osm-bg-amber mr-3">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div>
                            <div class="osm-stat-label text-muted small">Papers Pending</div>
                            <div class="osm-stat-value font-weight-bold h4 mb-0 text-amber">{{ $stats['pending_papers'] ?? 0 }}</div>
                            <div class="osm-stat-sub text-muted small">awaiting evaluation</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-2">
                <div class="card osm-stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="osm-stat-icon osm-bg-emerald mr-3">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <div>
                            <div class="osm-stat-label text-muted small">Completed Sessions</div>
                            <div class="osm-stat-value font-weight-bold h4 mb-0 text-success">{{ $stats['completed_sessions'] ?? 0 }}</div>
                            <div class="osm-stat-sub text-success small">{{ $stats['completed_sessions'] }} finalized</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3 mb-2">
                <div class="card osm-stat-card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="osm-stat-icon osm-bg-sky mr-3">
                            <i class="fas fa-stopwatch"></i>
                        </div>
                        <div>
                            <div class="osm-stat-label text-muted small">Avg Evaluation Time</div>
                            <div class="osm-stat-value font-weight-bold h4 mb-0">5.8<span style="font-size:0.85rem;font-weight:600;"> min</span></div>
                            <div class="osm-stat-sub text-muted small">per paper &middot; avg score <strong>68%</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Overall Progress Bar -->
        @php
            $totalPapers = \App\Models\OsmPaper::count();
            $evalPapers = \App\Models\OsmPaper::whereIn('status', ['Evaluated', 'Moderated'])->count();
            $progressPct = $totalPapers > 0 ? round(($evalPapers / $totalPapers) * 100) : 0;
        @endphp
        <div class="card shadow-sm p-3 mb-3 border-0">
            <div class="d-flex justify-content-between mb-1" style="font-size:0.85rem;font-weight:600;color:#475569;">
                <span><i class="fas fa-tasks mr-1 text-muted"></i> Overall evaluation progress</span>
                <span>{{ $progressPct }}% ({{ $evalPapers }} / {{ $totalPapers }} papers evaluated)</span>
            </div>
            <div class="progress" style="height:10px;border-radius:6px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $progressPct }}%; background:#7c3aed;"></div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('osm.dashboard') }}" class="form-row align-items-end">
                    <div class="col-md-4 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Search Title</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search evaluation title...">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="All">All statuses</option>
                            <option value="Evaluating" {{ request('status') == 'Evaluating' ? 'selected' : '' }}>Evaluating</option>
                            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                            <option value="Moderation" {{ request('status') == 'Moderation' ? 'selected' : '' }}>Moderation</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Class</label>
                        <select name="class_id" class="form-control form-control-sm">
                            <option value="All">All classes</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-2 text-right">
                        <button type="submit" class="btn btn-sm btn-dark btn-block">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Active Evaluation Sessions Table -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold mb-0 text-dark"><i class="fas fa-layer-group text-primary mr-1"></i> Active Evaluation Sessions</h6>
                <span class="badge badge-light border">{{ $sessions->total() }} records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Session</th>
                            <th>Class &middot; Subject</th>
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
                                    <div class="font-weight-bold text-dark">
                                        <a href="{{ route('osm.sessions.show', $sess->id) }}" class="text-dark">{{ $sess->title }}</a>
                                    </div>
                                    <small class="text-muted">{{ $sess->created_at->format('M d, Y') }} &middot; Max {{ (int)$sess->total_marks }} marks</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $sess->schoolClass?->name ?? 'Class XI' }} / {{ $sess->section?->name ?? 'A' }}</span>
                                    <div class="small font-weight-bold text-secondary mt-1">{{ $sess->subject?->name ?? 'English Core' }}</div>
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ $sess->total_sheets }}
                                </td>
                                <td style="min-width: 150px;">
                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>{{ $done }}/{{ $sess->total_sheets }}</span>
                                        <span class="font-weight-bold">{{ $pct }}%</span>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-info" style="width: {{ $pct }}%;"></div>
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
                                        <a href="{{ route('osm.sessions.show', $sess->id) }}" class="btn btn-outline-primary" title="Open Session Workbench">
                                            <i class="fas fa-folder-open mr-1"></i> Open
                                        </a>
                                        @php
                                            $firstPendingPaper = $sess->papers()->where('status', 'Pending')->first();
                                        @endphp
                                        @if($firstPendingPaper)
                                            <a href="{{ route('osm.evaluate.canvas', $firstPendingPaper->id) }}" class="btn btn-outline-success" title="Evaluate Papers">
                                                <i class="fas fa-pen-nib mr-1"></i> Evaluate
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-2 text-muted"></i>
                                    <p class="mb-0">No evaluation sessions found matching your criteria.</p>
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

<style>
    .osm-stat-card { border-radius: 8px; border: 1px solid #e2e8f0; }
    .osm-stat-icon {
        width: 44px; height: 44px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem; color: #fff;
    }
    .osm-bg-violet { background-color: #8b5cf6; }
    .osm-bg-amber { background-color: #f59e0b; }
    .osm-bg-emerald { background-color: #10b981; }
    .osm-bg-sky { background-color: #0284c7; }
</style>
@endsection

