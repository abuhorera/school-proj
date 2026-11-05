<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OsmSession;
use App\Models\OsmQuestion;
use App\Models\OsmPaper;
use App\Models\OsmPaperScore;
use App\Models\OsmAnnotation;
use App\Models\OsmSessionEvaluator;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamType;

class OsmController extends Controller
{
    /**
     * OSM Dashboard Master
     */
    public function dashboard(Request $request)
    {
        $query = OsmSession::with(['schoolClass', 'section', 'subject'])->latest();

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        if ($request->filled('class_id') && $request->class_id !== 'All') {
            $query->where('school_class_id', $request->class_id);
        }

        $sessions = $query->paginate(15);
        $classes = SchoolClass::orderBy('name')->get();

        $stats = [
            'total_sessions' => OsmSession::count(),
            'active_evaluating' => OsmSession::where('status', 'Evaluating')->count(),
            'pending_papers' => OsmPaper::where('status', 'Pending')->count(),
            'completed_sessions' => OsmSession::where('status', 'Completed')->count(),
        ];

        return view('osm.dashboard', compact('sessions', 'classes', 'stats'));
    }

    /**
     * OSM Sessions Ledger
     */
    public function sessions(Request $request)
    {
        $query = OsmSession::with(['schoolClass', 'section', 'subject'])->latest();

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        if ($request->filled('class_id') && $request->class_id !== 'All') {
            $query->where('school_class_id', $request->class_id);
        }

        $sessions = $query->paginate(15);
        $classes = SchoolClass::orderBy('name')->get();

        return view('osm.sessions', compact('sessions', 'classes'));
    }

    /**
     * Create Evaluation Session Form
     */
    public function createSession()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $exams = Exam::orderBy('name')->get();
        $examTypes = ExamType::all();

        return view('osm.session_create', compact('classes', 'sections', 'subjects', 'exams', 'examTypes'));
    }

    /**
     * Store New Evaluation Session
     */
    public function storeSession(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'school_class_id' => 'nullable|exists:classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'total_marks' => 'required|numeric|min:1',
            'evaluation_deadline' => 'nullable|date',
            'moderation_level' => 'nullable|string',
        ]);

        $session = OsmSession::create([
            'school_id' => 1,
            'title' => $validated['title'],
            'school_class_id' => $validated['school_class_id'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'subject_id' => $validated['subject_id'] ?? null,
            'exam_id' => $request->exam_id ?: null,
            'exam_type_id' => $request->exam_type_id ?: null,
            'total_marks' => $validated['total_marks'],
            'evaluation_deadline' => $validated['evaluation_deadline'] ?? null,
            'moderation_level' => $request->moderation_level ?: 'sample_10',
            'sync_to_report_card' => $request->boolean('sync_to_report_card'),
            'publish_to_parent' => $request->boolean('publish_to_parent'),
            'anonymous_mode' => $request->boolean('anonymous_mode', true),
            'allow_annotations' => $request->boolean('allow_annotations', true),
            'status' => 'Evaluating',
        ]);

        // Create default starter questions if total_marks given
        $defaultQMarks = min(10, $session->total_marks);
        $qCount = max(1, (int)($session->total_marks / $defaultQMarks));
        for ($i = 1; $i <= min($qCount, 5); $i++) {
            OsmQuestion::create([
                'school_id' => 1,
                'osm_session_id' => $session->id,
                'question_number' => $i,
                'question_label' => "Q{$i}",
                'question_text' => "Question {$i} description and instructions.",
                'max_marks' => $defaultQMarks,
                'is_compulsory' => true,
                'order_seq' => $i,
            ]);
        }

        // Auto-create sample student papers from class section if available
        $students = Student::take(3)->get();
        foreach ($students as $idx => $st) {
            OsmPaper::create([
                'school_id' => 1,
                'osm_session_id' => $session->id,
                'student_id' => $st->id,
                'anonymous_code' => 'OSM-' . strtoupper(substr($session->title, 0, 3)) . '-' . (1000 + $idx + 1),
                'original_file_name' => "ans_sheet_{$st->id}.pdf",
                'page_count' => rand(6, 20),
                'status' => 'Pending',
                'total_score' => 0.00,
            ]);
        }
        $session->update(['total_sheets' => count($students)]);

        return redirect()->route('osm.sessions.show', $session->id)->with('success', 'Evaluation session created successfully.');
    }

    /**
     * Show Session Workbench
     */
    public function showSession($id)
    {
        $session = OsmSession::with([
            'schoolClass', 'section', 'subject', 'questions', 'evaluators.staff',
            'papers.student', 'papers.evaluator'
        ])->findOrFail($id);

        $staffList = Staff::orderBy('first_name')->get();

        return view('osm.session_show', compact('session', 'staffList'));
    }

    /**
     * Edit Session Form
     */
    public function editSession($id)
    {
        $session = OsmSession::findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('osm.session_edit', compact('session', 'classes', 'sections', 'subjects'));
    }

    /**
     * Update Session
     */
    public function updateSession(Request $request, $id)
    {
        $session = OsmSession::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'total_marks' => 'required|numeric|min:1',
            'status' => 'nullable|string',
        ]);

        $session->update([
            'title' => $validated['title'],
            'total_marks' => $validated['total_marks'],
            'school_class_id' => $request->school_class_id ?: $session->school_class_id,
            'section_id' => $request->section_id ?: $session->section_id,
            'subject_id' => $request->subject_id ?: $session->subject_id,
            'evaluation_deadline' => $request->evaluation_deadline ?: $session->evaluation_deadline,
            'moderation_level' => $request->moderation_level ?: $session->moderation_level,
            'sync_to_report_card' => $request->boolean('sync_to_report_card'),
            'publish_to_parent' => $request->boolean('publish_to_parent'),
            'anonymous_mode' => $request->boolean('anonymous_mode'),
            'allow_annotations' => $request->boolean('allow_annotations'),
            'status' => $request->status ?: $session->status,
        ]);

        return redirect()->route('osm.sessions.show', $session->id)->with('success', 'Evaluation session updated.');
    }

    /**
     * Delete Session
     */
    public function destroySession($id)
    {
        $session = OsmSession::findOrFail($id);
        $session->delete();

        return redirect()->route('osm.sessions')->with('success', 'Evaluation session removed.');
    }

    /**
     * Save Question Paper Configuration
     */
    public function saveQuestions(Request $request, $id)
    {
        $session = OsmSession::findOrFail($id);
        $questionsData = $request->input('questions', []);

        if (!empty($questionsData)) {
            // Delete existing or update
            foreach ($questionsData as $idx => $qData) {
                if (!empty($qData['id'])) {
                    $q = OsmQuestion::find($qData['id']);
                } else {
                    $q = new OsmQuestion();
                    $q->osm_session_id = $session->id;
                    $q->school_id = 1;
                }
                $q->question_number = $qData['question_number'] ?? ($idx + 1);
                $q->question_label = $qData['question_label'] ?? ('Q' . ($idx + 1));
                $q->max_marks = $qData['max_marks'] ?? 10.00;
                $q->is_compulsory = isset($qData['is_compulsory']) ? (bool)$qData['is_compulsory'] : true;
                $q->question_text = $qData['question_text'] ?? '';
                $q->order_seq = $idx + 1;
                $q->save();
            }
        }

        return redirect()->route('osm.sessions.show', $session->id)->with('success', 'Question paper configuration saved.');
    }

    /**
     * Upload Answer Sheets
     */
    public function uploadSheets(Request $request, $id)
    {
        $session = OsmSession::findOrFail($id);
        
        // In demo / prototype mode, simulate receiving uploaded bundles and map to students
        $students = Student::take(5)->get();
        $addedCount = 0;
        foreach ($students as $idx => $st) {
            $exists = OsmPaper::where('osm_session_id', $session->id)->where('student_id', $st->id)->first();
            if (!$exists) {
                OsmPaper::create([
                    'school_id' => 1,
                    'osm_session_id' => $session->id,
                    'student_id' => $st->id,
                    'anonymous_code' => 'OSM-' . strtoupper(substr($session->title, 0, 3)) . '-' . (2000 + $idx),
                    'original_file_name' => "scanned_script_std_{$st->id}.pdf",
                    'page_count' => rand(8, 24),
                    'status' => 'Pending',
                    'total_score' => 0.00,
                ]);
                $addedCount++;
            }
        }

        $session->update([
            'total_sheets' => OsmPaper::where('osm_session_id', $session->id)->count()
        ]);

        return redirect()->route('osm.sessions.show', $session->id)->with('success', "Answer sheets uploaded and mapped successfully ({$addedCount} new papers added).");
    }

    /**
     * Assign Staff Evaluators
     */
    public function assignEvaluators(Request $request, $id)
    {
        $session = OsmSession::findOrFail($id);
        $staffIds = $request->input('staff_ids', []);

        if (!empty($staffIds)) {
            foreach ($staffIds as $sid) {
                OsmSessionEvaluator::firstOrCreate(
                    ['osm_session_id' => $session->id, 'staff_id' => $sid],
                    ['assigned_count' => 0, 'completed_count' => 0, 'status' => 'Active']
                );
            }

            // Distribute pending papers among assigned staff
            $unassignedPapers = OsmPaper::where('osm_session_id', $session->id)->whereNull('evaluator_staff_id')->get();
            $evaluators = OsmSessionEvaluator::where('osm_session_id', $session->id)->get();
            
            if ($evaluators->count() > 0 && $unassignedPapers->count() > 0) {
                $evalCount = $evaluators->count();
                foreach ($unassignedPapers as $k => $paper) {
                    $ev = $evaluators[$k % $evalCount];
                    $paper->update(['evaluator_staff_id' => $ev->staff_id]);
                    $ev->increment('assigned_count');
                }
            }
        }

        return redirect()->route('osm.sessions.show', $session->id)->with('success', 'Evaluators assigned and papers distributed.');
    }

    /**
     * Evaluator Workbench Hub
     */
    public function evaluateIndex()
    {
        $sessions = OsmSession::with(['schoolClass', 'section', 'subject', 'papers'])
            ->whereIn('status', ['Evaluating', 'Completed'])
            ->latest()
            ->get();

        return view('osm.evaluate', compact('sessions'));
    }

    /**
     * On-Screen Marking Interactive Canvas
     */
    public function evaluateCanvas($paperId)
    {
        $paper = OsmPaper::with(['session.questions', 'student', 'scores', 'annotations'])->findOrFail($paperId);
        $session = $paper->session;

        // Auto-seed question score slots if empty
        if ($session && $session->questions->count() > 0) {
            foreach ($session->questions as $q) {
                OsmPaperScore::firstOrCreate(
                    ['osm_paper_id' => $paper->id, 'osm_question_id' => $q->id],
                    ['marks_awarded' => 0.00, 'comment' => null, 'is_checked' => false]
                );
            }
            $paper->load('scores');
        }

        return view('osm.evaluate_canvas', compact('paper', 'session'));
    }

    /**
     * Save Single Question Score (AJAX / POST)
     */
    public function scoreQuestion(Request $request, $paperId)
    {
        $paper = OsmPaper::findOrFail($paperId);
        $validated = $request->validate([
            'question_id' => 'required|exists:osm_questions,id',
            'marks_awarded' => 'required|numeric|min:0',
            'comment' => 'nullable|string',
        ]);

        $score = OsmPaperScore::updateOrCreate(
            ['osm_paper_id' => $paper->id, 'osm_question_id' => $validated['question_id']],
            [
                'marks_awarded' => $validated['marks_awarded'],
                'comment' => $validated['comment'] ?? null,
                'is_checked' => true,
            ]
        );

        // Recalculate paper total score
        $newTotal = OsmPaperScore::where('osm_paper_id', $paper->id)->sum('marks_awarded');
        $paper->update([
            'total_score' => $newTotal,
            'status' => 'Evaluating',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'total_score' => $newTotal,
                'marks_awarded' => $score->marks_awarded,
            ]);
        }

        return back()->with('success', 'Question evaluated and score updated.');
    }

    /**
     * Save Annotation Strokes / Stamps
     */
    public function saveAnnotations(Request $request, $paperId)
    {
        $paper = OsmPaper::findOrFail($paperId);
        $anno = OsmAnnotation::create([
            'osm_paper_id' => $paper->id,
            'page_number' => $request->input('page_number', 1),
            'tool_type' => $request->input('tool_type', 'tick'),
            'x' => $request->input('x', 0),
            'y' => $request->input('y', 0),
            'data_json' => $request->input('data_json'),
            'created_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true, 'id' => $anno->id]);
    }

    /**
     * Finish Paper Evaluation
     */
    public function finishPaper(Request $request, $paperId)
    {
        $paper = OsmPaper::findOrFail($paperId);
        $elapsed = (int)$request->input('elapsed_seconds', 180);

        // Calculate total score from questions
        $finalScore = OsmPaperScore::where('osm_paper_id', $paper->id)->sum('marks_awarded');

        $paper->update([
            'total_score' => $finalScore,
            'status' => 'Evaluated',
            'elapsed_seconds' => $elapsed,
            'evaluated_at' => now(),
            'evaluator_notes' => $request->input('evaluator_notes'),
        ]);

        // Update session evaluated count
        $session = $paper->session;
        if ($session) {
            $evalCount = OsmPaper::where('osm_session_id', $session->id)->whereIn('status', ['Evaluated', 'Moderated'])->count();
            $session->update(['evaluated_sheets' => $evalCount]);

            // Check if all papers are finished
            if ($evalCount >= $session->total_sheets && $session->total_sheets > 0) {
                $session->update(['status' => 'Completed']);
            }
        }

        return redirect()->route('osm.evaluate')->with('success', "Paper #{$paper->anonymous_code} evaluated successfully (Score: {$finalScore}/{$session->total_marks}).");
    }

    /**
     * Reject / UFM Paper
     */
    public function rejectPaper(Request $request, $paperId)
    {
        $paper = OsmPaper::findOrFail($paperId);
        $reason = $request->input('reject_reason', 'Illegible / poor scan');

        $paper->update([
            'status' => 'Rejected',
            'reject_reason' => $reason,
        ]);

        return redirect()->route('osm.evaluate')->with('warning', "Paper marked as {$reason}.");
    }

    /**
     * OSM Moderation Console
     */
    public function moderation(Request $request)
    {
        $sessions = OsmSession::with(['schoolClass', 'section', 'subject'])->latest()->get();
        $selectedSessionId = $request->input('session_id');

        $selectedSession = null;
        $papers = collect();

        if ($selectedSessionId) {
            $selectedSession = OsmSession::find($selectedSessionId);
            if ($selectedSession) {
                $papers = OsmPaper::with(['student', 'evaluator', 'scores.question'])
                    ->where('osm_session_id', $selectedSession->id)
                    ->get();
            }
        } elseif ($sessions->count() > 0) {
            $selectedSession = $sessions->first();
            $papers = OsmPaper::with(['student', 'evaluator', 'scores.question'])
                ->where('osm_session_id', $selectedSession->id)
                ->get();
        }

        return view('osm.moderation', compact('sessions', 'selectedSession', 'papers'));
    }

    /**
     * Save Moderation Decision
     */
    public function saveModeration(Request $request, $paperId)
    {
        $paper = OsmPaper::findOrFail($paperId);
        $moderatedScore = $request->input('moderated_score');
        $notes = $request->input('moderator_notes');

        $paper->update([
            'moderated_score' => $moderatedScore !== null ? $moderatedScore : $paper->total_score,
            'is_moderated' => true,
            'status' => 'Moderated',
            'moderator_notes' => $notes,
            'moderated_at' => now(),
            'moderator_staff_id' => auth()->id() ?? 1,
        ]);

        return back()->with('success', "Moderation saved for Paper #{$paper->anonymous_code}.");
    }

    /**
     * OSM Reports Hub
     */
    public function reports(Request $request)
    {
        $sessions = OsmSession::with(['schoolClass', 'section', 'subject'])->latest()->get();
        $selectedSessionId = $request->input('session_id');

        $selectedSession = null;
        $papers = collect();

        if ($selectedSessionId) {
            $selectedSession = OsmSession::find($selectedSessionId);
            if ($selectedSession) {
                $papers = OsmPaper::with(['student', 'evaluator'])
                    ->where('osm_session_id', $selectedSession->id)
                    ->get();
            }
        } elseif ($sessions->count() > 0) {
            $selectedSession = $sessions->first();
            $papers = OsmPaper::with(['student', 'evaluator'])
                ->where('osm_session_id', $selectedSession->id)
                ->get();
        }

        return view('osm.reports', compact('sessions', 'selectedSession', 'papers'));
    }

    /**
     * Session Class Analytics Report
     */
    public function reportsAnalytics($sessionId)
    {
        $session = OsmSession::with(['schoolClass', 'section', 'subject', 'questions', 'papers.scores'])->findOrFail($sessionId);

        $papers = $session->papers;
        $scores = $papers->pluck('total_score')->filter();

        $stats = [
            'total_papers' => $papers->count(),
            'evaluated' => $papers->whereIn('status', ['Evaluated', 'Moderated'])->count(),
            'avg_score' => $scores->count() > 0 ? round($scores->average(), 2) : 0,
            'highest_score' => $scores->count() > 0 ? $scores->max() : 0,
            'lowest_score' => $scores->count() > 0 ? $scores->min() : 0,
            'distinction_count' => $papers->where('total_score', '>=', $session->total_marks * 0.75)->count(),
            'first_div_count' => $papers->whereBetween('total_score', [$session->total_marks * 0.60, $session->total_marks * 0.749])->count(),
            'pass_count' => $papers->whereBetween('total_score', [$session->total_marks * 0.40, $session->total_marks * 0.599])->count(),
            'remedial_count' => $papers->where('total_score', '<', $session->total_marks * 0.40)->count(),
        ];

        return view('osm.reports_analytics', compact('session', 'stats'));
    }

    /**
     * Evaluator Performance Report
     */
    public function reportsEvaluator($sessionId)
    {
        $session = OsmSession::with(['evaluators.staff', 'papers'])->findOrFail($sessionId);
        $evaluators = $session->evaluators;

        return view('osm.reports_evaluator', compact('session', 'evaluators'));
    }

    /**
     * OSM Operational Guide
     */
    public function guide()
    {
        return view('osm.guide');
    }
}

