@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="osm-title-area">
                <h3 class="font-weight-bold text-dark mb-0">Analytics &mdash; {{ $session->title }}</h3>
                <p class="text-muted small mb-0">{{ $session->subject?->name ?? 'Subject' }} &middot; {{ $session->schoolClass?->name ?? 'Class' }} / {{ $session->section?->name ?? 'A' }}</p>
            </div>
            <div>
                <a class="btn btn-default mr-1" href="{{ route('osm.reports', ['session_id' => $session->id]) }}">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Report
                </a>
                <a class="btn btn-outline-dark" href="{{ route('osm.reports.evaluator', $session->id) }}">
                    <i class="fas fa-user-check mr-1"></i> Evaluator Performance
                </a>
            </div>
        </div>

        @include('osm.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Metric Cards -->
        <div class="row mb-3">
            <div class="col-6 col-lg-3 mb-2">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-muted small">Evaluated Papers</div>
                        <div class="h4 font-weight-bold text-dark mb-0">{{ $stats['evaluated'] }} / {{ $stats['total_papers'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-2">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-muted small">Average Score</div>
                        <div class="h4 font-weight-bold text-primary mb-0">
                            {{ $stats['avg_score'] }} <small class="text-muted font-weight-normal">({{ $session->total_marks > 0 ? round(($stats['avg_score'] / $session->total_marks) * 100) : 0 }}%)</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-2">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-muted small">Highest Mark</div>
                        <div class="h4 font-weight-bold text-success mb-0">{{ $stats['highest_score'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-2">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-3 text-center">
                        <div class="text-muted small">Lowest Mark</div>
                        <div class="h4 font-weight-bold text-danger mb-0">{{ $stats['lowest_score'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Score Distribution Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-chart-bar text-primary mr-1"></i> Performance Bands &amp; Score Distribution
                </h6>
            </div>
            <div class="card-body p-3">
                <div class="row text-center mb-3">
                    <div class="col-md-3 mb-2">
                        <div class="p-2 border rounded bg-light">
                            <span class="badge badge-success mb-1">Distinction (&ge;75%)</span>
                            <div class="h5 font-weight-bold mb-0 text-success">{{ $stats['distinction_count'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="p-2 border rounded bg-light">
                            <span class="badge badge-primary mb-1">First Division (60-74%)</span>
                            <div class="h5 font-weight-bold mb-0 text-primary">{{ $stats['first_div_count'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="p-2 border rounded bg-light">
                            <span class="badge badge-warning mb-1">Second Division (40-59%)</span>
                            <div class="h5 font-weight-bold mb-0 text-amber">{{ $stats['pass_count'] }}</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="p-2 border rounded bg-light">
                            <span class="badge badge-danger mb-1">Remedial (&lt;40%)</span>
                            <div class="h5 font-weight-bold mb-0 text-danger">{{ $stats['remedial_count'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Question Performance Breakdown Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-list-ol text-info mr-1"></i> Question-by-Question Difficulty &amp; Average Marks
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Q. No.</th>
                            <th>Question Label &amp; Description</th>
                            <th class="text-center">Max Marks</th>
                            <th class="text-center">Average Marks Awarded</th>
                            <th class="text-center">Scoring %</th>
                            <th>Pedagogical Difficulty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($session->questions as $q)
                            @php
                                $avg = $q->scores()->where('is_checked', true)->average('marks_awarded') ?? 0;
                                $pct = $q->max_marks > 0 ? round(($avg / $q->max_marks) * 100) : 0;
                            @endphp
                            <tr>
                                <td class="font-weight-bold">{{ $q->question_number }}</td>
                                <td>
                                    <strong>{{ $q->question_label }}</strong>
                                    <div class="small text-muted">{{ $q->question_text }}</div>
                                </td>
                                <td class="text-center font-weight-bold">{{ (int)$q->max_marks }}</td>
                                <td class="text-center font-weight-bold text-primary">{{ round($avg, 1) }}</td>
                                <td class="text-center">
                                    <span class="font-weight-bold">{{ $pct }}%</span>
                                    <div class="progress" style="height: 4px; width: 60px; margin: 0 auto;">
                                        <div class="progress-bar {{ $pct >= 70 ? 'bg-success' : ($pct >= 40 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $pct }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($pct >= 75)
                                        <span class="badge badge-success px-2 py-1">High Mastery (Easy)</span>
                                    @elseif($pct >= 50)
                                        <span class="badge badge-info px-2 py-1">Standard (Moderate)</span>
                                    @else
                                        <span class="badge badge-danger px-2 py-1">Needs Remedial (Hard)</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No questions configured for this session.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

