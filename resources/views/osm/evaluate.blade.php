@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="osm-title-area">
                <h3 class="font-weight-bold text-dark mb-0">Digital Evaluation</h3>
                <p class="text-muted small mb-0">On-screen marking of scanned answer sheets — upload, evaluate question-by-question, and report.</p>
            </div>
            <div>
                <a class="btn btn-osm-primary" href="{{ route('osm.sessions.create') }}">
                    <i class="fas fa-plus mr-1"></i> <span class="d-none d-md-inline">New Session</span>
                </a>
            </div>
        </div>

        @include('osm.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('warning') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            @forelse($sessions as $sess)
                @php
                    $total = $sess->total_sheets ?: 1;
                    $evalCount = $sess->papers()->whereIn('status', ['Evaluated', 'Moderated'])->count();
                    $pendingCount = $sess->papers()->where('status', 'Pending')->count();
                    $pct = min(100, round(($evalCount / $total) * 100));
                    $firstPending = $sess->papers()->where('status', 'Pending')->first();
                    $firstAnyPaper = $sess->papers()->first();
                @endphp
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card shadow-sm h-100 border-0" style="border-radius: 8px; border-left: 4px solid {{ $pct == 100 ? '#10b981' : '#0284c7' }} !important;">
                        <div class="card-body d-flex flex-column justify-content-between p-3">
                            <div>
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                                        <a href="{{ route('osm.sessions.show', $sess->id) }}" class="text-dark">{{ $sess->title }}</a>
                                    </h5>
                                    <span class="badge {{ $pct == 100 ? 'badge-success' : 'badge-light border' }}" style="font-size: 0.75rem;">
                                        {{ $pct }}%
                                    </span>
                                </div>
                                <div class="text-muted small mb-2">
                                    <strong>{{ $sess->subject?->name ?? 'English Core' }}</strong> &middot;
                                    {{ $sess->schoolClass?->name ?? 'Class XI' }} / {{ $sess->section?->name ?? 'A' }}
                                </div>
                                <div class="small font-weight-bold text-secondary mb-1">
                                    {{ $evalCount }}/{{ $sess->total_sheets }} done
                                </div>
                                <div class="progress mb-3" style="height: 6px;">
                                    <div class="progress-bar {{ $pct == 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>

                            <div>
                                @if($pendingCount > 0 && $firstPending)
                                    <a href="{{ route('osm.evaluate.canvas', $firstPending->id) }}" class="btn btn-osm-primary btn-block font-weight-bold">
                                        <i class="fas fa-pen-nib mr-1"></i> Evaluate ({{ $pendingCount }} pending)
                                    </a>
                                @elseif($firstAnyPaper)
                                    <a href="{{ route('osm.evaluate.canvas', $firstAnyPaper->id) }}" class="btn btn-outline-success btn-block font-weight-bold">
                                        <i class="fas fa-check-double mr-1"></i> Bundle complete (Review)
                                    </a>
                                @else
                                    <a href="{{ route('osm.sessions.show', $sess->id) }}" class="btn btn-outline-secondary btn-block">
                                        <i class="fas fa-upload mr-1"></i> Upload Answer Sheets
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-2"></i>
                    <h5 class="text-muted">No evaluation sessions currently queued.</h5>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

