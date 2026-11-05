@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Classroom Assessment Guide</h3>
                <p class="text-muted small mb-0">Best practices for frequent formative classroom quizzes, low-stakes testing, and evaluation rubrics.</p>
            </div>
            <div>
                <a href="{{ route('assessment.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-th-large mr-1"></i> Assessment Dashboard
                </a>
            </div>
        </div>

        @include('assessments.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Hero Banner -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="badge badge-light px-2.5 py-1 text-emerald-800 text-uppercase font-weight-bold mb-2">Formative Assessment</span>
                        <h3 class="font-weight-bold text-white mb-2">Run frequent classroom tests &mdash; the easy way</h3>
                        <p class="text-emerald-100 mb-0 leading-relaxed">
                            Continuous classroom evaluation helps teachers identify learning gaps weeks before high-stakes term examinations. Fast setup, recurring sittings, and instant mark entry matrices make testing frictionless.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-right mt-3 mt-lg-0">
                        <a href="{{ route('assessment.assessments.create') }}" class="btn btn-light text-emerald-800 font-weight-bold px-4 py-2 shadow-sm">
                            <i class="fas fa-plus mr-1"></i> Create Your First Test
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- How it Works -->
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-tasks text-emerald-600 mr-1.5"></i> How It Works
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex mb-3.5">
                            <div class="step-num mr-3 bg-emerald-100 text-emerald-800 font-weight-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                1
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Define the Assessment Blueprint</h6>
                                <p class="small text-muted mb-0">Select class, section, subject, maximum marks, and passing threshold. Choose from multiple types: <strong>Quiz</strong>, <strong>Unit Test</strong>, <strong>Oral Recitation</strong>, <strong>Practical Lab</strong>, or <strong>Assignment</strong>.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3.5">
                            <div class="step-num mr-3 bg-emerald-100 text-emerald-800 font-weight-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                2
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Add Sittings (Occurrences)</h6>
                                <p class="small text-muted mb-0">For recurring evaluations (e.g. Weekly Math Drill), generate new sittings under the same assessment blueprint without having to recreate parameters each time.</p>
                            </div>
                        </div>

                        <div class="d-flex mb-3.5">
                            <div class="step-num mr-3 bg-emerald-100 text-emerald-800 font-weight-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                3
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Rapid Mark Entry Matrix</h6>
                                <p class="small text-muted mb-0">Teachers can enter scores directly on tablet or PC with instant auto-save and 1-click absent candidate flagging.</p>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="step-num mr-3 bg-emerald-100 text-emerald-800 font-weight-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; min-width: 36px;">
                                4
                            </div>
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Publish Scorecard &amp; Rank Lists</h6>
                                <p class="small text-muted mb-0">Click <strong>Publish</strong> to release results to parent portals, student dashboards, and generate printable marksheets.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What it is NOT -->
            <div class="col-lg-5 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-exclamation-triangle text-amber-500 mr-1.5"></i> What It Is NOT
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-warning border-0 p-3 mb-3" style="border-radius: 8px;">
                            <strong class="d-block mb-1 text-dark"><i class="fas fa-times-circle text-danger mr-1"></i> Not a Formal Exam Replacement</strong>
                            <p class="small mb-0 text-muted">
                                This module does <strong>not</strong> generate official CBSE/ICSE term report cards or final annual marksheet certificates. Use the <strong>Offline Examinations</strong> module for formal hall exams.
                            </p>
                        </div>

                        <div class="alert alert-info border-0 p-3 mb-3" style="border-radius: 8px;">
                            <strong class="d-block mb-1 text-dark"><i class="fas fa-check-circle text-info mr-1"></i> Ideal For:</strong>
                            <ul class="small text-muted pl-3 mb-0">
                                <li>Weekly class pop quizzes</li>
                                <li>Unit test score tracking</li>
                                <li>Oral reading / recitation checks</li>
                                <li>Science lab practical records</li>
                                <li>Homework project rubrics</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

