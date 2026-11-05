<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolComplianceProfile;
use App\Models\CompliancePack;
use App\Models\ComplianceDocument;
use App\Models\ComplianceValidationFinding;
use App\Models\ComplianceInspectionItem;
use App\Models\ComplianceCalendarEvent;
use App\Models\Student;
use App\Models\Staff;

class ComplianceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. School Compliance Profile
        SchoolComplianceProfile::updateOrCreate(
            ['school_id' => 1],
            [
                'udise_code' => '07010200345',
                'recognition_no' => 'EDN/REC/2018/8921',
                'recognition_date' => '2018-04-12',
                'management_type' => 'Private Unaided',
                'school_category' => 'Higher Secondary (I–XII)',
                'board' => 'CBSE',
                'minority_group' => 'None',
                'established_year' => 2012,
                'affiliation_no' => 'CBSE/AFF/2130982',
                'valid_upto' => '2028-03-31',
                'campus_area_sqm' => 12500.00,
                'built_up_area_sqm' => 6400.00,
                'playground_available' => true,
                'fire_safety_valid_upto' => '2027-05-15',
                'building_safety_valid_upto' => '2028-02-28',
            ]
        );

        // 2. Compliance Packs
        $packs = [
            [
                'pack_key' => 'cbse',
                'name' => 'India — CBSE / UDISE+',
                'description' => 'UDISE+, SARAS, OASIS, SQAAF, APAAR ID, Aadhaar, CWSN and CBSE statutory documents. The flagship pack for CBSE-affiliated schools.',
                'is_installed' => true,
                'is_primary' => true,
                'installed_at' => now()->subMonths(6),
            ],
            [
                'pack_key' => 'state_board',
                'name' => 'India — State Board / UDISE+',
                'description' => 'UDISE+ student, teacher and school fields plus common statutory documents, without CBSE-only affiliation (SARAS/OASIS).',
                'is_installed' => true,
                'is_primary' => false,
                'installed_at' => now()->subMonths(4),
            ],
            [
                'pack_key' => 'nemis',
                'name' => 'Kenya — CBC / NEMIS',
                'description' => 'NEMIS UPI / assessment number, county and CBC learner fields for Kenyan schools. Hides India-only columns.',
                'is_installed' => false,
                'is_primary' => false,
                'installed_at' => null,
            ],
            [
                'pack_key' => 'generic',
                'name' => 'Generic / International',
                'description' => 'No country-specific statutory fields. Hides India-only columns (caste, BPL, RTE, Aadhaar). Safe default for schools outside India/Kenya.',
                'is_installed' => true,
                'is_primary' => false,
                'installed_at' => now()->subMonths(2),
            ],
        ];

        foreach ($packs as $p) {
            CompliancePack::updateOrCreate(['pack_key' => $p['pack_key']], $p);
        }

        // 3. Compliance Documents (Statutory Vault)
        $docs = [
            [
                'owner_type' => 'school',
                'category' => 'CBSE Affiliation Letter',
                'title' => 'CBSE Provisional Affiliation Grant Letter 2023-28',
                'document_number' => 'CBSE/AFF/2130982/2023',
                'issue_date' => '2023-04-01',
                'expiry_date' => '2028-03-31',
                'file_name' => 'cbse_affiliation_grant_letter.pdf',
                'is_verified' => true,
                'notes' => 'Affiliated up to Senior Secondary level.',
            ],
            [
                'owner_type' => 'school',
                'category' => 'Recognition Certificate',
                'title' => 'State Directorate of Education Recognition Order',
                'document_number' => 'EDN/REC/2018/8921',
                'issue_date' => '2018-04-12',
                'expiry_date' => null,
                'file_name' => 'state_recognition_order.pdf',
                'is_verified' => true,
                'notes' => 'Permanent recognition order for Nursery to Class 12.',
            ],
            [
                'owner_type' => 'school',
                'category' => 'Fire Safety NOC',
                'title' => 'Fire Safety Compliance Certificate (FS-NOC)',
                'document_number' => 'FS/NOC/2024/4910',
                'issue_date' => '2024-05-15',
                'expiry_date' => '2027-05-15',
                'file_name' => 'fire_safety_noc_2024.pdf',
                'is_verified' => true,
                'notes' => '3-year periodic inspection cleared by Chief Fire Officer.',
            ],
            [
                'owner_type' => 'school',
                'category' => 'Building Safety Certificate',
                'title' => 'Structural Stability & Building Safety Certificate',
                'document_number' => 'PWD/STR/2025/1102',
                'issue_date' => '2025-02-28',
                'expiry_date' => '2028-02-28',
                'file_name' => 'building_safety_pwd.pdf',
                'is_verified' => true,
                'notes' => 'Certified safe under seismic zone IV specifications by Executive Engineer.',
            ],
            [
                'owner_type' => 'school',
                'category' => 'Sanitation Certificate',
                'title' => 'Safe Drinking Water & Sanitary Conditions Certificate',
                'document_number' => 'MCD/HLT/2025/7891',
                'issue_date' => '2025-12-10',
                'expiry_date' => now()->addDays(35)->toDateString(),
                'file_name' => 'sanitation_health_cert.pdf',
                'is_verified' => true,
                'notes' => 'Renewal inspection scheduled with municipal health officer.',
            ],
            [
                'owner_type' => 'school',
                'category' => 'Water Testing Report',
                'title' => 'Bacteriological & Chemical Water Purity Test',
                'document_number' => 'DJB/LAB/2026/304',
                'issue_date' => '2026-01-20',
                'expiry_date' => '2027-01-20',
                'file_name' => 'water_potability_test_report.pdf',
                'is_verified' => true,
                'notes' => 'Potable water sample tested from 4 campus water coolers.',
            ],
            [
                'owner_type' => 'school',
                'category' => 'Society Registration',
                'title' => 'Societies Registration Act Certificate & Memorandum',
                'document_number' => 'SOC/REG/2011/34211',
                'issue_date' => '2011-09-18',
                'expiry_date' => null,
                'file_name' => 'society_registration_moa.pdf',
                'is_verified' => true,
                'notes' => 'Non-profit educational trust society.',
            ],
        ];

        foreach ($docs as $d) {
            ComplianceDocument::create(array_merge($d, ['school_id' => 1]));
        }

        // 4. Compliance Validation Findings
        $students = Student::take(6)->get();
        if ($students->isNotEmpty()) {
            ComplianceValidationFinding::create([
                'school_id' => 1,
                'owner_type' => 'student',
                'owner_id' => $students[0]->id,
                'severity' => 'critical',
                'check_key' => 'aadhaar_checksum',
                'check_name' => 'Invalid Aadhaar checksum',
                'issue_description' => "Aadhaar number fails Verhoeff algorithm checksum verification for pupil {$students[0]->first_name}.",
                'status' => 'open',
            ]);

            ComplianceValidationFinding::create([
                'school_id' => 1,
                'owner_type' => 'student',
                'owner_id' => $students[1]->id,
                'severity' => 'error',
                'check_key' => 'apaar_missing',
                'check_name' => 'Missing / invalid APAAR ID',
                'issue_description' => "APAAR ID (Automated Permanent Academic Account Registry) is blank.",
                'status' => 'open',
            ]);

            ComplianceValidationFinding::create([
                'school_id' => 1,
                'owner_type' => 'student',
                'owner_id' => $students[2]->id,
                'severity' => 'warning',
                'check_key' => 'social_category_missing',
                'check_name' => 'Missing social category',
                'issue_description' => "Statutory caste category (General / SC / ST / OBC) not assigned.",
                'status' => 'open',
            ]);
        }

        $staff = Staff::first();
        if ($staff) {
            ComplianceValidationFinding::create([
                'school_id' => 1,
                'owner_type' => 'staff',
                'owner_id' => $staff->id,
                'severity' => 'warning',
                'check_key' => 'tet_qualified',
                'check_name' => 'TET Qualification unverified',
                'issue_description' => "Teacher Eligibility Test certificate number not entered.",
                'status' => 'open',
            ]);
        }

        // 5. Compliance Inspection Items (CBSE Renewal & Annual Inspection)
        $cbseItems = [
            ['key' => 'building_safety', 'title' => 'Building safety certificate valid', 'status' => 'Done', 'notes' => 'PWD certificate valid till 2028.'],
            ['key' => 'fire_noc', 'title' => 'Fire NOC valid', 'status' => 'Done', 'notes' => 'NOC valid till May 2027.'],
            ['key' => 'sanitation', 'title' => 'Sanitation certificate valid', 'status' => 'Done', 'notes' => 'Renewed annually with municipal health office.'],
            ['key' => 'water_test', 'title' => 'Water testing report valid', 'status' => 'Done', 'notes' => 'Potability lab report attached.'],
            ['key' => 'composite_lab', 'title' => 'Composite Science Lab equipped as per CBSE norms', 'status' => 'Pending', 'notes' => 'Microscopes and safety eyewash stations arriving next week.'],
            ['key' => 'cwsn_ramp', 'title' => 'Ramp / Lift for CWSN barrier-free access', 'status' => 'Done', 'notes' => 'Ground floor ramp and disabled-friendly washroom verified.'],
            ['key' => 'web_disclosure', 'title' => 'Mandatory public disclosure on school website', 'status' => 'Done', 'notes' => 'OASIS Appendix IX active on school website portal.'],
            ['key' => '7th_cpc', 'title' => 'Teachers salary paid as per norms via bank ECS', 'status' => 'Done', 'notes' => '100% bank transfer via PFMS/NEFT.'],
            ['key' => 'special_educator', 'title' => 'Qualified Special Educator appointed', 'status' => 'Pending', 'notes' => 'Interviews underway for RCI certified educator.'],
            ['key' => 'counselor', 'title' => 'Qualified School Counselor appointed', 'status' => 'Done', 'notes' => 'MA Clinical Psychology certified counselor on roll.'],
            ['key' => 'epf_esi', 'title' => 'EPF and ESI deduction compliance for all staff', 'status' => 'Done', 'notes' => 'Monthly electronic challan receipts up to date.'],
        ];

        foreach ($cbseItems as $item) {
            ComplianceInspectionItem::create([
                'school_id' => 1,
                'checklist_type' => 'cbse_renewal',
                'item_key' => $item['key'],
                'item_title' => $item['title'],
                'status' => $item['status'],
                'notes' => $item['notes'],
            ]);
        }

        // 6. Compliance Calendar Events
        $events = [
            [
                'event_date' => '2027-07-31',
                'event_type' => 'Statutory',
                'title' => 'SARAS / OASIS annual update window (indicative)',
                'description' => 'Annual online affiliation information system update with teacher pupil ratio and financial balance sheet.',
            ],
            [
                'event_date' => '2027-09-30',
                'event_type' => 'Statutory',
                'title' => 'UDISE+ data collection freeze (indicative)',
                'description' => 'Ministry of Education annual school data collection cutoff date across all states.',
            ],
            [
                'event_date' => now()->addDays(35)->toDateString(),
                'event_type' => 'Document Expiry',
                'title' => 'Sanitation Certificate Expiry',
                'description' => 'Municipal sanitary inspector re-verification due.',
            ],
            [
                'event_date' => '2027-05-15',
                'event_type' => 'Document Expiry',
                'title' => 'Fire Safety NOC Renewal',
                'description' => 'Hydrant and smoke alarm pressure tests required for CFO certificate renewal.',
            ],
        ];

        foreach ($events as $ev) {
            ComplianceCalendarEvent::create(array_merge($ev, ['school_id' => 1]));
        }
    }
}

