@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">{{ $assessment->title }}</h3>
                <p class="text-muted small mb-0">{{ $assessment->subject?->name }} &bull; {{ $assessment->class_section_text }} &bull; {{ $assessment->frequency }}</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('assessment.assessments.edit', $assessment->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-pen mr-1"></i> Edit Assessment
                </a>
                <a href="{{ route('assessment.assessments') }}" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
        </div>

        @include('assessments.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <!-- Left: Assessment Details -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-info-circle text-emerald-600 mr-1.5"></i> Assessment Details
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped small mb-0">
                            <tbody>
                                <tr>
                                    <th class="pl-4 text-muted" style="width: 140px;">Type</th>
                                    <td><span class="badge badge-{{ $assessment->type_badge_color }}">{{ $assessment->type }}</span></td>
                                </tr>
                                <tr>
                                    <th class="pl-4 text-muted">Subject</th>
                                    <td><strong>{{ $assessment->subject?->name ?? 'General' }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="pl-4 text-muted">Class</th>
                                    <td><strong>{{ $assessment->class_section_text }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="pl-4 text-muted">Total Marks</th>
                                    <td><strong class="text-dark">{{ (int)$assessment->total_marks }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="pl-4 text-muted">Passing Marks</th>
                                    <td><strong class="text-danger">{{ (int)$assessment->passing_marks }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="pl-4 text-muted">Recurrence</th>
                                    <td>{{ $assessment->frequency }}</td>
                                </tr>
                                <tr>
                                    <th class="pl-4 text-muted">Conducted via</th>
                                    <td>{{ $assessment->conducted_via }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if($assessment->instructions)
                        <div class="card-footer bg-light border-top p-3 small text-muted">
                            <strong class="text-dark d-block mb-1">Instructions / Rubric:</strong>
                            {{ $assessment->instructions }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Sittings / Occurrences Ledger -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-calendar-check text-primary mr-1.5"></i> Sittings &amp; Grading Sessions
                        </h5>
                        <span class="badge badge-light border">{{ $assessment->occurrences->count() }} sitting(s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="pl-4">Sitting Date</th>
                                        <th>Status</th>
                                        <th>Marks Entered</th>
                                        <th class="pr-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assessment->occurrences as $occ)
                                        <tr>
                                            <td class="pl-4 font-weight-bold text-dark">
                                                <i class="fas fa-calendar-alt text-muted mr-1.5"></i>
                                                {{ $occ->sitting_date->format('d M Y') }}
                                            </td>
                                            <td>
                                                @if($occ->status === 'Published')
                                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Published</span>
                                                @else
                                                    <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Grading</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="font-weight-bold text-dark">{{ $occ->marks->count() }}</span>
                                                <small class="text-muted">pupil{{ $occ->marks->count() == 1 ? '' : 's' }}</small>
                                            </td>
                                            <td class="pr-4 text-right">
                                                <div class="btn-group">
                                                    <a href="{{ route('assessment.occurrences.marks', $occ->id) }}" class="btn btn-primary btn-xs px-2.5 py-1 font-weight-bold">
                                                        <i class="fas fa-edit mr-1"></i> Enter marks
                                                    </a>
                                                    @if($occ->status !== 'Published')
                                                        <form action="{{ route('assessment.occurrences.publish', $occ->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Publish assessment results to pupils & parents?')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-xs px-2.5 py-1 font-weight-bold">
                                                                <i class="fas fa-bullhorn mr-1"></i> Publish
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('assessment.occurrences.marksheet', $occ->id) }}" class="btn btn-outline-secondary btn-xs px-2 py-1" title="Print Marksheet" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">No sittings created yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

