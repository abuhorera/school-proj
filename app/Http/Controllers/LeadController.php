<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\LeadSource;
use App\Models\LeadFollowup;
use App\Models\SchoolClass;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Lead Management Dashboard with KPIs, Funnel, and Analytics
     */
    public function dashboard(Request $request)
    {
        $schoolId = 1;
        $today = Carbon::today()->toDateString();

        $totalLeads = Lead::where('school_id', $schoolId)->count();
        $activeLeads = Lead::where('school_id', $schoolId)->where('status', 'active')->count();
        $convertedLeads = Lead::where('school_id', $schoolId)->where('status', 'converted')->count();
        $lostLeads = Lead::where('school_id', $schoolId)->where('status', 'lost')->count();

        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;

        // Pipeline Stages Funnel
        $stages = LeadStage::where('school_id', $schoolId)
            ->where('is_active', true)
            ->withCount(['leads' => function ($q) {
                $q->whereNull('deleted_at');
            }])
            ->orderBy('sort_order')
            ->get();

        // Source Distribution
        $sources = LeadSource::where('school_id', $schoolId)
            ->where('is_active', true)
            ->withCount(['leads' => function ($q) {
                $q->whereNull('deleted_at');
            }])
            ->get();

        // Counselor Workload
        $counselors = Staff::where('school_id', $schoolId)
            ->withCount(['leads as assigned_leads_count' => function ($q) {
                $q->where('status', 'active');
            }])
            ->get();

        // Recent Follow-ups and Pending Tasks
        $todayFollowups = Lead::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('follow_up_date', $today)
            ->with(['stage', 'source', 'schoolClass', 'assignedStaff'])
            ->get();

        $overdueFollowups = Lead::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('follow_up_date', '<', $today)
            ->with(['stage', 'source', 'schoolClass', 'assignedStaff'])
            ->get();

        $recentLeads = Lead::where('school_id', $schoolId)
            ->with(['stage', 'source', 'schoolClass', 'assignedStaff'])
            ->latest()
            ->take(8)
            ->get();

        $schoolClasses = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();

        return view('leads.dashboard', compact(
            'totalLeads',
            'activeLeads',
            'convertedLeads',
            'lostLeads',
            'conversionRate',
            'stages',
            'sources',
            'counselors',
            'todayFollowups',
            'overdueFollowups',
            'recentLeads',
            'schoolClasses'
        ));
    }

    /**
     * Interactive Kanban Pipeline Board
     */
    public function board(Request $request)
    {
        $schoolId = 1;

        $stagesQuery = LeadStage::where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('sort_order');

        // Apply filters to leads inside stages
        $classFilter = $request->get('class_id');
        $sourceFilter = $request->get('source_id');
        $counselorFilter = $request->get('assigned_to');
        $search = $request->get('search');

        $stages = $stagesQuery->with(['leads' => function ($q) use ($classFilter, $sourceFilter, $counselorFilter, $search) {
            if ($classFilter) {
                $q->where('school_class_id', $classFilter);
            }
            if ($sourceFilter) {
                $q->where('lead_source_id', $sourceFilter);
            }
            if ($counselorFilter) {
                $q->where('assigned_to', $counselorFilter);
            }
            if ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('student_name', 'like', "%{$search}%")
                        ->orWhere('parent_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }
            $q->with(['source', 'schoolClass', 'assignedStaff', 'followups'])
              ->latest();
        }])->get();

        $sources = LeadSource::where('school_id', $schoolId)->where('is_active', true)->get();
        $schoolClasses = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $counselors = Staff::where('school_id', $schoolId)->get();

        return view('leads.board', compact('stages', 'sources', 'schoolClasses', 'counselors'));
    }

    /**
     * All Leads Directory with Filters & Pagination
     */
    public function list(Request $request)
    {
        $schoolId = 1;

        $query = Lead::where('school_id', $schoolId)
            ->with(['stage', 'source', 'schoolClass', 'assignedStaff', 'followups']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('student_name', 'like', "%{$s}%")
                  ->orWhere('parent_name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        if ($request->filled('stage_id')) {
            $query->where('lead_stage_id', $request->stage_id);
        }

        if ($request->filled('source_id')) {
            $query->where('lead_source_id', $request->source_id);
        }

        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(15)->withQueryString();

        $stages = LeadStage::where('school_id', $schoolId)->orderBy('sort_order')->get();
        $sources = LeadSource::where('school_id', $schoolId)->get();
        $schoolClasses = SchoolClass::where('school_id', $schoolId)->orderBy('name')->get();
        $counselors = Staff::where('school_id', $schoolId)->get();

        return view('leads.list', compact('leads', 'stages', 'sources', 'schoolClasses', 'counselors'));
    }

    /**
     * Follow-up Scheduler & Call Tasks
     */
    public function tasks(Request $request)
    {
        $schoolId = 1;
        $today = Carbon::today()->toDateString();

        $todayTasks = Lead::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('follow_up_date', $today)
            ->with(['stage', 'source', 'schoolClass', 'assignedStaff', 'followups'])
            ->orderBy('follow_up_date', 'asc')
            ->get();

        $overdueTasks = Lead::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('follow_up_date', '<', $today)
            ->with(['stage', 'source', 'schoolClass', 'assignedStaff', 'followups'])
            ->orderBy('follow_up_date', 'asc')
            ->get();

        $upcomingTasks = Lead::where('school_id', $schoolId)
            ->where('status', 'active')
            ->whereDate('follow_up_date', '>', $today)
            ->with(['stage', 'source', 'schoolClass', 'assignedStaff', 'followups'])
            ->orderBy('follow_up_date', 'asc')
            ->get();

        $recentLogs = LeadFollowup::whereHas('lead', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with(['lead', 'user'])
            ->latest()
            ->take(20)
            ->get();

        $stages = LeadStage::where('school_id', $schoolId)->orderBy('sort_order')->get();

        return view('leads.tasks', compact('todayTasks', 'overdueTasks', 'upcomingTasks', 'recentLogs', 'stages'));
    }

    /**
     * Lead Sources & Pipeline Stages Configuration
     */
    public function settings(Request $request)
    {
        $schoolId = 1;

        $stages = LeadStage::where('school_id', $schoolId)
            ->withCount('leads')
            ->orderBy('sort_order')
            ->get();

        $sources = LeadSource::where('school_id', $schoolId)
            ->withCount('leads')
            ->get();

        return view('leads.settings', compact('stages', 'sources'));
    }

    /**
     * Store new Lead Enquiry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:191',
            'parent_name' => 'required|string|max:191',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:191',
            'school_class_id' => 'nullable|integer',
            'lead_source_id' => 'nullable|integer',
            'lead_stage_id' => 'nullable|integer',
            'assigned_to' => 'nullable|integer',
            'follow_up_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $schoolId = 1;

        // Default stage if not selected
        if (empty($validated['lead_stage_id'])) {
            $firstStage = LeadStage::where('school_id', $schoolId)->orderBy('sort_order')->first();
            $validated['lead_stage_id'] = $firstStage ? $firstStage->id : null;
        }

        // Get class name
        if (!empty($validated['school_class_id'])) {
            $class = SchoolClass::find($validated['school_class_id']);
            $validated['class_name'] = $class ? $class->name : null;
        }

        // Get source name
        if (!empty($validated['lead_source_id'])) {
            $src = LeadSource::find($validated['lead_source_id']);
            $validated['source_name'] = $src ? $src->name : null;
        }

        $validated['school_id'] = $schoolId;
        $validated['status'] = 'active';

        $lead = Lead::create($validated);

        // If follow up notes provided, create initial log
        if (!empty($validated['notes']) || !empty($validated['follow_up_date'])) {
            LeadFollowup::create([
                'lead_id' => $lead->id,
                'user_id' => Auth::id() ?? 1,
                'follow_up_date' => Carbon::now()->toDateString(),
                'type' => 'Initial Enquiry',
                'response_notes' => $validated['notes'] ?? 'Lead received and logged in system.',
                'next_follow_up_date' => $validated['follow_up_date'] ?? null,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'lead' => $lead]);
        }

        return redirect()->back()->with('success', "Lead for '{$lead->student_name}' created successfully!");
    }

    /**
     * Move Lead to another Stage (Board drag-drop / quick select)
     */
    public function updateStage(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $stageId = $request->input('lead_stage_id');

        $stage = LeadStage::findOrFail($stageId);
        $oldStageName = $lead->stage ? $lead->stage->name : 'None';

        $lead->lead_stage_id = $stage->id;

        // Update status based on stage
        if (stripos($stage->name, 'Enrolled') !== false || stripos($stage->name, 'Admitted') !== false) {
            $lead->status = 'converted';
        } elseif (stripos($stage->name, 'Lost') !== false || stripos($stage->name, 'Reject') !== false || stripos($stage->name, 'Drop') !== false) {
            $lead->status = 'lost';
        } else {
            $lead->status = 'active';
        }

        $lead->save();

        // Optional log
        $notes = $request->input('notes', "Moved stage from '{$oldStageName}' to '{$stage->name}'");
        LeadFollowup::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id() ?? 1,
            'follow_up_date' => Carbon::now()->toDateString(),
            'type' => 'Stage Transition',
            'response_notes' => $notes,
            'next_follow_up_date' => $request->input('next_follow_up_date'),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Stage updated to {$stage->name}",
                'lead' => $lead
            ]);
        }

        return redirect()->back()->with('success', "Lead moved to '{$stage->name}' successfully!");
    }

    /**
     * Log a follow-up interaction
     */
    public function storeFollowup(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string',
            'response_notes' => 'required|string',
            'next_follow_up_date' => 'nullable|date',
            'lead_stage_id' => 'nullable|integer',
        ]);

        LeadFollowup::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id() ?? 1,
            'follow_up_date' => Carbon::now()->toDateString(),
            'type' => $validated['type'],
            'response_notes' => $validated['response_notes'],
            'next_follow_up_date' => $validated['next_follow_up_date'] ?? null,
        ]);

        if (!empty($validated['next_follow_up_date'])) {
            $lead->follow_up_date = $validated['next_follow_up_date'];
        }

        if (!empty($validated['lead_stage_id'])) {
            $stage = LeadStage::find($validated['lead_stage_id']);
            if ($stage) {
                $lead->lead_stage_id = $stage->id;
                if (stripos($stage->name, 'Enrolled') !== false) {
                    $lead->status = 'converted';
                } elseif (stripos($stage->name, 'Lost') !== false) {
                    $lead->status = 'lost';
                }
            }
        }

        $lead->save();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Follow-up logged successfully!']);
        }

        return redirect()->back()->with('success', 'Follow-up recorded successfully!');
    }

    /**
     * Delete Lead
     */
    public function delete($id)
    {
        $lead = Lead::findOrFail($id);
        $name = $lead->student_name;
        $lead->followups()->delete();
        $lead->delete();

        return redirect()->back()->with('success', "Lead '{$name}' deleted successfully.");
    }

    /**
     * Settings: Add new Stage
     */
    public function storeStage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'color' => 'required|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $maxOrder = LeadStage::where('school_id', 1)->max('sort_order') ?? 0;

        LeadStage::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'color' => $validated['color'],
            'sort_order' => $validated['sort_order'] ?? ($maxOrder + 1),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Stage '{$validated['name']}' created successfully.");
    }

    /**
     * Settings: Update Stage
     */
    public function updateStageSetting(Request $request, $id)
    {
        $stage = LeadStage::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'color' => 'required|string|max:20',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $stage->update([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'sort_order' => $validated['sort_order'] ?? $stage->sort_order,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->back()->with('success', "Stage '{$stage->name}' updated successfully.");
    }

    /**
     * Settings: Add new Lead Source
     */
    public function storeSource(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
        ]);

        LeadSource::create([
            'school_id' => 1,
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', "Source channel '{$validated['name']}' added successfully.");
    }
}
