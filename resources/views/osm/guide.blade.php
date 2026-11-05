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
        <div class="row">
            <!-- Left Column: 7 Step Guide -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 class="font-weight-bold text-dark mb-2">How On-Screen Marking (OSM) works</h4>
                        <p class="text-muted mb-4">
                            Digitize paper evaluation: scan answer sheets, mark them on-screen question-by-question, and get instant score reports &mdash; no red pen required.
                        </p>

                        <!-- Step 1 -->
                        <div class="osm-guide-step d-flex mb-4">
                            <div class="osm-step-num mr-3">1</div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">Create a session <span class="text-muted font-weight-normal small">(admin)</span></h5>
                                <p class="text-muted small mb-0">
                                    Go to <strong>Sessions &rarr; New Session</strong>. Choose the class/section, subject, and (optionally) link an exam &amp; exam type. Turn on <strong>Anonymous evaluation</strong> if you want evaluators to mark coded papers without seeing student names.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="osm-guide-step d-flex mb-4">
                            <div class="osm-step-num mr-3">2</div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">Configure the question paper</h5>
                                <p class="text-muted small mb-0">
                                    On the session page, open the <strong>Question Paper</strong> configuration card and add each question with its maximum marks. Compulsory questions must be marked before a paper can be finished. The total is calculated automatically.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="osm-guide-step d-flex mb-4">
                            <div class="osm-step-num mr-3">3</div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">Upload answer sheets (PDF / image)</h5>
                                <p class="text-muted small mb-0">
                                    Open the session workbench, use the <strong>Bulk Upload Answer Sheets</strong> area. Select files (PDF or images, &le; 20 MB each). The system auto-matches sheets to students using roll numbers or admission IDs in the filenames. Multi-page PDFs render each page as an on-screen canvas during evaluation.
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="osm-guide-step d-flex mb-4">
                            <div class="osm-step-num mr-3">4</div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">Assign evaluators</h5>
                                <p class="text-muted small mb-0">
                                    In the <strong>Assign Evaluators</strong> section, pick one or more teachers. Unassigned papers are shared round-robin. In anonymous mode, teachers only ever see coded papers assigned to them.
                                </p>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="osm-guide-step d-flex mb-4">
                            <div class="osm-step-num mr-3">5</div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">Evaluate <span class="text-muted font-weight-normal small">(evaluator)</span></h5>
                                <p class="text-muted small mb-0">
                                    Open <strong>Evaluate</strong>, pick a paper bundle, and mark each question on the interactive canvas. Annotate with green ticks, red crosses, freehand pen, and highlighters. Enter marks per question, auto-tally live, and click <strong>Finish Paper</strong> when done, or <strong>Reject / UFM</strong> to escalate a defective booklet.
                                </p>
                            </div>
                        </div>

                        <!-- Step 6 -->
                        <div class="osm-guide-step d-flex mb-4">
                            <div class="osm-step-num mr-3">6</div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">Moderation <span class="text-muted font-weight-normal small">(optional)</span></h5>
                                <p class="text-muted small mb-0">
                                    If <strong>Moderation</strong> is enabled on the session, evaluated papers route to the <strong>Moderation</strong> console for a second-level review by Head Examiners. The moderator can adjust scores, add rationale, or approve marks directly.
                                </p>
                            </div>
                        </div>

                        <!-- Step 7 -->
                        <div class="osm-guide-step d-flex mb-0">
                            <div class="osm-step-num mr-3">7</div>
                            <div>
                                <h5 class="font-weight-bold text-dark mb-1">Reports &amp; Analytics</h5>
                                <p class="text-muted small mb-0">
                                    The <strong>Reports</strong> tab displays per-session scorecards, rank lists, and per-student answer breakdowns. The <strong>Analytics</strong> page provides deeper insights: score distribution histograms, question-wise difficulty index, and turnaround speed audit per evaluator.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Good to know & Quick Navigation -->
            <div class="col-lg-4">
                <!-- Good to Know Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-lightbulb text-warning mr-1"></i> Good to know
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="text-muted small pl-3 mb-0" style="line-height: 1.8;">
                            <li class="mb-2"><strong>Canvas Markup</strong>: Pages render on HTML5 canvas so you can annotate them &mdash; tick, cross, underline, circle, highlight, and stamps.</li>
                            <li class="mb-2"><strong>Tenant Security</strong>: Answer-sheet files are authenticated and tenant-scoped.</li>
                            <li class="mb-2"><strong>Anonymous Mode</strong>: Masks candidate names &amp; roll numbers from evaluators to eliminate subconscious bias.</li>
                            <li class="mb-2"><strong>Audit Trail</strong>: Every score entry, override, and comment is timestamped and tracked.</li>
                            <li class="mb-0"><strong>Report Card Pipeline</strong>: Sync finalized OSM marks directly into examination marks registers.</li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Navigation Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-compass text-info mr-1"></i> Quick navigation
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="list-group list-group-flush small">
                            <a href="{{ route('osm.dashboard') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-tachometer-alt text-primary mr-2"></i> OSM Dashboard</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('osm.sessions') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-layer-group text-primary mr-2"></i> Evaluation Sessions</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('osm.evaluate') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-pen-nib text-primary mr-2"></i> Evaluator Workbench</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('osm.moderation') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-balance-scale text-primary mr-2"></i> Head Examiner Moderation</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                            <a href="{{ route('osm.reports') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                                <span><i class="fas fa-chart-bar text-primary mr-2"></i> Reports &amp; Analytics</span>
                                <i class="fas fa-chevron-right text-muted small"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .osm-step-num {
        flex: 0 0 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #0284c7;
        color: #fff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }
</style>
@endsection

