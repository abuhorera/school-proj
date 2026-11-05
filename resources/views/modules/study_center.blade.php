@extends('layouts.app')

@section('title', 'Study Center')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-book-reader text-primary mr-2"></i> Study Center &amp; Digital Classroom</h3>
            <p class="text-muted mb-0 small">Classwork logbooks, syllabus completion milestones, assignments, and downloadable learning resources.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#uploadResourceModal">
                <i class="fas fa-upload mr-1"></i> Upload Study Material
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.study-center', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'classwork' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.study-center', ['tab' => 'classwork']) }}">
                <i class="fas fa-book-open mr-1"></i> Classwork &amp; Logbook
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'syllabus' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.study-center', ['tab' => 'syllabus']) }}">
                <i class="fas fa-sitemap mr-1"></i> Manage Syllabus
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'materials' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.study-center', ['tab' => 'materials']) }}">
                <i class="fas fa-folder mr-1"></i> Manage Resources
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'homework' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.study-center', ['tab' => 'homework']) }}">
                <i class="fas fa-edit mr-1"></i> Homework &amp; Assignments
            </a>
        </li>
    </ul>

    {{-- Stats Row --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-chalkboard fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">48</div>
                        <small class="text-muted">Total Classes Logged</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-tasks fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">82.4%</div>
                        <small class="text-muted">Avg Syllabus Complete</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-file-pdf fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">124</div>
                        <small class="text-muted">Shared Study Files</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-check-double fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">96%</div>
                        <small class="text-muted">HW Submission Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($tab === 'syllabus')
        {{-- Syllabus Progress View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-sitemap text-primary mr-2"></i> Academic Curriculum &amp; Syllabus Coverage</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Class &amp; Subject</th>
                                <th>Teacher In-Charge</th>
                                <th>Chapters Covered</th>
                                <th>Progress Bar</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold">Class 10-A &middot; Mathematics</td>
                                <td>Mrs. Sunita Rao</td>
                                <td>11 of 14 Chapters</td>
                                <td style="width: 250px;">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: 78.5%;">78.5%</div>
                                    </div>
                                </td>
                                <td><span class="badge badge-success px-2 py-1">On Schedule</span></td>
                                <td class="text-right"><button class="btn btn-outline-primary btn-sm">View Chapters</button></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Class 10-A &middot; Physics</td>
                                <td>Dr. H.C. Verma</td>
                                <td>9 of 12 Chapters</td>
                                <td style="width: 250px;">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-info" style="width: 75%;">75%</div>
                                    </div>
                                </td>
                                <td><span class="badge badge-success px-2 py-1">On Schedule</span></td>
                                <td class="text-right"><button class="btn btn-outline-primary btn-sm">View Chapters</button></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Class 9-B &middot; Social Studies</td>
                                <td>Mr. Amit Choudhary</td>
                                <td>14 of 16 Chapters</td>
                                <td style="width: 250px;">
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-primary" style="width: 87.5%;">87.5%</div>
                                    </div>
                                </td>
                                <td><span class="badge badge-success px-2 py-1">Ahead of Plan</span></td>
                                <td class="text-right"><button class="btn btn-outline-primary btn-sm">View Chapters</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($tab === 'materials')
        {{-- Resources View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-folder text-warning mr-2"></i> Digital Learning Resources &amp; Notes</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Resource Title</th>
                                <th>Class &amp; Subject</th>
                                <th>Type</th>
                                <th>Uploaded By</th>
                                <th>Date</th>
                                <th class="text-right">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold"><i class="fas fa-file-pdf text-danger mr-2"></i> Trigonometry Formula Sheet &amp; Exemplar</td>
                                <td>Class 10-A &middot; Math</td>
                                <td><span class="badge badge-light border">PDF (2.4 MB)</span></td>
                                <td>Mrs. Sunita Rao</td>
                                <td>02 Nov 2026</td>
                                <td class="text-right"><a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-download mr-1"></i> Get</a></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold"><i class="fas fa-file-powerpoint text-warning mr-2"></i> Ray Optics &amp; Mirror Equations PPT</td>
                                <td>Class 12-Science &middot; Physics</td>
                                <td><span class="badge badge-light border">PPTX (8.1 MB)</span></td>
                                <td>Dr. H. Verma</td>
                                <td>28 Oct 2026</td>
                                <td class="text-right"><a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-download mr-1"></i> Get</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Default Dashboard & Classwork View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-book-open text-primary mr-2"></i> Daily Teacher Classwork Logbook</h5>
                <button class="btn btn-outline-primary btn-sm"><i class="fas fa-plus mr-1"></i> New Log Entry</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Class &amp; Section</th>
                                <th>Subject</th>
                                <th>Topic Taught in Class</th>
                                <th>Homework Assigned</th>
                                <th>Faculty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-weight-bold text-muted">Today</td>
                                <td><span class="badge badge-primary px-2 py-1">Class 10-A</span></td>
                                <td>Mathematics</td>
                                <td>Quadratic Equations (Discriminant &amp; Nature of Roots)</td>
                                <td>Exercise 4.3 (Q1 to Q8 in Fair Copy)</td>
                                <td>Mrs. Sunita Rao</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Today</td>
                                <td><span class="badge badge-info px-2 py-1">Class 10-A</span></td>
                                <td>Science (Physics)</td>
                                <td>Refraction through Glass Prism &amp; Dispersion of Light</td>
                                <td>Draw ray diagram of rainbow formation</td>
                                <td>Dr. H.C. Verma</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-muted">Yesterday</td>
                                <td><span class="badge badge-secondary px-2 py-1">Class 9-B</span></td>
                                <td>English Lit</td>
                                <td>The Road Not Taken by Robert Frost (Poetic Devices)</td>
                                <td>Write central theme summary (150 words)</td>
                                <td>Mrs. Ananya Sen</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Upload Resource Modal --}}
<div class="modal fade" id="uploadResourceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-upload text-primary mr-1"></i> Upload Digital Learning Material</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Resource Title</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Chapter 4 Practice Questions">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Target Class</label>
                        <select class="form-control form-control-sm">
                            @foreach($classes as $c)
                                <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Subject</label>
                        <select class="form-control form-control-sm">
                            @foreach($subjects as $sb)
                                <option value="{{ $sb->name }}">{{ $sb->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Choose File (PDF, PPT, Word, Video)</label>
                    <input type="file" class="form-control-file border p-1 rounded">
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Upload Resource</button>
            </div>
        </div>
    </div>
</div>
@endsection

