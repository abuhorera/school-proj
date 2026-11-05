<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\AcademicSession;
use App\Models\StudentEnrollment;
use App\Models\StudentHouse;
use App\Models\StudentCategory;
use App\Models\StudentBehavior;
use App\Models\StudentExit;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // =========================================================================
    // 1. STUDENT DASHBOARD (/school/student-dashboard)
    // =========================================================================
    public function dashboard()
    {
        $school = School::first();
        $totalStudents = Student::count();
        $activeStudents = Student::where('status', 'active')->count();
        $boysCount = Student::where('gender', 'male')->count();
        $girlsCount = Student::where('gender', 'female')->count();
        $sessionTotal = $totalStudents;

        // Class-wise strength
        $classes = SchoolClass::withCount('enrollments')->get();
        $houses = StudentHouse::withCount('students')->get();
        $categories = StudentCategory::withCount('students')->get();
        $recentStudents = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section'])
            ->orderBy('id', 'desc')->take(6)->get();

        return view('students.dashboard', compact(
            'school', 'totalStudents', 'activeStudents', 'boysCount', 'girlsCount', 
            'sessionTotal', 'classes', 'houses', 'categories', 'recentStudents'
        ));
    }

    // =========================================================================
    // 2. STUDENT LIST / DIRECTORY (/school/students)
    // =========================================================================
    public function index(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        $houses = StudentHouse::all();
        $categories = StudentCategory::all();

        $query = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'guardian', 'house', 'category', 'feeAllocations', 'feePayments']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('admission_no', 'like', "%{$s}%")
                  ->orWhere('roll_no', 'like', "%{$s}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->whereHas('latestEnrollment', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('house_id')) {
            $query->where('house_id', $request->house_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $students = $query->orderBy('admission_no', 'asc')->paginate(15);
        $totalCount = Student::count();

        return view('students.index', compact('students', 'classes', 'houses', 'categories', 'school', 'totalCount'));
    }

    // =========================================================================
    // 3. STUDENT ADMISSION (/school/students/create)
    // =========================================================================
    public function create()
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        $houses = StudentHouse::all();
        $categories = StudentCategory::all();
        $nextAdmissionNo = 'YISADM-' . str_pad(Student::withTrashed()->count() + 1, 3, '0', STR_PAD_LEFT);

        return view('students.create', compact('classes', 'houses', 'categories', 'school', 'nextAdmissionNo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'admission_no' => 'required|unique:students,admission_no',
            'first_name' => 'required|string|max:100',
            'gender' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
        ]);

        $school = School::first();
        $session = AcademicSession::where('is_current', true)->first();

        $student = Student::create([
            'school_id' => $school->id ?? 1,
            'admission_no' => $request->admission_no,
            'roll_no' => $request->roll_no,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'blood_group' => $request->blood_group,
            'house_id' => $request->house_id,
            'category_id' => $request->category_id,
            'religion' => $request->religion,
            'height' => $request->height,
            'weight' => $request->weight,
            'allergies' => $request->allergies,
            'medical_alerts' => $request->medical_alerts,
            'admission_date' => $request->admission_date ?? now()->format('Y-m-d'),
            'status' => 'active',
        ]);

        Guardian::create([
            'student_id' => $student->id,
            'father_name' => $request->father_name ?? 'N/A',
            'father_phone' => $request->father_phone,
            'mother_name' => $request->mother_name,
            'emergency_contact' => $request->emergency_contact ?? $request->father_phone,
            'address' => $request->address,
        ]);

        StudentEnrollment::create([
            'student_id' => $student->id,
            'session_id' => $session ? $session->id : 1,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'enrolled_at' => now()->format('Y-m-d'),
        ]);

        return redirect()->route('students.show', $student->id)->with('success', 'Student enrolled successfully!');
    }

    // =========================================================================
    // 4. SEARCH STUDENT BY PHOTO / FACIAL GALLERY (/school/students-face-search)
    // =========================================================================
    public function faceSearch(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();

        $query = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'guardian'])
            ->where('status', 'active');

        if ($request->filled('class_id')) {
            $query->whereHas('latestEnrollment', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('admission_no', 'like', "%{$s}%");
            });
        }

        $students = $query->orderBy('first_name')->paginate(20);

        return view('students.face_search', compact('school', 'classes', 'students'));
    }

    // =========================================================================
    // 5. PARENTS & GUARDIANS (/school/parents)
    // =========================================================================
    public function parents(Request $request)
    {
        $school = School::first();
        $query = Guardian::with(['student.latestEnrollment.schoolClass', 'student.latestEnrollment.section']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('father_name', 'like', "%{$s}%")
                  ->orWhere('mother_name', 'like', "%{$s}%")
                  ->orWhere('father_phone', 'like', "%{$s}%");
            });
        }

        $parents = $query->paginate(15);
        $totalParents = Guardian::count();

        return view('students.parents', compact('school', 'parents', 'totalParents'));
    }

    // =========================================================================
    // 6. STUDENT ATTENDANCE (/school/student-attendance)
    // =========================================================================
    public function attendance(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $selectedClassId = $request->get('class_id', $classes->first()->id ?? null);
        $selectedSectionId = $request->get('section_id', null);

        $students = [];
        if ($selectedClassId) {
            $studentsQuery = Student::with(['latestEnrollment.section', 'attendances' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }])
            ->whereHas('latestEnrollment', function ($q) use ($selectedClassId, $selectedSectionId) {
                $q->where('class_id', $selectedClassId);
                if ($selectedSectionId) {
                    $q->where('section_id', $selectedSectionId);
                }
            })
            ->where('status', 'active')
            ->orderBy('first_name');

            $students = $studentsQuery->get();
        }

        return view('students.attendance', compact('school', 'classes', 'students', 'date', 'selectedClassId', 'selectedSectionId'));
    }

    public function markAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $school = School::first();
        $date = $request->date;

        foreach ($request->attendance as $studentId => $status) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'date' => $date],
                [
                    'school_id' => $school->id ?? 1,
                    'status' => $status,
                    'remarks' => $request->remarks[$studentId] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Student attendance recorded successfully for ' . $date . '!');
    }

    // =========================================================================
    // 7. BEHAVIOR RECORDS (/school/student-behavior)
    // =========================================================================
    public function behaviorRecords(Request $request)
    {
        $school = School::first();
        $students = Student::orderBy('first_name')->get();
        $query = StudentBehavior::with('student.latestEnrollment.schoolClass');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $records = $query->orderBy('date', 'desc')->paginate(15);
        $positivePoints = StudentBehavior::where('type', 'Positive')->sum('points');
        $disciplinaryCount = StudentBehavior::where('type', 'Disciplinary')->count();

        return view('students.behavior', compact('school', 'students', 'records', 'positivePoints', 'disciplinaryCount'));
    }

    public function storeBehavior(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:Positive,Disciplinary,Warning,Award',
            'points' => 'required|integer',
            'date' => 'required|date',
        ]);

        $school = School::first();
        StudentBehavior::create([
            'school_id' => $school->id ?? 1,
            'student_id' => $request->student_id,
            'title' => $request->title,
            'type' => $request->type,
            'points' => $request->points,
            'date' => $request->date,
            'reported_by' => $request->reported_by ?? (auth()->user()->name ?? 'School Admin'),
            'description' => $request->description,
        ]);

        return redirect()->route('students.behavior')->with('success', 'Behavior record logged successfully!');
    }

    // =========================================================================
    // 8. STUDENT HOUSES (/school/student-houses)
    // =========================================================================
    public function houses()
    {
        $school = School::first();
        $houses = StudentHouse::withCount('students')->get();

        return view('students.houses', compact('school', 'houses'));
    }

    public function storeHouse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'nullable|string',
            'code' => 'nullable|string',
            'captain' => 'nullable|string',
        ]);

        $school = School::first();
        StudentHouse::create([
            'school_id' => $school->id ?? 1,
            'name' => $request->name,
            'color' => $request->color ?? '#3b82f6',
            'code' => $request->code,
            'captain' => $request->captain,
            'description' => $request->description,
        ]);

        return redirect()->route('students.houses')->with('success', 'Student House added successfully!');
    }

    // =========================================================================
    // 9. STUDENT CATEGORIES (/school/student-categories)
    // =========================================================================
    public function categories()
    {
        $school = School::first();
        $categories = StudentCategory::withCount('students')->get();

        return view('students.categories', compact('school', 'categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'nullable|string',
        ]);

        $school = School::first();
        StudentCategory::create([
            'school_id' => $school->id ?? 1,
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect()->route('students.categories')->with('success', 'Student Category added successfully!');
    }

    // =========================================================================
    // 10. TC & EXIT MANAGEMENT (/school/student-exits)
    // =========================================================================
    public function exits(Request $request)
    {
        $school = School::first();
        $students = Student::where('status', 'active')->orderBy('first_name')->get();
        $exits = StudentExit::with('student.latestEnrollment.schoolClass')->orderBy('exit_date', 'desc')->paginate(15);

        return view('students.exits', compact('school', 'students', 'exits'));
    }

    public function storeExit(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'reason' => 'required|string',
            'exit_date' => 'required|date',
        ]);

        $school = School::first();
        $tcNo = 'TC-' . date('Y') . '-' . str_pad(StudentExit::count() + 1, 4, '0', STR_PAD_LEFT);

        StudentExit::create([
            'school_id' => $school->id ?? 1,
            'student_id' => $request->student_id,
            'tc_number' => $tcNo,
            'reason' => $request->reason,
            'exit_date' => $request->exit_date,
            'certificate_printed' => now()->format('Y-m-d'),
            'status' => 'TC Issued',
            'remarks' => $request->remarks ?? 'TC issued upon parent request. Cleared library and fee dues.',
        ]);

        Student::where('id', $request->student_id)->update(['status' => 'withdrawn']);

        return redirect()->route('students.exits')->with('success', 'Student Transfer Certificate (TC) #' . $tcNo . ' issued successfully!');
    }

    // =========================================================================
    // 11. STUDENT HEALTH RECORDS (/school/student-health)
    // =========================================================================
    public function health(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();

        $query = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section'])
            ->where('status', 'active');

        if ($request->filled('class_id')) {
            $query->whereHas('latestEnrollment', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('admission_no', 'like', "%{$s}%");
            });
        }

        $students = $query->orderBy('admission_no')->paginate(15);

        return view('students.health', compact('school', 'classes', 'students'));
    }

    public function updateHealth(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'blood_group' => 'nullable|string',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'allergies' => 'nullable|string',
            'medical_alerts' => 'nullable|string',
        ]);

        Student::where('id', $request->student_id)->update([
            'blood_group' => $request->blood_group,
            'height' => $request->height,
            'weight' => $request->weight,
            'allergies' => $request->allergies,
            'medical_alerts' => $request->medical_alerts,
        ]);

        return redirect()->back()->with('success', 'Health record updated successfully!');
    }

    // =========================================================================
    // 12. DELETED STUDENTS (RECYCLE BIN) (/school/students-recycle-bin)
    // =========================================================================
    public function recycleBin()
    {
        $school = School::first();
        $deletedStudents = Student::onlyTrashed()
            ->with(['latestEnrollment.schoolClass', 'latestEnrollment.section'])
            ->orderBy('deleted_at', 'desc')->paginate(15);

        return view('students.recycle_bin', compact('school', 'deletedStudents'));
    }

    public function restore($id)
    {
        $student = Student::onlyTrashed()->findOrFail($id);
        $student->restore();

        return redirect()->back()->with('success', 'Student #' . $student->admission_no . ' restored successfully!');
    }

    // =========================================================================
    // STUDENT PROFILE DETAILS & DELETE
    // =========================================================================
    public function show($id)
    {
        $school = School::first();
        $student = Student::with([
            'latestEnrollment.schoolClass',
            'latestEnrollment.section',
            'guardian',
            'house',
            'category',
            'behaviors',
            'feeAllocations.feeMaster.feeType',
            'feePayments',
            'attendances' => function ($q) {
                $q->latest()->take(30);
            },
            'examMarks.schedule.exam',
            'examMarks.schedule.subject'
        ])->findOrFail($id);

        $totalDays = $student->attendances->count();
        $presentDays = $student->attendances->where('status', 'present')->count();
        $attendancePercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 100;

        $totalFees = $student->feeAllocations->sum('final_amount');
        $paidFees = $student->feePayments->sum('amount_paid');
        $dueFees = max(0, $totalFees - $paidFees);

        return view('students.show', compact('student', 'school', 'attendancePercentage', 'totalFees', 'paidFees', 'dueFees'));
    }

    public function destroy(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update([
            'deleted_reason' => $request->reason ?? 'Admin removal',
            'deleted_by' => auth()->user()->name ?? 'school admin',
        ]);
        $student->delete(); // Soft delete

        return redirect()->route('students.index')->with('success', 'Student moved to Recycle Bin.');
    }
}
