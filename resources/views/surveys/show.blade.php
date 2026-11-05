@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <a href="{{ route('survey.index') }}" class="text-muted small text-decoration-none mb-1 d-inline-block">
                    <i class="fas fa-arrow-left mr-1"></i> All surveys
                </a>
                <div class="d-flex align-items-center gap-2">
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">{{ $survey->title }}</h1>
                    <span class="badge {{ $survey->status_badge }} text-uppercase px-2 py-0.5 ml-2" style="font-size: 11px;">
                        {{ $survey->status }}
                    </span>
                    <span class="badge badge-light border text-muted px-2 py-0.5 ml-1" style="font-size: 11px;">
                        {{ $survey->kind_label }}
                    </span>
                    @if($survey->is_anonymous)
                        <span class="badge badge-info-light border text-info px-2 py-0.5 ml-1" style="font-size: 10px;">
                            <i class="fas fa-user-secret mr-1"></i> Anonymous
                        </span>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('survey.report', $survey->id) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-chart-pie mr-1"></i> Report
                </a>
                <a href="{{ route('survey.report.export', $survey->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-csv mr-1"></i> Responses CSV
                </a>
                <a href="{{ route('survey.edit', $survey->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-pen mr-1"></i> Edit
                </a>
                @if($survey->status === 'draft')
                    <form action="{{ route('survey.publish', $survey->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm font-weight-bold">
                            <i class="fas fa-upload mr-1"></i> Publish
                        </button>
                    </form>
                @elseif($survey->status === 'published')
                    <form action="{{ route('survey.close', $survey->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm text-dark font-weight-bold">
                            <i class="fas fa-lock mr-1"></i> Close
                        </button>
                    </form>
                @endif
            </div>
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

        <!-- Quick Counters Row -->
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Invited / Sent</div>
                    <div class="h2 font-weight-bold text-dark my-1">{{ $survey->invited_count }}</div>
                    <div class="small text-muted">Total target recipients</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Responses Logged</div>
                    <div class="h2 font-weight-bold text-primary my-1">{{ $survey->responses_count }}</div>
                    <div class="small text-muted">Submissions collected</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Response Rate</div>
                    <div class="h2 font-weight-bold text-success my-1">{{ $survey->response_rate }}%</div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: {{ min(100, $survey->response_rate) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        @if($survey->description)
            <div class="alert alert-light border mb-4 text-muted small p-3">
                <i class="fas fa-info-circle mr-1 text-primary"></i> {{ $survey->description }}
            </div>
        @endif

        <!-- Shareable Link Banner -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px; background: #f8fafc;">
            <div class="card-body p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-link fa-lg text-primary mr-2"></i>
                    <div>
                        <div class="font-weight-bold text-dark small">Public / Respondent Response URL</div>
                        <code class="small text-secondary">{{ url('/school/survey/r/' . $survey->access_token) }}</code>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('survey.respond', $survey->access_token) }}" class="btn btn-outline-primary btn-sm" target="_blank">
                        <i class="fas fa-external-link-alt mr-1"></i> Preview / Fill Response
                    </a>
                </div>
            </div>
        </div>

        <!-- Questions Roster Card -->
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">Questions</h5>
                    <span class="text-muted small ml-2">{{ $survey->questions->count() }} questions configured</span>
                </div>
                <a href="{{ route('survey.edit', $survey->id) }}" class="btn btn-outline-secondary btn-xs px-2.5 py-1">
                    <i class="fas fa-pen mr-1"></i> Edit Questions
                </a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($survey->questions as $q)
                        <div class="list-group-item p-3 d-flex align-items-start justify-content-between">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge badge-dark mr-2">Q{{ $q->order_num }}</span>
                                    <h6 class="font-weight-bold text-dark mb-0">{{ $q->question_text }}</h6>
                                </div>
                                <div class="text-muted small mt-1">
                                    <span class="badge badge-light border text-secondary px-2 py-0.5 mr-2">{{ $q->type_label }}</span>
                                    @if(in_array($q->question_type, ['rating_scale', 'nps_score']))
                                        <span class="mr-2">Scale: {{ $q->scale_min }}–{{ $q->scale_max }}</span>
                                    @endif
                                    @if($q->is_required)
                                        <span class="badge badge-danger-light text-danger border px-1.5 py-0.5">Required</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">No questions configured. Click Edit to add questions.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

