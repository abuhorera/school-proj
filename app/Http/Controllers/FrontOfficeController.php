<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\AdmissionEnquiry;
use App\Models\Visitor;
use App\Models\Complaint;
use App\Models\PostalRecord;
use App\Models\GatePass;
use App\Models\CampusWorker;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FrontOfficeController extends Controller
{
    /**
     * 1. Front Office Dashboard
     */
    public function dashboard(Request $request)
    {
        $school = School::first() ?? new School(['name' => 'YUG INTERNATIONAL SCHOOL']);

        // KPIs
        $totalEnquiries = AdmissionEnquiry::count();
        $openEnquiries = AdmissionEnquiry::where('status', 'Open')->count();
        $todayVisitors = Visitor::whereDate('in_time', Carbon::today())->count();
        $activeVisitorsOnCampus = Visitor::whereNull('out_time')->count();
        $pendingComplaints = Complaint::where('status', '!=', 'Resolved')->count();
        $totalGatePassesToday = GatePass::whereDate('created_at', Carbon::today())->count();
        $workersOnCampus = CampusWorker::where('is_on_campus', 1)->count();
        $totalPostalRecords = PostalRecord::count();

        // Recent items
        $recentEnquiries = AdmissionEnquiry::with('schoolClass')->latest()->limit(6)->get();
        $recentVisitors = Visitor::latest()->limit(6)->get();
        $activeGatePasses = GatePass::with('student')->latest()->limit(5)->get();
        $recentComplaints = Complaint::latest()->limit(5)->get();

        $classes = SchoolClass::orderBy('numeric_level')->get();
        $staff = Staff::orderBy('first_name')->get();

        return view('front_office.dashboard', compact(
            'school',
            'totalEnquiries',
            'openEnquiries',
            'todayVisitors',
            'activeVisitorsOnCampus',
            'pendingComplaints',
            'totalGatePassesToday',
            'workersOnCampus',
            'totalPostalRecords',
            'recentEnquiries',
            'recentVisitors',
            'activeGatePasses',
            'recentComplaints',
            'classes',
            'staff'
        ));
    }

    /**
     * 2. Admission Enquiries
     */
    public function admissionEnquiries(Request $request)
    {
        $school = School::first();
        $statusFilter = $request->query('status');
        $classFilter = $request->query('class_id');
        $search = $request->query('search');

        $query = AdmissionEnquiry::with('schoolClass', 'assignedStaff');

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($classFilter) {
            $query->where('school_class_id', $classFilter);
        }
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('parent_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->latest()->get();
        $classes = SchoolClass::orderBy('numeric_level')->get();
        $staff = Staff::orderBy('first_name')->get();

        return view('front_office.admission_enquiries', compact(
            'school',
            'enquiries',
            'classes',
            'staff',
            'statusFilter',
            'classFilter',
            'search'
        ));
    }

    public function createAdmissionEnquiry(Request $request)
    {
        $school = School::first();
        $classes = SchoolClass::orderBy('numeric_level')->get();
        $staff = Staff::orderBy('first_name')->get();

        return view('front_office.admission_enquiries_create', compact('school', 'classes', 'staff'));
    }

    public function storeAdmissionEnquiry(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:150',
            'parent_name' => 'nullable|string|max:150',
            'phone' => 'required|string|max:30',
            'school_class_id' => 'nullable|integer',
            'enquiry_date' => 'required|date',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;
        $cls = SchoolClass::find($request->school_class_id);

        AdmissionEnquiry::create([
            'school_id' => $schoolId,
            'name' => $request->student_name,
            'parent_name' => $request->parent_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'class_applying_for' => $cls ? $cls->name : $request->class_applying_for,
            'school_class_id' => $request->school_class_id,
            'source' => $request->admission_source ?? 'Walk In',
            'status' => $request->status ?? 'Open',
            'enquiry_date' => $request->enquiry_date,
            'next_follow_up' => $request->follow_up_date,
            'assigned_to' => $request->assigned_to,
            'remarks' => $request->notes,
        ]);

        return redirect()->route('front-office.admission-enquiries')->with('success', "Admission enquiry for '{$request->student_name}' logged successfully.");
    }

    public function deleteAdmissionEnquiry($id)
    {
        $enq = AdmissionEnquiry::findOrFail($id);
        $name = $enq->name;
        $enq->delete();

        return redirect()->route('front-office.admission-enquiries')->with('success', "Enquiry for '{$name}' deleted successfully.");
    }

    /**
     * 3. Visitor Book (Logs)
     */
    public function visitorLogs(Request $request)
    {
        $school = School::first();
        $search = $request->query('search');

        $query = Visitor::query();
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('person_to_meet', 'like', "%{$search}%");
            });
        }

        $visitors = $query->latest()->get();
        $staff = Staff::orderBy('first_name')->get();

        return view('front_office.visitor_logs', compact('school', 'visitors', 'staff', 'search'));
    }

    public function createVisitorLog(Request $request)
    {
        $school = School::first();
        $staff = Staff::orderBy('first_name')->get();

        return view('front_office.visitor_logs_create', compact('school', 'staff'));
    }

    public function storeVisitorLog(Request $request)
    {
        $request->validate([
            'visitor_name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'purpose' => 'required|string|max:200',
            'person_to_meet' => 'required|string|max:150',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;

        Visitor::create([
            'school_id' => $schoolId,
            'name' => $request->visitor_name,
            'phone' => $request->phone,
            'purpose' => $request->purpose,
            'person_to_meet' => $request->person_to_meet,
            'id_card_number' => $request->id_card_number ?? ('V-' . rand(100, 999)),
            'in_time' => Carbon::now(),
            'status' => 'checked_in',
        ]);

        return redirect()->route('front-office.visitor-logs')->with('success', "Visitor '{$request->visitor_name}' checked in successfully.");
    }

    public function markVisitorOut($id)
    {
        $visitor = Visitor::findOrFail($id);
        $visitor->update([
            'out_time' => Carbon::now(),
            'status' => 'checked_out',
        ]);

        return redirect()->back()->with('success', "Visitor '{$visitor->name}' marked out at " . Carbon::now()->format('h:i A') . ".");
    }

    public function deleteVisitorLog($id)
    {
        $visitor = Visitor::findOrFail($id);
        $name = $visitor->name;
        $visitor->delete();

        return redirect()->route('front-office.visitor-logs')->with('success', "Visitor record '{$name}' removed.");
    }

    /**
     * 4. Complaint Management
     */
    public function complaints(Request $request)
    {
        $school = School::first();
        $statusFilter = $request->query('status');
        $typeFilter = $request->query('type');

        $query = Complaint::with('assignedStaff');
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        if ($typeFilter) {
            $query->where('complaint_type', $typeFilter);
        }

        $complaints = $query->latest()->get();
        $staff = Staff::orderBy('first_name')->get();
        $types = ['Transport', 'Cafeteria', 'Academics', 'Facilities', 'Fees & Accounts', 'Behavior', 'General'];

        return view('front_office.complaints', compact('school', 'complaints', 'staff', 'types', 'statusFilter', 'typeFilter'));
    }

    public function createComplaint(Request $request)
    {
        $school = School::first();
        $staff = Staff::orderBy('first_name')->get();
        $types = ['Transport', 'Cafeteria', 'Academics', 'Facilities', 'Fees & Accounts', 'Behavior', 'General'];

        return view('front_office.complaints_create', compact('school', 'staff', 'types'));
    }

    public function storeComplaint(Request $request)
    {
        $request->validate([
            'complainant_name' => 'required|string|max:150',
            'phone' => 'required|string|max:30',
            'type' => 'required|string|max:100',
            'description' => 'required|string',
            'complaint_date' => 'required|date',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;

        Complaint::create([
            'school_id' => $schoolId,
            'complaint_by' => $request->complainant_name,
            'complaint_type' => $request->type,
            'phone' => $request->phone,
            'date' => $request->complaint_date,
            'description' => $request->description,
            'assigned_to' => $request->assigned_to,
            'status' => 'Open',
        ]);

        return redirect()->route('front-office.complaints')->with('success', "Complaint ticket logged for '{$request->complainant_name}'.");
    }

    public function resolveComplaint(Request $request, $id)
    {
        $c = Complaint::findOrFail($id);
        $c->update([
            'status' => $request->status ?? 'Resolved',
            'action_taken' => $request->action_taken ?? 'Resolved and closed by administration.',
        ]);

        return redirect()->back()->with('success', "Complaint #{$c->id} updated to '{$c->status}'.");
    }

    public function deleteComplaint($id)
    {
        $c = Complaint::findOrFail($id);
        $c->delete();

        return redirect()->route('front-office.complaints')->with('success', "Complaint #{$id} deleted.");
    }

    /**
     * 5. Postal Records
     */
    public function postalRecords(Request $request)
    {
        $school = School::first();
        $typeFilter = $request->query('type');

        $query = PostalRecord::query();
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        $records = $query->latest('date')->get();

        return view('front_office.postal_records', compact('school', 'records', 'typeFilter'));
    }

    public function createPostalRecord(Request $request)
    {
        $school = School::first();
        return view('front_office.postal_records_create', compact('school'));
    }

    public function storePostalRecord(Request $request)
    {
        $request->validate([
            'type' => 'required|in:Dispatch,Receive',
            'date' => 'required|date',
            'from_name' => 'required|string|max:150',
            'to_name' => 'required|string|max:150',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;

        PostalRecord::create([
            'school_id' => $schoolId,
            'type' => $request->type,
            'date' => $request->date,
            'from_name' => $request->from_name,
            'to_name' => $request->to_name,
            'to_address' => $request->to_address,
            'reference_number' => $request->reference_number ?? ('REF-' . date('Ymd') . '-' . rand(10, 99)),
            'notes' => $request->notes,
        ]);

        return redirect()->route('front-office.postal-records')->with('success', "Postal record logged successfully.");
    }

    public function deletePostalRecord($id)
    {
        $rec = PostalRecord::findOrFail($id);
        $rec->delete();

        return redirect()->route('front-office.postal-records')->with('success', "Postal record removed.");
    }

    /**
     * 6. Gate Passes
     */
    public function gatePasses(Request $request)
    {
        $school = School::first();
        $statusFilter = $request->query('status');

        $query = GatePass::with('student');
        if ($statusFilter && $statusFilter != 'all') {
            $query->where('status', $statusFilter);
        }

        $passes = $query->latest()->get();
        $students = Student::orderBy('first_name')->get();

        return view('front_office.gate_passes', compact('school', 'passes', 'students', 'statusFilter'));
    }

    public function createGatePass(Request $request)
    {
        $school = School::first();
        $students = Student::orderBy('first_name')->get();

        return view('front_office.gate_passes_create', compact('school', 'students'));
    }

    public function storeGatePass(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'escort_name' => 'required|string|max:150',
            'escort_relation' => 'required|string|max:50',
            'escort_phone' => 'required|string|max:30',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;
        $student = Student::find($request->student_id);

        $passNo = 'GP-' . date('Y') . '-' . rand(1000, 9999);

        GatePass::create([
            'school_id' => $schoolId,
            'pass_number' => $passNo,
            'student_id' => $request->student_id,
            'visitor_name' => $student ? ($student->first_name . ' ' . $student->last_name) : 'Student',
            'phone' => $request->escort_phone,
            'purpose' => $request->reason_id ?? 'Official Permission',
            'reason_text' => $request->reason_text ?? 'Authorized early departure by guardian.',
            'escort_name' => $request->escort_name,
            'escort_relation' => $request->escort_relation,
            'escort_phone' => $request->escort_phone,
            'escort_id_proof' => $request->escort_id_proof ?? 'Verified ID',
            'gate_name' => $request->gate_name ?? 'Main Gate 1',
            'is_return_expected' => $request->boolean('is_return_expected'),
            'status' => 'approved',
            'notes' => $request->notes,
            'in_time' => Carbon::now(),
        ]);

        return redirect()->route('front-office.gate-passes')->with('success', "Gate pass '{$passNo}' issued successfully.");
    }

    public function updateGatePassStatus(Request $request, $id)
    {
        $pass = GatePass::findOrFail($id);
        $newStatus = $request->status;

        $updates = ['status' => $newStatus];
        if ($newStatus == 'out') {
            $updates['out_time'] = Carbon::now();
        } elseif ($newStatus == 'returned') {
            $updates['in_time'] = Carbon::now();
        }

        $pass->update($updates);

        return redirect()->back()->with('success', "Gate pass #{$pass->pass_number} marked as '{$newStatus}'.");
    }

    public function deleteGatePass($id)
    {
        $pass = GatePass::findOrFail($id);
        $num = $pass->pass_number;
        $pass->delete();

        return redirect()->route('front-office.gate-passes')->with('success', "Gate pass '{$num}' deleted.");
    }

    /**
     * 7. Gate Control Terminal
     */
    public function gateTerminal(Request $request)
    {
        $school = School::first();
        $queryPass = $request->query('pass');
        $foundPass = null;

        if ($queryPass) {
            $foundPass = GatePass::with('student')
                ->where('pass_number', 'like', "%{$queryPass}%")
                ->orWhere('id', $queryPass)
                ->first();
        }

        $todayPasses = GatePass::with('student')->whereDate('created_at', Carbon::today())->latest()->limit(10)->get();

        return view('front_office.gate_terminal', compact('school', 'queryPass', 'foundPass', 'todayPasses'));
    }

    /**
     * 8. Campus Workers
     */
    public function campusWorkers(Request $request)
    {
        $school = School::first();
        $filter = $request->query('filter');

        $query = CampusWorker::query();
        if ($filter == 'on_campus') {
            $query->where('is_on_campus', 1);
        } elseif ($filter == 'unverified') {
            $query->where('police_verified', 0);
        }

        $workers = $query->orderBy('name')->get();

        return view('front_office.campus_workers', compact('school', 'workers', 'filter'));
    }

    public function createCampusWorker(Request $request)
    {
        $school = School::first();
        return view('front_office.campus_workers_create', compact('school'));
    }

    public function storeCampusWorker(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category' => 'required|string|max:100',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
        ]);

        $school = School::first();
        $schoolId = $school ? $school->id : 1;
        $badge = 'CW-' . rand(200, 999);

        CampusWorker::create([
            'school_id' => $schoolId,
            'badge_number' => $badge,
            'name' => $request->name,
            'phone' => $request->phone,
            'category' => $request->category,
            'agency_name' => $request->agency_name,
            'agency_contact' => $request->agency_contact,
            'id_proof' => $request->id_proof,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'police_verified' => $request->boolean('police_verified'),
            'police_verified_on' => $request->police_verified_on,
            'is_on_campus' => true,
            'notes' => $request->notes,
        ]);

        return redirect()->route('front-office.campus-workers')->with('success', "Campus worker '{$request->name}' registered with Badge #{$badge}.");
    }

    public function toggleWorkerCampusState($id)
    {
        $w = CampusWorker::findOrFail($id);
        $w->is_on_campus = !$w->is_on_campus;
        $w->save();

        $stateText = $w->is_on_campus ? 'CHECKED IN (On Campus)' : 'CHECKED OUT';
        return redirect()->back()->with('success', "Worker '{$w->name}' marked as {$stateText}.");
    }

    public function deleteCampusWorker($id)
    {
        $w = CampusWorker::findOrFail($id);
        $name = $w->name;
        $w->delete();

        return redirect()->route('front-office.campus-workers')->with('success', "Worker record '{$name}' deleted.");
    }
}

