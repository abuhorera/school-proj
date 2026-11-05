<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PtmMeeting;
use App\Models\PtmAttendance;
use App\Models\PtmFollowup;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Staff;
use Carbon\Carbon;

class PtmController extends Controller
{
    /**
     * 1. PTM Dashboard
     */
    public function dashboard(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $meetingsThisSession = PtmMeeting::where('school_id', $schoolId)->count();
        $upcomingMeetings = PtmMeeting::where('school_id', $schoolId)
            ->where('meeting_date', '>=', Carbon::today()->toDateString())
            ->where('status', '!=', 'Cancelled')
            ->count();
        $completedMeetings = PtmMeeting::where('school_id', $schoolId)
            ->where('status', 'Completed')
            ->count();

        $openFollowups = PtmFollowup::where('school_id', $schoolId)->where('status', 'Open')->count();
        $inProgressFollowups = PtmFollowup::where('school_id', $schoolId)->where('status', 'In progress')->count();

        $totalAttendances = PtmAttendance::where('school_id', $schoolId)->count();
        $presentAttendances = PtmAttendance::where('school_id', $schoolId)->where('attendance_status', 'Present')->count();
        $attendanceRate = $totalAttendances > 0 ? round(($presentAttendances / $totalAttendances) * 100, 1) : 0;

        $recentMeetings = PtmMeeting::where('school_id', $schoolId)
            ->with(['schoolClass', 'section'])
            ->withCount([
                'attendances',
                'attendances as present_count' => function ($q) {
                    $q->where('attendance_status', 'Present');
                },
                'followups'
            ])
            ->latest('meeting_date')
            ->take(6)
            ->get();

        $upcomingList = PtmMeeting::where('school_id', $schoolId)
            ->where('meeting_date', '>=', Carbon::today()->toDateString())
            ->orderBy('meeting_date')
            ->take(4)
            ->get();

        return view('ptm.dashboard', compact(
            'school',
            'meetingsThisSession',
            'upcomingMeetings',
            'completedMeetings',
            'openFollowups',
            'inProgressFollowups',
            'attendanceRate',
            'recentMeetings',
            'upcomingList'
        ));
    }

    /**
     * 2. Schedule Meetings Master
     */
    public function meetings(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $status = $request->get('status');
        $classId = $request->get('class_id');
        $from = $request->get('from');
        $to = $request->get('to');

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        $meetings = PtmMeeting::where('school_id', $schoolId)
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($classId, function ($q) use ($classId) {
                $q->where(function ($sq) use ($classId) {
                    $sq->where('school_class_id', $classId)
                       ->orWhere('class_id', $classId);
                });
            })
            ->when($from, function ($q) use ($from) {
                $q->whereDate('meeting_date', '>=', $from);
            })
            ->when($to, function ($q) use ($to) {
                $q->whereDate('meeting_date', '<=', $to);
            })
            ->with(['schoolClass', 'section'])
            ->withCount([
                'attendances',
                'attendances as present_count' => function ($q) {
                    $q->where('attendance_status', 'Present');
                }
            ])
            ->latest('meeting_date')
            ->get();

        return view('ptm.meetings', compact('school', 'meetings', 'classes', 'status', 'classId', 'from', 'to'));
    }

    /**
     * Create PTM Meeting Form
     */
    public function create()
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();

        return view('ptm.create', compact('school', 'classes', 'sections'));
    }

    /**
     * Store new PTM Meeting
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'mode' => 'required|string|in:in_person,online,hybrid',
            'venue' => 'nullable|string|max:255',
            'online_link' => 'nullable|url|max:255',
            'scope' => 'required|string|in:whole_school,class,section',
            'school_class_id' => 'nullable|integer',
            'section_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'generate_roster' => 'nullable|boolean',
        ]);

        $schoolId = 1;

        $meeting = PtmMeeting::create([
            'school_id' => $schoolId,
            'title' => $validated['title'],
            'meeting_date' => $validated['meeting_date'],
            'start_time' => $validated['start_time'] ?? '09:00',
            'end_time' => $validated['end_time'] ?? '13:00',
            'mode' => $validated['mode'],
            'venue' => $validated['venue'] ?? ($validated['mode'] == 'online' ? 'Online Video Conference' : 'School Campus'),
            'online_link' => $validated['online_link'] ?? null,
            'scope' => $validated['scope'],
            'class_id' => $validated['school_class_id'] ?? null,
            'school_class_id' => $validated['school_class_id'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => 'Scheduled',
        ]);

        // Auto-generate roster if requested
        if ($request->has('generate_roster') || $request->input('generate_roster')) {
            $studentQuery = Student::where('school_id', $schoolId);

            if ($meeting->scope == 'class' && $meeting->school_class_id) {
                $studentQuery->where('class_id', $meeting->school_class_id);
            } elseif ($meeting->scope == 'section' && $meeting->section_id) {
                $studentQuery->where('section_id', $meeting->section_id);
            }

            $students = $studentQuery->get();
            $slots = ['09:00 AM - 09:15 AM', '09:15 AM - 09:30 AM', '09:30 AM - 09:45 AM', '09:45 AM - 10:00 AM', '10:00 AM - 10:15 AM'];

            foreach ($students as $idx => $st) {
                PtmAttendance::create([
                    'school_id' => $schoolId,
                    'ptm_meeting_id' => $meeting->id,
                    'student_id' => $st->id,
                    'guardian_id' => $st->guardian_id,
                    'slot_time' => $slots[$idx % count($slots)],
                    'attendance_status' => 'Pending',
                    'is_notified' => false,
                    'visible_to_parent' => true,
                ]);
            }
        }

        return redirect()->route('ptm.meetings')->with('success', "PTM '{$meeting->title}' scheduled successfully!");
    }

    /**
     * Show PTM Meeting & Guardian Roster
     */
    public function show($id)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $meeting = PtmMeeting::where('school_id', $schoolId)
            ->with(['schoolClass', 'section', 'attendances.student', 'attendances.guardian', 'followups.student'])
            ->findOrFail($id);

        $totalInvited = $meeting->attendances->count();
        $presentCount = $meeting->attendances->where('attendance_status', 'Present')->count();
        $absentCount = $meeting->attendances->where('attendance_status', 'Absent')->count();
        $pendingCount = $meeting->attendances->where('attendance_status', 'Pending')->count();
        $rescheduledCount = $meeting->attendances->where('attendance_status', 'Rescheduled')->count();
        $followupsCount = $meeting->followups->count();

        return view('ptm.show', compact(
            'school',
            'meeting',
            'totalInvited',
            'presentCount',
            'absentCount',
            'pendingCount',
            'rescheduledCount',
            'followupsCount'
        ));
    }

    /**
     * Edit PTM Meeting Form
     */
    public function edit($id)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $meeting = PtmMeeting::where('school_id', $schoolId)->findOrFail($id);
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();

        return view('ptm.edit', compact('school', 'meeting', 'classes', 'sections'));
    }

    /**
     * Update PTM Meeting
     */
    public function update(Request $request, $id)
    {
        $meeting = PtmMeeting::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'mode' => 'required|string|in:in_person,online,hybrid',
            'venue' => 'nullable|string|max:255',
            'online_link' => 'nullable|url|max:255',
            'scope' => 'required|string|in:whole_school,class,section',
            'school_class_id' => 'nullable|integer',
            'section_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $meeting->update([
            'title' => $validated['title'],
            'meeting_date' => $validated['meeting_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'mode' => $validated['mode'],
            'venue' => $validated['venue'],
            'online_link' => $validated['online_link'],
            'scope' => $validated['scope'],
            'class_id' => $validated['school_class_id'],
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $validated['section_id'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('ptm.meetings')->with('success', "PTM '{$meeting->title}' updated successfully!");
    }

    /**
     * Delete PTM Meeting
     */
    public function destroy($id)
    {
        $meeting = PtmMeeting::findOrFail($id);
        $title = $meeting->title;
        $meeting->delete();

        return redirect()->route('ptm.meetings')->with('success', "PTM Meeting '{$title}' removed.");
    }

    /**
     * Send Invitations / Mark Notified
     */
    public function notifyMeeting($id)
    {
        $meeting = PtmMeeting::findOrFail($id);
        $meeting->update(['status' => 'Notified']);
        $meeting->attendances()->update(['is_notified' => true]);

        return redirect()->back()->with('success', "Invitations broadcasted to {$meeting->attendances()->count()} guardians! Status updated to 'Notified'.");
    }

    /**
     * 3. PTM Attendance & Remarks Hub (Index of meetings ready for recording)
     */
    public function recordIndex(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $meetings = PtmMeeting::where('school_id', $schoolId)
            ->with(['schoolClass', 'section'])
            ->withCount([
                'attendances',
                'attendances as present_count' => function ($q) {
                    $q->where('attendance_status', 'Present');
                }
            ])
            ->latest('meeting_date')
            ->get();

        return view('ptm.record_index', compact('school', 'meetings'));
    }

    /**
     * Record Attendance & Remarks for Specific Meeting
     */
    public function record($id)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $meeting = PtmMeeting::where('school_id', $schoolId)
            ->with(['schoolClass', 'section'])
            ->findOrFail($id);

        $attendances = PtmAttendance::where('ptm_meeting_id', $id)
            ->with('student')
            ->get();

        return view('ptm.record', compact('school', 'meeting', 'attendances'));
    }

    /**
     * Save Attendance & Remarks Batch
     */
    public function saveRecord(Request $request, $id)
    {
        $meeting = PtmMeeting::findOrFail($id);
        $rows = $request->input('rows', []);

        foreach ($rows as $attId => $data) {
            $att = PtmAttendance::find($attId);
            if ($att && $att->ptm_meeting_id == $meeting->id) {
                $att->update([
                    'attendance_status' => $data['attendance_status'] ?? 'Pending',
                    'performance_remark' => $data['performance_remark'] ?? null,
                    'attendance_remark' => $data['attendance_remark'] ?? null,
                    'behaviour_remark' => $data['behaviour_remark'] ?? null,
                    'overall_rating' => !empty($data['overall_rating']) ? (int)$data['overall_rating'] : null,
                    'visible_to_parent' => isset($data['visible_to_parent']) && $data['visible_to_parent'],
                ]);
            }
        }

        // If meeting was Scheduled/Notified and at least one student is marked Present, advance status to 'In progress'
        if (in_array($meeting->status, ['Scheduled', 'Notified'])) {
            $meeting->update(['status' => 'In progress']);
        }

        return redirect()->back()->with('success', "Attendance and remarks for '{$meeting->title}' saved successfully!");
    }

    /**
     * 4. PTM Follow-ups
     */
    public function followups(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $status = $request->get('status');

        $openCount = PtmFollowup::where('school_id', $schoolId)->where('status', 'Open')->count();
        $inProgressCount = PtmFollowup::where('school_id', $schoolId)->where('status', 'In progress')->count();
        $completedCount = PtmFollowup::where('school_id', $schoolId)->where('status', 'Completed')->count();

        $followups = PtmFollowup::where('school_id', $schoolId)
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->with(['student', 'meeting', 'staff'])
            ->latest()
            ->get();

        $meetings = PtmMeeting::where('school_id', $schoolId)->orderByDesc('meeting_date')->get();
        $students = Student::where('school_id', $schoolId)->orderBy('first_name')->get();
        $staffMembers = Staff::where('school_id', $schoolId)->orderBy('first_name')->get();

        return view('ptm.followups', compact(
            'school',
            'followups',
            'openCount',
            'inProgressCount',
            'completedCount',
            'status',
            'meetings',
            'students',
            'staffMembers'
        ));
    }

    /**
     * Store new Follow-up
     */
    public function storeFollowup(Request $request)
    {
        $validated = $request->validate([
            'ptm_meeting_id' => 'required|integer',
            'student_id' => 'required|integer',
            'action_plan' => 'required|string',
            'assigned_to' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|in:Open,In progress,Completed',
        ]);

        PtmFollowup::create([
            'school_id' => 1,
            'ptm_meeting_id' => $validated['ptm_meeting_id'],
            'student_id' => $validated['student_id'],
            'action_plan' => $validated['action_plan'],
            'assigned_to' => $validated['assigned_to'] ?? 'Academic Mentor',
            'due_date' => $validated['due_date'] ?? Carbon::now()->addDays(14)->toDateString(),
            'status' => $validated['status'] ?? 'Open',
        ]);

        return redirect()->back()->with('success', "Follow-up action item created successfully!");
    }

    /**
     * Update Follow-up
     */
    public function updateFollowup(Request $request, $id)
    {
        $followup = PtmFollowup::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|in:Open,In progress,Completed',
            'action_plan' => 'nullable|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|string',
        ]);

        $followup->update(array_filter([
            'status' => $validated['status'],
            'action_plan' => $validated['action_plan'] ?? $followup->action_plan,
            'due_date' => $validated['due_date'] ?? $followup->due_date,
            'assigned_to' => $validated['assigned_to'] ?? $followup->assigned_to,
        ]));

        return redirect()->back()->with('success', "Follow-up status updated to '{$validated['status']}'.");
    }

    /**
     * Delete Follow-up
     */
    public function destroyFollowup($id)
    {
        $followup = PtmFollowup::findOrFail($id);
        $followup->delete();

        return redirect()->back()->with('success', "Follow-up record removed.");
    }

    /**
     * 5. PTM Reports
     */
    public function reports(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $meetingId = $request->get('meeting_id');
        $meetings = PtmMeeting::where('school_id', $schoolId)->orderByDesc('meeting_date')->get();

        $selectedMeeting = null;
        $summary = null;

        if ($meetingId) {
            $selectedMeeting = PtmMeeting::where('school_id', $schoolId)
                ->with(['schoolClass', 'section', 'attendances.student', 'attendances.guardian', 'followups'])
                ->find($meetingId);

            if ($selectedMeeting) {
                $total = $selectedMeeting->attendances->count();
                $present = $selectedMeeting->attendances->where('attendance_status', 'Present')->count();
                $absent = $selectedMeeting->attendances->where('attendance_status', 'Absent')->count();
                $pending = $selectedMeeting->attendances->where('attendance_status', 'Pending')->count();
                $rescheduled = $selectedMeeting->attendances->where('attendance_status', 'Rescheduled')->count();
                $turnoutRate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                $avgRating = $selectedMeeting->attendances->whereNotNull('overall_rating')->avg('overall_rating') ?? 0;

                $summary = [
                    'total' => $total,
                    'present' => $present,
                    'absent' => $absent,
                    'pending' => $pending,
                    'rescheduled' => $rescheduled,
                    'turnout_rate' => $turnoutRate,
                    'avg_rating' => round($avgRating, 1),
                ];
            }
        }

        return view('ptm.reports', compact('school', 'meetings', 'meetingId', 'selectedMeeting', 'summary'));
    }

    /**
     * 6. PTM Guide
     */
    public function guide()
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        return view('ptm.guide', compact('school'));
    }
}

