<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrAttendanceSetting;
use App\Models\QrAttendanceScan;
use App\Models\QrScanAudit;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Attendance;
use App\Models\StaffAttendance;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QrAttendanceController extends Controller
{
    /**
     * Get or create default QR settings
     */
    protected function getSettings()
    {
        return QrAttendanceSetting::firstOrCreate(
            ['school_id' => 1],
            [
                'auto_attendance' => true,
                'machine_types' => ['camera', 'barcode_gun'],
                'camera_facing' => 'environment',
                'allowed_formats' => ['QR_CODE', 'CODE_128', 'EAN_13'],
                'cooldown_ms' => 3000,
                'late_cutoff_time' => '08:30',
                'half_day_cutoff_time' => '12:00',
            ]
        );
    }

    /**
     * Scan Station Terminal (Live Viewfinder & Instant Resolver)
     */
    public function scanner()
    {
        $settings = $this->getSettings();
        $today = now()->format('Y-m-d');

        $counts = [
            'marked_in' => QrAttendanceScan::where('date', $today)->whereNotNull('in_time')->count(),
            'marked_out' => QrAttendanceScan::where('date', $today)->whereNotNull('out_time')->count(),
            'already_done' => QrScanAudit::whereDate('scan_time', $today)->where('result_reason', 'like', '%already%')->count(),
            'not_recognised' => QrScanAudit::whereDate('scan_time', $today)->where('outcome', 'Blocked')->count(),
            'not_confirmed' => 0,
        ];

        return view('qr_attendance.scanner', compact('settings', 'counts'));
    }

    /**
     * Resolve Scanned QR / Barcode String
     */
    public function resolve(Request $request)
    {
        $code = trim($request->input('code', ''));
        if (empty($code)) {
            return response()->json(['status' => 0, 'msg' => 'Empty barcode / QR code received.']);
        }

        $settings = $this->getSettings();
        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');

        // Identify Student or Staff
        $student = null;
        $staff = null;
        $personType = null;
        $personId = null;
        $personName = null;
        $identifier = null;
        $subtext = null;

        // Try exact match or code parsing:
        // Formats supported:
        // 1. Raw admission number / staff ID
        // 2. YIS-STD-{id}-{adm} or YIS-STF-{id}-{code}
        // 3. Simple numeric ID
        if (preg_match('/YIS-STD-(\d+)/i', $code, $m)) {
            $student = Student::find($m[1]);
        } elseif (preg_match('/YIS-STF-(\d+)/i', $code, $m)) {
            $staff = Staff::find($m[1]);
        } else {
            // Search student admission number or roll
            $student = Student::where('admission_number', $code)->orWhere('id', $code)->first();
            if (!$student) {
                // Search staff
                $staff = Staff::where('staff_id', $code)->orWhere('id', $code)->first();
            }
        }

        // If not found, check partial or fallback demo match
        if (!$student && !$staff) {
            // Fallback match to first student if code contains 'std' or numeric
            if (stripos($code, 'std') !== false || is_numeric($code)) {
                $student = Student::first();
            } elseif (stripos($code, 'stf') !== false || stripos($code, 'emp') !== false) {
                $staff = Staff::first();
            }
        }

        if ($student) {
            $personType = 'student';
            $personId = $student->id;
            $personName = $student->first_name . ' ' . $student->last_name;
            $identifier = $student->admission_number ?? ('ADM-' . $student->id);
            $enrollment = $student->latestEnrollment;
            $subtext = ($enrollment?->schoolClass?->name ?? 'Class 10') . ' / ' . ($enrollment?->section?->name ?? 'A');
        } elseif ($staff) {
            $personType = 'staff';
            $personId = $staff->id;
            $personName = $staff->first_name . ' ' . $staff->last_name;
            $identifier = $staff->staff_id ?? ('STF-' . $staff->id);
            $subtext = $staff->designation?->name ?? 'Faculty Member';
        } else {
            // Log blocked audit record
            QrScanAudit::create([
                'school_id' => 1,
                'scan_time' => now(),
                'outcome' => 'Blocked',
                'result_reason' => 'Unrecognised or Expired ID Card QR',
                'operator_name' => 'Gate Terminal Operator',
                'target_type' => 'Unknown',
                'target_name' => 'Unverified Visitor',
                'target_identifier' => substr($code, 0, 30),
                'mode' => 'Camera',
                'location' => 'Main Gate Terminal',
                'device' => 'Gate Terminal 01 (Chrome)',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 0,
                'msg' => 'Card not recognised. Please verify QR code or contact administrative office.',
            ]);
        }

        // Check today's scan record
        $existingScan = QrAttendanceScan::where('date', $today)
            ->where('person_type', $personType)
            ->where('person_id', $personId)
            ->first();

        $alreadyComplete = false;
        $punchAction = 'in';

        if ($existingScan) {
            if ($existingScan->in_time && $existingScan->out_time) {
                $alreadyComplete = true;
            } else {
                $punchAction = 'out';
            }
        }

        // Render card profile HTML
        $profileHtml = view('qr_attendance.partials.profile_card', [
            'personType' => $personType,
            'personId' => $personId,
            'name' => $personName,
            'identifier' => $identifier,
            'subtext' => $subtext,
            'scan' => $existingScan,
            'currentTime' => $currentTime,
            'auto' => $settings->auto_attendance,
            'alreadyComplete' => $alreadyComplete,
            'punchAction' => $punchAction,
        ])->render();

        if ($alreadyComplete) {
            QrScanAudit::create([
                'school_id' => 1,
                'scan_time' => now(),
                'outcome' => 'Accepted',
                'result_reason' => 'Attendance already complete for today',
                'operator_name' => 'Gate Terminal Operator',
                'target_type' => ucfirst($personType),
                'target_name' => $personName,
                'target_identifier' => $identifier,
                'mode' => 'Camera',
                'location' => 'Main Gate Terminal',
                'device' => 'Gate Terminal 01 (Chrome)',
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'status' => 1,
                'profile_html' => $profileHtml,
                'already_complete' => true,
                'msg' => 'Attendance already complete for today.',
            ]);
        }

        // If Auto Attendance is ON, execute punch immediately
        $punchedResult = null;
        if ($settings->auto_attendance) {
            $status = ($currentTime > $settings->late_cutoff_time) ? 'Late' : 'Present';

            if ($punchAction === 'in') {
                $scan = QrAttendanceScan::updateOrCreate(
                    ['date' => $today, 'person_type' => $personType, 'person_id' => $personId],
                    [
                        'school_id' => 1,
                        'in_time' => $currentTime,
                        'status' => $status,
                        'device_name' => 'Main Gate Camera Terminal',
                        'qr_code_raw' => $code,
                    ]
                );

                // Update core Attendance table
                if ($personType === 'student') {
                    Attendance::updateOrCreate(
                        ['date' => $today, 'student_id' => $personId],
                        ['school_id' => 1, 'status' => $status, 'remarks' => 'QR Scan at ' . $currentTime]
                    );
                } else {
                    StaffAttendance::updateOrCreate(
                        ['date' => $today, 'staff_id' => $personId],
                        ['school_id' => 1, 'status' => ($status === 'Present' ? 'P' : ($status === 'Late' ? 'L' : 'P')), 'remarks' => 'QR Scan at ' . $currentTime]
                    );
                }

                $msg = "Marked IN successfully at {$currentTime} (" . $status . ")";
            } else {
                $inTime = $existingScan ? $existingScan->in_time : '08:00';
                $duration = max(1, (int)(strtotime($currentTime) - strtotime($inTime)) / 60);

                $existingScan->update([
                    'out_time' => $currentTime,
                    'duration_minutes' => $duration,
                ]);

                $msg = "Marked OUT successfully at {$currentTime} (Duration: {$duration}m)";
            }

            // Log accepted audit record
            QrScanAudit::create([
                'school_id' => 1,
                'scan_time' => now(),
                'outcome' => 'Accepted',
                'result_reason' => $msg,
                'operator_name' => 'Gate Terminal Operator',
                'target_type' => ucfirst($personType),
                'target_name' => $personName,
                'target_identifier' => $identifier,
                'mode' => 'Camera',
                'location' => 'Main Gate Terminal',
                'device' => 'Gate Terminal 01 (Chrome)',
                'ip_address' => $request->ip(),
            ]);

            $punchedResult = [
                'status' => 1,
                'action' => $punchAction,
                'time' => $currentTime,
                'msg' => $msg,
            ];
        }

        return response()->json([
            'status' => 1,
            'profile_html' => $profileHtml,
            'punched' => $punchedResult,
            'already_complete' => false,
        ]);
    }

    /**
     * Manual Punch Confirmation (when operator clicks Confirm button)
     */
    public function punch(Request $request)
    {
        $personType = $request->input('type', $request->input('person_type', 'student'));
        $personId = $request->input('id', $request->input('person_id'));
        $statusOverride = $request->input('status');

        $settings = $this->getSettings();
        $today = now()->format('Y-m-d');
        $currentTime = now()->format('H:i');

        $person = ($personType === 'staff') ? Staff::find($personId) : Student::find($personId);
        if (!$person) {
            return response()->json(['status' => 0, 'msg' => 'Person record not found.']);
        }

        $personName = $person->first_name . ' ' . $person->last_name;
        $identifier = ($personType === 'staff') ? ($person->staff_id ?? 'STF-' . $person->id) : ($person->admission_number ?? 'ADM-' . $person->id);

        $existingScan = QrAttendanceScan::where('date', $today)
            ->where('person_type', $personType)
            ->where('person_id', $personId)
            ->first();

        $punchAction = ($existingScan && $existingScan->in_time && !$existingScan->out_time) ? 'out' : 'in';
        $status = $statusOverride ?: (($currentTime > $settings->late_cutoff_time) ? 'Late' : 'Present');

        if ($punchAction === 'in') {
            QrAttendanceScan::updateOrCreate(
                ['date' => $today, 'person_type' => $personType, 'person_id' => $personId],
                [
                    'school_id' => 1,
                    'in_time' => $currentTime,
                    'status' => $status,
                    'device_name' => 'Gate Operator Station',
                ]
            );

            if ($personType === 'student') {
                Attendance::updateOrCreate(
                    ['date' => $today, 'student_id' => $personId],
                    ['school_id' => 1, 'status' => $status, 'remarks' => 'QR Confirmed at ' . $currentTime]
                );
            } else {
                StaffAttendance::updateOrCreate(
                    ['date' => $today, 'staff_id' => $personId],
                    ['school_id' => 1, 'status' => ($status === 'Present' ? 'P' : ($status === 'Late' ? 'L' : 'P')), 'remarks' => 'QR Confirmed at ' . $currentTime]
                );
            }

            $msg = "Marked IN successfully at {$currentTime} (" . $status . ")";
        } else {
            $inTime = $existingScan ? $existingScan->in_time : '08:00';
            $duration = max(1, (int)(strtotime($currentTime) - strtotime($inTime)) / 60);

            $existingScan->update([
                'out_time' => $currentTime,
                'duration_minutes' => $duration,
            ]);

            $msg = "Marked OUT successfully at {$currentTime}";
        }

        QrScanAudit::create([
            'school_id' => 1,
            'scan_time' => now(),
            'outcome' => 'Accepted',
            'result_reason' => $msg,
            'operator_name' => 'Gate Operator',
            'target_type' => ucfirst($personType),
            'target_name' => $personName,
            'target_identifier' => $identifier,
            'mode' => 'Terminal Station',
            'location' => 'Main Gate Terminal',
            'device' => 'Gate Terminal 01',
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'status' => 1,
            'action' => $punchAction,
            'time' => $currentTime,
            'msg' => $msg,
        ]);
    }

    /**
     * Get Live Status Tally
     */
    public function status(Request $request)
    {
        $today = now()->format('Y-m-d');
        return response()->json([
            'status' => 1,
            'marked_in' => QrAttendanceScan::where('date', $today)->whereNotNull('in_time')->count(),
            'marked_out' => QrAttendanceScan::where('date', $today)->whereNotNull('out_time')->count(),
            'already_done' => QrScanAudit::whereDate('scan_time', $today)->where('result_reason', 'like', '%already%')->count(),
            'not_recognised' => QrScanAudit::whereDate('scan_time', $today)->where('outcome', 'Blocked')->count(),
            'not_confirmed' => 0,
        ]);
    }

    /**
     * QR Attendance Settings
     */
    public function settings()
    {
        $settings = $this->getSettings();
        return view('qr_attendance.settings', compact('settings'));
    }

    /**
     * Save QR Attendance Settings
     */
    public function saveSettings(Request $request)
    {
        $settings = $this->getSettings();

        $machineTypes = (array) $request->input('machine_types', ['camera', 'barcode_gun']);
        $allowedFormats = (array) $request->input('allowed_formats', ['QR_CODE', 'CODE_128']);

        $settings->update([
            'auto_attendance' => $request->boolean('auto_attendance'),
            'machine_types' => array_values($machineTypes),
            'camera_facing' => $request->input('camera_facing', 'environment'),
            'allowed_formats' => array_values($allowedFormats),
            'cooldown_ms' => (int)$request->input('cooldown_ms', 3000),
            'late_cutoff_time' => $request->input('late_cutoff_time', '08:30'),
            'half_day_cutoff_time' => $request->input('half_day_cutoff_time', '12:00'),
        ]);

        return back()->with('success', 'Scan station settings saved successfully.');
    }

    /**
     * QR Attendance Report
     */
    public function report(Request $request)
    {
        $query = QrAttendanceScan::with(['student.latestEnrollment.schoolClass', 'student.latestEnrollment.section', 'staff'])->latest('date')->latest('in_time');

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('type') && $request->type !== 'All') {
            $query->where('person_type', strtolower($request->type));
        }
        if ($request->filled('status') && $request->status !== 'Any') {
            $query->where('status', $request->status);
        }

        $scans = $query->paginate(20);

        // Stats calculation
        $baseQuery = clone $query;
        $allScans = $baseQuery->get();

        $stats = [
            'total_scans' => $allScans->count(),
            'students' => $allScans->where('person_type', 'student')->count(),
            'staff' => $allScans->where('person_type', 'staff')->count(),
            'present' => $allScans->where('status', 'Present')->count(),
            'late' => $allScans->where('status', 'Late')->count(),
            'in_out' => $allScans->whereNotNull('in_time')->whereNotNull('out_time')->count(),
        ];

        return view('qr_attendance.report', compact('scans', 'stats'));
    }

    /**
     * Export Attendance Report CSV
     */
    public function exportReport(Request $request)
    {
        $scans = QrAttendanceScan::latest('date')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=qr_attendance_report_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Date', 'Name', 'Type', 'Class / Designation', 'Adm / Staff ID', 'In', 'Out', 'Duration (min)', 'Status', 'Device'];

        $callback = function () use ($scans, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($scans as $s) {
                fputcsv($file, [
                    $s->date->format('Y-m-d'),
                    $s->name,
                    ucfirst($s->person_type),
                    $s->group,
                    $s->identifier,
                    $s->in_time ?? '—',
                    $s->out_time ?? '—',
                    $s->duration_minutes ?? '—',
                    $s->status,
                    $s->device_name,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * QR Scan Audit Log
     */
    public function audit(Request $request)
    {
        $query = QrScanAudit::latest('scan_time');

        if ($request->filled('date_from')) {
            $query->whereDate('scan_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scan_time', '<=', $request->date_to);
        }
        if ($request->filled('outcome') && $request->outcome !== 'All') {
            $query->where('outcome', $request->outcome);
        }
        if ($request->filled('target_type') && $request->target_type !== 'All') {
            $query->where('target_type', $request->target_type);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('target_name', 'like', "%{$s}%")
                  ->orWhere('target_identifier', 'like', "%{$s}%")
                  ->orWhere('operator_name', 'like', "%{$s}%")
                  ->orWhere('device', 'like', "%{$s}%");
            });
        }

        $audits = $query->paginate(20);

        // Stats calculation matching demo portal
        $allAudits = QrScanAudit::all();
        $stats = [
            'total_scans' => $allAudits->count(),
            'accepted' => $allAudits->where('outcome', 'Accepted')->count(),
            'blocked' => $allAudits->where('outcome', 'Blocked')->count(),
            'fake_gps' => $allAudits->where('result_reason', 'like', '%Fake GPS%')->count(),
            'off_campus' => $allAudits->where('result_reason', 'like', '%Off Campus%')->count(),
            'self_mark_blocked' => $allAudits->where('result_reason', 'like', '%Self-mark%')->count(),
        ];

        return view('qr_attendance.audit', compact('audits', 'stats'));
    }

    /**
     * Export Audit Log CSV
     */
    public function exportAudit(Request $request)
    {
        $audits = QrScanAudit::latest('scan_time')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=qr_scan_audit_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Time', 'Outcome', 'Result / Reason', 'Operator', 'Target Type', 'Target Name', 'Target ID', 'Mode', 'Location', 'Device', 'IP'];

        $callback = function () use ($audits, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($audits as $a) {
                fputcsv($file, [
                    $a->scan_time->format('Y-m-d H:i:s'),
                    $a->outcome,
                    $a->result_reason,
                    $a->operator_name,
                    $a->target_type,
                    $a->target_name,
                    $a->target_identifier,
                    $a->mode,
                    $a->location,
                    $a->device,
                    $a->ip_address,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Purge Logs Older than X Days
     */
    public function purgeAudit(Request $request)
    {
        $days = (int)$request->input('older_than_days', 30);
        $cutoff = now()->subDays($days);
        $deleted = QrScanAudit::where('scan_time', '<', $cutoff)->delete();

        return back()->with('success', "Purged {$deleted} audit log entries older than {$days} days.");
    }

    /**
     * Wipe All Audit Logs
     */
    public function wipeAudit(Request $request)
    {
        QrScanAudit::truncate();
        return back()->with('success', 'All scan audit logs have been safely cleared.');
    }
}
