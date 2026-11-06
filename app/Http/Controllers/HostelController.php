<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\HostelRoom;
use App\Models\HostelRoomType;
use App\Models\HostelAllocation;
use App\Models\Student;
use Carbon\Carbon;

class HostelController extends Controller
{
    // =========================================================================
    // 1. DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        $totalHostels = Hostel::count();
        $totalRooms = HostelRoom::count();
        $occupiedBeds = HostelAllocation::where('status', 'Active')->count();
        $totalCapacity = HostelRoom::sum('capacity');
        $availableBeds = max(0, $totalCapacity - $occupiedBeds);
        $occupancyRate = $totalCapacity > 0 ? round(($occupiedBeds / $totalCapacity) * 100) : 0;

        $hostels = Hostel::with(['rooms.roomType', 'rooms.allocations'])->get();
        $rooms = HostelRoom::with(['hostel', 'roomType', 'allocations.student'])->latest()->get();
        $recentAllocations = HostelAllocation::with(['student', 'room.hostel', 'room.roomType'])
            ->latest()
            ->take(6)
            ->get();

        return view('hostel.dashboard', compact(
            'totalHostels',
            'totalRooms',
            'occupiedBeds',
            'availableBeds',
            'totalCapacity',
            'occupancyRate',
            'hostels',
            'rooms',
            'recentAllocations'
        ));
    }

    // =========================================================================
    // 2. GUIDE
    // =========================================================================
    public function guide()
    {
        return view('hostel.guide');
    }

    // =========================================================================
    // 3. STUDENT ALLOCATION
    // =========================================================================
    public function allocations(Request $request)
    {
        $query = Hostel::with(['rooms.roomType', 'rooms.allocations.student']);

        if ($request->filled('hostel_id')) {
            $query->where('id', $request->hostel_id);
        }

        $hostels = $query->get();
        $allHostels = Hostel::orderBy('name')->get();
        $students = Student::orderBy('first_name')->get();

        return view('hostel.allocations', compact('hostels', 'allHostels', 'students'));
    }

    public function allocateStudent(Request $request)
    {
        $request->validate([
            'hostel_room_id' => 'required|exists:hostel_rooms,id',
            'student_id' => 'required|exists:students,id',
        ]);

        $room = HostelRoom::findOrFail($request->hostel_room_id);

        if ($room->isFull()) {
            return back()->with('error', 'Cannot allocate: This room is already at full capacity!');
        }

        // Check if student already has active allocation in this room
        $exists = HostelAllocation::where('hostel_room_id', $room->id)
            ->where('student_id', $request->student_id)
            ->where('status', 'Active')
            ->exists();

        if ($exists) {
            return back()->with('error', 'This student is already allocated to this room.');
        }

        HostelAllocation::create([
            'hostel_room_id' => $room->id,
            'student_id' => $request->student_id,
            'allocated_at' => Carbon::now(),
            'status' => 'Active',
        ]);

        return redirect()->route('school.hostel.allocations')->with('success', 'Student allocated to room successfully!');
    }

    public function unallocateStudent($id)
    {
        $allocation = HostelAllocation::findOrFail($id);
        $allocation->delete();

        return redirect()->route('school.hostel.allocations')->with('success', 'Student allocation removed.');
    }

    // =========================================================================
    // 4. MANAGE ROOMS
    // =========================================================================
    public function rooms()
    {
        $rooms = HostelRoom::with(['hostel', 'roomType', 'allocations.student'])->latest()->get();
        $hostels = Hostel::orderBy('name')->get();
        $roomTypes = HostelRoomType::orderBy('name')->get();

        return view('hostel.rooms', compact('rooms', 'hostels', 'roomTypes'));
    }

    public function storeRoom(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_type_id' => 'required|exists:hostel_room_types,id',
            'room_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
        ]);

        HostelRoom::create([
            'hostel_id' => $request->hostel_id,
            'room_type_id' => $request->room_type_id,
            'room_number' => $request->room_number,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('school.hostel.rooms')->with('success', "Room '{$request->room_number}' added successfully!");
    }

    public function updateRoom(Request $request, $id)
    {
        $room = HostelRoom::findOrFail($id);

        $request->validate([
            'hostel_id' => 'required|exists:hostels,id',
            'room_type_id' => 'required|exists:hostel_room_types,id',
            'room_number' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1',
        ]);

        $room->update([
            'hostel_id' => $request->hostel_id,
            'room_type_id' => $request->room_type_id,
            'room_number' => $request->room_number,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('school.hostel.rooms')->with('success', "Room '{$room->room_number}' updated successfully!");
    }

    public function destroyRoom($id)
    {
        $room = HostelRoom::findOrFail($id);
        $num = $room->room_number;
        $room->delete();

        return redirect()->route('school.hostel.rooms')->with('success', "Room '{$num}' deleted successfully.");
    }

    // =========================================================================
    // 5. ROOM TYPES
    // =========================================================================
    public function roomTypes()
    {
        $roomTypes = HostelRoomType::withCount('rooms')->latest()->get();
        return view('hostel.room_types', compact('roomTypes'));
    }

    public function storeRoomType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'fare' => 'required|numeric|min:0',
        ]);

        HostelRoomType::create([
            'name' => $request->name,
            'fare' => $request->fare,
            'description' => $request->description,
        ]);

        return redirect()->route('school.hostel.room-types')->with('success', "Room Type '{$request->name}' created successfully!");
    }

    public function updateRoomType(Request $request, $id)
    {
        $type = HostelRoomType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'fare' => 'required|numeric|min:0',
        ]);

        $type->update([
            'name' => $request->name,
            'fare' => $request->fare,
            'description' => $request->description,
        ]);

        return redirect()->route('school.hostel.room-types')->with('success', "Room Type '{$type->name}' updated successfully!");
    }

    public function destroyRoomType($id)
    {
        $type = HostelRoomType::findOrFail($id);
        $name = $type->name;
        $type->delete();

        return redirect()->route('school.hostel.room-types')->with('success', "Room Type '{$name}' deleted.");
    }

    // =========================================================================
    // 6. MANAGE HOSTELS
    // =========================================================================
    public function hostels()
    {
        $hostels = Hostel::withCount('rooms')->latest()->get();
        return view('hostel.hostels', compact('hostels'));
    }

    public function createHostel()
    {
        return view('hostel.hostel_create');
    }

    public function storeHostel(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|string',
        ]);

        $hostel = Hostel::create([
            'name' => $request->name,
            'type' => $request->type,
            'address' => $request->address,
            'note' => $request->note,
        ]);

        return redirect()->route('school.hostel.hostels')->with('success', "Hostel '{$hostel->name}' registered successfully!");
    }

    public function editHostel($id)
    {
        $hostel = Hostel::findOrFail($id);
        return view('hostel.hostel_edit', compact('hostel'));
    }

    public function updateHostel(Request $request, $id)
    {
        $hostel = Hostel::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|string',
        ]);

        $hostel->update([
            'name' => $request->name,
            'type' => $request->type,
            'address' => $request->address,
            'note' => $request->note,
        ]);

        return redirect()->route('school.hostel.hostels')->with('success', "Hostel '{$hostel->name}' updated successfully!");
    }

    public function destroyHostel($id)
    {
        $hostel = Hostel::findOrFail($id);
        $name = $hostel->name;
        $hostel->delete();

        return redirect()->route('school.hostel.hostels')->with('success', "Hostel '{$name}' deleted.");
    }
}
