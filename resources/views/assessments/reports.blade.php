@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Assessment Reports &amp; Analytics</h3>
                <p class="text-muted small mb-0">Cohort learning performance, score distributions, and subject-wise achievement trends.</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('assessment.reports.ranking') }}" class="btn btn-outline-warning btn-sm text-dark font-weight-bold">
                    <i class="fas fa-trophy mr-1 text-warning"></i> Rank List
                </a>
                <a href="{{ route('assessment.reports.student') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-user-graduate mr-1"></i> Student Report
                </a>
            </div>
        </div>

        @include('assessments.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-2.5">
                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-filter text-info mr-1"></i> Filter Analytics</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('assessment.reports') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Class</label>
                            <select name="class_id" class="form-control form-control-sm">
                                <option value="">-- All Classes --</option>
                                @foreach($classes as $cls)
                                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                        {{ $cls->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Subject</label>
                            <select name="subject_id" class="form-control form-control-sm">
                                <option value="">-- All Subjects --</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Type</label>
                            <select name="type" class="form-control form-control-sm">
                                <option value="All">All Types</option>
                                <option value="Quiz" {{ request('type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                <option value="Test" {{ request('type') == 'Test' ? 'selected' : '' }}>Test</option>
                                <option value="Oral" {{ request('type') == 'Oral' ? 'selected' : '' }}>Oral</option>
                                <option value="Practical" {{ request('type') == 'Practical' ? 'selected' : '' }}>Practical</option>
                                <option value="Assignment" {{ request('type') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2 d-flex" style="gap: 5px;">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Apply</button>
                            <a href="{{ route('assessment.reports') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sittings Analytics Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">Assessment Sittings Performance Summary</h5>
                <span class="badge badge-light border text-muted">{{ $sittings->total() }} sittings evaluated</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Date</th>
                                <th>Assessment</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Total Marks</th>
                                <th>Evaluated</th>
                                <th>Average Score</th>
                                <th class="pr-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sittings as $sit)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark font-monospace small">
                                        {{ $sit->sitting_date->format('d M Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('assessment.assessments.show', $sit->assessment->id) }}" class="font-weight-bold text-dark text-decoration-none">
                                            {{ $sit->assessment->title }}
                                        </a>
                                        <small class="d-block text-muted">{{ $sit->assessment->type }}</small>
                                    </td>
                                    <td><span class="badge badge-light border text-dark">{{ $sit->assessment->class_section_text }}</span></td>
                                    <td><strong>{{ $sit->assessment->subject?->name ?? 'General' }}</strong></td>
                                    <td><span class="font-weight-bold">{{ (int)$sit->assessment->total_marks }}</span></td>
                                    <td>
                                        <span class="badge badge-light border text-muted">
                                            {{ $sit->marks->count() }} pupils
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $avgPct = $sit->average_percentage;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <span class="small font-weight-bold mr-2 text-dark">{{ $avgPct }}%</span>
                                            <div class="progress flex-fill" style="height: 6px; width: 60px;">
                                                <div class="progress-bar {{ $avgPct >= 75 ? 'bg-success' : ($avgPct >= 50 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $avgPct }}%;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pr-4 text-right">
                                        <a href="{{ route('assessment.occurrences.marksheet', $sit->id) }}" class="btn btn-outline-info btn-xs px-2 py-1" target="_blank">
                                            <i class="fas fa-print mr-1"></i> Marksheet
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">No assessment sitting records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($sittings->hasPages())
                <div class="card-footer bg-white border-top py-2 d-flex justify-content-end">
                    {{ $sittings->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

