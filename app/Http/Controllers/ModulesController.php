<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Notice;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class ModulesController extends Controller
{
    // =========================================================================
    // 1. LIVE CLASSES
    // =========================================================================
    public function liveClasses(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'manage');
        $classes = SchoolClass::all();
        $subjects = Subject::all();
        $sessions = DB::table('live_class_sessions')->latest()->get();

        return view('modules.live_classes', compact('school', 'tab', 'classes', 'subjects', 'sessions'));
    }

    // =========================================================================
    // 2. APPS CENTER
    // =========================================================================
    public function appsCenter()
    {
        $school = School::first();
        return view('modules.apps_center', compact('school'));
    }

    // =========================================================================
    // 3. STUDY CENTER
    // =========================================================================
    public function studyCenter(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $classes = SchoolClass::all();
        $subjects = Subject::all();

        return view('modules.study_center', compact('school', 'tab', 'classes', 'subjects'));
    }

    // =========================================================================
    // 4. CERTIFICATES
    // =========================================================================
    public function certificates(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'templates');
        $templates = DB::table('certificate_templates')->get();
        $students = Student::with('schoolClass')->take(10)->get();

        return view('modules.certificates', compact('school', 'tab', 'templates', 'students'));
    }

    // =========================================================================
    // 5. ID CARDS
    // =========================================================================
    public function idCards(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'students');
        $templates = DB::table('id_card_templates')->get();
        $students = Student::with('schoolClass')->take(10)->get();
        $staff = Staff::take(10)->get();

        return view('modules.id_cards', compact('school', 'tab', 'templates', 'students', 'staff'));
    }

    // =========================================================================
    // 6. COMMUNICATE
    // =========================================================================
    public function communicate(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'notices');
        $notices = Notice::latest()->get();
        $events = Event::latest()->get();

        return view('modules.communicate', compact('school', 'tab', 'notices', 'events'));
    }

    // =========================================================================
    // 7. LIBRARY
    // =========================================================================
    public function library(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $books = DB::table('library_books')
            ->leftJoin('book_categories', 'library_books.category_id', '=', 'book_categories.id')
            ->select('library_books.*', 'book_categories.name as category_name')
            ->get();
        $categories = DB::table('book_categories')->get();
        $issues = DB::table('book_issues')
            ->join('library_books', 'book_issues.book_id', '=', 'library_books.id')
            ->select('book_issues.*', 'library_books.title as book_title')
            ->latest('book_issues.created_at')
            ->get();

        return view('modules.library', compact('school', 'tab', 'books', 'categories', 'issues'));
    }

    // =========================================================================
    // 8. INVENTORY & STOCK
    // =========================================================================
    public function inventory(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $items = DB::table('inventory_items')
            ->leftJoin('inventory_categories', 'inventory_items.category_id', '=', 'inventory_categories.id')
            ->select('inventory_items.*', 'inventory_categories.name as category_name')
            ->get();
        $categories = DB::table('inventory_categories')->get();
        $suppliers = DB::table('inventory_suppliers')->get();
        $sales = DB::table('inventory_sales')->latest()->get();

        return view('modules.inventory', compact('school', 'tab', 'items', 'categories', 'suppliers', 'sales'));
    }

    // =========================================================================
    // 9. TRANSPORT & FLEET GPS
    // =========================================================================
    public function transport(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $vehicles = DB::table('transport_vehicles')->get();
        $routes = DB::table('transport_routes')
            ->leftJoin('transport_vehicles', 'transport_routes.vehicle_id', '=', 'transport_vehicles.id')
            ->select('transport_routes.*', 'transport_vehicles.vehicle_no')
            ->get();

        return view('modules.transport', compact('school', 'tab', 'vehicles', 'routes'));
    }

    // =========================================================================
    // 10. HOSTEL MANAGEMENT
    // =========================================================================
    public function hostel(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $blocks = DB::table('hostel_blocks')->get();
        $rooms = DB::table('hostel_rooms')
            ->join('hostel_blocks', 'hostel_rooms.block_id', '=', 'hostel_blocks.id')
            ->select('hostel_rooms.*', 'hostel_blocks.name as block_name')
            ->get();
        $allocations = DB::table('hostel_allocations')
            ->join('hostel_rooms', 'hostel_allocations.room_id', '=', 'hostel_rooms.id')
            ->select('hostel_allocations.*', 'hostel_rooms.room_number')
            ->get();

        return view('modules.hostel', compact('school', 'tab', 'blocks', 'rooms', 'allocations'));
    }

    // =========================================================================
    // 11. HELP CENTER & KNOWLEDGE BASE
    // =========================================================================
    public function helpCenter(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'categories');
        $categories = DB::table('kb_categories')->get();
        $articles = DB::table('kb_articles')
            ->join('kb_categories', 'kb_articles.category_id', '=', 'kb_categories.id')
            ->select('kb_articles.*', 'kb_categories.name as category_name')
            ->get();

        return view('modules.help_center', compact('school', 'tab', 'categories', 'articles'));
    }

    // =========================================================================
    // 12. ASSET MANAGEMENT
    // =========================================================================
    public function assets(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $assets = DB::table('asset_items')->get();
        $totalAcquisition = $assets->sum('cost_price');
        $currentBookValue = $assets->sum('current_value');

        return view('modules.assets', compact('school', 'tab', 'assets', 'totalAcquisition', 'currentBookValue'));
    }

    // =========================================================================
    // 13. ENGAGEMENT & CREATIVES
    // =========================================================================
    public function engagement(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'designs');
        $studentsToday = Student::take(3)->get();
        $staffToday = Staff::take(2)->get();

        return view('modules.engagement', compact('school', 'tab', 'studentsToday', 'staffToday'));
    }

    // =========================================================================
    // 14. CCTV LIVE WALL
    // =========================================================================
    public function cctv(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'wall');
        $cameras = DB::table('cctv_cameras')->get();

        return view('modules.cctv', compact('school', 'tab', 'cameras'));
    }

    // =========================================================================
    // 15. ALUMNI
    // =========================================================================
    public function alumni(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $members = DB::table('alumni_members')->latest()->get();
        $events = DB::table('alumni_events')->latest()->get();

        return view('modules.alumni', compact('school', 'tab', 'members', 'events'));
    }

    // =========================================================================
    // 16. LABS
    // =========================================================================
    public function labs(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'labs');
        $labs = DB::table('lab_records')->get();
        $sessions = DB::table('lab_sessions')
            ->join('lab_records', 'lab_sessions.lab_id', '=', 'lab_records.id')
            ->select('lab_sessions.*', 'lab_records.name as lab_name')
            ->latest('session_date')
            ->get();
        $incidents = DB::table('lab_incidents')
            ->join('lab_records', 'lab_incidents.lab_id', '=', 'lab_records.id')
            ->select('lab_incidents.*', 'lab_records.name as lab_name')
            ->latest('incident_date')
            ->get();

        return view('modules.labs', compact('school', 'tab', 'labs', 'sessions', 'incidents'));
    }

    // =========================================================================
    // 17. CANTEEN & DIGITAL CAMPUS WALLET
    // =========================================================================
    public function canteen(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $products = DB::table('canteen_products')->get();
        $wallets = DB::table('canteen_wallets')->get();
        $transactions = DB::table('canteen_transactions')
            ->join('canteen_wallets', 'canteen_transactions.wallet_id', '=', 'canteen_wallets.id')
            ->select('canteen_transactions.*', 'canteen_wallets.user_name')
            ->latest('canteen_transactions.created_at')
            ->take(15)
            ->get();

        return view('modules.canteen', compact('school', 'tab', 'products', 'wallets', 'transactions'));
    }

    // =========================================================================
    // 18. ONLINE COURSES LMS
    // =========================================================================
    public function courses(Request $request)
    {
        $school = School::first();
        $tab = $request->query('tab', 'dashboard');
        $courses = DB::table('online_courses')->get();

        return view('modules.courses', compact('school', 'tab', 'courses'));
    }

    // =========================================================================
    // 19. REPORTS & ANALYTICS HUB
    // =========================================================================
    public function reports()
    {
        $school = School::first();
        return view('modules.reports', compact('school'));
    }
}
