@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Surveys & Feedback Guide</h1>
                <p class="text-muted small mb-0">Learn how to run effective school pulse surveys and close the loop on community feedback.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('survey.templates') }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-layer-group mr-1"></i> Templates
                </a>
                <a href="{{ route('survey.create') }}" class="btn btn-primary btn-sm font-weight-bold">
                    <i class="fas fa-plus mr-1"></i> New survey
                </a>
            </div>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('surveys.partials.subnav')

        <!-- Big Banner -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
            <div class="card-body p-4 text-white">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge badge-light text-primary font-weight-bold px-2 py-1 mb-2">FEEDBACK LIFECYCLE</span>
                        <h3 class="font-weight-bold mb-2">Listen, Understand, and Resolve</h3>
                        <p class="text-white-50 mb-0" style="color: #e0f2fe !important;">
                            Schools thrive on active parent, student, and teacher alignment. Run low-friction short pulses instead of exhaustive annual questionnaires to capture real-time sentiments.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                        <a href="{{ route('survey.templates') }}" class="btn btn-light text-primary font-weight-bold px-3 py-2 shadow-sm">
                            <i class="fas fa-magic mr-1"></i> Start from a template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- 4 Lifecycle Steps -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title font-weight-bold m-0 text-dark">The 4-Step Survey Lifecycle</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 38px; height: 38px; font-weight: bold;">
                                1
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Design & Audience Targeting</h6>
                                <p class="text-muted small mb-0">
                                    Pick a blueprint or start from scratch. Define whether the survey is a short 2-question Poll, a general satisfaction Survey, or an Anonymous Feedback form. Target whole school, specific classes, or parent roles.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 38px; height: 38px; font-weight: bold;">
                                2
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Distribute & Collect Submissions</h6>
                                <p class="text-muted small mb-0">
                                    Publish the survey. It instantly appears in parent and student inboxes under <strong>My Surveys</strong> and in optional login modal popups. You can also share the public access URL on WhatsApp or school circulars.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-4">
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 38px; height: 38px; font-weight: bold;">
                                3
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Analyze & Aggregate Sentiments</h6>
                                <p class="text-muted small mb-0">
                                    View real-time response rates, average Likert ratings (scale 1–5), Net Promoter Scores (0–10), and Yes/No ratios. Download complete submissions as a CSV spreadsheet or publish high-level findings to stakeholders.
                                </p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 38px; height: 38px; font-weight: bold;">
                                4
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Close the Loop with Feedback Triage</h6>
                                <p class="text-muted small mb-0">
                                    Low scores and critical complaints automatically generate tickets in the <strong>Feedback Triage</strong> board. Assign tasks to coordinators, add internal notes, and track resolution through to Actioned status.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Cards: Anonymity & Tips -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="font-weight-bold m-0 text-dark"><i class="fas fa-shield-alt mr-2 text-info"></i> Anonymity & Trust</h6>
                    </div>
                    <div class="card-body p-3">
                        <p class="small text-muted mb-2">
                            When <strong>Anonymous Mode</strong> is enabled on a survey, neither respondent IDs, student roll numbers, nor email addresses are recorded.
                        </p>
                        <p class="small text-muted mb-0">
                            Use anonymous pulses for sensitive topics like <strong>Teacher Appraisals</strong>, <strong>Student Wellbeing Checks</strong>, and <strong>Suggestion Boxes</strong> to encourage 100% candid feedback.
                        </p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="font-weight-bold m-0 text-dark"><i class="fas fa-lightbulb mr-2 text-warning"></i> Best Practices</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="small text-muted pl-3 mb-0" style="line-height: 1.8;">
                            <li>Keep surveys under <strong>5 questions</strong> for 80%+ completion rates.</li>
                            <li>Use <strong>Rating Scales</strong> (1–5) and <strong>NPS</strong> (0–10) for quantifiable metrics.</li>
                            <li>Include only <strong>one open-ended paragraph</strong> question at the end for specific suggestions.</li>
                            <li>Always acknowledge feedback publicly in the next school newsletter.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

