<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\AcademicSession;
use App\Models\TimeSlot;
use App\Models\Timetable;
use App\Models\ClassSubject;
use Carbon\Carbon;

class AcademicSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        if (!$school) return;

        // 1. Academic Sessions
        $sCurrent = AcademicSession::updateOrCreate(
            ['name' => '2026-2027 (26-27)', 'school_id' => $school->id],
            [
                'start_date' => '2026-04-01',
                'end_date' => '2027-03-25',
                'is_current' => true
            ]
        );

        $sPast = AcademicSession::updateOrCreate(
            ['name' => '2025-2026 (25-26)', 'school_id' => $school->id],
            [
                'start_date' => '2025-04-01',
                'end_date' => '2026-03-25',
                'is_current' => false
            ]
        );

        $sNext = AcademicSession::updateOrCreate(
            ['name' => '2027-2028 (27-28)', 'school_id' => $school->id],
            [
                'start_date' => '2027-04-01',
                'end_date' => '2028-03-25',
                'is_current' => false
            ]
        );

        // Staff references
        $staff = Staff::all();
        $t1 = $staff->first();
        $t2 = $staff->skip(1)->first() ?? $t1;
        $t3 = $staff->skip(2)->first() ?? $t1;

        // 2. Classes & Sections Setup
        $classDefinitions = [
            ['name' => 'Nursery', 'numeric_level' => 0],
            ['name' => 'LKG', 'numeric_level' => 0],
            ['name' => 'UKG', 'numeric_level' => 0],
            ['name' => 'Class 1', 'numeric_level' => 1],
            ['name' => 'Class 2', 'numeric_level' => 2],
            ['name' => 'Class 3', 'numeric_level' => 3],
            ['name' => 'Class 4', 'numeric_level' => 4],
            ['name' => 'Class 5', 'numeric_level' => 5],
            ['name' => 'Class 6', 'numeric_level' => 6],
            ['name' => 'Class 7', 'numeric_level' => 7],
            ['name' => 'Class 8', 'numeric_level' => 8],
            ['name' => 'Class 9', 'numeric_level' => 9],
            ['name' => 'Class 10', 'numeric_level' => 10],
            ['name' => 'Class 11 (Science)', 'numeric_level' => 11],
            ['name' => 'Class 12 (Science)', 'numeric_level' => 12],
        ];

        foreach ($classDefinitions as $cDef) {
            $c = SchoolClass::updateOrCreate(
                ['name' => $cDef['name'], 'school_id' => $school->id],
                [
                    'numeric_level' => $cDef['numeric_level'],
                    'coordinator_id' => $t1 ? $t1->id : null
                ]
            );

            // Sections A and B
            Section::firstOrCreate(
                ['name' => 'A', 'class_id' => $c->id],
                ['capacity' => 40, 'in_charge_teacher_id' => $t2 ? $t2->id : null]
            );
            Section::firstOrCreate(
                ['name' => 'B', 'class_id' => $c->id],
                ['capacity' => 40, 'in_charge_teacher_id' => $t3 ? $t3->id : null]
            );
        }

        // 3. Subjects Catalog
        $subjectsData = [
            ['name' => 'English Language & Literature', 'code' => 'ENG-101', 'type' => 'theory', 'group' => 'Languages', 'is_elective' => false],
            ['name' => 'Hindi Sahitya & Vyakaran', 'code' => 'HIN-102', 'type' => 'theory', 'group' => 'Languages', 'is_elective' => false],
            ['name' => 'Sanskrit / French', 'code' => 'ELE-LAN', 'type' => 'theory', 'group' => 'Languages', 'is_elective' => true],
            ['name' => 'Mathematics & Numerical Logic', 'code' => 'MATH-103', 'type' => 'theory', 'group' => 'Science & Math', 'is_elective' => false],
            ['name' => 'General Science & Environment', 'code' => 'SCI-104', 'type' => 'theory', 'group' => 'Science & Math', 'is_elective' => false],
            ['name' => 'Physics Laboratory & Theory', 'code' => 'PHY-105', 'type' => 'practical', 'group' => 'Science & Math', 'is_elective' => false],
            ['name' => 'Chemistry Laboratory & Reactions', 'code' => 'CHEM-106', 'type' => 'practical', 'group' => 'Science & Math', 'is_elective' => false],
            ['name' => 'Biology & Botany Dissection', 'code' => 'BIO-107', 'type' => 'practical', 'group' => 'Science & Math', 'is_elective' => false],
            ['name' => 'Social Science & History', 'code' => 'SST-108', 'type' => 'theory', 'group' => 'Social Studies', 'is_elective' => false],
            ['name' => 'Computer Applications & AI', 'code' => 'CS-109', 'type' => 'practical', 'group' => 'Vocational', 'is_elective' => true],
            ['name' => 'Physical Health & Yoga', 'code' => 'PE-110', 'type' => 'practical', 'group' => 'Sports', 'is_elective' => false],
            ['name' => 'Creative Arts & Music', 'code' => 'ART-111', 'type' => 'practical', 'group' => 'Cultural', 'is_elective' => true]
        ];

        foreach ($subjectsData as $sData) {
            Subject::updateOrCreate(
                ['code' => $sData['code'], 'school_id' => $school->id],
                $sData
            );
        }

        // 4. Time Slots / Periods Setup
        $slots = [
            ['name' => 'Morning Assembly & Prayer', 'start_time' => '08:00:00', 'end_time' => '08:20:00', 'is_break' => true, 'sort_order' => 1],
            ['name' => 'Period 1', 'start_time' => '08:20:00', 'end_time' => '09:05:00', 'is_break' => false, 'sort_order' => 2],
            ['name' => 'Period 2', 'start_time' => '09:05:00', 'end_time' => '09:50:00', 'is_break' => false, 'sort_order' => 3],
            ['name' => 'Recess / Fruit Break', 'start_time' => '09:50:00', 'end_time' => '10:05:00', 'is_break' => true, 'sort_order' => 4],
            ['name' => 'Period 3', 'start_time' => '10:05:00', 'end_time' => '10:50:00', 'is_break' => false, 'sort_order' => 5],
            ['name' => 'Period 4', 'start_time' => '10:50:00', 'end_time' => '11:35:00', 'is_break' => false, 'sort_order' => 6],
            ['name' => 'Lunch Break', 'start_time' => '11:35:00', 'end_time' => '12:15:00', 'is_break' => true, 'sort_order' => 7],
            ['name' => 'Period 5', 'start_time' => '12:15:00', 'end_time' => '01:00:00', 'is_break' => false, 'sort_order' => 8],
            ['name' => 'Period 6', 'start_time' => '01:00:00', 'end_time' => '01:45:00', 'is_break' => false, 'sort_order' => 9],
            ['name' => 'Period 7', 'start_time' => '01:45:00', 'end_time' => '02:30:00', 'is_break' => false, 'sort_order' => 10],
        ];

        foreach ($slots as $sl) {
            TimeSlot::updateOrCreate(
                ['name' => $sl['name'], 'school_id' => $school->id],
                $sl
            );
        }

        // 5. Seed Class Subjects & Timetable for Class 10
        $class10 = SchoolClass::where('numeric_level', 10)->first() ?? SchoolClass::first();
        $secA = $class10->sections()->first();
        $subList = Subject::all();
        $slotList = TimeSlot::where('is_break', false)->orderBy('sort_order')->get();

        if ($class10 && $secA && $subList->count() > 0 && $slotList->count() > 0) {
            // Assign class subjects
            foreach ($subList as $sIdx => $sb) {
                ClassSubject::firstOrCreate(
                    ['class_id' => $class10->id, 'section_id' => $secA->id, 'subject_id' => $sb->id],
                    [
                        'school_id' => $school->id,
                        'teacher_id' => $staff[$sIdx % $staff->count()]->id ?? null,
                        'is_elective' => $sb->is_elective
                    ]
                );
            }

            // Timetable for Monday - Friday
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($days as $dIdx => $day) {
                foreach ($slotList as $pIdx => $slot) {
                    $sb = $subList[($dIdx + $pIdx) % $subList->count()];
                    Timetable::updateOrCreate(
                        ['class_id' => $class10->id, 'section_id' => $secA->id, 'day_of_week' => $day, 'time_slot_id' => $slot->id],
                        [
                            'school_id' => $school->id,
                            'subject_id' => $sb->id,
                            'teacher_id' => $t1 ? $t1->id : null,
                            'room_number' => 'Room 101'
                        ]
                    );
                }
            }
        }
    }
}

