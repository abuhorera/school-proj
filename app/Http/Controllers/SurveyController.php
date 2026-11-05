<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\FeedbackTriageItem;
use App\Models\SchoolClass;
use App\Models\Staff;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SurveyController extends Controller
{
    /**
     * Survey Dashboard
     */
    public function dashboard()
    {
        $surveys = Survey::with(['questions', 'responses'])->latest()->take(7)->get();
        
        $totalSurveys = Survey::count();
        $publishedCount = Survey::where('status', 'published')->count();
        $draftCount = Survey::where('status', 'draft')->count();
        $totalResponses = Survey::sum('responses_count');
        $openFeedbackCount = FeedbackTriageItem::whereIn('status', ['New', 'In progress'])->count();

        $allPublished = Survey::where('status', 'published')->get();
        $avgResponseRate = 86;
        if ($allPublished->isNotEmpty()) {
            $rates = $allPublished->map(fn($s) => $s->response_rate);
            $avgResponseRate = round($rates->avg());
        }

        $stats = [
            'total_surveys' => $totalSurveys,
            'published_count' => $publishedCount,
            'draft_count' => $draftCount,
            'total_responses' => $totalResponses,
            'avg_response_rate' => $avgResponseRate,
            'open_feedback' => $openFeedbackCount,
        ];

        return view('surveys.dashboard', compact('surveys', 'stats'));
    }

    /**
     * All Surveys Ledger
     */
    public function index(Request $request)
    {
        $query = Survey::with(['questions', 'responses'])->latest();

        if ($request->filled('q')) {
            $query->where('title', 'like', "%{$request->q}%");
        }
        if ($request->filled('kind') && $request->kind !== 'All') {
            $query->where('kind', $request->kind);
        }
        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        $surveys = $query->paginate(15);

        return view('surveys.index', compact('surveys'));
    }

    /**
     * My Surveys Inbox (for student / parent / staff)
     */
    public function mine()
    {
        $surveys = Survey::whereIn('status', ['published', 'closed'])->latest()->get();
        return view('surveys.mine', compact('surveys'));
    }

    /**
     * Survey Templates Gallery
     */
    public function templates()
    {
        $templates = [
            'parent_satisfaction' => [
                'title' => 'Parent Satisfaction',
                'kind' => 'feedback_form',
                'description' => 'How happy are parents with teaching, communication and facilities. Great as a recurring termly pulse.',
                'questions_count' => 5,
                'is_anonymous' => false,
            ],
            'teacher_evaluation' => [
                'title' => 'Teacher Evaluation',
                'kind' => 'survey',
                'description' => 'Structured feedback on a teacher’s clarity, engagement and support. Usually run anonymously.',
                'questions_count' => 5,
                'is_anonymous' => true,
            ],
            'event_feedback' => [
                'title' => 'Event Feedback',
                'kind' => 'survey',
                'description' => 'Quick post-event feedback — sports day, annual function, PTM. Share as a public link too.',
                'questions_count' => 4,
                'is_anonymous' => false,
            ],
            'nps' => [
                'title' => 'Net Promoter (NPS)',
                'kind' => 'poll',
                'description' => 'The classic one-number loyalty pulse: “How likely are you to recommend us?” plus a reason.',
                'questions_count' => 2,
                'is_anonymous' => false,
            ],
            'student_wellbeing' => [
                'title' => 'Student Wellbeing Check',
                'kind' => 'feedback_form',
                'description' => 'A gentle anonymous check-in on how students are feeling at school.',
                'questions_count' => 4,
                'is_anonymous' => true,
            ],
            'suggestion_box' => [
                'title' => 'Suggestion Box',
                'kind' => 'survey',
                'description' => 'An always-open anonymous channel for ideas and concerns from anyone.',
                'questions_count' => 2,
                'is_anonymous' => true,
            ],
        ];

        return view('surveys.templates', compact('templates'));
    }

    /**
     * Instantiate Survey from Template
     */
    public function useTemplate($key)
    {
        $presets = [
            'parent_satisfaction' => [
                'title' => 'Parent Satisfaction Survey ' . date('Y'),
                'kind' => 'feedback_form',
                'description' => 'How happy are parents with teaching, communication and facilities.',
                'is_anonymous' => false,
                'questions' => [
                    ['text' => 'Overall, how satisfied are you with the school?', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'How satisfied are you with the quality of teaching?', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'How satisfied are you with communication from the school?', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'How likely are you to recommend the school to other parents?', 'type' => 'nps_score', 'min' => 0, 'max' => 10],
                    ['text' => 'What is one thing we could do better?', 'type' => 'paragraph', 'min' => 1, 'max' => 5],
                ],
            ],
            'teacher_evaluation' => [
                'title' => 'Teacher Evaluation Pulse',
                'kind' => 'survey',
                'description' => 'Structured feedback on clarity, engagement and support.',
                'is_anonymous' => true,
                'questions' => [
                    ['text' => 'The teacher explains concepts clearly.', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'The teacher encourages classroom questions.', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'Assignments are graded promptly with helpful feedback.', 'type' => 'yes_no', 'min' => 1, 'max' => 5],
                    ['text' => 'Rate your overall learning progress in this subject.', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'Any specific comments or feedback for this educator:', 'type' => 'paragraph', 'min' => 1, 'max' => 5],
                ],
            ],
            'event_feedback' => [
                'title' => 'Post-Event Feedback',
                'kind' => 'survey',
                'description' => 'Quick post-event evaluation from attendees and parents.',
                'is_anonymous' => false,
                'questions' => [
                    ['text' => 'How would you rate the overall event organization?', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'Were seating and refreshment arrangements satisfactory?', 'type' => 'yes_no', 'min' => 1, 'max' => 5],
                    ['text' => 'What was your favorite aspect of the event?', 'type' => 'short_text', 'min' => 1, 'max' => 5],
                    ['text' => 'Suggestions for future events:', 'type' => 'paragraph', 'min' => 1, 'max' => 5],
                ],
            ],
            'nps' => [
                'title' => 'Institutional NPS Survey',
                'kind' => 'poll',
                'description' => 'Loyalty rating: How likely are you to recommend us to peers?',
                'is_anonymous' => false,
                'questions' => [
                    ['text' => 'How likely are you to recommend our institution to family and friends?', 'type' => 'nps_score', 'min' => 0, 'max' => 10],
                    ['text' => 'What is the primary reason for your score?', 'type' => 'paragraph', 'min' => 1, 'max' => 5],
                ],
            ],
            'student_wellbeing' => [
                'title' => 'Student Wellbeing Check-in',
                'kind' => 'feedback_form',
                'description' => 'Gentle anonymous check-in on pupil wellbeing and campus comfort.',
                'is_anonymous' => true,
                'questions' => [
                    ['text' => 'How happy do you feel at school this week?', 'type' => 'rating_scale', 'min' => 1, 'max' => 5],
                    ['text' => 'Do you feel you have an adult at school you can talk to?', 'type' => 'yes_no', 'min' => 1, 'max' => 5],
                    ['text' => 'Do you feel safe on campus and in classrooms?', 'type' => 'yes_no', 'min' => 1, 'max' => 5],
                    ['text' => 'Is there anything you would like to tell us confidentially?', 'type' => 'paragraph', 'min' => 1, 'max' => 5],
                ],
            ],
            'suggestion_box' => [
                'title' => 'Campus Suggestion Box',
                'kind' => 'survey',
                'description' => 'Continuous open suggestions and feedback for campus improvement.',
                'is_anonymous' => true,
                'questions' => [
                    ['text' => 'What area of the school does your suggestion relate to?', 'type' => 'short_text', 'min' => 1, 'max' => 5],
                    ['text' => 'Please describe your idea or concern in detail:', 'type' => 'paragraph', 'min' => 1, 'max' => 5],
                ],
            ],
        ];

        $preset = $presets[$key] ?? $presets['parent_satisfaction'];

        $survey = Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => $preset['title'],
            'description' => $preset['description'],
            'slug' => Str::slug($preset['title']) . '-' . rand(100, 999),
            'access_token' => Str::random(32),
            'kind' => $preset['kind'],
            'status' => 'draft',
            'is_anonymous' => $preset['is_anonymous'],
            'closes_at' => now()->addMonth(),
            'invited_count' => 0,
            'responses_count' => 0,
        ]);

        foreach ($preset['questions'] as $idx => $q) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'order_num' => $idx + 1,
                'question_text' => $q['text'],
                'question_type' => $q['type'],
                'scale_min' => $q['min'],
                'scale_max' => $q['max'],
                'is_required' => true,
            ]);
        }

        return redirect()->route('survey.show', $survey->id)->with('success', "Survey template [{$survey->title}] created in Draft. You can now tweak questions or publish.");
    }

    /**
     * Create Survey Form
     */
    public function create(Request $request)
    {
        $classes = SchoolClass::orderBy('id')->get();
        $kind = $request->query('kind', 'survey');
        return view('surveys.create', compact('classes', 'kind'));
    }

    /**
     * Store New Survey
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'kind' => 'required|string',
        ]);

        $survey = Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => Str::slug($request->input('title')) . '-' . rand(100, 999),
            'access_token' => Str::random(32),
            'kind' => $request->input('kind', 'survey'),
            'status' => 'draft',
            'closes_at' => $request->input('closes_at'),
            'recurrence_freq' => $request->input('recurrence_freq'),
            'is_anonymous' => $request->boolean('is_anonymous'),
            'allow_resubmit' => $request->boolean('allow_resubmit'),
            'force_popup' => $request->boolean('force_popup'),
            'one_per_device' => $request->boolean('one_per_device'),
            'audience_type' => $request->input('audience_type', 'all'),
            'target_role_ids' => $request->input('target_role_ids'),
            'target_class_ids' => $request->input('target_class_ids'),
            'invited_count' => 30,
            'responses_count' => 0,
        ]);

        // Process questions from repeater
        $questions = $request->input('questions', []);
        if (empty($questions)) {
            // Default first question
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'order_num' => 1,
                'question_text' => 'Overall, how would you rate your experience?',
                'question_type' => 'rating_scale',
                'scale_min' => 1,
                'scale_max' => 5,
                'is_required' => true,
            ]);
        } else {
            foreach ($questions as $idx => $qData) {
                if (!empty($qData['text'])) {
                    SurveyQuestion::create([
                        'survey_id' => $survey->id,
                        'order_num' => $idx + 1,
                        'question_text' => $qData['text'],
                        'question_type' => $qData['type'] ?? 'rating_scale',
                        'scale_min' => $qData['min'] ?? 1,
                        'scale_max' => $qData['max'] ?? 5,
                        'is_required' => isset($qData['is_required']) && $qData['is_required'] == '1',
                        'options' => isset($qData['options']) ? array_map('trim', explode(',', $qData['options'])) : null,
                    ]);
                }
            }
        }

        return redirect()->route('survey.show', $survey->id)->with('success', "Survey [{$survey->title}] created successfully.");
    }

    /**
     * Show Survey Detail & Management
     */
    public function show($id)
    {
        $survey = Survey::with(['questions', 'responses'])->findOrFail($id);
        return view('surveys.show', compact('survey'));
    }

    /**
     * Edit Survey Form
     */
    public function edit($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);
        $classes = SchoolClass::orderBy('id')->get();
        return view('surveys.edit', compact('survey', 'classes'));
    }

    /**
     * Update Survey
     */
    public function update(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'kind' => 'required|string',
        ]);

        $survey->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'kind' => $request->input('kind'),
            'closes_at' => $request->input('closes_at'),
            'recurrence_freq' => $request->input('recurrence_freq'),
            'is_anonymous' => $request->boolean('is_anonymous'),
            'allow_resubmit' => $request->boolean('allow_resubmit'),
            'force_popup' => $request->boolean('force_popup'),
            'one_per_device' => $request->boolean('one_per_device'),
            'audience_type' => $request->input('audience_type', 'all'),
            'target_role_ids' => $request->input('target_role_ids'),
            'target_class_ids' => $request->input('target_class_ids'),
        ]);

        // If questions submitted, refresh them
        if ($request->has('questions')) {
            $survey->questions()->delete();
            foreach ($request->input('questions') as $idx => $qData) {
                if (!empty($qData['text'])) {
                    SurveyQuestion::create([
                        'survey_id' => $survey->id,
                        'order_num' => $idx + 1,
                        'question_text' => $qData['text'],
                        'question_type' => $qData['type'] ?? 'rating_scale',
                        'scale_min' => $qData['min'] ?? 1,
                        'scale_max' => $qData['max'] ?? 5,
                        'is_required' => isset($qData['is_required']) && $qData['is_required'] == '1',
                        'options' => isset($qData['options']) ? (is_array($qData['options']) ? $qData['options'] : array_map('trim', explode(',', $qData['options']))) : null,
                    ]);
                }
            }
        }

        return redirect()->route('survey.show', $survey->id)->with('success', "Survey [{$survey->title}] updated successfully.");
    }

    /**
     * Delete Survey
     */
    public function destroy($id)
    {
        $survey = Survey::findOrFail($id);
        $title = $survey->title;
        $survey->delete();

        return redirect()->route('survey.index')->with('success', "Survey [{$title}] deleted successfully.");
    }

    /**
     * Publish Survey
     */
    public function publish($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->update([
            'status' => 'published',
        ]);

        return back()->with('success', "Survey [{$survey->title}] is now Published and accepting responses.");
    }

    /**
     * Close Survey
     */
    public function close($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->update([
            'status' => 'closed',
        ]);

        return back()->with('success', "Survey [{$survey->title}] is now Closed.");
    }

    /**
     * Survey Analytics & Report
     */
    public function report($id)
    {
        $survey = Survey::with(['questions.answers', 'responses'])->findOrFail($id);

        // Precompute statistics for each question
        $analytics = [];
        foreach ($survey->questions as $q) {
            $answers = $q->answers;
            $totalAnswered = $answers->count();

            if (in_array($q->question_type, ['rating_scale', 'nps_score'])) {
                $avgScore = $totalAnswered > 0 ? round($answers->avg('numeric_value'), 2) : 0;
                $counts = [];
                for ($v = $q->scale_min; $v <= $q->scale_max; $v++) {
                    $cnt = $answers->where('numeric_value', $v)->count();
                    $pct = $totalAnswered > 0 ? round(($cnt / $totalAnswered) * 100) : 0;
                    $counts[$v] = ['count' => $cnt, 'percent' => $pct];
                }
                $analytics[$q->id] = [
                    'avg' => $avgScore,
                    'scale_range' => "{$q->scale_min}–{$q->scale_max}",
                    'counts' => $counts,
                    'total_answered' => $totalAnswered,
                ];
            } elseif ($q->question_type === 'yes_no') {
                $yesCount = $answers->filter(fn($a) => strtolower($a->answer_text ?? '') === 'yes')->count();
                $noCount = $answers->filter(fn($a) => strtolower($a->answer_text ?? '') === 'no')->count();
                $analytics[$q->id] = [
                    'yes_count' => $yesCount,
                    'no_count' => $noCount,
                    'yes_percent' => $totalAnswered > 0 ? round(($yesCount / $totalAnswered) * 100) : 0,
                    'no_percent' => $totalAnswered > 0 ? round(($noCount / $totalAnswered) * 100) : 0,
                    'total_answered' => $totalAnswered,
                ];
            } else {
                $textAnswers = $answers->pluck('answer_text')->filter()->take(15);
                $analytics[$q->id] = [
                    'total_answered' => $totalAnswered,
                    'sample_texts' => $textAnswers,
                ];
            }
        }

        return view('surveys.report', compact('survey', 'analytics'));
    }

    /**
     * Publish Survey Report
     */
    public function publishReport($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->update([
            'is_report_published' => true,
            'report_published_at' => now(),
        ]);

        return back()->with('success', "Survey report published successfully.");
    }

    /**
     * Export Survey Responses CSV
     */
    public function exportCsv($id)
    {
        $survey = Survey::with(['questions', 'responses.answers'])->findOrFail($id);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"survey_{$survey->id}_responses.csv\"",
        ];

        $callback = function () use ($survey) {
            $handle = fopen('php://output', 'w');
            
            // CSV Headers
            $colHeaders = ['Response ID', 'Submitted At', 'Respondent', 'Role', 'Device IP'];
            foreach ($survey->questions as $q) {
                $colHeaders[] = "Q{$q->order_num}: " . Str::limit($q->question_text, 40);
            }
            fputcsv($handle, $colHeaders);

            // CSV Rows
            foreach ($survey->responses as $resp) {
                $row = [
                    $resp->id,
                    $resp->submitted_at ? $resp->submitted_at->toDateTimeString() : '',
                    $survey->is_anonymous ? 'Anonymous' : ($resp->respondent_name ?: 'Guest'),
                    $resp->respondent_role ?: 'User',
                    $resp->device_ip ?: '—',
                ];

                foreach ($survey->questions as $q) {
                    $ans = $resp->answers->firstWhere('question_id', $q->id);
                    $val = '';
                    if ($ans) {
                        $val = $ans->numeric_value !== null ? $ans->numeric_value : ($ans->answer_text ?? '');
                    }
                    $row[] = $val;
                }
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Public / In-Portal Response Form
     */
    public function respondView($token)
    {
        $survey = Survey::with('questions')->where('access_token', $token)->orWhere('id', $token)->firstOrFail();
        return view('surveys.respond', compact('survey'));
    }

    /**
     * Submit Survey Response
     */
    public function submitResponse(Request $request, $token)
    {
        $survey = Survey::with('questions')->where('access_token', $token)->orWhere('id', $token)->firstOrFail();

        $resp = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => auth()->id() ?? null,
            'respondent_name' => $survey->is_anonymous ? 'Anonymous' : ($request->input('respondent_name') ?: 'Verified Parent'),
            'respondent_role' => 'Parent',
            'device_ip' => $request->ip(),
            'submitted_at' => now(),
        ]);

        $answers = $request->input('answers', []);
        $flagForTriage = false;
        $triageNote = '';

        foreach ($survey->questions as $q) {
            $val = $answers[$q->id] ?? null;
            if ($val !== null && $val !== '') {
                $numVal = is_numeric($val) ? (float) $val : null;
                $textVal = is_string($val) ? $val : null;

                SurveyAnswer::create([
                    'response_id' => $resp->id,
                    'question_id' => $q->id,
                    'numeric_value' => $numVal,
                    'answer_text' => $textVal,
                ]);

                // Check if negative review or complaint
                if ($numVal !== null && $numVal <= 2) {
                    $flagForTriage = true;
                    $triageNote .= "Low rating ({$numVal}/{$q->scale_max}) on '{$q->question_text}'. ";
                }
            }
        }

        $survey->increment('responses_count');

        // Auto-generate Triage item if flagged
        if ($flagForTriage) {
            FeedbackTriageItem::create([
                'school_id' => 1,
                'survey_id' => $survey->id,
                'response_id' => $resp->id,
                'title' => "Low Rating Alert on {$survey->title}",
                'status' => 'New',
                'priority' => 'High',
                'tags' => 'automated-alert, feedback-survey',
                'internal_notes' => $triageNote ?: 'Respondent gave low scores requiring admin attention.',
                'flagged_by' => 'Automated Monitor',
                'flagged_at' => now(),
            ]);
        }

        return redirect()->route('survey.mine')->with('success', "Thank you! Your survey response has been submitted successfully.");
    }

    /**
     * Survey Guide
     */
    public function guide()
    {
        return view('surveys.guide');
    }

    /**
     * Feedback Triage Board
     */
    public function triageIndex(Request $request)
    {
        $query = FeedbackTriageItem::with(['survey', 'assignee'])->latest('flagged_at');

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority') && $request->priority !== 'All') {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('assigned_to') && $request->assigned_to !== 'Anyone') {
            $query->where('assigned_to', $request->assigned_to);
        }
        if ($request->boolean('open_only')) {
            $query->whereIn('status', ['New', 'Reviewed', 'In progress']);
        }

        $items = $query->paginate(15);
        $staffMembers = Staff::orderBy('first_name')->take(10)->get();

        $stats = [
            'open' => FeedbackTriageItem::whereIn('status', ['New', 'In progress'])->count(),
            'urgent_open' => FeedbackTriageItem::where('priority', 'Urgent')->whereIn('status', ['New', 'In progress'])->count(),
            'assigned_to_me' => FeedbackTriageItem::where('assigned_to', 1)->whereIn('status', ['New', 'In progress'])->count(),
        ];

        return view('surveys.feedback_triage', compact('items', 'staffMembers', 'stats'));
    }

    /**
     * Feedback Triage Detail & Ticket Management
     */
    public function triageShow($id)
    {
        $item = FeedbackTriageItem::with(['survey.questions', 'response.answers.question', 'assignee'])->findOrFail($id);
        $staffMembers = Staff::orderBy('first_name')->take(15)->get();

        return view('surveys.feedback_detail', compact('item', 'staffMembers'));
    }

    /**
     * Update Feedback Triage Item
     */
    public function triageUpdate(Request $request, $id)
    {
        $item = FeedbackTriageItem::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|string',
            'priority' => 'required|string',
        ]);

        $item->update([
            'title' => $request->input('title'),
            'status' => $request->input('status'),
            'priority' => $request->input('priority'),
            'assigned_to' => $request->input('assigned_to') ?: null,
            'tags' => $request->input('tags'),
            'internal_notes' => $request->input('internal_notes'),
        ]);

        return back()->with('success', "Feedback action item updated successfully.");
    }

    /**
     * Delete Feedback Triage Item
     */
    public function triageDestroy($id)
    {
        $item = FeedbackTriageItem::findOrFail($id);
        $item->delete();

        return redirect()->route('feedback.index')->with('success', "Feedback triage item deleted.");
    }
}

