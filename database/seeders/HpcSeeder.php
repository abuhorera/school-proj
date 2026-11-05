<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HpcFramework;
use App\Models\HpcActivity;
use App\Models\HpcActivityMark;
use App\Models\HpcCard;
use App\Models\HpcCardSetting;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\AcademicSession;

class HpcSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        HpcCardSetting::firstOrCreate(['id' => 1], [
            'theme' => 'playful',
            'edition_label' => 'PARAKH NEP-2020 Validated',
            'show_radar_chart' => true,
            'show_monthly_attendance' => true,
            'show_health_metrics' => true,
            'principal_name' => 'Dr. Rajesh Sharma',
        ]);

        // 2. Frameworks
        $fwFoundational = HpcFramework::firstOrCreate(['code' => 'PARAKH-Foundational-2024'], [
            'title' => 'Holistic Progress Card — Foundational Stage',
            'stage' => 'Foundational',
            'description' => '360-degree holistic assessment framework for Foundational Stage (Ages 3-8, Nursery to Class 2) in accordance with NEP 2020 & NCF-FS.',
            'is_active' => true,
            'is_locked' => true,
            'levels_json' => [
                ['code' => 'B', 'name' => 'Beginner', 'description' => 'Developing with support and scaffolding'],
                ['code' => 'P', 'name' => 'Proficient', 'description' => 'Demonstrates competency independently and consistently'],
                ['code' => 'A', 'name' => 'Advanced', 'description' => 'Extends competency innovatively to novel situations'],
            ],
            'domains_json' => [
                [
                    'code' => 'PD',
                    'name' => 'Physical Development',
                    'descriptors' => ['Gross motor balance', 'Fine motor coordination', 'Personal hygiene habits', 'Nutritional awareness'],
                ],
                [
                    'code' => 'SED',
                    'name' => 'Socio-Emotional and Ethical Development',
                    'descriptors' => ['Empathy towards peers', 'Emotional self-regulation', 'Cooperative play', 'Kindness and sharing'],
                ],
                [
                    'code' => 'CD',
                    'name' => 'Cognitive Development',
                    'descriptors' => ['Sensory inquiry and observation', 'Problem-solving persistence', 'Pattern recognition', 'Logical reasoning'],
                ],
                [
                    'code' => 'LLD',
                    'name' => 'Language and Literacy Development',
                    'descriptors' => ['Active listening', 'Expressive vocabulary', 'Phonemic awareness', 'Early mark-making/writing'],
                ],
                [
                    'code' => 'ACD',
                    'name' => 'Aesthetic and Cultural Development',
                    'descriptors' => ['Rhythmic movement and music', 'Visual arts and craft', 'Dramatic roleplay', 'Appreciation of beauty'],
                ],
                [
                    'code' => 'PLH',
                    'name' => 'Positive Learning Habits',
                    'descriptors' => ['Curiosity to explore', 'Concentration on task', 'Care for environment and materials'],
                ],
            ],
            'parts_json' => [
                ['code' => 'A1', 'title' => 'Part A(1) — General & Health Information', 'filled_by' => 'Teacher', 'required' => true],
                ['code' => 'A2', 'title' => 'Part A(2) — Self-Assessment (Child\'s Voice)', 'filled_by' => 'Learner with Teacher', 'required' => true],
                ['code' => 'A3', 'title' => 'Part A(3) — Peer Assessment (Peer\'s Voice)', 'filled_by' => 'Peer', 'required' => true],
                ['code' => 'A4', 'title' => 'Part A(4) — Parent & Caregiver Feedback', 'filled_by' => 'Parent', 'required' => true],
                ['code' => 'B',  'title' => 'Part B — Developmental Domain Competencies', 'filled_by' => 'Teacher', 'required' => true],
                ['code' => 'C',  'title' => 'Part C — Annual Holistic Narrative & Signatures', 'filled_by' => 'Teacher & Head', 'required' => true],
            ],
        ]);

        $fwPreparatory = HpcFramework::firstOrCreate(['code' => 'PARAKH-Preparatory-2024'], [
            'title' => 'Holistic Progress Card — Preparatory Stage',
            'stage' => 'Preparatory',
            'description' => 'NCF Preparatory Stage (Ages 8-11, Classes 3 to 5) multi-dimensional assessment with peer and self reflection.',
            'is_active' => true,
            'is_locked' => false,
            'levels_json' => [
                ['code' => 'B', 'name' => 'Beginner', 'description' => 'Developing with teacher scaffolding'],
                ['code' => 'P', 'name' => 'Proficient', 'description' => 'Competent and independent'],
                ['code' => 'A', 'name' => 'Advanced', 'description' => 'Demonstrates mastery and mentors others'],
            ],
            'domains_json' => [
                ['code' => 'MSI', 'name' => 'Mathematical & Scientific Inquiry', 'descriptors' => ['Numerical reasoning', 'Scientific investigation', 'Spatial sense']],
                ['code' => 'LFL', 'name' => 'Language Fluency & Literature', 'descriptors' => ['Reading comprehension', 'Creative writing', 'Bilingual fluency']],
                ['code' => 'SSV', 'name' => 'Social Sensitivities & Values', 'descriptors' => ['Civic consciousness', 'Environmental ethics', 'Inclusivity']],
                ['code' => 'AHE', 'name' => 'Arts & Heritage Expression', 'descriptors' => ['Visual arts', 'Performing arts', 'Cultural heritage']],
                ['code' => 'PFE', 'name' => 'Physical Fitness & Sportsmanship', 'descriptors' => ['Team sports', 'Endurance and agility', 'Fair play']],
            ],
            'parts_json' => [
                ['code' => 'A1', 'title' => 'Part A(1) — Learner Profile & Health', 'filled_by' => 'Teacher', 'required' => true],
                ['code' => 'A2', 'title' => 'Part A(2) — Self Reflection & Goal Setting', 'filled_by' => 'Learner', 'required' => true],
                ['code' => 'A3', 'title' => 'Part A(3) — Peer Feedback & Team Collaboration', 'filled_by' => 'Peer', 'required' => true],
                ['code' => 'A4', 'title' => 'Part A(4) — Parent Perception & Home Observations', 'filled_by' => 'Parent', 'required' => true],
                ['code' => 'B',  'title' => 'Part B — Subject & Skill Competency Matrix', 'filled_by' => 'Teacher', 'required' => true],
                ['code' => 'C',  'title' => 'Part C — Year-end Holistic Evaluation', 'filled_by' => 'Teacher & Principal', 'required' => true],
            ],
        ]);

        // Assign classes to Frameworks
        $classes = SchoolClass::all();
        foreach ($classes as $cls) {
            $name = strtolower($cls->name);
            if (str_contains($name, 'nursery') || str_contains($name, 'lkg') || str_contains($name, 'ukg') || str_contains($name, 'class 1') || str_contains($name, 'class 2')) {
                $fwFoundational->classes()->syncWithoutDetaching([$cls->id]);
            } elseif (str_contains($name, 'class 3') || str_contains($name, 'class 4') || str_contains($name, 'class 5')) {
                $fwPreparatory->classes()->syncWithoutDetaching([$cls->id]);
            }
        }

        // 3. Activities
        $targetClass = SchoolClass::first() ?? SchoolClass::create(['name' => 'Class 1']);
        $act1 = HpcActivity::firstOrCreate(['title' => 'Demo activity · Aesthetic and Cultural Development'], [
            'framework_id' => $fwFoundational->id,
            'school_class_id' => $targetClass->id,
            'activity_type' => 'Learning Activity',
            'domain' => 'Aesthetic and Cultural Development',
            'interaction_type' => 'Small Group',
            'duration_minutes' => 45,
            'term' => 'Full year',
            'prompt' => 'Learners collaborate in small groups to compose a rhythm using clapping and natural instruments, illustrating animal sounds.',
            'pedagogies_json' => ['Arts-integrated', 'Play-based', 'Experiential'],
            'conducted_date' => now()->subDays(3)->toDateString(),
        ]);

        $act2 = HpcActivity::firstOrCreate(['title' => 'Nature Leaf Inquiry & Pattern Sorting'], [
            'framework_id' => $fwFoundational->id,
            'school_class_id' => $targetClass->id,
            'activity_type' => 'Group Project Work',
            'domain' => 'Cognitive Development',
            'interaction_type' => 'Pair',
            'duration_minutes' => 40,
            'term' => 'Term 1',
            'prompt' => 'Collect different fallen leaves from the school garden, classify by size and vein patterns, and count in groups of five.',
            'pedagogies_json' => ['Toy-based', 'Experiential'],
            'conducted_date' => now()->subDays(6)->toDateString(),
        ]);

        $act3 = HpcActivity::firstOrCreate(['title' => 'Circle Time Feelings Wheel & Empathy Drama'], [
            'framework_id' => $fwFoundational->id,
            'school_class_id' => $targetClass->id,
            'activity_type' => 'Learning Activity',
            'domain' => 'Socio-Emotional and Ethical Development',
            'interaction_type' => 'Whole Class',
            'duration_minutes' => 35,
            'term' => 'Full year',
            'prompt' => 'Students spin the emotion wheel, share a moment when they experienced that feeling, and discuss how friends can support each other.',
            'pedagogies_json' => ['Storytelling', 'Experiential'],
            'conducted_date' => now()->subDays(10)->toDateString(),
        ]);

        // 4. Students & Cards
        $students = Student::take(6)->get();
        $session = AcademicSession::where('is_current', 1)->first() ?? AcademicSession::first();
        $sessionId = $session ? $session->id : 1;

        $statuses = ['teacher_submitted', 'reviewed', 'published', 'draft', 'draft', 'draft'];
        $idx = 0;

        foreach ($students as $stu) {
            $status = $statuses[$idx % count($statuses)];
            $idx++;

            // Create marks for activity 1
            HpcActivityMark::updateOrCreate(
                ['activity_id' => $act1->id, 'student_id' => $stu->id],
                [
                    'levels_json' => [
                        'Awareness' => ($idx % 2 == 0) ? 'A' : 'P',
                        'Sensitivity' => 'P',
                        'Creativity' => ($idx % 3 == 0) ? 'A' : 'B',
                    ],
                    'observations' => "{$stu->first_name} engaged enthusiastically with rhythmic beats and supported peers kindly during rehearsals.",
                    'challenges' => 'Needed slight encouragement to speak up in the opening round, but quickly took the lead.',
                ]
            );

            // Create HPC Card
            $attendanceMonths = [
                'Apr' => ['working' => 22, 'present' => 21],
                'May' => ['working' => 12, 'present' => 12],
                'Jun' => ['working' => 10, 'present' => 9],
                'Jul' => ['working' => 24, 'present' => 23],
                'Aug' => ['working' => 23, 'present' => 22],
                'Sep' => ['working' => 22, 'present' => 21],
                'Oct' => ['working' => 20, 'present' => 19],
                'Nov' => ['working' => 21, 'present' => 20],
                'Dec' => ['working' => 20, 'present' => 19],
                'Jan' => ['working' => 22, 'present' => 21],
                'Feb' => ['working' => 21, 'present' => 21],
                'Mar' => ['working' => 23, 'present' => 22],
                'total_working' => 240,
                'total_present' => 230,
                'percentage' => 95.8,
            ];

            HpcCard::firstOrCreate(
                ['student_id' => $stu->id, 'term' => 'Full year'],
                [
                    'school_class_id' => $targetClass->id,
                    'academic_session_id' => $sessionId,
                    'framework_id' => $fwFoundational->id,
                    'stage' => 'Foundational',
                    'status' => $status,
                    'submitted_at' => in_array($status, ['teacher_submitted', 'reviewed', 'published']) ? now()->subDays(4) : null,
                    'reviewed_at' => in_array($status, ['reviewed', 'published']) ? now()->subDays(2) : null,
                    'published_at' => ($status === 'published') ? now()->subDay() : null,
                    'general_info_json' => [
                        'school_name' => 'YUG INTERNATIONAL SCHOOL',
                        'school_address' => 'Bhatagaon, Raipur, Chhattisgarh 490021',
                        'village_ward' => 'Ward 42, Central Raipur',
                        'brc' => 'Raipur Urban BRC',
                        'crc' => 'Bhatagaon CRC',
                        'district' => 'Raipur',
                        'state' => 'Chhattisgarh',
                        'pin_code' => '490021',
                        'udise_code' => '22100405102',
                        'teacher_code' => 'TCH-2024-99',
                        'apaar_id' => '9845-1234-8765',
                        'udid' => 'NA',
                        'blood_group' => $stu->blood_group ?? 'B+',
                        'height_cm' => 114,
                        'weight_kg' => 19.5,
                    ],
                    'attendance_json' => $attendanceMonths,
                    'self_assessment_json' => [
                        'likes' => 'I love drawing bright peacocks, singing the morning song, and building tall wooden block castles.',
                        'strengths' => 'I share my crayons, listen carefully to stories, and always put toys back in their baskets.',
                        'goals' => 'I want to read long fairy tales all by myself and learn how to ride a two-wheeler bicycle.',
                        'favorite_corner' => 'Arts & Crafts Studio',
                        'self_emoji' => 'Happy / Excited',
                    ],
                    'peer_assessment_json' => [
                        'peer_name' => 'Aarav Sharma (Roll 1)',
                        'peer_remarks' => "{$stu->first_name} is very friendly, always takes turns on the slide, and helped me find my missing eraser.",
                        'cooperation_rating' => 'Always Helpful & Cooperative',
                    ],
                    'parent_feedback_json' => [
                        'parent_name' => 'Mrs. & Mr. ' . ($stu->last_name ?? 'Sharma'),
                        'habits_at_home' => "Shares daily school experiences eagerly during dinner. Enjoys picture books and brushes teeth without reminder.",
                        'parent_observation' => 'Shows keen curiosity about nature, birds, and colors. Very affectionate and responsible with younger siblings.',
                        'suggestions' => 'We are very delighted with the positive progress and communicative confidence nurtured by the school.',
                    ],
                    'domain_scores_json' => [
                        'Physical Development' => ['Gross motor balance' => 'A', 'Fine motor coordination' => 'P', 'Personal hygiene habits' => 'A', 'Nutritional awareness' => 'P'],
                        'Socio-Emotional and Ethical Development' => ['Empathy towards peers' => 'A', 'Emotional self-regulation' => 'P', 'Cooperative play' => 'A', 'Kindness and sharing' => 'A'],
                        'Cognitive Development' => ['Sensory inquiry and observation' => 'P', 'Problem-solving persistence' => 'P', 'Pattern recognition' => 'A', 'Logical reasoning' => 'P'],
                        'Language and Literacy Development' => ['Active listening' => 'A', 'Expressive vocabulary' => 'A', 'Phonemic awareness' => 'P', 'Early mark-making/writing' => 'P'],
                        'Aesthetic and Cultural Development' => ['Rhythmic movement and music' => 'A', 'Visual arts and craft' => 'A', 'Dramatic roleplay' => 'P', 'Appreciation of beauty' => 'A'],
                        'Positive Learning Habits' => ['Curiosity to explore' => 'A', 'Concentration on task' => 'P', 'Care for environment and materials' => 'A'],
                    ],
                    'teacher_summary_json' => [
                        'teacher_name' => 'Ms. Ananya Sen',
                        'holistic_remarks' => "{$stu->first_name} has shown remarkable holistic growth across cognitive and socio-emotional domains. Demonstrates outstanding curiosity, gentle peer empathy, and creative flair in rhythmic arts. A delight to teach!",
                        'areas_of_growth' => 'Continue developing pencil grip and fine motor precision during detailed coloring.',
                        'principal_remarks' => 'Exceptional holistic development aligned with NEP 2020 core competencies. Promoted with distinction to next level.',
                    ],
                ]
            );
        }
    }
}

