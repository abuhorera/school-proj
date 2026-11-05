@extends('layouts.app')

@section('title', 'Certificates')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-certificate text-primary mr-2"></i> Certificates &amp; Documents</h3>
            <p class="text-muted mb-0 small">Transfer Certificates (TC), Bonafide, Character, and Merit Award generation with dynamic merge tags.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#newTemplateModal">
                <i class="fas fa-plus-circle mr-1"></i> New Certificate Template
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab !== 'generate' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.certificates', ['tab' => 'templates']) }}">
                <i class="fas fa-file-alt mr-1"></i> Certificate Templates
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'generate' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.certificates', ['tab' => 'generate']) }}">
                <i class="fas fa-print mr-1"></i> Certificates &amp; Documents (Generate)
            </a>
        </li>
    </ul>

    @if($tab === 'generate')
        {{-- Generate Certificates View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user-graduate text-primary mr-2"></i> Student Document Generator</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Admission No</th>
                                <th>Student Name</th>
                                <th>Class &amp; Section</th>
                                <th>Roll No</th>
                                <th>Select Document</th>
                                <th class="text-right">Generate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $st)
                                <tr>
                                    <td class="font-weight-bold text-primary">{{ $st->admission_number ?? 'ADM-'.$st->id }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $st->first_name }} {{ $st->last_name }}</div>
                                        <small class="text-muted">Father: {{ $st->father_name ?? 'Parent' }}</small>
                                    </td>
                                    <td><span class="badge badge-light border">{{ $st->schoolClass->name ?? 'Class 10' }}</span></td>
                                    <td>{{ $st->roll_number ?? '-' }}</td>
                                    <td>
                                        <select class="form-control form-control-sm" style="max-width: 220px;">
                                            <option>Transfer Certificate (TC)</option>
                                            <option>Character Certificate</option>
                                            <option>Bonafide Certificate</option>
                                            <option>Sports Merit Certificate</option>
                                        </select>
                                    </td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-outline-primary btn-sm px-3" onclick="alert('Generating official digital signed certificate for {{ $st->first_name }}...')">
                                            <i class="fas fa-print mr-1"></i> Print / PDF
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No students available for generation.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Templates List View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-layer-group text-primary mr-2"></i> Active Certificate Layout Templates</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Template Name</th>
                                <th>Category / Type</th>
                                <th>Page Layout</th>
                                <th>Header Text</th>
                                <th>Merge Variables</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $tpl)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-award text-warning mr-2"></i> {{ $tpl->name }}</td>
                                    <td><span class="badge badge-info px-2 py-1">{{ $tpl->certificate_type }}</span></td>
                                    <td><span class="badge badge-light border">{{ $tpl->layout }}</span></td>
                                    <td class="text-muted small" style="max-width: 250px;">{{ $tpl->header_text ?? 'School Affiliation Header' }}</td>
                                    <td>
                                        <span class="badge badge-secondary">{name}</span>
                                        <span class="badge badge-secondary">{class}</span>
                                        <span class="badge badge-secondary">{admission_no}</span>
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-edit mr-1"></i> Edit Design</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No templates configured yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- New Template Modal --}}
<div class="modal fade" id="newTemplateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-certificate text-primary mr-1"></i> Add Certificate Template</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Template Name</label>
                    <input type="text" class="form-control form-control-sm" placeholder="e.g. Annual Sports Merit Certificate">
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Certificate Type</label>
                        <select class="form-control form-control-sm">
                            <option>Transfer Certificate (TC)</option>
                            <option>Character Certificate</option>
                            <option>Bonafide Certificate</option>
                            <option>Merit Award Certificate</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="font-weight-bold small text-muted">Orientation</label>
                        <select class="form-control form-control-sm">
                            <option>Landscape (Horizontal)</option>
                            <option>Portrait (Vertical)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="font-weight-bold small text-muted">Certificate Body (with {student_name}, {class})</label>
                    <textarea class="form-control form-control-sm" rows="3" placeholder="This is to certify that {student_name} of {class}..."></textarea>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-dismiss="modal">Save Template</button>
            </div>
        </div>
    </div>
</div>
@endsection
