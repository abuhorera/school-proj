@extends('layouts.app')

@section('title', 'Online Courses LMS')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-graduation-cap text-primary mr-2"></i> Online Courses &amp; LMS</h3>
            <p class="text-muted mb-0 small">Self-paced digital video courses, lesson quizzes, instructor marking queue, and completion certificates.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#addCourseModal">
                <i class="fas fa-plus-circle mr-1"></i> Create New Course
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs matching live demo --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'dashboard' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'dashboard']) }}">
                <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'courses' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'courses']) }}">
                <i class="fas fa-play-circle mr-1"></i> All Courses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'marking' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'marking']) }}">
                <i class="fas fa-check-circle mr-1"></i> Marking Queue <span class="badge badge-warning">5</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'learn' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'learn']) }}">
                <i class="fas fa-user-graduate mr-1"></i> My Learning
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'reports' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'reports']) }}">
                <i class="fas fa-chart-line mr-1"></i> Learner Reports <span class="badge badge-info">14</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'certificates' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'certificates']) }}">
                <i class="fas fa-certificate mr-1"></i> Certificates
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'orders' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'orders']) }}">
                <i class="fas fa-shopping-cart mr-1"></i> Orders
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'guide' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.courses', ['tab' => 'guide']) }}">
                <i class="fas fa-book-open mr-1"></i> Guide
            </a>
        </li>
    </ul>

    {{-- Stats Row matching live demo --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-primary"><i class="fas fa-user-clock fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">1</div>
                        <small class="text-muted">Active this week &middot; 100 enrolments</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-success"><i class="fas fa-user-plus fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">40</div>
                        <small class="text-muted">Enrolled this month</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-info"><i class="fas fa-video fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">{{ count($courses) > 0 ? count($courses) : 6 }}</div>
                        <small class="text-muted">Courses Live on Portal</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-2">
            <div class="card shadow-sm border-0 bg-white">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle p-3 mr-3 bg-light text-warning"><i class="fas fa-award fa-lg"></i></div>
                    <div>
                        <div class="h4 mb-0 font-weight-bold">26%</div>
                        <small class="text-muted">Course Completion Rate</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Courses Grid / Table --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-play-circle text-primary mr-2"></i> All Published Learning Courses</h5>
            <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($courses) }} Active Courses</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-sm align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Course Title</th>
                            <th>Category</th>
                            <th>Instructor</th>
                            <th>Curriculum</th>
                            <th>Duration</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $crs)
                            <tr>
                                <td class="font-weight-bold text-dark">
                                    <i class="fas fa-book-reader text-primary mr-1"></i> {{ $crs->title }}
                                </td>
                                <td><span class="badge badge-light border">{{ $crs->category }}</span></td>
                                <td><i class="fas fa-chalkboard-teacher text-muted mr-1"></i> {{ $crs->instructor_name }}</td>
                                <td>{{ $crs->lessons_count }} Lessons</td>
                                <td>{{ $crs->duration_hours }} Hours</td>
                                <td>
                                    @if($crs->price > 0)
                                        <span class="font-weight-bold text-success">₹{{ number_format($crs->price, 2) }}</span>
                                    @else
                                        <span class="badge badge-success px-2 py-1">Free</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-primary px-2 py-1"><i class="fas fa-globe mr-1"></i> Published</span></td>
                                <td class="text-right">
                                    <button class="btn btn-outline-primary btn-sm"><i class="fas fa-play mr-1"></i> Preview</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No courses listed.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Add Course Modal --}}
<div class="modal fade" id="addCourseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-graduation-cap text-primary mr-1"></i> Create Online Course</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Course Title</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. CBSE Class 10 Physics Mechanics">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Category</label>
                        <select class="form-control form-control-sm">
                            <option>Science</option>
                            <option>Mathematics</option>
                            <option>Computer Science</option>
                            <option>Languages</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Instructor Name</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Teacher name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Price (₹, 0 for Free)</label>
                        <input type="number" class="form-control form-control-sm" value="0">
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Duration (Hours)</label>
                        <input type="number" class="form-control form-control-sm" value="12">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Publish Course</button>
            </div>
        </div>
    </div>
</div>
@endsection

