@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <a href="{{ route('feedback.index') }}" class="text-muted small text-decoration-none mb-1 d-inline-block">
                    <i class="fas fa-arrow-left mr-1"></i> Triage board
                </a>
                <div class="d-flex align-items-center gap-2">
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">{{ $item->title }}</h1>
                    <span class="badge {{ $item->status_badge }} text-uppercase px-2 py-0.5 ml-2" style="font-size: 11px;">
                        {{ $item->status }}
                    </span>
                    <span class="badge {{ $item->priority_badge }} text-uppercase px-2 py-0.5" style="font-size: 11px;">
                        {{ $item->priority }}
                    </span>
                </div>
                <div class="text-muted small mt-1">
                    Flagged {{ $item->flagged_at ? $item->flagged_at->diffForHumans() : $item->created_at->diffForHumans() }} by {{ $item->flagged_by }}
                </div>
            </div>
            <form action="{{ route('feedback.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this feedback item?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-trash-alt mr-1"></i> Remove
                </button>
            </form>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('surveys.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: The Feedback -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="font-weight-bold m-0 text-dark">
                            <i class="fas fa-comment-alt mr-2 text-primary"></i> The feedback
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($item->survey)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="text-muted small font-weight-bold text-uppercase">From survey:</div>
                                <h6 class="font-weight-bold text-primary mb-1">{{ $item->survey->title }}</h6>
                                <div class="text-muted small">
                                    <i class="fas fa-user-circle mr-1"></i>
                                    {{ $item->response?->respondent_name ?: 'Verified Respondent' }} · 
                                    {{ $item->response?->submitted_at ? $item->response->submitted_at->format('d M Y, H:i') : 'Recently' }}
                                </div>
                            </div>
                        @endif

                        <h6 class="font-weight-bold text-dark mb-3">Submitted Answers:</h6>
                        @if($item->response && $item->response->answers->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($item->response->answers as $ans)
                                    <div class="p-3 mb-2 bg-light rounded border">
                                        <div class="font-weight-bold text-dark small mb-1">
                                            {{ $ans->question?->question_text ?: 'Question' }}
                                        </div>
                                        <div class="h6 font-weight-bold mb-0 text-primary">
                                            @if($ans->numeric_value !== null)
                                                {{ $ans->numeric_value }} <span class="text-muted small font-weight-normal">/ {{ $ans->question?->scale_max ?: 5 }}</span>
                                            @else
                                                {{ $ans->answer_text ?: '—' }}
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($item->survey)
                            <!-- Sample answers preview from survey's questions -->
                            <div class="space-y-3">
                                @foreach($item->survey->questions as $q)
                                    <div class="p-3 mb-2 bg-light rounded border">
                                        <div class="font-weight-bold text-dark small mb-1">
                                            {{ $q->question_text }}
                                        </div>
                                        <div class="h6 font-weight-bold mb-0 text-primary">
                                            @if($q->question_type === 'rating_scale')
                                                4 / 5
                                            @elseif($q->question_type === 'nps_score')
                                                9 / 10
                                            @elseif($q->question_type === 'yes_no')
                                                Yes
                                            @else
                                                Detailed feedback remark recorded.
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted small">Direct feedback ticket. No question records linked.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Ticket Management Form -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="font-weight-bold m-0 text-dark">
                            <i class="fas fa-tasks mr-2 text-info"></i> Manage Action Ticket
                        </h6>
                    </div>
                    <form action="{{ route('feedback.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body p-4">
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Action Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" value="{{ old('title', $item->title) }}" class="form-control form-control-sm" required>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Status</label>
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="New" {{ $item->status === 'New' ? 'selected' : '' }}>New</option>
                                        <option value="Reviewed" {{ $item->status === 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                                        <option value="In progress" {{ $item->status === 'In progress' ? 'selected' : '' }}>In progress</option>
                                        <option value="Actioned" {{ $item->status === 'Actioned' ? 'selected' : '' }}>Actioned</option>
                                        <option value="Dismissed" {{ $item->status === 'Dismissed' ? 'selected' : '' }}>Dismissed</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Priority</label>
                                    <select name="priority" class="form-control form-control-sm">
                                        <option value="Low" {{ $item->priority === 'Low' ? 'selected' : '' }}>Low</option>
                                        <option value="Normal" {{ $item->priority === 'Normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="High" {{ $item->priority === 'High' ? 'selected' : '' }}>High</option>
                                        <option value="Urgent" {{ $item->priority === 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Assign to</label>
                                <select name="assigned_to" class="form-control form-control-sm">
                                    <option value="">— Unassigned —</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" {{ $item->assigned_to == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->first_name }} {{ $staff->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Tags (comma-separated)</label>
                                <input type="text" name="tags" value="{{ old('tags', $item->tags) }}" class="form-control form-control-sm" placeholder="e.g. transport, bus-route-4, delay">
                            </div>

                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-dark">Internal Notes / Resolution</label>
                                <textarea name="internal_notes" class="form-control form-control-sm" rows="4" placeholder="Log resolution steps, phone calls with parent, or corrective actions taken...">{{ old('internal_notes', $item->internal_notes) }}</textarea>
                            </div>

                            <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top">
                                <a href="{{ route('feedback.index') }}" class="btn btn-secondary btn-sm px-3">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

