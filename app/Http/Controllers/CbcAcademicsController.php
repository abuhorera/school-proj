<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\CbcCompetency;
use App\Models\CbcStrand;
use App\Models\CbcOutcome;
use App\Models\CbcAssessment;
use App\Models\CbcStudentEvaluation;
use App\Models\CbcPathway;
use App\Models\CbcTrack;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CbcAcademicsController extends Controller
{
    /**
     * 1. CBC Dashboard (Competency-Based Curriculum Command Center)
     */
    public function dashboard(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $totalCompetencies = CbcCompetency::where('school_id', $schoolId)->count();
        $totalStrands = CbcStrand::where('school_id', $schoolId)->count();
        $totalOutcomes = CbcOutcome::where('school_id', $schoolId)->count();
        $totalAssessments = CbcAssessment::where('school_id', $schoolId)->count();
        $totalPathways = CbcPathway::where('school_id', $schoolId)->count();
        $totalTracks = CbcTrack::where('school_id', $schoolId)->count();

        $competencies = CbcCompetency::where('school_id', $schoolId)->take(7)->get();
        $pathways = CbcPathway::where('school_id', $schoolId)->with('tracks')->get();

        return view('cbc.dashboard', compact(
            'school',
            'totalCompetencies',
            'totalStrands',
            'totalOutcomes',
            'totalAssessments',
            'totalPathways',
            'totalTracks',
            'competencies',
            'pathways'
        ));
    }

    /**
     * 2. Strands & Learning Outcomes
     */
    public function strands(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $selectedSubjectId = $request->get('subject_id') ?? ($subjects->first() ? $subjects->first()->id : null);
        $selectedSubject = $selectedSubjectId ? Subject::find($selectedSubjectId) : null;

        $strands = CbcStrand::where('school_id', $schoolId)
            ->where('subject_id', $selectedSubjectId)
            ->with('outcomes')
            ->orderBy('sort_order')
            ->get();

        return view('cbc.strands', compact(
            'school',
            'subjects',
            'selectedSubjectId',
            'selectedSubject',
            'strands'
        ));
    }

    public function storeStrand(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|integer',
            'name' => 'required|string|max:191',
            'code' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $maxOrder = CbcStrand::where('subject_id', $validated['subject_id'])->max('sort_order') ?? 0;

        CbcStrand::create([
            'school_id' => 1,
            'subject_id' => $validated['subject_id'],
            'name' => $validated['name'],
            'code' => $validated['code'] ?? strtoupper(substr($validated['name'], 0, 4) . '-STR'),
            'description' => $validated['description'] ?? null,
            'sort_order' => $validated['sort_order'] ?? ($maxOrder + 1),
        ]);

        return redirect()->back()->with('success', "Curriculum strand '{$validated['name']}' created successfully!");
    }

    public function storeOutcome(Request $request)
    {
        $validated = $request->validate([
            'cbc_strand_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:30',
            'description' => 'nullable|string',
        ]);

        CbcOutcome::create([
            'school_id' => 1,
            'cbc_strand_id' => $validated['cbc_strand_id'],
            'name' => $validated['name'],
            'code' => $validated['code'] ?? ('LO-' . rand(100, 999)),
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', "Learning outcome saved successfully!");
    }

    public function destroyStrand($id)
    {
        $strand = CbcStrand::findOrFail($id);
        $name = $strand->name;
        $strand->outcomes()->delete();
        $strand->delete();

        return redirect()->back()->with('success', "Strand '{$name}' deleted.");
    }

    public function destroyOutcome($id)
    {
        $outcome = CbcOutcome::findOrFail($id);
        $outcome->delete();

        return redirect()->back()->with('success', "Learning outcome removed.");
    }

    /**
     * 3. CBC Assessments & Rubric Scoring
     */
    public function assessments(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();

        $classId = $request->get('class_id') ?? ($classes->first() ? $classes->first()->id : null);
        $sectionId = $request->get('section_id') ?? ($sections->first() ? $sections->first()->id : null);
        $subjectId = $request->get('subject_id') ?? ($subjects->first() ? $subjects->first()->id : null);

        $assessments = CbcAssessment::where('school_id', $schoolId)
            ->when($classId, function ($q) use ($classId) { $q->where('class_id', $classId); })
            ->when($sectionId, function ($q) use ($sectionId) { $q->where('section_id', $sectionId); })
            ->when($subjectId, function ($q) use ($subjectId) { $q->where('subject_id', $subjectId); })
            ->with(['schoolClass', 'section', 'subject', 'evaluations'])
            ->latest()
            ->get();

        $selectedAssessmentId = $request->get('assessment_id') ?? ($assessments->first() ? $assessments->first()->id : null);
        $selectedAssessment = $selectedAssessmentId ? CbcAssessment::find($selectedAssessmentId) : null;

        $students = collect();
        $outcomes = collect();
        $existingEvals = collect();

        if ($selectedAssessment) {
            $students = Student::where('school_id', $schoolId)
                ->whereHas('latestEnrollment', function ($q) use ($selectedAssessment) {
                    if ($selectedAssessment->class_id) $q->where('class_id', $selectedAssessment->class_id);
                    if ($selectedAssessment->section_id) $q->where('section_id', $selectedAssessment->section_id);
                })
                ->with('latestEnrollment.section')
                ->orderBy('first_name')
                ->get();

            if ($students->count() == 0) {
                $students = Student::where('school_id', $schoolId)->take(8)->get();
            }

            $outcomes = CbcOutcome::whereHas('strand', function ($q) use ($selectedAssessment) {
                $q->where('subject_id', $selectedAssessment->subject_id);
            })->get();

            if ($outcomes->count() == 0) {
                $outcomes = CbcOutcome::all();
            }

            $existingEvals = CbcStudentEvaluation::where('cbc_assessment_id', $selectedAssessment->id)
                ->get()
                ->groupBy('student_id');
        }

        return view('cbc.assessments', compact(
            'school',
            'classes',
            'sections',
            'subjects',
            'classId',
            'sectionId',
            'subjectId',
            'assessments',
            'selectedAssessmentId',
            'selectedAssessment',
            'students',
            'outcomes',
            'existingEvals'
        ));
    }

    public function storeAssessment(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'subject_id' => 'required|integer',
            'title' => 'required|string|max:191',
            'assessment_date' => 'nullable|date',
            'type' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $assessment = CbcAssessment::create([
            'school_id' => 1,
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'] ?? 1,
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'assessment_date' => $validated['assessment_date'] ?? Carbon::now()->toDateString(),
            'type' => $validated['type'] ?? 'Formative',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('cbc.assessments', ['assessment_id' => $assessment->id])
            ->with('success', "CBC Assessment '{$assessment->title}' created successfully!");
    }

    public function saveEvaluations(Request $request)
    {
        $validated = $request->validate([
            'cbc_assessment_id' => 'required|integer',
            'evaluations' => 'required|array',
            'feedback' => 'nullable|array',
        ]);

        $assessmentId = $validated['cbc_assessment_id'];
        $schoolId = 1;

        foreach ($validated['evaluations'] as $studentId => $outcomeLevels) {
            foreach ($outcomeLevels as $outcomeId => $rubricLevel) {
                CbcStudentEvaluation::updateOrCreate(
                    [
                        'school_id' => $schoolId,
                        'cbc_assessment_id' => $assessmentId,
                        'student_id' => $studentId,
                        'cbc_outcome_id' => $outcomeId,
                    ],
                    [
                        'rubric_level' => $rubricLevel,
                        'teacher_feedback' => $validated['feedback'][$studentId] ?? null,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Student rubric evaluations and feedback recorded successfully!');
    }

    /**
     * 4. Core Competencies Master
     */
    public function competencies(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $competencies = CbcCompetency::where('school_id', $schoolId)->latest()->get();

        return view('cbc.competencies', compact('school', 'competencies'));
    }

    public function storeCompetency(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'indicators' => 'nullable|string',
        ]);

        $indicatorsArray = [];
        if (!empty($validated['indicators'])) {
            $indicatorsArray = array_filter(array_map('trim', explode("\n", $validated['indicators'])));
        }

        CbcCompetency::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'Core Competency',
            'description' => $validated['description'] ?? null,
            'indicators' => $indicatorsArray,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Competency '{$validated['name']}' saved successfully!");
    }

    public function seedDefaults(Request $request)
    {
        $seeder = new \Database\Seeders\CbcAcademicsSeeder();
        $seeder->run();

        return redirect()->back()->with('success', 'Default 7 Core Competencies & NEP 2020 frameworks loaded successfully!');
    }

    public function destroyCompetency($id)
    {
        $comp = CbcCompetency::findOrFail($id);
        $name = $comp->name;
        $comp->delete();

        return redirect()->back()->with('success', "Competency '{$name}' removed.");
    }

    /**
     * 5. Senior Secondary Pathways & Tracks
     */
    public function pathways(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $pathways = CbcPathway::where('school_id', $schoolId)->with('tracks')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();

        return view('cbc.pathways', compact('school', 'pathways', 'subjects'));
    }

    public function storePathway(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        CbcPathway::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'icon' => $validated['icon'] ?? 'fa-solid fa-graduation-cap',
        ]);

        return redirect()->back()->with('success', "Senior Pathway '{$validated['name']}' registered successfully!");
    }

    public function storeTrack(Request $request)
    {
        $validated = $request->validate([
            'cbc_pathway_id' => 'required|integer',
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'compulsory_subjects' => 'nullable|array',
            'elective_subjects' => 'nullable|array',
        ]);

        CbcTrack::create([
            'school_id' => 1,
            'cbc_pathway_id' => $validated['cbc_pathway_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'compulsory_subjects' => $validated['compulsory_subjects'] ?? [],
            'elective_subjects' => $validated['elective_subjects'] ?? [],
        ]);

        return redirect()->back()->with('success', "Specialized track '{$validated['name']}' added to pathway!");
    }

    public function destroyPathway($id)
    {
        $pathway = CbcPathway::findOrFail($id);
        $name = $pathway->name;
        $pathway->tracks()->delete();
        $pathway->delete();

        return redirect()->back()->with('success', "Pathway '{$name}' deleted.");
    }

    /**
     * 6. CBC Reports & Holistic Analytics
     */
    public function reports(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $totalAssessments = CbcAssessment::where('school_id', $schoolId)->count();
        $totalEvaluations = CbcStudentEvaluation::where('school_id', $schoolId)->count();
        $totalOutcomes = CbcOutcome::where('school_id', $schoolId)->count();

        // Calculate mastery rate (EE and ME levels)
        $masteryCount = CbcStudentEvaluation::where('school_id', $schoolId)
            ->whereIn('rubric_level', ['EE', 'ME'])
            ->count();

        $masteryRate = $totalEvaluations > 0 ? round(($masteryCount / $totalEvaluations) * 100, 1) : 0;

        // Class-wise Report Readiness Table
        $classes = SchoolClass::where('school_id', $schoolId)
            ->with(['sections', 'students'])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $students = Student::where('school_id', $schoolId)->orderBy('first_name')->get();

        return view('cbc.reports', compact(
            'school',
            'totalAssessments',
            'totalEvaluations',
            'totalOutcomes',
            'masteryRate',
            'classes',
            'students'
        ));
    }

    /**
     * Printable Student Holistic CBC Profile
     */
    public function showStudentReport($studentId)
    {
        $school = School::first();
        $student = Student::with(['latestEnrollment.schoolClass', 'latestEnrollment.section', 'guardian'])->findOrFail($studentId);

        $evaluations = CbcStudentEvaluation::where('student_id', $student->id)
            ->with(['assessment.subject', 'outcome.strand'])
            ->get();

        $competencies = CbcCompetency::where('school_id', 1)->get();

        return view('cbc.student_report', compact('school', 'student', 'evaluations', 'competencies'));
    }
}

