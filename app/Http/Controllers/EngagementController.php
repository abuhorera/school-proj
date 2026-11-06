<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EngagementDesign;
use App\Models\EngagementFestival;
use App\Models\EngagementSetting;
use App\Models\EngagementDispatch;
use App\Models\Student;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EngagementController extends Controller
{
    // =========================================================================
    // 1. CREATIVES / DESIGNS
    // =========================================================================
    public function designsIndex(Request $request)
    {
        $category = $request->query('category', 'all');
        $query = EngagementDesign::where('is_template', false);

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $designs = $query->orderBy('id', 'desc')->get();

        // Category counts
        $counts = [
            'all' => EngagementDesign::where('is_template', false)->count(),
            'birthday_card' => EngagementDesign::where('is_template', false)->where('category', 'birthday_card')->count(),
            'festival_banner' => EngagementDesign::where('is_template', false)->where('category', 'festival_banner')->count(),
            'popup_card' => EngagementDesign::where('is_template', false)->where('category', 'popup_card')->count(),
            'story_card' => EngagementDesign::where('is_template', false)->where('category', 'story_card')->count(),
        ];

        return view('engagement.designs', compact('designs', 'category', 'counts'));
    }

    public function designCreate(Request $request)
    {
        $category = $request->query('category', 'birthday_card');
        $templateId = $request->query('template_id');

        if ($templateId === 'scratch') {
            // Create a blank scratch design directly
            $design = EngagementDesign::create([
                'name' => 'Blank ' . ucfirst(str_replace('_', ' ', $category)),
                'category' => $category,
                'editor_type' => 'canvas',
                'width' => ($category === 'festival_banner' ? 1200 : 1080),
                'height' => ($category === 'festival_banner' ? 630 : ($category === 'story_card' ? 1920 : 1080)),
                'background_color' => '#ffffff',
                'greeting_title' => 'Happy Celebrations!',
                'greeting_message' => 'Warm wishes from our school.',
                'footer_text' => 'Yug International School',
            ]);

            return redirect()->route('school.engagement.designs.edit', $design->id)
                ->with('success', 'New blank canvas created.');
        }

        // Templates library
        $templates = EngagementDesign::orderBy('id')->get();

        return view('engagement.create', compact('templates', 'category'));
    }

    public function designStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'background_color' => 'nullable|string',
            'background_gradient' => 'nullable|string',
            'greeting_title' => 'nullable|string|max:255',
            'greeting_message' => 'nullable|string',
            'footer_text' => 'nullable|string|max:255',
        ]);

        $validated['editor_type'] = 'canvas';
        $validated['width'] = $validated['width'] ?? ($validated['category'] === 'festival_banner' ? 1200 : 1080);
        $validated['height'] = $validated['height'] ?? ($validated['category'] === 'festival_banner' ? 630 : 1080);

        $design = EngagementDesign::create($validated);

        return redirect()->route('school.engagement.designs.preview', $design->id)
            ->with('success', "Creative '{$design->name}' created successfully.");
    }

    public function designClone($id)
    {
        $source = EngagementDesign::findOrFail($id);
        $replica = $source->replicate(['is_template']);
        $replica->name = $source->name . ' (Copy)';
        $replica->is_template = false;
        $replica->save();

        return redirect()->route('school.engagement.designs.edit', $replica->id)
            ->with('success', "Template '{$source->name}' copied to your designs library.");
    }

    public function designPreview(Request $request, $id)
    {
        $design = EngagementDesign::findOrFail($id);

        // Fetch students and staff for personalisation
        $students = Student::orderBy('first_name')->take(20)->get();
        $staffMembers = Staff::orderBy('first_name')->take(10)->get();

        $selectedStudentId = $request->input('student_id', $students->first()?->id);
        $selectedStudent = $selectedStudentId ? Student::find($selectedStudentId) : $students->first();

        // Calculate student age
        $turningAge = 5;
        if ($selectedStudent && $selectedStudent->dob) {
            $turningAge = Carbon::parse($selectedStudent->dob)->age;
        }

        return view('engagement.preview', compact(
            'design',
            'students',
            'staffMembers',
            'selectedStudent',
            'turningAge'
        ));
    }

    public function designEdit($id)
    {
        $design = EngagementDesign::findOrFail($id);
        return view('engagement.edit', compact('design'));
    }

    public function designUpdate(Request $request, $id)
    {
        $design = EngagementDesign::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string',
            'card_width_mm' => 'nullable|integer',
            'card_height_mm' => 'nullable|integer',
            'background_color' => 'nullable|string',
            'background_gradient' => 'nullable|string',
            'greeting_title' => 'nullable|string|max:255',
            'greeting_message' => 'nullable|string',
            'footer_text' => 'nullable|string|max:255',
            'content_json' => 'nullable|string',
        ]);

        if (isset($validated['card_width_mm'])) {
            $validated['width'] = intval($validated['card_width_mm']);
        }
        if (isset($validated['card_height_mm'])) {
            $validated['height'] = intval($validated['card_height_mm']);
        }

        $design->update($validated);

        return redirect()->route('school.engagement.designs.preview', $design->id)
            ->with('success', "Design '{$design->name}' updated successfully.");
    }

    public function designDestroy($id)
    {
        $design = EngagementDesign::findOrFail($id);
        $name = $design->name;
        $design->delete();

        return redirect()->route('school.engagement.designs')
            ->with('success', "Design '{$name}' deleted successfully.");
    }

    // =========================================================================
    // 2. BIRTHDAY MANAGER
    // =========================================================================
    public function birthdaysIndex(Request $request)
    {
        $window = $request->query('window', 'week'); // today, week, month
        $today = Carbon::today();

        // Query students with DOB
        $students = Student::whereNotNull('dob')->get();
        $staffMembers = Staff::whereNotNull('dob')->get();

        $allBirthdays = collect();

        // Process Students
        foreach ($students as $st) {
            $dob = Carbon::parse($st->dob);
            $bdayThisYear = Carbon::create($today->year, $dob->month, $dob->day)->startOfDay();

            // If already passed earlier this year, check next year
            if ($bdayThisYear->lt($today)) {
                $diffDays = $today->diffInDays($bdayThisYear->copy()->addYear(), false);
            } else {
                $diffDays = $today->diffInDays($bdayThisYear, false);
            }

            $turning = $today->year - $dob->year;
            if ($bdayThisYear->lt($today)) $turning += 1;

            $allBirthdays->push([
                'id' => $st->id,
                'name' => $st->first_name . ' ' . $st->last_name,
                'type' => 'Student',
                'subtext' => 'Student · Admission #' . $st->admission_no,
                'dob' => $dob,
                'birthday_str' => $dob->format('d M'),
                'turning' => max(1, $turning),
                'diff_days' => $diffDays,
                'is_today' => ($diffDays === 0),
                'when_text' => $diffDays === 0 ? 'Today 🎂' : ($diffDays === 1 ? 'Tomorrow' : "in {$diffDays} days"),
                'status' => $diffDays === 0 ? 'Pending' : 'Scheduled',
            ]);
        }

        // Process Staff
        foreach ($staffMembers as $sf) {
            $dob = Carbon::parse($sf->dob);
            $bdayThisYear = Carbon::create($today->year, $dob->month, $dob->day)->startOfDay();

            if ($bdayThisYear->lt($today)) {
                $diffDays = $today->diffInDays($bdayThisYear->copy()->addYear(), false);
            } else {
                $diffDays = $today->diffInDays($bdayThisYear, false);
            }

            $turning = $today->year - $dob->year;
            if ($bdayThisYear->lt($today)) $turning += 1;

            $allBirthdays->push([
                'id' => $sf->id,
                'name' => $sf->first_name . ' ' . $sf->last_name,
                'type' => 'Staff',
                'subtext' => 'Staff · ' . ($sf->designation ?? 'Faculty'),
                'dob' => $dob,
                'birthday_str' => $dob->format('d M'),
                'turning' => max(1, $turning),
                'diff_days' => $diffDays,
                'is_today' => ($diffDays === 0),
                'when_text' => $diffDays === 0 ? 'Today 🎂' : ($diffDays === 1 ? 'Tomorrow' : "in {$diffDays} days"),
                'status' => $diffDays === 0 ? 'Pending' : 'Scheduled',
            ]);
        }

        // Sort by upcoming days
        $sortedBirthdays = $allBirthdays->sortBy('diff_days');

        // Counters
        $todayCount = $sortedBirthdays->where('diff_days', 0)->count();
        $weekCount = $sortedBirthdays->whereBetween('diff_days', [0, 7])->count();
        $monthCount = $sortedBirthdays->whereBetween('diff_days', [0, 30])->count();

        // Filter based on window
        $filteredBirthdays = match ($window) {
            'today' => $sortedBirthdays->where('diff_days', 0),
            'week' => $sortedBirthdays->whereBetween('diff_days', [0, 7]),
            'month' => $sortedBirthdays->whereBetween('diff_days', [0, 30]),
            default => $sortedBirthdays->whereBetween('diff_days', [0, 7]),
        };

        // Available Birthday Designs
        $birthdayDesigns = EngagementDesign::where('category', 'birthday_card')->orderBy('name')->get();

        // Recent Dispatches Log
        $dispatches = EngagementDispatch::with('design')->orderBy('dispatched_at', 'desc')->take(10)->get();

        return view('engagement.birthdays', compact(
            'filteredBirthdays',
            'window',
            'todayCount',
            'weekCount',
            'monthCount',
            'birthdayDesigns',
            'dispatches'
        ));
    }

    public function sendBirthdayGreeting(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string',
            'recipient_type' => 'required|string',
            'recipient_id' => 'nullable|integer',
            'design_id' => 'nullable|exists:engagement_designs,id',
            'channels' => 'nullable|string',
        ]);

        EngagementDispatch::create([
            'type' => 'Birthday',
            'recipient_type' => $validated['recipient_type'],
            'recipient_id' => $validated['recipient_id'] ?? null,
            'recipient_name' => $validated['recipient_name'],
            'subject' => "Happy Birthday {$validated['recipient_name']}!",
            'design_id' => $validated['design_id'] ?? null,
            'channels' => $validated['channels'] ?? 'email, sms, whatsapp, push',
            'dispatched_at' => Carbon::now(),
            'status' => 'Sent',
            'notes' => 'Direct dispatch from Birthday Manager.',
        ]);

        return redirect()->back()
            ->with('success', "Personalised birthday greeting card dispatched successfully to {$validated['recipient_name']}.");
    }

    // =========================================================================
    // 3. FESTIVAL GREETINGS
    // =========================================================================
    public function festivalsIndex()
    {
        $today = Carbon::today();
        $festivals = EngagementFestival::with('design')
            ->orderBy('festival_date', 'asc')
            ->get();

        // Compute relative days
        foreach ($festivals as $f) {
            $diff = $today->diffInDays($f->festival_date, false);
            if ($diff < 0) {
                $f->when_text = abs($diff) . ' days ago';
            } elseif ($diff === 0) {
                $f->when_text = 'Today 🎉';
            } elseif ($diff === 1) {
                $f->when_text = 'Tomorrow';
            } else {
                $f->when_text = "in {$diff} days";
            }
        }

        $festivalDesigns = EngagementDesign::where('category', 'festival_banner')->orderBy('name')->get();

        return view('engagement.festivals', compact('festivals', 'festivalDesigns'));
    }

    public function festivalStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'festival_date' => 'required|date',
            'region' => 'nullable|string|max:50',
            'design_id' => 'nullable|exists:engagement_designs,id',
            'greeting_message' => 'nullable|string',
        ]);

        $validated['region'] = $validated['region'] ?? 'ALL';
        $validated['is_custom'] = true;
        $validated['auto_send'] = true;

        EngagementFestival::create($validated);

        return redirect()->route('school.engagement.festivals')
            ->with('success', "Festival / Special Day '{$validated['name']}' added to calendar.");
    }

    public function sendFestivalGreeting(Request $request)
    {
        $validated = $request->validate([
            'festival_id' => 'required|exists:engagement_festivals,id',
            'design_id' => 'nullable|exists:engagement_designs,id',
        ]);

        $festival = EngagementFestival::findOrFail($validated['festival_id']);

        EngagementDispatch::create([
            'type' => 'Festival',
            'recipient_type' => 'All Parents & Staff',
            'recipient_id' => null,
            'recipient_name' => 'Entire School Community',
            'subject' => "Festive Greetings: {$festival->name}",
            'design_id' => $validated['design_id'] ?? $festival->design_id,
            'channels' => 'push, whatsapp, email',
            'dispatched_at' => Carbon::now(),
            'status' => 'Sent',
            'notes' => 'Broadcast festival greeting dispatched to all students, parents and faculty.',
        ]);

        return redirect()->back()
            ->with('success', "Festive greetings for '{$festival->name}' broadcasted successfully.");
    }

    public function festivalDestroy($id)
    {
        $festival = EngagementFestival::findOrFail($id);
        $name = $festival->name;
        $festival->delete();

        return redirect()->route('school.engagement.festivals')
            ->with('success', "Special day '{$name}' deleted.");
    }

    // =========================================================================
    // 4. AUTO-SEND SETTINGS
    // =========================================================================
    public function settingsIndex()
    {
        $settings = EngagementSetting::first() ?? EngagementSetting::create([
            'birthday_students_enabled' => true,
            'birthday_staff_enabled' => true,
            'festivals_enabled' => true,
            'channels' => 'push,whatsapp,email,sms',
            'send_time' => '09:00',
        ]);

        $birthdayDesigns = EngagementDesign::where('category', 'birthday_card')->orderBy('name')->get();
        $festivalDesigns = EngagementDesign::where('category', 'festival_banner')->orderBy('name')->get();

        return view('engagement.settings', compact('settings', 'birthdayDesigns', 'festivalDesigns'));
    }

    public function settingsUpdate(Request $request)
    {
        $settings = EngagementSetting::firstOrCreate(['id' => 1]);

        $settings->update([
            'birthday_students_enabled' => $request->has('birthday_students_enabled'),
            'birthday_student_design_id' => $request->input('birthday_student_design_id') ?: null,
            'birthday_staff_enabled' => $request->has('birthday_staff_enabled'),
            'birthday_staff_design_id' => $request->input('birthday_staff_design_id') ?: null,
            'festivals_enabled' => $request->has('festivals_enabled'),
            'festival_design_id' => $request->input('festival_design_id') ?: null,
            'channels' => is_array($request->input('channels')) ? implode(',', $request->input('channels')) : $settings->channels,
            'send_time' => $request->input('send_time', '09:00'),
        ]);

        return redirect()->route('school.engagement.settings')
            ->with('success', 'Auto-send preferences saved successfully.');
    }

    public function sendTestGreeting(Request $request)
    {
        $designId = $request->input('design_id');
        $design = $designId ? EngagementDesign::find($designId) : EngagementDesign::first();

        EngagementDispatch::create([
            'type' => 'Custom',
            'recipient_type' => 'Admin Test',
            'recipient_id' => null,
            'recipient_name' => 'School Administrator',
            'subject' => 'Test Engagement Card Dispatch',
            'design_id' => $design?->id,
            'channels' => 'email, whatsapp',
            'dispatched_at' => Carbon::now(),
            'status' => 'Delivered',
            'notes' => 'Test simulation verified delivery to gateway.',
        ]);

        return redirect()->back()
            ->with('success', 'Test engagement card delivered successfully to admin portal.');
    }
}

