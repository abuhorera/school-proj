@extends('layouts.app')

@section('content')
<div class="content-header py-2 bg-dark text-white border-bottom">
    <div class="container-fluid">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="d-flex align-items-center mb-1 mb-md-0">
                <a href="{{ route('osm.evaluate') }}" class="btn btn-sm btn-outline-light mr-3" title="Back to Evaluate Queue">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h5 class="font-weight-bold mb-0 text-white">
                        {{ $session->title }}
                    </h5>
                    <div class="small text-muted" style="color: #cbd5e1 !important;">
                        @if($session->anonymous_mode)
                            <span class="badge badge-warning mr-1">ANONYMOUS</span>
                            <strong>{{ $paper->anonymous_code }}</strong>
                        @else
                            <strong>{{ $paper->student?->first_name }} {{ $paper->student?->last_name }}</strong> &middot; Roll {{ $paper->student?->admission_number ?? '1' }}
                        @endif
                        &middot; <span class="badge badge-light text-dark">{{ $paper->page_count }} page(s)</span>
                        &middot; <span class="badge badge-info">{{ $paper->status }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Evaluation Timer -->
                <div class="mr-3 text-right">
                    <div class="small text-muted" style="color: #94a3b8 !important;">Time</div>
                    <div class="font-weight-bold text-warning font-monospace" id="timerDisplay">00:00:00</div>
                </div>

                <!-- Score Tally -->
                <div class="mr-3 text-right">
                    <div class="small text-muted" style="color: #94a3b8 !important;">Total Score</div>
                    <div class="h5 font-weight-bold text-success mb-0">
                        <span id="currentTotalScore">{{ $paper->total_score }}</span> / {{ (int)$session->total_marks }}
                    </div>
                </div>

                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#rejectModal">
                        <i class="fas fa-ban mr-1"></i> Reject / UFM
                    </button>
                    <button type="button" class="btn btn-success font-weight-bold px-3" onclick="document.getElementById('finishPaperForm').submit();">
                        <i class="fas fa-check-circle mr-1"></i> Finish Paper
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content p-0" style="background-color: #0f172a; min-height: 85vh;">
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <!-- Left & Center: Canvas & Annotations Workbench -->
            <div class="col-lg-8 col-xl-9 border-right border-secondary d-flex flex-column" style="height: 85vh;">
                <!-- Annotation Toolbar -->
                <div class="osm-anno-toolbar d-flex flex-wrap justify-content-between align-items-center px-3 py-2 bg-secondary text-white">
                    <!-- Page Nav -->
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-dark" onclick="prevPage()"><i class="fas fa-chevron-left"></i></button>
                        <span class="btn btn-dark disabled font-weight-bold px-3">
                            Page <span id="currentPageNum">1</span> / <span id="totalPagesNum">{{ $paper->page_count }}</span>
                        </span>
                        <button type="button" class="btn btn-dark" onclick="nextPage()"><i class="fas fa-chevron-right"></i></button>
                    </div>

                    <!-- Tools -->
                    <div class="btn-group btn-group-sm my-1">
                        <button type="button" class="btn btn-light anno-tool active" data-tool="select" title="Select / Pan">
                            <i class="fas fa-mouse-pointer"></i>
                        </button>
                        <button type="button" class="btn btn-light anno-tool text-success font-weight-bold" data-tool="tick" title="Stamp Green Tick (Correct)">
                            <i class="fas fa-check"></i> &check;
                        </button>
                        <button type="button" class="btn btn-light anno-tool text-danger font-weight-bold" data-tool="cross" title="Stamp Red Cross (Incorrect)">
                            <i class="fas fa-times"></i> &cross;
                        </button>
                        <button type="button" class="btn btn-light anno-tool text-primary" data-tool="pen" title="Red Pen Freehand">
                            <i class="fas fa-pen"></i> Pen
                        </button>
                        <button type="button" class="btn btn-light anno-tool text-warning" data-tool="highlight" title="Highlighter">
                            <i class="fas fa-highlighter"></i> Highlight
                        </button>
                        <button type="button" class="btn btn-light anno-tool text-info" data-tool="underline" title="Underline">
                            <i class="fas fa-underline"></i>
                        </button>
                    </div>

                    <!-- Zoom & Reset -->
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-dark" onclick="zoomIn()"><i class="fas fa-search-plus"></i></button>
                        <button type="button" class="btn btn-dark" onclick="zoomOut()"><i class="fas fa-search-minus"></i></button>
                        <button type="button" class="btn btn-dark" onclick="clearCanvasAnnotations()"><i class="fas fa-eraser mr-1"></i> Clear</button>
                    </div>
                </div>

                <!-- Canvas Workspace Container -->
                <div class="osm-canvas-scroll flex-grow-1 overflow-auto p-4 d-flex justify-content-center align-items-start" style="background-color: #1e293b;">
                    <div id="paperSheetContainer" style="position: relative; width: 680px; min-height: 900px; background: #ffffff; box-shadow: 0 10px 25px rgba(0,0,0,0.5); border-radius: 4px; padding: 40px; color: #1e293b;">
                        <!-- Scanned Answer Sheet Simulation Mockup -->
                        <div class="border-bottom pb-3 mb-3 d-flex justify-content-between text-muted small">
                            <div>
                                <strong>MultiSchool ERP &middot; On-Screen Marking System</strong><br>
                                Subject: {{ $session->subject?->name ?? 'English Core' }}
                            </div>
                            <div class="text-right">
                                Code: <strong>{{ $paper->anonymous_code }}</strong><br>
                                Page <span class="page-indicator">1</span> of {{ $paper->page_count }}
                            </div>
                        </div>

                        <!-- Handwritten / Printed Student Response Simulation -->
                        <div class="answer-content font-serif" style="font-family: 'Georgia', serif; line-height: 1.8; font-size: 1.05rem;">
                            <h6 class="font-weight-bold text-dark mb-2">Section A: Reading Comprehension</h6>
                            <p class="text-justify mb-3">
                                <em>Answer 1:</em> The author indicates that artificial intelligence and neural computing have shifted the paradigm of academic evaluations from mechanical assessments to conceptual mastery. By analyzing cognitive patterns, educational institutions can foster individual brilliance without bias.
                            </p>
                            <p class="text-justify mb-3">
                                <em>Answer 2:</em> Two primary advantages highlighted in paragraph 3 include:
                                <br>&nbsp;&nbsp;(a) Complete transparency and auditability of graded marks.
                                <br>&nbsp;&nbsp;(b) Real-time feedback mechanisms enabling remedial pedagogy.
                            </p>
                            <div class="p-3 bg-light border rounded mb-3 font-mono text-dark" style="font-family: monospace; font-size: 0.9rem;">
                                // Q2 Invitation Letter Draft:<br>
                                The Principal, Staff and Students of Yug International School<br>
                                cordially invite you to the Annual Inter-School Science Fair on Nov 25, 2026.<br>
                                Venue: Main Auditorium &middot; Time: 10:00 AM &middot; RSVP: Admin Office
                            </div>
                        </div>

                        <!-- Interactive Annotation Overlay Canvas -->
                        <canvas id="markingCanvas" width="680" height="900" style="position: absolute; top: 0; left: 0; cursor: crosshair; z-index: 10;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Column: Question Palette & Scoring Drawer -->
            <div class="col-lg-4 col-xl-3 bg-white d-flex flex-column" style="height: 85vh; border-left: 1px solid #e2e8f0;">
                <div class="p-3 border-bottom bg-light">
                    <h6 class="font-weight-bold text-dark mb-1">
                        <i class="fas fa-th-list text-primary mr-1"></i> Question Palette
                    </h6>
                    <div class="small text-muted">Click any question to enter marks &amp; remarks.</div>
                </div>

                <!-- Questions List Grid / Tabs -->
                <div class="p-2 border-bottom bg-white overflow-auto" style="max-height: 160px;">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($session->questions as $q)
                            @php
                                $score = $paper->scores->firstWhere('osm_question_id', $q->id);
                                $isMarked = $score && $score->is_checked;
                            @endphp
                            <button type="button" class="btn btn-sm m-1 {{ $isMarked ? 'btn-success' : 'btn-outline-secondary' }} question-btn"
                                data-qid="{{ $q->id }}"
                                data-label="{{ $q->question_label }}"
                                data-max="{{ $q->max_marks }}"
                                data-text="{{ $q->question_text }}"
                                data-compulsory="{{ $q->is_compulsory ? '1' : '0' }}"
                                data-awarded="{{ $score ? $score->marks_awarded : '0' }}"
                                data-comment="{{ $score ? $score->comment : '' }}"
                                onclick="selectQuestion(this)">
                                <strong>{{ $q->question_label }}</strong>
                                <span class="d-block small" style="font-size: 0.7rem;">
                                    {{ $isMarked ? $score->marks_awarded : '–' }}/{{ (int)$q->max_marks }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Active Question Scoring Form Box -->
                <div class="p-3 flex-grow-1 overflow-auto">
                    <form id="scoreForm" action="{{ route('osm.evaluate.score', $paper->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="question_id" id="formQuestionId" value="{{ $session->questions->first()?->id }}">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-primary px-2 py-1" id="activeQLabel" style="font-size: 0.9rem;">
                                {{ $session->questions->first()?->question_label ?? 'Q1' }}
                            </span>
                            <span class="badge badge-light border" id="activeQCompulsory">Compulsory</span>
                        </div>

                        <div class="small text-muted mb-3 p-2 bg-light rounded" id="activeQText">
                            {{ $session->questions->first()?->question_text ?? 'Evaluate answer.' }}
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Marks Awarded (Max <span id="activeQMax">{{ (int)($session->questions->first()?->max_marks ?? 10) }}</span>) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.5" min="0" max="100" name="marks_awarded" id="formMarksAwarded" class="form-control font-weight-bold h5 text-primary mb-0" required value="{{ $paper->scores->first()?->marks_awarded ?? 0 }}">
                                <div class="input-group-append">
                                    <span class="input-group-text font-weight-bold">/ <span id="maxLabelSuffix">{{ (int)($session->questions->first()?->max_marks ?? 10) }}</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold small">Examiner Remarks (optional)</label>
                            <textarea name="comment" id="formComment" rows="2" class="form-control form-control-sm" placeholder="Observation, rationale, or deductions...">{{ $paper->scores->first()?->comment ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-osm-primary btn-block font-weight-bold mb-2">
                            <i class="fas fa-save mr-1"></i> Save Marks &amp; Next
                        </button>
                    </form>
                </div>

                <div class="p-3 bg-light border-top">
                    <small class="text-muted d-block mb-2">
                        <i class="fas fa-info-circle mr-1"></i> Marks are tallied live. Finish paper once all questions are marked.
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Finish Paper Form -->
<form id="finishPaperForm" action="{{ route('osm.evaluate.finish', $paper->id) }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="elapsed_seconds" id="finishElapsedSeconds" value="180">
</form>

<!-- Reject Paper Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-ban mr-1"></i> Reject Paper / Flag UFM</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('osm.evaluate.reject', $paper->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-dark small mb-3">
                        If this paper cannot be marked fairly, select the reason below. It will be escalated to the examination superintendent.
                    </p>
                    <div class="form-group">
                        <label class="font-weight-bold">Reason *</label>
                        <select name="reject_reason" class="form-control" required>
                            <option value="Illegible / poor scan">Illegible / poor scan</option>
                            <option value="Wrong / missing pages">Wrong / missing pages</option>
                            <option value="Unfair means (UFM) suspected">Unfair means (UFM) suspected</option>
                            <option value="Mismatched student">Mismatched student</option>
                            <option value="Blank answer booklet">Blank answer booklet</option>
                            <option value="Other">Other violation</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger font-weight-bold">
                        <i class="fas fa-ban mr-1"></i> Reject Paper
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Timer
    let secondsElapsed = {{ $paper->elapsed_seconds ?: 0 }};
    setInterval(function() {
        secondsElapsed++;
        let hrs = Math.floor(secondsElapsed / 3600).toString().padStart(2, '0');
        let mins = Math.floor((secondsElapsed % 3600) / 60).toString().padStart(2, '0');
        let secs = (secondsElapsed % 60).toString().padStart(2, '0');
        document.getElementById('timerDisplay').innerText = `${hrs}:${mins}:${secs}`;
        document.getElementById('finishElapsedSeconds').value = secondsElapsed;
    }, 1000);

    // Question Selection
    function selectQuestion(btn) {
        document.querySelectorAll('.question-btn').forEach(b => b.classList.remove('border-primary', 'shadow-sm'));
        btn.classList.add('border-primary', 'shadow-sm');

        document.getElementById('formQuestionId').value = btn.dataset.qid;
        document.getElementById('activeQLabel').innerText = btn.dataset.label;
        document.getElementById('activeQMax').innerText = btn.dataset.max;
        document.getElementById('maxLabelSuffix').innerText = btn.dataset.max;
        document.getElementById('activeQText').innerText = btn.dataset.text || 'No question description provided.';
        document.getElementById('formMarksAwarded').max = btn.dataset.max;
        document.getElementById('formMarksAwarded').value = btn.dataset.awarded || 0;
        document.getElementById('formComment').value = btn.dataset.comment || '';
        document.getElementById('activeQCompulsory').innerText = btn.dataset.compulsory === '1' ? 'Compulsory' : 'Optional';
    }

    // Page navigation
    let curPage = 1;
    const maxPage = {{ $paper->page_count }};
    function nextPage() {
        if (curPage < maxPage) {
            curPage++;
            updatePageDisplay();
        }
    }
    function prevPage() {
        if (curPage > 1) {
            curPage--;
            updatePageDisplay();
        }
    }
    function updatePageDisplay() {
        document.getElementById('currentPageNum').innerText = curPage;
        document.querySelectorAll('.page-indicator').forEach(el => el.innerText = curPage);
    }

    // Canvas Tools & Annotations
    const canvas = document.getElementById('markingCanvas');
    const ctx = canvas.getContext('2d');
    let currentTool = 'select';
    let isDrawing = false;
    let startX = 0, startY = 0;

    document.querySelectorAll('.anno-tool').forEach(b => {
        b.addEventListener('click', function() {
            document.querySelectorAll('.anno-tool').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            currentTool = this.dataset.tool;
        });
    });

    canvas.addEventListener('mousedown', function(e) {
        const rect = canvas.getBoundingClientRect();
        startX = e.clientX - rect.left;
        startY = e.clientY - rect.top;

        if (currentTool === 'tick') {
            drawTick(startX, startY);
        } else if (currentTool === 'cross') {
            drawCross(startX, startY);
        } else if (currentTool === 'pen' || currentTool === 'highlight' || currentTool === 'underline') {
            isDrawing = true;
            ctx.beginPath();
            ctx.moveTo(startX, startY);
        }
    });

    canvas.addEventListener('mousemove', function(e) {
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        const curX = e.clientX - rect.left;
        const curY = e.clientY - rect.top;

        if (currentTool === 'pen') {
            ctx.strokeStyle = '#dc2626';
            ctx.lineWidth = 2;
            ctx.lineTo(curX, curY);
            ctx.stroke();
        } else if (currentTool === 'highlight') {
            ctx.strokeStyle = 'rgba(250, 204, 21, 0.4)';
            ctx.lineWidth = 14;
            ctx.lineTo(curX, curY);
            ctx.stroke();
        } else if (currentTool === 'underline') {
            ctx.strokeStyle = '#dc2626';
            ctx.lineWidth = 2;
            ctx.lineTo(curX, curY);
            ctx.stroke();
        }
    });

    canvas.addEventListener('mouseup', function() {
        if (isDrawing) {
            isDrawing = false;
            ctx.closePath();
        }
    });

    function drawTick(x, y) {
        ctx.strokeStyle = '#16a34a';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(x - 10, y);
        ctx.lineTo(x, y + 10);
        ctx.lineTo(x + 18, y - 12);
        ctx.stroke();
    }

    function drawCross(x, y) {
        ctx.strokeStyle = '#dc2626';
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.moveTo(x - 10, y - 10);
        ctx.lineTo(x + 10, y + 10);
        ctx.moveTo(x + 10, y - 10);
        ctx.lineTo(x - 10, y + 10);
        ctx.stroke();
    }

    function clearCanvasAnnotations() {
        if (confirm('Clear drawings on this page?')) {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }
    }

    // Zoom
    let zoomLevel = 1;
    function zoomIn() {
        zoomLevel += 0.1;
        document.getElementById('paperSheetContainer').style.transform = `scale(${zoomLevel})`;
        document.getElementById('paperSheetContainer').style.transformOrigin = 'top center';
    }
    function zoomOut() {
        if (zoomLevel > 0.6) {
            zoomLevel -= 0.1;
            document.getElementById('paperSheetContainer').style.transform = `scale(${zoomLevel})`;
            document.getElementById('paperSheetContainer').style.transformOrigin = 'top center';
        }
    }
</script>

<style>
    .anno-tool.active {
        background-color: #0284c7 !important;
        color: #fff !important;
    }
</style>
@endsection

