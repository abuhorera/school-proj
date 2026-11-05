<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();

        $selectedClassId = $request->class_id ?? ($classes->first() ? $classes->first()->id : null);
        $selectedSectionId = $request->section_id;
        $date = $request->date ?? Carbon::today()->format('Y-m-d');

        $students = collect();
        $attendanceMap = [];

        if ($selectedClassId) {
            $studentsQuery = Student::whereHas('latestEnrollment', function ($q) use ($selectedClassId, $selectedSectionId) {
                $q->where('class_id', $selectedClassId);
                if ($selectedSectionId) {
                    $q->where('section_id', $selectedSectionId);
                }
            })->where('status', 'active')->orderBy('roll_no');

            $students = $studentsQuery->get();

            // Load existing attendance for this date
            $existing = Attendance::where('date', $date)
                ->whereIn('student_id', $students->pluck('id'))
                ->get();

            foreach ($existing as $att) {
                $attendanceMap[$att->student_id] = $att->status;
            }
        }

        return view('attendance.index', compact('school', 'classes', 'selectedClassId', 'selectedSectionId', 'date', 'students', 'attendanceMap'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $school = School::first();
        $date = $request->date;

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'student_id' => $studentId,
                    'type' => 'student',
                    'date' => $date,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance recorded successfully for ' . $date . '!');
    }
}

