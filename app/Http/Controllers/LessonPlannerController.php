<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LessonPlan;
use App\Models\LessonPlanTopic;
use App\Models\LessonPlannerSetting;
use App\Models\DepartmentHead;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Staff;
use Carbon\Carbon;

class LessonPlannerController extends Controller
{
    /**
     * 1. Lesson Planner Dashboard
     */
    public function dashboard(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $totalPlans = LessonPlan::where('school_id', $schoolId)->count();
        $draftsCount = LessonPlan::where('school_id', $schoolId)->where('status', 'Draft')->count();
        $pendingReviewCount = LessonPlan::where('school_id', $schoolId)->whereIn('status', ['Pending HOD', 'Pending Principal'])->count();
        $approvedCount = LessonPlan::where('school_id', $schoolId)->where('status', 'Approved')->count();
        $revisionCount = LessonPlan::where('school_id', $schoolId)->where('status', 'Needs revision')->count();

        $overallCoverageRate = LessonPlan::where('school_id', $schoolId)
            ->where('status', 'Approved')
            ->avg('coverage_percentage') ?? 0;

        $recentPlans = LessonPlan::where('school_id', $schoolId)
            ->with(['schoolClass', 'section', 'subject', 'staff'])
            ->withCount('topics')
            ->latest()
            ->take(8)
            ->get();

        return view('lesson_plans.dashboard', compact(
            'school',
            'totalPlans',
            'draftsCount',
            'pendingReviewCount',
            'approvedCount',
            'revisionCount',
            'overallCoverageRate',
            'recentPlans'
        ));
    }

    /**
     * 2. Lesson Plans Master
     */
    public function plans(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $status = $request->get('status');
        $classId = $request->get('class_id');
        $week = $request->get('week');

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        $plans = LessonPlan::where('school_id', $schoolId)
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($classId, function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->when($week, function ($q) use ($week) {
                $q->whereDate('week_start_date', '<=', $week)
                  ->whereDate('week_end_date', '>=', $week);
            })
            ->with(['schoolClass', 'section', 'subject', 'staff'])
            ->withCount('topics')
            ->latest('week_start_date')
            ->get();

        return view('lesson_plans.plans', compact('school', 'plans', 'classes', 'status', 'classId', 'week'));
    }

    /**
     * Create Lesson Plan Form
     */
    public function create()
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $staffMembers = Staff::where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();

        return view('lesson_plans.create', compact('school', 'classes', 'sections', 'subjects', 'staffMembers'));
    }

    /**
     * Store new Lesson Plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'subject_id' => 'required|integer',
            'staff_id' => 'required|integer',
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date',
            'planned_periods' => 'required|integer|min:1',
            'chapter_names' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'teaching_methods' => 'nullable|array',
            'teaching_aids' => 'nullable|array',
            'resources_required' => 'nullable|string',
            'adhoc_topics' => 'nullable|string',
            'homework_planned' => 'nullable|string',
            'action_type' => 'nullable|string', // save_draft or submit_review
        ]);

        $schoolId = 1;
        $actionType = $request->input('action_type', 'save_draft');
        $status = ($actionType == 'submit_review') ? 'Pending HOD' : 'Draft';

        $weekNumber = Carbon::parse($validated['week_start_date'])->weekOfYear;

        $plan = LessonPlan::create([
            'school_id' => $schoolId,
            'title' => $validated['title'],
            'topic' => $validated['title'],
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'subject_id' => $validated['subject_id'],
            'staff_id' => $validated['staff_id'],
            'teacher_id' => $validated['staff_id'],
            'week_number' => $weekNumber,
            'week_start_date' => $validated['week_start_date'],
            'week_end_date' => $validated['week_end_date'],
            'planned_periods' => $validated['planned_periods'],
            'chapter_names' => $validated['chapter_names'] ?? null,
            'learning_objectives' => $validated['learning_objectives'] ?? null,
            'teaching_methods' => $validated['teaching_methods'] ?? ['Lecture', 'Discussion'],
            'teaching_aids' => $validated['teaching_aids'] ?? ['Whiteboard'],
            'resources_required' => $validated['resources_required'] ?? null,
            'adhoc_topics' => $validated['adhoc_topics'] ?? null,
            'homework_planned' => $validated['homework_planned'] ?? null,
            'status' => $status,
            'submitted_at' => ($status != 'Draft') ? Carbon::now() : null,
            'coverage_percentage' => 0.00,
        ]);

        // Auto-split adhoc_topics into LessonPlanTopic records
        if (!empty($validated['adhoc_topics'])) {
            $lines = preg_split('/[\r\n]+/', $validated['adhoc_topics']);
            foreach ($lines as $line) {
                $trimmed = trim($line, " \t\n\r\0\x0B-•*0123456789.)");
                if (!empty($trimmed)) {
                    LessonPlanTopic::create([
                        'school_id' => $schoolId,
                        'lesson_plan_id' => $plan->id,
                        'topic_name' => $trimmed,
                        'periods_allocated' => 1,
                        'coverage_status' => 'Pending',
                    ]);
                }
            }
        }

        // If no topics created, create a default topic from title
        if ($plan->topics()->count() == 0) {
            LessonPlanTopic::create([
                'school_id' => $schoolId,
                'lesson_plan_id' => $plan->id,
                'topic_name' => $plan->title,
                'periods_allocated' => $plan->planned_periods,
                'coverage_status' => 'Pending',
            ]);
        }

        $msg = ($status == 'Draft') 
            ? "Lesson plan '{$plan->title}' saved as Draft."
            : "Lesson plan '{$plan->title}' submitted for HOD review!";

        return redirect()->route('lesson-plans.plans')->with('success', $msg);
    }

    /**
     * Show Lesson Plan Detail
     */
    public function show($id)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $plan = LessonPlan::where('school_id', $schoolId)
            ->with(['schoolClass', 'section', 'subject', 'staff', 'topics', 'reviewer', 'approver'])
            ->findOrFail($id);

        return view('lesson_plans.show', compact('school', 'plan'));
    }

    /**
     * Edit Lesson Plan Form
     */
    public function edit($id)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();
        $plan = LessonPlan::where('school_id', $schoolId)->findOrFail($id);

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $sections = Section::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $staffMembers = Staff::where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();

        return view('lesson_plans.edit', compact('school', 'plan', 'classes', 'sections', 'subjects', 'staffMembers'));
    }

    /**
     * Update Lesson Plan
     */
    public function update(Request $request, $id)
    {
        $plan = LessonPlan::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'subject_id' => 'required|integer',
            'staff_id' => 'required|integer',
            'week_start_date' => 'required|date',
            'week_end_date' => 'required|date',
            'planned_periods' => 'required|integer|min:1',
            'chapter_names' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'teaching_methods' => 'nullable|array',
            'teaching_aids' => 'nullable|array',
            'resources_required' => 'nullable|string',
            'adhoc_topics' => 'nullable|string',
            'homework_planned' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $plan->update([
            'title' => $validated['title'],
            'topic' => $validated['title'],
            'class_id' => $validated['class_id'],
            'section_id' => $validated['section_id'] ?? null,
            'subject_id' => $validated['subject_id'],
            'staff_id' => $validated['staff_id'],
            'teacher_id' => $validated['staff_id'],
            'week_start_date' => $validated['week_start_date'],
            'week_end_date' => $validated['week_end_date'],
            'planned_periods' => $validated['planned_periods'],
            'chapter_names' => $validated['chapter_names'],
            'learning_objectives' => $validated['learning_objectives'],
            'teaching_methods' => $validated['teaching_methods'] ?? $plan->teaching_methods,
            'teaching_aids' => $validated['teaching_aids'] ?? $plan->teaching_aids,
            'resources_required' => $validated['resources_required'],
            'adhoc_topics' => $validated['adhoc_topics'],
            'homework_planned' => $validated['homework_planned'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('lesson-plans.plans')->with('success', "Lesson plan '{$plan->title}' updated successfully!");
    }

    /**
     * Delete Lesson Plan
     */
    public function destroy($id)
    {
        $plan = LessonPlan::findOrFail($id);
        $title = $plan->title;
        $plan->delete();

        return redirect()->route('lesson-plans.plans')->with('success', "Lesson plan '{$title}' removed.");
    }

    /**
     * Clone Plan to Next Week
     */
    public function clonePlan($id)
    {
        $original = LessonPlan::with('topics')->findOrFail($id);

        $nextWeekStart = Carbon::parse($original->week_start_date)->addWeek()->toDateString();
        $nextWeekEnd = Carbon::parse($original->week_end_date)->addWeek()->toDateString();
        $nextWeekNumber = Carbon::parse($nextWeekStart)->weekOfYear;

        $cloned = $original->replicate([
            'status',
            'coverage_percentage',
            'review_feedback',
            'reviewed_by',
            'approved_by',
            'submitted_at',
            'approved_at',
            'created_at',
            'updated_at',
        ]);

        $cloned->title = $original->title . ' (Copy)';
        $cloned->topic = $cloned->title;
        $cloned->week_start_date = $nextWeekStart;
        $cloned->week_end_date = $nextWeekEnd;
        $cloned->week_number = $nextWeekNumber;
        $cloned->status = 'Draft';
        $cloned->coverage_percentage = 0.00;
        $cloned->save();

        foreach ($original->topics as $t) {
            LessonPlanTopic::create([
                'school_id' => $cloned->school_id,
                'lesson_plan_id' => $cloned->id,
                'topic_name' => $t->topic_name,
                'subtopic_name' => $t->subtopic_name,
                'periods_allocated' => $t->periods_allocated,
                'coverage_status' => 'Pending',
            ]);
        }

        return redirect()->back()->with('success', "Lesson plan cloned to next week (Week {$nextWeekNumber}) as a new Draft!");
    }

    /**
     * Submit Plan for Review
     */
    public function submitForReview($id)
    {
        $plan = LessonPlan::findOrFail($id);
        $settings = LessonPlannerSetting::where('school_id', 1)->first();
        $targetStatus = ($settings && !$settings->hod_step_enabled) ? 'Pending Principal' : 'Pending HOD';

        $plan->update([
            'status' => $targetStatus,
            'submitted_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', "Plan '{$plan->title}' submitted for review ({$targetStatus})!");
    }

    /**
     * 3. Lesson Plan Review (Awaiting HOD)
     */
    public function reviewIndex(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $plans = LessonPlan::where('school_id', $schoolId)
            ->where('status', 'Pending HOD')
            ->with(['schoolClass', 'section', 'subject', 'staff', 'topics'])
            ->latest('submitted_at')
            ->get();

        return view('lesson_plans.review', compact('school', 'plans'));
    }

    /**
     * HOD Review Action
     */
    public function reviewAction(Request $request, $id)
    {
        $plan = LessonPlan::findOrFail($id);
        $action = $request->input('action', 'forward_to_principal');
        $feedback = $request->input('feedback');

        if ($action == 'forward_to_principal') {
            $plan->update([
                'status' => 'Pending Principal',
                'review_feedback' => $feedback ?? 'HOD endorsed; forwarded for Principal approval.',
                'reviewed_by' => 1,
            ]);
            $msg = "Plan endorsed and forwarded to Principal for final sign-off.";
        } else {
            $plan->update([
                'status' => 'Needs revision',
                'review_feedback' => $feedback ?? 'Please revise learning objectives and period pacing.',
                'reviewed_by' => 1,
            ]);
            $msg = "Plan returned to teacher for revisions.";
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * 4. Lesson Plan Approvals (Awaiting Principal)
     */
    public function approvalsIndex(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classId = $request->get('class_id');
        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        $plans = LessonPlan::where('school_id', $schoolId)
            ->where('status', 'Pending Principal')
            ->when($classId, function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->with(['schoolClass', 'section', 'subject', 'staff', 'topics'])
            ->latest('submitted_at')
            ->get();

        return view('lesson_plans.approvals', compact('school', 'plans', 'classes', 'classId'));
    }

    /**
     * Principal Single Approval
     */
    public function approvePlan(Request $request, $id)
    {
        $plan = LessonPlan::findOrFail($id);
        $plan->update([
            'status' => 'Approved',
            'approved_at' => Carbon::now(),
            'approved_by' => 1,
        ]);

        return redirect()->back()->with('success', "Lesson plan '{$plan->title}' approved!");
    }

    /**
     * Principal Bulk Approval
     */
    public function bulkApprove(Request $request)
    {
        $planIds = $request->input('plan_ids', []);

        if (empty($planIds)) {
            return redirect()->back()->with('error', "No plans selected for bulk approval.");
        }

        LessonPlan::whereIn('id', $planIds)->update([
            'status' => 'Approved',
            'approved_at' => Carbon::now(),
            'approved_by' => 1,
        ]);

        return redirect()->back()->with('success', count($planIds) . " lesson plans approved in batch!");
    }

    /**
     * 5. Lesson Plan Coverage Tracker
     */
    public function coverageIndex(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $show = $request->get('show', 'all');

        $plans = LessonPlan::where('school_id', $schoolId)
            ->where('status', 'Approved')
            ->when($show == 'pending', function ($q) {
                $q->where('coverage_percentage', '<', 100);
            })
            ->with(['schoolClass', 'section', 'subject', 'staff', 'topics'])
            ->withCount([
                'topics',
                'topics as covered_count' => function ($sq) {
                    $sq->where('coverage_status', 'Covered');
                }
            ])
            ->latest('week_start_date')
            ->get();

        return view('lesson_plans.coverage', compact('school', 'plans', 'show'));
    }

    /**
     * Mark Coverage Form
     */
    public function coverageDetail($id)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $plan = LessonPlan::where('school_id', $schoolId)
            ->with(['schoolClass', 'section', 'subject', 'staff', 'topics'])
            ->findOrFail($id);

        return view('lesson_plans.coverage_detail', compact('school', 'plan'));
    }

    /**
     * Save Topic-wise Coverage
     */
    public function saveCoverage(Request $request, $id)
    {
        $plan = LessonPlan::with('topics')->findOrFail($id);
        $topicData = $request->input('topics', []);

        foreach ($topicData as $topicId => $data) {
            $topic = LessonPlanTopic::where('lesson_plan_id', $plan->id)->find($topicId);
            if ($topic) {
                $topic->update([
                    'coverage_status' => $data['coverage_status'] ?? 'Pending',
                    'coverage_reason' => $data['coverage_reason'] ?? null,
                    'covered_on' => $data['covered_on'] ?? ($data['coverage_status'] == 'Covered' ? Carbon::today()->toDateString() : null),
                ]);
            }
        }

        // Recalculate coverage percentage
        $total = $plan->topics()->count();
        if ($total > 0) {
            $covered = $plan->topics()->where('coverage_status', 'Covered')->count();
            $partial = $plan->topics()->where('coverage_status', 'Partial')->count();
            $effectiveCovered = $covered + ($partial * 0.5);
            $percentage = round(($effectiveCovered / $total) * 100, 2);

            $plan->update(['coverage_percentage' => $percentage]);
        }

        return redirect()->route('lesson-plans.coverage')->with('success', "Coverage progress saved! Overall completion: {$plan->coverage_percentage}%");
    }

    /**
     * 6. Lesson Plan Reports & Analytics
     */
    public function reports(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $classId = $request->get('class_id');
        $subjectId = $request->get('subject_id');
        $staffId = $request->get('staff_id');

        $classes = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $subjects = Subject::where('school_id', $schoolId)->orderBy('name')->get();
        $staffMembers = Staff::where('school_id', $schoolId)->orderBy('first_name')->get();

        $plans = LessonPlan::where('school_id', $schoolId)
            ->where('status', 'Approved')
            ->when($classId, function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->when($subjectId, function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->when($staffId, function ($q) use ($staffId) {
                $q->where('staff_id', $staffId);
            })
            ->with(['schoolClass', 'section', 'subject', 'staff', 'topics'])
            ->get();

        $totalTopics = 0;
        $coveredTopics = 0;
        $partialTopics = 0;
        $notCoveredTopics = 0;
        $pendingTopics = 0;

        foreach ($plans as $p) {
            foreach ($p->topics as $t) {
                $totalTopics++;
                if ($t->coverage_status == 'Covered') $coveredTopics++;
                elseif ($t->coverage_status == 'Partial') $partialTopics++;
                elseif ($t->coverage_status == 'Not Covered') $notCoveredTopics++;
                else $pendingTopics++;
            }
        }

        $overallRate = $totalTopics > 0 ? round((($coveredTopics + ($partialTopics * 0.5)) / $totalTopics) * 100, 1) : 0;

        return view('lesson_plans.reports', compact(
            'school',
            'classes',
            'subjects',
            'staffMembers',
            'plans',
            'classId',
            'subjectId',
            'staffId',
            'totalTopics',
            'coveredTopics',
            'partialTopics',
            'notCoveredTopics',
            'pendingTopics',
            'overallRate'
        ));
    }

    /**
     * 7. Lesson Planner Settings
     */
    public function settings(Request $request)
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        $settings = LessonPlannerSetting::firstOrCreate(
            ['school_id' => $schoolId],
            [
                'hod_step_enabled' => true,
                'allow_edit_after_submit' => false,
                'reminder_enabled' => true,
                'reminder_day' => 'Friday',
                'reminder_time' => '15:00',
                'submission_deadline_day' => 'Monday',
                'submission_deadline_time' => '09:00',
            ]
        );

        $departmentHeads = DepartmentHead::where('school_id', $schoolId)->with('headStaff')->get();
        $staffMembers = Staff::where('school_id', $schoolId)->where('status', 'active')->orderBy('first_name')->get();

        return view('lesson_plans.settings', compact('school', 'settings', 'departmentHeads', 'staffMembers'));
    }

    /**
     * Update Settings
     */
    public function updateSettings(Request $request)
    {
        $settings = LessonPlannerSetting::firstOrCreate(['school_id' => 1]);

        $settings->update([
            'hod_step_enabled' => $request->has('hod_step_enabled'),
            'allow_edit_after_submit' => $request->has('allow_edit_after_submit'),
            'reminder_enabled' => $request->has('reminder_enabled'),
            'reminder_day' => $request->input('reminder_day', 'Friday'),
            'reminder_time' => $request->input('reminder_time', '15:00'),
            'submission_deadline_day' => $request->input('submission_deadline_day', 'Monday'),
            'submission_deadline_time' => $request->input('submission_deadline_time', '09:00'),
        ]);

        return redirect()->back()->with('success', "Lesson planner workflow & reminder rules saved!");
    }

    /**
     * Assign Department Head
     */
    public function assignDepartmentHead(Request $request)
    {
        $validated = $request->validate([
            'department' => 'required|string|max:191',
            'head_staff_id' => 'required|integer',
        ]);

        DepartmentHead::updateOrCreate(
            ['school_id' => 1, 'department' => $validated['department']],
            ['head_staff_id' => $validated['head_staff_id']]
        );

        return redirect()->back()->with('success', "Department head mapped for '{$validated['department']}'!");
    }

    /**
     * 8. Lesson Planner Guide
     */
    public function guide()
    {
        $schoolId = 1;
        $school = School::find($schoolId) ?? School::first();

        return view('lesson_plans.guide', compact('school'));
    }
}

