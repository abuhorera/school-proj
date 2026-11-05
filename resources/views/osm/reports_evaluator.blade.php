@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="osm-title-area">
                <h3 class="font-weight-bold text-dark mb-0">Evaluator Performance</h3>
                <p class="text-muted small mb-0">{{ $session->title }} &middot; {{ $session->subject?->name ?? 'Subject' }}</p>
            </div>
            <div>
                <a class="btn btn-default" href="{{ route('osm.reports', ['session_id' => $session->id]) }}">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Report
                </a>
            </div>
        </div>

        @include('osm.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-user-check text-primary mr-1"></i> Evaluator Activity &amp; Turnaround Audit
                </h6>
                <span class="badge badge-light border">Max {{ (int)$session->total_marks }} marks</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Evaluator</th>
                            <th class="text-center">Assigned</th>
                            <th class="text-center">Completed</th>
                            <th class="text-center">Turnaround (Avg Time)</th>
                            <th class="text-center">Avg Score Awarded</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluators as $ev)
                            @php
                                $evalPapers = $session->papers()->where('evaluator_staff_id', $ev->staff_id)->whereIn('status', ['Evaluated', 'Moderated'])->get();
                                $completedCount = $evalPapers->count();
                                $avgSecs = $completedCount > 0 ? $evalPapers->average('elapsed_seconds') : 0;
                                $avgMins = $avgSecs > 0 ? round($avgSecs / 60, 1) : 5.5;
                                $avgSc = $completedCount > 0 ? round($evalPapers->average('total_score'), 1) : '—';
                            @endphp
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        <i class="fas fa-user-circle text-muted mr-1"></i>
                                        {{ $ev->staff?->first_name }} {{ $ev->staff?->last_name }}
                                    </div>
                                    <small class="text-muted">{{ $ev->staff?->designation?->name ?? 'Faculty Member' }}</small>
                                </td>
                                <td class="text-center font-weight-bold">{{ $ev->assigned_count ?: 1 }}</td>
                                <td class="text-center font-weight-bold text-success">{{ $completedCount }}</td>
                                <td class="text-center">
                                    <span class="badge badge-light border font-monospace">
                                        {{ $avgMins }} min
                                    </span>
                                </td>
                                <td class="text-center font-weight-bold text-primary">
                                    {{ $avgSc }}
                                </td>
                                <td>
                                    @if($completedCount >= ($ev->assigned_count ?: 1))
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Completed</span>
                                    @else
                                        <span class="badge badge-warning px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> In Progress</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No evaluators assigned yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

