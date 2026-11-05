<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\AcademicsController;
use App\Http\Controllers\SchoolWebsiteController;
use App\Http\Controllers\ModulesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\FrontOfficeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CbcAcademicsController;
use App\Http\Controllers\OnlineExamController;
use App\Http\Controllers\PtmController;
use App\Http\Controllers\LessonPlannerController;
use App\Http\Controllers\OsmController;
use App\Http\Controllers\QrAttendanceController;
use App\Http\Controllers\BiometricDeviceController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\HpcController;
use App\Http\Controllers\ClassroomController;

// Root redirect
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication & Demo Logins
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']);

// Theme Switcher Route
Route::get('/set-theme/{theme}', function ($theme) {
    return redirect()->back()->withCookie(cookie()->forever('erp_theme', $theme));
})->name('theme.set');

// Protected School ERP Routes
Route::prefix('school')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =========================================================================
    // 🔥 COMPLETE 16 FINANCE & FEES SUB-MODULES (Exact match to demo)
    // =========================================================================
    Route::get('/fees', [FeeController::class, 'dashboard'])->name('fees.index');
    Route::get('/fees-dashboard', [FeeController::class, 'dashboard'])->name('fees.dashboard');
    Route::get('/collect-fees', [FeeController::class, 'collect'])->name('fees.collect');
    Route::get('/fees/collect', [FeeController::class, 'collect']); // alias
    Route::post('/collect-fees/pay', [FeeController::class, 'storePayment'])->name('fees.pay');
    Route::get('/due-fees', [FeeController::class, 'dueFees'])->name('fees.due');
    Route::get('/fees/due', [FeeController::class, 'dueFees']); // alias
    Route::get('/transactions', [FeeController::class, 'transactions'])->name('fees.transactions');
    Route::get('/online-payments', [FeeController::class, 'onlinePayments'])->name('fees.online');
    Route::get('/fee-challans', [FeeController::class, 'challans'])->name('fees.challans');
    Route::get('/assign-fees', [FeeController::class, 'assignFees'])->name('fees.assign');
    Route::get('/fees-carry-forward', [FeeController::class, 'carryForward'])->name('fees.carry-forward');
    Route::get('/fee-groups', [FeeController::class, 'feeGroups'])->name('fees.groups');
    Route::get('/fee-discounts', [FeeController::class, 'feeDiscounts'])->name('fees.discounts');
    Route::get('/fee-types', [FeeController::class, 'feeTypes'])->name('fees.types');
    Route::get('/due-slips', [FeeController::class, 'dueSlips'])->name('fees.due-slips');
    Route::get('/due-slips/history', [FeeController::class, 'dueSlipHistory'])->name('fees.due-slips-history');
    Route::get('/fee-audit', [FeeController::class, 'feeAudit'])->name('fees.audit');
    Route::get('/fee-imports', [FeeController::class, 'feeImports'])->name('fees.imports');
    Route::get('/fee-fine-rules', [FeeController::class, 'fineRules'])->name('fees.fine-rules');
    Route::get('/fees/receipt/{id}', [FeeController::class, 'receipt'])->name('fees.receipt');

    // =========================================================================
    // 🔥 COMPLETE ACCOUNTS & BOOKKEEPING SUITE (Exact match to demo)
    // =========================================================================
    Route::get('/accounting/dashboard', [AccountsController::class, 'dashboard'])->name('accounts.dashboard');
    Route::get('/accounts/dashboard', [AccountsController::class, 'dashboard']); // alias
    
    // Incomes
    Route::get('/accounts/incomes', [AccountsController::class, 'incomes'])->name('accounts.incomes.index');
    Route::get('/accounts/incomes/create', [AccountsController::class, 'createIncome'])->name('accounts.incomes.create');
    Route::post('/accounts/incomes', [AccountsController::class, 'storeIncome'])->name('accounts.incomes.store');
    Route::delete('/accounts/incomes/{id}', [AccountsController::class, 'destroyIncome'])->name('accounts.incomes.destroy');

    // Expenses
    Route::get('/accounts/expenses', [AccountsController::class, 'expenses'])->name('accounts.expenses.index');
    Route::get('/accounts/expenses/create', [AccountsController::class, 'createExpense'])->name('accounts.expenses.create');
    Route::post('/accounts/expenses', [AccountsController::class, 'storeExpense'])->name('accounts.expenses.store');
    Route::delete('/accounts/expenses/{id}', [AccountsController::class, 'destroyExpense'])->name('accounts.expenses.destroy');

    // Income Heads
    Route::get('/accounts/income-heads', [AccountsController::class, 'incomeHeads'])->name('accounts.income-heads.index');
    Route::get('/accounts/income-heads/create', [AccountsController::class, 'createIncomeHead'])->name('accounts.income-heads.create');
    Route::post('/accounts/income-heads', [AccountsController::class, 'storeIncomeHead'])->name('accounts.income-heads.store');
    Route::delete('/accounts/income-heads/{id}', [AccountsController::class, 'destroyIncomeHead'])->name('accounts.income-heads.destroy');

    // Expense Heads
    Route::get('/accounts/expense-heads', [AccountsController::class, 'expenseHeads'])->name('accounts.expense-heads.index');
    Route::get('/accounts/expense-heads/create', [AccountsController::class, 'createExpenseHead'])->name('accounts.expense-heads.create');
    Route::post('/accounts/expense-heads', [AccountsController::class, 'storeExpenseHead'])->name('accounts.expense-heads.store');
    Route::delete('/accounts/expense-heads/{id}', [AccountsController::class, 'destroyExpenseHead'])->name('accounts.expense-heads.destroy');

    // Bank Accounts
    Route::get('/bank-accounts', [AccountsController::class, 'bankAccounts'])->name('accounts.bank-accounts.index');
    Route::post('/bank-accounts', [AccountsController::class, 'storeBankAccount'])->name('accounts.bank-accounts.store');
    Route::delete('/bank-accounts/{id}', [AccountsController::class, 'destroyBankAccount'])->name('accounts.bank-accounts.destroy');

    // Day Book & Chart of Accounts
    Route::get('/accounting/day-book', [AccountsController::class, 'dayBook'])->name('accounts.day-book');
    Route::get('/accounting/chart-of-accounts', [AccountsController::class, 'chartOfAccounts'])->name('accounts.chart-of-accounts');

    // MANAGEMENT SUITE ROUTES (Remaining Modules)
    Route::prefix('management')->name('management.')->group(function () {
        Route::get('/', [ManagementController::class, 'index'])->name('index');
        Route::get('/front-office', [ManagementController::class, 'frontOffice'])->name('front-office');
        Route::get('/accounts', [AccountsController::class, 'dashboard'])->name('accounts');
        Route::get('/classrooms', [ManagementController::class, 'classrooms'])->name('classrooms');
        Route::get('/ptm-lesson-planner', [ManagementController::class, 'ptmLessonPlanner'])->name('ptm-lessons');
        Route::get('/compliance', [ManagementController::class, 'compliance'])->name('compliance');
        Route::get('/attendance-devices', [ManagementController::class, 'attendanceDevices'])->name('devices');
        Route::get('/hpc-cbc', [ManagementController::class, 'hpcCbc'])->name('hpc-cbc');
    });

    // =========================================================================
    // 🔥 ACADEMICS MODULE (11 Sub-Modules Matching Live MultiSchool ERP Demo)
    // =========================================================================
    // 1. Academic Dashboard
    Route::get('/academics/dashboard', [AcademicsController::class, 'dashboard'])->name('academics.dashboard');
    Route::get('/academics', [AcademicsController::class, 'dashboard'])->name('academics.index');

    // 2. Academic Sessions
    Route::get('/academic-sessions', [AcademicsController::class, 'sessions'])->name('academics.sessions');
    Route::post('/academic-sessions', [AcademicsController::class, 'storeSession'])->name('academics.sessions.store');
    Route::post('/academic-sessions/{id}/set-current', [AcademicsController::class, 'setCurrentSession'])->name('academics.sessions.set-current');
    Route::get('/academics/sessions', [AcademicsController::class, 'sessions'])->name('academics.sessions.alias');

    // 3. Classes
    Route::get('/classes', [AcademicsController::class, 'classes'])->name('academics.classes');
    Route::post('/classes', [AcademicsController::class, 'storeClass'])->name('academics.classes.store');
    Route::post('/classes/{id}/delete', [AcademicsController::class, 'deleteClass'])->name('academics.classes.delete');
    Route::get('/academics/classes', [AcademicsController::class, 'classes'])->name('academics.classes.alias');

    // 4. Sections
    Route::get('/sections', [AcademicsController::class, 'sections'])->name('academics.sections');
    Route::post('/sections', [AcademicsController::class, 'storeSection'])->name('academics.sections.store');
    Route::post('/sections/{id}/delete', [AcademicsController::class, 'deleteSection'])->name('academics.sections.delete');
    Route::get('/academics/sections', [AcademicsController::class, 'sections'])->name('academics.sections.alias');

    // 5. Subjects
    Route::get('/subjects', [AcademicsController::class, 'subjects'])->name('academics.subjects');
    Route::post('/subjects', [AcademicsController::class, 'storeSubject'])->name('academics.subjects.store');
    Route::post('/subjects/{id}/delete', [AcademicsController::class, 'deleteSubject'])->name('academics.subjects.delete');
    Route::get('/academics/subjects', [AcademicsController::class, 'subjects'])->name('academics.subjects.alias');

    // 6. Assign Subjects
    Route::get('/assign-subjects', [AcademicsController::class, 'assignSubjects'])->name('academics.assign-subjects');
    Route::post('/assign-subjects', [AcademicsController::class, 'storeAssignSubjects'])->name('academics.assign-subjects.store');

    // 7. Assign Electives
    Route::get('/assign-electives', [AcademicsController::class, 'assignElectives'])->name('academics.assign-electives');
    Route::post('/assign-electives', [AcademicsController::class, 'storeAssignElectives'])->name('academics.assign-electives.store');

    // 8. Assign Class Teacher
    Route::get('/assign-class-teacher', [AcademicsController::class, 'assignClassTeacher'])->name('academics.assign-class-teacher');
    Route::post('/assign-class-teacher', [AcademicsController::class, 'storeClassTeacher'])->name('academics.assign-class-teacher.store');

    // 9. Manage Periods (Time Slots)
    Route::get('/time-slots', [AcademicsController::class, 'timeSlots'])->name('academics.time-slots');
    Route::get('/period-sets', [AcademicsController::class, 'timeSlots'])->name('academics.period-sets');
    Route::post('/time-slots', [AcademicsController::class, 'storeTimeSlot'])->name('academics.time-slots.store');
    Route::post('/time-slots/{id}/delete', [AcademicsController::class, 'deleteTimeSlot'])->name('academics.time-slots.delete');

    // 10. Class Timetable
    Route::get('/timetable', [AcademicsController::class, 'timetable'])->name('academics.timetable');
    Route::post('/timetable/save-slot', [AcademicsController::class, 'saveSlot'])->name('academics.timetable.save-slot');
    Route::get('/academics/timetable', [AcademicsController::class, 'timetable'])->name('academics.timetable.alias');

    // 11. Promote Students
    Route::get('/promote-students', [AcademicsController::class, 'promoteStudents'])->name('academics.promote-students');
    Route::post('/promote-students', [AcademicsController::class, 'executePromotion'])->name('academics.promote-students.store');
    Route::get('/academics/promote', [AcademicsController::class, 'promoteStudents'])->name('academics.promote');

    // =========================================================================
    // 🔥 FRONT OFFICE MODULE (8 Sub-Modules Exact Match)
    // =========================================================================
    Route::prefix('front-office')->name('front-office.')->group(function () {
        // 1. Dashboard
        Route::get('/dashboard', [FrontOfficeController::class, 'dashboard'])->name('dashboard');
        Route::get('/', [FrontOfficeController::class, 'dashboard'])->name('index');

        // 2. Admission Enquiries
        Route::get('/admission-enquiries', [FrontOfficeController::class, 'admissionEnquiries'])->name('admission-enquiries');
        Route::get('/admission-enquiries/create', [FrontOfficeController::class, 'createAdmissionEnquiry'])->name('admission-enquiries.create');
        Route::post('/admission-enquiries', [FrontOfficeController::class, 'storeAdmissionEnquiry'])->name('admission-enquiries.store');
        Route::post('/admission-enquiries/{id}/delete', [FrontOfficeController::class, 'deleteAdmissionEnquiry'])->name('admission-enquiries.delete');

        // 3. Visitor Book
        Route::get('/visitor-logs', [FrontOfficeController::class, 'visitorLogs'])->name('visitor-logs');
        Route::get('/visitor-logs/create', [FrontOfficeController::class, 'createVisitorLog'])->name('visitor-logs.create');
        Route::post('/visitor-logs', [FrontOfficeController::class, 'storeVisitorLog'])->name('visitor-logs.store');
        Route::post('/visitor-logs/{id}/out', [FrontOfficeController::class, 'markVisitorOut'])->name('visitor-logs.out');
        Route::post('/visitor-logs/{id}/delete', [FrontOfficeController::class, 'deleteVisitorLog'])->name('visitor-logs.delete');

        // 4. Complaints
        Route::get('/complaints', [FrontOfficeController::class, 'complaints'])->name('complaints');
        Route::get('/complaints/create', [FrontOfficeController::class, 'createComplaint'])->name('complaints.create');
        Route::post('/complaints', [FrontOfficeController::class, 'storeComplaint'])->name('complaints.store');
        Route::post('/complaints/{id}/resolve', [FrontOfficeController::class, 'resolveComplaint'])->name('complaints.resolve');
        Route::post('/complaints/{id}/delete', [FrontOfficeController::class, 'deleteComplaint'])->name('complaints.delete');

        // 5. Postal Records
        Route::get('/postal-records', [FrontOfficeController::class, 'postalRecords'])->name('postal-records');
        Route::get('/postal-records/create', [FrontOfficeController::class, 'createPostalRecord'])->name('postal-records.create');
        Route::post('/postal-records', [FrontOfficeController::class, 'storePostalRecord'])->name('postal-records.store');
        Route::post('/postal-records/{id}/delete', [FrontOfficeController::class, 'deletePostalRecord'])->name('postal-records.delete');

        // 6. Gate Passes
        Route::get('/gate-passes', [FrontOfficeController::class, 'gatePasses'])->name('gate-passes');
        Route::get('/gate-passes/create', [FrontOfficeController::class, 'createGatePass'])->name('gate-passes.create');
        Route::post('/gate-passes', [FrontOfficeController::class, 'storeGatePass'])->name('gate-passes.store');
        Route::post('/gate-passes/{id}/status', [FrontOfficeController::class, 'updateGatePassStatus'])->name('gate-passes.status');
        Route::post('/gate-passes/{id}/delete', [FrontOfficeController::class, 'deleteGatePass'])->name('gate-passes.delete');

        // 7. Gate Control Terminal
        Route::get('/gate-passes/gate', [FrontOfficeController::class, 'gateTerminal'])->name('gate-terminal');

        // 8. Campus Workers
        Route::get('/campus-workers', [FrontOfficeController::class, 'campusWorkers'])->name('campus-workers');
        Route::get('/campus-workers/create', [FrontOfficeController::class, 'createCampusWorker'])->name('campus-workers.create');
        Route::post('/campus-workers', [FrontOfficeController::class, 'storeCampusWorker'])->name('campus-workers.store');
        Route::post('/campus-workers/{id}/toggle-state', [FrontOfficeController::class, 'toggleWorkerCampusState'])->name('campus-workers.toggle-state');
        Route::post('/campus-workers/{id}/delete', [FrontOfficeController::class, 'deleteCampusWorker'])->name('campus-workers.delete');
    });

    // School Website CMS
    Route::prefix('website')->name('website.')->group(function () {
        Route::get('/', [SchoolWebsiteController::class, 'index'])->name('index');
        Route::get('/pages', [SchoolWebsiteController::class, 'pages'])->name('pages');
        Route::get('/slides', [SchoolWebsiteController::class, 'slides'])->name('slides');
        Route::get('/facilities', [SchoolWebsiteController::class, 'facilities'])->name('facilities');
        Route::get('/testimonials', [SchoolWebsiteController::class, 'testimonials'])->name('testimonials');
        Route::get('/disclosure', [SchoolWebsiteController::class, 'disclosure'])->name('disclosure');
    });

    // =========================================================================
    // 🔥 COMPLETE LEAD MANAGEMENT SUITE (Exact match to demo)
    // =========================================================================
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [LeadController::class, 'dashboard'])->name('dashboard');
        Route::get('/board', [LeadController::class, 'board'])->name('board');
        Route::get('/list', [LeadController::class, 'list'])->name('list');
        Route::get('/tasks', [LeadController::class, 'tasks'])->name('tasks');
        Route::get('/settings', [LeadController::class, 'settings'])->name('settings');

        Route::post('/', [LeadController::class, 'store'])->name('store');
        Route::post('/{id}/stage', [LeadController::class, 'updateStage'])->name('stage.update');
        Route::post('/{id}/followup', [LeadController::class, 'storeFollowup'])->name('followup.store');
        Route::post('/{id}/delete', [LeadController::class, 'delete'])->name('delete');

        Route::post('/settings/stages', [LeadController::class, 'storeStage'])->name('settings.stages.store');
        Route::post('/settings/stages/{id}', [LeadController::class, 'updateStageSetting'])->name('settings.stages.update');
        Route::post('/settings/sources', [LeadController::class, 'storeSource'])->name('settings.sources.store');
    });

    // =========================================================================
    // 🔥 COMPLETE OFFLINE EXAMINATIONS SUITE (All 12+ Sub-Modules Exact Match)
    // =========================================================================
    Route::get('/exams-dashboard', [ExamController::class, 'dashboard'])->name('exams.dashboard');
    Route::get('/exams/dashboard', [ExamController::class, 'dashboard']); // alias

    // Manage Exams
    Route::get('/exams', [ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/create', [ExamController::class, 'create'])->name('exams.create');
    Route::post('/exams', [ExamController::class, 'store'])->name('exams.store');
    Route::post('/exams/{id}/delete', [ExamController::class, 'destroy'])->name('exams.delete');

    // Exam Types
    Route::get('/exam-types', [ExamController::class, 'examTypes'])->name('exams.types');
    Route::post('/exam-types', [ExamController::class, 'storeExamType'])->name('exams.types.store');
    Route::post('/exam-types/{id}/delete', [ExamController::class, 'destroyExamType'])->name('exams.types.delete');

    // Exam Schedule & Marks Setup
    Route::get('/exam-schedule', [ExamController::class, 'schedule'])->name('exams.schedule');
    Route::post('/exam-schedule', [ExamController::class, 'storeSchedule'])->name('exams.schedule.store');
    Route::post('/exam-schedule/{id}/delete', [ExamController::class, 'destroySchedule'])->name('exams.schedule.delete');

    // Enter Marks
    Route::get('/exam-marks/entry', [ExamController::class, 'enterMarks'])->name('exams.marks.entry');
    Route::get('/enter-marks', [ExamController::class, 'enterMarks'])->name('exams.enter-marks'); // alias
    Route::post('/exam-marks/save', [ExamController::class, 'saveMarks'])->name('exams.marks.save');

    // Cocurricular Grades
    Route::get('/cocurricular-grades', [ExamController::class, 'cocurricularGrades'])->name('exams.cocurricular');
    Route::post('/cocurricular-grades/save', [ExamController::class, 'saveCocurricularGrades'])->name('exams.cocurricular.save');

    // Manage Grades
    Route::get('/grades', [ExamController::class, 'grades'])->name('exams.grades');
    Route::post('/grades', [ExamController::class, 'storeGrade'])->name('exams.grades.store');
    Route::post('/grades/{id}/delete', [ExamController::class, 'destroyGrade'])->name('exams.grades.delete');

    // Marksheets & Report Cards
    Route::get('/marksheet', [ExamController::class, 'marksheet'])->name('exams.marksheet.index');

    // Upload External Marksheets (Must come before {student_id} parameter)
    Route::get('/marksheet/upload', [ExamController::class, 'uploadMarksheet'])->name('exams.marksheet.upload');
    Route::post('/marksheet/upload', [ExamController::class, 'storeUploadedMarksheet'])->name('exams.marksheet.upload.store');
    Route::get('/marksheet/upload-list', [ExamController::class, 'uploadList'])->name('exams.marksheet.upload-list');
    Route::post('/marksheet/upload/{id}/toggle-publish', [ExamController::class, 'togglePublishUpload'])->name('exams.marksheet.upload.toggle');
    Route::post('/marksheet/upload/{id}/delete', [ExamController::class, 'destroyUpload'])->name('exams.marksheet.upload.delete');

    // Individual Printable Marksheet
    Route::get('/marksheet/{student_id}/{exam_id?}', [ExamController::class, 'showMarksheet'])->where('student_id', '[0-9]+')->name('exams.marksheet.show');
    Route::get('/exams/marksheet/{studentId}', [ExamController::class, 'showMarksheet'])->name('exams.marksheet'); // alias

    // Teacher Remarks on Report Card
    Route::get('/marksheet-remarks', [ExamController::class, 'marksheetRemarks'])->name('exams.marksheet.remarks');
    Route::post('/marksheet-remarks/save', [ExamController::class, 'saveMarksheetRemarks'])->name('exams.marksheet.remarks.save');

    // Admit Cards (Hall Tickets)
    Route::get('/admit-cards', [ExamController::class, 'admitCards'])->name('exams.admit-cards');
    Route::get('/admit-cards/print/{student_id}/{exam_id?}', [ExamController::class, 'printAdmitCard'])->name('exams.admit-cards.print');

    // =========================================================================
    // 🔥 COMPLETE CBC ACADEMICS SUITE (Competency-Based Curriculum Exact Match)
    // =========================================================================
    Route::prefix('cbc')->name('cbc.')->group(function () {
        Route::get('/', [CbcAcademicsController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [CbcAcademicsController::class, 'dashboard'])->name('dashboard');
        
        // Strands & Learning Outcomes
        Route::get('/strands', [CbcAcademicsController::class, 'strands'])->name('strands');
        Route::post('/strands', [CbcAcademicsController::class, 'storeStrand'])->name('strands.store');
        Route::match(['post', 'delete'], '/strands/{id}/delete', [CbcAcademicsController::class, 'destroyStrand'])->name('strands.delete');
        Route::delete('/strands/{id}', [CbcAcademicsController::class, 'destroyStrand'])->name('strands.destroy');
        Route::post('/outcomes', [CbcAcademicsController::class, 'storeOutcome'])->name('outcomes.store');
        Route::match(['post', 'delete'], '/outcomes/{id}/delete', [CbcAcademicsController::class, 'destroyOutcome'])->name('outcomes.delete');
        Route::delete('/outcomes/{id}', [CbcAcademicsController::class, 'destroyOutcome'])->name('outcomes.destroy');

        // CBC Assessments
        Route::get('/assessments', [CbcAcademicsController::class, 'assessments'])->name('assessments');
        Route::post('/assessments', [CbcAcademicsController::class, 'storeAssessment'])->name('assessments.store');
        Route::post('/assessments/evaluate', [CbcAcademicsController::class, 'saveEvaluations'])->name('assessments.evaluate');

        // Core Competencies
        Route::get('/competencies', [CbcAcademicsController::class, 'competencies'])->name('competencies');
        Route::post('/competencies', [CbcAcademicsController::class, 'storeCompetency'])->name('competencies.store');
        Route::post('/competencies/seed-defaults', [CbcAcademicsController::class, 'seedDefaults'])->name('competencies.seed');
        Route::match(['post', 'delete'], '/competencies/{id}/delete', [CbcAcademicsController::class, 'destroyCompetency'])->name('competencies.delete');
        Route::delete('/competencies/{id}', [CbcAcademicsController::class, 'destroyCompetency'])->name('competencies.destroy');

        // Senior Pathways & Tracks
        Route::get('/pathways', [CbcAcademicsController::class, 'pathways'])->name('pathways');
        Route::post('/pathways', [CbcAcademicsController::class, 'storePathway'])->name('pathways.store');
        Route::match(['post', 'delete'], '/pathways/{id}/delete', [CbcAcademicsController::class, 'destroyPathway'])->name('pathways.delete');
        Route::delete('/pathways/{id}', [CbcAcademicsController::class, 'destroyPathway'])->name('pathways.destroy');
        Route::post('/tracks', [CbcAcademicsController::class, 'storeTrack'])->name('tracks.store');

        // CBC Reports & Holistic Analytics
        Route::get('/cbcreports', [CbcAcademicsController::class, 'reports'])->name('reports');
        Route::get('/reports', [CbcAcademicsController::class, 'reports']); // alias
        Route::get('/reports/student/{id}', [CbcAcademicsController::class, 'showStudentReport'])->name('reports.student');
    });
    // =========================================================================
    // 🔥 COMPLETE ONLINE EXAMINATIONS SUITE (CBT, Question Bank, Online Exams)
    // =========================================================================
    Route::prefix('online-exam')->name('online-exams.')->group(function () {
        Route::get('/', [OnlineExamController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [OnlineExamController::class, 'dashboard'])->name('dashboard');
        
        // Manage Online Exams
        Route::get('/manage-exams', [OnlineExamController::class, 'manageExams'])->name('manage');
        Route::post('/manage-exams', [OnlineExamController::class, 'storeExam'])->name('store');
        Route::match(['post', 'delete'], '/manage-exams/{id}/delete', [OnlineExamController::class, 'destroyExam'])->name('destroy');
        
        // Online Exam Paper Builder
        Route::get('/builder/{id}', [OnlineExamController::class, 'builder'])->name('builder');
        Route::post('/builder/{id}/questions', [OnlineExamController::class, 'addQuestionToExam'])->name('builder.add-question');
        Route::match(['post', 'delete'], '/builder/{examId}/questions/{questionId}/remove', [OnlineExamController::class, 'removeQuestionFromExam'])->name('builder.remove-question');
        
        // Question Bank Master
        Route::get('/question-bank', [OnlineExamController::class, 'questionBank'])->name('question-bank');
        Route::get('/question-bank/create', [OnlineExamController::class, 'createQuestion'])->name('question-bank.create');
        Route::post('/question-bank', [OnlineExamController::class, 'storeQuestion'])->name('question-bank.store');
        Route::match(['post', 'delete'], '/question-bank/{id}/delete', [OnlineExamController::class, 'destroyQuestion'])->name('question-bank.destroy');
        Route::get('/question-bank/bulk-upload', [OnlineExamController::class, 'bulkUpload'])->name('question-bank.bulk-upload');
        Route::post('/question-bank/bulk-upload', [OnlineExamController::class, 'processBulkUpload'])->name('question-bank.bulk-upload.process');
        
        // Chapters & Topics
        Route::get('/question-topics', [OnlineExamController::class, 'questionTopics'])->name('question-topics');
        Route::post('/question-topics', [OnlineExamController::class, 'storeTopic'])->name('question-topics.store');
        Route::match(['post', 'delete'], '/question-topics/{id}/delete', [OnlineExamController::class, 'destroyTopic'])->name('question-topics.destroy');
        
        // Results & Evaluation
        Route::get('/manage-exams/{id}/results', [OnlineExamController::class, 'results'])->name('results');
        Route::get('/manage-exams/{examId}/results/{attemptId}', [OnlineExamController::class, 'showAnswerSheet'])->name('answer-sheet');
        
        // Student CBT Online Exam Simulator
        Route::get('/take/{id}', [OnlineExamController::class, 'takeExam'])->name('take');
        Route::post('/take/{id}/submit', [OnlineExamController::class, 'submitExam'])->name('submit');
    });
    Route::get('/online-exams', [OnlineExamController::class, 'dashboard']); // alias
    Route::get('/osm', [ModulesController::class, 'osmConsole'])->name('osm.index');
    Route::get('/surveys', [ModulesController::class, 'surveys'])->name('surveys.index');
    Route::get('/library', [ModulesController::class, 'library'])->name('library.index');
    Route::get('/transport', [ModulesController::class, 'transport'])->name('transport.index');
    Route::get('/hostel', [ModulesController::class, 'hostel'])->name('hostel.index');
    Route::get('/canteen', [ModulesController::class, 'canteen'])->name('canteen.index');
    Route::get('/cctv', [ModulesController::class, 'cctv'])->name('cctv.index');
    Route::get('/certificates', [ModulesController::class, 'certificates'])->name('certificates.index');
    Route::get('/id-cards', [ModulesController::class, 'idCards'])->name('id-cards.index');

    // =========================================================================
    // 🔥 COMPLETE STUDENT INFORMATION SYSTEM (12 Sub-Modules Exact Match)
    // =========================================================================
    Route::get('/student-dashboard', [StudentController::class, 'dashboard'])->name('students.dashboard');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students-face-search', [StudentController::class, 'faceSearch'])->name('students.face-search');
    Route::get('/parents', [StudentController::class, 'parents'])->name('students.parents');
    Route::get('/student-attendance', [StudentController::class, 'attendance'])->name('students.attendance');
    Route::post('/student-attendance', [StudentController::class, 'markAttendance'])->name('students.attendance.store');
    Route::get('/student-behavior', [StudentController::class, 'behaviorRecords'])->name('students.behavior');
    Route::post('/student-behavior', [StudentController::class, 'storeBehavior'])->name('students.behavior.store');
    Route::get('/student-houses', [StudentController::class, 'houses'])->name('students.houses');
    Route::post('/student-houses', [StudentController::class, 'storeHouse'])->name('students.houses.store');
    Route::get('/student-categories', [StudentController::class, 'categories'])->name('students.categories');
    Route::post('/student-categories', [StudentController::class, 'storeCategory'])->name('students.categories.store');
    Route::get('/student-exits', [StudentController::class, 'exits'])->name('students.exits');
    Route::post('/student-exits', [StudentController::class, 'storeExit'])->name('students.exits.store');
    Route::get('/student-health', [StudentController::class, 'health'])->name('students.health');
    Route::post('/student-health', [StudentController::class, 'updateHealth'])->name('students.health.update');
    Route::get('/students-recycle-bin', [StudentController::class, 'recycleBin'])->name('students.recycle-bin');
    Route::post('/students/{id}/restore', [StudentController::class, 'restore'])->name('students.restore');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Attendance Management
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');

    // =========================================================================
    // 🔥 COMPLETE HUMAN RESOURCE (HR & STAFF MANAGEMENT SUITE)
    // =========================================================================
    // 1. Staff Directory & Profile
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');

    // 2. Staff Attendance
    Route::get('/staff-attendance', [StaffController::class, 'attendance'])->name('staff.attendance');
    Route::post('/staff-attendance', [StaffController::class, 'storeAttendance'])->name('staff.attendance.store');

    // 3. Payroll Management
    Route::get('/payroll', [StaffController::class, 'payroll'])->name('staff.payroll');
    Route::post('/payroll/generate', [StaffController::class, 'generatePayroll'])->name('staff.payroll.generate');
    Route::post('/payroll/{id}/toggle', [StaffController::class, 'togglePayrollStatus'])->name('staff.payroll.toggle');

    // 4. Set Salary
    Route::get('/staff-salary', [StaffController::class, 'staffSalary'])->name('staff.salary');
    Route::post('/staff-salary/{id}', [StaffController::class, 'updateStaffSalary'])->name('staff.salary.update');

    // 5. Salary Templates
    Route::get('/salary-templates', [StaffController::class, 'salaryTemplates'])->name('staff.salary-templates');
    Route::post('/salary-templates', [StaffController::class, 'storeSalaryTemplate'])->name('staff.salary-templates.store');
    Route::match(['post', 'delete'], '/salary-templates/{id}/delete', [StaffController::class, 'destroySalaryTemplate'])->name('staff.salary-templates.destroy');

    // 6. Approve Leave
    Route::get('/approve-leave', [StaffController::class, 'approveLeave'])->name('staff.approve-leave');
    Route::post('/approve-leave/{id}', [StaffController::class, 'updateLeaveStatus'])->name('staff.approve-leave.update');

    // 7. Leave Types
    Route::get('/leave-types', [StaffController::class, 'leaveTypes'])->name('staff.leave-types');
    Route::post('/leave-types', [StaffController::class, 'storeLeaveType'])->name('staff.leave-types.store');
    Route::match(['post', 'delete'], '/leave-types/{id}/delete', [StaffController::class, 'destroyLeaveType'])->name('staff.leave-types.destroy');

    // 8. Departments
    Route::get('/departments', [StaffController::class, 'departments'])->name('staff.departments');
    Route::post('/departments', [StaffController::class, 'storeDepartment'])->name('staff.departments.store');
    Route::match(['post', 'delete'], '/departments/{id}/delete', [StaffController::class, 'destroyDepartment'])->name('staff.departments.destroy');

    // 9. Designations
    Route::get('/designations', [StaffController::class, 'designations'])->name('staff.designations');
    Route::post('/designations', [StaffController::class, 'storeDesignation'])->name('staff.designations.store');
    Route::match(['post', 'delete'], '/designations/{id}/delete', [StaffController::class, 'destroyDesignation'])->name('staff.designations.destroy');

    // 10. Staff Loans
    Route::get('/loans', [StaffController::class, 'loans'])->name('staff.loans');
    Route::post('/loans', [StaffController::class, 'storeLoan'])->name('staff.loans.store');
    Route::post('/loans/{id}/repay', [StaffController::class, 'repayLoan'])->name('staff.loans.repay');

    // 11. Staff ID Card
    Route::get('/staff-id-card', [StaffController::class, 'idCards'])->name('staff.id-card');

    // ==========================================
    // PTM MEETINGS (PARENT-TEACHER CONFERENCES)
    // ==========================================
    Route::prefix('ptm')->group(function () {
        Route::get('/', function () { return redirect()->route('ptm.dashboard'); });
        Route::get('/dashboard', [PtmController::class, 'dashboard'])->name('ptm.dashboard');
        Route::get('/meetings', [PtmController::class, 'meetings'])->name('ptm.meetings');
        Route::get('/meetings/create', [PtmController::class, 'create'])->name('ptm.meetings.create');
        Route::post('/meetings', [PtmController::class, 'store'])->name('ptm.meetings.store');
        Route::get('/meetings/{id}', [PtmController::class, 'show'])->name('ptm.meetings.show');
        Route::get('/meetings/{id}/edit', [PtmController::class, 'edit'])->name('ptm.meetings.edit');
        Route::match(['post', 'put'], '/meetings/{id}', [PtmController::class, 'update'])->name('ptm.meetings.update');
        Route::match(['post', 'delete'], '/meetings/{id}/delete', [PtmController::class, 'destroy'])->name('ptm.meetings.destroy');
        Route::post('/meetings/{id}/notify', [PtmController::class, 'notifyMeeting'])->name('ptm.meetings.notify');

        // Attendance & Remarks Matrix
        Route::get('/record', [PtmController::class, 'recordIndex'])->name('ptm.record');
        Route::get('/meetings/{id}/record', [PtmController::class, 'record'])->name('ptm.meetings.record');
        Route::post('/meetings/{id}/record', [PtmController::class, 'saveRecord'])->name('ptm.meetings.save-record');

        // Follow-ups & Action Items
        Route::get('/followups', [PtmController::class, 'followups'])->name('ptm.followups');
        Route::post('/followups', [PtmController::class, 'storeFollowup'])->name('ptm.followups.store');
        Route::match(['post', 'put'], '/followups/{id}', [PtmController::class, 'updateFollowup'])->name('ptm.followups.update');
        Route::match(['post', 'delete'], '/followups/{id}/delete', [PtmController::class, 'destroyFollowup'])->name('ptm.followups.destroy');

        // Reports & Guides
        Route::get('/reports', [PtmController::class, 'reports'])->name('ptm.reports');
        Route::get('/guide', [PtmController::class, 'guide'])->name('ptm.guide');
    });

    // ==========================================
    // LESSON PLANNER MODULE
    // ==========================================
    Route::prefix('lesson-plans')->group(function () {
        Route::get('/', function () { return redirect()->route('lesson-plans.dashboard'); });
        Route::get('/dashboard', [LessonPlannerController::class, 'dashboard'])->name('lesson-plans.dashboard');
        Route::get('/plans', [LessonPlannerController::class, 'plans'])->name('lesson-plans.plans');
        Route::get('/plans/create', [LessonPlannerController::class, 'create'])->name('lesson-plans.plans.create');
        Route::post('/plans', [LessonPlannerController::class, 'store'])->name('lesson-plans.plans.store');
        Route::get('/plans/{id}', [LessonPlannerController::class, 'show'])->name('lesson-plans.plans.show');
        Route::get('/plans/{id}/edit', [LessonPlannerController::class, 'edit'])->name('lesson-plans.plans.edit');
        Route::match(['post', 'put'], '/plans/{id}', [LessonPlannerController::class, 'update'])->name('lesson-plans.plans.update');
        Route::match(['post', 'delete'], '/plans/{id}/delete', [LessonPlannerController::class, 'destroy'])->name('lesson-plans.plans.destroy');
        Route::post('/plans/{id}/clone', [LessonPlannerController::class, 'clonePlan'])->name('lesson-plans.plans.clone');
        Route::post('/plans/{id}/submit', [LessonPlannerController::class, 'submitForReview'])->name('lesson-plans.plans.submit');

        // Review & Approvals
        Route::get('/review', [LessonPlannerController::class, 'reviewIndex'])->name('lesson-plans.review');
        Route::post('/review/{id}', [LessonPlannerController::class, 'reviewAction'])->name('lesson-plans.review.action');
        Route::get('/approvals', [LessonPlannerController::class, 'approvalsIndex'])->name('lesson-plans.approvals');
        Route::post('/approvals/bulk', [LessonPlannerController::class, 'bulkApprove'])->name('lesson-plans.approvals.bulk');
        Route::post('/approvals/{id}', [LessonPlannerController::class, 'approvePlan'])->name('lesson-plans.approvals.approve');

        // Coverage Tracker
        Route::get('/coverage', [LessonPlannerController::class, 'coverageIndex'])->name('lesson-plans.coverage');
        Route::get('/coverage/{id}', [LessonPlannerController::class, 'coverageDetail'])->name('lesson-plans.coverage.detail');
        Route::match(['post', 'put'], '/coverage/{id}', [LessonPlannerController::class, 'saveCoverage'])->name('lesson-plans.coverage.save');

        // Reports, Settings & Guide
        Route::get('/reports', [LessonPlannerController::class, 'reports'])->name('lesson-plans.reports');
        Route::get('/settings', [LessonPlannerController::class, 'settings'])->name('lesson-plans.settings');
        Route::match(['post', 'put'], '/settings', [LessonPlannerController::class, 'updateSettings'])->name('lesson-plans.settings.update');
        Route::post('/settings/department-heads', [LessonPlannerController::class, 'assignDepartmentHead'])->name('lesson-plans.settings.dept-heads');
        Route::get('/guide', [LessonPlannerController::class, 'guide'])->name('lesson-plans.guide');
    });

    // ==========================================
    // 🔥 OSM MODULE (ON-SCREEN MARKING & DIGITAL EVALUATION)
    // ==========================================
    Route::prefix('osm')->group(function () {
        Route::get('/', function () { return redirect()->route('osm.dashboard'); });
        Route::get('/dashboard', [OsmController::class, 'dashboard'])->name('osm.dashboard');
        
        // Sessions
        Route::get('/sessions', [OsmController::class, 'sessions'])->name('osm.sessions');
        Route::get('/sessions/create', [OsmController::class, 'createSession'])->name('osm.sessions.create');
        Route::post('/sessions', [OsmController::class, 'storeSession'])->name('osm.sessions.store');
        Route::get('/sessions/{id}', [OsmController::class, 'showSession'])->name('osm.sessions.show');
        Route::get('/sessions/{id}/edit', [OsmController::class, 'editSession'])->name('osm.sessions.edit');
        Route::match(['post', 'put'], '/sessions/{id}', [OsmController::class, 'updateSession'])->name('osm.sessions.update');
        Route::match(['post', 'delete'], '/sessions/{id}/delete', [OsmController::class, 'destroySession'])->name('osm.sessions.destroy');

        // Session Workbench Actions
        Route::post('/sessions/{id}/questions', [OsmController::class, 'saveQuestions'])->name('osm.sessions.questions');
        Route::post('/sessions/{id}/upload-sheets', [OsmController::class, 'uploadSheets'])->name('osm.sessions.upload');
        Route::post('/sessions/{id}/assign-evaluators', [OsmController::class, 'assignEvaluators'])->name('osm.sessions.assign');

        // Evaluator Workbench & Canvas
        Route::get('/evaluate', [OsmController::class, 'evaluateIndex'])->name('osm.evaluate');
        Route::get('/evaluate/{paperId}', [OsmController::class, 'evaluateCanvas'])->name('osm.evaluate.canvas');
        Route::post('/evaluate/{paperId}/score-question', [OsmController::class, 'scoreQuestion'])->name('osm.evaluate.score');
        Route::post('/evaluate/{paperId}/annotations', [OsmController::class, 'saveAnnotations'])->name('osm.evaluate.annotations');
        Route::post('/evaluate/{paperId}/finish', [OsmController::class, 'finishPaper'])->name('osm.evaluate.finish');
        Route::post('/evaluate/{paperId}/reject', [OsmController::class, 'rejectPaper'])->name('osm.evaluate.reject');

        // Moderation
        Route::get('/moderation', [OsmController::class, 'moderation'])->name('osm.moderation');
        Route::post('/moderation/{paperId}', [OsmController::class, 'saveModeration'])->name('osm.moderation.save');

        // Reports & Analytics
        Route::get('/reports', [OsmController::class, 'reports'])->name('osm.reports');
        Route::get('/reports/analytics/{id}', [OsmController::class, 'reportsAnalytics'])->name('osm.reports.analytics');
        Route::get('/reports/evaluator/{id}', [OsmController::class, 'reportsEvaluator'])->name('osm.reports.evaluator');

        // Operational Guide
        Route::get('/guide', [OsmController::class, 'guide'])->name('osm.guide');
    });

    // ==========================================
    // 🔥 QR CODE ATTENDANCE MODULE
    // ==========================================
    Route::prefix('qr-attendance')->group(function () {
        Route::get('/', [QrAttendanceController::class, 'scanner'])->name('qr-attendance.scanner');
        Route::post('/resolve', [QrAttendanceController::class, 'resolve'])->name('qr-attendance.resolve');
        Route::post('/punch', [QrAttendanceController::class, 'punch'])->name('qr-attendance.punch');
        Route::get('/status', [QrAttendanceController::class, 'status'])->name('qr-attendance.status');
        Route::get('/settings', [QrAttendanceController::class, 'settings'])->name('qr-attendance.settings');
        Route::post('/settings', [QrAttendanceController::class, 'saveSettings'])->name('qr-attendance.settings.save');
        Route::get('/report', [QrAttendanceController::class, 'report'])->name('qr-attendance.report');
        Route::get('/report/export', [QrAttendanceController::class, 'exportReport'])->name('qr-attendance.report.export');
        Route::get('/audit', [QrAttendanceController::class, 'audit'])->name('qr-attendance.audit');
        Route::get('/audit/export', [QrAttendanceController::class, 'exportAudit'])->name('qr-attendance.audit.export');
        Route::post('/audit/purge', [QrAttendanceController::class, 'purgeAudit'])->name('qr-attendance.audit.purge');
        Route::post('/audit/wipe', [QrAttendanceController::class, 'wipeAudit'])->name('qr-attendance.audit.wipe');
    });

    // ==========================================
    // 🔏 BIOMETRIC DEVICES MODULE (Exact match to live demo)
    // ==========================================
    Route::prefix('biometric')->group(function () {
        Route::get('/devices', [BiometricDeviceController::class, 'index'])->name('biometric.devices');
        Route::get('/devices/create', [BiometricDeviceController::class, 'create'])->name('biometric.devices.create');
        Route::post('/devices', [BiometricDeviceController::class, 'store'])->name('biometric.devices.store');
        Route::get('/devices/{id}/edit', [BiometricDeviceController::class, 'edit'])->name('biometric.devices.edit');
        Route::put('/devices/{id}', [BiometricDeviceController::class, 'update'])->name('biometric.devices.update');
        Route::delete('/devices/{id}', [BiometricDeviceController::class, 'destroy'])->name('biometric.devices.destroy');
        Route::get('/devices/{id}/control-panel', [BiometricDeviceController::class, 'controlPanel'])->name('biometric.devices.control-panel');
        Route::post('/devices/{id}/command', [BiometricDeviceController::class, 'executeCommand'])->name('biometric.devices.command');
        Route::post('/devices/{id}/ping', [BiometricDeviceController::class, 'ping'])->name('biometric.devices.ping');
        Route::post('/devices/{id}/templates/import', [BiometricDeviceController::class, 'importTemplates'])->name('biometric.devices.templates.import');
        Route::get('/devices/{id}/templates/export', [BiometricDeviceController::class, 'exportTemplates'])->name('biometric.devices.templates.export');

        // Logs & Monitoring
        Route::get('/logs', [BiometricDeviceController::class, 'logs'])->name('biometric.logs');
        Route::get('/logs/export', [BiometricDeviceController::class, 'exportLogs'])->name('biometric.logs.export');
        Route::get('/monitoring-logs', [BiometricDeviceController::class, 'monitoringLogs'])->name('biometric.monitoring-logs');
        Route::get('/system-logs', [BiometricDeviceController::class, 'systemLogs'])->name('biometric.system-logs');

        // API Playground & Readiness
        Route::get('/playground', [BiometricDeviceController::class, 'playground'])->name('biometric.playground');
        Route::post('/playground/push', [BiometricDeviceController::class, 'simulatePush'])->name('biometric.playground.push');
        Route::get('/readiness', [BiometricDeviceController::class, 'readiness'])->name('biometric.readiness');
    });

    // Backward compatibility
    Route::get('/devices', [BiometricDeviceController::class, 'index'])->name('management.devices');

    // ==========================================
    // 📋 CLASSROOM ASSESSMENT MODULE (Exact match to demo)
    // ==========================================
    Route::prefix('assessment')->group(function () {
        Route::get('/dashboard', [AssessmentController::class, 'dashboard'])->name('assessment.dashboard');
        Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessment.assessments');
        Route::get('/assessments/create', [AssessmentController::class, 'create'])->name('assessment.assessments.create');
        Route::post('/assessments', [AssessmentController::class, 'store'])->name('assessment.assessments.store');
        Route::get('/assessments/{id}', [AssessmentController::class, 'show'])->name('assessment.assessments.show');
        Route::get('/assessments/{id}/edit', [AssessmentController::class, 'edit'])->name('assessment.assessments.edit');
        Route::match(['post', 'put'], '/assessments/{id}', [AssessmentController::class, 'update'])->name('assessment.assessments.update');
        Route::match(['post', 'delete'], '/assessments/{id}', [AssessmentController::class, 'destroy'])->name('assessment.assessments.destroy');

        // Sittings / Occurrences & Mark Entry
        Route::get('/occurrences/{id}/marks', [AssessmentController::class, 'marksEntry'])->name('assessment.occurrences.marks');
        Route::post('/occurrences/{id}/marks', [AssessmentController::class, 'saveMarks'])->name('assessment.occurrences.marks.save');
        Route::post('/occurrences/{id}/publish', [AssessmentController::class, 'publishOccurrence'])->name('assessment.occurrences.publish');
        Route::get('/occurrences/{id}/marksheet', [AssessmentController::class, 'marksheet'])->name('assessment.occurrences.marksheet');

        // Guide & Reports
        Route::get('/guide', [AssessmentController::class, 'guide'])->name('assessment.guide');
        Route::get('/reports', [AssessmentController::class, 'reports'])->name('assessment.reports');
        Route::get('/reports/ranking', [AssessmentController::class, 'ranking'])->name('assessment.reports.ranking');
        Route::get('/reports/student', [AssessmentController::class, 'studentReport'])->name('assessment.reports.student');
    });

    // Backward compatibility for assessments.index
    Route::get('/assessments-hub', [AssessmentController::class, 'dashboard'])->name('assessments.index');

    // ==========================================
    // 🗳️ SURVEYS & FEEDBACK MODULE (Exact match to demo)
    // ==========================================
    Route::prefix('survey')->group(function () {
        Route::get('/dashboard', [SurveyController::class, 'dashboard'])->name('survey.dashboard');
        Route::get('/', [SurveyController::class, 'index'])->name('survey.index');
        Route::get('/mine', [SurveyController::class, 'mine'])->name('survey.mine');
        Route::get('/templates', [SurveyController::class, 'templates'])->name('survey.templates');
        Route::post('/templates/{key}/use', [SurveyController::class, 'useTemplate'])->name('survey.templates.use');
        Route::get('/create', [SurveyController::class, 'create'])->name('survey.create');
        Route::post('/', [SurveyController::class, 'store'])->name('survey.store');
        Route::get('/guide', [SurveyController::class, 'guide'])->name('survey.guide');
        Route::get('/r/{token}', [SurveyController::class, 'respondView'])->name('survey.respond');
        Route::post('/r/{token}', [SurveyController::class, 'submitResponse'])->name('survey.respond.submit');

        Route::get('/{id}', [SurveyController::class, 'show'])->name('survey.show')->whereNumber('id');
        Route::get('/{id}/edit', [SurveyController::class, 'edit'])->name('survey.edit')->whereNumber('id');
        Route::match(['post', 'put'], '/{id}', [SurveyController::class, 'update'])->name('survey.update')->whereNumber('id');
        Route::match(['post', 'delete'], '/{id}', [SurveyController::class, 'destroy'])->name('survey.destroy')->whereNumber('id');
        Route::post('/{id}/publish', [SurveyController::class, 'publish'])->name('survey.publish')->whereNumber('id');
        Route::post('/{id}/close', [SurveyController::class, 'close'])->name('survey.close')->whereNumber('id');
        Route::get('/{id}/report', [SurveyController::class, 'report'])->name('survey.report')->whereNumber('id');
        Route::post('/{id}/report/publish', [SurveyController::class, 'publishReport'])->name('survey.report.publish')->whereNumber('id');
        Route::get('/{id}/report/export', [SurveyController::class, 'exportCsv'])->name('survey.report.export')->whereNumber('id');
    });

    // Feedback Triage
    Route::prefix('feedback')->group(function () {
        Route::get('/', [SurveyController::class, 'triageIndex'])->name('feedback.index');
        Route::get('/{id}', [SurveyController::class, 'triageShow'])->name('feedback.show');
        Route::match(['post', 'put'], '/{id}', [SurveyController::class, 'triageUpdate'])->name('feedback.update');
        Route::match(['post', 'delete'], '/{id}', [SurveyController::class, 'triageDestroy'])->name('feedback.destroy');
    });

    // ==========================================
    // 🛡️ COMPLIANCE & GOVERNANCE MODULE (Exact match to demo)
    // ==========================================
    Route::prefix('compliance')->group(function () {
        Route::get('/', [ComplianceController::class, 'overview'])->name('compliance.overview');
        Route::get('/profile', [ComplianceController::class, 'profile'])->name('compliance.profile');
        Route::match(['post', 'put'], '/profile', [ComplianceController::class, 'updateProfile'])->name('compliance.profile.update');

        Route::get('/packs', [ComplianceController::class, 'packs'])->name('compliance.packs');
        Route::post('/packs/install', [ComplianceController::class, 'installPack'])->name('compliance.packs.install');
        Route::post('/packs/uninstall', [ComplianceController::class, 'uninstallPack'])->name('compliance.packs.uninstall');
        Route::post('/packs/primary', [ComplianceController::class, 'makePrimaryPack'])->name('compliance.packs.primary');

        Route::get('/fields', [ComplianceController::class, 'fields'])->name('compliance.fields');
        Route::post('/fields', [ComplianceController::class, 'updateFields'])->name('compliance.fields.update');

        Route::get('/documents', [ComplianceController::class, 'documents'])->name('compliance.documents');
        Route::post('/documents', [ComplianceController::class, 'storeDocument'])->name('compliance.documents.store');
        Route::match(['post', 'delete'], '/documents/{id}', [ComplianceController::class, 'destroyDocument'])->name('compliance.documents.destroy');

        Route::get('/checklist', [ComplianceController::class, 'checklist'])->name('compliance.checklist');
        Route::get('/records', [ComplianceController::class, 'records'])->name('compliance.records');

        Route::get('/validator', [ComplianceController::class, 'validator'])->name('compliance.validator');
        Route::post('/validator/run', [ComplianceController::class, 'runValidator'])->name('compliance.validator.run');
        Route::post('/validator/waive', [ComplianceController::class, 'waiveFinding'])->name('compliance.validator.waive');
        Route::get('/validator/export', [ComplianceController::class, 'exportWorklist'])->name('compliance.validator.export');

        Route::get('/reports', [ComplianceController::class, 'reports'])->name('compliance.reports');
        Route::get('/reports/{type}/export', [ComplianceController::class, 'exportReport'])->name('compliance.reports.export');

        Route::get('/inspections', [ComplianceController::class, 'inspections'])->name('compliance.inspections');
        Route::match(['post', 'put'], '/inspections', [ComplianceController::class, 'saveInspections'])->name('compliance.inspections.save');

        Route::get('/calendar', [ComplianceController::class, 'calendar'])->name('compliance.calendar');
    });

    // Holistic Progress Card (HPC) Routes
    Route::prefix('hpc')->group(function () {
        // Dashboard
        Route::get('/dashboard', [HpcController::class, 'dashboard'])->name('hpc.dashboard');

        // Activities
        Route::get('/activities', [HpcController::class, 'activities'])->name('hpc.activities');
        Route::get('/activities/create', [HpcController::class, 'createActivity'])->name('hpc.activities.create');
        Route::post('/activities', [HpcController::class, 'storeActivity'])->name('hpc.activities.store');
        Route::get('/activities/{id}/mark', [HpcController::class, 'markActivity'])->name('hpc.activities.mark');
        Route::post('/activities/{id}/mark', [HpcController::class, 'saveActivityMark'])->name('hpc.activities.save-mark');
        Route::match(['post', 'delete'], '/activities/{id}', [HpcController::class, 'destroyActivity'])->name('hpc.activities.destroy');

        // Progress Cards
        Route::get('/cards', [HpcController::class, 'cards'])->name('hpc.cards');
        Route::get('/cards/create', [HpcController::class, 'createCards'])->name('hpc.cards.create');
        Route::post('/cards', [HpcController::class, 'storeCards'])->name('hpc.cards.store');
        Route::get('/cards/{id}', [HpcController::class, 'showCard'])->name('hpc.cards.show');
        Route::post('/cards/{id}/sections/{section}', [HpcController::class, 'updateCardSection'])->name('hpc.cards.update-section');
        Route::post('/cards/{id}/review', [HpcController::class, 'reviewCard'])->name('hpc.cards.review');
        Route::post('/cards/{id}/return', [HpcController::class, 'returnCard'])->name('hpc.cards.return');
        Route::post('/cards/{id}/publish', [HpcController::class, 'publishCard'])->name('hpc.cards.publish');
        Route::get('/cards/{id}/preview', [HpcController::class, 'previewCard'])->name('hpc.cards.preview');
        Route::get('/cards/{id}/colour', [HpcController::class, 'colourCard'])->name('hpc.cards.colour');

        // Frameworks / Editions
        Route::get('/frameworks', [HpcController::class, 'frameworks'])->name('hpc.frameworks');
        Route::get('/frameworks/{id}', [HpcController::class, 'showFramework'])->name('hpc.frameworks.show');
        Route::post('/frameworks/{id}/classes', [HpcController::class, 'saveFrameworkClasses'])->name('hpc.frameworks.classes');
        Route::match(['post', 'patch'], '/frameworks/{id}/toggle', [HpcController::class, 'toggleFramework'])->name('hpc.frameworks.toggle');

        // Card Appearance
        Route::get('/card-appearance', [HpcController::class, 'cardAppearance'])->name('hpc.card-appearance');
        Route::post('/card-appearance/edition-label', [HpcController::class, 'updateCardAppearance'])->name('hpc.card-appearance.edition-label');
        Route::post('/card-appearance/emblems', [HpcController::class, 'updateCardAppearance'])->name('hpc.card-appearance.emblem-store');
        Route::delete('/card-appearance/emblems', [HpcController::class, 'deleteCardEmblem'])->name('hpc.card-appearance.emblem-delete');
        Route::post('/card-appearance', [HpcController::class, 'updateCardAppearance'])->name('hpc.card-appearance.update');
    });

    // Classrooms Management Routes
    Route::prefix('classrooms')->name('classrooms.')->group(function () {
        // 1. Rooms
        Route::get('/rooms', [ClassroomController::class, 'rooms'])->name('rooms');
        Route::post('/rooms', [ClassroomController::class, 'storeRoom'])->name('rooms.store');
        Route::post('/rooms/quick-setup', [ClassroomController::class, 'quickSetup'])->name('rooms.quick-setup');
        Route::post('/quick-setup', [ClassroomController::class, 'quickSetup']);
        Route::post('/rooms/import-halls', [ClassroomController::class, 'importHalls'])->name('rooms.import-halls');
        Route::match(['post', 'put', 'patch'], '/rooms/{id}', [ClassroomController::class, 'updateRoom'])->name('rooms.update');
        Route::delete('/rooms/{id}', [ClassroomController::class, 'destroyRoom'])->name('rooms.destroy');

        // 2. Buildings & Blocks
        Route::get('/buildings', [ClassroomController::class, 'buildings'])->name('buildings');
        Route::post('/buildings', [ClassroomController::class, 'storeBuilding'])->name('buildings.store');
        Route::match(['post', 'put', 'patch'], '/buildings/{id}', [ClassroomController::class, 'updateBuilding'])->name('buildings.update');
        Route::delete('/buildings/{id}', [ClassroomController::class, 'destroyBuilding'])->name('buildings.destroy');

        // 3. Room Label Matching
        Route::get('/labels', [ClassroomController::class, 'labels'])->name('labels');
        Route::post('/labels/map', [ClassroomController::class, 'mapLabel'])->name('labels.map');
        Route::post('/labels/match', [ClassroomController::class, 'mapLabel']);

        // 4. Room Plan
        Route::get('/plan', [ClassroomController::class, 'plan'])->name('plan');
        Route::post('/plan', [ClassroomController::class, 'savePlan'])->name('plan.save');
        Route::post('/plan/save', [ClassroomController::class, 'savePlan']);
        Route::post('/plan/temporary', [ClassroomController::class, 'saveTemporaryAllocation'])->name('plan.temporary');
        Route::post('/plan/temporary-move', [ClassroomController::class, 'saveTemporaryAllocation']);

        // 5. Capacity Report
        Route::get('/plan/capacity', [ClassroomController::class, 'capacity'])->name('plan.capacity');

        // 6. Room Timetable
        Route::get('/timetable', [ClassroomController::class, 'timetable'])->name('timetable');

        // 7. Free Rooms
        Route::get('/free-rooms', [ClassroomController::class, 'freeRooms'])->name('free-rooms');

        // 8. Room Usage
        Route::get('/usage', [ClassroomController::class, 'usage'])->name('usage');

        // 9. Room Clashes
        Route::get('/clashes', [ClassroomController::class, 'clashes'])->name('clashes');

        // 10. Room Bookings
        Route::get('/bookings', [ClassroomController::class, 'bookings'])->name('bookings');
        Route::post('/bookings', [ClassroomController::class, 'storeBooking'])->name('bookings.store');
        Route::post('/bookings/settings', [ClassroomController::class, 'updateBookingSettings'])->name('bookings.settings');
        Route::post('/bookings/{id}/status', [ClassroomController::class, 'updateBookingStatus'])->name('bookings.status');
        Route::post('/bookings/{id}/approve', function(Illuminate\Http\Request $request, $id) {
            $request->merge(['status' => 'Approved']);
            return app(App\Http\Controllers\ClassroomController::class)->updateBookingStatus($request, $id);
        })->name('bookings.approve');
        Route::post('/bookings/{id}/reject', function(Illuminate\Http\Request $request, $id) {
            $request->merge(['status' => 'Rejected']);
            return app(App\Http\Controllers\ClassroomController::class)->updateBookingStatus($request, $id);
        })->name('bookings.reject');

        // 11. Room Maintenance
        Route::get('/maintenance', [ClassroomController::class, 'maintenance'])->name('maintenance');
        Route::post('/maintenance', [ClassroomController::class, 'storeMaintenance'])->name('maintenance.store');
        Route::match(['post', 'put'], '/maintenance/{id}', [ClassroomController::class, 'updateMaintenance'])->name('maintenance.update');

        // 12. Guide
        Route::get('/guide', [ClassroomController::class, 'guide'])->name('guide');
    });

    // =========================================================================
    // 🔥 COMPLETE MODULES & FACILITIES SUITE (19 MODULES LIVE DEMO MATCH)
    // =========================================================================
    Route::prefix('modules')->name('modules.')->group(function () {
        Route::get('/live-classes', [ModulesController::class, 'liveClasses'])->name('live-classes');
        Route::post('/live-classes/store', [ModulesController::class, 'storeLiveClass'])->name('live-classes.store');
        Route::get('/apps', [ModulesController::class, 'appsCenter'])->name('apps');
        Route::get('/study-center', [ModulesController::class, 'studyCenter'])->name('study-center');
        Route::get('/certificates', [ModulesController::class, 'certificates'])->name('certificates');
        Route::get('/id-cards', [ModulesController::class, 'idCards'])->name('id-cards');
        Route::get('/communicate', [ModulesController::class, 'communicate'])->name('communicate');
        Route::get('/library', [ModulesController::class, 'library'])->name('library');
        Route::get('/inventory', [ModulesController::class, 'inventory'])->name('inventory');
        Route::get('/transport', [ModulesController::class, 'transport'])->name('transport');
        Route::get('/hostel', [ModulesController::class, 'hostel'])->name('hostel');
        Route::get('/help-center', [ModulesController::class, 'helpCenter'])->name('help-center');
        Route::get('/assets', [ModulesController::class, 'assets'])->name('assets');
        Route::get('/engagement', [ModulesController::class, 'engagement'])->name('engagement');
        Route::get('/cctv', [ModulesController::class, 'cctv'])->name('cctv');
        Route::get('/alumni', [ModulesController::class, 'alumni'])->name('alumni');
        Route::get('/labs', [ModulesController::class, 'labs'])->name('labs');
        Route::get('/canteen', [ModulesController::class, 'canteen'])->name('canteen');
        Route::get('/courses', [ModulesController::class, 'courses'])->name('courses');
        Route::get('/reports', [ModulesController::class, 'reports'])->name('reports');
    });

    // Direct Live Demo Aliases - Study Center
    Route::prefix('study-center')->name('study-center.')->group(function () {
        Route::get('/', [ModulesController::class, 'studyCenter'])->name('index');
        Route::get('/dashboard', [ModulesController::class, 'studyCenter'])->name('dashboard');
        
        // Classwork
        Route::get('/classwork', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->studyCenter($request, 'classwork');
        })->name('classwork');
        Route::get('/classwork/create', [ModulesController::class, 'createClasswork'])->name('classwork.create');
        Route::post('/classwork', [ModulesController::class, 'storeClasswork'])->name('classwork.store');
        Route::delete('/classwork/{id}', [ModulesController::class, 'deleteClasswork'])->name('classwork.delete');

        // Syllabus
        Route::get('/syllabus', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->studyCenter($request, 'syllabus');
        })->name('syllabus');
        Route::post('/syllabus', [ModulesController::class, 'storeSyllabus'])->name('syllabus.store');
        Route::post('/syllabus/{id}/toggle', [ModulesController::class, 'toggleSyllabusStatus'])->name('syllabus.toggle');
        Route::delete('/syllabus/{id}', [ModulesController::class, 'deleteSyllabus'])->name('syllabus.delete');

        // Materials
        Route::get('/materials', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->studyCenter($request, 'materials');
        })->name('materials');
        Route::post('/materials', [ModulesController::class, 'storeMaterial'])->name('materials.store');
        Route::delete('/materials/{id}', [ModulesController::class, 'deleteMaterial'])->name('materials.delete');

        // Homework
        Route::get('/homework', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->studyCenter($request, 'homework');
        })->name('homework');
        Route::get('/homework/create', [ModulesController::class, 'createHomework'])->name('homework.create');
        Route::post('/homework', [ModulesController::class, 'storeHomework'])->name('homework.store');
        Route::delete('/homework/{id}', [ModulesController::class, 'deleteHomework'])->name('homework.delete');
        Route::get('/homework/{id}/submissions', [ModulesController::class, 'homeworkSubmissions'])->name('homework.submissions');
        Route::post('/homework/submissions/{id}/evaluate', [ModulesController::class, 'evaluateHomeworkSubmission'])->name('homework.submissions.evaluate');

        // Guide
        Route::get('/guide', [ModulesController::class, 'studyGuide'])->name('guide');

        // Live Classes link
        Route::get('/live-classes', [ModulesController::class, 'liveClasses'])->name('live-classes');
        Route::get('/live-classes/settings', function() {
            return redirect()->route('modules.live-classes', ['tab' => 'settings']);
        });
    });

    Route::get('/apps', [ModulesController::class, 'appsCenter']);

    Route::get('/certificate-templates', [ModulesController::class, 'certificates']);
    Route::get('/generate-document', function() { return redirect()->route('modules.certificates', ['tab' => 'generate']); });

    // Direct Live Demo Aliases - ID Cards
    Route::prefix('id-cards')->name('id-cards.')->group(function () {
        Route::get('/', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->idCards($request, 'students');
        })->name('index');
        Route::get('/students', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->idCards($request, 'students');
        })->name('students');
        Route::post('/students/generate', [ModulesController::class, 'generateStudentIdCards'])->name('students.generate');

        Route::get('/staff', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->idCards($request, 'staff');
        })->name('staff');
        Route::post('/staff/generate', [ModulesController::class, 'generateStaffIdCards'])->name('staff.generate');

        Route::get('/designs', function(\Illuminate\Http\Request $request) {
            return app(ModulesController::class)->idCards($request, 'designs');
        })->name('designs');
        Route::post('/templates/store', [ModulesController::class, 'storeIdCardTemplate'])->name('templates.store');
        Route::post('/templates/{id}/default', [ModulesController::class, 'setDefaultTemplate'])->name('templates.default');
        Route::delete('/templates/{id}', [ModulesController::class, 'deleteIdCardTemplate'])->name('templates.delete');
    });

    Route::get('/staff-id-card', function(\Illuminate\Http\Request $request) {
        return app(ModulesController::class)->idCards($request, 'staff');
    })->name('staff.id-card');

    Route::get('/notices', [ModulesController::class, 'communicate']);
    Route::get('/events', function() { return redirect()->route('modules.communicate', ['tab' => 'events']); });
    Route::get('/communicate/broadcast', function() { return redirect()->route('modules.communicate', ['tab' => 'broadcast_history']); });
    Route::get('/communicate/broadcast/create', function() { return redirect()->route('modules.communicate', ['tab' => 'broadcast_create']); });
    Route::get('/gallery', function() { return redirect()->route('modules.communicate', ['tab' => 'gallery']); });

    Route::get('/library/dashboard', [ModulesController::class, 'library']);
    Route::get('/library/issue-return', function() { return redirect()->route('modules.library', ['tab' => 'issue-return']); });
    Route::get('/library/books', function() { return redirect()->route('modules.library', ['tab' => 'books']); });
    Route::get('/library/categories', function() { return redirect()->route('modules.library', ['tab' => 'categories']); });

    Route::get('/inventory/dashboard', [ModulesController::class, 'inventory']);
    Route::get('/inventory/issue-item', function() { return redirect()->route('modules.inventory', ['tab' => 'issue-item']); });
    Route::get('/inventory/add-stock', function() { return redirect()->route('modules.inventory', ['tab' => 'add-stock']); });
    Route::get('/inventory/items', function() { return redirect()->route('modules.inventory', ['tab' => 'items']); });
    Route::get('/inventory/categories', function() { return redirect()->route('modules.inventory', ['tab' => 'categories']); });
    Route::get('/inventory/suppliers', function() { return redirect()->route('modules.inventory', ['tab' => 'suppliers']); });
    Route::get('/inventory/sales/create', function() { return redirect()->route('modules.inventory', ['tab' => 'pos']); });
    Route::get('/inventory/sales', function() { return redirect()->route('modules.inventory', ['tab' => 'sales']); });
    Route::get('/inventory/purchase-orders', function() { return redirect()->route('modules.inventory', ['tab' => 'purchase-orders']); });
    Route::get('/inventory/grns', function() { return redirect()->route('modules.inventory', ['tab' => 'grns']); });
    Route::get('/inventory/supplier-payments', function() { return redirect()->route('modules.inventory', ['tab' => 'payments']); });

    Route::get('/transport/dashboard', [ModulesController::class, 'transport']);
    Route::get('/transport/vehicles', function() { return redirect()->route('modules.transport', ['tab' => 'vehicles']); });
    Route::get('/transport/live', function() { return redirect()->route('modules.transport', ['tab' => 'live']); });
    Route::get('/transport/routes', function() { return redirect()->route('modules.transport', ['tab' => 'routes']); });
    Route::get('/transport/tracking', function() { return redirect()->route('modules.transport', ['tab' => 'tracking']); });
    Route::get('/transport/drivers', function() { return redirect()->route('modules.transport', ['tab' => 'drivers']); });

    Route::get('/hostel/dashboard', [ModulesController::class, 'hostel']);
    Route::get('/hostel/allocations', function() { return redirect()->route('modules.hostel', ['tab' => 'allocations']); });
    Route::get('/hostel/rooms', function() { return redirect()->route('modules.hostel', ['tab' => 'rooms']); });
    Route::get('/hostel/room-types', function() { return redirect()->route('modules.hostel', ['tab' => 'room-types']); });
    Route::get('/hostel/hostels', function() { return redirect()->route('modules.hostel', ['tab' => 'hostels']); });

    Route::get('/knowledge-base/categories', [ModulesController::class, 'helpCenter']);
    Route::get('/knowledge-base/articles', function() { return redirect()->route('modules.help-center', ['tab' => 'articles']); });
    Route::get('/knowledge-base/chatbot', function() { return redirect()->route('modules.help-center', ['tab' => 'chatbot']); });

    Route::get('/assets/dashboard', [ModulesController::class, 'assets']);
    Route::get('/assets/assets', function() { return redirect()->route('modules.assets', ['tab' => 'register']); });
    Route::get('/assets/categories', function() { return redirect()->route('modules.assets', ['tab' => 'categories']); });
    Route::get('/assets/assignments', function() { return redirect()->route('modules.assets', ['tab' => 'assignments']); });
    Route::get('/assets/depreciation', function() { return redirect()->route('modules.assets', ['tab' => 'depreciation']); });
    Route::get('/assets/maintenance', function() { return redirect()->route('modules.assets', ['tab' => 'maintenance']); });
    Route::get('/assets/disposals', function() { return redirect()->route('modules.assets', ['tab' => 'disposals']); });
    Route::get('/assets/audits', function() { return redirect()->route('modules.assets', ['tab' => 'audits']); });
    Route::get('/assets/reports', function() { return redirect()->route('modules.assets', ['tab' => 'reports']); });

    Route::get('/engagement/designs', [ModulesController::class, 'engagement']);
    Route::get('/engagement/birthdays', function() { return redirect()->route('modules.engagement', ['tab' => 'birthdays']); });
    Route::get('/engagement/festivals', function() { return redirect()->route('modules.engagement', ['tab' => 'festivals']); });
    Route::get('/engagement/settings', function() { return redirect()->route('modules.engagement', ['tab' => 'settings']); });

    Route::get('/cctv/wall', [ModulesController::class, 'cctv']);
    Route::get('/cctv/cameras', function() { return redirect()->route('modules.cctv', ['tab' => 'cameras']); });
    Route::get('/cctv/logs', function() { return redirect()->route('modules.cctv', ['tab' => 'logs']); });

    Route::get('/alumni/dashboard', [ModulesController::class, 'alumni']);
    Route::get('/alumni', function() { return redirect()->route('modules.alumni', ['tab' => 'directory']); });
    Route::get('/alumni/guide', function() { return redirect()->route('modules.alumni', ['tab' => 'guide']); });
    Route::get('/alumni/events', function() { return redirect()->route('modules.alumni', ['tab' => 'events']); });
    Route::get('/alumni/checkin', function() { return redirect()->route('modules.alumni', ['tab' => 'checkin']); });

    Route::get('/labs', [ModulesController::class, 'labs']);
    Route::get('/labs/sessions', function() { return redirect()->route('modules.labs', ['tab' => 'sessions']); });
    Route::get('/labs/incidents', function() { return redirect()->route('modules.labs', ['tab' => 'incidents']); });
    Route::get('/labs/guide', function() { return redirect()->route('modules.labs', ['tab' => 'guide']); });
    Route::get('/labs/issues', function() { return redirect()->route('modules.labs', ['tab' => 'issues']); });
    Route::get('/labs/batches', function() { return redirect()->route('modules.labs', ['tab' => 'batches']); });
    Route::get('/labs/reports', function() { return redirect()->route('modules.labs', ['tab' => 'reports']); });

    Route::get('/canteen', [ModulesController::class, 'canteen']);
    Route::get('/canteen/pos', function() { return redirect()->route('modules.canteen', ['tab' => 'pos']); });
    Route::get('/canteen/wallets', function() { return redirect()->route('modules.canteen', ['tab' => 'wallets']); });
    Route::get('/canteen/transactions', function() { return redirect()->route('modules.canteen', ['tab' => 'transactions']); });
    Route::get('/canteen/topups', function() { return redirect()->route('modules.canteen', ['tab' => 'topups']); });
    Route::get('/canteen/products', function() { return redirect()->route('modules.canteen', ['tab' => 'products']); });
    Route::get('/canteen/stock', function() { return redirect()->route('modules.canteen', ['tab' => 'stock']); });
    Route::get('/canteen/outlets', function() { return redirect()->route('modules.canteen', ['tab' => 'outlets']); });
    Route::get('/canteen/shifts', function() { return redirect()->route('modules.canteen', ['tab' => 'shifts']); });
    Route::get('/canteen/refunds', function() { return redirect()->route('modules.canteen', ['tab' => 'refunds']); });
    Route::get('/canteen/leavers', function() { return redirect()->route('modules.canteen', ['tab' => 'leavers']); });
    Route::get('/canteen/reports', function() { return redirect()->route('modules.canteen', ['tab' => 'reports']); });
    Route::get('/canteen/accounts', function() { return redirect()->route('modules.canteen', ['tab' => 'accounts']); });
    Route::get('/canteen/settlements', function() { return redirect()->route('modules.canteen', ['tab' => 'settlements']); });
    Route::get('/canteen/settings', function() { return redirect()->route('modules.canteen', ['tab' => 'settings']); });
    Route::get('/canteen/guide', function() { return redirect()->route('modules.canteen', ['tab' => 'guide']); });
    Route::get('/canteen/my-wallet', function() { return redirect()->route('modules.canteen', ['tab' => 'my-wallet']); });

    Route::get('/courses/dashboard', [ModulesController::class, 'courses']);
    Route::get('/courses', function() { return redirect()->route('modules.courses', ['tab' => 'courses']); });
    Route::get('/courses/marking', function() { return redirect()->route('modules.courses', ['tab' => 'marking']); });
    Route::get('/courses/learn', function() { return redirect()->route('modules.courses', ['tab' => 'learn']); });
    Route::get('/courses/reports', function() { return redirect()->route('modules.courses', ['tab' => 'reports']); });
    Route::get('/courses/certificates', function() { return redirect()->route('modules.courses', ['tab' => 'certificates']); });
    Route::get('/courses/orders', function() { return redirect()->route('modules.courses', ['tab' => 'orders']); });
    Route::get('/courses/coupons', function() { return redirect()->route('modules.courses', ['tab' => 'coupons']); });
    Route::get('/courses/categories', function() { return redirect()->route('modules.courses', ['tab' => 'categories']); });
    Route::get('/courses/settings', function() { return redirect()->route('modules.courses', ['tab' => 'settings']); });
    Route::get('/courses/guide', function() { return redirect()->route('modules.courses', ['tab' => 'guide']); });

    Route::get('/reports', [ModulesController::class, 'reports']);
});
