<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Notice;
use App\Models\Event;
use App\Models\Section;
use App\Models\StudySyllabus;
use App\Models\StudyMaterial;
use App\Models\StudyClasswork;
use App\Models\StudyHomework;
use App\Models\StudyHomeworkSubmission;
use Illuminate\Support\Facades\DB;

class ModulesController extends Controller
{
    // =========================================================================
    // 1. LIVE CLASSES
    // =========================================================================
    public function liveClasses(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'manage');
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $sessions = DB::table('live_class_sessions')->latest()->get();

        return view('modules.live_classes', compact('school', 'tab', 'classes', 'subjects', 'sessions'));
    }

    public function storeLiveClass(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'platform' => 'required|string',
            'class_name' => 'required|string',
        ]);

        DB::table('live_class_sessions')->insert([
            'title' => $request->input('title'),
            'platform' => $request->input('platform', 'Zoom'),
            'meeting_id' => 'MC-' . rand(100000, 999999),
            'join_url' => $request->input('join_url') ?: ('https://zoom.us/j/' . rand(100000000, 999999999)),
            'host_name' => $request->input('host_name') ?: 'Prof. Rajesh Sharma',
            'class_name' => $request->input('class_name'),
            'subject_name' => $request->input('subject_name') ?: 'General Studies',
            'start_time' => $request->input('start_time') ?: now()->addDay()->format('Y-m-d 10:00:00'),
            'end_time' => $request->input('end_time') ?: now()->addDay()->format('Y-m-d 11:00:00'),
            'status' => 'Scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('modules.live-classes')->with('success', 'Live Virtual Class scheduled successfully!');
    }

    // =========================================================================
    // 2. APPS CENTER
    // =========================================================================
    public function appsCenter()
    {
        $school = School::first();
        return view('modules.apps_center', compact('school'));
    }

    // =========================================================================
    // 3. STUDY CENTER
    // =========================================================================
    public function studyCenter(Request $request, $activeTab = null)
    {
        $school = School::first();
        $tab = $activeTab ?? $request->query('tab', 'dashboard');
        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();
        $staffMembers = Staff::all();

        // Selected filters
        $defaultClass = $classes->where('name', 'Class 1')->first() ?? $classes->first();
        $selectedClassId = $request->query('school_class_id') ?? $request->query('class_id') ?? ($defaultClass->id ?? 1);
        $selectedSubjectId = $request->query('subject_id') ?? ($subjects->first()->id ?? 1);
        $selectedSectionId = $request->query('section_id');
        $selectedDate = $request->query('date');
        $selectedStatus = $request->query('status');

        // Common KPI counters
        $totalClassworks = StudyClasswork::count();
        $totalSyllabuses = StudySyllabus::count();
        $completedSyllabuses = StudySyllabus::where('status', 'completed')->count();
        $syllabusPercent = $totalSyllabuses > 0 ? round(($completedSyllabuses / $totalSyllabuses) * 100) : 0;
        $totalMaterials = StudyMaterial::count();
        $activeHomeworks = StudyHomework::where('status', 'Active')->count();
        $totalHomeworks = StudyHomework::count();

        // Materials breakdown
        $materialCounts = [
            'all' => $totalMaterials,
            'file' => StudyMaterial::where('type', 'file')->count(),
            'video' => StudyMaterial::where('type', 'video')->count(),
            'link' => StudyMaterial::where('type', 'link')->count(),
        ];

        // 1. Classwork logbook records
        $classworkQuery = StudyClasswork::with(['schoolClass', 'section', 'subject', 'staff'])->latest('date');
        if ($request->filled('school_class_id') || $request->filled('class_id')) {
            $classworkQuery->where('school_class_id', $request->query('school_class_id') ?? $request->query('class_id'));
        }
        if ($request->filled('section_id')) {
            $classworkQuery->where('section_id', $request->query('section_id'));
        }
        if ($request->filled('subject_id')) {
            $classworkQuery->where('subject_id', $request->query('subject_id'));
        }
        if ($request->filled('date')) {
            $classworkQuery->where('date', $request->query('date'));
        }
        $classworks = $classworkQuery->get();

        // 2. Syllabus structure for selected class & subject
        $syllabusUnits = StudySyllabus::with(['children.materials', 'materials'])
            ->where('school_class_id', $selectedClassId)
            ->where('subject_id', $selectedSubjectId)
            ->whereNull('parent_id')
            ->orderBy('order_no')
            ->get();

        $allSyllabusForSelect = StudySyllabus::where('school_class_id', $selectedClassId)
            ->where('subject_id', $selectedSubjectId)
            ->orderBy('order_no')
            ->get();

        $classSyllabusTotal = StudySyllabus::where('school_class_id', $selectedClassId)
            ->where('subject_id', $selectedSubjectId)
            ->count();
        $classSyllabusCompleted = StudySyllabus::where('school_class_id', $selectedClassId)
            ->where('subject_id', $selectedSubjectId)
            ->where('status', 'completed')
            ->count();
        $classSyllabusPercent = $classSyllabusTotal > 0 ? round(($classSyllabusCompleted / $classSyllabusTotal) * 100) : 0;

        // 3. Study Materials
        $materialQuery = StudyMaterial::with(['schoolClass', 'subject', 'syllabus', 'staff'])->latest();
        if ($request->filled('school_class_id') || $request->filled('class_id')) {
            $materialQuery->where('school_class_id', $request->query('school_class_id') ?? $request->query('class_id'));
        }
        if ($request->filled('subject_id')) {
            $materialQuery->where('subject_id', $request->query('subject_id'));
        }
        if ($request->filled('type') && in_array($request->query('type'), ['file', 'video', 'link'])) {
            $materialQuery->where('type', $request->query('type'));
        }
        $materials = $materialQuery->get();

        // 4. Homework & Assignments
        $homeworkQuery = StudyHomework::with(['schoolClass', 'section', 'subject', 'staff', 'material', 'submissions'])->latest();
        if ($request->filled('school_class_id') || $request->filled('class_id')) {
            $homeworkQuery->where('school_class_id', $request->query('school_class_id') ?? $request->query('class_id'));
        }
        if ($request->filled('status')) {
            $homeworkQuery->where('status', $request->query('status'));
        }
        $homeworks = $homeworkQuery->get();

        // 5. Dashboard recent items & live classes
        $recentHomeworks = StudyHomework::with(['schoolClass', 'subject', 'submissions'])->latest()->take(5)->get();
        $recentClassworks = StudyClasswork::with(['schoolClass', 'section', 'subject', 'staff'])->latest('date')->take(5)->get();
        $liveSessions = DB::table('live_class_sessions')->latest()->take(4)->get();

        return view('modules.study_center', compact(
            'school',
            'tab',
            'classes',
            'subjects',
            'staffMembers',
            'selectedClassId',
            'selectedSubjectId',
            'selectedSectionId',
            'selectedDate',
            'selectedStatus',
            'totalClassworks',
            'totalSyllabuses',
            'completedSyllabuses',
            'syllabusPercent',
            'totalMaterials',
            'activeHomeworks',
            'totalHomeworks',
            'materialCounts',
            'classworks',
            'syllabusUnits',
            'allSyllabusForSelect',
            'classSyllabusTotal',
            'classSyllabusCompleted',
            'classSyllabusPercent',
            'materials',
            'homeworks',
            'recentHomeworks',
            'recentClassworks',
            'liveSessions'
        ));
    }

    // =========================================================================
    // STUDY CENTER ACTIONS: CLASSWORK
    // =========================================================================
    public function createClasswork(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();
        $staffMembers = Staff::all();

        return view('modules.study_center.classwork_create', compact('school', 'classes', 'subjects', 'staffMembers'));
    }

    public function storeClasswork(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required',
            'subject_id' => 'required',
            'date' => 'required|date',
            'topic' => 'required|string|max:255',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/classwork'), $fileName);
            $filePath = 'uploads/classwork/' . $fileName;
        }

        StudyClasswork::create([
            'school_id' => 1,
            'school_class_id' => $request->school_class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'staff_id' => $request->staff_id,
            'date' => $request->date,
            'type' => $request->type ?? 'Classwork',
            'topic' => $request->topic,
            'content' => $request->content,
            'file_path' => $filePath,
        ]);

        return redirect()->route('study-center.classwork')->with('success', 'Classwork log entry recorded successfully.');
    }

    public function deleteClasswork($id)
    {
        $item = StudyClasswork::findOrFail($id);
        $item->delete();
        return redirect()->back()->with('success', 'Classwork entry deleted.');
    }

    // =========================================================================
    // STUDY CENTER ACTIONS: SYLLABUS
    // =========================================================================
    public function storeSyllabus(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|string|max:255',
        ]);

        $orderNo = StudySyllabus::where('school_class_id', $request->school_class_id)
            ->where('subject_id', $request->subject_id)
            ->where('parent_id', $request->parent_id)
            ->count() + 1;

        StudySyllabus::create([
            'school_id' => 1,
            'school_class_id' => $request->school_class_id,
            'subject_id' => $request->subject_id,
            'parent_id' => $request->parent_id ?: null,
            'title' => $request->title,
            'description' => $request->description,
            'order_no' => $orderNo,
            'status' => 'pending',
        ]);

        return redirect()->route('study-center.syllabus', [
            'school_class_id' => $request->school_class_id,
            'subject_id' => $request->subject_id
        ])->with('success', 'Syllabus unit added successfully.');
    }

    public function toggleSyllabusStatus(Request $request, $id)
    {
        $unit = StudySyllabus::findOrFail($id);
        $unit->status = $unit->status === 'completed' ? 'pending' : 'completed';
        $unit->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => $unit->status]);
        }

        return redirect()->back()->with('success', 'Syllabus item updated.');
    }

    public function deleteSyllabus($id)
    {
        $unit = StudySyllabus::findOrFail($id);
        StudySyllabus::where('parent_id', $unit->id)->delete();
        $unit->delete();
        return redirect()->back()->with('success', 'Syllabus unit and its sub-topics deleted.');
    }

    // =========================================================================
    // STUDY CENTER ACTIONS: MATERIALS
    // =========================================================================
    public function storeMaterial(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,video,link',
        ]);

        $filePath = null;
        $fileName = null;
        $fileSize = null;
        $contentLink = $request->content_link;

        if ($request->type === 'file' && $request->hasFile('content_file')) {
            $file = $request->file('content_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = round($file->getSize() / 1024, 1) . ' KB';
            $savedName = time() . '_' . $fileName;
            $file->move(public_path('uploads/materials'), $savedName);
            $filePath = 'uploads/materials/' . $savedName;
        }

        StudyMaterial::create([
            'school_id' => 1,
            'school_class_id' => $request->school_class_id,
            'subject_id' => $request->subject_id,
            'syllabus_id' => $request->syllabus_id ?: null,
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'content_link' => $contentLink,
            'staff_id' => 1,
        ]);

        return redirect()->route('study-center.materials', [
            'school_class_id' => $request->school_class_id,
            'subject_id' => $request->subject_id
        ])->with('success', 'Study material uploaded successfully.');
    }

    public function deleteMaterial($id)
    {
        $mat = StudyMaterial::findOrFail($id);
        $mat->delete();
        return redirect()->back()->with('success', 'Material removed.');
    }

    // =========================================================================
    // STUDY CENTER ACTIONS: HOMEWORK
    // =========================================================================
    public function createHomework(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::with('sections')->get();
        $subjects = Subject::all();
        $staffMembers = Staff::all();
        $materials = StudyMaterial::all();

        return view('modules.study_center.homework_create', compact('school', 'classes', 'subjects', 'staffMembers', 'materials'));
    }

    public function storeHomework(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'school_class_id' => 'required',
            'subject_id' => 'required',
            'due_date' => 'required|date',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/homework'), $fileName);
            $filePath = 'uploads/homework/' . $fileName;
        }

        StudyHomework::create([
            'school_id' => 1,
            'school_class_id' => $request->school_class_id,
            'section_id' => $request->section_id,
            'subject_id' => $request->subject_id,
            'staff_id' => $request->staff_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'study_material_id' => $request->study_material_id ?: null,
            'file_path' => $filePath,
            'status' => 'Active',
        ]);

        return redirect()->route('study-center.homework')->with('success', 'Homework assignment created and published to students.');
    }

    public function deleteHomework($id)
    {
        $hw = StudyHomework::findOrFail($id);
        StudyHomeworkSubmission::where('homework_id', $hw->id)->delete();
        $hw->delete();
        return redirect()->back()->with('success', 'Homework assignment removed.');
    }

    public function homeworkSubmissions($id)
    {
        $school = School::first();
        $homework = StudyHomework::with(['schoolClass', 'section', 'subject', 'staff', 'material', 'submissions.student'])->findOrFail($id);
        $submissions = $homework->submissions;
        
        // Get students in this class
        $students = DB::table('students')
            ->join('student_enrollments', 'students.id', '=', 'student_enrollments.student_id')
            ->where('student_enrollments.class_id', $homework->school_class_id)
            ->select('students.*')
            ->get();

        if ($students->isEmpty()) {
            $students = DB::table('students')->take(10)->get();
        }

        return view('modules.study_center.homework_submissions', compact('school', 'homework', 'submissions', 'students'));
    }

    public function evaluateHomeworkSubmission(Request $request, $id)
    {
        $request->validate([
            'marks' => 'required|numeric',
        ]);

        $submission = StudyHomeworkSubmission::findOrFail($id);
        $submission->marks = $request->marks;
        $submission->remarks = $request->remarks;
        $submission->status = 'Evaluated';
        $submission->save();

        return redirect()->back()->with('success', 'Homework evaluated and marks awarded to student.');
    }

    public function studyGuide(Request $request)
    {
        return $this->studyCenter($request, 'guide');
    }

    // =========================================================================
    // 4. CERTIFICATES
    // =========================================================================
    public function certificates(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'templates');
        $templates = DB::table('certificate_templates')->get();
        $students = Student::with('schoolClass')->take(10)->get();

        return view('modules.certificates', compact('school', 'tab', 'templates', 'students'));
    }

    // =========================================================================
    // 5. ID CARDS
    // =========================================================================
    public function idCards(Request $request, $activeTab = null)
    {
        $school = School::first();
        $tab = $activeTab ?? $request->query('tab', 'students');
        $classes = SchoolClass::with('sections')->get();
        $departments = DB::table('staff')->whereNotNull('department')->distinct()->pluck('department');
        $designations = DB::table('staff')->whereNotNull('designation')->distinct()->pluck('designation');

        // Templates
        $studentTemplates = DB::table('id_card_templates')->where('card_type', 'Student')->get();
        $staffTemplates = DB::table('id_card_templates')->where('card_type', 'Staff')->get();
        $templates = DB::table('id_card_templates')->get();

        // Selected filter values
        $classId = $request->query('class_id');
        $sectionId = $request->query('section_id');
        $roleId = $request->query('role_id');
        $departmentId = $request->query('department');
        $designationId = $request->query('designation');
        $search = $request->query('search');
        $templateId = $request->query('template_id');

        // Dynamic sections for chosen class
        $selectedClass = $classId ? $classes->firstWhere('id', $classId) : null;
        $activeSections = $selectedClass ? $selectedClass->sections : collect();

        // Query students
        $studentsQuery = Student::query()
            ->leftJoin('student_enrollments', 'students.id', '=', 'student_enrollments.student_id')
            ->leftJoin('classes', 'student_enrollments.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'student_enrollments.section_id', '=', 'sections.id')
            ->select('students.*', 'classes.name as class_name', 'sections.name as section_name')
            ->groupBy('students.id');

        if ($classId) {
            $studentsQuery->where('student_enrollments.class_id', $classId);
        }
        if ($sectionId) {
            $studentsQuery->where('student_enrollments.section_id', $sectionId);
        }
        if ($search) {
            $studentsQuery->where(function($q) use ($search) {
                $q->where('students.first_name', 'like', "%{$search}%")
                  ->orWhere('students.last_name', 'like', "%{$search}%")
                  ->orWhere('students.admission_no', 'like', "%{$search}%")
                  ->orWhere('students.roll_no', 'like', "%{$search}%");
            });
        }
        $students = $studentsQuery->take(50)->get();
        if ($students->isEmpty() && !$classId && !$search) {
            $students = Student::take(16)->get();
        }

        // Query staff
        $staffQuery = Staff::query();

        if ($departmentId) {
            $staffQuery->where('department', $departmentId);
        }
        if ($designationId) {
            $staffQuery->where('designation', $designationId);
        }
        if ($search) {
            $staffQuery->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        $staff = $staffQuery->take(50)->get();

        // Selected Template
        $selectedTemplate = null;
        if ($templateId) {
            $selectedTemplate = DB::table('id_card_templates')->where('id', $templateId)->first();
        }
        if (!$selectedTemplate) {
            $selectedTemplate = ($tab === 'staff')
                ? ($staffTemplates->where('is_default', 1)->first() ?? $staffTemplates->first())
                : ($studentTemplates->where('is_default', 1)->first() ?? $studentTemplates->first());
        }

        return view('modules.id_cards', compact(
            'school',
            'tab',
            'templates',
            'studentTemplates',
            'staffTemplates',
            'selectedTemplate',
            'students',
            'staff',
            'classes',
            'activeSections',
            'departments',
            'designations',
            'classId',
            'sectionId',
            'roleId',
            'departmentId',
            'designationId',
            'search',
            'templateId'
        ));
    }

    public function generateStudentIdCards(Request $request)
    {
        $studentIds = (array) $request->input('student_ids', []);
        $templateId = $request->input('template_id', 1);
        $cardSize = $request->input('card_size', 'cr80_portrait');
        $paper = $request->input('paper', 'a4');
        $paperOrientation = $request->input('paper_orientation', 'portrait');
        $marginMm = (float) $request->input('margin_mm', 10);
        $gapMm = (float) $request->input('gap_mm', 3);
        $cutMarks = $request->has('cut_marks');
        $alignCenter = $request->has('align_center');
        $cutStack = $request->has('cut_stack');
        $outputType = $request->input('output_type', 'preview');
        $autoImages = $request->input('auto_images', 0);

        if (empty($studentIds)) {
            return redirect()->back()->with('error', 'Please tick at least one student before printing.');
        }

        $template = DB::table('id_card_templates')->where('id', $templateId)->first()
            ?? DB::table('id_card_templates')->first();

        $students = Student::whereIn('students.id', $studentIds)
            ->leftJoin('student_enrollments', 'students.id', '=', 'student_enrollments.student_id')
            ->leftJoin('classes', 'student_enrollments.class_id', '=', 'classes.id')
            ->leftJoin('sections', 'student_enrollments.section_id', '=', 'sections.id')
            ->select('students.*', 'classes.name as class_name', 'sections.name as section_name')
            ->groupBy('students.id')
            ->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'No student records found to generate ID cards.');
        }

        // Custom dimension calculation
        $customWidth = (float) $request->input('custom_card_width_mm', 54);
        $customHeight = (float) $request->input('custom_card_height_mm', 85.6);
        $dimensions = $this->calculateCardDimensions($cardSize, $template, $customWidth, $customHeight);

        // ZIP download output
        if ($outputType === 'images' || $autoImages == 1) {
            return $this->downloadCardsZip($students, 'student', $template);
        }

        $sheetSpecs = $this->calculateSheetSpecs($paper, $paperOrientation, $dimensions['width'], $dimensions['height'], $marginMm, $gapMm, $alignCenter);

        return view('modules.id_cards_print', [
            'type' => 'student',
            'school' => School::first(),
            'records' => $students,
            'students' => $students,
            'template' => $template,
            'cardSize' => $cardSize,
            'cardWidthMm' => $dimensions['width'],
            'cardHeightMm' => $dimensions['height'],
            'paper' => $paper,
            'paperOrientation' => $paperOrientation,
            'marginMm' => $marginMm,
            'gapMm' => $gapMm,
            'cutMarks' => $cutMarks,
            'alignCenter' => $alignCenter,
            'cutStack' => $cutStack,
            'outputType' => $outputType,
            'sheetSpecs' => $sheetSpecs,
            'pages' => $students->chunk($sheetSpecs['perSheet'])
        ]);
    }

    public function generateStaffIdCards(Request $request)
    {
        $staffIds = (array) $request->input('staff_ids', []);
        $templateId = $request->input('template_id', 6);
        $cardSize = $request->input('card_size', 'cr80_portrait');
        $paper = $request->input('paper', 'a4');
        $paperOrientation = $request->input('paper_orientation', 'portrait');
        $marginMm = (float) $request->input('margin_mm', 10);
        $gapMm = (float) $request->input('gap_mm', 3);
        $cutMarks = $request->has('cut_marks');
        $alignCenter = $request->has('align_center');
        $cutStack = $request->has('cut_stack');
        $outputType = $request->input('output_type', 'preview');
        $autoImages = $request->input('auto_images', 0);

        if (empty($staffIds)) {
            return redirect()->back()->with('error', 'Please tick at least one staff member before printing.');
        }

        $template = DB::table('id_card_templates')->where('id', $templateId)->first()
            ?? DB::table('id_card_templates')->where('card_type', 'Staff')->first()
            ?? DB::table('id_card_templates')->first();

        $staff = Staff::whereIn('id', $staffIds)->get();

        if ($staff->isEmpty()) {
            return redirect()->back()->with('error', 'No staff records found to generate ID cards.');
        }

        // Custom dimension calculation
        $customWidth = (float) $request->input('custom_card_width_mm', 54);
        $customHeight = (float) $request->input('custom_card_height_mm', 85.6);
        $dimensions = $this->calculateCardDimensions($cardSize, $template, $customWidth, $customHeight);

        // ZIP download output
        if ($outputType === 'images' || $autoImages == 1) {
            return $this->downloadCardsZip($staff, 'staff', $template);
        }

        $sheetSpecs = $this->calculateSheetSpecs($paper, $paperOrientation, $dimensions['width'], $dimensions['height'], $marginMm, $gapMm, $alignCenter);

        return view('modules.id_cards_print', [
            'type' => 'staff',
            'school' => School::first(),
            'records' => $staff,
            'staff' => $staff,
            'template' => $template,
            'cardSize' => $cardSize,
            'cardWidthMm' => $dimensions['width'],
            'cardHeightMm' => $dimensions['height'],
            'paper' => $paper,
            'paperOrientation' => $paperOrientation,
            'marginMm' => $marginMm,
            'gapMm' => $gapMm,
            'cutMarks' => $cutMarks,
            'alignCenter' => $alignCenter,
            'cutStack' => $cutStack,
            'outputType' => $outputType,
            'sheetSpecs' => $sheetSpecs,
            'pages' => $staff->chunk($sheetSpecs['perSheet'])
        ]);
    }

    private function calculateCardDimensions($cardSize, $template, $customWidth, $customHeight)
    {
        if ($cardSize === 'custom') {
            return [
                'width' => $customWidth > 0 ? $customWidth : 54.0,
                'height' => $customHeight > 0 ? $customHeight : 85.6
            ];
        } elseif ($cardSize === 'cr80_landscape') {
            return ['width' => 85.6, 'height' => 54.0];
        } elseif ($cardSize === 'a7_portrait') {
            return ['width' => 74.0, 'height' => 105.0];
        } elseif ($cardSize === 'a6_portrait') {
            return ['width' => 105.0, 'height' => 148.0];
        } elseif ($cardSize === 'a6_landscape') {
            return ['width' => 148.0, 'height' => 105.0];
        } elseif ($cardSize === 'template' && $template) {
            return [
                'width' => (float)($template->width_mm ?? 54.0),
                'height' => (float)($template->height_mm ?? 85.6)
            ];
        }
        return ['width' => 54.0, 'height' => 85.6];
    }

    private function calculateSheetSpecs($paper, $paperOrientation, $cardW, $cardH, $marginMm, $gapMm, $alignCenter)
    {
        $specs = [
            'a4' => [210.0, 297.0],
            'a3' => [297.0, 420.0],
            'letter' => [215.9, 279.4],
        ];
        $base = $specs[strtolower($paper)] ?? [210.0, 297.0];
        if (strtolower($paperOrientation) === 'landscape') {
            $sheetW = max($base[0], $base[1]);
            $sheetH = min($base[0], $base[1]);
        } else {
            $sheetW = min($base[0], $base[1]);
            $sheetH = max($base[0], $base[1]);
        }

        $cardW = (float) $cardW;
        $cardH = (float) $cardH;
        $margin = (float) $marginMm;
        $gap = (float) $gapMm;

        $cols = max(1, (int) floor(($sheetW - 2 * $margin + $gap) / ($cardW + $gap)));
        $rows = max(1, (int) floor(($sheetH - 2 * $margin + $gap) / ($cardH + $gap)));
        $perSheet = $cols * $rows;

        $gridW = $cols * $cardW + ($cols - 1) * $gap;
        $gridH = $rows * $cardH + ($rows - 1) * $gap;

        $startX = $alignCenter ? ($sheetW - $gridW) / 2 : $margin;
        $startY = $alignCenter ? ($sheetH - $gridH) / 2 : $margin;

        $colCuts = [];
        for ($c = 0; $c < $cols; $c++) {
            $left = $startX + $c * ($cardW + $gap);
            $right = $left + $cardW;
            $colCuts[] = ['left' => round($left, 1), 'right' => round($right, 1)];
        }

        $rowCuts = [];
        for ($r = 0; $r < $rows; $r++) {
            $top = $startY + $r * ($cardH + $gap);
            $bottom = $top + $cardH;
            $rowCuts[] = ['top' => round($top, 1), 'bottom' => round($bottom, 1)];
        }

        return [
            'sheetW' => $sheetW,
            'sheetH' => $sheetH,
            'cols' => $cols,
            'rows' => $rows,
            'perSheet' => $perSheet,
            'startX' => round($startX, 1),
            'startY' => round($startY, 1),
            'colCuts' => $colCuts,
            'rowCuts' => $rowCuts
        ];
    }

    private function downloadCardsZip($records, $type, $template)
    {
        $school = School::first();
        $schoolName = $school->name ?? 'DEMO INTERNATIONAL SCHOOL';
        $zipFile = tempnam(sys_get_temp_dir(), 'idcards_') . '.zip';
        $zip = new \ZipArchive();

        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'Unable to create ZIP package on the server.');
        }

        foreach ($records as $index => $rec) {
            $isStaff = ($type === 'staff');
            $personData = [
                'name' => $rec->first_name . ' ' . $rec->last_name,
                'role_info' => $isStaff
                    ? ($rec->designation ?? 'Faculty') . ' • ' . ($rec->department ?? 'Academics')
                    : ($rec->class_name ?? 'Class') . ' - ' . ($rec->section_name ?? 'A'),
                'adm_no' => $isStaff ? ($rec->employee_id ?? 'STF-' . $rec->id) : ($rec->admission_no ?? 'ADM-' . $rec->id),
                'roll_dept' => $isStaff ? ($rec->department ?? 'General') : ($rec->roll_no ?? ($index + 1)),
                'blood_group' => $rec->blood_group ?? 'O+',
                'phone' => $rec->phone ?? '9876543210'
            ];

            $pngData = $this->createCardPng($personData, $type, $template, $schoolName);
            $filename = 'ID_Card_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $personData['adm_no']) . '.png';
            $zip->addFromString($filename, $pngData);
        }

        $zip->close();
        $downloadName = ($type === 'staff' ? 'Staff_ID_Cards.zip' : 'Student_ID_Cards.zip');
        return response()->download($zipFile, $downloadName)->deleteFileAfterSend(true);
    }

    private function createCardPng($person, $type, $template, $schoolName)
    {
        $width = 638;
        $height = 1011;
        $layout = $template->layout ?? 'Vertical';
        if ($layout === 'Horizontal') {
            $width = 1011;
            $height = 638;
        }

        $im = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($im, 255, 255, 255);
        $bgLight = imagecolorallocate($im, 248, 250, 252);
        $slate900 = imagecolorallocate($im, 15, 23, 42);
        $slate700 = imagecolorallocate($im, 51, 65, 85);
        $slate500 = imagecolorallocate($im, 100, 116, 139);
        $slate200 = imagecolorallocate($im, 226, 232, 240);
        $blue = imagecolorallocate($im, 37, 99, 235);
        $emerald = imagecolorallocate($im, 5, 150, 105);

        imagefill($im, 0, 0, $white);

        // Header accent
        $headerColor = ($type === 'staff') ? $slate900 : $blue;
        imagefilledrectangle($im, 0, 0, $width, 180, $headerColor);

        // Header text
        $schoolTitle = strtoupper($schoolName);
        imagestring($im, 5, max(20, (int)(($width - strlen($schoolTitle) * 9) / 2)), 60, $schoolTitle, $white);
        $subTitle = ($type === 'staff') ? 'FACULTY & STAFF IDENTITY CARD' : 'STUDENT IDENTITY CARD';
        imagestring($im, 4, max(20, (int)(($width - strlen($subTitle) * 8) / 2)), 110, $subTitle, $white);

        // Photo circle
        $cx = (int)($width / 2);
        $cy = 340;
        imagefilledellipse($im, $cx, $cy, 220, 220, $slate200);
        $initial = strtoupper(substr($person['name'] ?? 'S', 0, 1));
        imagestring($im, 5, $cx - 10, $cy - 15, $initial, $slate700);

        // Name
        $name = strtoupper($person['name'] ?? 'PERSON NAME');
        imagestring($im, 5, max(20, (int)(($width - strlen($name) * 9) / 2)), 480, $name, $slate900);

        // Role Info
        $roleText = $person['role_info'] ?? '';
        $roleColor = ($type === 'staff') ? $emerald : $blue;
        imagestring($im, 4, max(20, (int)(($width - strlen($roleText) * 8) / 2)), 520, $roleText, $roleColor);

        // Details block
        imagefilledrectangle($im, 40, 570, $width - 40, 840, $bgLight);
        imagerectangle($im, 40, 570, $width - 40, 840, $slate200);

        $y = 600;
        $fields = [
            ($type === 'staff' ? 'Staff ID: ' : 'Adm No: ') => $person['adm_no'] ?? 'N/A',
            ($type === 'staff' ? 'Dept: ' : 'Roll No: ') => $person['roll_dept'] ?? 'N/A',
            'Blood Group: ' => $person['blood_group'] ?? 'O+',
            'Phone: ' => $person['phone'] ?? '9876543210',
        ];

        foreach ($fields as $label => $val) {
            imagestring($im, 4, 70, $y, $label, $slate500);
            imagestring($im, 4, 300, $y, (string)$val, $slate900);
            $y += 50;
        }

        // Footer Barcode & Signature
        imagefilledrectangle($im, 0, $height - 100, $width, $height, $slate200);
        imagestring($im, 4, 40, $height - 60, "|||| | ||||| || ||||||", $slate700);
        imagestring($im, 4, $width - 240, $height - 60, "Authorized Signatory", $slate700);

        ob_start();
        imagepng($im);
        $data = ob_get_clean();
        imagedestroy($im);

        return $data;
    }

    public function storeIdCardTemplate(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'card_type' => 'required|in:Student,Staff',
            'layout' => 'required|in:Vertical,Horizontal',
            'bg_color' => 'required|string',
        ]);

        $defaultWidth = $request->layout === 'Horizontal' ? 85.60 : 54.00;
        $defaultHeight = $request->layout === 'Horizontal' ? 54.00 : 85.60;
        $width = (float) $request->input('width_mm', $defaultWidth);
        $height = (float) $request->input('height_mm', $defaultHeight);
        if ($width <= 0) $width = $defaultWidth;
        if ($height <= 0) $height = $defaultHeight;

        $newId = DB::table('id_card_templates')->insertGetId([
            'title' => $request->title,
            'card_type' => $request->card_type,
            'layout' => $request->layout,
            'width_mm' => $width,
            'height_mm' => $height,
            'bg_color' => $request->bg_color,
            'fields_json' => json_encode(['Photo', 'Name', 'ID No', 'Role/Class', 'Blood Group', 'Barcode']),
            'is_default' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $redirectUrl = ($request->card_type === 'Staff')
            ? route('id-cards.staff', ['template_id' => $newId])
            : route('id-cards.students', ['template_id' => $newId]);

        return redirect($redirectUrl)->with('success', 'New ID Card design template "' . $request->title . '" created successfully.');
    }

    public function setDefaultTemplate($id)
    {
        $tpl = DB::table('id_card_templates')->where('id', $id)->first();
        if ($tpl) {
            DB::table('id_card_templates')->where('card_type', $tpl->card_type)->update(['is_default' => 0]);
            DB::table('id_card_templates')->where('id', $id)->update(['is_default' => 1]);
        }

        return redirect()->back()->with('success', 'Default card template updated.');
    }

    public function deleteIdCardTemplate($id)
    {
        DB::table('id_card_templates')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Card template deleted.');
    }

    // =========================================================================
    // 6. COMMUNICATE
    // =========================================================================
    public function communicate(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'notices');
        $notices = Notice::latest()->get();
        $events = Event::latest()->get();

        return view('modules.communicate', compact('school', 'tab', 'notices', 'events'));
    }

    // =========================================================================
    // 7. LIBRARY
    // =========================================================================
    public function library(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $books = DB::table('library_books')
            ->leftJoin('book_categories', 'library_books.category_id', '=', 'book_categories.id')
            ->select('library_books.*', 'book_categories.name as category_name')
            ->get();
        $categories = DB::table('book_categories')->get();
        $issues = DB::table('book_issues')
            ->join('library_books', 'book_issues.book_id', '=', 'library_books.id')
            ->select('book_issues.*', 'library_books.title as book_title')
            ->latest('book_issues.created_at')
            ->get();

        return view('modules.library', compact('school', 'tab', 'books', 'categories', 'issues'));
    }

    // =========================================================================
    // 8. INVENTORY & STOCK
    // =========================================================================
    public function inventory(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $items = DB::table('inventory_items')
            ->leftJoin('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
            ->select('inventory_items.*', 'inventory_categories.name as category_name')
            ->get();
        $categories = DB::table('inventory_categories')->get();
        $suppliers = DB::table('inventory_suppliers')->get();
        $sales = DB::table('inventory_sales')->latest()->get();

        return view('modules.inventory', compact('school', 'tab', 'items', 'categories', 'suppliers', 'sales'));
    }

    // =========================================================================
    // 9. TRANSPORT & FLEET GPS
    // =========================================================================
    public function transport(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $vehicles = DB::table('transport_vehicles')->get();
        $routes = DB::table('transport_routes')
            ->leftJoin('transport_vehicles', 'transport_routes.vehicle_id', '=', 'transport_vehicles.id')
            ->select('transport_routes.*', 'transport_vehicles.vehicle_no')
            ->get();

        return view('modules.transport', compact('school', 'tab', 'vehicles', 'routes'));
    }

    // =========================================================================
    // 10. HOSTEL MANAGEMENT
    // =========================================================================
    public function hostel(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $blocks = DB::table('hostel_blocks')->get();
        $rooms = DB::table('hostel_rooms')
            ->join('hostel_blocks', 'hostel_rooms.block_id', '=', 'hostel_blocks.id')
            ->select('hostel_rooms.*', 'hostel_blocks.name as block_name')
            ->get();
        $allocations = DB::table('hostel_allocations')
            ->join('hostel_rooms', 'hostel_allocations.room_id', '=', 'hostel_rooms.id')
            ->select('hostel_allocations.*', 'hostel_rooms.room_number')
            ->get();

        return view('modules.hostel', compact('school', 'tab', 'blocks', 'rooms', 'allocations'));
    }

    // =========================================================================
    // 11. HELP CENTER & KNOWLEDGE BASE
    // =========================================================================
    public function helpCenter(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'categories');
        $categories = DB::table('kb_categories')->get();
        $articles = DB::table('kb_articles')
            ->join('kb_categories', 'kb_articles.category_id', '=', 'kb_categories.id')
            ->select('kb_articles.*', 'kb_categories.name as category_name')
            ->get();

        return view('modules.help_center', compact('school', 'tab', 'categories', 'articles'));
    }

    // =========================================================================
    // 12. ASSET MANAGEMENT
    // =========================================================================
    public function assets(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $assets = DB::table('asset_items')->get();
        $totalAcquisition = $assets->sum('cost_price');
        $currentBookValue = $assets->sum('current_value');

        return view('modules.assets', compact('school', 'tab', 'assets', 'totalAcquisition', 'currentBookValue'));
    }

    // =========================================================================
    // 13. ENGAGEMENT & CREATIVES
    // =========================================================================
    public function engagement(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'designs');
        $studentsToday = Student::take(3)->get();
        $staffToday = Staff::take(2)->get();

        return view('modules.engagement', compact('school', 'tab', 'studentsToday', 'staffToday'));
    }

    // =========================================================================
    // 14. CCTV LIVE WALL
    // =========================================================================
    public function cctv(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'wall');
        $cameras = DB::table('cctv_cameras')->get();

        return view('modules.cctv', compact('school', 'tab', 'cameras'));
    }

    // =========================================================================
    // 15. ALUMNI
    // =========================================================================
    public function alumni(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $members = DB::table('alumni_members')->latest()->get();
        $events = DB::table('alumni_events')->latest()->get();

        return view('modules.alumni', compact('school', 'tab', 'members', 'events'));
    }

    // =========================================================================
    // 16. LABS
    // =========================================================================
    public function labs(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'labs');
        $labs = DB::table('lab_records')->get();
        $sessions = DB::table('lab_sessions')
            ->join('lab_records', 'lab_sessions.lab_id', '=', 'lab_records.id')
            ->select('lab_sessions.*', 'lab_records.name as lab_name')
            ->latest('session_date')
            ->get();
        $incidents = DB::table('lab_incidents')
            ->join('lab_records', 'lab_incidents.lab_id', '=', 'lab_records.id')
            ->select('lab_incidents.*', 'lab_records.name as lab_name')
            ->latest('incident_date')
            ->get();

        return view('modules.labs', compact('school', 'tab', 'labs', 'sessions', 'incidents'));
    }

    // =========================================================================
    // 17. CANTEEN & DIGITAL CAMPUS WALLET
    // =========================================================================
    public function canteen(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $products = DB::table('canteen_products')->get();
        $wallets = DB::table('canteen_wallets')->get();
        $transactions = DB::table('canteen_transactions')
            ->join('canteen_wallets', 'canteen_transactions.wallet_id', '=', 'canteen_wallets.id')
            ->select('canteen_transactions.*', 'canteen_wallets.user_name')
            ->latest('canteen_transactions.created_at')
            ->take(15)
            ->get();

        return view('modules.canteen', compact('school', 'tab', 'products', 'wallets', 'transactions'));
    }

    // =========================================================================
    // 18. ONLINE COURSES LMS
    // =========================================================================
    public function courses(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $courses = DB::table('online_courses')->get();

        return view('modules.courses', compact('school', 'tab', 'courses'));
    }

    // =========================================================================
    // 19. REPORTS & ANALYTICS HUB
    // =========================================================================
    public function reports()
    {
        $school = School::first();
        return view('modules.reports', compact('school'));
    }
}
