@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="lp-title-area d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Lesson Planner</h3>
                <p class="text-muted small mb-0">Operational guide, workflow steps &amp; best practices</p>
            </div>
            <div>
                <a href="{{ route('lesson-plans.plans.create') }}" class="btn btn-lp-primary">
                    <i class="fas fa-plus mr-1"></i> New Plan
                </a>
            </div>
        </div>

        @include('lesson_plans.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="lp-guide">
            <div class="row">
                <!-- Left Column (col-lg-8) -->
                <div class="col-lg-8">
                    <!-- What is a lesson plan -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5>What is a lesson plan — and how is it different from Classwork?</h5>
                            <p class="text-muted mb-0">
                                A <strong>lesson plan</strong> is what you submit <em>before</em> a teaching week — objectives, methods,
                                teaching aids and the syllabus topics you intend to cover. Your <strong>Classwork &amp; Logbook</strong>
                                records what was <em>actually</em> taught. The planner closes the loop: chapters come from the
                                <strong>Syllabus</strong>, the plan is approved, and after the week you mark each topic covered — which
                                feeds the logbook and the principal's <strong>syllabus-completion report</strong> (the document CBSE
                                inspections check).
                            </p>
                        </div>
                    </div>

                    <!-- The tabs at a glance -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="lp-eyebrow mb-2">The tabs at a glance</h6>
                            <div class="lp-tab-row">
                                <div class="lp-tab-ic"><i class="fas fa-tachometer-alt"></i></div>
                                <div><strong>Dashboard</strong> — your at-a-glance counts: drafts, pending review, approved, items needing revision, this week's plans, and overall syllabus coverage.</div>
                            </div>
                            <div class="lp-tab-row">
                                <div class="lp-tab-ic"><i class="fas fa-clipboard-list"></i></div>
                                <div><strong>Lesson Plans</strong> — create, edit, submit and clone your plans. (Admins see every teacher's plans here.)</div>
                            </div>
                            <div class="lp-tab-row">
                                <div class="lp-tab-ic"><i class="fas fa-user-check"></i></div>
                                <div><strong>Review (HOD)</strong> — if you are a department head, plans routed to you wait here to be approved (forwarded to the principal) or returned to the teacher with comments.</div>
                            </div>
                            <div class="lp-tab-row">
                                <div class="lp-tab-ic"><i class="fas fa-stamp"></i></div>
                                <div><strong>Approvals</strong> — the principal/admin queue of plans awaiting final approval. Approve one at a time (with inline comments) or tick several and <em>Approve selected</em> in bulk.</div>
                            </div>
                            <div class="lp-tab-row">
                                <div class="lp-tab-ic"><i class="fas fa-clipboard-check"></i></div>
                                <div><strong>Coverage</strong> — after a week ends, mark each planned topic <em>Covered / Partial / Not covered</em>. Covered &amp; partial topics are auto-logged to Classwork &amp; Logbook.</div>
                            </div>
                            <div class="lp-tab-row">
                                <div class="lp-tab-ic"><i class="fas fa-chart-line"></i></div>
                                <div><strong>Reports</strong> — syllabus-completion % by class, subject and teacher, per-plan detail, chronic non-coverage flags, and a one-click PDF for inspections.</div>
                            </div>
                            <div class="lp-tab-row">
                                <div class="lp-tab-ic"><i class="fas fa-cog"></i></div>
                                <div><strong>Settings</strong> (admin) — turn the HOD step on/off, map departments to their heads, and set the weekly-reminder day/time and submission deadline.</div>
                            </div>
                        </div>
                    </div>

                    <!-- The weekly flow -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="lp-eyebrow mb-3">The weekly flow</h6>
                            <div class="lp-step">
                                <div class="lp-num">1</div>
                                <div><strong>Plan (teacher).</strong> Create a plan per subject/section/week. Pick chapters &amp; topics from the syllabus, set learning objectives, choose teaching methods and aids, add homework. <em>Planned periods auto-suggest from your timetable.</em> Save as a draft and edit anytime.</div>
                            </div>
                            <div class="lp-step">
                                <div class="lp-num">2</div>
                                <div><strong>Submit.</strong> One click sends it for review. If your school uses HODs, it goes to your department head first, then the principal; otherwise straight to the principal. Once submitted you can't edit it unless it's returned.</div>
                            </div>
                            <div class="lp-step">
                                <div class="lp-num">3</div>
                                <div><strong>Review (HOD, optional).</strong> The department head approves (forwarding to the principal) or returns it with comments on specific sections.</div>
                            </div>
                            <div class="lp-step">
                                <div class="lp-num">4</div>
                                <div><strong>Approve (principal).</strong> The principal approves — individually with inline comments, or in bulk — or returns it for revision.</div>
                            </div>
                            <div class="lp-step">
                                <div class="lp-num">5</div>
                                <div><strong>Revise &amp; resubmit.</strong> If returned, the reviewer's comments show highlighted on the plan. Fix the flagged parts and resubmit — every revision is counted in the audit trail.</div>
                            </div>
                            <div class="lp-step">
                                <div class="lp-num">6</div>
                                <div><strong>Cover.</strong> After the week ends, open <strong>Coverage</strong> and mark each topic Covered / Partial / Not covered (with a reason). This updates your logbook and the syllabus-completion report automatically.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Who does what -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="lp-eyebrow mb-3">Who does what</h6>
                            <div class="lp-role">
                                <strong><i class="fas fa-chalkboard-teacher mr-1 text-primary"></i> Teachers</strong>
                                <div class="text-muted small">Create &amp; submit plans for their own classes only, revise returned plans, and mark coverage after the week. They see their own plans and reports.</div>
                            </div>
                            <div class="lp-role">
                                <strong><i class="fas fa-user-check mr-1 text-primary"></i> Heads of Department (HOD)</strong>
                                <div class="text-muted small">A teacher mapped as a department head in Settings. They review plans from their department in the <em>Review</em> tab — approve to forward, or return with comments. Only plans routed to them appear.</div>
                            </div>
                            <div class="lp-role">
                                <strong><i class="fas fa-user-tie mr-1 text-primary"></i> Principal / Admin</strong>
                                <div class="text-muted small">Final approval (single or bulk), see every plan and the school-wide reports, and configure the module in Settings.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Coverage & completion report -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="fas fa-sync-alt text-primary mr-2"></i>Coverage &amp; the syllabus-completion report</h5>
                            <p class="text-muted">
                                Marking a topic <strong>Covered</strong> or <strong>Partial</strong> automatically creates a matching entry in
                                <strong>Classwork &amp; Logbook</strong> — so you never write the same thing twice, and the "what was taught"
                                record stays truthful. Changing it back to <em>Not covered</em> removes that logbook entry.
                            </p>
                            <p class="text-muted mb-0">
                                The <strong>Reports</strong> tab rolls this up into a completion percentage (Covered = full, Partial = half) by
                                class, subject and teacher. Anyone consistently below the threshold is flagged as <strong>chronic
                                non-coverage</strong>, and the whole report exports to <strong>PDF</strong> for CBSE inspection.
                            </p>
                        </div>
                    </div>

                    <!-- Weekly reminders -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5><i class="fas fa-bell text-primary mr-2"></i>Weekly reminders</h5>
                            <p class="text-muted mb-0">
                                Teachers who haven't submitted for the coming week get an automatic reminder on the day/time set in
                                <strong>Settings</strong> (default Friday 3 PM). It lists how many plans are still pending and the submission
                                deadline. Delivery channels (in-app, email, SMS, WhatsApp) are chosen per school in
                                <em>Settings &rarr; Notifications</em>.
                            </p>
                        </div>
                    </div>

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-lightbulb mr-1"></i>
                        <strong>Tip:</strong> Use <strong>Clone to next week</strong> on any plan to carry its structure forward — then just swap the topics. Great for recurring weekly subjects.
                    </div>
                </div>

                <!-- Right Column (col-lg-4) -->
                <div class="col-lg-4">
                    <!-- Status meanings -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="lp-eyebrow mb-2">Status meanings</h6>
                            <p class="mb-2"><span class="badge badge-secondary">Draft</span> — yours to edit, not yet submitted.</p>
                            <p class="mb-2"><span class="badge badge-info">Pending HOD</span> — waiting on the department head.</p>
                            <p class="mb-2"><span class="badge badge-primary">Pending Principal</span> — waiting on the principal.</p>
                            <p class="mb-2"><span class="badge badge-success">Approved</span> — locked and approved.</p>
                            <p class="mb-0"><span class="badge badge-danger">Needs revision</span> — returned to you with comments.</p>
                        </div>
                    </div>

                    <!-- Coverage labels -->
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="lp-eyebrow mb-2">Coverage labels</h6>
                            <p class="mb-2"><span class="badge badge-success">Covered</span> — taught as planned (logged to Classwork).</p>
                            <p class="mb-2"><span class="badge badge-warning">Partial</span> — partly taught; add a reason.</p>
                            <p class="mb-2"><span class="badge badge-danger">Not covered</span> — missed; add a reason. Counts against completion.</p>
                            <p class="mb-0"><span class="badge badge-secondary">Pending</span> — not yet reconciled after the week.</p>
                        </div>
                    </div>

                    <!-- Good to know -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="lp-eyebrow mb-2">Good to know</h6>
                            <ul class="pl-3 text-muted small mb-0">
                                <li class="mb-2">Teachers only ever see and plan for <strong>their own allotted classes</strong>.</li>
                                <li class="mb-2">The <strong>HOD step is optional</strong> — schools without HODs go teacher &rarr; principal.</li>
                                <li class="mb-2">Topics pulled from the <strong>syllabus</strong> drive the completion %; you can still add ad-hoc topics.</li>
                                <li class="mb-0">Every submit, approval and return is kept in the plan's <strong>audit history</strong>.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .lp-guide h5 { font-weight:700; color:#0f172a; }
    .lp-guide h6.lp-eyebrow { font-size:0.72rem; letter-spacing:.5px; text-transform:uppercase; color:#64748b; font-weight:700; }
    .lp-guide .lp-step { display:flex; gap:12px; margin-bottom:12px; }
    .lp-guide .lp-num { flex:0 0 26px; height:26px; border-radius:50%; background:#0284c7; color:#fff; font-weight:700; font-size:0.8rem; display:flex; align-items:center; justify-content:center; }
    .lp-guide .lp-tab-row { display:flex; gap:10px; padding:8px 0; border-bottom:1px solid #f1f5f9; }
    .lp-guide .lp-tab-row:last-child { border-bottom:none; }
    .lp-guide .lp-tab-ic { flex:0 0 30px; color:#0284c7; text-align:center; padding-top:2px; }
    .lp-guide .lp-role { border-left:3px solid #0284c7; padding:4px 0 4px 12px; margin-bottom:14px; }
</style>
@endsection

