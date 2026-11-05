<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QrAttendanceSetting;
use App\Models\QrAttendanceScan;
use App\Models\QrScanAudit;
use App\Models\Student;
use App\Models\Staff;

class QrCodeAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Settings
        QrAttendanceSetting::updateOrCreate(
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

        $students = Student::take(8)->get();
        $staffMembers = Staff::take(4)->get();
        $today = now()->format('Y-m-d');

        // 2. Student attendance scans today
        foreach ($students as $idx => $st) {
            $inHour = ($idx % 3 === 0) ? '08:42' : '08:15';
            $status = ($idx % 3 === 0) ? 'Late' : 'Present';
            $outTime = ($idx % 2 === 0) ? '15:30' : null;
            $duration = $outTime ? 435 : null;

            QrAttendanceScan::updateOrCreate(
                ['date' => $today, 'person_type' => 'student', 'person_id' => $st->id],
                [
                    'school_id' => 1,
                    'in_time' => $inHour,
                    'out_time' => $outTime,
                    'duration_minutes' => $duration,
                    'status' => $status,
                    'device_name' => 'Main Gate Camera Kiosk #01',
                    'qr_code_raw' => 'YIS-STD-' . $st->id . '-' . ($st->admission_number ?? '100' . $st->id),
                ]
            );
        }

        // 3. Staff attendance scans today
        foreach ($staffMembers as $idx => $stf) {
            $inHour = ($idx === 1) ? '08:35' : '07:55';
            $status = ($idx === 1) ? 'Late' : 'Present';
            $outTime = '16:00';

            QrAttendanceScan::updateOrCreate(
                ['date' => $today, 'person_type' => 'staff', 'person_id' => $stf->id],
                [
                    'school_id' => 1,
                    'in_time' => $inHour,
                    'out_time' => $outTime,
                    'duration_minutes' => 485,
                    'status' => $status,
                    'device_name' => 'Faculty Reception Barcode Terminal',
                    'qr_code_raw' => 'YIS-STF-' . $stf->id . '-' . ($stf->staff_id ?? 'EMP-' . $stf->id),
                ]
            );
        }

        // 4. Populate 19 audit log decisions matching demo metrics (18 Accepted, 1 Blocked)
        QrScanAudit::truncate();

        for ($i = 1; $i <= 18; $i++) {
            $isStudent = ($i % 3 !== 0);
            $target = $isStudent ? ($students[$i % count($students)] ?? null) : ($staffMembers[$i % count($staffMembers)] ?? null);
            $tName = $target ? ($target->first_name . ' ' . $target->last_name) : ("Candidate #{$i}");
            $tIdent = $target ? ($isStudent ? ($target->admission_number ?? "ADM-{$target->id}") : ($target->staff_id ?? "STF-{$target->id}")) : "CODE-{$i}";
            
            $action = ($i % 4 === 0) ? "Marked OUT at " . now()->subMinutes(18 - $i)->format('H:i') : "Marked IN at 08:" . sprintf('%02d', 10 + $i);

            QrScanAudit::create([
                'school_id' => 1,
                'scan_time' => now()->subMinutes(rand(10, 480)),
                'outcome' => 'Accepted',
                'result_reason' => $action,
                'operator_name' => 'Gate Officer (Kiosk 01)',
                'target_type' => $isStudent ? 'Student' : 'Staff',
                'target_name' => $tName,
                'target_identifier' => $tIdent,
                'mode' => ($i % 2 === 0) ? 'Camera' : 'Barcode Gun',
                'location' => ($i % 3 === 0) ? 'Campus South Gate' : 'Main Gate Terminal',
                'device' => 'Gate Terminal 01 (Chrome/Android)',
                'ip_address' => '192.168.1.10' . ($i % 5),
                'latitude' => 28.613939,
                'longitude' => 77.209021,
            ]);
        }

        // 1 Blocked Audit Record
        QrScanAudit::create([
            'school_id' => 1,
            'scan_time' => now()->subMinutes(45),
            'outcome' => 'Blocked',
            'result_reason' => 'Unrecognised or Expired ID Card QR',
            'operator_name' => 'Gate Officer (Kiosk 01)',
            'target_type' => 'Unknown',
            'target_name' => 'Unverified Visitor / Defective QR',
            'target_identifier' => 'QR-ERR-9921',
            'mode' => 'Camera',
            'location' => 'Main Gate Terminal',
            'device' => 'Gate Terminal 01 (Chrome/Android)',
            'ip_address' => '192.168.1.101',
            'latitude' => 28.613939,
            'longitude' => 77.209021,
        ]);
    }
}

