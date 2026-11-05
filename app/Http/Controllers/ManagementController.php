<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ManagementController extends Controller
{
    // Central Management Hub
    public function index()
    {
        $school = School::first();

        // High-level counts for all 21 management modules
        $counts = [
            'enquiries' => DB::table('admission_enquiries')->count(),
            'visitors' => DB::table('visitors')->where('status', 'inside')->count(),
            'complaints' => DB::table('complaints')->where('status', '!=', 'Resolved')->count(),
            'incomes' => DB::table('incomes')->sum('amount'),
            'bank_balance' => DB::table('bank_accounts')->sum('current_balance'),
            'buildings' => DB::table('buildings')->count(),
            'rooms' => DB::table('classroom_rooms')->count(),
            'occupied_rooms' => DB::table('classroom_rooms')->where('status', 'Occupied')->count(),
            'ptm_scheduled' => DB::table('ptm_meetings')->where('status', 'Scheduled')->count(),
            'lesson_plans' => DB::table('lesson_plans')->count(),
            'compliance_items' => DB::table('compliance_items')->count(),
        ];

        return view('management.index', compact('school', 'counts'));
    }

    // 1. Front Office & Lead Management
    public function frontOffice()
    {
        $school = School::first();
        $enquiries = DB::table('admission_enquiries')->orderBy('id', 'desc')->get();
        $visitors = DB::table('visitors')->orderBy('id', 'desc')->get();
        $complaints = DB::table('complaints')->orderBy('id', 'desc')->get();

        return view('management.front_office', compact('school', 'enquiries', 'visitors', 'complaints'));
    }

    // 2. Accounts (Incomes, Expenses & Bank Accounts)
    public function accounts()
    {
        $school = School::first();
        $incomeHeads = DB::table('income_heads')->get();
        $expenseHeads = DB::table('expense_heads')->get();
        $incomes = DB::table('incomes')->join('income_heads', 'incomes.income_head_id', '=', 'income_heads.id')
            ->select('incomes.*', 'income_heads.name as head_name')
            ->orderBy('incomes.date', 'desc')->get();
        $bankAccounts = DB::table('bank_accounts')->get();

        $totalIncome = DB::table('incomes')->sum('amount');
        $totalBank = DB::table('bank_accounts')->sum('current_balance');

        return view('management.accounts', compact('school', 'incomeHeads', 'expenseHeads', 'incomes', 'bankAccounts', 'totalIncome', 'totalBank'));
    }

    // 3. Classrooms & Infrastructure
    public function classrooms()
    {
        $school = School::first();
        $buildings = DB::table('buildings')->get();
        $rooms = DB::table('classroom_rooms')
            ->join('buildings', 'classroom_rooms.building_id', '=', 'buildings.id')
            ->select('classroom_rooms.*', 'buildings.name as building_name', 'buildings.code as building_code')
            ->orderBy('classroom_rooms.room_number')
            ->get();

        $totalCapacity = $rooms->sum('capacity');
        $availableRooms = $rooms->where('status', 'Available')->count();
        $occupiedRooms = $rooms->where('status', 'Occupied')->count();

        return view('management.classrooms', compact('school', 'buildings', 'rooms', 'totalCapacity', 'availableRooms', 'occupiedRooms'));
    }

    // 4. PTM & Lesson Planner
    public function ptmLessonPlanner()
    {
        $school = School::first();
        $ptmMeetings = DB::table('ptm_meetings')
            ->leftJoin('classes', 'ptm_meetings.class_id', '=', 'classes.id')
            ->select('ptm_meetings.*', 'classes.name as class_name')
            ->orderBy('meeting_date', 'asc')
            ->get();

        $lessonPlans = DB::table('lesson_plans')
            ->join('classes', 'lesson_plans.class_id', '=', 'classes.id')
            ->join('subjects', 'lesson_plans.subject_id', '=', 'subjects.id')
            ->leftJoin('staff', 'lesson_plans.teacher_id', '=', 'staff.id')
            ->select('lesson_plans.*', 'classes.name as class_name', 'subjects.name as subject_name', 'staff.first_name as teacher_first', 'staff.last_name as teacher_last')
            ->get();

        return view('management.ptm_lessons', compact('school', 'ptmMeetings', 'lessonPlans'));
    }

    // 5. Compliance, Governance & Government Vault
    public function compliance()
    {
        $school = School::first();
        $complianceItems = DB::table('compliance_items')->orderBy('expiry_date', 'asc')->get();

        return view('management.compliance', compact('school', 'complianceItems'));
    }

    // 6. QR Attendance & Biometric Devices
    public function attendanceDevices()
    {
        $school = School::first();
        return view('management.attendance_devices', compact('school'));
    }

    // 7. HPC (Holistic Progress Card) & CBC
    public function hpcCbc()
    {
        $school = School::first();
        return view('management.hpc_cbc', compact('school'));
    }
}

