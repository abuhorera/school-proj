@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Classroom Assessment</h3>
                <p class="text-muted small mb-0">Continuous evaluation, weekly quizzes, unit tests, and student progress tracking.</p>
            </div>
            <div>
                <a href="{{ route('assessment.assessments.create') }}" class="btn btn-emerald text-white btn-sm font-weight-bold" style="background-color: #059669;">
                    <i class="fas fa-plus mr-1"></i> New Assessment
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

        <!-- 4 KPI Cards Matching Demo -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3.5" style="border-radius: 12px; border-left: 4px solid #059669 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Assessments</span>
                    <h2 class="font-weight-bold text-dark mb-0 mt-1">{{ number_format($stats['total_assessments']) }}</h2>
                    <small class="text-muted">Total tests &amp; quizzes created</small>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3.5" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Sittings</span>
                    <h2 class="font-weight-bold text-info mb-0 mt-1">{{ number_format($stats['total_occurrences']) }}</h2>
                    <small class="text-muted">Classroom examination sessions</small>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3.5" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Published</span>
                    <h2 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($stats['published_count']) }}</h2>
                    <small class="text-muted">Results available to parents</small>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3.5" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Average Score</span>
                    <h2 class="font-weight-bold text-warning mb-0 mt-1">{{ $stats['average_score'] }}%</h2>
                    <small class="text-muted">Overall student achievement</small>
                </div>
            </div>
        </div>

        <!-- Recent Assessments Table Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-history text-emerald-600 mr-1.5"></i> Recent Assessments
                </h5>
                <a href="{{ route('assessment.assessments') }}" class="btn btn-outline-secondary btn-xs px-2.5 py-1">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Title</th>
                                <th>Type</th>
                                <th>Subject</th>
                                <th>Class / Section</th>
                                <th>Marks</th>
                                <th>Sittings</th>
                                <th class="pr-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assessments as $ass)
                                <tr>
                                    <td class="pl-4">
                                        <a href="{{ route('assessment.assessments.show', $ass->id) }}" class="font-weight-bold text-dark text-decoration-none hover-text-primary">
                                            {{ $ass->title }}
                                        </a>
                                        <small class="d-block text-muted">Via {{ $ass->conducted_via }} &bull; {{ $ass->frequency }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ass->type_badge_color }} px-2 py-1">
                                            {{ $ass->type }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $ass->subject?->name ?? 'General' }}</strong></td>
                                    <td><span class="badge badge-light border text-dark">{{ $ass->class_section_text }}</span></td>
                                    <td>
                                        <span class="font-weight-bold">{{ (int)$ass->total_marks }}</span>
                                        <small class="text-muted">/ Pass {{ (int)$ass->passing_marks }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-info font-weight-bold">
                                            {{ $ass->occurrences->count() }} sitting{{ $ass->occurrences->count() == 1 ? '' : 's' }}
                                        </span>
                                    </td>
                                    <td class="pr-4 text-right">
                                        <a href="{{ route('assessment.assessments.show', $ass->id) }}" class="btn btn-outline-primary btn-xs px-2 py-1">
                                            <i class="fas fa-eye mr-1"></i> Open
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard-list fa-3x mb-2 text-slate-300"></i>
                                        <p class="mb-0">No classroom assessments created yet. Click <strong>New Assessment</strong> to get started.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Guide & Pedagogical Callout -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border: 1px solid #bbf7d0 !important;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap">
                <div class="mb-2 mb-md-0">
                    <h5 class="font-weight-bold text-emerald-800 mb-1">
                        <i class="fas fa-graduation-cap mr-1"></i> Formative vs Summative Assessment
                    </h5>
                    <p class="small text-emerald-700 mb-0">
                        Use this module for continuous classroom evaluations, weekly tests, orals, and assignments without overwriting formal term report cards.
                    </p>
                </div>
                <div>
                    <a href="{{ route('assessment.guide') }}" class="btn btn-emerald text-white btn-sm font-weight-bold" style="background-color: #059669;">
                        Read Assessment Guide <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

