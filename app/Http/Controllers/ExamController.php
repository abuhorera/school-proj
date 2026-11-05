<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\ExamSchedule;
use App\Models\ExamMark;
use App\Models\Grade;
use App\Models\CocurricularGrade;
use App\Models\UploadedMarksheet;
use App\Models\StudentExamRemark;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\AcademicSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExamController extends Controller
{
    /**
     * 1. Offline Exam Command Dashboard
     */
    public function dashboard(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $totalExams = Exam::where('school_id', $schoolId)->count();
        $activeExams = Exam::where('school_id', $schoolId)->where('status', 'Active')->count();
        $upcomingExams = Exam::where('school_id', $schoolId)->where('status', 'Upcoming')->count();
        $publishedMarksheets = UploadedMarksheet::where('school_id', $schoolId)->where('is_published', true)->count();

        // Calculate marks entry progress
        $totalSchedules = ExamSchedule::whereHas('exam', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->count();

        $scoredSchedules = ExamSchedule::whereHas('exam', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->has('marks')->count();

        $marksEntryPct = $totalSchedules > 0 ? round(($scoredSchedules / $totalSchedules) * 100, 1) : 0;

        // Breakdown by Exam Types
        $examTypes = ExamType::where('school_id', $schoolId)->orderBy('sort_order')->get();

        // Recent / Active Exams List
        $exams = Exam::where('school_id', $schoolId)
            ->with(['academicSession', 'schedules.schoolClass', 'schedules.subject'])
            ->latest()
            ->take(6)
            ->get();

        // Standard Grades for quick reference
        $grades = Grade::where('school_id', $schoolId)->orderBy('min_percentage', 'desc')->get();

        return view('exams.dashboard', compact(
            'school',
            'totalExams',
            'activeExams',
            'upcomingExams',
            'publishedMarksheets',
            'totalSchedules',
            'scoredSchedules',
            'marksEntryPct',
            'examTypes',
            'exams',
            'grades'
        ));
    }

    /**
     * 2. Manage Offline Exams Directory
     */
    public function index(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $query = Exam::where('school_id', $schoolId)->with(['academicSession', 'schedules.schoolClass']);

        if ($request->filled('session_id')) {
            $query->where('session_id', $request->session_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $exams = $query->latest()->paginate(15)->withQueryString();
        $sessions = AcademicSession::where('school_id', $schoolId)->orderBy('name', 'desc')->get();
        $types = ExamType::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        return view('exams.index', compact('school', 'exams', 'sessions', 'types', 'classes'));
    }

    /**
     * Add Exam Form
     */
    public function create()
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $sessions = AcademicSession::where('school_id', $schoolId)->orderBy('name', 'desc')->get();
        $types = ExamType::where('school_id', $schoolId)->orderBy('sort_order')->get();
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        return view('exams.create', compact('school', 'sessions', 'types', 'classes'));
    }

    /**
     * Store new Exam
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'academic_session_id' => 'nullable|integer',
            'session_id' => 'nullable|integer',
            'type' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'description' => 'nullable|string',
            'class_ids' => 'nullable|array',
            'status' => 'nullable|string',
        ]);

        $schoolId = 1;
        $sessionId = $validated['academic_session_id'] ?? $validated['session_id'] ?? 1;

        $exam = Exam::create([
            'school_id' => $schoolId,
            'session_id' => $sessionId,
            'name' => $validated['name'],
            'type' => $validated['type'] ?? 'Term Exam',
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'description' => $validated['description'] ?? null,
            'class_ids' => $validated['class_ids'] ?? [],
            'status' => $validated['status'] ?? 'Active',
            'is_published' => true,
        ]);

        return redirect()->route('exams.index')->with('success', "Exam '{$exam->name}' created successfully!");
    }

    /**
     * Delete Exam
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $name = $exam->name;
        $exam->schedules()->delete();
        $exam->delete();

        return redirect()->back()->with('success', "Exam '{$name}' deleted successfully.");
    }

    /**
     * 3. Manage Exam Types
     */
    public function examTypes(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $types = ExamType::where('school_id', $schoolId)->orderBy('sort_order')->get();

        return view('exams.types', compact('school', 'types'));
    }

    public function storeExamType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'abbreviation' => 'nullable|string|max:20',
            'weightage' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $schoolId = 1;
        $maxOrder = ExamType::where('school_id', $schoolId)->max('sort_order') ?? 0;

        ExamType::create([
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'abbreviation' => $validated['abbreviation'] ?? strtoupper(substr($validated['name'], 0, 3)),
            'weightage' => $validated['weightage'] ?? '100%',
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ($maxOrder + 1),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Exam Type '{$validated['name']}' saved successfully!");
    }

    public function destroyExamType($id)
    {
        $type = ExamType::findOrFail($id);
        $name = $type->name;
        $type->delete();

        return redirect()->back()->with('success', "Exam Type '{$name}' removed.");
    }

    /**
     * 4. Exam Schedule & Marks Setup
     */
    public function schedule(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $exams = Exam::where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();
        $selectedExamId = $request->get('exam_id') ?? ($exams->first() ? $exams->first()->id : null);
        $selectedExam = $selectedExamId ? Exam::find($selectedExamId) : null;

        $classes = SchoolClass::where('school_id', $schoolId)
            ->with(['subjects', 'sections'])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $allSubjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();

        $schedules = ExamSchedule::where('exam_id', $selectedExamId)
            ->with(['schoolClass', 'subject', 'marks'])
            ->orderBy('exam_date')
            ->get();

        return view('exams.schedule', compact(
            'school',
            'exams',
            'selectedExamId',
            'selectedExam',
            'classes',
            'allSubjects',
            'schedules'
        ));
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|integer',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'exam_date' => 'required|date',
            'start_time' => 'nullable|string',
            'end_time' => 'nullable|string',
            'room_no' => 'nullable|string',
            'max_marks' => 'required|numeric',
            'pass_marks' => 'required|numeric',
        ]);

        ExamSchedule::updateOrCreate(
            [
                'exam_id' => $validated['exam_id'],
                'class_id' => $validated['class_id'],
                'subject_id' => $validated['subject_id'],
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Exam schedule slot and marks criteria saved successfully!');
    }

    public function destroySchedule($id)
    {
        $schedule = ExamSchedule::findOrFail($id);
        $schedule->marks()->delete();
        $schedule->delete();

        return redirect()->back()->with('success', 'Exam schedule component removed.');
    }

    /**
     * 5. Enter Marks (Criteria Selection & Student Matrix)
     */
    public function enterMarks(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $exams = Exam::where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();

        $examId = $request->get('exam_id');
        $classId = $request->get('class_id');
        $sectionId = $request->get('section_id');
        $subjectId = $request->get('subject_id');

        $schedule = null;
        $students = collect();
        $existingMarks = collect();

        if ($examId && $classId && $subjectId) {
            $schedule = ExamSchedule::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->where('subject_id', $subjectId)
                ->first();

            // Fetch students enrolled in class/section
            $studentQuery = Student::where('school_id', $schoolId)
                ->whereHas('latestEnrollment', function ($q) use ($classId, $sectionId) {
                    $q->where('class_id', $classId);
                    if ($sectionId) {
                        $q->where('section_id', $sectionId);
                    }
                });

            $students = $studentQuery->with(['latestEnrollment.section'])->orderBy('first_name')->get();

            if ($schedule) {
                $existingMarks = ExamMark::where('exam_schedule_id', $schedule->id)->get()->keyBy('student_id');
            }
        }

        $grades = Grade::where('school_id', $schoolId)->orderBy('min_percentage', 'desc')->get();

        return view('exams.enter_marks', compact(
            'school',
            'exams',
            'classes',
            'sections',
            'subjects',
            'examId',
            'classId',
            'sectionId',
            'subjectId',
            'schedule',
            'students',
            'existingMarks',
            'grades'
        ));
    }

    public function saveMarks(Request $request)
    {
        $validated = $request->validate([
            'exam_schedule_id' => 'required|integer',
            'marks' => 'required|array',
            'absent' => 'nullable|array',
            'remarks' => 'nullable|array',
        ]);

        $schedule = ExamSchedule::findOrFail($validated['exam_schedule_id']);
        $maxMarks = $schedule->max_marks > 0 ? $schedule->max_marks : 100;

        $grades = Grade::where('school_id', 1)->orderBy('min_percentage', 'desc')->get();

        foreach ($validated['marks'] as $studentId => $score) {
            $isAbsent = isset($validated['absent'][$studentId]);
            $scoreVal = $isAbsent ? 0 : floatval($score);

            // Compute Grade
            $percentage = $maxMarks > 0 ? ($scoreVal / $maxMarks) * 100 : 0;
            $matchedGrade = 'F';

            if (!$isAbsent) {
                foreach ($grades as $g) {
                    if ($percentage >= $g->min_percentage && $percentage <= $g->max_percentage) {
                        $matchedGrade = $g->name;
                        break;
                    }
                }
            } else {
                $matchedGrade = 'AB';
            }

            ExamMark::updateOrCreate(
                [
                    'exam_schedule_id' => $schedule->id,
                    'student_id' => $studentId,
                ],
                [
                    'marks_obtained' => $scoreVal,
                    'is_absent' => $isAbsent,
                    'grade' => $matchedGrade,
                    'remarks' => $validated['remarks'][$studentId] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Student examination marks saved successfully!');
    }

    /**
     * 6. Cocurricular Grades
     */
    public function cocurricularGrades(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $exams = Exam::where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();

        $classId = $request->get('class_id');
        $sectionId = $request->get('section_id');
        $examId = $request->get('exam_id');

        $areas = [
            'Work Education (Cookery, Gardening)',
            'Art Education (Drawing & Sketching)',
            'Health & Physical Education (Sports)',
            'Discipline & Moral Ethics',
        ];

        $students = collect();
        $existingGrades = collect();

        if ($classId && $examId) {
            $students = Student::where('school_id', $schoolId)
                ->whereHas('latestEnrollment', function ($q) use ($classId, $sectionId) {
                    $q->where('class_id', $classId);
                    if ($sectionId) {
                        $q->where('section_id', $sectionId);
                    }
                })
                ->with('latestEnrollment.section')
                ->orderBy('first_name')
                ->get();

            $existingGrades = CocurricularGrade::where('exam_id', $examId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->groupBy('student_id');
        }

        return view('exams.cocurricular_grades', compact(
            'school',
            'classes',
            'sections',
            'exams',
            'classId',
            'sectionId',
            'examId',
            'areas',
            'students',
            'existingGrades'
        ));
    }

    public function saveCocurricularGrades(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|integer',
            'grades' => 'required|array',
        ]);

        $examId = $validated['exam_id'];
        $schoolId = 1;

        foreach ($validated['grades'] as $studentId => $areas) {
            foreach ($areas as $areaName => $gradeVal) {
                CocurricularGrade::updateOrCreate(
                    [
                        'school_id' => $schoolId,
                        'exam_id' => $examId,
                        'student_id' => $studentId,
                        'area_name' => $areaName,
                    ],
                    [
                        'grade' => $gradeVal,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Co-curricular grades evaluated and saved successfully!');
    }

    /**
     * 7. Manage Grading Scales Master
     */
    public function grades(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $grades = Grade::where('school_id', $schoolId)->orderBy('min_percentage', 'desc')->get();

        return view('exams.grades', compact('school', 'grades'));
    }

    public function storeGrade(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:20',
            'min_percentage' => 'required|numeric',
            'max_percentage' => 'required|numeric',
            'grade_point' => 'nullable|numeric',
            'description' => 'nullable|string|max:191',
        ]);

        $schoolId = 1;

        Grade::create([
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'min_percentage' => $validated['min_percentage'],
            'max_percentage' => $validated['max_percentage'],
            'grade_point' => $validated['grade_point'] ?? 0,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', "Grade tier '{$validated['name']}' saved successfully!");
    }

    public function destroyGrade($id)
    {
        $grade = Grade::findOrFail($id);
        $name = $grade->name;
        $grade->delete();

        return redirect()->back()->with('success', "Grade tier '{$name}' deleted.");
    }

    /**
     * 8. Generate Marksheets & Report Cards Directory
     */
    public function marksheet(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $exams = Exam::where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();

        $classId = $request->get('class_id');
        $sectionId = $request->get('section_id');
        $examId = $request->get('exam_id') ?? ($exams->first() ? $exams->first()->id : null);

        $students = collect();

        if ($classId) {
            $students = Student::where('school_id', $schoolId)
                ->whereHas('latestEnrollment', function ($q) use ($classId, $sectionId) {
                    $q->where('class_id', $classId);
                    if ($sectionId) {
                        $q->where('section_id', $sectionId);
                    }
                })
                ->with(['latestEnrollment.schoolClass', 'latestEnrollment.section'])
                ->orderBy('first_name')
                ->get();
        }

        return view('exams.marksheet', compact(
            'school',
            'classes',
            'sections',
            'exams',
            'classId',
            'sectionId',
            'examId',
            'students'
        ));
    }

    /**
     * Printable Holistic CBSE Report Card
     */
    public function showMarksheet($studentId, $examId = null)
    {
        $school = School::first();
        $student = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'guardian'])->findOrFail($studentId);

        $exam = $examId ? Exam::findOrFail($examId) : Exam::latest()->first();

        $marks = ExamMark::with('schedule.subject')
            ->where('student_id', $student->id)
            ->whereHas('schedule', function ($q) use ($exam) {
                if ($exam) {
                    $q->where('exam_id', $exam->id);
                }
            })->get();

        $cocurricular = CocurricularGrade::where('student_id', $student->id)
            ->where('exam_id', $exam ? $exam->id : 1)
            ->get();

        $remark = StudentExamRemark::where('student_id', $student->id)
            ->where('exam_id', $exam ? $exam->id : 1)
            ->first();

        $totalScored = $marks->where('is_absent', false)->sum('marks_obtained');
        $totalMax = $marks->sum(function ($m) {
            return $m->schedule->max_marks ?? 100;
        });

        $percentage = $totalMax > 0 ? round(($totalScored / $totalMax) * 100, 1) : 0;

        $grades = Grade::where('school_id', 1)->orderBy('min_percentage', 'desc')->get();
        $overallGrade = 'D';
        foreach ($grades as $g) {
            if ($percentage >= $g->min_percentage && $percentage <= $g->max_percentage) {
                $overallGrade = $g->name;
                break;
            }
        }

        return view('exams.report_card', compact(
            'school',
            'student',
            'exam',
            'marks',
            'cocurricular',
            'remark',
            'totalScored',
            'totalMax',
            'percentage',
            'overallGrade'
        ));
    }

    /**
     * 9. Upload External PDF Marksheet
     */
    public function uploadMarksheet()
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $exams = Exam::where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();
        $students = Student::where('school_id', $schoolId)->orderBy('first_name')->get();

        return view('exams.marksheet_upload', compact('school', 'classes', 'sections', 'exams', 'students'));
    }

    public function storeUploadedMarksheet(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'nullable|integer',
            'section_id' => 'nullable|integer',
            'student_id' => 'required|integer',
            'exam_id' => 'nullable|integer',
            'title' => 'required|string|max:191',
            'marksheet_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('marksheet_file')) {
            $filePath = $request->file('marksheet_file')->store('marksheets', 'public');
        } else {
            $filePath = 'marksheets/external_report_' . time() . '.pdf';
        }

        UploadedMarksheet::create([
            'school_id' => 1,
            'student_id' => $validated['student_id'],
            'exam_id' => $validated['exam_id'] ?? null,
            'class_id' => $validated['class_id'] ?? 1,
            'section_id' => $validated['section_id'] ?? 1,
            'title' => $validated['title'],
            'file_path' => $filePath,
            'is_published' => true,
        ]);

        return redirect()->route('exams.marksheet.upload-list')->with('success', 'External marksheet uploaded successfully!');
    }

    /**
     * 10. Manage Uploaded External Marksheets
     */
    public function uploadList(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $uploads = UploadedMarksheet::where('school_id', $schoolId)
            ->with(['student.latestEnrollment.schoolClass', 'student.latestEnrollment.section', 'exam'])
            ->latest()
            ->paginate(15);

        return view('exams.marksheet_upload_list', compact('school', 'uploads'));
    }

    public function togglePublishUpload($id)
    {
        $upload = UploadedMarksheet::findOrFail($id);
        $upload->is_published = !$upload->is_published;
        $upload->save();

        $statusStr = $upload->is_published ? 'Published' : 'Hidden';
        return redirect()->back()->with('success', "Marksheet {$upload->title} is now {$statusStr}.");
    }

    public function destroyUpload($id)
    {
        $upload = UploadedMarksheet::findOrFail($id);
        $title = $upload->title;
        $upload->delete();

        return redirect()->back()->with('success', "Marksheet '{$title}' deleted.");
    }

    /**
     * 11. Teacher Remarks on Report Card
     */
    public function marksheetRemarks(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $exams = Exam::where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();

        $classId = $request->get('class_id');
        $sectionId = $request->get('section_id');
        $examId = $request->get('exam_id') ?? ($exams->first() ? $exams->first()->id : null);

        $students = collect();
        $existingRemarks = collect();

        if ($classId && $examId) {
            $students = Student::where('school_id', $schoolId)
                ->whereHas('latestEnrollment', function ($q) use ($classId, $sectionId) {
                    $q->where('class_id', $classId);
                    if ($sectionId) {
                        $q->where('section_id', $sectionId);
                    }
                })
                ->with('latestEnrollment.section')
                ->orderBy('first_name')
                ->get();

            $existingRemarks = StudentExamRemark::where('exam_id', $examId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
        }

        return view('exams.marksheet_remarks', compact(
            'school',
            'classes',
            'sections',
            'exams',
            'classId',
            'sectionId',
            'examId',
            'students',
            'existingRemarks'
        ));
    }

    public function saveMarksheetRemarks(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|integer',
            'remarks' => 'required|array',
            'conduct' => 'nullable|array',
            'attendance' => 'nullable|array',
        ]);

        $schoolId = 1;
        $examId = $validated['exam_id'];

        foreach ($validated['remarks'] as $studentId => $teacherNotes) {
            StudentExamRemark::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'student_id' => $studentId,
                    'exam_id' => $examId,
                ],
                [
                    'class_teacher_remarks' => $teacherNotes,
                    'conduct' => $validated['conduct'][$studentId] ?? 'Exemplary',
                    'attendance_percentage' => floatval($validated['attendance'][$studentId] ?? 92.0),
                ]
            );
        }

        return redirect()->back()->with('success', 'Class teacher remarks and conduct ratings recorded successfully!');
    }

    /**
     * 12. Bulk Download & Print Admit Cards (Hall Tickets)
     */
    public function admitCards(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $exams = Exam::where('school_id', $schoolId)->orderBy('start_date', 'desc')->get();
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();

        $examId = $request->get('exam_id') ?? ($exams->first() ? $exams->first()->id : null);
        $classId = $request->get('class_id');
        $sectionId = $request->get('section_id');

        $students = collect();
        $selectedExam = $examId ? Exam::find($examId) : null;
        $schedules = collect();

        if ($classId && $examId) {
            $students = Student::where('school_id', $schoolId)
                ->whereHas('latestEnrollment', function ($q) use ($classId, $sectionId) {
                    $q->where('class_id', $classId);
                    if ($sectionId) {
                        $q->where('section_id', $sectionId);
                    }
                })
                ->with(['latestEnrollment.schoolClass', 'latestEnrollment.section'])
                ->orderBy('first_name')
                ->get();

            $schedules = ExamSchedule::where('exam_id', $examId)
                ->where('class_id', $classId)
                ->with('subject')
                ->orderBy('exam_date')
                ->get();
        }

        return view('exams.admit_cards', compact(
            'school',
            'exams',
            'classes',
            'sections',
            'examId',
            'classId',
            'sectionId',
            'selectedExam',
            'students',
            'schedules'
        ));
    }

    public function printAdmitCard($studentId, $examId = null)
    {
        $school = School::first();
        $student = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'guardian'])->findOrFail($studentId);
        $exam = $examId ? Exam::findOrFail($examId) : Exam::latest()->first();

        $classId = $student->latestEnrollment ? $student->latestEnrollment->class_id : null;

        $schedules = ExamSchedule::where('exam_id', $exam ? $exam->id : null)
            ->where('class_id', $classId)
            ->with('subject')
            ->orderBy('exam_date')
            ->get();

        return view('exams.admit_card_print', compact('school', 'student', 'exam', 'schedules'));
    }
}
