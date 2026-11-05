<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LessonPlan;
use App\Models\LessonPlanTopic;
use App\Models\LessonPlannerSetting;
use App\Models\DepartmentHead;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Staff;
use Carbon\Carbon;

class LessonPlannerSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        $class = SchoolClass::first();
        $classId = $class ? $class->id : 1;
        $section = Section::first();
        $sectionId = $section ? $section->id : null;
        $subject = Subject::first();
        $subjectId = $subject ? $subject->id : 1;
        $staffMembers = Staff::where('school_id', $schoolId)->get();

        $t1 = $staffMembers->get(0) ?? Staff::first();
        $t2 = $staffMembers->get(1) ?? $t1;
        $t3 = $staffMembers->get(2) ?? $t1;

        // 1. Lesson Planner Settings
        LessonPlannerSetting::updateOrCreate(
            ['school_id' => $schoolId],
            [
                'hod_step_enabled' => true,
                'allow_edit_after_submit' => false,
                'reminder_enabled' => true,
                'reminder_day' => 'Friday',
                'reminder_time' => '15:00',
                'submission_deadline_day' => 'Monday',
                'submission_deadline_time' => '09:00',
            ]
        );

        // 2. Department Heads
        if ($t1) {
            DepartmentHead::updateOrCreate(
                ['school_id' => $schoolId, 'department' => 'Mathematics'],
                ['head_staff_id' => $t1->id]
            );
        }
        if ($t2) {
            DepartmentHead::updateOrCreate(
                ['school_id' => $schoolId, 'department' => 'Science'],
                ['head_staff_id' => $t2->id]
            );
        }
        if ($t3) {
            DepartmentHead::updateOrCreate(
                ['school_id' => $schoolId, 'department' => 'Languages'],
                ['head_staff_id' => $t3->id]
            );
        }

        // 3. Approved Plan (Mathematics)
        $p1 = LessonPlan::updateOrCreate(
            ['school_id' => $schoolId, 'title' => 'Linear Equations in Two Variables & Graphical Solutions'],
            [
                'topic' => 'Linear Equations in Two Variables & Graphical Solutions',
                'class_id' => $classId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'staff_id' => $t1 ? $t1->id : 1,
                'teacher_id' => $t1 ? $t1->id : 1,
                'week_number' => 46,
                'week_start_date' => Carbon::now()->startOfWeek()->toDateString(),
                'week_end_date' => Carbon::now()->endOfWeek()->toDateString(),
                'planned_periods' => 5,
                'chapter_names' => 'Chapter 3: Pair of Linear Equations in Two Variables',
                'learning_objectives' => 'Students will understand consistent and inconsistent systems, plot linear graphs on coordinate planes, and solve simultaneous algebraic equations using substitution and elimination.',
                'teaching_methods' => ['Lecture', 'Problem Solving', 'Demonstration'],
                'teaching_aids' => ['Whiteboard', 'Smart Board', 'Worksheets'],
                'resources_required' => 'Graph books, geometry compass rulers, NCERT exemplar problem sheet.',
                'homework_planned' => 'Exercise 3.2 Questions 1 to 5; submit assignment on Friday.',
                'status' => 'Approved',
                'coverage_percentage' => 80.00,
                'submitted_at' => Carbon::now()->subDays(5),
                'approved_at' => Carbon::now()->subDays(3),
                'approved_by' => $t1 ? $t1->id : 1,
                'review_feedback' => 'Comprehensive pedagogy and well-paced period distribution.',
            ]
        );

        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p1->id, 'topic_name' => 'Graphical Representation of Linear Equations'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Covered', 'covered_on' => Carbon::now()->subDays(4)->toDateString()]
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p1->id, 'topic_name' => 'Algebraic Substitution Method'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Covered', 'covered_on' => Carbon::now()->subDays(3)->toDateString()]
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p1->id, 'topic_name' => 'Elimination by Equating Coefficients'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Covered', 'covered_on' => Carbon::now()->subDays(2)->toDateString()]
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p1->id, 'topic_name' => 'Cross-Multiplication Formula Technique'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Covered', 'covered_on' => Carbon::now()->subDays(1)->toDateString()]
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p1->id, 'topic_name' => 'Word Problems & Real-World Modeling'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Partial', 'coverage_reason' => 'Extended time needed on upstream-downstream boat problem', 'covered_on' => Carbon::now()->toDateString()]
        );

        // 4. Pending HOD Review Plan (Science)
        $p2 = LessonPlan::updateOrCreate(
            ['school_id' => $schoolId, 'title' => 'Photosynthesis & Autotrophic Plant Nutrition'],
            [
                'topic' => 'Photosynthesis & Autotrophic Plant Nutrition',
                'class_id' => $classId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'staff_id' => $t2 ? $t2->id : 1,
                'teacher_id' => $t2 ? $t2->id : 1,
                'week_number' => 47,
                'week_start_date' => Carbon::now()->addDays(2)->startOfWeek()->toDateString(),
                'week_end_date' => Carbon::now()->addDays(2)->endOfWeek()->toDateString(),
                'planned_periods' => 4,
                'chapter_names' => 'Chapter 6: Life Processes (Biological Systems)',
                'learning_objectives' => 'Explain chloroplast anatomy, light-dependent Hill reaction, stomatal aperture mechanics, and test starch presence in variegated leaves.',
                'teaching_methods' => ['Demonstration', 'Practical/Lab', 'Discussion'],
                'teaching_aids' => ['Models/Charts', 'Lab Equipment', 'PPT Slides'],
                'resources_required' => 'Microscopes, iodine solution, boiled leaf apparatus, spirit burner.',
                'homework_planned' => 'Draw and label the internal cross-section of a dicot leaf.',
                'status' => 'Pending HOD',
                'coverage_percentage' => 0.00,
                'submitted_at' => Carbon::now()->subHours(8),
            ]
        );

        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p2->id, 'topic_name' => 'Chloroplast Structure & Pigment Absorption Spectra'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Pending']
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p2->id, 'topic_name' => 'Light Reactions & Photolysis of Water'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Pending']
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p2->id, 'topic_name' => 'Calvin Cycle & Biosynthetic Phase'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Pending']
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p2->id, 'topic_name' => 'Laboratory Practical: Starch Test on Hydrilla'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Pending']
        );

        // 5. Pending Principal Approval Plan (English Literature)
        $p3 = LessonPlan::updateOrCreate(
            ['school_id' => $schoolId, 'title' => 'The Merchant of Venice — Act III Casket Choice'],
            [
                'topic' => 'The Merchant of Venice — Act III Casket Choice',
                'class_id' => $classId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'staff_id' => $t3 ? $t3->id : 1,
                'teacher_id' => $t3 ? $t3->id : 1,
                'week_number' => 47,
                'week_start_date' => Carbon::now()->addDays(2)->startOfWeek()->toDateString(),
                'week_end_date' => Carbon::now()->addDays(2)->endOfWeek()->toDateString(),
                'planned_periods' => 3,
                'chapter_names' => 'Drama: Shakespeare The Merchant of Venice Act III Scene 2',
                'learning_objectives' => 'Examine dramatic tension during Bassanio selection of the lead casket, Portia lyrical subtext, and news of Antonio lost argosies.',
                'teaching_methods' => ['Discussion', 'Group Activity', 'Flipped Classroom'],
                'teaching_aids' => ['Video/Audio', 'Whiteboard'],
                'resources_required' => 'Audio recording of Royal Shakespeare Company staging.',
                'homework_planned' => 'Write a 250-word critical reflection on Bassanio reasoning over gold versus lead.',
                'status' => 'Pending Principal',
                'coverage_percentage' => 0.00,
                'submitted_at' => Carbon::now()->subDays(1),
                'reviewed_by' => $t3 ? $t3->id : 1,
                'review_feedback' => 'HOD approved; recommended for Principal sign-off.',
            ]
        );

        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p3->id, 'topic_name' => 'Bassanio Deliberation on True Worth'],
            ['school_id' => $schoolId, 'periods_allocated' => 2, 'coverage_status' => 'Pending']
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p3->id, 'topic_name' => 'Salerio Arrival and Shylock Bond Forfeiture'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Pending']
        );

        // 6. Draft Plan
        $p4 = LessonPlan::updateOrCreate(
            ['school_id' => $schoolId, 'title' => 'Causes of World War II & Treaty of Versailles'],
            [
                'topic' => 'Causes of World War II & Treaty of Versailles',
                'class_id' => $classId,
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'staff_id' => $t1 ? $t1->id : 1,
                'teacher_id' => $t1 ? $t1->id : 1,
                'week_number' => 48,
                'week_start_date' => Carbon::now()->addDays(9)->startOfWeek()->toDateString(),
                'week_end_date' => Carbon::now()->addDays(9)->endOfWeek()->toDateString(),
                'planned_periods' => 3,
                'chapter_names' => 'Chapter 4: The Contemporary World',
                'learning_objectives' => 'Trace the dissatisfaction caused by the Treaty of Versailles, rise of totalitarian regimes, and failure of collective security.',
                'teaching_methods' => ['Lecture', 'Discussion'],
                'teaching_aids' => ['Models/Charts', 'PPT Slides'],
                'status' => 'Draft',
                'coverage_percentage' => 0.00,
            ]
        );

        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p4->id, 'topic_name' => 'Territorial & Economic Clauses of 1919 Versailles Treaty'],
            ['school_id' => $schoolId, 'periods_allocated' => 1, 'coverage_status' => 'Pending']
        );
        LessonPlanTopic::updateOrCreate(
            ['lesson_plan_id' => $p4->id, 'topic_name' => 'Appeasement Policy and Invasion of Poland'],
            ['school_id' => $schoolId, 'periods_allocated' => 2, 'coverage_status' => 'Pending']
        );
    }
}
