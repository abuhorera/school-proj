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
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title font-weight-bold text-dark mb-0">
                    <i class="fas fa-plus-circle text-primary mr-1"></i> New Evaluation Session
                </h5>
            </div>
            <form action="{{ route('osm.sessions.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Term 1 – Mathematics – Class 10A" required value="{{ old('title') }}">
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Total Max Marks <span class="text-danger">*</span></label>
                            <input type="number" step="0.5" name="total_marks" class="form-control" placeholder="e.g. 100" required value="{{ old('total_marks', 100) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Class</label>
                            <select name="school_class_id" class="form-control" id="school_class_id">
                                <option value="">-- Select Class --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Section</label>
                            <select name="section_id" class="form-control" id="section_id">
                                <option value="">-- All sections --</option>
                                @foreach($sections as $sec)
                                    <option value="{{ $sec->id }}">{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" class="form-control" required>
                                <option value="">-- Select Subject --</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Linked Exam (optional)</label>
                            <select name="exam_id" class="form-control">
                                <option value="">-- None (Standalone Session) --</option>
                                @foreach($exams as $ex)
                                    <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Evaluation Deadline</label>
                            <input type="date" name="evaluation_deadline" class="form-control" value="{{ old('evaluation_deadline', now()->addDays(7)->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Moderation Level</label>
                            <select name="moderation_level" class="form-control">
                                <option value="sample_10">10% Random Sample</option>
                                <option value="sample_20">20% Random Sample</option>
                                <option value="borderline">Borderline Cases (&plusmn;3% of pass/distinction)</option>
                                <option value="full">100% Full Moderation</option>
                                <option value="none">No Moderation</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-sliders-h text-secondary mr-1"></i> Workflow &amp; Integrity Controls</h6>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Report Card Sync</label>
                            <div class="custom-control custom-switch mt-1">
                                <input type="checkbox" class="custom-control-input" id="sync_to_report_card" name="sync_to_report_card" value="1">
                                <label class="custom-control-label font-weight-normal" for="sync_to_report_card">Allow sync to report card (exam marks)</label>
                            </div>
                            <small class="text-muted">Requires a linked Exam. Sync is triggered from the session page.</small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Parent Portal</label>
                            <div class="custom-control custom-switch mt-1">
                                <input type="checkbox" class="custom-control-input" id="publish_to_parent" name="publish_to_parent" value="1">
                                <label class="custom-control-label font-weight-normal" for="publish_to_parent">Show evaluated copy to parents</label>
                            </div>
                            <small class="text-muted">Parents see finalized papers + per-question marks for their child.</small>
                        </div>

                        <div class="col-md-4 form-group">
                            <label class="font-weight-bold">Anonymous Evaluation</label>
                            <div class="custom-control custom-switch mt-1">
                                <input type="checkbox" class="custom-control-input" id="anonymous_mode" name="anonymous_mode" value="1" checked>
                                <label class="custom-control-label font-weight-normal" for="anonymous_mode">Blind marking (hide student identity)</label>
                            </div>
                            <small class="text-muted">Evaluators see masked code (e.g. OSM-XI-7821) to prevent bias.</small>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="allow_annotations" name="allow_annotations" value="1" checked>
                                <label class="custom-control-label font-weight-normal" for="allow_annotations">Allow canvas on-image annotations (ticks, crosses, pen, stamps)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right bg-white">
                    <a href="{{ route('osm.sessions') }}" class="btn btn-default mr-2">Cancel</a>
                    <button type="submit" class="btn btn-osm-primary">
                        <i class="fas fa-save mr-1"></i> Create Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

