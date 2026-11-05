@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <a href="{{ route('survey.show', $survey->id) }}" class="text-muted small text-decoration-none mb-1 d-inline-block">
                    <i class="fas fa-arrow-left mr-1"></i> Back to survey
                </a>
                <div class="d-flex align-items-center gap-2">
                    <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">{{ $survey->title }} — Report</h1>
                    @if($survey->is_report_published)
                        <span class="badge badge-success px-2 py-0.5 ml-2" style="font-size: 11px;">Published</span>
                    @else
                        <span class="badge badge-light border text-muted px-2 py-0.5 ml-2" style="font-size: 11px;">Internal Only</span>
                    @endif
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('survey.report.export', $survey->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-csv mr-1 text-success"></i> Export CSV
                </a>
                @if(!$survey->is_report_published)
                    <form action="{{ route('survey.report.publish', $survey->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm font-weight-bold">
                            <i class="fas fa-bullhorn mr-1"></i> Publish report
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

        <!-- 3 KPI Overview Cards -->
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Invited Recipients</div>
                    <div class="h2 font-weight-bold text-dark my-1">{{ $survey->invited_count }}</div>
                    <div class="small text-muted">Target cohort size</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Total Responses</div>
                    <div class="h2 font-weight-bold text-primary my-1">{{ $survey->responses_count }}</div>
                    <div class="small text-muted">Submissions analyzed below</div>
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

        <!-- Question-by-Question Analytics -->
        @foreach($survey->questions as $q)
            @php $stat = $analytics[$q->id] ?? null; @endphp
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="font-weight-bold m-0 text-dark">
                            Q{{ $q->order_num }}. {{ $q->question_text }}
                        </h6>
                        <span class="text-muted small">
                            {{ $q->type_label }} · {{ $stat['total_answered'] ?? 0 }} answered
                        </span>
                    </div>
                    @if(in_array($q->question_type, ['rating_scale', 'nps_score']) && isset($stat['avg']))
                        <div class="text-right">
                            <span class="h4 font-weight-bold text-dark">{{ $stat['avg'] }}</span>
                            <span class="small text-muted">avg (scale {{ $stat['scale_range'] }})</span>
                        </div>
                    @endif
                </div>
                <div class="card-body p-4">
                    @if(in_array($q->question_type, ['rating_scale', 'nps_score']) && isset($stat['counts']))
                        <!-- Scale breakdown bars -->
                        <div class="row">
                            <div class="col-lg-8">
                                @foreach($stat['counts'] as $val => $data)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="font-weight-bold text-muted mr-3" style="width: 25px; text-align: right;">{{ $val }}</div>
                                        <div class="progress flex-grow-1 mr-3" style="height: 12px; border-radius: 6px; background-color: #f1f5f9;">
                                            <div class="progress-bar bg-primary" style="width: {{ $data['percent'] }}%;"></div>
                                        </div>
                                        <div class="small font-weight-bold text-dark" style="width: 90px;">
                                            {{ $data['count'] }} <span class="text-muted font-weight-normal">· {{ $data['percent'] }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-lg-4 d-flex align-items-center justify-content-center border-left">
                                <div class="text-center p-3">
                                    <div class="display-4 font-weight-bold text-primary">{{ $stat['avg'] }}</div>
                                    <div class="text-muted small">Overall Score</div>
                                    <div class="text-warning mt-1">
                                        @for($s = 1; $s <= 5; $s++)
                                            <i class="fas fa-star {{ $s <= round($stat['avg']) ? 'text-warning' : 'text-slate-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($q->question_type === 'yes_no' && isset($stat['yes_count']))
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded text-center border">
                                    <div class="h3 font-weight-bold text-success mb-1">{{ $stat['yes_percent'] }}%</div>
                                    <div class="text-muted small font-weight-bold">YES ({{ $stat['yes_count'] }} respondents)</div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: {{ $stat['yes_percent'] }}%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="p-3 bg-light rounded text-center border">
                                    <div class="h3 font-weight-bold text-danger mb-1">{{ $stat['no_percent'] }}%</div>
                                    <div class="text-muted small font-weight-bold">NO ({{ $stat['no_count'] }} respondents)</div>
                                    <div class="progress mt-2" style="height: 8px;">
                                        <div class="progress-bar bg-danger" style="width: {{ $stat['no_percent'] }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Text / Open responses -->
                        <h6 class="font-weight-bold text-muted small text-uppercase mb-3">Recent Open Submissions</h6>
                        @if(isset($stat['sample_texts']) && $stat['sample_texts']->isNotEmpty())
                            <div class="list-group list-group-flush border rounded">
                                @foreach($stat['sample_texts'] as $txt)
                                    <div class="list-group-item p-2.5 small text-dark d-flex align-items-start">
                                        <i class="fas fa-quote-left text-muted mr-2 mt-1"></i>
                                        <div>{{ $txt }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted small italic">No text feedback remarks recorded yet.</div>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </section>
</div>
@endsection

