<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OsmSession;
use App\Models\OsmQuestion;
use App\Models\OsmPaper;
use App\Models\OsmPaperScore;
use App\Models\OsmAnnotation;
use App\Models\OsmSessionEvaluator;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\Student;

class OsmModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $class = SchoolClass::first();
        $section = Section::first();
        $subject = Subject::first();
        $staff = Staff::first();
        $students = Student::take(10)->get();

        // 1. Session 1: Term1 (Evaluating)
        $s1 = OsmSession::updateOrCreate(
            ['title' => 'Term1'],
            [
                'school_id' => 1,
                'school_class_id' => $class?->id,
                'section_id' => $section?->id,
                'subject_id' => $subject?->id,
                'total_marks' => 20.00,
                'evaluation_deadline' => now()->addDays(7),
                'moderation_level' => 'sample_10',
                'sync_to_report_card' => true,
                'publish_to_parent' => false,
                'anonymous_mode' => true,
                'allow_annotations' => true,
                'status' => 'Evaluating',
                'total_sheets' => 3,
                'evaluated_sheets' => 1,
            ]
        );

        // Questions for Session 1
        $q1 = OsmQuestion::updateOrCreate(
            ['osm_session_id' => $s1->id, 'question_number' => 1],
            [
                'school_id' => 1,
                'question_label' => 'Q1',
                'question_text' => 'Read the passage given below and answer the conceptual comprehension questions.',
                'max_marks' => 10.00,
                'is_compulsory' => true,
                'order_seq' => 1,
            ]
        );

        $q2 = OsmQuestion::updateOrCreate(
            ['osm_session_id' => $s1->id, 'question_number' => 2],
            [
                'school_id' => 1,
                'question_label' => 'Q2',
                'question_text' => 'Draft a formal invitation letter for the Annual Inter-School Science Fair (50 words).',
                'max_marks' => 10.00,
                'is_compulsory' => true,
                'order_seq' => 2,
            ]
        );

        // Evaluator for Session 1
        if ($staff) {
            OsmSessionEvaluator::updateOrCreate(
                ['osm_session_id' => $s1->id, 'staff_id' => $staff->id],
                ['assigned_count' => 3, 'completed_count' => 1, 'status' => 'Active']
            );
        }

        // Paper 1: Pending evaluation
        if ($students->count() > 0) {
            $p1 = OsmPaper::updateOrCreate(
                ['osm_session_id' => $s1->id, 'student_id' => $students[0]->id],
                [
                    'school_id' => 1,
                    'evaluator_staff_id' => $staff?->id,
                    'anonymous_code' => 'OSM-XI-7821',
                    'original_file_name' => 'ans_sheet_ansh_sharma_english.pdf',
                    'page_count' => 38,
                    'status' => 'Pending',
                    'total_score' => 0.00,
                    'elapsed_seconds' => 45,
                ]
            );

            // Paper 2: Evaluated paper
            if ($students->count() > 1) {
                $p2 = OsmPaper::updateOrCreate(
                    ['osm_session_id' => $s1->id, 'student_id' => $students[1]->id],
                    [
                        'school_id' => 1,
                        'evaluator_staff_id' => $staff?->id,
                        'anonymous_code' => 'OSM-XI-7822',
                        'original_file_name' => 'ans_sheet_kabir_singh_english.pdf',
                        'page_count' => 12,
                        'status' => 'Evaluated',
                        'total_score' => 18.00,
                        'elapsed_seconds' => 420,
                        'evaluated_at' => now()->subDay(),
                        'evaluator_notes' => 'Excellent vocabulary and structured arguments in reading comprehension.',
                    ]
                );

                OsmPaperScore::updateOrCreate(
                    ['osm_paper_id' => $p2->id, 'osm_question_id' => $q1->id],
                    ['marks_awarded' => 9.00, 'comment' => 'Accurate inferences in Q1(a)-(e).']
                );
                OsmPaperScore::updateOrCreate(
                    ['osm_paper_id' => $p2->id, 'osm_question_id' => $q2->id],
                    ['marks_awarded' => 9.00, 'comment' => 'Clean format and tone.']
                );

                // Add sample tick/stamp annotations
                OsmAnnotation::updateOrCreate(
                    ['osm_paper_id' => $p2->id, 'page_number' => 1, 'tool_type' => 'tick'],
                    ['x' => 120.50, 'y' => 240.00, 'data_json' => '{"stamp":"tick","color":"#16a34a"}']
                );
                OsmAnnotation::updateOrCreate(
                    ['osm_paper_id' => $p2->id, 'page_number' => 1, 'tool_type' => 'pen'],
                    ['x' => 140.00, 'y' => 280.00, 'data_json' => '{"stroke":"underline","color":"#dc2626"}']
                );
            }

            // Paper 3: Moderated paper
            if ($students->count() > 2) {
                $p3 = OsmPaper::updateOrCreate(
                    ['osm_session_id' => $s1->id, 'student_id' => $students[2]->id],
                    [
                        'school_id' => 1,
                        'evaluator_staff_id' => $staff?->id,
                        'moderator_staff_id' => $staff?->id,
                        'anonymous_code' => 'OSM-XI-7823',
                        'original_file_name' => 'ans_sheet_rohan_verma_english.pdf',
                        'page_count' => 15,
                        'status' => 'Moderated',
                        'total_score' => 14.00,
                        'moderated_score' => 15.50,
                        'is_moderated' => true,
                        'moderated_at' => now()->subHours(5),
                        'moderator_notes' => 'Benefit of doubt given for Q1(c) alternative textual interpretation (+1.5m).',
                        'elapsed_seconds' => 310,
                    ]
                );

                OsmPaperScore::updateOrCreate(
                    ['osm_paper_id' => $p3->id, 'osm_question_id' => $q1->id],
                    ['marks_awarded' => 7.50, 'comment' => 'Moderated up from 6.0']
                );
                OsmPaperScore::updateOrCreate(
                    ['osm_paper_id' => $p3->id, 'osm_question_id' => $q2->id],
                    ['marks_awarded' => 8.00, 'comment' => 'Format adheres to rubric.']
                );
            }
        }

        // 2. Session 2: DEMO — OSM — English (Class I) (Completed)
        $s2 = OsmSession::updateOrCreate(
            ['title' => 'DEMO — OSM — English (Class I)'],
            [
                'school_id' => 1,
                'school_class_id' => $class?->id,
                'section_id' => $section?->id,
                'subject_id' => $subject?->id,
                'total_marks' => 25.00,
                'evaluation_deadline' => now()->subDays(2),
                'moderation_level' => 'sample_10',
                'sync_to_report_card' => true,
                'publish_to_parent' => true,
                'anonymous_mode' => true,
                'allow_annotations' => true,
                'status' => 'Completed',
                'total_sheets' => 11,
                'evaluated_sheets' => 11,
            ]
        );

        // 3. Session 3: DEMO — OSM — English (Class III) (Completed)
        OsmSession::updateOrCreate(
            ['title' => 'DEMO — OSM — English (Class III)'],
            [
                'school_id' => 1,
                'school_class_id' => $class?->id,
                'section_id' => $section?->id,
                'subject_id' => $subject?->id,
                'total_marks' => 30.00,
                'evaluation_deadline' => now()->subDays(5),
                'moderation_level' => 'none',
                'sync_to_report_card' => true,
                'publish_to_parent' => true,
                'anonymous_mode' => true,
                'allow_annotations' => true,
                'status' => 'Completed',
                'total_sheets' => 12,
                'evaluated_sheets' => 12,
            ]
        );

        // 4. Session 4: DEMO — OSM — English (Class V) (Completed)
        OsmSession::updateOrCreate(
            ['title' => 'DEMO — OSM — English (Class V)'],
            [
                'school_id' => 1,
                'school_class_id' => $class?->id,
                'section_id' => $section?->id,
                'subject_id' => $subject?->id,
                'total_marks' => 40.00,
                'evaluation_deadline' => now()->subDays(10),
                'moderation_level' => 'sample_20',
                'sync_to_report_card' => true,
                'publish_to_parent' => true,
                'anonymous_mode' => true,
                'allow_annotations' => true,
                'status' => 'Completed',
                'total_sheets' => 12,
                'evaluated_sheets' => 12,
            ]
        );

        // 5. Session 5: Mid-Term Mathematics & Analytical Reasoning (Evaluating)
        OsmSession::updateOrCreate(
            ['title' => 'Mid-Term Mathematics & Analytical Reasoning'],
            [
                'school_id' => 1,
                'school_class_id' => $class?->id,
                'section_id' => $section?->id,
                'subject_id' => $subject?->id,
                'total_marks' => 50.00,
                'evaluation_deadline' => now()->addDays(5),
                'moderation_level' => 'full',
                'sync_to_report_card' => false,
                'publish_to_parent' => false,
                'anonymous_mode' => true,
                'allow_annotations' => true,
                'status' => 'Evaluating',
                'total_sheets' => 5,
                'evaluated_sheets' => 3,
            ]
        );
    }
}

