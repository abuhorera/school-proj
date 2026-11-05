<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HpcFramework;
use App\Models\HpcActivity;
use App\Models\HpcActivityMark;
use App\Models\HpcCard;
use App\Models\HpcCardSetting;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AcademicSession;
use Illuminate\Support\Facades\Storage;

class HpcController extends Controller
{
    /**
     * HPC Dashboard Overview
     */
    public function dashboard()
    {
        $frameworks = HpcFramework::withCount('classes')->get();
        $activeFrameworksCount = $frameworks->where('is_active', true)->count();
        $totalClassesCount = SchoolClass::count();
        $assignedClassesCount = $frameworks->sum('classes_count');
        $unassignedClassesCount = max(0, $totalClassesCount - $assignedClassesCount);

        $totalCardsCount = HpcCard::count();
        $publishedCardsCount = HpcCard::where('status', 'published')->count();
        $draftCardsCount = HpcCard::where('status', 'draft')->count();
        $submittedCardsCount = HpcCard::where('status', 'teacher_submitted')->count();
        $reviewedCardsCount = HpcCard::where('status', 'reviewed')->count();

        $totalActivitiesCount = HpcActivity::count();

        $recentCards = HpcCard::with(['student', 'schoolClass', 'framework'])
            ->latest()
            ->take(8)
            ->get();

        $setting = HpcCardSetting::first() ?? new HpcCardSetting();

        return view('hpc.dashboard', compact(
            'frameworks',
            'activeFrameworksCount',
            'totalClassesCount',
            'assignedClassesCount',
            'unassignedClassesCount',
            'totalCardsCount',
            'publishedCardsCount',
            'draftCardsCount',
            'submittedCardsCount',
            'reviewedCardsCount',
            'totalActivitiesCount',
            'recentCards',
            'setting'
        ));
    }

    /**
     * Activities Ledger
     */
    public function activities(Request $request)
    {
        $query = HpcActivity::with(['schoolClass', 'framework', 'marks']);

        if ($request->filled('type')) {
            $query->where('activity_type', $request->type);
        }

        if ($request->filled('school_class_id')) {
            $query->where('school_class_id', $request->school_class_id);
        }

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $activities = $query->latest()->paginate(15)->withQueryString();
        $classes = SchoolClass::orderBy('name')->get();
        $activityTypes = ['Learning Activity', 'Group Project Work', 'Problem-based Task', 'Creative Expression', 'Physical Game', 'Experiential Inquiry'];

        return view('hpc.activities', compact('activities', 'classes', 'activityTypes'));
    }

    /**
     * Show New Activity Form
     */
    public function createActivity()
    {
        $frameworks = HpcFramework::where('is_active', true)->get();
        $classes = SchoolClass::orderBy('name')->get();
        $domains = [
            'Physical Development',
            'Socio-Emotional and Ethical Development',
            'Cognitive Development',
            'Language and Literacy Development',
            'Aesthetic and Cultural Development',
            'Positive Learning Habits',
        ];
        $pedagogies = [
            'Toy-based', 'Storytelling', 'Arts-integrated', 'Play-based', 'Experiential',
            'Sports-integrated', 'Peer tutoring', 'Nature exploration', 'Roleplay',
        ];

        return view('hpc.create_activity', compact('frameworks', 'classes', 'domains', 'pedagogies'));
    }

    /**
     * Store New Activity
     */
    public function storeActivity(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'school_class_id' => 'required|exists:classes,id',
            'domain' => 'required|string',
            'activity_type' => 'nullable|string',
            'term' => 'nullable|string',
            'duration_minutes' => 'nullable|integer',
            'prompt' => 'nullable|string',
        ]);

        $activity = HpcActivity::create([
            'framework_id' => $request->framework_id,
            'school_class_id' => $request->school_class_id,
            'title' => $request->title,
            'activity_type' => $request->activity_type ?? 'Learning Activity',
            'domain' => $request->domain,
            'interaction_type' => $request->interaction_type ?? 'Individual',
            'duration_minutes' => $request->duration_minutes ?? 45,
            'term' => $request->term ?? 'Full year',
            'prompt' => $request->prompt,
            'pedagogies_json' => $request->pedagogies ?? [],
            'conducted_date' => now()->toDateString(),
        ]);

        return redirect()->route('hpc.activities')->with('success', 'HPC Activity created successfully.');
    }

    /**
     * Mark Activity Grid for Students
     */
    public function markActivity($id)
    {
        $activity = HpcActivity::with(['schoolClass', 'framework'])->findOrFail($id);
        
        // Fetch students in this class
        $students = Student::whereHas('enrollments', function ($q) use ($activity) {
            $q->where('class_id', $activity->school_class_id);
        })->get();

        if ($students->isEmpty()) {
            $students = Student::take(10)->get();
        }

        $existingMarks = HpcActivityMark::where('activity_id', $activity->id)
            ->get()
            ->keyBy('student_id');

        $rubricCriteria = ['Awareness', 'Sensitivity', 'Creativity'];

        return view('hpc.mark_activity', compact('activity', 'students', 'existingMarks', 'rubricCriteria'));
    }

    /**
     * Save Marks for Activity
     */
    public function saveActivityMark(Request $request, $id)
    {
        $activity = HpcActivity::findOrFail($id);
        $levels = $request->input('levels', []);
        $observations = $request->input('observations', []);
        $challenges = $request->input('challenges', []);

        foreach ($levels as $studentId => $criteriaLevels) {
            HpcActivityMark::updateOrCreate(
                ['activity_id' => $activity->id, 'student_id' => $studentId],
                [
                    'levels_json' => $criteriaLevels,
                    'observations' => $observations[$studentId] ?? null,
                    'challenges' => $challenges[$studentId] ?? null,
                ]
            );
        }

        return redirect()->route('hpc.activities')->with('success', 'Learner marks and rubrics saved successfully.');
    }

    /**
     * Delete Activity
     */
    public function destroyActivity($id)
    {
        $activity = HpcActivity::findOrFail($id);
        $activity->marks()->delete();
        $activity->delete();

        return redirect()->route('hpc.activities')->with('success', 'Activity removed successfully.');
    }

    /**
     * Cards Listing Hub
     */
    public function cards(Request $request)
    {
        $query = HpcCard::with(['student', 'schoolClass', 'framework']);

        if ($request->filled('school_class_id')) {
            $query->where('school_class_id', $request->school_class_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $term = $request->q;
            $query->whereHas('student', function ($sq) use ($term) {
                $sq->where('first_name', 'like', "%{$term}%")
                   ->orWhere('last_name', 'like', "%{$term}%")
                   ->orWhere('admission_no', 'like', "%{$term}%");
            });
        }

        $cards = $query->latest()->paginate(15)->withQueryString();
        $classes = SchoolClass::orderBy('name')->get();

        return view('hpc.cards', compact('cards', 'classes'));
    }

    /**
     * Create Cards Step
     */
    public function createCards(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $selectedClassId = $request->school_class_id;
        $term = $request->term ?? 'Full year';
        $students = collect();

        if ($selectedClassId) {
            $students = Student::whereHas('enrollments', function ($q) use ($selectedClassId) {
                $q->where('class_id', $selectedClassId);
            })->get();

            if ($students->isEmpty()) {
                $students = Student::take(15)->get();
            }
        }

        return view('hpc.create_cards', compact('classes', 'selectedClassId', 'term', 'students'));
    }

    /**
     * Store Bulk Cards for a Class
     */
    public function storeCards(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:classes,id',
            'student_ids' => 'required|array|min:1',
        ]);

        $classId = $request->school_class_id;
        $term = $request->term ?? 'Full year';
        $studentIds = $request->student_ids;

        // Find framework for this class
        $framework = HpcFramework::whereHas('classes', function ($q) use ($classId) {
            $q->where('classes.id', $classId);
        })->first() ?? HpcFramework::first();

        $session = AcademicSession::where('is_current', 1)->first() ?? AcademicSession::first();
        $sessionId = $session ? $session->id : 1;

        $createdCount = 0;

        foreach ($studentIds as $stuId) {
            $student = Student::find($stuId);
            if (!$student) continue;

            $card = HpcCard::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'school_class_id' => $classId,
                    'term' => $term,
                ],
                [
                    'academic_session_id' => $sessionId,
                    'framework_id' => $framework ? $framework->id : null,
                    'stage' => $framework ? $framework->stage : 'Foundational',
                    'status' => 'draft',
                    'general_info_json' => [
                        'school_name' => 'YUG INTERNATIONAL SCHOOL',
                        'school_address' => 'Bhatagaon, Raipur, Chhattisgarh 490021',
                        'village_ward' => 'Ward 42',
                        'brc' => 'Raipur Urban BRC',
                        'crc' => 'Bhatagaon CRC',
                        'district' => 'Raipur',
                        'state' => 'Chhattisgarh',
                        'pin_code' => '490021',
                        'udise_code' => '22100405102',
                        'teacher_code' => 'TCH-2024-99',
                        'apaar_id' => '9845-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                        'blood_group' => $student->blood_group ?? 'B+',
                        'height_cm' => 115,
                        'weight_kg' => 20,
                    ],
                    'attendance_json' => [
                        'total_working' => 220,
                        'total_present' => 210,
                        'percentage' => 95.4,
                    ],
                ]
            );

            if ($card->wasRecentlyCreated) {
                $createdCount++;
            }
        }

        return redirect()->route('hpc.cards')->with('success', "{$createdCount} Progress Card(s) generated successfully.");
    }

    /**
     * View/Edit Individual Card
     */
    public function showCard(Request $request, $id)
    {
        $card = HpcCard::with(['student', 'schoolClass', 'framework'])->findOrFail($id);
        $activePart = $request->query('part', 'A1');
        $setting = HpcCardSetting::first() ?? new HpcCardSetting();

        return view('hpc.show_card', compact('card', 'activePart', 'setting'));
    }

    /**
     * Update Card Section Data
     */
    public function updateCardSection(Request $request, $id, $section)
    {
        $card = HpcCard::findOrFail($id);

        if ($section === '87' || $section === 'A1') {
            $general = $card->general_info_json ?? [];
            if ($request->has('fields')) {
                foreach ($request->input('fields') as $key => $val) {
                    $general[$key] = $val;
                }
            }
            $card->general_info_json = $general;
        } elseif ($section === 'A2') {
            $card->self_assessment_json = array_merge($card->self_assessment_json ?? [], $request->input('self_assessment', []));
        } elseif ($section === 'A3') {
            $card->peer_assessment_json = array_merge($card->peer_assessment_json ?? [], $request->input('peer_assessment', []));
        } elseif ($section === 'A4') {
            $card->parent_feedback_json = array_merge($card->parent_feedback_json ?? [], $request->input('parent_feedback', []));
        } elseif ($section === 'B') {
            $card->domain_scores_json = array_merge($card->domain_scores_json ?? [], $request->input('domain_scores', []));
        } elseif ($section === 'C') {
            $card->teacher_summary_json = array_merge($card->teacher_summary_json ?? [], $request->input('teacher_summary', []));
        }

        $card->save();

        return redirect()->route('hpc.cards.show', ['id' => $card->id, 'part' => $request->query('part', $section)])
            ->with('success', "Card Section {$section} saved successfully.");
    }

    /**
     * Review Card (Teacher submitted -> Reviewed)
     */
    public function reviewCard($id)
    {
        $card = HpcCard::findOrFail($id);
        $card->status = 'reviewed';
        $card->reviewed_at = now();
        $card->save();

        return redirect()->back()->with('success', 'Progress Card marked as reviewed.');
    }

    /**
     * Return Card to Teacher with Reason
     */
    public function returnCard(Request $request, $id)
    {
        $card = HpcCard::findOrFail($id);
        $card->status = 'draft';
        $card->return_reason = $request->input('reason', 'Requires revisions before final review.');
        $card->save();

        return redirect()->back()->with('info', 'Progress Card returned to teacher with revision notes.');
    }

    /**
     * Publish Card
     */
    public function publishCard($id)
    {
        $card = HpcCard::findOrFail($id);
        $card->status = 'published';
        $card->published_at = now();
        $card->save();

        return redirect()->back()->with('success', 'Progress Card published and ready to hand out.');
    }

    /**
     * Official Printable Preview View
     */
    public function previewCard($id)
    {
        $card = HpcCard::with(['student', 'schoolClass', 'framework'])->findOrFail($id);
        $setting = HpcCardSetting::first() ?? new HpcCardSetting();

        return view('hpc.preview_card', compact('card', 'setting'));
    }

    /**
     * Colour Illustrated Keepsake View for Parents
     */
    public function colourCard(Request $request, $id)
    {
        $card = HpcCard::with(['student', 'schoolClass', 'framework'])->findOrFail($id);
        $setting = HpcCardSetting::first() ?? new HpcCardSetting();
        $theme = $request->query('theme', $setting->theme ?? 'playful');

        return view('hpc.colour_card', compact('card', 'setting', 'theme'));
    }

    /**
     * Card Editions / Frameworks
     */
    public function frameworks()
    {
        $frameworks = HpcFramework::with(['classes'])->get();
        return view('hpc.frameworks', compact('frameworks'));
    }

    /**
     * Framework Detail & Class Assignment
     */
    public function showFramework($id)
    {
        $framework = HpcFramework::with(['classes'])->findOrFail($id);
        $classes = SchoolClass::orderBy('name')->get();
        $assignedClassIds = $framework->classes->pluck('id')->toArray();

        return view('hpc.show_framework', compact('framework', 'classes', 'assignedClassIds'));
    }

    /**
     * Assign Classes to Framework
     */
    public function saveFrameworkClasses(Request $request, $id)
    {
        $framework = HpcFramework::findOrFail($id);
        $classIds = $request->input('school_class_ids', []);

        // Sync to this framework (moves from any other framework)
        $framework->classes()->sync($classIds);

        return redirect()->route('hpc.frameworks.show', $framework->id)
            ->with('success', 'Classes assigned to this card edition successfully.');
    }

    /**
     * Toggle Framework Active/Inactive
     */
    public function toggleFramework($id)
    {
        $framework = HpcFramework::findOrFail($id);
        $framework->is_active = !$framework->is_active;
        $framework->save();

        return redirect()->back()->with('success', 'Framework status updated.');
    }

    /**
     * Card Appearance & Customizer
     */
    public function cardAppearance()
    {
        $setting = HpcCardSetting::first() ?? HpcCardSetting::create([
            'theme' => 'playful',
            'edition_label' => 'PARAKH NEP-2020 Validated',
            'show_radar_chart' => true,
            'show_monthly_attendance' => true,
            'show_health_metrics' => true,
            'principal_name' => 'Dr. Rajesh Sharma',
        ]);

        return view('hpc.card_appearance', compact('setting'));
    }

    /**
     * Update Appearance Settings
     */
    public function updateCardAppearance(Request $request)
    {
        $setting = HpcCardSetting::first() ?? new HpcCardSetting();

        if ($request->filled('edition_label')) {
            $setting->edition_label = $request->edition_label;
        }

        if ($request->filled('theme')) {
            $setting->theme = $request->theme;
        }

        if ($request->has('show_radar_chart')) {
            $setting->show_radar_chart = (bool) $request->show_radar_chart;
        }

        if ($request->has('show_monthly_attendance')) {
            $setting->show_monthly_attendance = (bool) $request->show_monthly_attendance;
        }

        if ($request->has('show_health_metrics')) {
            $setting->show_health_metrics = (bool) $request->show_health_metrics;
        }

        if ($request->hasFile('emblem')) {
            $path = $request->file('emblem')->store('branding/hpc_emblems', 'public');
            $setting->emblem_path = $path;
        }

        $setting->save();

        return redirect()->route('hpc.card-appearance')->with('success', 'Appearance configuration saved successfully.');
    }

    /**
     * Delete Emblem
     */
    public function deleteCardEmblem(Request $request)
    {
        $setting = HpcCardSetting::first();
        if ($setting && $setting->emblem_path) {
            Storage::disk('public')->delete($setting->emblem_path);
            $setting->emblem_path = null;
            $setting->save();
        }

        return redirect()->route('hpc.card-appearance')->with('success', 'Emblem removed successfully.');
    }
}

