@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Enter Marks &mdash; {{ $assessment->title }}</h3>
                <p class="text-muted small mb-0">{{ $assessment->class_section_text }} &bull; Max Marks: {{ (int)$assessment->total_marks }} &bull; Sitting Date: {{ $occurrence->sitting_date->format('d M Y') }}</p>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <a href="{{ route('assessment.occurrences.marksheet', $occurrence->id) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                    <i class="fas fa-print mr-1"></i> Printable Marksheet
                </a>
                <a href="{{ route('assessment.assessments.show', $assessment->id) }}" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Assessment
                </a>
            </div>
        </div>

        @include('assessments.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <form action="{{ route('assessment.occurrences.marks.save', $occurrence->id) }}" method="POST">
            @csrf
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="card-title font-weight-bold text-dark mb-0">
                            <i class="fas fa-clipboard-check text-emerald-600 mr-1.5"></i> Student Marks Matrix
                        </h5>
                        <small class="text-muted">Enter marks obtained out of {{ (int)$assessment->total_marks }} or flag candidate as absent</small>
                    </div>
                    <div class="mt-2 mt-md-0">
                        <button type="submit" class="btn btn-emerald text-white btn-sm px-4 font-weight-bold" style="background-color: #059669;">
                            <i class="fas fa-save mr-1"></i> Save Marks
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="pl-4" style="width: 45%;">Student</th>
                                    <th style="width: 35%;">Marks / {{ (int)$assessment->total_marks }}</th>
                                    <th class="pr-4 text-center" style="width: 20%;">Absent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $st)
                                    @php
                                        $markRecord = $existingMarks->get($st->id);
                                        $isAbsent = $markRecord ? $markRecord->is_absent : false;
                                        $val = $markRecord && !$isAbsent ? $markRecord->marks_obtained : '';
                                    @endphp
                                    <tr>
                                        <td class="pl-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle mr-2.5 bg-light rounded-circle d-flex align-items-center justify-content-center text-muted border" style="width: 36px; height: 36px;">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                                <div>
                                                    <strong class="text-dark d-block">{{ $st->first_name }} {{ $st->last_name }}</strong>
                                                    <small class="text-muted">
                                                        Roll: {{ $st->roll_no ?? '-' }} &bull; Adm: <code>{{ $st->admission_number ?? ('ADM-' . $st->id) }}</code>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group input-group-sm" style="max-width: 180px;">
                                                <input type="number" 
                                                       name="marks[{{ $st->id }}]" 
                                                       id="mark_input_{{ $st->id }}"
                                                       class="form-control font-weight-bold text-dark" 
                                                       value="{{ $val }}" 
                                                       min="0" 
                                                       max="{{ (int)$assessment->total_marks }}" 
                                                       step="0.5" 
                                                       placeholder="0 - {{ (int)$assessment->total_marks }}"
                                                       {{ $isAbsent ? 'disabled' : '' }}>
                                                <div class="input-group-append">
                                                    <span class="input-group-text text-muted">/ {{ (int)$assessment->total_marks }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="pr-4 text-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" 
                                                       class="custom-control-input absent-toggle" 
                                                       id="absent_{{ $st->id }}" 
                                                       name="absent[{{ $st->id }}]" 
                                                       value="1" 
                                                       {{ $isAbsent ? 'checked' : '' }}
                                                       onchange="toggleAbsent({{ $st->id }}, this.checked)">
                                                <label class="custom-control-label small text-muted font-weight-normal" for="absent_{{ $st->id }}">
                                                    Mark Absent
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">No students enrolled in this class.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-top py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Showing {{ count($students) }} students</span>
                    <button type="submit" class="btn btn-emerald text-white btn-sm px-4 font-weight-bold" style="background-color: #059669;">
                        <i class="fas fa-save mr-1"></i> Save Marks
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function toggleAbsent(studentId, isChecked) {
    const input = document.getElementById('mark_input_' + studentId);
    if (isChecked) {
        input.value = '';
        input.disabled = true;
    } else {
        input.disabled = false;
        input.focus();
    }
}
</script>
@endsection

