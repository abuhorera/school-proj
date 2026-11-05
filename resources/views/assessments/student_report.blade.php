@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Classroom Assessment &mdash; Student Report</h3>
                <p class="text-muted small mb-0">Individual candidate progress scorecard, continuous evaluation history, and grade analysis.</p>
            </div>
            <div>
                <a href="{{ route('assessment.reports') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Analytics
                </a>
            </div>
        </div>

        @include('assessments.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Student Selector Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-body p-3">
                <form action="{{ route('assessment.reports.student') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-6 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Select Student</label>
                            <select name="student_id" class="form-control form-control-sm" onchange="this.form.submit()">
                                @foreach($students as $st)
                                    <option value="{{ $st->id }}" {{ ($selectedStudent?->id == $st->id) ? 'selected' : '' }}>
                                        {{ $st->first_name }} {{ $st->last_name }} ({{ $st->admission_number ?? 'ADM-' . $st->id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button type="submit" class="btn btn-primary btn-sm px-3">View Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($selectedStudent)
            <!-- Student Header Profile Banner -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center flex-wrap">
                        <div class="avatar-lg bg-emerald-500 rounded-circle text-white d-flex align-items-center justify-content-center mr-3 font-weight-bold" style="width: 55px; height: 55px; font-size: 22px;">
                            {{ substr($selectedStudent->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-weight-bold text-white mb-1">{{ $selectedStudent->first_name }} {{ $selectedStudent->last_name }}</h4>
                            <p class="text-slate-300 small mb-0">
                                Admission No: <code class="text-cyan-300">{{ $selectedStudent->admission_number ?? ('ADM-' . $selectedStudent->id) }}</code> &bull; 
                                Roll: <strong class="text-white">{{ $selectedStudent->roll_no ?? '-' }}</strong> &bull; 
                                Session: 2026-2027
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment History Ledger Table -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold text-dark mb-0">
                        <i class="fas fa-history text-primary mr-1.5"></i> Assessment Evaluation History
                    </h5>
                    <span class="badge badge-light border text-muted">{{ $studentMarks->count() }} evaluations</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="pl-4">Date</th>
                                    <th>Assessment Title</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                    <th class="text-center">Score / Max</th>
                                    <th class="text-center">Percentage</th>
                                    <th class="text-center">Grade</th>
                                    <th class="pr-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($studentMarks as $sm)
                                    @php
                                        $occ = $sm->occurrence;
                                        $ass = $occ?->assessment;
                                    @endphp
                                    <tr>
                                        <td class="pl-4 font-monospace small text-muted font-weight-bold">
                                            {{ $occ?->sitting_date ? $occ->sitting_date->format('d M Y') : '-' }}
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ $ass?->title ?? 'Classroom Assessment' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $ass?->type_badge_color ?? 'light' }} px-2 py-1">
                                                {{ $ass?->type ?? 'Quiz' }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $ass?->subject?->name ?? 'General' }}</strong></td>
                                        <td class="text-center font-weight-bold">
                                            @if($sm->is_absent)
                                                <span class="text-danger">AB</span>
                                            @else
                                                {{ (float)$sm->marks_obtained }} / {{ (int)($ass?->total_marks ?? 30) }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $sm->percentage !== null ? $sm->percentage . '%' : '-' }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ $sm->grade_badge['color'] }} px-2 py-1 font-weight-bold">
                                                {{ $sm->grade_badge['grade'] }}
                                            </span>
                                        </td>
                                        <td class="pr-4 text-center">
                                            @if($sm->is_absent)
                                                <span class="badge badge-danger">Absent</span>
                                            @elseif($sm->marks_obtained >= ($ass?->passing_marks ?? 12))
                                                <span class="badge badge-success">Passed</span>
                                            @else
                                                <span class="badge badge-warning">Needs Review</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">No assessment scores recorded for this pupil yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

