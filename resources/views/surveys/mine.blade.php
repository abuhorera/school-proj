@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">My Surveys</h1>
                <p class="text-muted small mb-0">Surveys addressed to you — pending and completed.</p>
            </div>
            <a href="{{ route('survey.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> All surveys
            </a>
        </div>
    </div>

    <section class="content px-3">
        @include('surveys.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="card-title font-weight-bold m-0 text-dark">
                    <i class="fas fa-inbox text-purple-500 mr-2"></i> Inbox
                </h5>
                <span class="badge badge-light border text-muted px-2 py-1 font-weight-normal">{{ $surveys->count() }} surveys</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($surveys as $survey)
                        <div class="list-group-item p-3 d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div class="flex-grow-1" style="max-width: 800px;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <h6 class="font-weight-bold text-dark mb-0">{{ $survey->title }}</h6>
                                    <span class="badge badge-light border text-secondary px-2 py-0.5 ml-2" style="font-size: 11px;">
                                        {{ $survey->kind_label }}
                                    </span>
                                    @if($survey->is_anonymous)
                                        <span class="badge badge-info-light border text-info px-2 py-0.5" style="font-size: 10px;">
                                            <i class="fas fa-user-secret mr-1"></i> Anonymous
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-2">{{ $survey->description }}</p>
                                <div class="text-muted" style="font-size: 11px;">
                                    <span class="mr-3"><i class="fas fa-question-circle mr-1"></i> {{ $survey->questions->count() }} questions</span>
                                    @if($survey->closes_at)
                                        <span class="mr-3"><i class="fas fa-calendar mr-1"></i> Closes {{ $survey->closes_at->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                @if($survey->status === 'published')
                                    <a href="{{ route('survey.respond', $survey->access_token) }}" class="btn btn-primary btn-sm px-3 font-weight-bold">
                                        <i class="fas fa-pen-alt mr-1"></i> Respond
                                    </a>
                                @else
                                    <span class="badge badge-light border text-muted px-3 py-1.5" style="font-size: 12px;">
                                        <i class="fas fa-check-circle mr-1 text-success"></i> Done
                                    </span>
                                @endif
                                <a href="{{ route('survey.report', $survey->id) }}" class="btn btn-outline-secondary btn-sm" title="View Results">
                                    <i class="fas fa-chart-pie"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="mb-0">You have no active surveys in your inbox.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

