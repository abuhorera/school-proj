<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\OnlineExam;
use App\Models\OnlineQuestion;
use App\Models\OnlineExamQuestion;
use App\Models\OnlineExamAttempt;
use App\Models\QuestionTopic;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OnlineExamController extends Controller
{
    /**
     * 1. Online Exam Dashboard
     */
    public function dashboard(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $totalExams = OnlineExam::where('school_id', $schoolId)->count();
        $activeExams = OnlineExam::where('school_id', $schoolId)->where('status', 'Active')->count();
        $totalQuestions = OnlineQuestion::where('school_id', $schoolId)->count();
        $totalAttempts = OnlineExamAttempt::where('school_id', $schoolId)->count();

        // Calculate average score percentage across attempts
        $avgScoreRate = OnlineExamAttempt::where('school_id', $schoolId)->avg('percentage') ?? 0;
        $avgScoreRate = round($avgScoreRate, 1);

        // Recent exams performance table (matching live demo)
        $recentExams = OnlineExam::where('school_id', $schoolId)
            ->with(['schoolClass', 'subject', 'attempts'])
            ->withCount('attempts')
            ->latest()
            ->take(8)
            ->get();

        return view('online_exams.dashboard', compact(
            'school',
            'totalExams',
            'activeExams',
            'totalQuestions',
            'totalAttempts',
            'avgScoreRate',
            'recentExams'
        ));
    }

    /**
     * 2. Manage Online Exams
     */
    public function manageExams(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();

        $kind = $request->get('type'); // exam, quiz, practice
        $status = $request->get('status');
        $classId = $request->get('class_id');

        $exams = OnlineExam::where('school_id', $schoolId)
            ->when($kind, function ($q) use ($kind) {
                $q->where('kind', ucfirst($kind));
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($classId, function ($q) use ($classId) {
                $q->where('school_class_id', $classId);
            })
            ->with(['schoolClass', 'section', 'subject', 'questions', 'attempts'])
            ->withCount(['questions', 'attempts'])
            ->latest()
            ->get();

        return view('online_exams.manage_exams', compact(
            'school',
            'classes',
            'sections',
            'subjects',
            'exams',
            'kind',
            'status',
            'classId'
        ));
    }

    /**
     * Store New Online Exam
     */
    public function storeExam(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'kind' => 'required|string',
            'school_class_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'subject_id' => 'required|integer',
            'start_time' => 'nullable|date',
            'duration' => 'required|integer|min:1',
            'total_marks' => 'nullable|numeric',
            'passing_percentage' => 'nullable|numeric',
            'negative_marking' => 'nullable|boolean',
            'negative_mark_rate' => 'nullable|numeric',
            'instructions' => 'nullable|string',
        ]);

        $sectionId = $validated['section_id'] ?? (Section::first() ? Section::first()->id : null);

        $exam = OnlineExam::create([
            'school_id' => 1,
            'school_class_id' => $validated['school_class_id'],
            'section_id' => $sectionId,
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'kind' => $validated['kind'],
            'start_time' => !empty($validated['start_time']) ? Carbon::parse($validated['start_time']) : Carbon::now(),
            'end_time' => !empty($validated['start_time']) ? Carbon::parse($validated['start_time'])->addMinutes((int)$validated['duration']) : Carbon::now()->addDays(7),
            'duration' => $validated['duration'],
            'total_marks' => $validated['total_marks'] ?? 100.00,
            'passing_percentage' => $validated['passing_percentage'] ?? 40.00,
            'negative_marking' => $request->has('negative_marking'),
            'negative_mark_rate' => $validated['negative_mark_rate'] ?? 0.25,
            'instructions' => $validated['instructions'] ?? 'Read questions carefully and manage your time effectively.',
            'status' => 'Active',
        ]);

        return redirect()->route('online-exams.builder', $exam->id)
            ->with('success', "Online exam '{$exam->title}' created! Now add questions using the Paper Builder.");
    }

    /**
     * Delete Exam
     */
    public function destroyExam($id)
    {
        $exam = OnlineExam::findOrFail($id);
        $title = $exam->title;
        $exam->questions()->detach();
        $exam->attempts()->delete();
        $exam->delete();

        return redirect()->back()->with('success', "Online exam '{$title}' removed.");
    }

    /**
     * 3. Online Exam Builder (Questions Setup)
     */
    public function builder($id)
    {
        $exam = OnlineExam::with(['schoolClass', 'section', 'subject', 'questions.topic'])->findOrFail($id);
        
        // Available questions from bank not yet attached
        $attachedIds = $exam->questions->pluck('id')->toArray();
        $availableQuestions = OnlineQuestion::where('school_id', 1)
            ->whereNotIn('id', $attachedIds)
            ->with(['schoolClass', 'subject', 'topic'])
            ->orderBy('id', 'desc')
            ->get();

        return view('online_exams.builder', compact('exam', 'availableQuestions'));
    }

    /**
     * Attach Questions to Exam
     */
    public function addQuestionToExam(Request $request, $id)
    {
        $exam = OnlineExam::findOrFail($id);
        $validated = $request->validate([
            'question_ids' => 'required|array',
        ]);

        $maxSort = OnlineExamQuestion::where('online_exam_id', $exam->id)->max('sort_order') ?? 0;

        foreach ($validated['question_ids'] as $qId) {
            $question = OnlineQuestion::find($qId);
            if ($question) {
                $maxSort++;
                OnlineExamQuestion::firstOrCreate([
                    'online_exam_id' => $exam->id,
                    'online_question_id' => $qId,
                ], [
                    'marks' => $question->marks,
                    'sort_order' => $maxSort,
                ]);
            }
        }

        // Recalculate total marks
        $newTotal = $exam->fresh()->questions->sum('pivot.marks');
        if ($newTotal > 0) {
            $exam->update(['total_marks' => $newTotal]);
        }

        return redirect()->back()->with('success', count($validated['question_ids']) . " questions added to '{$exam->title}'.");
    }

    /**
     * Detach Question from Exam
     */
    public function removeQuestionFromExam($examId, $questionId)
    {
        $exam = OnlineExam::findOrFail($examId);
        $exam->questions()->detach($questionId);

        $newTotal = $exam->fresh()->questions->sum('pivot.marks');
        $exam->update(['total_marks' => max(1, $newTotal)]);

        return redirect()->back()->with('success', "Question detached from examination paper.");
    }

    /**
     * 4. Question Bank Directory
     */
    public function questionBank(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $topics = QuestionTopic::where('school_id', $schoolId)->orderBy('name')->get();

        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');
        $type = $request->get('type');
        $difficulty = $request->get('difficulty');
        $search = $request->get('search');

        $questions = OnlineQuestion::where('school_id', $schoolId)
            ->when($classId, function ($q) use ($classId) { $q->where('school_class_id', $classId); })
            ->when($subjectId, function ($q) use ($subjectId) { $q->where('subject_id', $subjectId); })
            ->when($type, function ($q) use ($type) { $q->where('question_type', $type); })
            ->when($difficulty, function ($q) use ($difficulty) { $q->where('difficulty', $difficulty); })
            ->when($search, function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")->orWhere('tags', 'like', "%{$search}%");
            })
            ->with(['schoolClass', 'subject', 'topic', 'exams'])
            ->withCount('exams')
            ->latest()
            ->paginate(15);

        return view('online_exams.question_bank', compact(
            'school',
            'classes',
            'subjects',
            'topics',
            'questions',
            'classId',
            'subjectId',
            'type',
            'difficulty',
            'search'
        ));
    }

    /**
     * Create Question Form
     */
    public function createQuestion(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $topics = QuestionTopic::where('school_id', $schoolId)->orderBy('name')->get();

        return view('online_exams.create_question', compact('school', 'classes', 'subjects', 'topics'));
    }

    /**
     * Store Question to Bank
     */
    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'question_type' => 'required|string',
            'school_class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'question_topic_id' => 'nullable|integer',
            'difficulty' => 'required|string',
            'question' => 'required|string',
            'marks' => 'required|numeric|min:0.5',
            'negative_marks' => 'nullable|numeric',
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
            'solution_html' => 'nullable|string',
            'hint_html' => 'nullable|string',
            'tags' => 'nullable|string',
        ]);

        $formattedOptions = [];
        if ($validated['question_type'] == 'true_false') {
            $formattedOptions = [
                ['key' => 'True', 'text' => 'True'],
                ['key' => 'False', 'text' => 'False'],
            ];
            $correctAnswers = [$request->input('tf_correct', 'True')];
        } else {
            if ($request->has('option_keys') && $request->has('option_texts')) {
                foreach ($request->input('option_keys') as $i => $k) {
                    $text = $request->input('option_texts')[$i] ?? '';
                    if (!empty($text)) {
                        $formattedOptions[] = ['key' => $k, 'text' => $text];
                    }
                }
            } else {
                $formattedOptions = [
                    ['key' => 'A', 'text' => $request->input('opt_a', 'Option A')],
                    ['key' => 'B', 'text' => $request->input('opt_b', 'Option B')],
                    ['key' => 'C', 'text' => $request->input('opt_c', 'Option C')],
                    ['key' => 'D', 'text' => $request->input('opt_d', 'Option D')],
                ];
            }
            $correctAnswers = [$validated['correct_answer'] ?? 'A'];
        }

        OnlineQuestion::create([
            'school_id' => 1,
            'school_class_id' => $validated['school_class_id'],
            'subject_id' => $validated['subject_id'],
            'question_topic_id' => $validated['question_topic_id'] ?? null,
            'question_type' => $validated['question_type'],
            'difficulty' => $validated['difficulty'],
            'question' => $validated['question'],
            'options' => $formattedOptions,
            'correct_answers' => $correctAnswers,
            'marks' => $validated['marks'],
            'negative_marks' => $validated['negative_marks'] ?? 0.00,
            'solution_html' => $validated['solution_html'] ?? null,
            'hint_html' => $validated['hint_html'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'is_active' => true,
        ]);

        return redirect()->route('online-exams.question-bank')
            ->with('success', 'Question saved to master question bank!');
    }

    /**
     * Delete Question
     */
    public function destroyQuestion($id)
    {
        $question = OnlineQuestion::findOrFail($id);
        $question->delete();

        return redirect()->back()->with('success', 'Question deleted from question bank.');
    }

    /**
     * Bulk Upload Questions
     */
    public function bulkUpload(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();

        return view('online_exams.bulk_upload', compact('school', 'classes', 'subjects'));
    }

    /**
     * Process Bulk Upload
     */
    public function processBulkUpload(Request $request)
    {
        $validated = $request->validate([
            'school_class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'json_data' => 'nullable|string',
        ]);

        $imported = 0;
        if (!empty($validated['json_data'])) {
            $items = json_decode($validated['json_data'], true);
            if (is_array($items)) {
                foreach ($items as $item) {
                    OnlineQuestion::create([
                        'school_id' => 1,
                        'school_class_id' => $validated['school_class_id'],
                        'subject_id' => $validated['subject_id'],
                        'question' => $item['question'] ?? 'Sample imported question',
                        'question_type' => $item['type'] ?? 'single_choice',
                        'difficulty' => $item['difficulty'] ?? 'Medium',
                        'options' => $item['options'] ?? [
                            ['key' => 'A', 'text' => 'Option A'],
                            ['key' => 'B', 'text' => 'Option B'],
                        ],
                        'correct_answers' => $item['correct'] ?? ['A'],
                        'marks' => $item['marks'] ?? 1.0,
                        'solution_html' => $item['solution'] ?? null,
                        'is_active' => true,
                    ]);
                    $imported++;
                }
            }
        }

        return redirect()->route('online-exams.question-bank')
            ->with('success', "Bulk upload completed! {$imported} questions successfully added.");
    }

    /**
     * 5. Chapters & Topics
     */
    public function questionTopics(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $topics = QuestionTopic::where('school_id', $schoolId)
            ->with(['subject', 'questions'])
            ->withCount('questions')
            ->latest()
            ->get();

        return view('online_exams.question_topics', compact('school', 'subjects', 'topics'));
    }

    /**
     * Store Topic
     */
    public function storeTopic(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'subject_id' => 'required|integer',
            'chapter' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        QuestionTopic::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'subject_id' => $validated['subject_id'],
            'chapter' => $validated['chapter'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->back()->with('success', "Topic / Chapter '{$validated['name']}' saved successfully!");
    }

    /**
     * Delete Topic
     */
    public function destroyTopic($id)
    {
        $topic = QuestionTopic::findOrFail($id);
        $name = $topic->name;
        $topic->delete();

        return redirect()->back()->with('success', "Topic '{$name}' deleted.");
    }

    /**
     * 6. Exam Results & Submissions
     */
    public function results($id)
    {
        $exam = OnlineExam::with(['schoolClass', 'section', 'subject', 'attempts.student.latestEnrollment.section'])
            ->findOrFail($id);

        $attempts = $exam->attempts()->with('student')->latest()->get();

        return view('online_exams.results', compact('exam', 'attempts'));
    }

    /**
     * Detailed Answer Sheet
     */
    public function showAnswerSheet($examId, $attemptId)
    {
        $exam = OnlineExam::with(['schoolClass', 'subject', 'questions'])->findOrFail($examId);
        $attempt = OnlineExamAttempt::with('student')->findOrFail($attemptId);

        return view('online_exams.answer_sheet', compact('exam', 'attempt'));
    }

    /**
     * 7. Live Student CBT Simulator (Take Exam)
     */
    public function takeExam($id)
    {
        $exam = OnlineExam::with(['schoolClass', 'subject', 'questions'])->findOrFail($id);
        $students = Student::where('school_id', 1)->take(10)->get();

        return view('online_exams.take_exam', compact('exam', 'students'));
    }

    /**
     * Submit Student Exam & Grade Automatically
     */
    public function submitExam(Request $request, $id)
    {
        $exam = OnlineExam::with('questions')->findOrFail($id);
        $studentId = $request->input('student_id', (Student::first() ? Student::first()->id : 1));

        $answers = $request->input('answers', []); // [question_id => selected_key]
        $totalQuestions = $exam->questions->count();
        $totalAnswered = count($answers);

        $correctCount = 0;
        $incorrectCount = 0;
        $scoreObtained = 0.0;
        $detailedResults = [];

        foreach ($exam->questions as $q) {
            $qMarks = $q->pivot->marks ?? $q->marks;
            $selected = $answers[$q->id] ?? null;

            $isCorrect = false;
            $marksAwarded = 0.0;

            if ($selected !== null) {
                $correct = $q->correct_answers[0] ?? null;
                if ($selected == $correct) {
                    $isCorrect = true;
                    $correctCount++;
                    $marksAwarded = $qMarks;
                    $scoreObtained += $qMarks;
                } else {
                    $incorrectCount++;
                    if ($exam->negative_marking) {
                        $marksAwarded = -abs($exam->negative_mark_rate);
                        $scoreObtained -= abs($exam->negative_mark_rate);
                    }
                }
            }

            $detailedResults[$q->id] = [
                'selected' => $selected,
                'is_correct' => $isCorrect,
                'marks_awarded' => $marksAwarded,
            ];
        }

        $totalMarks = $exam->total_marks > 0 ? $exam->total_marks : ($totalQuestions * 2);
        $percentage = round(($scoreObtained / $totalMarks) * 100, 1);
        $status = $percentage >= $exam->passing_percentage ? 'Passed' : 'Failed';

        $attempt = OnlineExamAttempt::create([
            'school_id' => 1,
            'online_exam_id' => $exam->id,
            'student_id' => $studentId,
            'started_at' => Carbon::now()->subMinutes(rand(10, $exam->duration)),
            'submitted_at' => Carbon::now(),
            'total_questions' => $totalQuestions,
            'total_answered' => $totalAnswered,
            'correct_answers_count' => $correctCount,
            'incorrect_answers_count' => $incorrectCount,
            'score_obtained' => max(0, $scoreObtained),
            'percentage' => max(0, $percentage),
            'status' => $status,
            'student_answers' => $detailedResults,
        ]);

        return redirect()->route('online-exams.answer-sheet', ['examId' => $exam->id, 'attemptId' => $attempt->id])
            ->with('success', "Exam submitted! Score: {$scoreObtained}/{$totalMarks} ({$percentage}%) - {$status}");
    }
}
