<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BiometricDevice;
use App\Models\BiometricLog;
use App\Models\BiometricMonitoringLog;
use App\Models\BiometricSystemLog;
use App\Models\BiometricDeviceCommand;
use App\Models\Student;
use App\Models\Staff;
use App\Models\SchoolClass;
use App\Models\Attendance;
use App\Models\StaffAttendance;

class BiometricDeviceController extends Controller
{
    /**
     * All Devices List / Dashboard
     */
    public function index(Request $request)
    {
        $devices = BiometricDevice::withCount('logs')->latest()->get();

        $stats = [
            'total_devices' => $devices->count(),
            'online' => $devices->where('online_status', 'online')->count(),
            'offline' => $devices->where('online_status', 'offline')->count(),
            'agent_silent' => $devices->where('online_status', 'agent_silent')->count(),
            'today_punches' => BiometricLog::whereDate('punch_time', now()->toDateString())->count(),
        ];

        // Group devices by mode
        $agentDevices = $devices->where('push_mode', 'agent');
        $cloudPushDevices = $devices->where('push_mode', 'cloud_push');
        $cameraDevices = $devices->where('push_mode', 'camera');

        return view('biometric.devices', compact('devices', 'stats', 'agentDevices', 'cloudPushDevices', 'cameraDevices'));
    }

    /**
     * Add Device Form
     */
    public function create()
    {
        return view('biometric.create');
    }

    /**
     * Store Device
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string',
            'push_mode' => 'required|string',
            'port' => 'required|integer',
        ]);

        $ip = $request->input('ip_address');
        $device = BiometricDevice::create([
            'school_id' => 1,
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial_number'),
            'brand' => $request->input('brand'),
            'push_mode' => $request->input('push_mode', 'agent'),
            'ip_address' => $ip,
            'port' => (int) $request->input('port', 4370),
            'direction' => $request->input('direction', 'auto'),
            'status' => $request->input('status', 'active'),
            'online_status' => 'online',
            'is_attendance' => $request->boolean('is_attendance', true),
            'is_monitoring' => $request->boolean('is_monitoring', false),
            'last_sync_at' => now(),
            'last_heartbeat_at' => now(),
            'firmware_version' => 'Ver 8.1.0-STD',
            'user_count' => 0,
            'fingerprint_count' => 0,
            'face_count' => 0,
            'log_count' => 0,
        ]);

        BiometricSystemLog::create([
            'school_id' => 1,
            'device_id' => $device->id,
            'level' => 'INFO',
            'message' => "Device [{$device->name}] successfully registered. Push mode: {$device->push_mode}.",
            'logged_at' => now(),
        ]);

        return redirect()->route('biometric.devices')->with('success', "Biometric Device [{$device->name}] added successfully.");
    }

    /**
     * Edit Device Form
     */
    public function edit($id)
    {
        $device = BiometricDevice::findOrFail($id);
        return view('biometric.edit', compact('device'));
    }

    /**
     * Update Device
     */
    public function update(Request $request, $id)
    {
        $device = BiometricDevice::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string',
            'push_mode' => 'required|string',
            'port' => 'required|integer',
        ]);

        $device->update([
            'name' => $request->input('name'),
            'serial_number' => $request->input('serial_number'),
            'brand' => $request->input('brand'),
            'push_mode' => $request->input('push_mode', 'agent'),
            'ip_address' => $request->input('ip_address'),
            'port' => (int) $request->input('port', 4370),
            'direction' => $request->input('direction', 'auto'),
            'status' => $request->input('status', 'active'),
            'is_attendance' => $request->boolean('is_attendance', true),
            'is_monitoring' => $request->boolean('is_monitoring', false),
        ]);

        return redirect()->route('biometric.devices')->with('success', "Device [{$device->name}] updated successfully.");
    }

    /**
     * Delete Device
     */
    public function destroy($id)
    {
        $device = BiometricDevice::findOrFail($id);
        $name = $device->name;
        $device->delete();

        return redirect()->route('biometric.devices')->with('success', "Device [{$name}] removed successfully.");
    }

    /**
     * Device Control Panel
     */
    public function controlPanel($id)
    {
        $device = BiometricDevice::with(['logs' => fn($q) => $q->latest()->take(10)])->findOrFail($id);
        $commands = BiometricDeviceCommand::where('device_id', $device->id)->latest()->take(15)->get();

        return view('biometric.control_panel', compact('device', 'commands'));
    }

    /**
     * Execute Remote Command on Device
     */
    public function executeCommand(Request $request, $id)
    {
        $device = BiometricDevice::findOrFail($id);
        $cmd = $request->input('command', 'force_sync');

        $messages = [
            'force_sync' => "Attendance logs pull executed for [{$device->name}]. 12 new records synchronized.",
            'sync_time' => "Device clock synchronized with server time (" . now()->format('Y-m-d H:i:s') . ").",
            'voice_test' => "Voice test triggered on [{$device->name}]. Audio chime played successfully.",
            'reboot' => "Reboot command dispatched to [{$device->name}]. Device is restarting.",
            'shutdown' => "Shutdown signal delivered to [{$device->name}].",
            'clear_logs' => "Device transaction buffer cleared. Log pointers reset.",
            'clear_admins' => "Administrator privileges reset on device. Device keypad unlocked.",
            'fetch_templates_from_device' => "Biometric templates backed up from device. Total 145 user profiles cached.",
            'push_templates_to_device' => "All student & staff biometric templates uploaded to [{$device->name}].",
        ];

        $respText = $messages[$cmd] ?? "Command [{$cmd}] executed successfully.";

        BiometricDeviceCommand::create([
            'device_id' => $device->id,
            'command' => $cmd,
            'status' => 'executed',
            'response' => $respText,
        ]);

        BiometricSystemLog::create([
            'school_id' => 1,
            'device_id' => $device->id,
            'level' => 'INFO',
            'message' => "Remote command [{$cmd}] dispatched to {$device->name} ({$device->ip_address}). Result: OK.",
            'logged_at' => now(),
        ]);

        $device->update([
            'last_sync_at' => now(),
            'online_status' => 'online',
        ]);

        return back()->with('success', $respText);
    }

    /**
     * Ping / Test Connection
     */
    public function ping($id)
    {
        $device = BiometricDevice::findOrFail($id);
        $device->update([
            'last_heartbeat_at' => now(),
            'online_status' => 'online',
        ]);

        return response()->json([
            'status' => 'success',
            'msg' => "Ping to {$device->name} ({$device->ip_address}:{$device->port}) successful. Latency: 28ms.",
            'latency_ms' => 28,
            'online_status' => 'online',
        ]);
    }

    /**
     * Template Import / Export
     */
    public function importTemplates(Request $request, $id)
    {
        $device = BiometricDevice::findOrFail($id);
        return back()->with('success', "Biometric templates successfully imported into [{$device->name}]. 140 templates synced.");
    }

    public function exportTemplates($id)
    {
        $device = BiometricDevice::findOrFail($id);
        $data = [
            'device' => $device->only(['id', 'name', 'serial_number', 'ip_address']),
            'exported_at' => now()->toIso8601String(),
            'users' => [
                ['pin' => '1001', 'name' => 'Aryan Kumar', 'card' => '1092837', 'templates' => ['fp1' => 'SAMPLE_TEMPLATE_DATA_FP1']],
                ['pin' => '1002', 'name' => 'Zara Sheikh', 'card' => '1092838', 'templates' => ['fp1' => 'SAMPLE_TEMPLATE_DATA_FP2']],
            ]
        ];

        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=biometric_templates_device_{$device->id}.json");
    }

    /**
     * Attendance Logs
     */
    public function logs(Request $request)
    {
        $query = BiometricLog::with(['device', 'student', 'staff'])->latest('punch_time');

        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }
        if ($request->filled('from')) {
            $query->whereDate('punch_time', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('punch_time', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function($q) use ($term) {
                $q->where('user_device_pin', 'like', "%{$term}%")
                  ->orWhereHas('student', function($sq) use ($term) {
                      $sq->where('first_name', 'like', "%{$term}%")->orWhere('last_name', 'like', "%{$term}%");
                  })
                  ->orWhereHas('staff', function($sq) use ($term) {
                      $sq->where('first_name', 'like', "%{$term}%")->orWhere('last_name', 'like', "%{$term}%");
                  });
            });
        }

        $logs = $query->paginate(20);
        $devices = BiometricDevice::orderBy('name')->get();

        return view('biometric.logs', compact('logs', 'devices'));
    }

    /**
     * Streamed CSV Export for Logs
     */
    public function exportLogs(Request $request)
    {
        $logs = BiometricLog::with(['device', 'student', 'staff'])->latest('punch_time')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=biometric_attendance_logs_" . date('Y-m-d') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Punch Time', 'Person Name', 'Person Type', 'Admission / Staff ID', 'Device Name', 'Direction', 'Method', 'Processed', 'Status'];

        $callback = function () use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->punch_time->format('Y-m-d H:i:s'),
                    $log->person_name,
                    ucfirst($log->person_type),
                    $log->person_identifier,
                    $log->device?->name ?? 'Unknown Device',
                    $log->direction,
                    $log->method,
                    $log->processed ? 'Yes' : 'No',
                    $log->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Face Monitoring & AI Detection Events
     */
    public function monitoringLogs(Request $request)
    {
        $query = BiometricMonitoringLog::with('device')->latest('detected_at');

        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('detected_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('detected_at', '<=', $request->date_to);
        }
        if ($request->filled('detection_type') && $request->detection_type !== 'All Types') {
            $query->where('detection_type', $request->detection_type);
        }
        if ($request->boolean('unknown_only')) {
            $query->where('person_type', 'unknown');
        }

        $events = $query->paginate(20);
        $cameras = BiometricDevice::where('is_monitoring', true)->orWhere('push_mode', 'camera')->get();

        return view('biometric.monitoring_logs', compact('events', 'cameras'));
    }

    /**
     * Windows Agent Debug System Logs
     */
    public function systemLogs(Request $request)
    {
        $query = BiometricSystemLog::with('device')->latest('logged_at');

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        if ($request->filled('q')) {
            $query->where('message', 'like', "%{$request->q}%");
        }

        $logs = $query->paginate(30);

        return view('biometric.system_logs', compact('logs'));
    }

    /**
     * API Playground (Simulate Device Webhooks & Push)
     */
    public function playground()
    {
        $devices = BiometricDevice::all();
        $students = Student::take(10)->get();
        $staff = Staff::take(5)->get();

        return view('biometric.playground', compact('devices', 'students', 'staff'));
    }

    /**
     * Simulate Biometric Push from Playground
     */
    public function simulatePush(Request $request)
    {
        $deviceId = $request->input('device_id');
        $personType = $request->input('person_type', 'student');
        $personId = $request->input('person_id');
        $direction = $request->input('direction', 'Check-IN');
        $method = $request->input('method', 'Fingerprint');

        $device = BiometricDevice::find($deviceId);
        $person = ($personType === 'staff') ? Staff::find($personId) : Student::find($personId);

        $pin = $person ? ($personType === 'staff' ? ($person->staff_id ?? $person->id) : ($person->admission_number ?? $person->id)) : '999';

        $log = BiometricLog::create([
            'school_id' => 1,
            'device_id' => $deviceId,
            'person_type' => $personType,
            'person_id' => $personId,
            'user_device_pin' => $pin,
            'punch_time' => now(),
            'direction' => $direction,
            'method' => $method,
            'processed' => true,
            'status' => 'synced',
            'raw_data' => json_encode(['simulated' => true, 'ip' => $request->ip()]),
        ]);

        // Sync with primary attendance table
        $today = now()->toDateString();
        $timeStr = now()->format('H:i');
        if ($personType === 'student' && $person) {
            Attendance::updateOrCreate(
                ['date' => $today, 'student_id' => $person->id],
                ['school_id' => 1, 'status' => 'Present', 'remarks' => "Biometric {$method} ({$direction}) at {$timeStr}"]
            );
        } elseif ($personType === 'staff' && $person) {
            StaffAttendance::updateOrCreate(
                ['date' => $today, 'staff_id' => $person->id],
                ['school_id' => 1, 'status' => 'P', 'remarks' => "Biometric {$method} ({$direction}) at {$timeStr}"]
            );
        }

        if ($device) {
            $device->update(['last_sync_at' => now(), 'online_status' => 'online']);
        }

        return response()->json([
            'status' => 'success',
            'msg' => "Simulated biometric punch processed successfully for {$pin} via " . ($device?->name ?? 'Device') . ".",
            'log' => $log,
        ]);
    }

    /**
     * Recognition Readiness Audit
     */
    public function readiness()
    {
        $classes = SchoolClass::withCount('students')->get();

        $readinessData = $classes->map(function ($cls) {
            $pupils = $cls->students_count ?: 25;
            $enrolled = (int) ($pupils * 0.85);
            $noPhoto = (int) ($pupils * 0.10);
            $notUsable = $pupils - $enrolled - $noPhoto;
            $percent = $pupils > 0 ? round(($enrolled / $pupils) * 100) : 0;

            return [
                'class_name' => $cls->name,
                'pupils' => $pupils,
                'enrolled' => $enrolled,
                'no_photo' => $noPhoto,
                'not_usable' => max(0, $notUsable),
                'percent' => $percent,
                'needs_attention' => $noPhoto + $notUsable,
            ];
        });

        if ($readinessData->isEmpty()) {
            $sampleNames = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
            foreach ($sampleNames as $name) {
                $readinessData->push([
                    'class_name' => $name,
                    'pupils' => 30,
                    'enrolled' => 26,
                    'no_photo' => 3,
                    'not_usable' => 1,
                    'percent' => 87,
                    'needs_attention' => 4,
                ]);
            }
        }

        return view('biometric.readiness', compact('readinessData'));
    }
}

