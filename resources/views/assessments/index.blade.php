@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Classroom Assessments</h3>
                <p class="text-muted small mb-0">Master catalog of frequent tests, subject quizzes, oral drills, and practical assignments.</p>
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

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-2.5">
                <h6 class="font-weight-bold text-dark mb-0"><i class="fas fa-filter text-emerald-600 mr-1"></i> Filter Assessments</h6>
            </div>
            <div class="card-body p-3">
                <form action="{{ route('assessment.assessments') }}" method="GET">
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
                        <div class="col-md-2 mb-2">
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
                        <div class="col-md-3 mb-2">
                            <label class="small font-weight-bold text-muted mb-1">Search</label>
                            <input type="text" name="q" class="form-control form-control-sm" placeholder="Title..." value="{{ request('q') }}">
                        </div>
                        <div class="col-md-1 mb-2 d-flex" style="gap: 5px;">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Apply</button>
                            <a href="{{ route('assessment.assessments') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Assessments Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold text-dark mb-0">Assessments Ledger</h5>
                <span class="badge badge-light border text-muted">{{ $assessments->total() }} records</span>
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
                                <th class="pr-4 text-right">Actions</th>
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
                                        <div class="btn-group">
                                            <a href="{{ route('assessment.assessments.show', $ass->id) }}" class="btn btn-outline-primary btn-xs px-2 py-1" title="Open Details & Sittings">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('assessment.assessments.edit', $ass->id) }}" class="btn btn-outline-secondary btn-xs px-2 py-1" title="Edit Assessment">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <form action="{{ route('assessment.assessments.destroy', $ass->id) }}" method="POST" onsubmit="return confirm('Delete assessment [{{ $ass->title }}]?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-xs px-2 py-1" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-clipboard-list fa-3x mb-2 text-slate-300"></i>
                                        <p class="mb-0">No assessments found matching criteria.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($assessments->hasPages())
                <div class="card-footer bg-white border-top py-2 d-flex justify-content-end">
                    {{ $assessments->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

