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

        <!-- Session Selector Card -->
        <div class="card shadow-sm mb-3">
            <div class="card-body p-3">
                <form method="GET" action="{{ route('osm.moderation') }}" class="form-row align-items-end">
                    <div class="col-md-8 mb-2">
                        <label class="small text-muted mb-1 font-weight-bold">Session (moderation enabled)</label>
                        <select name="session_id" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Select a session --</option>
                            @foreach($sessions as $s)
                                <option value="{{ $s->id }}" {{ $selectedSession && $selectedSession->id == $s->id ? 'selected' : '' }}>
                                    {{ $s->title }} ({{ $s->subject?->name ?? 'Subject' }}) &mdash; {{ $s->papers()->where('status', 'Pending')->count() }} pending
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2 text-right">
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-filter mr-1"></i> Load Papers
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($selectedSession)
            <!-- Moderation KPI Metrics -->
            <div class="row mb-3">
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Total Papers</div>
                            <div class="h4 font-weight-bold text-dark mb-0">{{ $papers->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Evaluated</div>
                            <div class="h4 font-weight-bold text-success mb-0">
                                {{ $papers->whereIn('status', ['Evaluated', 'Moderated'])->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Moderated &amp; Approved</div>
                            <div class="h4 font-weight-bold text-primary mb-0">
                                {{ $papers->where('is_moderated', true)->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 mb-2">
                    <div class="card shadow-sm h-100">
                        <div class="card-body p-3 text-center">
                            <div class="text-muted small">Moderation Policy</div>
                            <div class="h5 font-weight-bold text-secondary mb-0">
                                {{ strtoupper(str_replace('_', ' ', $selectedSession->moderation_level)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Moderation Papers Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="font-weight-bold text-dark mb-0">
                        <i class="fas fa-balance-scale text-primary mr-1"></i> Moderation Queue: {{ $selectedSession->title }}
                    </h6>
                    <span class="badge badge-light border">Max {{ (int)$selectedSession->total_marks }} marks</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Student</th>
                                <th>Evaluator</th>
                                <th>Score</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($papers as $paper)
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">
                                            {{ $selectedSession->anonymous_mode ? $paper->anonymous_code : ($paper->student?->first_name . ' ' . $paper->student?->last_name) }}
                                        </div>
                                        <small class="text-muted">{{ $paper->original_file_name ?? 'paper.pdf' }}</small>
                                    </td>
                                    <td>
                                        @if($paper->evaluator)
                                            <span class="small font-weight-bold"><i class="fas fa-user-check text-muted mr-1"></i>{{ $paper->evaluator->first_name }} {{ $paper->evaluator->last_name }}</span>
                                        @else
                                            <span class="text-muted small">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="font-weight-bold {{ $paper->status == 'Evaluated' || $paper->status == 'Moderated' ? 'text-success' : 'text-muted' }}">
                                            {{ $paper->total_score }} / {{ (int)$selectedSession->total_marks }}
                                        </div>
                                        @if($paper->is_moderated)
                                            <small class="badge badge-info font-weight-normal">Moderated: {{ $paper->moderated_score }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($paper->status == 'Moderated')
                                            <span class="badge badge-info px-2 py-1"><i class="fas fa-balance-scale mr-1"></i> Moderated</span>
                                        @elseif($paper->status == 'Evaluated')
                                            <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Evaluated</span>
                                        @elseif($paper->status == 'Rejected')
                                            <span class="badge badge-danger px-2 py-1"><i class="fas fa-ban mr-1"></i> Rejected</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1"><i class="fas fa-hourglass-half mr-1"></i> Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('osm.evaluate.canvas', $paper->id) }}" class="btn btn-outline-primary" title="Inspect Marked Script">
                                                <i class="fas fa-eye mr-1"></i> View Script
                                            </a>
                                            <button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#moderateModal{{ $paper->id }}" title="Moderate / Override Marks">
                                                <i class="fas fa-balance-scale mr-1"></i> Moderate
                                            </button>
                                        </div>

                                        <!-- Moderation Modal -->
                                        <div class="modal fade text-left" id="moderateModal{{ $paper->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-dark text-white">
                                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-balance-scale mr-1"></i> Head Examiner Moderation</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <form action="{{ route('osm.moderation.save', $paper->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="p-2 mb-3 bg-light rounded border text-muted small">
                                                                Paper: <strong>{{ $paper->anonymous_code }}</strong> &middot; Original Evaluator: <strong>{{ $paper->evaluator?->first_name ?? 'Faculty' }}</strong><br>
                                                                Current Evaluator Marks: <strong class="text-dark">{{ $paper->total_score }} / {{ (int)$selectedSession->total_marks }}</strong>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Final Moderated Score (Max {{ (int)$selectedSession->total_marks }}) *</label>
                                                                <input type="number" step="0.5" name="moderated_score" class="form-control" required value="{{ $paper->is_moderated ? $paper->moderated_score : $paper->total_score }}" max="{{ $selectedSession->total_marks }}">
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="font-weight-bold">Moderator Rationale / Remarks</label>
                                                                <textarea name="moderator_notes" rows="3" class="form-control form-control-sm" placeholder="Reason for mark revision or endorsement notes...">{{ $paper->moderator_notes }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-osm-primary font-weight-bold">
                                                                <i class="fas fa-check-circle mr-1"></i> Save Moderation
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No papers found in this session.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="card shadow-sm p-5 text-center text-muted">
                <i class="fas fa-balance-scale fa-3x mb-3 text-muted"></i>
                <h5 class="text-muted">Select a session above to moderate its evaluated papers.</h5>
            </div>
        @endif
    </div>
</section>
@endsection

