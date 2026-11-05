<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BiometricDevice;
use App\Models\BiometricLog;
use App\Models\BiometricMonitoringLog;
use App\Models\BiometricSystemLog;
use App\Models\Student;
use App\Models\Staff;

class BiometricDeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Devices Seed
        $devicesData = [
            [
                'id' => 1,
                'school_id' => 1,
                'name' => 'Main Gate ZKTeco K40',
                'serial_number' => 'YUG-ZK-K40-001',
                'brand' => 'ZKTeco — ADMS / iclock',
                'push_mode' => 'agent',
                'ip_address' => '192.168.1.201',
                'port' => 4370,
                'direction' => 'auto',
                'status' => 'active',
                'online_status' => 'online',
                'is_attendance' => true,
                'is_monitoring' => false,
                'last_sync_at' => now()->subMinutes(12),
                'last_heartbeat_at' => now()->subSeconds(45),
                'firmware_version' => 'Ver 8.0.4.2-20230612',
                'user_count' => 145,
                'fingerprint_count' => 280,
                'face_count' => 0,
                'log_count' => 1842,
                'device_info' => ['mac' => '00:17:61:A4:B2:99', 'platform' => 'ZEM560', 'zk_version' => 10],
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'name' => 'Staff Room eSSL X990',
                'serial_number' => 'GED7253700485',
                'brand' => 'eSSL — ADMS / iclock',
                'push_mode' => 'cloud_push',
                'ip_address' => '192.168.1.202',
                'port' => 4370,
                'direction' => 'auto',
                'status' => 'active',
                'online_status' => 'online',
                'is_attendance' => true,
                'is_monitoring' => false,
                'last_sync_at' => now()->subMinutes(35),
                'last_heartbeat_at' => now()->subMinutes(2),
                'firmware_version' => 'Ver 6.60 Jul 20 2023',
                'user_count' => 48,
                'fingerprint_count' => 96,
                'face_count' => 0,
                'log_count' => 890,
                'device_info' => ['protocol' => 'ADMS iclock', 'cloud_url' => 'http://127.0.0.1:8000/iclock/cdata'],
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'name' => 'IPCAMERA1 — Main Foyer AI Face Camera',
                'serial_number' => 'CAM-88910-AI',
                'brand' => 'Hikvision — JSON webhook',
                'push_mode' => 'camera',
                'ip_address' => '192.168.1.209',
                'port' => 4370,
                'direction' => 'in',
                'status' => 'active',
                'online_status' => 'agent_silent',
                'is_attendance' => true,
                'is_monitoring' => true,
                'last_sync_at' => now()->subHours(4),
                'last_heartbeat_at' => now()->subHours(2),
                'firmware_version' => 'V5.7.3 build 230415',
                'user_count' => 210,
                'fingerprint_count' => 0,
                'face_count' => 210,
                'log_count' => 412,
                'device_info' => ['rtsp' => 'rtsp://admin:pass@192.168.1.209:554/Streaming/Channels/101', 'fps' => 25],
            ],
            [
                'id' => 4,
                'school_id' => 1,
                'name' => 'Senior Block Realtime RS-405',
                'serial_number' => 'RT-RS405-122',
                'brand' => 'Realtime — ADMS / iclock',
                'push_mode' => 'agent',
                'ip_address' => '192.168.1.12',
                'port' => 4370,
                'direction' => 'auto',
                'status' => 'active',
                'online_status' => 'offline',
                'is_attendance' => true,
                'is_monitoring' => false,
                'last_sync_at' => now()->subDays(2),
                'last_heartbeat_at' => now()->subDays(2),
                'firmware_version' => 'Ver 2.8.1-RT',
                'user_count' => 85,
                'fingerprint_count' => 160,
                'face_count' => 0,
                'log_count' => 540,
                'device_info' => ['agent_id' => 'win-agent-01'],
            ],
            [
                'id' => 5,
                'school_id' => 1,
                'name' => 'Hostel & Mess TimeWatch',
                'serial_number' => 'TW-HOSTEL-007',
                'brand' => 'TimeWatch — JSON webhook',
                'push_mode' => 'cloud_push',
                'ip_address' => '192.168.1.215',
                'port' => 4370,
                'direction' => 'auto',
                'status' => 'active',
                'online_status' => 'online',
                'is_attendance' => true,
                'is_monitoring' => false,
                'last_sync_at' => now()->subMinutes(5),
                'last_heartbeat_at' => now()->subSeconds(20),
                'firmware_version' => 'TW-OS-4.1',
                'user_count' => 112,
                'fingerprint_count' => 220,
                'face_count' => 55,
                'log_count' => 970,
                'device_info' => ['mode' => 'Direct Webhook'],
            ],
        ];

        foreach ($devicesData as $dev) {
            BiometricDevice::updateOrCreate(['id' => $dev['id']], $dev);
        }

        $students = Student::take(10)->get();
        $staffMembers = Staff::take(5)->get();

        // 2. Attendance Punches Seed
        BiometricLog::truncate();
        $methods = ['Fingerprint', 'Face', 'RFID Card', 'Fingerprint'];
        $directions = ['Check-IN', 'Check-OUT'];

        // Seed student punches
        foreach ($students as $idx => $st) {
            $inTime = now()->startOfDay()->addHours(8)->addMinutes($idx * 3);
            BiometricLog::create([
                'school_id' => 1,
                'device_id' => ($idx % 2 === 0) ? 1 : 2,
                'person_type' => 'student',
                'person_id' => $st->id,
                'user_device_pin' => $st->admission_number ?? ('ADM-0' . $st->id),
                'punch_time' => $inTime,
                'direction' => 'Check-IN',
                'method' => $methods[$idx % count($methods)],
                'processed' => true,
                'status' => 'synced',
                'raw_data' => json_encode(['verify' => 1, 'in_out' => 0]),
            ]);

            if ($idx % 2 === 0) {
                $outTime = now()->startOfDay()->addHours(15)->addMinutes(15 + $idx * 2);
                BiometricLog::create([
                    'school_id' => 1,
                    'device_id' => 1,
                    'person_type' => 'student',
                    'person_id' => $st->id,
                    'user_device_pin' => $st->admission_number ?? ('ADM-0' . $st->id),
                    'punch_time' => $outTime,
                    'direction' => 'Check-OUT',
                    'method' => 'Fingerprint',
                    'processed' => true,
                    'status' => 'synced',
                    'raw_data' => json_encode(['verify' => 1, 'in_out' => 1]),
                ]);
            }
        }

        // Seed staff punches
        foreach ($staffMembers as $idx => $stf) {
            $inTime = now()->startOfDay()->addHours(7)->addMinutes(45 + $idx * 4);
            BiometricLog::create([
                'school_id' => 1,
                'device_id' => 2,
                'person_type' => 'staff',
                'person_id' => $stf->id,
                'user_device_pin' => $stf->staff_id ?? ('STF-0' . $stf->id),
                'punch_time' => $inTime,
                'direction' => 'Check-IN',
                'method' => ($idx === 1) ? 'Face' : 'Fingerprint',
                'processed' => true,
                'status' => 'synced',
                'raw_data' => json_encode(['verify' => 15, 'in_out' => 0]),
            ]);
        }

        // Add 2 unmapped punches (visitor/temporary cards)
        BiometricLog::create([
            'school_id' => 1,
            'device_id' => 1,
            'person_type' => 'unmapped',
            'person_id' => null,
            'user_device_pin' => '9901',
            'punch_time' => now()->startOfDay()->addHours(9)->addMinutes(14),
            'direction' => 'Check-IN',
            'method' => 'RFID Card',
            'processed' => false,
            'status' => 'pending',
            'raw_data' => json_encode(['card_no' => '0012948192']),
        ]);

        // 3. Face Monitoring AI Events Seed
        BiometricMonitoringLog::truncate();
        $sampleDetections = [
            [
                'person_type' => 'student',
                'person_id' => $students[0]->id ?? 1,
                'person_name' => ($students[0]?->first_name . ' ' . $students[0]?->last_name) ?? 'Aryan Kumar',
                'snapshot_url' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?auto=format&fit=crop&w=120&q=80',
                'camera_name' => 'IPCAMERA1 — Main Foyer',
                'detection_type' => 'Face',
                'confidence' => 99.45,
                'liveness' => 'Verified',
                'detected_at' => now()->subMinutes(18),
            ],
            [
                'person_type' => 'student',
                'person_id' => $students[1]->id ?? 2,
                'person_name' => ($students[1]?->first_name . ' ' . $students[1]?->last_name) ?? 'Zara Sheikh',
                'snapshot_url' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=120&q=80',
                'camera_name' => 'IPCAMERA1 — Main Foyer',
                'detection_type' => 'Face',
                'confidence' => 98.80,
                'liveness' => 'Verified',
                'detected_at' => now()->subMinutes(32),
            ],
            [
                'person_type' => 'staff',
                'person_id' => $staffMembers[0]->id ?? 1,
                'person_name' => ($staffMembers[0]?->first_name . ' ' . $staffMembers[0]?->last_name) ?? 'Prof. Rajesh Sharma',
                'snapshot_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80',
                'camera_name' => 'IPCAMERA1 — Main Foyer',
                'detection_type' => 'Face',
                'confidence' => 99.85,
                'liveness' => 'Verified',
                'detected_at' => now()->subHours(1),
            ],
            [
                'person_type' => 'unknown',
                'person_id' => null,
                'person_name' => 'Unidentified Visitor (Guest)',
                'snapshot_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80',
                'camera_name' => 'IPCAMERA1 — Main Foyer',
                'detection_type' => 'Face',
                'confidence' => 84.20,
                'liveness' => 'Real',
                'detected_at' => now()->subHours(2),
            ],
        ];

        foreach ($sampleDetections as $det) {
            BiometricMonitoringLog::create(array_merge($det, ['school_id' => 1, 'device_id' => 3]));
        }

        // 4. Windows Agent & Sync Engine Debug System Logs Seed
        BiometricSystemLog::truncate();
        $sysLogs = [
            ['level' => 'INFO', 'message' => 'Agent v2.4 started. Scanning local network for ZK devices on port 4370.', 'time' => now()->subHours(5)],
            ['level' => 'INFO', 'message' => 'Connecting to device 192.168.1.201:4370 (Main Gate ZKTeco K40) - Connected OK.', 'time' => now()->subHours(5)->addMinutes(1)],
            ['level' => 'INFO', 'message' => 'Data Sync: Pulled 18 attendance records from device 192.168.1.201. Synchronizing with cloud.', 'time' => now()->subHours(4)],
            ['level' => 'INFO', 'message' => 'Heartbeat broadcast sent to cloud orchestrator. Latency: 42ms.', 'time' => now()->subHours(3)],
            ['level' => 'WARNING', 'message' => 'Device 192.168.1.12:4370 socket timeout. Retrying in 30 seconds.', 'time' => now()->subHours(2)],
            ['level' => 'ERROR', 'message' => 'Data Sync: Skipping unreachable device 192.168.1.12 (Host unreachable).', 'time' => now()->subHours(2)->addMinutes(1)],
            ['level' => 'INFO', 'message' => 'Cloud Push ADMS packet received from 192.168.1.202 (eSSL X990). Table ATTLOG: 6 records inserted.', 'time' => now()->subHours(1)],
            ['level' => 'INFO', 'message' => 'Data Sync: Routine health check passed for 3 of 4 active hardware endpoints.', 'time' => now()->subMinutes(30)],
        ];

        foreach ($sysLogs as $sl) {
            BiometricSystemLog::create([
                'school_id' => 1,
                'device_id' => 1,
                'level' => $sl['level'],
                'message' => $sl['message'],
                'logged_at' => $sl['time'],
            ]);
        }
    }
}

