@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Classroom Assessment &mdash; Rank List</h3>
                <p class="text-muted small mb-0">Student leaderboard, overall rank standings, and achievement tiers.</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('assessment.reports') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-chart-line mr-1"></i> Analytics Hub
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
                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-filter text-warning mr-1"></i> Leaderboard Filter</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('assessment.reports.ranking') }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Class</label>
                            <select name="class_id" class="form-control form-control-sm">
                                <option value="">Select class</option>
                                @foreach($classes as $cls)
                                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                                        {{ $cls->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Subject</label>
                            <select name="subject_id" class="form-control form-control-sm">
                                <option value="">Overall &mdash; All Subjects</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2 d-flex" style="gap: 5px;">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Apply Filter</button>
                            <a href="{{ route('assessment.reports.ranking') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Leaderboard Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-trophy text-warning mr-1.5"></i> Academic Rank Standings
                </h5>
                <span class="badge badge-light border text-muted">Top Achievers</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4 text-center" style="width: 80px;">Rank</th>
                                <th>Student Name</th>
                                <th>Admission Number</th>
                                <th class="text-center">Tests Taken</th>
                                <th style="width: 200px;">Average Score</th>
                                <th class="pr-4 text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $st)
                                <tr>
                                    <td class="pl-4 text-center">
                                        @if($st['rank'] === 1)
                                            <span class="badge badge-warning text-dark font-weight-bold px-2.5 py-1" style="font-size: 13px;">
                                                <i class="fas fa-medal mr-1"></i> #1
                                            </span>
                                        @elseif($st['rank'] === 2)
                                            <span class="badge badge-secondary font-weight-bold px-2 py-1" style="font-size: 12px;">
                                                #2
                                            </span>
                                        @elseif($st['rank'] === 3)
                                            <span class="badge font-weight-bold text-white px-2 py-1" style="background-color: #b45309; font-size: 12px;">
                                                #3
                                            </span>
                                        @else
                                            <span class="font-weight-bold text-muted">#{{ $st['rank'] }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ $st['name'] }}</strong>
                                    </td>
                                    <td><code>{{ $st['admission_number'] }}</code></td>
                                    <td class="text-center font-weight-bold text-muted">{{ $st['assessments_taken'] }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="font-weight-bold mr-2 text-dark small">{{ $st['average_percentage'] }}%</span>
                                            <div class="progress flex-fill" style="height: 6px;">
                                                <div class="progress-bar {{ $st['average_percentage'] >= 80 ? 'bg-success' : ($st['average_percentage'] >= 60 ? 'bg-warning' : 'bg-danger') }}" style="width: {{ $st['average_percentage'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pr-4 text-center">
                                        <span class="badge badge-{{ $st['grade'] === 'A+' || $st['grade'] === 'A' ? 'success' : ($st['grade'] === 'B' ? 'primary' : 'info') }} px-2.5 py-1">
                                            {{ $st['grade'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

