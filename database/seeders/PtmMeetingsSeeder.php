<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PtmMeeting;
use App\Models\PtmAttendance;
use App\Models\PtmFollowup;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Staff;
use Carbon\Carbon;

class PtmMeetingsSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;
        $class = SchoolClass::first();
        $classId = $class ? $class->id : 1;
        $students = Student::where('school_id', $schoolId)->get();
        $staffMembers = Staff::where('school_id', $schoolId)->get();
        $primaryTeacher = $staffMembers->first();

        // 1. Term 1 Academic Performance PTM (Scheduled / Notified)
        $m1 = PtmMeeting::updateOrCreate(
            ['school_id' => $schoolId, 'title' => 'Term 1 Academic Progress & Holistic Review PTM'],
            [
                'meeting_date' => Carbon::now()->addDays(5)->toDateString(),
                'start_time' => '09:00',
                'end_time' => '13:00',
                'mode' => 'in_person',
                'venue' => 'School Main Auditorium & Classrooms',
                'online_link' => null,
                'scope' => 'whole_school',
                'school_class_id' => null,
                'section_id' => null,
                'description' => 'Discussion on Term 1 formative assessments, CBC skill progression, behavioral feedback, and individualized learning support.',
                'status' => 'Notified',
            ]
        );

        // 2. Class 10 Board Exam Readiness PTM (Scheduled)
        $m2 = PtmMeeting::updateOrCreate(
            ['school_id' => $schoolId, 'title' => 'Senior Secondary Board Readiness & Career Orientation'],
            [
                'meeting_date' => Carbon::now()->addDays(14)->toDateString(),
                'start_time' => '10:00',
                'end_time' => '12:30',
                'mode' => 'hybrid',
                'venue' => 'Conference Hall A & Online Stream',
                'online_link' => 'https://meet.google.com/ptm-exam-prep',
                'scope' => 'class',
                'class_id' => $classId,
                'school_class_id' => $classId,
                'section_id' => null,
                'description' => 'Orientation on board examination revision blueprints, stress management, and scholarship streams.',
                'status' => 'Scheduled',
            ]
        );

        // 3. Past Completed PTM
        $m3 = PtmMeeting::updateOrCreate(
            ['school_id' => $schoolId, 'title' => 'Mid-Term Foundation & Early Years Developmental Check-in'],
            [
                'meeting_date' => Carbon::now()->subDays(12)->toDateString(),
                'start_time' => '09:30',
                'end_time' => '12:00',
                'mode' => 'in_person',
                'venue' => 'Primary Wing Activity Center',
                'online_link' => null,
                'scope' => 'whole_school',
                'school_class_id' => null,
                'section_id' => null,
                'description' => 'Assessment of foundational literacy, numeracy milestones, socialization, and co-curricular achievements.',
                'status' => 'Completed',
            ]
        );

        // Populate Attendances for Meeting 1 & Meeting 3
        $slots = ['09:00 AM - 09:15 AM', '09:15 AM - 09:30 AM', '09:30 AM - 09:45 AM', '09:45 AM - 10:00 AM', '10:00 AM - 10:15 AM', '10:15 AM - 10:30 AM'];

        foreach ($students as $index => $st) {
            $slot = $slots[$index % count($slots)];

            // For Upcoming Meeting 1:
            PtmAttendance::updateOrCreate(
                ['school_id' => $schoolId, 'ptm_meeting_id' => $m1->id, 'student_id' => $st->id],
                [
                    'guardian_id' => $st->guardian_id,
                    'slot_time' => $slot,
                    'attendance_status' => $index % 3 == 0 ? 'Present' : 'Pending',
                    'is_notified' => true,
                    'performance_remark' => $index % 3 == 0 ? 'Consistent academic dedication and sharp analytical thinking.' : null,
                    'attendance_remark' => $index % 3 == 0 ? 'Regular and prompt.' : null,
                    'behaviour_remark' => $index % 3 == 0 ? 'Respectful and collaborative with classmates.' : null,
                    'overall_rating' => $index % 3 == 0 ? 5 : null,
                    'visible_to_parent' => true,
                ]
            );

            // For Completed Meeting 3:
            $stat = ($index % 5 == 0) ? 'Absent' : 'Present';
            PtmAttendance::updateOrCreate(
                ['school_id' => $schoolId, 'ptm_meeting_id' => $m3->id, 'student_id' => $st->id],
                [
                    'guardian_id' => $st->guardian_id,
                    'slot_time' => $slot,
                    'attendance_status' => $stat,
                    'is_notified' => true,
                    'performance_remark' => $stat == 'Present' ? 'Active participation in interactive exercises; reading fluency has improved significantly.' : 'Parent was unavailable; rescheduled brief audio call.',
                    'attendance_remark' => $stat == 'Present' ? 'Excellent attendance track record.' : 'Late arrival due to transit.',
                    'behaviour_remark' => $stat == 'Present' ? 'Exemplary classroom conduct.' : null,
                    'overall_rating' => $stat == 'Present' ? ($index % 2 == 0 ? 5 : 4) : 2,
                    'visible_to_parent' => true,
                ]
            );
        }

        // Follow-ups
        if ($students->count() >= 3) {
            PtmFollowup::updateOrCreate(
                ['school_id' => $schoolId, 'ptm_meeting_id' => $m1->id, 'student_id' => $students[0]->id],
                [
                    'staff_id' => $primaryTeacher ? $primaryTeacher->id : 1,
                    'action_plan' => 'Provide weekly supplementary practice sheets in Geometry and Algebra.',
                    'assigned_to' => $primaryTeacher ? ($primaryTeacher->first_name . ' ' . $primaryTeacher->last_name) : 'Senior Mathematics Teacher',
                    'due_date' => Carbon::now()->addDays(15)->toDateString(),
                    'status' => 'Open',
                    'admin_notes' => 'Parent agreed to monitor 30 mins daily practice at home.',
                ]
            );

            PtmFollowup::updateOrCreate(
                ['school_id' => $schoolId, 'ptm_meeting_id' => $m3->id, 'student_id' => $students[1]->id],
                [
                    'staff_id' => $primaryTeacher ? $primaryTeacher->id : 1,
                    'action_plan' => 'Conduct reading lab diagnostic and assign phonics audiobooks.',
                    'assigned_to' => 'Priya Nair (Class Teacher)',
                    'due_date' => Carbon::now()->addDays(7)->toDateString(),
                    'status' => 'In progress',
                    'admin_notes' => 'Student enrolled in language enrichment club.',
                ]
            );

            PtmFollowup::updateOrCreate(
                ['school_id' => $schoolId, 'ptm_meeting_id' => $m3->id, 'student_id' => $students[2]->id],
                [
                    'staff_id' => $primaryTeacher ? $primaryTeacher->id : 1,
                    'action_plan' => 'Adjust seating to the front row and verify ophthalmologist vision assessment.',
                    'assigned_to' => 'Sunita Verma',
                    'due_date' => Carbon::now()->subDays(3)->toDateString(),
                    'status' => 'Completed',
                    'admin_notes' => 'New prescription glasses received and verified.',
                ]
            );
        }
    }
}

