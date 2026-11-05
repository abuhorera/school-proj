<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assessment Marksheet &mdash; {{ $occurrence->assessment->title }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Nunito', sans-serif; background: #fff; color: #1e293b; padding: 25px; }
        .marksheet-header { border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 20px; }
        .table-marksheet th { background-color: #f1f5f9; text-transform: uppercase; font-size: 11px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="no-print d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
            <div>
                <strong>Printable Assessment Ledger</strong> &bull; {{ $occurrence->assessment->title }}
            </div>
            <div>
                <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="fas fa-print mr-1"></i> Print / Save as PDF</button>
                <button onclick="window.close()" class="btn btn-secondary btn-sm ml-1">Close</button>
            </div>
        </div>

        <div class="marksheet-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold text-dark mb-0">YUG INTERNATIONAL SCHOOL</h4>
                <p class="text-muted small mb-0">Classroom Assessment Evaluation Ledger &bull; Academic Session 2026-2027</p>
            </div>
            <div class="text-right">
                <span class="badge badge-success px-2.5 py-1 text-uppercase">{{ $occurrence->status }}</span>
                <div class="small text-muted font-monospace mt-1">Date: {{ $occurrence->sitting_date->format('d M Y') }}</div>
            </div>
        </div>

        <!-- Assessment Meta Info -->
        <div class="card mb-4 border bg-light">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-3"><strong>Title:</strong> {{ $occurrence->assessment->title }}</div>
                    <div class="col-3"><strong>Subject:</strong> {{ $occurrence->assessment->subject?->name }}</div>
                    <div class="col-3"><strong>Class:</strong> {{ $occurrence->assessment->class_section_text }}</div>
                    <div class="col-3"><strong>Max / Pass:</strong> {{ (int)$occurrence->assessment->total_marks }} / {{ (int)$occurrence->assessment->passing_marks }}</div>
                </div>
            </div>
        </div>

        <!-- Marks Table -->
        <table class="table table-bordered table-sm table-marksheet">
            <thead>
                <tr>
                    <th style="width: 60px;" class="text-center">#</th>
                    <th>Roll / Adm</th>
                    <th>Student Name</th>
                    <th class="text-center" style="width: 140px;">Marks / {{ (int)$occurrence->assessment->total_marks }}</th>
                    <th class="text-center" style="width: 100px;">Percentage</th>
                    <th class="text-center" style="width: 90px;">Grade</th>
                    <th class="text-center" style="width: 100px;">Result</th>
                </tr>
            </thead>
            <tbody>
                @forelse($occurrence->marks as $idx => $m)
                    <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $idx + 1 }}</td>
                        <td>
                            <code>{{ $m->student?->admission_number ?? ('ADM-' . $m->student_id) }}</code>
                        </td>
                        <td><strong>{{ $m->student?->first_name }} {{ $m->student?->last_name }}</strong></td>
                        <td class="text-center font-weight-bold">
                            @if($m->is_absent)
                                <span class="text-danger">AB</span>
                            @else
                                {{ $m->marks_obtained !== null ? (float)$m->marks_obtained : '-' }}
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $m->percentage !== null ? $m->percentage . '%' : '-' }}
                        </td>
                        <td class="text-center">
                            <span class="badge badge-{{ $m->grade_badge['color'] }}">{{ $m->grade_badge['grade'] }}</span>
                        </td>
                        <td class="text-center">
                            @if($m->is_absent)
                                <span class="text-muted">Absent</span>
                            @elseif($m->marks_obtained >= $occurrence->assessment->passing_marks)
                                <span class="text-success font-weight-bold">PASS</span>
                            @else
                                <span class="text-danger font-weight-bold">FAIL</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No marks recorded for this sitting.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary Footer -->
        <div class="row mt-4 pt-3 border-top">
            <div class="col-md-6">
                <p class="small text-muted mb-0">Generated automatically by Multi-School ERP Classroom Assessment Module.</p>
            </div>
            <div class="col-md-6 text-right">
                <span class="small font-weight-bold text-dark mr-4">Teacher Signature: __________________</span>
                <span class="small font-weight-bold text-dark">Principal / HOD: __________________</span>
            </div>
        </div>
    </div>
</body>
</html>

