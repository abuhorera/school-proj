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
        <!-- Session Selector Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('osm.reports') }}" class="form-row align-items-end">
                    <div class="col-md-8 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Evaluation Session</label>
                        <select name="session_id" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Select a session --</option>
                            @foreach($sessions as $s)
                                <option value="{{ $s->id }}" {{ $selectedSession && $selectedSession->id == $s->id ? 'selected' : '' }}>
                                    {{ $s->title }} ({{ $s->subject?->name ?? 'Subject' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2 text-right">
                        @if($selectedSession)
                            <a href="{{ route('osm.reports.analytics', $selectedSession->id) }}" class="btn btn-outline-info mr-1">
                                <i class="fas fa-chart-pie mr-1"></i> Analytics
                            </a>
                            <a href="{{ route('osm.reports.evaluator', $selectedSession->id) }}" class="btn btn-outline-dark mr-1">
                                <i class="fas fa-user-check mr-1"></i> Evaluators
                            </a>
                            <button type="button" class="btn btn-secondary" onclick="window.print()">
                                <i class="fas fa-print mr-1"></i> Print
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($selectedSession)
            <!-- Executive Summary Cards -->
            @php
                $evalCount = $papers->whereIn('status', ['Evaluated', 'Moderated'])->count();
                $avgScore = $evalCount > 0 ? round($papers->whereIn('status', ['Evaluated', 'Moderated'])->average('total_score'), 1) : 0;
                $highest = $evalCount > 0 ? $papers->whereIn('status', ['Evaluated', 'Moderated'])->max('total_score') : 0;
            @endphp
            <div class="row mb-3">
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Total Candidates</div>
                            <div class="h4 font-weight-bold text-dark mb-0">{{ $papers->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Evaluated Papers</div>
                            <div class="h4 font-weight-bold text-success mb-0">{{ $evalCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Average Mark</div>
                            <div class="h4 font-weight-bold text-primary mb-0">{{ $avgScore }} <small class="text-muted" style="font-size: 0.8rem;">/ {{ (int)$selectedSession->total_marks }}</small></div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Top Score</div>
                            <div class="h4 font-weight-bold text-warning mb-0">{{ $highest }} <small class="text-muted" style="font-size: 0.8rem;">/ {{ (int)$selectedSession->total_marks }}</small></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Score Roster Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-clipboard-check text-primary mr-1"></i> Marks Ledger: {{ $selectedSession->title }}
                    </h6>
                    <span class="badge badge-light border">Subject: {{ $selectedSession->subject?->name ?? 'Subject' }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Student</th>
                                <th>Roll</th>
                                <th>Evaluator</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th class="text-right">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($papers as $paper)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">
                                            {{ $paper->student?->first_name }} {{ $paper->student?->last_name }}
                                        </div>
                                        <small class="text-muted">Code: {{ $paper->anonymous_code }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border font-mono">{{ $paper->student?->admission_number ?? '1' }}</span>
                                    </td>
                                    <td>
                                        <small class="font-weight-bold text-secondary">
                                            {{ $paper->evaluator?->first_name ?? 'Faculty' }} {{ $paper->evaluator?->last_name ?? '' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold {{ $paper->status == 'Evaluated' || $paper->status == 'Moderated' ? 'text-success' : 'text-muted' }}">
                                            {{ $paper->is_moderated ? $paper->moderated_score : $paper->total_score }} / {{ (int)$selectedSession->total_marks }}
                                        </span>
                                        @php
                                            $pct = $selectedSession->total_marks > 0 ? round((($paper->is_moderated ? $paper->moderated_score : $paper->total_score) / $selectedSession->total_marks) * 100) : 0;
                                        @endphp
                                        <small class="text-muted d-block">({{ $pct }}%)</small>
                                    </td>
                                    <td>
                                        @if($paper->status == 'Moderated')
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-balance-scale mr-1"></i> Moderated</span>
                                        @elseif($paper->status == 'Evaluated')
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Evaluated</span>
                                        @elseif($paper->status == 'Rejected')
                                            <span class="badge badge-danger px-2 py-1">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('osm.evaluate.canvas', $paper->id) }}" class="btn btn-sm btn-outline-primary" title="View Marked Script">
                                            <i class="fas fa-file-signature mr-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No papers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card shadow-sm p-5 text-center text-muted">
                <i class="fas fa-chart-bar fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">Select a session to view its report.</h5>
            </div>
        @endif
    </div>
</section>
@endsection

