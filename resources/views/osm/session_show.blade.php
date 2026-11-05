@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2">
            <div class="osm-title-area">
                <h3 class="font-weight-bold text-dark mb-0">Digital Evaluation</h3>
                <p class="text-muted small mb-0">Session workbench: question paper, upload scripts, assign evaluators, and manage papers.</p>
            </div>
            <div>
                <a class="btn btn-default mr-1" href="{{ route('osm.sessions') }}">
                    <i class="fas fa-list mr-1"></i> All Sessions
                </a>
                <a class="btn btn-osm-primary" href="{{ route('osm.sessions.create') }}">
                    <i class="fas fa-plus mr-1"></i> New Session
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

        <!-- Card 1: Session Overview Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <h4 class="font-weight-bold text-dark mb-0 mr-2">{{ $session->title }}</h4>
                            @if($session->status == 'Completed')
                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Completed</span>
                            @elseif($session->status == 'Evaluating')
                                <span class="badge badge-warning px-2 py-1"><i class="fas fa-spinner fa-spin mr-1"></i> Evaluating</span>
                            @elseif($session->status == 'Moderation')
                                <span class="badge badge-primary px-2 py-1"><i class="fas fa-balance-scale mr-1"></i> Moderation</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">{{ $session->status }}</span>
                            @endif
                        </div>
                        <div class="text-muted small">
                            <strong>{{ $session->subject?->name ?? 'English Core' }}</strong> &middot;
                            {{ $session->schoolClass?->name ?? 'Class XI' }} / {{ $session->section?->name ?? 'A' }} &middot;
                            Max <strong>{{ (int)$session->total_marks }} marks</strong> &middot;
                            Moderation: <strong>{{ strtoupper(str_replace('_', ' ', $session->moderation_level)) }}</strong>
                        </div>
                    </div>

                    <div class="mt-2 mt-md-0 d-flex gap-2">
                        <a href="{{ route('osm.sessions.edit', $session->id) }}" class="btn btn-sm btn-outline-secondary mr-2">
                            <i class="fas fa-pencil-alt mr-1"></i> Edit
                        </a>
                        <a href="{{ route('osm.reports.analytics', $session->id) }}" class="btn btn-sm btn-outline-info mr-2">
                            <i class="fas fa-chart-pie mr-1"></i> Analytics
                        </a>
                        <a href="{{ route('osm.reports.evaluator', $session->id) }}" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-user-check mr-1"></i> Evaluators
                        </a>
                    </div>
                </div>

                <hr class="my-3">

                <!-- 4 Quick Stats -->
                <div class="row text-center">
                    <div class="col-3 border-right">
                        <div class="text-muted small">Total Sheets</div>
                        <div class="h5 font-weight-bold text-dark mb-0">{{ $session->total_sheets }}</div>
                    </div>
                    <div class="col-3 border-right">
                        <div class="text-muted small">Pending</div>
                        <div class="h5 font-weight-bold text-amber mb-0">{{ $session->papers()->where('status', 'Pending')->count() }}</div>
                    </div>
                    <div class="col-3 border-right">
                        <div class="text-muted small">Evaluated</div>
                        <div class="h5 font-weight-bold text-success mb-0">{{ $session->papers()->whereIn('status', ['Evaluated', 'Moderated'])->count() }}</div>
                    </div>
                    <div class="col-3">
                        <div class="text-muted small">Configured Questions</div>
                        <div class="h5 font-weight-bold text-primary mb-0">{{ $session->questions->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Question Paper & Upload -->
            <div class="col-lg-7">
                <!-- Card 2: Question Paper Setup -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-file-alt text-primary mr-1"></i> Question Paper Configuration
                        </h6>
                        <span class="badge badge-light border">Max {{ (int)$session->total_marks }} Marks</span>
                    </div>
                    <form action="{{ route('osm.sessions.questions', $session->id) }}" method="POST">
                        @csrf
                        <div class="card-body p-3">
                            <p class="text-muted small mb-3">
                                Define each question, its full text, and maximum marks. Compulsory questions must be marked before an evaluator can finish a paper.
                            </p>

                            <div id="questionsContainer">
                                @forelse($session->questions as $idx => $q)
                                    <div class="card card-body bg-light mb-2 p-2 border question-row">
                                        <input type="hidden" name="questions[{{ $idx }}][id]" value="{{ $q->id }}">
                                        <div class="row align-items-center">
                                            <div class="col-2 form-group mb-1">
                                                <label class="small text-muted mb-0">Q. No.</label>
                                                <input type="number" name="questions[{{ $idx }}][question_number]" value="{{ $q->question_number }}" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-3 form-group mb-1">
                                                <label class="small text-muted mb-0">Label</label>
                                                <input type="text" name="questions[{{ $idx }}][question_label]" value="{{ $q->question_label }}" class="form-control form-control-sm" placeholder="Q1">
                                            </div>
                                            <div class="col-3 form-group mb-1">
                                                <label class="small text-muted mb-0">Max Marks</label>
                                                <input type="number" step="0.5" name="questions[{{ $idx }}][max_marks]" value="{{ $q->max_marks }}" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-4 form-group mb-1 text-right">
                                                <label class="small text-muted d-block mb-0">Compulsory?</label>
                                                <input type="checkbox" name="questions[{{ $idx }}][is_compulsory]" value="1" {{ $q->is_compulsory ? 'checked' : '' }}> Yes
                                            </div>
                                            <div class="col-12 form-group mb-0 mt-1">
                                                <input type="text" name="questions[{{ $idx }}][question_text]" value="{{ $q->question_text }}" class="form-control form-control-sm" placeholder="Question description & guidelines...">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="card card-body bg-light mb-2 p-2 border question-row">
                                        <div class="row align-items-center">
                                            <div class="col-2 form-group mb-1">
                                                <label class="small text-muted mb-0">Q. No.</label>
                                                <input type="number" name="questions[0][question_number]" value="1" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-3 form-group mb-1">
                                                <label class="small text-muted mb-0">Label</label>
                                                <input type="text" name="questions[0][question_label]" value="Q1" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-3 form-group mb-1">
                                                <label class="small text-muted mb-0">Max Marks</label>
                                                <input type="number" step="0.5" name="questions[0][max_marks]" value="10" class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-4 form-group mb-1 text-right">
                                                <label class="small text-muted d-block mb-0">Compulsory?</label>
                                                <input type="checkbox" name="questions[0][is_compulsory]" value="1" checked> Yes
                                            </div>
                                            <div class="col-12 form-group mb-0 mt-1">
                                                <input type="text" name="questions[0][question_text]" value="Comprehension question." class="form-control form-control-sm" placeholder="Question text...">
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addQuestionRow()">
                                    <i class="fas fa-plus mr-1"></i> Add Question
                                </button>
                                <button type="submit" class="btn btn-sm btn-osm-primary">
                                    <i class="fas fa-save mr-1"></i> Save Question Paper
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Card 3: Bulk Upload Answer Sheets -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-cloud-upload-alt text-success mr-1"></i> Bulk Upload Answer Sheets
                        </h6>
                    </div>
                    <form action="{{ route('osm.sessions.upload', $session->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body p-3">
                            <p class="text-muted small mb-2">
                                Select scanned scripts (PDF or image, &le; 20 MB each). Files are auto-matched to students when roll number or admission number appears in the filename (e.g. <code>ans_sheet_std_1.pdf</code>).
                            </p>
                            <div class="custom-file mb-3">
                                <input type="file" name="files[]" multiple class="custom-file-input" id="sheetFiles">
                                <label class="custom-file-label" for="sheetFiles">Choose files or drop scripts here...</label>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fas fa-upload mr-1"></i> Upload &amp; Auto-Map Sheets
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Evaluators & Quick Allocation -->
            <div class="col-lg-5">
                <!-- Card 4: Assign Evaluators -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-users text-info mr-1"></i> Assign Evaluators
                        </h6>
                        <span class="badge badge-light border">{{ $session->evaluators->count() }} assigned</span>
                    </div>
                    <form action="{{ route('osm.sessions.assign', $session->id) }}" method="POST">
                        @csrf
                        <div class="card-body p-3">
                            <p class="text-muted small mb-2">
                                Pick staff members to evaluate papers. Unassigned papers are distributed round-robin among selected staff.
                            </p>

                            <div class="border rounded p-2 mb-3 bg-light" style="max-height: 180px; overflow-y: auto;">
                                @foreach($staffList as $st)
                                    <div class="custom-control custom-checkbox py-1">
                                        <input type="checkbox" name="staff_ids[]" value="{{ $st->id }}" class="custom-control-input" id="staff_{{ $st->id }}"
                                            {{ $session->evaluators->pluck('staff_id')->contains($st->id) ? 'checked' : '' }}>
                                        <label class="custom-control-label small" for="staff_{{ $st->id }}">
                                            <strong>{{ $st->first_name }} {{ $st->last_name }}</strong>
                                            <span class="text-muted">({{ $st->designation?->name ?? 'Faculty' }})</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-sm btn-dark btn-block">
                                <i class="fas fa-user-plus mr-1"></i> Assign &amp; Distribute Papers
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Evaluator Allocation Summary -->
                @if($session->evaluators->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-2">
                            <h6 class="font-weight-bold text-dark small mb-0">Active Evaluator Allocations</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0" style="font-size: 0.85rem;">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Evaluator</th>
                                        <th class="text-center">Assigned</th>
                                        <th class="text-center">Done</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($session->evaluators as $ev)
                                        <tr>
                                            <td><strong>{{ $ev->staff?->first_name }} {{ $ev->staff?->last_name }}</strong></td>
                                            <td class="text-center">{{ $ev->assigned_count }}</td>
                                            <td class="text-center font-weight-bold text-success">{{ $ev->completed_count }}</td>
                                            <td><span class="badge badge-light border">{{ $ev->status }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Papers & Submissions Ledger -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold text-dark mb-0">
                    <i class="fas fa-layer-group text-primary mr-1"></i> Answer Scripts &amp; Marking Roster
                </h6>
                <span class="badge badge-secondary">{{ $session->papers->count() }} papers</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Script / Student</th>
                            <th>Pages</th>
                            <th>Evaluator</th>
                            <th>Score</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($session->papers as $p)
                            <tr>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        {{ $session->anonymous_mode ? $p->anonymous_code : ($p->student?->first_name . ' ' . $p->student?->last_name) }}
                                    </div>
                                    <small class="text-muted">{{ $p->original_file_name ?? 'scanned_paper.pdf' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $p->page_count }} page(s)</span>
                                </td>
                                <td>
                                    @if($p->evaluator)
                                        <span class="text-dark font-weight-bold small"><i class="fas fa-user-circle text-muted mr-1"></i>{{ $p->evaluator->first_name }} {{ $p->evaluator->last_name }}</span>
                                    @else
                                        <span class="badge badge-warning">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="h6 font-weight-bold {{ $p->status == 'Evaluated' || $p->status == 'Moderated' ? 'text-success' : 'text-muted' }}">
                                        {{ $p->total_score }} / {{ (int)$session->total_marks }}
                                    </span>
                                    @if($p->is_moderated)
                                        <small class="text-info d-block">(Mod: {{ $p->moderated_score }})</small>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status == 'Evaluated')
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i> Evaluated</span>
                                    @elseif($p->status == 'Moderated')
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-balance-scale mr-1"></i> Moderated</span>
                                    @elseif($p->status == 'Rejected')
                                        <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Rejected</span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1"><i class="fas fa-hourglass-start mr-1"></i> Pending</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('osm.evaluate.canvas', $p->id) }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                                        <i class="fas fa-pen-nib mr-1"></i> Mark Script
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <p class="mb-0">No answer scripts uploaded yet. Use the upload box above.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    function addQuestionRow() {
        const container = document.getElementById('questionsContainer');
        const rows = container.querySelectorAll('.question-row');
        const nextIdx = rows.length;
        const nextNum = nextIdx + 1;

        const div = document.createElement('div');
        div.className = 'card card-body bg-light mb-2 p-2 border question-row';
        div.innerHTML = `
            <div class="row align-items-center">
                <div class="col-2 form-group mb-1">
                    <label class="small text-muted mb-0">Q. No.</label>
                    <input type="number" name="questions[${nextIdx}][question_number]" value="${nextNum}" class="form-control form-control-sm" required>
                </div>
                <div class="col-3 form-group mb-1">
                    <label class="small text-muted mb-0">Label</label>
                    <input type="text" name="questions[${nextIdx}][question_label]" value="Q${nextNum}" class="form-control form-control-sm">
                </div>
                <div class="col-3 form-group mb-1">
                    <label class="small text-muted mb-0">Max Marks</label>
                    <input type="number" step="0.5" name="questions[${nextIdx}][max_marks]" value="10" class="form-control form-control-sm" required>
                </div>
                <div class="col-4 form-group mb-1 text-right">
                    <label class="small text-muted d-block mb-0">Compulsory?</label>
                    <input type="checkbox" name="questions[${nextIdx}][is_compulsory]" value="1" checked> Yes
                </div>
                <div class="col-12 form-group mb-0 mt-1">
                    <input type="text" name="questions[${nextIdx}][question_text]" value="" class="form-control form-control-sm" placeholder="Question description & guidelines...">
                </div>
            </div>
        `;
        container.appendChild(div);
    }
</script>
@endsection

