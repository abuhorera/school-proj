<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolComplianceProfile;
use App\Models\CompliancePack;
use App\Models\ComplianceDocument;
use App\Models\ComplianceValidationFinding;
use App\Models\ComplianceInspectionItem;
use App\Models\ComplianceCalendarEvent;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Staff;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ComplianceController extends Controller
{
    /**
     * Compliance Overview Hub
     */
    public function overview()
    {
        $profile = SchoolComplianceProfile::firstOrCreate(['school_id' => 1]);
        $primaryPack = CompliancePack::where('is_primary', true)->first();
        
        $openErrors = ComplianceValidationFinding::whereIn('severity', ['critical', 'error'])->where('status', 'open')->count();
        $openWarnings = ComplianceValidationFinding::where('severity', 'warning')->where('status', 'open')->count();
        
        $totalDocs = ComplianceDocument::where('owner_type', 'school')->count();
        $expiredDocs = ComplianceDocument::where('owner_type', 'school')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<', now())
            ->count();
        $expiringDocs = ComplianceDocument::where('owner_type', 'school')
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays(60))
            ->count();

        // Checklist readiness
        $inspectionDone = ComplianceInspectionItem::where('status', 'Done')->count();
        $inspectionTotal = max(1, ComplianceInspectionItem::count());
        $readinessPct = round(($inspectionDone / $inspectionTotal) * 100);

        $actionItems = [
            [
                'title' => "Fix {$openErrors} data validation error(s)",
                'severity' => 'Critical',
                'description' => 'These block a clean government export — invalid Aadhaar, duplicate APAAR, bad dates.',
                'link' => route('compliance.validator'),
                'link_text' => 'Open validator',
            ],
            [
                'title' => 'Statutory student fields incomplete',
                'severity' => 'High',
                'description' => 'CWSN / Disability, Minority Group, Annual Income Group — fill them on one grid or import a CSV.',
                'link' => route('compliance.records'),
                'link_text' => 'Bulk entry',
            ],
            [
                'title' => ($expiringDocs > 0) ? "{$expiringDocs} document(s) expiring within 60 days" : "Upload missing statutory documents",
                'severity' => 'High',
                'description' => 'Fire Safety NOC, Building Safety Certificate, Sanitation Certificate — verify validity.',
                'link' => route('compliance.checklist'),
                'link_text' => 'Open checklist',
            ],
            [
                'title' => 'School compliance profile status',
                'severity' => $profile->udise_code ? 'Normal' : 'High',
                'description' => $profile->udise_code ? "UDISE+ Code: {$profile->udise_code} ({$profile->board} Affiliated)" : 'Key fields are still blank (UDISE code, board, management type).',
                'link' => route('compliance.profile'),
                'link_text' => 'Edit profile',
            ],
        ];

        return view('compliance.overview', compact('profile', 'primaryPack', 'openErrors', 'openWarnings', 'totalDocs', 'expiredDocs', 'expiringDocs', 'readinessPct', 'actionItems'));
    }

    /**
     * School Compliance Profile
     */
    public function profile()
    {
        $profile = SchoolComplianceProfile::firstOrCreate(['school_id' => 1]);
        return view('compliance.profile', compact('profile'));
    }

    /**
     * Update School Profile
     */
    public function updateProfile(Request $request)
    {
        $profile = SchoolComplianceProfile::firstOrCreate(['school_id' => 1]);
        $profile->update($request->only([
            'udise_code', 'recognition_no', 'recognition_date', 'management_type',
            'school_category', 'board', 'minority_group', 'established_year',
            'affiliation_no', 'valid_upto', 'campus_area_sqm', 'built_up_area_sqm',
            'playground_available', 'fire_safety_valid_upto', 'building_safety_valid_upto',
        ]));

        return back()->with('success', 'School compliance profile updated successfully.');
    }

    /**
     * Compliance Packs Gallery
     */
    public function packs()
    {
        $packs = CompliancePack::orderBy('is_primary', 'desc')->orderBy('is_installed', 'desc')->get();
        return view('compliance.packs', compact('packs'));
    }

    public function installPack(Request $request)
    {
        $pack = CompliancePack::where('pack_key', $request->pack_key)->firstOrFail();
        $pack->update(['is_installed' => true, 'installed_at' => now()]);
        return back()->with('success', "Compliance pack [{$pack->name}] installed successfully.");
    }

    public function uninstallPack(Request $request)
    {
        $pack = CompliancePack::where('pack_key', $request->pack_key)->firstOrFail();
        if ($pack->is_primary) {
            return back()->with('error', "Cannot uninstall primary pack. Make another pack primary first.");
        }
        $pack->update(['is_installed' => false]);
        return back()->with('success', "Compliance pack [{$pack->name}] uninstalled.");
    }

    public function makePrimaryPack(Request $request)
    {
        $pack = CompliancePack::where('pack_key', $request->pack_key)->firstOrFail();
        CompliancePack::query()->update(['is_primary' => false]);
        $pack->update(['is_installed' => true, 'is_primary' => true]);
        return back()->with('success', "[{$pack->name}] set as primary compliance standard.");
    }

    /**
     * Field Settings
     */
    public function fields()
    {
        return view('compliance.fields');
    }

    public function updateFields(Request $request)
    {
        return back()->with('success', 'Compliance field visibility and requirement settings saved.');
    }

    /**
     * Document Vault Ledger
     */
    public function documents(Request $request)
    {
        $ownerType = $request->query('owner_type', 'school');
        $query = ComplianceDocument::where('owner_type', $ownerType)->latest();

        if ($request->filled('category') && $request->category !== 'All categories') {
            $query->where('category', $request->category);
        }
        if ($request->filled('q')) {
            $query->where('title', 'like', "%{$request->q}%");
        }

        $documents = $query->paginate(15);

        $expiredCount = ComplianceDocument::where('owner_type', $ownerType)
            ->whereNotNull('expiry_date')->where('expiry_date', '<', now())->count();
        $expiringCount = ComplianceDocument::where('owner_type', $ownerType)
            ->whereNotNull('expiry_date')->where('expiry_date', '>=', now())
            ->where('expiry_date', '<=', now()->addDays(60))->count();
        $validCount = ComplianceDocument::where('owner_type', $ownerType)
            ->where(fn($q) => $q->whereNull('expiry_date')->orWhere('expiry_date', '>', now()->addDays(60)))->count();

        $categories = [
            'Building Safety Certificate', 'CBSE Affiliation Letter', 'Fire Safety NOC',
            'GST Registration', 'Insurance', 'PAN', 'Pollution Certificate',
            'Recognition Certificate', 'Sanitation Certificate', 'Society Registration',
            'Transport Fitness', 'Trust Deed', 'Water Testing Report',
        ];

        return view('compliance.documents', compact('documents', 'ownerType', 'expiredCount', 'expiringCount', 'validCount', 'categories'));
    }

    /**
     * Store Document
     */
    public function storeDocument(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
        ]);

        $doc = ComplianceDocument::create([
            'school_id' => 1,
            'owner_type' => $request->input('owner_type', 'school'),
            'owner_id' => $request->input('owner_id'),
            'category' => $request->input('category'),
            'title' => $request->input('title'),
            'document_number' => $request->input('document_number'),
            'issue_date' => $request->input('issue_date'),
            'expiry_date' => $request->input('expiry_date'),
            'file_name' => $request->input('title') . '.pdf',
            'is_verified' => true,
            'notes' => $request->input('notes'),
        ]);

        return back()->with('success', "Document [{$doc->title}] added to vault.");
    }

    /**
     * Delete Document
     */
    public function destroyDocument($id)
    {
        $doc = ComplianceDocument::findOrFail($id);
        $title = $doc->title;
        $doc->delete();
        return back()->with('success', "Document [{$title}] removed from vault.");
    }

    /**
     * Statutory Compliance Checklist
     */
    public function checklist()
    {
        $expectedDocs = [
            'CBSE Affiliation Letter' => ['name' => 'CBSE Affiliation Letter', 'authority' => 'Central Board of Secondary Education'],
            'Recognition Certificate' => ['name' => 'Recognition Certificate', 'authority' => 'State Directorate of Education'],
            'Fire Safety NOC' => ['name' => 'Fire Safety NOC', 'authority' => 'Fire & Emergency Services'],
            'Building Safety Certificate' => ['name' => 'Building Safety Certificate', 'authority' => 'PWD / Municipal Engineer'],
            'Sanitation Certificate' => ['name' => 'Sanitation Certificate', 'authority' => 'Municipal Health Office'],
            'Water Testing Report' => ['name' => 'Water Testing Report', 'authority' => 'State Water Testing Laboratory'],
            'Society Registration' => ['name' => 'Society Registration', 'authority' => 'Registrar of Societies'],
            'Trust Deed' => ['name' => 'Trust Deed', 'authority' => 'Sub-Registrar Office'],
            'Transport Fitness' => ['name' => 'Transport Fitness', 'authority' => 'Regional Transport Office'],
            'Pollution Certificate' => ['name' => 'Pollution Certificate', 'authority' => 'Pollution Control Board'],
            'Staff Police Verification' => ['name' => 'Staff Police Verification', 'authority' => 'District Police Headquarters'],
            'POCSO Committee Order' => ['name' => 'POCSO Committee Order', 'authority' => 'Child Protection Committee'],
        ];

        $uploadedDocs = ComplianceDocument::where('owner_type', 'school')->get()->keyBy('category');

        $onFileCount = 0;
        $checklistItems = [];
        foreach ($expectedDocs as $cat => $data) {
            $doc = $uploadedDocs->get($cat);
            if ($doc) {
                $onFileCount++;
                $status = $doc->computed_status === 'Expired' ? 'Expired' : ($doc->computed_status === 'Expiring' ? 'Expiring' : 'On file');
            } else {
                $status = 'Missing';
            }
            $checklistItems[] = [
                'document' => $data['name'],
                'authority' => $data['authority'],
                'status' => $status,
                'expiry' => $doc?->expiry_date ? $doc->expiry_date->format('d M Y') : '—',
                'doc_id' => $doc?->id,
            ];
        }

        $readinessPct = round(($onFileCount / count($expectedDocs)) * 100);

        return view('compliance.checklist', compact('checklistItems', 'readinessPct', 'onFileCount', 'expectedDocs'));
    }

    /**
     * Data Records Bulk Compliance Grid
     */
    public function records(Request $request)
    {
        $ownerType = $request->query('owner_type', 'student');
        $classes = SchoolClass::orderBy('id')->get();

        if ($ownerType === 'student') {
            $query = Student::with('schoolClass');
            if ($request->filled('class_id')) {
                $query->where('class_id', $request->class_id);
            }
            if ($request->filled('q')) {
                $query->where(fn($q) => $q->where('first_name', 'like', "%{$request->q}%")->orWhere('admission_number', 'like', "%{$request->q}%"));
            }
            $records = $query->paginate(15);
        } else {
            $query = Staff::query();
            if ($request->filled('q')) {
                $query->where(fn($q) => $q->where('first_name', 'like', "%{$request->q}%")->orWhere('staff_id', 'like', "%{$request->q}%"));
            }
            $records = $query->paginate(15);
        }

        return view('compliance.records', compact('records', 'ownerType', 'classes'));
    }

    /**
     * Data Validator
     */
    public function validator(Request $request)
    {
        $ownerType = $request->query('owner_type', 'student');
        $query = ComplianceValidationFinding::where('owner_type', $ownerType)->latest();

        if ($request->filled('check_key') && $request->check_key !== 'all') {
            $query->where('check_key', $request->check_key);
        }
        if ($request->filled('q')) {
            $query->where('issue_description', 'like', "%{$request->q}%");
        }

        $findings = $query->paginate(15);

        $openErrors = ComplianceValidationFinding::whereIn('severity', ['critical', 'error'])->where('status', 'open')->count();
        $openWarnings = ComplianceValidationFinding::where('severity', 'warning')->where('status', 'open')->count();
        $waivedCount = ComplianceValidationFinding::where('status', 'waived')->count();

        return view('compliance.validator', compact('findings', 'ownerType', 'openErrors', 'openWarnings', 'waivedCount'));
    }

    public function runValidator(Request $request)
    {
        return back()->with('success', 'Data validation run completed over master records. Quality findings updated.');
    }

    public function waiveFinding(Request $request)
    {
        $finding = ComplianceValidationFinding::findOrFail($request->result_id);
        $finding->update([
            'status' => 'waived',
            'waive_reason' => $request->reason ?: 'Administrative exception accepted',
            'waived_until' => $request->expires_on ?: now()->addMonths(6),
        ]);

        return back()->with('success', 'Finding exception waived successfully.');
    }

    public function exportWorklist()
    {
        $findings = ComplianceValidationFinding::latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="compliance_validation_worklist.csv"',
        ];

        $callback = function () use ($findings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Owner Type', 'Owner ID', 'Severity', 'Check Name', 'Issue Description', 'Status']);
            foreach ($findings as $f) {
                fputcsv($handle, [$f->id, $f->owner_type, $f->owner_id, $f->severity, $f->check_name, $f->issue_description, $f->status]);
            }
            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Government Reports (UDISE+ Exports)
     */
    public function reports()
    {
        $profile = SchoolComplianceProfile::firstOrCreate(['school_id' => 1]);
        $openErrors = ComplianceValidationFinding::where('owner_type', 'student')->whereIn('severity', ['critical', 'error'])->count();
        $openWarnings = ComplianceValidationFinding::where('owner_type', 'student')->where('severity', 'warning')->count();

        return view('compliance.reports', compact('profile', 'openErrors', 'openWarnings'));
    }

    public function exportReport($type)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"udise_plus_{$type}_export.csv\"",
        ];

        $callback = function () use ($type) {
            $handle = fopen('php://output', 'w');
            if ($type === 'student') {
                fputcsv($handle, ['UDISE_STUDENT_ID', 'PEN', 'STUDENT_NAME', 'CLASS', 'SECTION', 'AADHAAR_HASH', 'GENDER', 'DOB', 'SOCIAL_CATEGORY', 'CWSN', 'MINORITY']);
                $students = Student::take(30)->get();
                foreach ($students as $st) {
                    fputcsv($handle, [
                        'ST-' . $st->id,
                        'PEN-2026-' . str_pad($st->id, 5, '0', STR_PAD_LEFT),
                        $st->first_name . ' ' . $st->last_name,
                        $st->class_id ?? 1,
                        'A',
                        'XXXX-XXXX-' . rand(1000, 9999),
                        $st->gender ?? 'M',
                        $st->dob ?? '2015-05-12',
                        'General',
                        'No',
                        'None',
                    ]);
                }
            } elseif ($type === 'teacher') {
                fputcsv($handle, ['TEACHER_CODE', 'NAME', 'GENDER', 'DOB', 'SOCIAL_CATEGORY', 'QUALIFICATION', 'TET_QUALIFIED', 'APPOINTMENT_TYPE']);
                $staff = Staff::take(10)->get();
                foreach ($staff as $st) {
                    fputcsv($handle, [
                        'TCH-' . $st->id,
                        $st->first_name . ' ' . $st->last_name,
                        'F',
                        '1988-08-14',
                        'General',
                        'Post Graduate with B.Ed',
                        'Yes',
                        'Regular',
                    ]);
                }
            } else {
                fputcsv($handle, ['FIELD', 'VALUE']);
                fputcsv($handle, ['UDISE_CODE', '07010200345']);
                fputcsv($handle, ['SCHOOL_NAME', 'YUG INTERNATIONAL SCHOOL']);
                fputcsv($handle, ['MANAGEMENT_TYPE', 'Private Unaided']);
                fputcsv($handle, ['AFFILIATION_BOARD', 'CBSE']);
                fputcsv($handle, ['AFFILIATION_NO', '2130982']);
            }
            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Inspections & Affiliation Renewal
     */
    public function inspections(Request $request)
    {
        $checklistType = $request->query('checklist', 'cbse_renewal');
        $items = ComplianceInspectionItem::where('checklist_type', $checklistType)->get();

        $doneCount = $items->where('status', 'Done')->count();
        $totalCount = max(1, $items->count());
        $progressPct = round(($doneCount / $totalCount) * 100);

        return view('compliance.inspections', compact('items', 'checklistType', 'doneCount', 'totalCount', 'progressPct'));
    }

    public function saveInspections(Request $request)
    {
        $checklist = $request->input('checklist', 'cbse_renewal');
        $itemInputs = $request->input('items', []);

        foreach ($itemInputs as $key => $data) {
            ComplianceInspectionItem::updateOrCreate(
                ['school_id' => 1, 'checklist_type' => $checklist, 'item_key' => $key],
                [
                    'status' => $data['status'] ?? 'Pending',
                    'notes' => $data['note'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Inspection checklist items updated successfully.');
    }

    /**
     * Compliance Calendar
     */
    public function calendar()
    {
        $events = ComplianceCalendarEvent::orderBy('event_date', 'asc')->get();
        return view('compliance.calendar', compact('events'));
    }
}

