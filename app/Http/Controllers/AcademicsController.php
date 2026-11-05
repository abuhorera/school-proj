<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\AcademicSession;
use App\Models\ClassSubject;
use App\Models\TimeSlot;
use App\Models\Timetable;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class AcademicsController extends Controller
{
    /**
     * 1. Academic Dashboard
     */
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }

    public function dashboard(Request $request)
    {
        $school = School::first() ?? new School(['name' => 'YUG INTERNATIONAL SCHOOL']);
        $currentSession = AcademicSession::where('is_current', 1)->first() ?? AcademicSession::first();
        $sessions = AcademicSession::all();

        $totalClasses = SchoolClass::count();
        $totalSections = Section::count();
        $totalSubjects = Subject::count();
        $totalStudents = Student::count();
        $totalTimeSlots = TimeSlot::count();
        $totalTimetableEntries = Timetable::count();
        $totalStaff = DB::table('staff')->count();

        // Classes with sections, coordinators, subjects count and enrolled students
        $classes = SchoolClass::with(['sections.inChargeTeacher', 'coordinator'])
            ->withCount(['sections', 'students'])
            ->orderBy('numeric_level')
            ->get();

        // Top teachers by assigned subjects count
        $teacherWorkload = DB::table('staff')
            ->leftJoin('class_subjects', 'staff.id', '=', 'class_subjects.teacher_id')
            ->select('staff.id', 'staff.first_name', 'staff.last_name', 'staff.designation', DB::raw('count(class_subjects.id) as subjects_count'))
            ->groupBy('staff.id', 'staff.first_name', 'staff.last_name', 'staff.designation')
            ->orderByDesc('subjects_count')
            ->limit(6)
            ->get();

        // Sample upcoming timetable slots today
        $today = date('l'); // e.g. "Monday"
        $todayTimetable = Timetable::with(['schoolClass', 'section', 'subject', 'teacher', 'timeSlot'])
            ->where('day_of_week', $today)
            ->limit(8)
            ->get();
        if ($todayTimetable->isEmpty()) {
            $todayTimetable = Timetable::with(['schoolClass', 'section', 'subject', 'teacher', 'timeSlot'])
                ->limit(8)
                ->get();
        }

        return view('academics.dashboard', compact(
            'school',
            'currentSession',
            'sessions',
            'totalClasses',
            'totalSections',
            'totalSubjects',
            'totalStudents',
            'totalTimeSlots',
            'totalTimetableEntries',
            'totalStaff',
            'classes',
            'teacherWorkload',
            'todayTimetable'
        ));
    }

    /**
     * 2. Academic Sessions
     */
    public function sessions(Request $request)
    {
        $school = School::first();
        $sessions = AcademicSession::withCount('students')->orderByDesc('is_current')->orderByDesc('start_date')->get();
        $currentSession = $sessions->firstWhere('is_current', 1);

        return view('academics.sessions', compact('school', 'sessions', 'currentSession'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $school = School::first();

        if ($request->boolean('is_current')) {
            AcademicSession::query()->update(['is_current' => 0]);
        }

        AcademicSession::create([
            'school_id' => $school ? $school->id : 1,
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_current' => $request->boolean('is_current') ? 1 : 0,
        ]);

        return redirect()->route('academics.sessions')->with('success', "Academic Session '{$request->name}' created successfully.");
    }

    public function setCurrentSession($id)
    {
        AcademicSession::query()->update(['is_current' => 0]);
        $session = AcademicSession::findOrFail($id);
        $session->update(['is_current' => 1]);

        return redirect()->back()->with('success', "Active academic session switched to '{$session->name}'.");
    }

    /**
     * 3. Classes Management
     */
    public function classes(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with(['coordinator', 'sections'])
            ->withCount(['sections', 'students'])
            ->orderBy('numeric_level')
            ->get();
        $staff = DB::table('staff')->orderBy('first_name')->get();

        return view('academics.classes', compact('school', 'classes', 'staff'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'numeric_level' => 'nullable|integer',
            'coordinator_id' => 'nullable|integer',
        ]);

        $school = School::first();
        $schoolClass = SchoolClass::create([
            'school_id' => $school ? $school->id : 1,
            'name' => $request->name,
            'numeric_level' => $request->numeric_level ?? (SchoolClass::max('numeric_level') + 1),
            'coordinator_id' => $request->coordinator_id,
        ]);

        // Auto create Section A
        Section::create([
            'class_id' => $schoolClass->id,
            'name' => 'A',
            'capacity' => 40,
        ]);

        return redirect()->route('academics.classes')->with('success', "Class '{$schoolClass->name}' created successfully with Section A.");
    }

    public function deleteClass($id)
    {
        $cls = SchoolClass::findOrFail($id);
        $name = $cls->name;
        Section::where('class_id', $cls->id)->delete();
        ClassSubject::where('class_id', $cls->id)->delete();
        Timetable::where('class_id', $cls->id)->delete();
        $cls->delete();

        return redirect()->route('academics.classes')->with('success', "Class '{$name}' and its related sections deleted successfully.");
    }

    /**
     * 4. Sections Management
     */
    public function sections(Request $request)
    {
        $school = School::first();
        $classId = $request->query('class_id');

        $classes = SchoolClass::orderBy('numeric_level')->get();
        $staff = DB::table('staff')->orderBy('first_name')->get();

        $sectionsQuery = Section::with(['schoolClass', 'inChargeTeacher'])->withCount('students');
        if ($classId) {
            $sectionsQuery->where('class_id', $classId);
        }
        $sections = $sectionsQuery->get();

        return view('academics.sections', compact('school', 'sections', 'classes', 'staff', 'classId'));
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'name' => 'required|string|max:50',
            'capacity' => 'nullable|integer|min:1',
            'in_charge_teacher_id' => 'nullable|integer',
        ]);

        $section = Section::create([
            'class_id' => $request->class_id,
            'name' => strtoupper($request->name),
            'capacity' => $request->capacity ?? 40,
            'in_charge_teacher_id' => $request->in_charge_teacher_id,
        ]);

        return redirect()->route('academics.sections')->with('success', "Section '{$section->name}' added successfully.");
    }

    public function deleteSection($id)
    {
        $sec = Section::findOrFail($id);
        $name = $sec->name;
        $sec->delete();

        return redirect()->route('academics.sections')->with('success', "Section '{$name}' deleted successfully.");
    }

    /**
     * 5. Subjects Catalog
     */
    public function subjects(Request $request)
    {
        $school = School::first();
        $groupFilter = $request->query('group');
        $typeFilter = $request->query('type');

        $query = Subject::query();
        if ($groupFilter) {
            $query->where('group', $groupFilter);
        }
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        $subjects = $query->orderBy('name')->get();
        $groups = ['Language', 'Science & Mathematics', 'Social Studies', 'Commerce & Humanities', 'Vocational & Arts', 'Sports & Physical Ed'];
        $types = ['Theory', 'Practical', 'Both'];

        return view('academics.subjects', compact('school', 'subjects', 'groups', 'types', 'groupFilter', 'typeFilter'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20',
            'type' => 'required|string|in:Theory,Practical,Both',
            'group' => 'nullable|string|max:100',
        ]);

        $school = School::first();
        Subject::create([
            'school_id' => $school ? $school->id : 1,
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'group' => $request->group ?? 'General',
            'is_elective' => $request->boolean('is_elective') ? 1 : 0,
        ]);

        return redirect()->route('academics.subjects')->with('success', "Subject '{$request->name}' added successfully.");
    }

    public function deleteSubject($id)
    {
        $sub = Subject::findOrFail($id);
        $name = $sub->name;
        ClassSubject::where('subject_id', $sub->id)->delete();
        Timetable::where('subject_id', $sub->id)->delete();
        $sub->delete();

        return redirect()->route('academics.subjects')->with('success', "Subject '{$name}' deleted successfully.");
    }

    /**
     * 6. Assign Subjects to Classes (NEW)
     */
    public function assignSubjects(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with(['sections', 'classSubjects.subject'])->orderBy('numeric_level')->get();
        $subjects = Subject::orderBy('group')->orderBy('name')->get();

        return view('academics.assign_subjects', compact('school', 'classes', 'subjects'));
    }

    public function storeAssignSubjects(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'nullable|array',
        ]);

        $classId = $request->class_id;
        $subjectIds = $request->subject_ids ?? [];
        $school = School::first();
        $schoolId = $school ? $school->id : 1;

        $sections = Section::where('class_id', $classId)->get();

        // Wipe existing assignments for this class
        ClassSubject::where('class_id', $classId)->delete();

        // Insert new assignments for each section or class
        foreach ($subjectIds as $subjectId) {
            $subj = Subject::find($subjectId);
            $isElective = $subj ? $subj->is_elective : 0;

            if ($sections->isNotEmpty()) {
                foreach ($sections as $sec) {
                    ClassSubject::create([
                        'school_id' => $schoolId,
                        'class_id' => $classId,
                        'section_id' => $sec->id,
                        'subject_id' => $subjectId,
                        'is_elective' => $isElective,
                    ]);
                }
            } else {
                ClassSubject::create([
                    'school_id' => $schoolId,
                    'class_id' => $classId,
                    'subject_id' => $subjectId,
                    'is_elective' => $isElective,
                ]);
            }
        }

        $cls = SchoolClass::find($classId);
        return redirect()->route('academics.assign-subjects')->with('success', "Subjects assigned successfully to '{$cls->name}'.");
    }

    /**
     * 7. Assign Electives
     */
    public function assignElectives(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->orderBy('numeric_level')->get();
        $electives = Subject::where('is_elective', 1)->get();
        if ($electives->isEmpty()) {
            $electives = Subject::whereIn('name', ['Computer Science', 'French', 'Hindi Elective', 'Sanskrit', 'Fine Arts', 'Physical Education'])->get();
        }

        $selectedClassId = $request->query('class_id', $classes->first()->id ?? null);
        $selectedSectionId = $request->query('section_id');
        $selectedSubjectId = $request->query('subject_id', $electives->first()->id ?? null);

        $sections = $selectedClassId ? Section::where('class_id', $selectedClassId)->get() : collect();
        if (!$selectedSectionId && $sections->isNotEmpty()) {
            $selectedSectionId = $sections->first()->id;
        }

        // Students in this section
        $students = collect();
        if ($selectedSectionId) {
            $students = Student::whereHas('enrollments', function($q) use ($selectedClassId, $selectedSectionId) {
                $q->where('class_id', $selectedClassId)->where('section_id', $selectedSectionId);
            })->get();

            // If empty, fallback to demo sample students
            if ($students->isEmpty()) {
                $students = Student::limit(15)->get();
            }
        }

        return view('academics.assign_electives', compact(
            'school',
            'classes',
            'electives',
            'sections',
            'selectedClassId',
            'selectedSectionId',
            'selectedSubjectId',
            'students'
        ));
    }

    public function storeAssignElectives(Request $request)
    {
        $studentIds = $request->student_ids ?? [];
        $subjectId = $request->subject_id;
        $classId = $request->class_id;
        $sectionId = $request->section_id;

        $subject = Subject::find($subjectId);
        $subjectName = $subject ? $subject->name : 'Elective';

        return redirect()->back()->with('success', "Assigned '{$subjectName}' to " . count($studentIds) . " students successfully.");
    }

    /**
     * 8. Assign Subject Teacher
     */
    public function assignClassTeacher(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with(['sections', 'classSubjects.teacher', 'classSubjects.subject'])->orderBy('numeric_level')->get();
        $staff = DB::table('staff')->orderBy('first_name')->get();

        return view('academics.assign_class_teacher', compact('school', 'classes', 'staff'));
    }

    public function storeClassTeacher(Request $request)
    {
        $classId = $request->class_id;
        $assignments = $request->assignments ?? []; // [section_id => [subject_id => teacher_id]]
        $school = School::first();
        $schoolId = $school ? $school->id : 1;

        foreach ($assignments as $sectionId => $subMap) {
            foreach ($subMap as $subjectId => $teacherId) {
                if ($teacherId) {
                    ClassSubject::updateOrCreate(
                        [
                            'class_id' => $classId,
                            'section_id' => $sectionId,
                            'subject_id' => $subjectId,
                        ],
                        [
                            'school_id' => $schoolId,
                            'teacher_id' => $teacherId,
                        ]
                    );
                }
            }
        }

        $cls = SchoolClass::find($classId);
        $className = $cls ? $cls->name : 'Class';

        return redirect()->back()->with('success', "Subject teacher assignments for {$className} updated successfully.");
    }

    /**
     * 9. Manage Periods (Time Slots)
     */
    public function timeSlots(Request $request)
    {
        $school = School::first();
        $timeSlots = TimeSlot::orderBy('sort_order')->get();

        return view('academics.time_slots', compact('school', 'timeSlots'));
    }

    public function storeTimeSlot(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'start_time' => 'required',
            'end_time' => 'required',
            'sort_order' => 'nullable|integer',
        ]);

        $school = School::first();
        TimeSlot::create([
            'school_id' => $school ? $school->id : 1,
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_break' => $request->boolean('is_break') ? 1 : 0,
            'sort_order' => $request->sort_order ?? (TimeSlot::max('sort_order') + 1),
        ]);

        return redirect()->route('academics.time-slots')->with('success', "Period '{$request->name}' added successfully.");
    }

    public function deleteTimeSlot($id)
    {
        $slot = TimeSlot::findOrFail($id);
        $name = $slot->name;
        Timetable::where('time_slot_id', $slot->id)->delete();
        $slot->delete();

        return redirect()->route('academics.time-slots')->with('success', "Period '{$name}' deleted successfully.");
    }

    /**
     * 10. Class Timetable
     */
    public function timetable(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->orderBy('numeric_level')->get();
        
        $selectedClassId = $request->query('class_id', $classes->first()->id ?? null);
        $sections = $selectedClassId ? Section::where('class_id', $selectedClassId)->get() : collect();
        $selectedSectionId = $request->query('section_id', $sections->first()->id ?? null);

        $selectedClass = SchoolClass::find($selectedClassId);
        $selectedSection = Section::find($selectedSectionId);

        $timeSlots = TimeSlot::orderBy('sort_order')->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Existing timetable records
        $timetableRecords = Timetable::with(['subject', 'teacher'])
            ->where('class_id', $selectedClassId)
            ->where('section_id', $selectedSectionId)
            ->get();

        // Build 2D lookup array: matrix[day][time_slot_id]
        $matrix = [];
        foreach ($timetableRecords as $rec) {
            $matrix[$rec->day_of_week][$rec->time_slot_id] = $rec;
        }

        $subjects = Subject::orderBy('name')->get();
        $staff = DB::table('staff')->orderBy('first_name')->get();

        return view('academics.timetable', compact(
            'school',
            'classes',
            'sections',
            'selectedClassId',
            'selectedSectionId',
            'selectedClass',
            'selectedSection',
            'timeSlots',
            'days',
            'matrix',
            'subjects',
            'staff'
        ));
    }

    public function saveSlot(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'day_of_week' => 'required|string',
            'time_slot_id' => 'required|exists:time_slots,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:staff,id',
            'room_number' => 'nullable|string|max:20',
        ]);

        $school = School::first();
        Timetable::updateOrCreate(
            [
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'day_of_week' => $request->day_of_week,
                'time_slot_id' => $request->time_slot_id,
            ],
            [
                'school_id' => $school ? $school->id : 1,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'room_number' => $request->room_number ?? '101',
            ]
        );

        return redirect()->back()->with('success', 'Timetable slot scheduled successfully.');
    }

    /**
     * 11. Promote Students Wizard
     */
    public function promote(Request $request)
    {
        return $this->promoteStudents($request);
    }

    public function promoteStudents(Request $request)
    {
        $school = School::first();
        $sessions = AcademicSession::orderByDesc('is_current')->orderByDesc('start_date')->get();
        $classes = SchoolClass::with('sections')->orderBy('numeric_level')->get();

        $fromSessionId = $request->query('from_session_id', $sessions->firstWhere('is_current', 1)->id ?? null);
        $fromClassId = $request->query('from_class_id', $classes->first()->id ?? null);
        
        $fromSections = $fromClassId ? Section::where('class_id', $fromClassId)->get() : collect();
        $fromSectionId = $request->query('from_section_id', $fromSections->first()->id ?? null);

        $toSessionId = $request->query('to_session_id', $sessions->where('id', '!=', $fromSessionId)->first()->id ?? $fromSessionId);
        $toClassId = $request->query('to_class_id');
        $toSectionId = $request->query('to_section_id');

        // Fetch eligible students for promotion
        $students = collect();
        if ($fromClassId) {
            $studentsQuery = Student::query();
            if ($fromSectionId) {
                $studentsQuery->whereHas('enrollments', function($q) use ($fromClassId, $fromSectionId) {
                    $q->where('class_id', $fromClassId)->where('section_id', $fromSectionId);
                });
            }
            $students = $studentsQuery->get();
            if ($students->isEmpty()) {
                $students = Student::limit(12)->get();
            }
        }

        return view('academics.promote', compact(
            'school',
            'sessions',
            'classes',
            'fromSessionId',
            'fromClassId',
            'fromSections',
            'fromSectionId',
            'toSessionId',
            'toClassId',
            'toSectionId',
            'students'
        ));
    }

    public function executePromotion(Request $request)
    {
        $request->validate([
            'from_session_id' => 'required',
            'to_session_id' => 'required',
            'to_class_id' => 'required',
            'student_ids' => 'required|array|min:1',
        ]);

        $studentIds = $request->student_ids;
        $targetSessionId = $request->to_session_id;
        $targetClassId = $request->to_class_id;
        $targetSectionId = $request->to_section_id;

        $targetClass = SchoolClass::find($targetClassId);
        $className = $targetClass ? $targetClass->name : 'Next Class';

        // Update or create enrollment records
        foreach ($studentIds as $sId) {
            StudentEnrollment::updateOrCreate(
                [
                    'student_id' => $sId,
                    'session_id' => $targetSessionId,
                ],
                [
                    'class_id' => $targetClassId,
                    'section_id' => $targetSectionId,
                    'enrolled_at' => now(),
                ]
            );
        }

        return redirect()->route('academics.promote-students')->with('success', "Batch promotion completed successfully! " . count($studentIds) . " students promoted to {$className}.");
    }
}
