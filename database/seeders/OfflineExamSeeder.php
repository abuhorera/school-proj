<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\Exam;
use App\Models\ExamSchedule;
use App\Models\ExamMark;
use App\Models\CocurricularGrade;
use App\Models\UploadedMarksheet;
use App\Models\StudentExamRemark;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use Carbon\Carbon;

class OfflineExamSeeder extends Seeder
{
    public function run()
    {
        $schoolId = 1;

        // 1. Exam Types
        $types = [
            ['name' => 'Theory Examination', 'abbreviation' => 'TH', 'weightage' => '80%', 'description' => 'Written board & term pen-and-paper assessment', 'sort_order' => 1],
            ['name' => 'Practical / Lab Experiment', 'abbreviation' => 'PR', 'weightage' => '20%', 'description' => 'Laboratory hands-on practical session and experiment file', 'sort_order' => 2],
            ['name' => 'Oral / Viva Voce', 'abbreviation' => 'OR', 'weightage' => '10%', 'description' => 'Interactive verbal communication assessment', 'sort_order' => 3],
            ['name' => 'Internal Assessment (Portfolio)', 'abbreviation' => 'IA', 'weightage' => '20%', 'description' => 'Subject enrichment portfolios, notebook submission & assignments', 'sort_order' => 4],
            ['name' => 'Periodic Test', 'abbreviation' => 'PT', 'weightage' => '10%', 'description' => 'Fortnightly and monthly syllabus unit tests', 'sort_order' => 5],
            ['name' => 'Project Work & Presentation', 'abbreviation' => 'PW', 'weightage' => '10%', 'description' => 'Group and individual interdisciplinary project work', 'sort_order' => 6],
        ];

        foreach ($types as $t) {
            ExamType::firstOrCreate(
                ['school_id' => $schoolId, 'name' => $t['name']],
                $t
            );
        }

        // 2. CBSE 9-Point Grading Scale
        $grades = [
            ['name' => 'A1', 'min_percentage' => 91, 'max_percentage' => 100, 'grade_point' => 10.0, 'description' => 'Outstanding Performance'],
            ['name' => 'A2', 'min_percentage' => 81, 'max_percentage' => 90.99, 'grade_point' => 9.0, 'description' => 'Excellent Achievement'],
            ['name' => 'B1', 'min_percentage' => 71, 'max_percentage' => 80.99, 'grade_point' => 8.0, 'description' => 'Very Good Competency'],
            ['name' => 'B2', 'min_percentage' => 61, 'max_percentage' => 70.99, 'grade_point' => 7.0, 'description' => 'Good Proficiency'],
            ['name' => 'C1', 'min_percentage' => 51, 'max_percentage' => 60.99, 'grade_point' => 6.0, 'description' => 'Above Average Mastery'],
            ['name' => 'C2', 'min_percentage' => 41, 'max_percentage' => 50.99, 'grade_point' => 5.0, 'description' => 'Average Passing Standard'],
            ['name' => 'D', 'min_percentage' => 33, 'max_percentage' => 40.99, 'grade_point' => 4.0, 'description' => 'Marginal Passing'],
            ['name' => 'E', 'min_percentage' => 21, 'max_percentage' => 32.99, 'grade_point' => 0.0, 'description' => 'Needs Remedial Coaching'],
            ['name' => 'F', 'min_percentage' => 0, 'max_percentage' => 20.99, 'grade_point' => 0.0, 'description' => 'Substantial Improvement Required'],
        ];

        foreach ($grades as $g) {
            Grade::firstOrCreate(
                ['school_id' => $schoolId, 'name' => $g['name']],
                $g
            );
        }

        // 3. Exams
        $allClasses = SchoolClass::where('school_id', $schoolId)->pluck('id')->toArray();

        $examData = [
            [
                'school_id' => $schoolId,
                'session_id' => 1,
                'name' => 'Mid-Term Examinations 2026',
                'type' => 'Term Exam',
                'start_date' => '2026-09-15',
                'end_date' => '2026-09-28',
                'description' => 'Half-yearly summative evaluation covering 50% CBSE curriculum',
                'class_ids' => $allClasses,
                'status' => 'Active',
                'is_published' => true,
            ],
            [
                'school_id' => $schoolId,
                'session_id' => 1,
                'name' => 'Periodic Assessment 1 (PA-1)',
                'type' => 'Periodic Test',
                'start_date' => '2026-07-10',
                'end_date' => '2026-07-18',
                'description' => 'First quarterly formative assessment for all grades',
                'class_ids' => $allClasses,
                'status' => 'Completed',
                'is_published' => true,
            ],
            [
                'school_id' => $schoolId,
                'session_id' => 1,
                'name' => 'Periodic Assessment 2 (PA-2)',
                'type' => 'Periodic Test',
                'start_date' => '2026-11-20',
                'end_date' => '2026-11-28',
                'description' => 'Second term unit assessments before winter break',
                'class_ids' => $allClasses,
                'status' => 'Upcoming',
                'is_published' => false,
            ],
            [
                'school_id' => $schoolId,
                'session_id' => 1,
                'name' => 'Annual Board Pre-Board 2027',
                'type' => 'Final Exam',
                'start_date' => '2027-01-12',
                'end_date' => '2027-01-25',
                'description' => 'Full syllabus rehearsal examination according to CBSE Board pattern',
                'class_ids' => $allClasses,
                'status' => 'Upcoming',
                'is_published' => false,
            ]
        ];

        foreach ($examData as $ed) {
            $ex = Exam::where('name', $ed['name'])->where('school_id', $schoolId)->first();
            if (!$ex) {
                $ex = Exam::create($ed);
            } else {
                $ex->update($ed);
            }
        }

        $midterm = Exam::where('name', 'like', '%Mid-Term%')->first();
        $subjects = Subject::where('school_id', $schoolId)->take(6)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->take(4)->get();
        $students = Student::where('school_id', $schoolId)->take(25)->get();

        // 4. Schedules & Marks
        if ($midterm && $subjects->count() > 0) {
            $examDate = Carbon::parse('2026-09-15');
            foreach ($classes as $cls) {
                foreach ($subjects as $idx => $sub) {
                    $sched = ExamSchedule::firstOrCreate(
                        [
                            'exam_id' => $midterm->id,
                            'class_id' => $cls->id,
                            'subject_id' => $sub->id,
                        ],
                        [
                            'exam_date' => $examDate->copy()->addDays($idx * 2)->toDateString(),
                            'start_time' => '09:00:00',
                            'end_time' => '12:00:00',
                            'room_no' => 'Room ' . (101 + $idx),
                            'max_marks' => 100,
                            'pass_marks' => 33,
                        ]
                    );

                    // Add marks for students in this class
                    $classStudents = Student::where('school_id', $schoolId)
                        ->whereHas('latestEnrollment', function ($q) use ($cls) {
                            $q->where('class_id', $cls->id);
                        })->get();

                    if ($classStudents->count() == 0) {
                        $classStudents = $students->take(5);
                    }

                    foreach ($classStudents as $st) {
                        $score = rand(45, 98);
                        $grd = 'A1';
                        if ($score < 50) $grd = 'C2';
                        elseif ($score < 60) $grd = 'C1';
                        elseif ($score < 70) $grd = 'B2';
                        elseif ($score < 80) $grd = 'B1';
                        elseif ($score < 90) $grd = 'A2';

                        ExamMark::firstOrCreate(
                            [
                                'exam_schedule_id' => $sched->id,
                                'student_id' => $st->id,
                            ],
                            [
                                'marks_obtained' => $score,
                                'is_absent' => false,
                                'grade' => $grd,
                                'remarks' => 'Good conceptual clarity shown.',
                            ]
                        );
                    }
                }
            }
        }

        // 5. Cocurricular Grades
        $cocurricularAreas = [
            'Work Education (Cookery, Gardening)',
            'Art Education (Drawing & Sketching)',
            'Health & Physical Education (Sports)',
            'Discipline & Moral Ethics',
        ];

        if ($midterm) {
            foreach ($students->take(8) as $stu) {
                foreach ($cocurricularAreas as $area) {
                    CocurricularGrade::firstOrCreate(
                        [
                            'school_id' => $schoolId,
                            'exam_id' => $midterm->id,
                            'student_id' => $stu->id,
                            'area_name' => $area,
                        ],
                        [
                            'grade' => ['A', 'A+', 'B+'][array_rand(['A', 'A+', 'B+'])],
                            'remarks' => 'Actively participated in class activities.',
                        ]
                    );
                }

                // 6. Student Exam Remarks
                StudentExamRemark::firstOrCreate(
                    [
                        'school_id' => $schoolId,
                        'student_id' => $stu->id,
                        'exam_id' => $midterm->id,
                    ],
                    [
                        'class_teacher_remarks' => 'Excellent conduct throughout the term. Shows natural enthusiasm for scientific experiments and languages.',
                        'principal_remarks' => 'Promoted to maintain high academic standard.',
                        'attendance_percentage' => rand(88, 98),
                        'conduct' => 'Exemplary',
                    ]
                );
            }
        }

        // 7. Uploaded External Marksheets
        if ($students->count() > 0 && $midterm) {
            $s1 = $students[0];
            UploadedMarksheet::firstOrCreate(
                [
                    'school_id' => $schoolId,
                    'student_id' => $s1->id,
                    'title' => 'CBSE Olympiad Certificate & Scorecard',
                ],
                [
                    'exam_id' => $midterm->id,
                    'class_id' => 1,
                    'section_id' => 1,
                    'file_path' => 'marksheets/sample_olympiad.pdf',
                    'is_published' => true,
                ]
            );

            if ($students->count() > 1) {
                $s2 = $students[1];
                UploadedMarksheet::firstOrCreate(
                    [
                        'school_id' => $schoolId,
                        'student_id' => $s2->id,
                        'title' => 'National Science Talent Assessment',
                    ],
                    [
                        'exam_id' => $midterm->id,
                        'class_id' => 1,
                        'section_id' => 1,
                        'file_path' => 'marksheets/sample_talent.pdf',
                        'is_published' => true,
                    ]
                );
            }
        }
    }
}

