@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="container py-4" style="max-width: 760px;">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
            <div class="card-header bg-white p-4 border-bottom">
                <span class="badge badge-light border text-muted px-2 py-1 mb-2">{{ $survey->kind_label }}</span>
                <h2 class="font-weight-bold text-dark mb-2">{{ $survey->title }}</h2>
                @if($survey->description)
                    <p class="text-muted mb-3" style="font-size: 14px; line-height: 1.5;">{{ $survey->description }}</p>
                @endif

                @if($survey->is_anonymous)
                    <div class="alert alert-info border-0 p-2.5 mb-0 small text-info" style="background-color: #f0fdfa;">
                        <i class="fas fa-user-secret mr-1.5"></i> <strong>This survey is anonymous</strong> — your answers are not linked to your name or login identity.
                    </div>
                @endif
            </div>

            <form action="{{ route('survey.respond.submit', $survey->access_token) }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    @if(!$survey->is_anonymous)
                        <div class="form-group mb-4 p-3 bg-light rounded border">
                            <label class="small font-weight-bold text-dark">Your Name / Identifier (Optional)</label>
                            <input type="text" name="respondent_name" class="form-control form-control-sm" placeholder="e.g. Rahul Sharma (Parent of Class 4A)">
                        </div>
                    @endif

                    @foreach($survey->questions as $idx => $q)
                        <div class="form-group mb-4 pb-3 border-bottom">
                            <label class="font-weight-bold text-dark d-block mb-2" style="font-size: 14px;">
                                <span class="badge badge-secondary mr-1">Q{{ $q->order_num }}</span>
                                {{ $q->question_text }}
                                @if($q->is_required)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>

                            @if($q->question_type === 'rating_scale')
                                <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                    @for($v = $q->scale_min; $v <= $q->scale_max; $v++)
                                        <label class="btn btn-outline-secondary px-3 py-2 flex-grow-1" style="font-size: 14px; border-radius: 6px; margin: 2px;">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $v }}" {{ $q->is_required ? 'required' : '' }}>
                                            <div class="font-weight-bold">{{ $v }}</div>
                                            <div style="font-size: 10px;">
                                                @if($v == $q->scale_min) Lowest @elseif($v == $q->scale_max) Highest @endif
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            @elseif($q->question_type === 'nps_score')
                                <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                    @for($v = 0; $v <= 10; $v++)
                                        <label class="btn btn-outline-secondary px-2 py-1.5 flex-grow-1" style="font-size: 13px; border-radius: 4px; margin: 1px;">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $v }}" {{ $q->is_required ? 'required' : '' }}>
                                            {{ $v }}
                                        </label>
                                    @endfor
                                </div>
                                <div class="d-flex justify-content-between text-muted small mt-1 px-1">
                                    <span>0 - Not likely at all</span>
                                    <span>10 - Extremely likely</span>
                                </div>
                            @elseif($q->question_type === 'yes_no')
                                <div class="btn-group btn-group-toggle d-flex" data-toggle="buttons" style="max-width: 250px;">
                                    <label class="btn btn-outline-success py-2 flex-grow-1">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="Yes" {{ $q->is_required ? 'required' : '' }}>
                                        <i class="fas fa-check mr-1"></i> Yes
                                    </label>
                                    <label class="btn btn-outline-danger py-2 flex-grow-1">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="No">
                                        <i class="fas fa-times mr-1"></i> No
                                    </label>
                                </div>
                            @elseif($q->question_type === 'paragraph')
                                <textarea name="answers[{{ $q->id }}]" class="form-control" rows="3" placeholder="Type your answer here..." {{ $q->is_required ? 'required' : '' }}></textarea>
                            @else
                                <input type="text" name="answers[{{ $q->id }}]" class="form-control" placeholder="Your response..." {{ $q->is_required ? 'required' : '' }}>
                            @endif
                        </div>
                    @endforeach

                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <span class="text-muted small">Submitted answers cannot be modified later.</span>
                        <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2" style="border-radius: 8px;">
                            <i class="fas fa-paper-plane mr-1.5"></i> Submit Response
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

