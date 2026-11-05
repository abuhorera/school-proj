@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Edit Survey: {{ $survey->title }}</h1>
                <p class="text-muted small mb-0">Modify survey details, recurrence, audience, and questions.</p>
            </div>
            <a href="{{ route('survey.show', $survey->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to survey
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('surveys.partials.subnav')

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('survey.update', $survey->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Left Column: Survey Parameters & Audience -->
                <div class="col-lg-5 mb-4">
                    <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px;">
                        <div class="card-header bg-white py-3 border-bottom">
                            <h6 class="font-weight-bold m-0 text-dark"><i class="fas fa-sliders-h mr-2 text-primary"></i> 1. Setup Parameters</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Survey Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $survey->title) }}" class="form-control form-control-sm" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Description</label>
                                <textarea name="description" class="form-control form-control-sm" rows="2">{{ old('description', $survey->description) }}</textarea>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Kind</label>
                                    <select name="kind" class="form-control form-control-sm">
                                        <option value="survey" {{ $survey->kind === 'survey' ? 'selected' : '' }}>Survey</option>
                                        <option value="poll" {{ $survey->kind === 'poll' ? 'selected' : '' }}>Poll</option>
                                        <option value="feedback_form" {{ $survey->kind === 'feedback_form' ? 'selected' : '' }}>Feedback form</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Recurrence</label>
                                    <select name="recurrence_freq" class="form-control form-control-sm">
                                        <option value="One-off" {{ $survey->recurrence_freq === 'One-off' ? 'selected' : '' }}>One-off</option>
                                        <option value="Weekly" {{ $survey->recurrence_freq === 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                        <option value="Monthly" {{ $survey->recurrence_freq === 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="Termly" {{ $survey->recurrence_freq === 'Termly' ? 'selected' : '' }}>Termly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Closes At</label>
                                <input type="datetime-local" name="closes_at" class="form-control form-control-sm" value="{{ $survey->closes_at ? $survey->closes_at->format('Y-m-d\TH:i') : '' }}">
                            </div>

                            <hr class="my-3">
                            <h6 class="font-weight-bold small text-uppercase text-muted mb-2">Privacy & Submission Rules</h6>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="is_anonymous" value="1" class="custom-control-input" id="is_anonymous" {{ $survey->is_anonymous ? 'checked' : '' }}>
                                <label class="custom-control-label small" for="is_anonymous">
                                    <strong>Anonymous responses</strong> (Answers not linked to respondent identity)
                                </label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="allow_resubmit" value="1" class="custom-control-input" id="allow_resubmit" {{ $survey->allow_resubmit ? 'checked' : '' }}>
                                <label class="custom-control-label small" for="allow_resubmit">Allow re-submission / edits</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="force_popup" value="1" class="custom-control-input" id="force_popup" {{ $survey->force_popup ? 'checked' : '' }}>
                                <label class="custom-control-label small" for="force_popup">Prompt on login modal</label>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" name="one_per_device" value="1" class="custom-control-input" id="one_per_device" {{ $survey->one_per_device ? 'checked' : '' }}>
                                <label class="custom-control-label small" for="one_per_device">Enforce one submission per device</label>
                            </div>

                            <hr class="my-3">
                            <h6 class="font-weight-bold small text-uppercase text-muted mb-2">Target Audience</h6>
                            <div class="form-group mb-3">
                                <select name="audience_type" class="form-control form-control-sm mb-2">
                                    <option value="all" {{ $survey->audience_type === 'all' ? 'selected' : '' }}>Whole School (All Roles)</option>
                                    <option value="roles" {{ $survey->audience_type === 'roles' ? 'selected' : '' }}>Specific Roles</option>
                                    <option value="classes" {{ $survey->audience_type === 'classes' ? 'selected' : '' }}>Specific Classes</option>
                                    <option value="public" {{ $survey->audience_type === 'public' ? 'selected' : '' }}>Public Link / Anyone</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Question Builder -->
                <div class="col-lg-7 mb-4">
                    <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h6 class="font-weight-bold m-0 text-dark"><i class="fas fa-list-ol mr-2 text-info"></i> 2. Questions ({{ $survey->questions->count() }})</h6>
                            <button type="button" class="btn btn-outline-primary btn-xs px-2.5 py-1" onclick="addQuestion()">
                                <i class="fas fa-plus mr-1"></i> Add question
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <div id="questions_container">
                                @foreach($survey->questions as $idx => $q)
                                    <div class="question-item card border p-3 mb-3 bg-light" id="q_card_{{ $idx }}">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge badge-dark q-number">Question {{ $idx + 1 }}</span>
                                            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" onclick="removeQuestion({{ $idx }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label class="small font-weight-bold">Question Text</label>
                                            <input type="text" name="questions[{{ $idx }}][text]" class="form-control form-control-sm" value="{{ $q->question_text }}" required>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6 mb-2">
                                                <label class="small font-weight-bold">Question Type</label>
                                                <select name="questions[{{ $idx }}][type]" class="form-control form-control-sm">
                                                    <option value="rating_scale" {{ $q->question_type === 'rating_scale' ? 'selected' : '' }}>Rating scale (1–5)</option>
                                                    <option value="nps_score" {{ $q->question_type === 'nps_score' ? 'selected' : '' }}>NPS Scale (0–10)</option>
                                                    <option value="yes_no" {{ $q->question_type === 'yes_no' ? 'selected' : '' }}>Yes / No</option>
                                                    <option value="short_text" {{ $q->question_type === 'short_text' ? 'selected' : '' }}>Short Text</option>
                                                    <option value="paragraph" {{ $q->question_type === 'paragraph' ? 'selected' : '' }}>Paragraph Feedback</option>
                                                    <option value="single_choice" {{ $q->question_type === 'single_choice' ? 'selected' : '' }}>Single Choice (Dropdown)</option>
                                                    <option value="multiple_choice" {{ $q->question_type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice (Checkboxes)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 col-6 mb-2">
                                                <label class="small font-weight-bold">Min Scale</label>
                                                <input type="number" name="questions[{{ $idx }}][min]" class="form-control form-control-sm" value="{{ $q->scale_min }}">
                                            </div>
                                            <div class="col-md-3 col-6 mb-2">
                                                <label class="small font-weight-bold">Max Scale</label>
                                                <input type="number" name="questions[{{ $idx }}][max]" class="form-control form-control-sm" value="{{ $q->scale_max }}">
                                            </div>
                                        </div>
                                        <div class="custom-control custom-checkbox mt-1">
                                            <input type="checkbox" name="questions[{{ $idx }}][is_required]" value="1" class="custom-control-input" id="req_{{ $idx }}" {{ $q->is_required ? 'checked' : '' }}>
                                            <label class="custom-control-label small" for="req_{{ $idx }}">Required question</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-outline-secondary btn-sm btn-block py-2 mb-3" onclick="addQuestion()">
                                <i class="fas fa-plus mr-1"></i> Add another question
                            </button>

                            <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ route('survey.show', $survey->id) }}" class="btn btn-secondary btn-sm px-3">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</div>

<script>
let qIndex = {{ $survey->questions->count() }};
function addQuestion() {
    const container = document.getElementById('questions_container');
    const html = `
    <div class="question-item card border p-3 mb-3 bg-light" id="q_card_${qIndex}">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="badge badge-dark q-number">Question ${qIndex + 1}</span>
            <button type="button" class="btn btn-outline-danger btn-xs py-0 px-2" onclick="removeQuestion(${qIndex})">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="form-group mb-2">
            <label class="small font-weight-bold">Question Text</label>
            <input type="text" name="questions[${qIndex}][text]" class="form-control form-control-sm" placeholder="Enter question..." required>
        </div>
        <div class="row g-2">
            <div class="col-md-6 mb-2">
                <label class="small font-weight-bold">Question Type</label>
                <select name="questions[${qIndex}][type]" class="form-control form-control-sm">
                    <option value="rating_scale">Rating scale (1–5)</option>
                    <option value="nps_score">NPS Scale (0–10)</option>
                    <option value="yes_no">Yes / No</option>
                    <option value="short_text">Short Text</option>
                    <option value="paragraph">Paragraph Feedback</option>
                    <option value="single_choice">Single Choice (Dropdown)</option>
                    <option value="multiple_choice">Multiple Choice (Checkboxes)</option>
                </select>
            </div>
            <div class="col-md-3 col-6 mb-2">
                <label class="small font-weight-bold">Min Scale</label>
                <input type="number" name="questions[${qIndex}][min]" class="form-control form-control-sm" value="1">
            </div>
            <div class="col-md-3 col-6 mb-2">
                <label class="small font-weight-bold">Max Scale</label>
                <input type="number" name="questions[${qIndex}][max]" class="form-control form-control-sm" value="5">
            </div>
        </div>
        <div class="custom-control custom-checkbox mt-1">
            <input type="checkbox" name="questions[${qIndex}][is_required]" value="1" class="custom-control-input" id="req_${qIndex}" checked>
            <label class="custom-control-label small" for="req_${qIndex}">Required question</label>
        </div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
    qIndex++;
}

function removeQuestion(idx) {
    const el = document.getElementById(`q_card_${idx}`);
    if (el) el.remove();
}
</script>
@endsection

