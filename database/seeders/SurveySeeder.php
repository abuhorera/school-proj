<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\FeedbackTriageItem;
use Illuminate\Support\Str;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Parent Satisfaction (Term 1)
        $s1 = Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => 'DEMO — Parent Satisfaction (Term 1)',
            'description' => 'How happy are parents with teaching, communication and facilities. Great as a recurring termly pulse.',
            'slug' => 'parent-satisfaction-term-1',
            'access_token' => 'wggetcij1889nxt0zhvornqdiv7j6oi1',
            'kind' => 'feedback_form',
            'status' => 'published',
            'closes_at' => now()->addMonth(),
            'recurrence_freq' => 'Termly',
            'is_anonymous' => false,
            'allow_resubmit' => false,
            'force_popup' => true,
            'one_per_device' => false,
            'audience_type' => 'roles',
            'target_role_ids' => ['Parent'],
            'invited_count' => 31,
            'responses_count' => 36,
            'is_report_published' => true,
            'report_published_at' => now()->subMonths(3),
        ]);

        $q1_1 = SurveyQuestion::create([
            'survey_id' => $s1->id,
            'order_num' => 1,
            'question_text' => 'Overall, how satisfied are you with the school?',
            'question_type' => 'rating_scale',
            'scale_min' => 1,
            'scale_max' => 5,
            'is_required' => true,
        ]);
        $q1_2 = SurveyQuestion::create([
            'survey_id' => $s1->id,
            'order_num' => 2,
            'question_text' => 'How satisfied are you with the quality of teaching?',
            'question_type' => 'rating_scale',
            'scale_min' => 1,
            'scale_max' => 5,
            'is_required' => true,
        ]);
        $q1_3 = SurveyQuestion::create([
            'survey_id' => $s1->id,
            'order_num' => 3,
            'question_text' => 'How satisfied are you with communication from the school?',
            'question_type' => 'rating_scale',
            'scale_min' => 1,
            'scale_max' => 5,
            'is_required' => true,
        ]);
        $q1_4 = SurveyQuestion::create([
            'survey_id' => $s1->id,
            'order_num' => 4,
            'question_text' => 'How likely are you to recommend the school to other parents?',
            'question_type' => 'nps_score',
            'scale_min' => 0,
            'scale_max' => 10,
            'is_required' => true,
        ]);
        $q1_5 = SurveyQuestion::create([
            'survey_id' => $s1->id,
            'order_num' => 5,
            'question_text' => 'What is one thing we could do better?',
            'question_type' => 'paragraph',
            'is_required' => false,
        ]);

        // Seed sample responses for Parent Satisfaction
        for ($i = 1; $i <= 36; $i++) {
            $resp = SurveyResponse::create([
                'survey_id' => $s1->id,
                'respondent_name' => $i === 1 ? 'Tarun Bansal' : ('Parent #' . $i),
                'respondent_role' => 'Parent',
                'device_ip' => '192.168.1.' . (10 + $i),
                'submitted_at' => now()->subDays(rand(1, 20)),
            ]);

            SurveyAnswer::create([
                'response_id' => $resp->id,
                'question_id' => $q1_1->id,
                'numeric_value' => rand(3, 5),
            ]);
            SurveyAnswer::create([
                'response_id' => $resp->id,
                'question_id' => $q1_2->id,
                'numeric_value' => rand(3, 5),
            ]);
            SurveyAnswer::create([
                'response_id' => $resp->id,
                'question_id' => $q1_3->id,
                'numeric_value' => rand(3, 5),
            ]);
            SurveyAnswer::create([
                'response_id' => $resp->id,
                'question_id' => $q1_4->id,
                'numeric_value' => rand(7, 10),
            ]);
            if ($i <= 10) {
                SurveyAnswer::create([
                    'response_id' => $resp->id,
                    'question_id' => $q1_5->id,
                    'answer_text' => $i === 1 ? 'Bus route #4 experiences frequent delays during morning pick-up.' : 'School app notifications could be faster.',
                ]);
            }
        }

        // 2. Teacher Evaluation (Anonymous)
        $s2 = Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => 'DEMO — Teacher Evaluation (Anonymous)',
            'description' => 'Structured feedback on a teacher’s clarity, engagement and support. Usually run anonymously.',
            'slug' => 'teacher-evaluation-anonymous',
            'access_token' => 'wggetcij1889nxt0zhvornqdiv7j6oi2',
            'kind' => 'survey',
            'status' => 'published',
            'closes_at' => now()->addWeeks(2),
            'recurrence_freq' => 'Termly',
            'is_anonymous' => true,
            'allow_resubmit' => false,
            'force_popup' => false,
            'one_per_device' => true,
            'audience_type' => 'classes',
            'target_class_ids' => [1, 2, 3],
            'invited_count' => 40,
            'responses_count' => 28,
            'is_report_published' => true,
            'report_published_at' => now()->subWeeks(1),
        ]);

        $q2_1 = SurveyQuestion::create([
            'survey_id' => $s2->id,
            'order_num' => 1,
            'question_text' => 'The teacher explains complex concepts clearly.',
            'question_type' => 'rating_scale',
            'scale_min' => 1,
            'scale_max' => 5,
            'is_required' => true,
        ]);
        $q2_2 = SurveyQuestion::create([
            'survey_id' => $s2->id,
            'order_num' => 2,
            'question_text' => 'The teacher encourages classroom questions and discussions.',
            'question_type' => 'rating_scale',
            'scale_min' => 1,
            'scale_max' => 5,
            'is_required' => true,
        ]);
        $q2_3 = SurveyQuestion::create([
            'survey_id' => $s2->id,
            'order_num' => 3,
            'question_text' => 'Homework and assignments are graded promptly with helpful remarks.',
            'question_type' => 'yes_no',
            'is_required' => true,
        ]);
        $q2_4 = SurveyQuestion::create([
            'survey_id' => $s2->id,
            'order_num' => 4,
            'question_text' => 'General comments or suggestions for this teacher:',
            'question_type' => 'paragraph',
            'is_required' => false,
        ]);

        for ($i = 1; $i <= 28; $i++) {
            $resp = SurveyResponse::create([
                'survey_id' => $s2->id,
                'respondent_name' => 'Anonymous Pupil',
                'respondent_role' => 'Student',
                'device_ip' => '10.0.0.' . $i,
                'submitted_at' => now()->subDays(rand(1, 14)),
            ]);
            SurveyAnswer::create(['response_id' => $resp->id, 'question_id' => $q2_1->id, 'numeric_value' => rand(4, 5)]);
            SurveyAnswer::create(['response_id' => $resp->id, 'question_id' => $q2_2->id, 'numeric_value' => rand(3, 5)]);
            SurveyAnswer::create(['response_id' => $resp->id, 'question_id' => $q2_3->id, 'answer_text' => rand(0, 1) ? 'Yes' : 'No']);
        }

        // 3. Monthly Wellbeing Pulse
        $s3 = Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => 'DEMO — Monthly Wellbeing Pulse (Recurring)',
            'description' => 'A gentle anonymous check-in on how students are feeling at school.',
            'slug' => 'monthly-wellbeing-pulse',
            'access_token' => 'wggetcij1889nxt0zhvornqdiv7j6oi5',
            'kind' => 'feedback_form',
            'status' => 'published',
            'closes_at' => now()->addDays(10),
            'recurrence_freq' => 'Monthly',
            'is_anonymous' => true,
            'allow_resubmit' => false,
            'force_popup' => false,
            'one_per_device' => false,
            'audience_type' => 'roles',
            'target_role_ids' => ['Student'],
            'invited_count' => 50,
            'responses_count' => 42,
            'is_report_published' => true,
            'report_published_at' => now()->subDays(5),
        ]);

        $q3_1 = SurveyQuestion::create([
            'survey_id' => $s3->id,
            'order_num' => 1,
            'question_text' => 'How happy do you feel at school this week?',
            'question_type' => 'rating_scale',
            'scale_min' => 1,
            'scale_max' => 5,
            'is_required' => true,
        ]);
        $q3_2 = SurveyQuestion::create([
            'survey_id' => $s3->id,
            'order_num' => 2,
            'question_text' => 'Do you feel you have an adult at school you can talk to?',
            'question_type' => 'yes_no',
            'is_required' => true,
        ]);
        $q3_3 = SurveyQuestion::create([
            'survey_id' => $s3->id,
            'order_num' => 3,
            'question_text' => 'Do you feel safe at school?',
            'question_type' => 'yes_no',
            'is_required' => true,
        ]);
        $q3_4 = SurveyQuestion::create([
            'survey_id' => $s3->id,
            'order_num' => 4,
            'question_text' => 'Is there anything you would like to tell us? (optional)',
            'question_type' => 'paragraph',
            'is_required' => false,
        ]);

        // 4. Community Feedback
        Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => 'DEMO — Public — Community Feedback',
            'description' => 'Open community sentiment on public school events and community engagements.',
            'slug' => 'community-feedback',
            'access_token' => 'wggetcij1889nxt0zhvornqdiv7j6oi4',
            'kind' => 'survey',
            'status' => 'published',
            'closes_at' => now()->addMonth(),
            'recurrence_freq' => 'One-off',
            'is_anonymous' => false,
            'audience_type' => 'public',
            'invited_count' => 0,
            'responses_count' => 16,
            'is_report_published' => false,
        ]);

        // 5. Net Promoter (NPS)
        Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => 'Net Promoter (NPS) Pulse 2026',
            'description' => 'The classic one-number loyalty pulse: “How likely are you to recommend us?” plus a reason.',
            'slug' => 'nps-pulse-2026',
            'access_token' => 'wggetcij1889nxt0zhvornqdiv7j6oi6',
            'kind' => 'poll',
            'status' => 'published',
            'closes_at' => now()->addWeeks(3),
            'recurrence_freq' => 'Monthly',
            'is_anonymous' => false,
            'audience_type' => 'all',
            'invited_count' => 80,
            'responses_count' => 68,
            'is_report_published' => true,
            'report_published_at' => now()->subDays(2),
        ]);

        // 6. Annual Curriculum Review 2026 (Draft)
        Survey::create([
            'school_id' => 1,
            'academic_session_id' => 1,
            'title' => 'Annual Curriculum Review 2026',
            'description' => 'Staff and departmental suggestions for curriculum updates and textbooks.',
            'slug' => 'annual-curriculum-review-2026',
            'access_token' => 'wggetcij1889nxt0zhvornqdiv7j6oi7',
            'kind' => 'survey',
            'status' => 'draft',
            'closes_at' => null,
            'recurrence_freq' => 'One-off',
            'is_anonymous' => false,
            'audience_type' => 'roles',
            'target_role_ids' => ['Teacher'],
            'invited_count' => 0,
            'responses_count' => 0,
            'is_report_published' => false,
        ]);

        // Seed Feedback Triage Items
        FeedbackTriageItem::create([
            'school_id' => 1,
            'survey_id' => $s1->id,
            'response_id' => 1,
            'title' => 'DEMO — Follow up: transport delays raised by a parent',
            'status' => 'New',
            'priority' => 'High',
            'assigned_to' => 1,
            'tags' => 'transport, bus-route-4',
            'internal_notes' => 'Transport coordinator instructed to inspect morning route timings.',
            'flagged_by' => 'school admin',
            'flagged_at' => now()->subMonths(3),
        ]);

        FeedbackTriageItem::create([
            'school_id' => 1,
            'survey_id' => $s2->id,
            'response_id' => 5,
            'title' => 'DEMO — Additional remedial classes requested in Mathematics',
            'status' => 'In progress',
            'priority' => 'Urgent',
            'assigned_to' => 2,
            'tags' => 'academics, math, remedial',
            'internal_notes' => 'Head of Science & Math department reviewing student performance.',
            'flagged_by' => 'school admin',
            'flagged_at' => now()->subWeeks(2),
        ]);

        FeedbackTriageItem::create([
            'school_id' => 1,
            'survey_id' => $s3->id,
            'response_id' => 12,
            'title' => 'DEMO — Peer support group suggestion for new admissions',
            'status' => 'Reviewed',
            'priority' => 'Normal',
            'assigned_to' => 3,
            'tags' => 'wellbeing, counseling',
            'internal_notes' => 'Counselor will set up a buddy program for fresh enrollments.',
            'flagged_by' => 'school admin',
            'flagged_at' => now()->subDays(6),
        ]);
    }
}

