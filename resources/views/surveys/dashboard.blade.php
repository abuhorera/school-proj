@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Surveys & Feedback</h1>
                <p class="text-muted small mb-0">Your feedback command centre — surveys, responses and follow-ups at a glance.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('survey.guide') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-book-open mr-1"></i> Guide
                </a>
                <a href="{{ route('survey.templates') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-layer-group mr-1"></i> Templates
                </a>
                <a href="{{ route('survey.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-plus mr-1"></i> New survey
                </a>
            </div>
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

        <!-- 6 KPI Stats Cards -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-2 mb-4">
            <div class="col mb-2">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px; background: #f8fafc;">
                    <div class="card-body p-3">
                        <div class="text-muted small font-weight-bold text-uppercase" style="font-size: 11px;">Surveys</div>
                        <div class="h3 font-weight-bold text-dark my-1">{{ $stats['total_surveys'] }}</div>
                        <div class="small text-muted"><span class="text-success font-weight-bold">Total</span> blueprints</div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px; background: #f0fdf4;">
                    <div class="card-body p-3">
                        <div class="text-success small font-weight-bold text-uppercase" style="font-size: 11px;">Published</div>
                        <div class="h3 font-weight-bold text-success my-1">{{ $stats['published_count'] }}</div>
                        <div class="small text-muted">Accepting answers</div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px; background: #fefce8;">
                    <div class="card-body p-3">
                        <div class="text-warning small font-weight-bold text-uppercase" style="font-size: 11px;">Drafts</div>
                        <div class="h3 font-weight-bold text-warning my-1">{{ $stats['draft_count'] }}</div>
                        <div class="small text-muted">In preparation</div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px; background: #f5f3ff;">
                    <div class="card-body p-3">
                        <div class="text-purple small font-weight-bold text-uppercase" style="font-size: 11px; color: #7c3aed;">Responses</div>
                        <div class="h3 font-weight-bold text-dark my-1" style="color: #6d28d9 !important;">{{ $stats['total_responses'] }}</div>
                        <div class="small text-muted">Total submissions</div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px; background: #eff6ff;">
                    <div class="card-body p-3">
                        <div class="text-primary small font-weight-bold text-uppercase" style="font-size: 11px;">Avg Response Rate</div>
                        <div class="h3 font-weight-bold text-primary my-1">{{ $stats['avg_response_rate'] }}%</div>
                        <div class="small text-muted">Audience engagement</div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 10px; background: #fff1f2;">
                    <div class="card-body p-3">
                        <div class="text-danger small font-weight-bold text-uppercase" style="font-size: 11px;">Open Feedback</div>
                        <div class="h3 font-weight-bold text-danger my-1">{{ $stats['open_feedback'] }}</div>
                        <div class="small text-muted">Action tickets</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Surveys Table Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">Recent Surveys</h5>
                    <p class="text-muted small mb-0">Active and recently modified feedback forms</p>
                </div>
                <a href="{{ route('survey.index') }}" class="btn btn-outline-secondary btn-xs px-2.5 py-1">View all</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3">Title</th>
                            <th class="py-2.5">Kind</th>
                            <th class="py-2.5 text-center">Invited / Sent</th>
                            <th class="py-2.5 text-center">Responses</th>
                            <th class="py-2.5">Status</th>
                            <th class="py-2.5 text-right pr-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($surveys as $survey)
                            <tr>
                                <td class="py-3 pl-3">
                                    <a href="{{ route('survey.show', $survey->id) }}" class="font-weight-bold text-dark text-decoration-none hover-text-primary">
                                        {{ $survey->title }}
                                    </a>
                                    @if($survey->is_anonymous)
                                        <span class="badge badge-light border text-muted ml-1" style="font-size: 10px;">Anonymous</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-light border px-2 py-1 text-secondary" style="font-size: 11px;">
                                        {{ $survey->kind_label }}
                                    </span>
                                </td>
                                <td class="text-center font-weight-bold text-muted">{{ $survey->invited_count }}</td>
                                <td class="text-center">
                                    <span class="font-weight-bold text-dark">{{ $survey->responses_count }}</span>
                                    @if($survey->invited_count > 0)
                                        <span class="text-muted small">({{ $survey->response_rate }}%)</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $survey->status_badge }} px-2 py-1 text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;">
                                        {{ $survey->status }}
                                    </span>
                                </td>
                                <td class="text-right pr-3">
                                    <div class="btn-group btn-group-xs">
                                        <a href="{{ route('survey.show', $survey->id) }}" class="btn btn-outline-secondary btn-xs px-2 py-1" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('survey.report', $survey->id) }}" class="btn btn-outline-info btn-xs px-2 py-1" title="Analytics Report">
                                            <i class="fas fa-chart-bar"></i>
                                        </a>
                                        @if($survey->status === 'published')
                                            <a href="{{ route('survey.respond', $survey->access_token) }}" class="btn btn-outline-success btn-xs px-2 py-1" title="Open Form" target="_blank">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No surveys created yet. Use a template or start blank.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Start Templates Promo & Guide Banner -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="card-body p-4 text-white">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge badge-warning text-dark font-weight-bold px-2 py-1 mb-2">QUICK LAUNCH</span>
                        <h4 class="font-weight-bold mb-2">Need to gather parent or teacher sentiment quickly?</h4>
                        <p class="text-slate-300 small mb-0" style="color: #cbd5e1;">
                            Explore professionally designed templates for <strong>Parent Satisfaction</strong>, <strong>Teacher Evaluation</strong>, <strong>Student Wellbeing Check</strong>, and <strong>NPS Loyalty Surveys</strong>. Deploy in under 60 seconds with automated triage alerts.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                        <a href="{{ route('survey.templates') }}" class="btn btn-light text-dark font-weight-bold px-3 py-2 mr-2 shadow-sm">
                            <i class="fas fa-layer-group mr-1.5 text-primary"></i> Browse Templates
                        </a>
                        <a href="{{ route('feedback.index') }}" class="btn btn-outline-light px-3 py-2">
                            <i class="fas fa-tasks mr-1"></i> Triage Board
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

