<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentHouse;
use App\Models\StudentCategory;
use App\Models\StudentBehavior;
use App\Models\StudentExit;
use Carbon\Carbon;

class StudentInformationSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        if (!$school) return;

        // 1. Student Houses
        $hRed = StudentHouse::firstOrCreate(
            ['name' => 'Red Tigers', 'school_id' => $school->id],
            ['code' => 'RED', 'color' => '#ef4444', 'captain' => 'Arjun Verma', 'description' => 'Courage and Leadership in sports']
        );
        $hBlue = StudentHouse::firstOrCreate(
            ['name' => 'Blue Falcons', 'school_id' => $school->id],
            ['code' => 'BLUE', 'color' => '#3b82f6', 'captain' => 'Ananya Roy', 'description' => 'Wisdom and Innovation in academics']
        );
        $hGreen = StudentHouse::firstOrCreate(
            ['name' => 'Green Cheetahs', 'school_id' => $school->id],
            ['code' => 'GREEN', 'color' => '#10b981', 'captain' => 'Rohan Gupta', 'description' => 'Agility, Speed and Environmental care']
        );
        $hYellow = StudentHouse::firstOrCreate(
            ['name' => 'Yellow Eagles', 'school_id' => $school->id],
            ['code' => 'YELLOW', 'color' => '#f59e0b', 'captain' => 'Pooja Sharma', 'description' => 'Vision and Excellence in cultural arts']
        );

        // 2. Student Categories
        $cGen = StudentCategory::firstOrCreate(
            ['name' => 'General', 'school_id' => $school->id],
            ['code' => 'GEN', 'description' => 'Unreserved general merit category']
        );
        $cObc = StudentCategory::firstOrCreate(
            ['name' => 'OBC (Other Backward Class)', 'school_id' => $school->id],
            ['code' => 'OBC', 'description' => 'Non-creamy layer state quota']
        );
        $cSc = StudentCategory::firstOrCreate(
            ['name' => 'SC (Scheduled Caste)', 'school_id' => $school->id],
            ['code' => 'SC', 'description' => 'Affirmative action schedule']
        );
        $cSt = StudentCategory::firstOrCreate(
            ['name' => 'ST (Scheduled Tribe)', 'school_id' => $school->id],
            ['code' => 'ST', 'description' => 'Tribal welfare allocation']
        );
        $cEws = StudentCategory::firstOrCreate(
            ['name' => 'EWS (Economically Weaker)', 'school_id' => $school->id],
            ['code' => 'EWS', 'description' => 'RTE 25% economically weaker category']
        );

        // 3. Update existing active students with houses, categories and health attributes
        $students = Student::all();
        $houses = [$hRed, $hBlue, $hGreen, $hYellow];
        $cats = [$cGen, $cObc, $cSc, $cEws];
        $allergiesList = ['None', 'Dust Allergy', 'Mild Asthmatic', 'Peanut Allergy', 'Spectacles Required', 'None'];

        foreach ($students as $idx => $st) {
            $h = $houses[$idx % count($houses)];
            $c = $cats[$idx % count($cats)];
            $st->update([
                'house_id' => $h->id,
                'category_id' => $c->id,
                'religion' => ($idx % 2 == 0) ? 'Hindu' : 'Muslim',
                'height' => 140 + ($idx * 3),
                'weight' => 38 + ($idx * 2),
                'blood_group' => $st->blood_group ?: (['A+', 'B+', 'O+', 'AB+'][$idx % 4]),
                'allergies' => $allergiesList[$idx % count($allergiesList)],
                'medical_alerts' => ($idx % 3 == 0) ? 'Annual pediatric checkup recommended; cleared for outdoor physical sports' : 'Fit for all campus athletic activities'
            ]);
        }

        // 4. Seed Behavior Records
        if ($students->count() > 0) {
            $st1 = $students->first();
            StudentBehavior::firstOrCreate(
                ['title' => '1st Prize - Inter-School Robotics Olympiad', 'student_id' => $st1->id],
                [
                    'school_id' => $school->id,
                    'type' => 'Positive',
                    'points' => 10,
                    'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                    'reported_by' => 'Dr. Meenakshi Sundaram (HOD Physics)',
                    'description' => 'Demonstrated exemplary programming skills in the autonomous obstacle avoider robot contest.'
                ]
            );

            if ($students->count() > 1) {
                $st2 = $students->skip(1)->first();
                StudentBehavior::firstOrCreate(
                    ['title' => 'House Assembly Discipline & Leadership', 'student_id' => $st2->id],
                    [
                        'school_id' => $school->id,
                        'type' => 'Positive',
                        'points' => 5,
                        'date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                        'reported_by' => 'Kavita Chawla (House Mentor)',
                        'description' => 'Led the morning assembly prayer and national anthem recitation with great enthusiasm.'
                    ]
                );

                StudentBehavior::firstOrCreate(
                    ['title' => 'Late reporting to Zero Period Assembly', 'student_id' => $st2->id],
                    [
                        'school_id' => $school->id,
                        'type' => 'Disciplinary',
                        'points' => -2,
                        'date' => Carbon::now()->subDays(12)->format('Y-m-d'),
                        'reported_by' => 'Col. R. S. Rathore (Discipline In-charge)',
                        'description' => 'Reported 15 minutes late without valid bus delay slip. Advised on timekeeping.'
                    ]
                );
            }
        }

        // 5. Seed TC & Student Exit
        if ($students->count() > 2) {
            $st3 = $students->skip(2)->first();
            StudentExit::firstOrCreate(
                ['tc_number' => 'TC-2026-0042'],
                [
                    'school_id' => $school->id,
                    'student_id' => $st3->id,
                    'reason' => 'Parent Relocation (Transfer to Mumbai Corporate Office)',
                    'exit_date' => Carbon::now()->subDays(14)->format('Y-m-d'),
                    'certificate_printed' => Carbon::now()->subDays(12)->format('Y-m-d'),
                    'status' => 'TC Issued',
                    'remarks' => 'All dues cleared, library clearance verified, conduct certified as Good.'
                ]
            );
        }

        // 6. Seed an Archived / Soft-Deleted Student for Recycle Bin demo
        $deletedStudent = Student::withTrashed()->where('admission_no', 'YISADM-244')->first();
        if (!$deletedStudent) {
            $del = Student::create([
                'school_id' => $school->id,
                'admission_no' => 'YISADM-244',
                'roll_no' => '99',
                'first_name' => 'Nitya',
                'last_name' => 'Singh',
                'gender' => 'female',
                'dob' => '2012-08-14',
                'blood_group' => 'B+',
                'admission_date' => '2024-04-01',
                'status' => 'inactive',
                'deleted_reason' => 'Duplicate admission record cleanup',
                'deleted_by' => 'school admin'
            ]);
            $del->delete(); // soft delete
        }
    }
}

