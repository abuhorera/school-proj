@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">New Classroom Assessment</h3>
                <p class="text-muted small mb-0">Set up a low-stakes test, quiz, oral evaluation, or practical task.</p>
            </div>
            <div>
                <a href="{{ route('assessment.assessments') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Assessments
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
                            <i class="fas fa-plus-circle text-emerald-600 mr-1.5"></i> Assessment Setup Parameters
                        </h5>
                    </div>
                    <form action="{{ route('assessment.assessments.store') }}" method="POST">
                        @csrf
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
                                <!-- Title -->
                                <div class="col-12 mb-3">
                                    <label class="font-weight-bold small text-dark">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" class="form-control" placeholder="e.g. English CA1 — Reading Comprehension" value="{{ old('title') }}" required>
                                </div>

                                <!-- Type -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control" required>
                                        <option value="Quiz" {{ old('type') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                        <option value="Test" {{ old('type') == 'Test' ? 'selected' : '' }}>Test</option>
                                        <option value="Oral" {{ old('type') == 'Oral' ? 'selected' : '' }}>Oral</option>
                                        <option value="Practical" {{ old('type') == 'Practical' ? 'selected' : '' }}>Practical</option>
                                        <option value="Assignment" {{ old('type') == 'Assignment' ? 'selected' : '' }}>Assignment</option>
                                        <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <!-- Conducted Via -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Conducted Via</label>
                                    <select name="conducted_via" class="form-control">
                                        <option value="Paper">Paper</option>
                                        <option value="Oral">Oral</option>
                                        <option value="Online Exam module">Online Exam module</option>
                                        <option value="Other">Other</option>
                                        <option value="— Not recorded —">— Not recorded —</option>
                                    </select>
                                </div>

                                <!-- Class -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Target Class <span class="text-danger">*</span></label>
                                    <select name="school_class_id" class="form-control" required>
                                        <option value="">Select class</option>
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}" {{ old('school_class_id') == $cls->id ? 'selected' : '' }}>
                                                {{ $cls->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Section -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Section</label>
                                    <select name="section_id" class="form-control">
                                        <option value="">Whole class (All Sections)</option>
                                        @foreach($classes->flatMap->sections->unique('id') as $sec)
                                            <option value="{{ $sec->id }}" {{ old('section_id') == $sec->id ? 'selected' : '' }}>
                                                Section {{ $sec->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Subject -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Subject <span class="text-danger">*</span></label>
                                    <select name="subject_id" class="form-control" required>
                                        <option value="">Select subject</option>
                                        @foreach($subjects as $sub)
                                            <option value="{{ $sub->id }}" {{ old('subject_id') == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Recurrence / Frequency -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Recurrence</label>
                                    <select name="frequency" class="form-control">
                                        <option value="One-off">One-off</option>
                                        <option value="Weekly">Weekly</option>
                                        <option value="Fortnightly">Fortnightly</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Custom">Custom</option>
                                    </select>
                                </div>

                                <!-- Total Marks -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Total Marks <span class="text-danger">*</span></label>
                                    <input type="number" name="total_marks" class="form-control" value="{{ old('total_marks', 30) }}" required min="1" step="0.5">
                                </div>

                                <!-- Passing Marks -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">Passing Marks <span class="text-danger">*</span></label>
                                    <input type="number" name="passing_marks" class="form-control" value="{{ old('passing_marks', 12) }}" required min="0" step="0.5">
                                </div>

                                <!-- Scheduled Date -->
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold small text-dark">First Sitting Date</label>
                                    <input type="date" name="scheduled_date" class="form-control" value="{{ old('scheduled_date', date('Y-m-d')) }}">
                                </div>

                                <!-- Instructions -->
                                <div class="col-12 mb-3">
                                    <label class="font-weight-bold small text-dark">Instructions / Rubric Notes</label>
                                    <textarea name="instructions" rows="3" class="form-control" placeholder="Specific evaluation guidelines, chapters covered, or student instructions...">{{ old('instructions') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top py-3 d-flex justify-content-end" style="gap: 10px;">
                            <a href="{{ route('assessment.assessments') }}" class="btn btn-secondary btn-sm px-3">Cancel</a>
                            <button type="submit" class="btn btn-emerald text-white btn-sm px-4 font-weight-bold" style="background-color: #059669;">
                                <i class="fas fa-save mr-1"></i> Save &amp; Continue to Marks
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

