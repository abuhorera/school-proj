<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassroomAssessment;
use App\Models\AssessmentOccurrence;
use App\Models\AssessmentStudentMark;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $class1 = SchoolClass::first() ?? SchoolClass::create(['name' => 'Class I', 'numeric_name' => 1]);
        $class2 = SchoolClass::skip(1)->first() ?? $class1;
        $class10 = SchoolClass::where('name', 'like', '%10%')->orWhere('name', 'like', '%X%')->first() ?? $class1;

        $secA = Section::first();
        $subMath = Subject::where('name', 'like', '%Math%')->first() ?? Subject::first();
        $subEng = Subject::where('name', 'like', '%English%')->first() ?? Subject::skip(1)->first() ?? Subject::first();
        $subSci = Subject::where('name', 'like', '%Sci%')->orWhere('name', 'like', '%Physics%')->first() ?? $subMath;
        $subHindi = Subject::where('name', 'like', '%Hindi%')->first() ?? $subEng;

        $assessmentsData = [
            [
                'title' => 'English CA1 — Reading Comprehension',
                'type' => 'Quiz',
                'conducted_via' => 'Paper',
                'class_id' => $class2->id,
                'section_id' => $secA?->id,
                'subject_id' => $subEng->id,
                'total_marks' => 30.00,
                'passing_marks' => 12.00,
                'frequency' => 'One-off',
                'scheduled_date' => now()->subDays(10)->toDateString(),
                'instructions' => 'Read passage A & B and answer short comprehension questions.',
                'status' => 'Grading',
            ],
            [
                'title' => 'Unit Test — Arithmetic & Fractions',
                'type' => 'Test',
                'conducted_via' => 'Paper',
                'class_id' => $class1->id,
                'section_id' => $secA?->id,
                'subject_id' => $subMath->id,
                'total_marks' => 25.00,
                'passing_marks' => 10.00,
                'frequency' => 'Monthly',
                'scheduled_date' => now()->subDays(5)->toDateString(),
                'instructions' => 'Calculators are not allowed. Show all rough working steps.',
                'status' => 'Completed',
            ],
            [
                'title' => 'Weekly Quiz — Mechanics & Waves',
                'type' => 'Quiz',
                'conducted_via' => 'Online Exam module',
                'class_id' => $class10->id,
                'section_id' => null,
                'subject_id' => $subSci->id,
                'total_marks' => 20.00,
                'passing_marks' => 8.00,
                'frequency' => 'Weekly',
                'scheduled_date' => now()->subDays(3)->toDateString(),
                'instructions' => 'Timed quiz: 20 multiple choice questions in 25 minutes.',
                'status' => 'Completed',
            ],
            [
                'title' => 'Hindi Kavita Path — Oral Recitation',
                'type' => 'Oral',
                'conducted_via' => 'Oral',
                'class_id' => $class2->id,
                'section_id' => $secA?->id,
                'subject_id' => $subHindi->id,
                'total_marks' => 20.00,
                'passing_marks' => 8.00,
                'frequency' => 'Fortnightly',
                'scheduled_date' => now()->subDays(8)->toDateString(),
                'instructions' => 'Voice modulation, memory, and pronunciation evaluated out of 20.',
                'status' => 'Completed',
            ],
            [
                'title' => 'Science Lab Practical — Optics & Lenses',
                'type' => 'Practical',
                'conducted_via' => 'Other',
                'class_id' => $class10->id,
                'section_id' => null,
                'subject_id' => $subSci->id,
                'total_marks' => 50.00,
                'passing_marks' => 20.00,
                'frequency' => 'Monthly',
                'scheduled_date' => now()->subDays(12)->toDateString(),
                'instructions' => 'Bring verified lab journals. Experiment setup + viva voce.',
                'status' => 'Completed',
            ],
            [
                'title' => 'Creative Writing Assignment — Environmental Day',
                'type' => 'Assignment',
                'conducted_via' => 'Paper',
                'class_id' => $class1->id,
                'section_id' => null,
                'subject_id' => $subEng->id,
                'total_marks' => 25.00,
                'passing_marks' => 10.00,
                'frequency' => 'One-off',
                'scheduled_date' => now()->subDays(15)->toDateString(),
                'instructions' => 'Write a 250-word essay on reducing single-use plastics on campus.',
                'status' => 'Completed',
            ],
        ];

        $students = Student::take(10)->get();

        foreach ($assessmentsData as $data) {
            $ass = ClassroomAssessment::updateOrCreate(
                ['title' => $data['title'], 'class_id' => $data['class_id']],
                array_merge($data, ['school_id' => 1, 'academic_session_id' => 1])
            );

            // Create Sitting Occurrence
            $occ = AssessmentOccurrence::updateOrCreate(
                ['assessment_id' => $ass->id, 'sitting_date' => $data['scheduled_date']],
                [
                    'status' => $data['status'] === 'Completed' ? 'Published' : 'Grading',
                    'marks_entered_count' => count($students),
                    'published_at' => $data['status'] === 'Completed' ? now()->subDays(2) : null,
                    'notes' => 'Conducted on schedule in examination hall / regular period.',
                ]
            );

            // Seed student marks
            foreach ($students as $idx => $st) {
                $maxM = (float) $ass->total_marks;
                $isAbsent = ($idx === 4);
                $obtained = $isAbsent ? null : round(min($maxM, $maxM * (0.60 + (($idx * 7) % 38) / 100)), 1);

                AssessmentStudentMark::updateOrCreate(
                    ['occurrence_id' => $occ->id, 'student_id' => $st->id],
                    [
                        'marks_obtained' => $obtained,
                        'is_absent' => $isAbsent,
                        'feedback' => $isAbsent ? 'Candidate absent on scheduled date' : 'Good concept clarity, improve handwriting',
                    ]
                );
            }
        }
    }
}

