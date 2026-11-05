<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CbcCompetency;
use App\Models\CbcStrand;
use App\Models\CbcOutcome;
use App\Models\CbcAssessment;
use App\Models\CbcStudentEvaluation;
use App\Models\CbcPathway;
use App\Models\CbcTrack;
use App\Models\Subject;
use App\Models\Student;
use App\Models\SchoolClass;
use Carbon\Carbon;

class CbcAcademicsSeeder extends Seeder
{
    public function run()
    {
        $schoolId = 1;

        // 1. Core Competencies Master (7 Essential 21st-Century Competencies)
        $competencies = [
            [
                'name' => 'Communication & Collaboration',
                'category' => 'Interpersonal & Socio-Emotional',
                'description' => 'Ability to clearly articulate thoughts, actively listen to diverse perspectives, and cooperate effectively in multidisciplinary teams.',
                'indicators' => [
                    'Expresses complex ideas coherently using verbal, written, and multimedia formats.',
                    'Actively listens and provides constructive feedback in group discussions.',
                    'Demonstrates empathy, mutual respect, and shared accountability in group projects.',
                ],
            ],
            [
                'name' => 'Critical Thinking & Problem Solving',
                'category' => 'Cognitive & Analytical',
                'description' => 'Synthesizes information from multiple sources, identifies logical fallacies, and formulates evidence-based solutions.',
                'indicators' => [
                    'Questions underlying assumptions and verifies source credibility.',
                    'Breaks down complex real-world problems into logical sub-tasks.',
                    'Applies mathematical and scientific reasoning to solve novel challenges.',
                ],
            ],
            [
                'name' => 'Creativity & Imagination',
                'category' => 'Innovation & Design Thinking',
                'description' => 'Generates original concepts, experiments with novel techniques, and views situations from unconventional viewpoints.',
                'indicators' => [
                    'Generates divergent ideas through brainstorming and mind mapping.',
                    'Connects concepts across disparate disciplines to innovate.',
                    'Refines and iterates upon initial prototypes based on critique.',
                ],
            ],
            [
                'name' => 'Citizenship & Social Responsibility',
                'category' => 'Ethics & Global Awareness',
                'description' => 'Understands constitutional values, environmental sustainability, human rights, and active civic participation.',
                'indicators' => [
                    'Exhibits awareness of local and global socio-environmental issues.',
                    'Engages proactively in community service and eco-club activities.',
                    'Demonstrates respect for democratic principles, inclusivity, and diversity.',
                ],
            ],
            [
                'name' => 'Digital Literacy & Computational Thinking',
                'category' => 'Technological & Informatics',
                'description' => 'Navigates digital environments safely, analyzes data trends, and understands algorithmic logic and cybersecurity.',
                'indicators' => [
                    'Leverages productivity and research tools to analyze data.',
                    'Understands digital footprints, privacy laws, and cyber ethics.',
                    'Deconstructs algorithms and builds foundational logic flows.',
                ],
            ],
            [
                'name' => 'Learning to Learn (Metacognition)',
                'category' => 'Self-Regulation & Growth',
                'description' => 'Reflects upon personal learning styles, manages time effectively, and sets ambitious independent learning goals.',
                'indicators' => [
                    'Identifies personal cognitive strengths and areas requiring practice.',
                    'Demonstrates self-directed inquiry without constant teacher prompting.',
                    'Embraces mistakes as pivotal opportunities for intellectual growth.',
                ],
            ],
            [
                'name' => 'Self-Efficacy & Emotional Well-being',
                'category' => 'Personal & Resilience',
                'description' => 'Maintains emotional balance, exercises self-discipline, and exhibits confidence in facing adversity.',
                'indicators' => [
                    'Regulates stress and emotional impulses during challenging tasks.',
                    'Maintains optimism and perseveres in the face of setbacks.',
                    'Articulates self-worth and seeks assistance when experiencing distress.',
                ],
            ],
        ];

        foreach ($competencies as $c) {
            CbcCompetency::firstOrCreate(
                ['school_id' => $schoolId, 'name' => $c['name']],
                $c
            );
        }

        // 2. Strands & Outcomes for Subjects
        $math = Subject::where('school_id', $schoolId)->where('name', 'like', '%Math%')->first()
            ?? Subject::where('school_id', $schoolId)->first();

        $science = Subject::where('school_id', $schoolId)->where('name', 'like', '%Science%')->first()
            ?? Subject::where('school_id', $schoolId)->skip(1)->first();

        if ($math) {
            $strand1 = CbcStrand::firstOrCreate(
                ['school_id' => $schoolId, 'subject_id' => $math->id, 'name' => 'Numbers, Fractions & Algebra'],
                ['code' => 'MTH-STR-01', 'description' => 'Exploration of algebraic patterns, fractions, and numerical operations.', 'sort_order' => 1]
            );

            CbcOutcome::firstOrCreate(
                ['cbc_strand_id' => $strand1->id, 'name' => 'Applies fractional operations in practical real-world contexts'],
                ['school_id' => $schoolId, 'code' => 'LO-MTH-101', 'description' => 'Solves word problems involving fraction addition and multiplication.']
            );
            CbcOutcome::firstOrCreate(
                ['cbc_strand_id' => $strand1->id, 'name' => 'Identifies algebraic patterns and formulates linear equations'],
                ['school_id' => $schoolId, 'code' => 'LO-MTH-102', 'description' => 'Translates word statements into mathematical expressions.']
            );

            $strand2 = CbcStrand::firstOrCreate(
                ['school_id' => $schoolId, 'subject_id' => $math->id, 'name' => 'Geometry, Mensuration & Spatial Reasoning'],
                ['code' => 'MTH-STR-02', 'description' => 'Understanding spatial coordinates, 2D/3D shapes, and geometric theorems.', 'sort_order' => 2]
            );

            CbcOutcome::firstOrCreate(
                ['cbc_strand_id' => $strand2->id, 'name' => 'Constructs geometric shapes using compass and straightedge'],
                ['school_id' => $schoolId, 'code' => 'LO-MTH-201', 'description' => 'Demonstrates precise angle bisectors and triangle congruences.']
            );
        }

        if ($science) {
            $strand3 = CbcStrand::firstOrCreate(
                ['school_id' => $schoolId, 'subject_id' => $science->id, 'name' => 'Living Things & Environmental Ecosystems'],
                ['code' => 'SCI-STR-01', 'description' => 'Ecological balance, bio-diversity conservation, and plant biology.', 'sort_order' => 1]
            );

            CbcOutcome::firstOrCreate(
                ['cbc_strand_id' => $strand3->id, 'name' => 'Conducts ecological surveys and classifies plant specimens'],
                ['school_id' => $schoolId, 'code' => 'LO-SCI-101', 'description' => 'Demonstrates hands-on field sampling and dichotomous keys.']
            );
            CbcOutcome::firstOrCreate(
                ['cbc_strand_id' => $strand3->id, 'name' => 'Analyzes human impact on local watershed conservation'],
                ['school_id' => $schoolId, 'code' => 'LO-SCI-102', 'description' => 'Proposes sustainable mitigation plans for waste management.']
            );
        }

        // 3. CBC Assessments
        $assessment1 = CbcAssessment::firstOrCreate(
            ['school_id' => $schoolId, 'title' => 'Term 1 Environmental Science Fieldwork & Biodiversity'],
            [
                'class_id' => 1,
                'section_id' => 1,
                'subject_id' => $science ? $science->id : 1,
                'assessment_date' => Carbon::now()->subDays(5)->toDateString(),
                'type' => 'Formative',
                'description' => 'Authentic field task evaluating student sampling, specimen documentation, and ecological reasoning.'
            ]
        );

        $assessment2 = CbcAssessment::firstOrCreate(
            ['school_id' => $schoolId, 'title' => 'Mathematics Pattern Formulation & Problem Solving Task'],
            [
                'class_id' => 1,
                'section_id' => 1,
                'subject_id' => $math ? $math->id : 1,
                'assessment_date' => Carbon::now()->subDays(2)->toDateString(),
                'type' => 'Summative',
                'description' => 'Holistic task requiring candidates to build algebraic equations and justify their solution pathway.'
            ]
        );

        // 4. Student Evaluations
        $students = Student::where('school_id', $schoolId)->take(10)->get();
        $outcomes = CbcOutcome::all();

        foreach ($students as $st) {
            foreach ($outcomes as $oc) {
                $levels = ['EE', 'ME', 'ME', 'AE']; // Most meeting expectations
                $lvl = $levels[array_rand($levels)];

                CbcStudentEvaluation::firstOrCreate(
                    [
                        'school_id' => $schoolId,
                        'cbc_assessment_id' => $assessment1->id,
                        'student_id' => $st->id,
                        'cbc_outcome_id' => $oc->id,
                    ],
                    [
                        'rubric_level' => $lvl,
                        'teacher_feedback' => 'Demonstrates thorough observation and documented observations accurately in the field notebook.',
                    ]
                );
            }
        }

        // 5. Senior Secondary Pathways & Tracks
        $stem = CbcPathway::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'STEM (Science, Technology, Engineering & Mathematics)'],
            [
                'description' => 'Focuses on empirical investigation, mathematical rigor, computing, robotics, and medical science.',
                'icon' => 'fa-solid fa-atom',
            ]
        );

        CbcTrack::firstOrCreate(
            ['school_id' => $schoolId, 'cbc_pathway_id' => $stem->id, 'name' => 'Pure Sciences & Biomedical Track'],
            [
                'description' => 'Designed for aspiring physicians, biochemists, geneticists, and research scientists.',
                'compulsory_subjects' => ['Physics', 'Chemistry', 'Biology', 'Advanced Mathematics'],
                'elective_subjects' => ['Biotechnology', 'Psychology', 'Statistics'],
            ]
        );

        CbcTrack::firstOrCreate(
            ['school_id' => $schoolId, 'cbc_pathway_id' => $stem->id, 'name' => 'Computer Science & AI Engineering Track'],
            [
                'description' => 'Designed for software engineers, robotics developers, and data scientists.',
                'compulsory_subjects' => ['Physics', 'Mathematics', 'Computer Science & Python', 'Engineering Drawing'],
                'elective_subjects' => ['Artificial Intelligence', 'Applied Robotics', 'Cybersecurity'],
            ]
        );

        $artsSports = CbcPathway::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Arts, Design & Sports Science Pathway'],
            [
                'description' => 'Fosters creative expression, visual communication, performing arts, and physical education excellence.',
                'icon' => 'fa-solid fa-palette',
            ]
        );

        CbcTrack::firstOrCreate(
            ['school_id' => $schoolId, 'cbc_pathway_id' => $artsSports->id, 'name' => 'Visual Arts, UI/UX & Design Track'],
            [
                'description' => 'Geared towards architecture, graphic design, animation, and fashion technology.',
                'compulsory_subjects' => ['Fine Arts', 'Graphic Design', 'Applied Aesthetics', 'General English'],
                'elective_subjects' => ['Digital Photography', 'Art History', 'Sculpting'],
            ]
        );

        $humanities = CbcPathway::firstOrCreate(
            ['school_id' => $schoolId, 'name' => 'Social Sciences & Humanities Pathway'],
            [
                'description' => 'Explores governance, jurisprudence, history, international relations, and economic philosophy.',
                'icon' => 'fa-solid fa-landmark',
            ]
        );

        CbcTrack::firstOrCreate(
            ['school_id' => $schoolId, 'cbc_pathway_id' => $humanities->id, 'name' => 'Public Policy, Law & International Relations Track'],
            [
                'description' => 'Designed for legal advocates, diplomats, civil servants, and sociologists.',
                'compulsory_subjects' => ['Political Science', 'History', 'Economics', 'Legal Studies'],
                'elective_subjects' => ['Sociology', 'World Geography', 'Public Administration'],
            ]
        );
    }
}

