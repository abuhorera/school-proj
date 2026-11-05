@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Edit Assessment &mdash; {{ $assessment->title }}</h3>
                <p class="text-muted small mb-0">Modify test rubric parameters, class, or maximum score.</p>
            </div>
            <div>
                <a href="{{ route('assessment.assessments.show', $assessment->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Assessment
                </a>
            </div>
        </div>

        @include('assessments.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-pen-nib text-emerald-600 mr-1.5"></i> Edit Assessment Configuration
                        </h5>
                    </div>
                    <form action="{{ route('assessment.assessments.update', $assessment->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0 pl-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="font-weight-bold small text-dark">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title', $assessment->title) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="Quiz" {{ $assessment->type == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                        <option value="Test" {{ $assessment->type == 'Test' ? 'selected' : '' }}>Test</option>
                                        <option value="Oral" {{ $assessment->type == 'Oral' ? 'selected' : '' }}>Oral</option>
                                        <option value="Practical" {{ $assessment->type == 'Practical' ? 'selected' : '' }}>Practical</option>
                                        <option value="Assignment" {{ $assessment->type == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                        <option value="Other" {{ $assessment->type == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Conducted Via</label>
                                    <select name="conducted_via" class="form-control">
                                        <option value="Paper" {{ $assessment->conducted_via == 'Paper' ? 'selected' : '' }}>Paper</option>
                                        <option value="Oral" {{ $assessment->conducted_via == 'Oral' ? 'selected' : '' }}>Oral</option>
                                        <option value="Online Exam module" {{ $assessment->conducted_via == 'Online Exam module' ? 'selected' : '' }}>Online Exam module</option>
                                        <option value="Other" {{ $assessment->conducted_via == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Target Class <span class="text-danger">*</span></label>
                                    <select name="school_class_id" class="form-control" required>
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}" {{ $assessment->class_id == $cls->id ? 'selected' : '' }}>
                                                {{ $cls->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Section</label>
                                    <select name="section_id" class="form-control">
                                        <option value="">Whole class (All Sections)</option>
                                        @foreach($classes->flatMap->sections->unique('id') as $sec)
                                            <option value="{{ $sec->id }}" {{ $assessment->section_id == $sec->id ? 'selected' : '' }}>
                                                Section {{ $sec->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Subject <span class="text-danger">*</span></label>
                                    <select name="subject_id" class="form-control" required>
                                        @foreach($subjects as $sub)
                                            <option value="{{ $sub->id }}" {{ $assessment->subject_id == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Recurrence</label>
                                    <select name="frequency" class="form-control">
                                        <option value="One-off" {{ $assessment->frequency == 'One-off' ? 'selected' : '' }}>One-off</option>
                                        <option value="Weekly" {{ $assessment->frequency == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="Fortnightly" {{ $assessment->frequency == 'Fortnightly' ? 'selected' : '' }}>Fortnightly</option>
                                        <option value="Monthly" {{ $assessment->frequency == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="Custom" {{ $assessment->frequency == 'Custom' ? 'selected' : '' }}>Custom</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Total Marks <span class="text-danger">*</span></label>
                                    <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks', (int)$assessment->total_marks) }}" required min="1">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Passing Marks <span class="text-danger">*</span></label>
                                    <input type="number" name="passing_marks" class="form-control" value="{{ old('passing_marks', (int)$assessment->passing_marks) }}" required min="0">
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="font-weight-bold small text-dark">Instructions</label>
                                    <textarea name="instructions" rows="3" class="form-control">{{ old('instructions', $assessment->instructions) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top py-3 d-flex justify-content-end" style="gap: 10px;">
                            <a href="{{ route('assessment.assessments.show', $assessment->id) }}" class="btn btn-secondary btn-sm px-3">Cancel</a>
                            <button type="submit" class="btn btn-emerald text-white btn-sm px-4 font-weight-bold" style="background-color: #059669;">
                                <i class="fas fa-save mr-1"></i> Update Assessment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

