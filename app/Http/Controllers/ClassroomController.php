<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Building;
use App\Models\ClassroomRoom;
use App\Models\RoomBooking;
use App\Models\RoomMaintenance;
use App\Models\RoomLabelMatch;
use App\Models\RoomTemporaryAllocation;
use App\Models\RoomBookingSetting;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Timetable;

class ClassroomController extends Controller
{
    /**
     * 1. Classroom List (Rooms Hub)
     */
    public function rooms(Request $request)
    {
        $query = ClassroomRoom::with(['building', 'homeSection.schoolClass', 'activeMaintenance']);

        if ($request->filled('building_id')) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('room_number', 'like', "%{$term}%")
                  ->orWhere('name', 'like', "%{$term}%");
            });
        }

        $rooms = $query->orderBy('room_number')->paginate(15)->withQueryString();
        $buildings = Building::where('is_active', true)->orderBy('name')->get();
        $sections = Section::with('schoolClass')->get();

        // KPIs
        $totalActiveRooms = ClassroomRoom::where('status', 'Available')->count();
        $totalEverydaySeats = ClassroomRoom::sum('capacity');
        $totalExamSeats = ClassroomRoom::sum('exam_capacity') ?: ClassroomRoom::where('is_exam_hall', true)->sum('capacity');
        $underMaintenanceCount = ClassroomRoom::whereHas('activeMaintenance')->orWhere('status', 'Maintenance')->count();
        $unmatchedLabelsCount = RoomLabelMatch::whereNull('room_id')->count();

        $roomTypes = ['Classroom', 'Science lab', 'Computer lab', 'Hall', 'Library', 'Sports facility', 'Other'];

        return view('classrooms.rooms', compact(
            'rooms',
            'buildings',
            'sections',
            'totalActiveRooms',
            'totalEverydaySeats',
            'totalExamSeats',
            'underMaintenanceCount',
            'unmatchedLabelsCount',
            'roomTypes'
        ));
    }

    /**
     * Store Single Room
     */
    public function storeRoom(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|max:50',
            'building_id' => 'required|exists:buildings,id',
            'room_type' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        $school = School::first();

        ClassroomRoom::create([
            'school_id' => $school ? $school->id : 1,
            'building_id' => $request->building_id,
            'room_number' => $request->room_number,
            'name' => $request->name ?? $request->room_number,
            'room_type' => $request->room_type,
            'floor' => $request->floor ?? 1,
            'capacity' => $request->capacity,
            'exam_capacity' => $request->exam_capacity ?? round($request->capacity * 0.6),
            'facilities_json' => $request->facilities ?? ['Fans', 'Whiteboard'],
            'home_section_id' => $request->home_section_id,
            'is_exam_hall' => (bool)$request->is_exam_hall,
            'status' => $request->status ?? 'Available',
            'notes' => $request->notes,
        ]);

        return redirect()->route('classrooms.rooms')->with('success', 'Classroom created successfully.');
    }

    /**
     * Update Single Room
     */
    public function updateRoom(Request $request, $id)
    {
        $room = ClassroomRoom::findOrFail($id);

        $room->update([
            'building_id' => $request->building_id ?? $room->building_id,
            'room_number' => $request->room_number ?? $room->room_number,
            'name' => $request->name ?? $room->name,
            'room_type' => $request->room_type ?? $room->room_type,
            'floor' => $request->floor ?? $room->floor,
            'capacity' => $request->capacity ?? $room->capacity,
            'exam_capacity' => $request->exam_capacity ?? $room->exam_capacity,
            'home_section_id' => $request->home_section_id,
            'status' => $request->status ?? $room->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('classrooms.rooms')->with('success', 'Classroom updated successfully.');
    }

    /**
     * Destroy Room
     */
    public function destroyRoom($id)
    {
        $room = ClassroomRoom::findOrFail($id);
        $room->delete();

        return redirect()->route('classrooms.rooms')->with('success', 'Classroom deleted successfully.');
    }

    /**
     * Bulk Quick Setup for Rooms
     */
    public function quickSetup(Request $request)
    {
        $request->validate([
            'room_building_id' => 'required|exists:buildings,id',
            'prefix' => 'required|string',
            'floor_from' => 'required|integer|min:0',
            'floor_to' => 'required|integer|gte:floor_from',
            'rooms_per_floor' => 'required|integer|min:1|max:30',
            'room_type' => 'required|string',
            'seating_capacity' => 'required|integer|min:1',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;
        $created = 0;

        for ($fl = $request->floor_from; $fl <= $request->floor_to; $fl++) {
            for ($r = 1; $r <= $request->rooms_per_floor; $r++) {
                $numStr = sprintf('%d%02d', $fl, $r);
                $roomCode = $request->prefix . $numStr;

                ClassroomRoom::firstOrCreate(
                    ['room_number' => $roomCode],
                    [
                        'school_id' => $schoolId,
                        'building_id' => $request->room_building_id,
                        'name' => "Room {$numStr}",
                        'room_type' => $request->room_type,
                        'floor' => $fl,
                        'capacity' => $request->seating_capacity,
                        'exam_capacity' => round($request->seating_capacity * 0.6),
                        'facilities_json' => ['Fans', 'Whiteboard', 'Lighting'],
                        'status' => 'Available',
                    ]
                );
                $created++;
            }
        }

        return redirect()->route('classrooms.rooms')->with('success', "{$created} classrooms generated successfully.");
    }

    /**
     * Import Examination Halls
     */
    public function importHalls()
    {
        $school = School::first();
        $building = Building::first();

        // Create default hall
        ClassroomRoom::firstOrCreate(
            ['room_number' => 'HALL-EXAM-A'],
            [
                'school_id' => $school ? $school->id : 1,
                'building_id' => $building ? $building->id : 1,
                'name' => 'Examination Center Hall A',
                'room_type' => 'Hall',
                'floor' => 1,
                'capacity' => 150,
                'exam_capacity' => 120,
                'facilities_json' => ['Exam desks', 'CCTV monitoring', 'High ventilation'],
                'is_exam_hall' => true,
                'status' => 'Available',
            ]
        );

        return redirect()->route('classrooms.rooms')->with('success', 'Examination halls imported into room inventory.');
    }

    /**
     * 2. Buildings & Blocks
     */
    public function buildings()
    {
        $buildings = Building::withCount('rooms')
            ->with(['rooms'])
            ->orderBy('name')
            ->get();

        return view('classrooms.buildings', compact('buildings'));
    }

    /**
     * Store Building
     */
    public function storeBuilding(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:buildings,code',
            'floors' => 'required|integer|min:1',
        ]);

        $school = School::first();

        Building::create([
            'school_id' => $school ? $school->id : 1,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'floors' => $request->floors,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        return redirect()->route('classrooms.buildings')->with('success', 'Building added successfully.');
    }

    /**
     * Update Building
     */
    public function updateBuilding(Request $request, $id)
    {
        $building = Building::findOrFail($id);

        $building->update([
            'name' => $request->name ?? $building->name,
            'code' => $request->code ? strtoupper($request->code) : $building->code,
            'floors' => $request->floors ?? $building->floors,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('classrooms.buildings')->with('success', 'Building updated successfully.');
    }

    /**
     * Destroy Building
     */
    public function destroyBuilding($id)
    {
        $building = Building::findOrFail($id);
        $building->rooms()->delete();
        $building->delete();

        return redirect()->route('classrooms.buildings')->with('success', 'Building deleted successfully.');
    }

    /**
     * 3. Room Label Matching
     */
    public function labels()
    {
        $labels = RoomLabelMatch::with('room')->get();
        $rooms = ClassroomRoom::orderBy('room_number')->get();

        return view('classrooms.labels', compact('labels', 'rooms'));
    }

    /**
     * Map Label to Room
     */
    public function mapLabel(Request $request)
    {
        $request->validate([
            'label' => 'required|string',
            'room_id' => 'required|exists:classroom_rooms,id',
        ]);

        $match = RoomLabelMatch::where('label', $request->label)->first();
        if ($match) {
            $match->update(['room_id' => $request->room_id]);
        } else {
            RoomLabelMatch::create([
                'label' => $request->label,
                'room_id' => $request->room_id,
            ]);
        }

        return redirect()->route('classrooms.labels')->with('success', "Label '{$request->label}' mapped to room successfully.");
    }

    /**
     * 4. Room Plan
     */
    public function plan()
    {
        $sections = Section::with(['schoolClass', 'students'])->get();
        $rooms = ClassroomRoom::with('building')->orderBy('room_number')->get();
        $temporaryAllocations = RoomTemporaryAllocation::with(['section.schoolClass', 'room'])->latest()->get();

        $totalSectionsCount = $sections->count();
        $assignedCount = $sections->whereNotNull('class_id')->filter(function ($sec) use ($rooms) {
            return $rooms->where('home_section_id', $sec->id)->isNotEmpty();
        })->count();
        $unassignedCount = max(0, $totalSectionsCount - $assignedCount);
        $totalStudents = Student::count();

        return view('classrooms.plan', compact(
            'sections',
            'rooms',
            'temporaryAllocations',
            'totalSectionsCount',
            'assignedCount',
            'unassignedCount',
            'totalStudents'
        ));
    }

    /**
     * Save Home Room Plan
     */
    public function savePlan(Request $request)
    {
        $roomAssignments = $request->input('rooms', []);

        // Clear existing home sections
        ClassroomRoom::whereNotNull('home_section_id')->update(['home_section_id' => null]);

        // Assign selected home rooms
        foreach ($roomAssignments as $sectionId => $roomId) {
            if ($roomId) {
                ClassroomRoom::where('id', $roomId)->update(['home_section_id' => $sectionId]);
            }
        }

        return redirect()->route('classrooms.plan')->with('success', 'Room Plan saved successfully.');
    }

    /**
     * Save Temporary Room Move
     */
    public function saveTemporaryAllocation(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'room_id' => 'required|exists:classroom_rooms,id',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'reason' => 'nullable|string',
        ]);

        RoomTemporaryAllocation::create([
            'section_id' => $request->section_id,
            'room_id' => $request->room_id,
            'from_date' => $request->from,
            'to_date' => $request->to,
            'reason' => $request->reason,
        ]);

        return redirect()->route('classrooms.plan')->with('success', 'Temporary section relocation scheduled successfully.');
    }

    /**
     * 5. Capacity Report
     */
    public function capacity()
    {
        $sections = Section::with(['schoolClass', 'students'])->get();
        $rooms = ClassroomRoom::with('building')->get();

        $sectionsWithRoom = 0;
        $overCapacityCount = 0;
        $totalSeatsInUse = 0;

        $reportRows = [];
        foreach ($sections as $sec) {
            $assignedRoom = $rooms->where('home_section_id', $sec->id)->first();
            $studentCount = $sec->students->count();
            if ($studentCount == 0) $studentCount = 25; // fallback sample

            $status = 'No room';
            $seatsUsed = '—';
            $suggestion = 'Assign a home room';

            if ($assignedRoom) {
                $sectionsWithRoom++;
                $totalSeatsInUse += min($studentCount, $assignedRoom->capacity);
                $pct = round(($studentCount / max(1, $assignedRoom->capacity)) * 100);
                $seatsUsed = "{$studentCount} / {$assignedRoom->capacity} ({$pct}%)";

                if ($studentCount > $assignedRoom->capacity) {
                    $status = 'Over capacity';
                    $overCapacityCount++;
                    $suggestion = 'Move to a larger room or split section';
                } elseif ($pct < 50) {
                    $status = 'Under-utilized';
                    $suggestion = 'Consider moving to a smaller room';
                } else {
                    $status = 'Optimal fit';
                    $suggestion = 'Comfortable capacity match';
                }
            }

            $reportRows[] = [
                'section' => $sec,
                'student_count' => $studentCount,
                'room' => $assignedRoom,
                'seats_used' => $seatsUsed,
                'status' => $status,
                'suggestion' => $suggestion,
            ];
        }

        $availableRooms = ClassroomRoom::whereNull('home_section_id')->where('status', 'Available')->get();

        return view('classrooms.capacity', compact(
            'reportRows',
            'availableRooms',
            'sectionsWithRoom',
            'overCapacityCount',
            'totalSeatsInUse'
        ));
    }

    /**
     * 6. Room Timetable
     */
    public function timetable(Request $request)
    {
        $rooms = ClassroomRoom::orderBy('room_number')->get();
        $selectedRoomId = $request->room_id ?? ($rooms->first() ? $rooms->first()->id : null);
        $selectedRoom = $selectedRoomId ? ClassroomRoom::with('building')->find($selectedRoomId) : null;

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $periods = [
            'Period 1 (08:30 - 09:15)',
            'Period 2 (09:15 - 10:00)',
            'Period 3 (10:15 - 11:00)',
            'Period 4 (11:00 - 11:45)',
            'Period 5 (12:30 - 01:15)',
            'Period 6 (01:15 - 02:00)',
        ];

        return view('classrooms.timetable', compact('rooms', 'selectedRoom', 'days', 'periods'));
    }

    /**
     * 7. Free Rooms
     */
    public function freeRooms(Request $request)
    {
        $day = $request->day ?? 'Monday';
        $period = $request->period ?? 'Period 1';
        $rooms = ClassroomRoom::with(['building', 'activeMaintenance'])->where('status', 'Available')->get();

        return view('classrooms.free_rooms', compact('rooms', 'day', 'period'));
    }

    /**
     * 8. Room Usage
     */
    public function usage()
    {
        $rooms = ClassroomRoom::with('building')->orderBy('room_number')->get();

        return view('classrooms.usage', compact('rooms'));
    }

    /**
     * 9. Room Clashes
     */
    public function clashes()
    {
        $clashes = [];
        $unmappedLabels = RoomLabelMatch::whereNull('room_id')->get();
        $smallRoomsCount = 0;

        return view('classrooms.clashes', compact('clashes', 'unmappedLabels', 'smallRoomsCount'));
    }

    /**
     * 10. Room Bookings
     */
    public function bookings(Request $request)
    {
        $query = RoomBooking::with('room.building');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15)->withQueryString();
        $rooms = ClassroomRoom::where('status', 'Available')->orderBy('room_number')->get();
        $settings = RoomBookingSetting::first() ?? new RoomBookingSetting();

        return view('classrooms.bookings', compact('bookings', 'rooms', 'settings'));
    }

    /**
     * Store Room Booking
     */
    public function storeBooking(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:classroom_rooms,id',
            'title' => 'required|string|max:200',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'booked_by' => 'required|string',
        ]);

        $settings = RoomBookingSetting::first();
        $status = ($settings && $settings->requests_need_approval) ? 'Pending' : 'Approved';

        RoomBooking::create([
            'room_id' => $request->room_id,
            'title' => $request->title,
            'booked_by' => $request->booked_by,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $status,
        ]);

        return redirect()->route('classrooms.bookings')->with('success', 'Room booking requested successfully.');
    }

    /**
     * Update Booking Status (Approve / Reject)
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $booking = RoomBooking::findOrFail($id);
        $booking->update(['status' => $request->input('status', 'Approved')]);

        return redirect()->route('classrooms.bookings')->with('success', "Booking status updated to {$booking->status}.");
    }

    /**
     * Update Booking Settings
     */
    public function updateBookingSettings(Request $request)
    {
        $settings = RoomBookingSetting::first() ?? new RoomBookingSetting();
        $settings->teachers_can_request = $request->has('teachers_can_request');
        $settings->requests_need_approval = $request->has('requests_need_approval');
        $settings->save();

        return redirect()->route('classrooms.bookings')->with('success', 'Booking preferences saved successfully.');
    }

    /**
     * 11. Room Maintenance
     */
    public function maintenance(Request $request)
    {
        $query = RoomMaintenance::with('room.building');

        if ($request->filled('tab')) {
            if ($request->tab === 'open') {
                $query->whereIn('status', ['Reported', 'Being fixed']);
            } elseif ($request->tab === 'done') {
                $query->whereIn('status', ['Resolved', 'Closed']);
            }
        }

        if ($request->filled('room')) {
            $query->where('room_id', $request->room);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where(function($q) use ($request) {
                $q->where('priority', $request->priority)
                  ->orWhere('priority', ucfirst(strtolower($request->priority)));
            });
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();
        $rooms = ClassroomRoom::orderBy('room_number')->get();

        $openJobsCount = RoomMaintenance::whereIn('status', ['Reported', 'Being fixed'])->count();
        $urgentJobsCount = RoomMaintenance::whereIn('status', ['Reported', 'Being fixed'])
            ->where(function($q) {
                $q->whereIn('priority', ['Urgent', 'High', 'urgent', 'high']);
            })->count();
        $unassignedCount = RoomMaintenance::where(function($q) {
            $q->whereNull('given_to')->orWhere('given_to', '');
        })->whereIn('status', ['Reported', 'Being fixed'])->count();
        $outOfUseCount = RoomMaintenance::where('is_unusable', true)->whereIn('status', ['Reported', 'Being fixed'])->count();

        return view('classrooms.maintenance', compact(
            'tickets',
            'rooms',
            'openJobsCount',
            'urgentJobsCount',
            'unassignedCount',
            'outOfUseCount'
        ));
    }

    /**
     * Store Maintenance Ticket
     */
    public function storeMaintenance(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:classroom_rooms,id',
            'problem' => 'required|string|max:255',
            'priority' => 'required|string',
        ]);

        RoomMaintenance::create([
            'room_id' => $request->room_id,
            'problem' => $request->problem,
            'priority' => $request->priority,
            'given_to' => $request->given_to,
            'status' => 'Reported',
            'is_unusable' => $request->has('is_unusable'),
            'reported_by' => 'School Admin',
            'reported_at' => now()->toDateString(),
            'notes' => $request->notes,
        ]);

        return redirect()->route('classrooms.maintenance')->with('success', 'Maintenance job logged successfully.');
    }

    /**
     * Update Maintenance Ticket
     */
    public function updateMaintenance(Request $request, $id)
    {
        $ticket = RoomMaintenance::findOrFail($id);

        $ticket->update([
            'status' => $request->status ?? $ticket->status,
            'given_to' => $request->given_to ?? $ticket->given_to,
            'notes' => $request->notes ?? $ticket->notes,
            'resolved_at' => ($request->status === 'Resolved' || $request->status === 'Closed') ? now()->toDateString() : null,
        ]);

        return redirect()->route('classrooms.maintenance')->with('success', 'Maintenance job updated successfully.');
    }

    /**
     * 12. Classroom Guide
     */
    public function guide()
    {
        return view('classrooms.guide');
    }
}
