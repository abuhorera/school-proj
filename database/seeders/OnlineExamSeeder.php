<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionTopic;
use App\Models\OnlineQuestion;
use App\Models\OnlineExam;
use App\Models\OnlineExamQuestion;
use App\Models\OnlineExamAttempt;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Student;
use Carbon\Carbon;

class OnlineExamSeeder extends Seeder
{
    public function run()
    {
        $schoolId = 1;
        $class = SchoolClass::where('school_id', $schoolId)->first() ?? SchoolClass::create(['school_id' => 1, 'name' => 'Class 10']);
        $section = Section::first() ?? Section::create(['class_id' => $class->id, 'name' => 'A']);
        $math = Subject::where('name', 'like', '%Math%')->first() ?? Subject::where('school_id', $schoolId)->first();
        $english = Subject::where('name', 'like', '%Eng%')->first() ?? $math;

        // 1. Seed Topics
        $topic1 = QuestionTopic::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Linear Equations in Two Variables'],
            ['subject_id' => $math ? $math->id : 1, 'chapter' => 'Chapter 3', 'description' => 'Algebraic and graphical solutions']
        );

        $topic2 = QuestionTopic::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Quadratic Equations & Polynomials'],
            ['subject_id' => $math ? $math->id : 1, 'chapter' => 'Chapter 4', 'description' => 'Roots, discriminant, and factorization']
        );

        $topic3 = QuestionTopic::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Tenses & Active-Passive Voice'],
            ['subject_id' => $english ? $english->id : 1, 'chapter' => 'Unit 2', 'description' => 'Grammar conventions and syntax rules']
        );

        $topic4 = QuestionTopic::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Reading Comprehension & Inferences'],
            ['subject_id' => $english ? $english->id : 1, 'chapter' => 'Unit 1', 'description' => 'Context clues and analytical reading']
        );

        // 2. Seed Questions
        $questionsData = [
            [
                'question' => 'What is the standard form of a quadratic equation in one variable x?',
                'question_type' => 'single_choice',
                'difficulty' => 'Easy',
                'subject_id' => $math ? $math->id : 1,
                'question_topic_id' => $topic2->id,
                'marks' => 2.00,
                'negative_marks' => 0.50,
                'options' => [
                    ['key' => 'A', 'text' => 'ax^2 + bx + c = 0 (where a != 0)'],
                    ['key' => 'B', 'text' => 'ax + by + c = 0'],
                    ['key' => 'C', 'text' => 'ax^3 + bx^2 + cx + d = 0'],
                    ['key' => 'D', 'text' => 'y = mx + c'],
                ],
                'correct_answers' => ['A'],
                'solution_html' => 'A quadratic equation is a second-order polynomial equation in a single variable x with ax^2 + bx + c = 0 and a != 0.',
                'hint_html' => 'Look for the equation where the highest exponent of the variable is 2.',
                'tags' => 'algebra, quadratics, cbse',
            ],
            [
                'question' => 'If the discriminant D = b^2 - 4ac of a quadratic equation is greater than zero (D > 0), the roots are:',
                'question_type' => 'single_choice',
                'difficulty' => 'Medium',
                'subject_id' => $math ? $math->id : 1,
                'question_topic_id' => $topic2->id,
                'marks' => 2.00,
                'negative_marks' => 0.50,
                'options' => [
                    ['key' => 'A', 'text' => 'Real and distinct (two unequal roots)'],
                    ['key' => 'B', 'text' => 'Real and equal (one repeated root)'],
                    ['key' => 'C', 'text' => 'Complex or imaginary (no real roots)'],
                    ['key' => 'D', 'text' => 'Zero'],
                ],
                'correct_answers' => ['A'],
                'solution_html' => 'When D > 0, the square root of D is a positive real number, yielding two distinct real solutions.',
                'hint_html' => 'Think about the quadratic formula x = (-b +- sqrt(D)) / 2a.',
                'tags' => 'discriminant, roots',
            ],
            [
                'question' => 'The pair of linear equations 2x + 3y = 9 and 4x + 6y = 18 has how many solutions?',
                'question_type' => 'single_choice',
                'difficulty' => 'Medium',
                'subject_id' => $math ? $math->id : 1,
                'question_topic_id' => $topic1->id,
                'marks' => 2.00,
                'negative_marks' => 0.50,
                'options' => [
                    ['key' => 'A', 'text' => 'Infinitely many solutions (coincident lines)'],
                    ['key' => 'B', 'text' => 'A unique solution (intersecting lines)'],
                    ['key' => 'C', 'text' => 'No solution (parallel lines)'],
                    ['key' => 'D', 'text' => 'Exactly two solutions'],
                ],
                'correct_answers' => ['A'],
                'solution_html' => 'Here a1/a2 = 2/4 = 1/2, b1/b2 = 3/6 = 1/2, and c1/c2 = 9/18 = 1/2. Since a1/a2 = b1/b2 = c1/c2, the lines are coincident with infinitely many solutions.',
                'hint_html' => 'Check the ratios of the coefficients: a1/a2, b1/b2, and c1/c2.',
                'tags' => 'linear equations, consistency',
            ],
            [
                'question' => 'True or False: Every polynomial equation of degree 2 has at most two real roots.',
                'question_type' => 'true_false',
                'difficulty' => 'Easy',
                'subject_id' => $math ? $math->id : 1,
                'question_topic_id' => $topic2->id,
                'marks' => 1.00,
                'negative_marks' => 0.25,
                'options' => [
                    ['key' => 'True', 'text' => 'True'],
                    ['key' => 'False', 'text' => 'False'],
                ],
                'correct_answers' => ['True'],
                'solution_html' => 'By the Fundamental Theorem of Algebra, a degree n polynomial has exactly n roots over complex numbers and at most n real roots.',
                'hint_html' => 'Recall the degree of the polynomial.',
                'tags' => 'polynomials, truth value',
            ],
            [
                'question' => 'Which of the following sentences is in the Present Perfect Continuous tense?',
                'question_type' => 'single_choice',
                'difficulty' => 'Easy',
                'subject_id' => $english ? $english->id : 1,
                'question_topic_id' => $topic3->id,
                'marks' => 2.00,
                'negative_marks' => 0.50,
                'options' => [
                    ['key' => 'A', 'text' => 'She has been writing letters since morning.'],
                    ['key' => 'B', 'text' => 'She wrote letters yesterday.'],
                    ['key' => 'C', 'text' => 'She is writing letters right now.'],
                    ['key' => 'D', 'text' => 'She will have written letters.'],
                ],
                'correct_answers' => ['A'],
                'solution_html' => 'Present perfect continuous structure: Subject + has/have + been + verb-ing.',
                'hint_html' => 'Look for "has been" or "have been" followed by a continuous "-ing" verb.',
                'tags' => 'tenses, grammar',
            ],
            [
                'question' => 'Select the correct passive voice for: "The teacher delivered an inspiring lecture."',
                'question_type' => 'single_choice',
                'difficulty' => 'Medium',
                'subject_id' => $english ? $english->id : 1,
                'question_topic_id' => $topic3->id,
                'marks' => 2.00,
                'negative_marks' => 0.50,
                'options' => [
                    ['key' => 'A', 'text' => 'An inspiring lecture was delivered by the teacher.'],
                    ['key' => 'B', 'text' => 'An inspiring lecture has been delivered by the teacher.'],
                    ['key' => 'C', 'text' => 'The teacher was delivering an inspiring lecture.'],
                    ['key' => 'D', 'text' => 'An inspiring lecture is delivered by the teacher.'],
                ],
                'correct_answers' => ['A'],
                'solution_html' => 'Simple past passive structure: Object + was/were + past participle (delivered) + by + Subject.',
                'hint_html' => 'The original sentence is in the simple past tense (delivered).',
                'tags' => 'passive voice, syntax',
            ],
        ];

        $createdQuestions = [];
        foreach ($questionsData as $qData) {
            $createdQuestions[] = OnlineQuestion::firstOrCreate(
                ['question' => $qData['question']],
                array_merge($qData, [
                    'school_id' => $schoolId,
                    'school_class_id' => $class->id,
                    'is_active' => true,
                ])
            );
        }

        // 3. Seed Online Exams (Matching Demo)
        $exam1 = OnlineExam::firstOrCreate(
            ['title' => 'Unit 1 - Algebra'],
            [
                'school_id' => $schoolId,
                'school_class_id' => $class->id,
                'section_id' => $section->id,
                'subject_id' => $math ? $math->id : 1,
                'kind' => 'Exam',
                'start_time' => Carbon::now()->subHours(2),
                'end_time' => Carbon::now()->addHours(3),
                'duration' => 180,
                'total_marks' => 25.00,
                'passing_percentage' => 40.00,
                'negative_marking' => true,
                'negative_mark_rate' => 0.50,
                'auto_publish_results' => true,
                'randomize_questions' => true,
                'status' => 'Active',
                'instructions' => 'Read all questions carefully. Negative marking of 0.50 marks applies for incorrect choices. Do not switch tabs during CBT.',
            ]
        );

        $exam2 = OnlineExam::firstOrCreate(
            ['title' => 'english grammer'],
            [
                'school_id' => $schoolId,
                'school_class_id' => $class->id,
                'section_id' => $section->id,
                'subject_id' => $english ? $english->id : 1,
                'kind' => 'Quiz',
                'start_time' => Carbon::now()->subDays(2),
                'end_time' => Carbon::now()->subDays(1),
                'duration' => 30,
                'total_marks' => 10.00,
                'passing_percentage' => 50.00,
                'negative_marking' => false,
                'auto_publish_results' => true,
                'status' => 'Completed',
                'instructions' => 'Grammar review quiz. Select the most appropriate grammatical form.',
            ]
        );

        $exam3 = OnlineExam::firstOrCreate(
            ['title' => 'QA test paper (draft, ignore)'],
            [
                'school_id' => $schoolId,
                'school_class_id' => $class->id,
                'section_id' => $section->id,
                'subject_id' => $english ? $english->id : 1,
                'kind' => 'Quiz',
                'start_time' => Carbon::now()->addDays(5),
                'end_time' => Carbon::now()->addDays(6),
                'duration' => 10,
                'total_marks' => 10.00,
                'passing_percentage' => 40.00,
                'status' => 'Upcoming',
                'instructions' => 'Pre-release QA testing exam paper.',
            ]
        );

        // 4. Attach Questions to Exams
        foreach ($createdQuestions as $idx => $q) {
            OnlineExamQuestion::firstOrCreate([
                'online_exam_id' => $exam1->id,
                'online_question_id' => $q->id,
            ], [
                'marks' => $q->marks,
                'sort_order' => $idx + 1,
            ]);

            if ($q->subject_id == ($english ? $english->id : 1)) {
                OnlineExamQuestion::firstOrCreate([
                    'online_exam_id' => $exam2->id,
                    'online_question_id' => $q->id,
                ], [
                    'marks' => $q->marks,
                    'sort_order' => $idx + 1,
                ]);
            }
        }

        // 5. Seed Student Exam Attempts
        $students = Student::where('school_id', $schoolId)->take(5)->get();
        foreach ($students as $index => $st) {
            $score = 18.00 - ($index * 3);
            $percentage = round(($score / 25.00) * 100, 1);
            OnlineExamAttempt::firstOrCreate([
                'online_exam_id' => $exam1->id,
                'student_id' => $st->id,
            ], [
                'school_id' => $schoolId,
                'started_at' => Carbon::now()->subMinutes(60 - $index * 5),
                'submitted_at' => Carbon::now()->subMinutes(15 - $index * 2),
                'total_questions' => 4,
                'total_answered' => 4,
                'correct_answers_count' => 3,
                'incorrect_answers_count' => 1,
                'score_obtained' => max(0, $score),
                'percentage' => max(0, $percentage),
                'status' => $percentage >= 40.0 ? 'Passed' : 'Failed',
                'student_answers' => [
                    1 => ['selected' => 'A', 'is_correct' => true, 'marks_awarded' => 2.0],
                    2 => ['selected' => 'A', 'is_correct' => true, 'marks_awarded' => 2.0],
                    3 => ['selected' => 'B', 'is_correct' => false, 'marks_awarded' => -0.5],
                    4 => ['selected' => 'True', 'is_correct' => true, 'marks_awarded' => 1.0],
                ],
            ]);
        }
    }
}
