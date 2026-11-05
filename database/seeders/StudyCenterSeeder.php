<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudyCenterSeeder extends Seeder
{
    public function run(): void
    {
        // Clean existing records
        DB::table('study_homework_submissions')->delete();
        DB::table('study_homeworks')->delete();
        DB::table('study_classworks')->delete();
        DB::table('study_materials')->delete();
        DB::table('study_syllabuses')->delete();

        // 1. Seed Syllabus Structure (Units & Chapters)
        $units = [
            [
                'id' => 1,
                'school_id' => 1,
                'school_class_id' => 4, // Class 1
                'subject_id' => 1, // Mathematics
                'parent_id' => null,
                'title' => 'Unit 1: Numbers & Place Values (1-100)',
                'description' => 'Understanding counting, ascending/descending order, single and double digits, and place value concepts.',
                'order_no' => 1,
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'parent_id' => 1,
                'title' => '1.1 Counting and Grouping Objects',
                'description' => 'Practical object counting in groups of 5 and 10.',
                'order_no' => 1,
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(18),
                'updated_at' => Carbon::now()->subDays(16),
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'parent_id' => 1,
                'title' => '1.2 Place Values: Tens and Ones',
                'description' => 'Decomposing 2-digit numbers into tens and units.',
                'order_no' => 2,
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(14),
            ],
            [
                'id' => 4,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'parent_id' => null,
                'title' => 'Unit 2: Basic Addition & Subtraction',
                'description' => 'Single-digit addition on number line, word problems, carry-over basics.',
                'order_no' => 2,
                'status' => 'in_progress',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'id' => 5,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'parent_id' => 4,
                'title' => '2.1 Addition on the Number Line',
                'description' => 'Hopping forward on a 1-20 horizontal number ruler.',
                'order_no' => 1,
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'id' => 6,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'parent_id' => 4,
                'title' => '2.2 Simple Real-World Word Problems',
                'description' => 'Stories involving combining apples, candies, and pencils.',
                'order_no' => 2,
                'status' => 'in_progress',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'id' => 7,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'parent_id' => null,
                'title' => 'Unit 3: 2D Geometrical Shapes & Patterns',
                'description' => 'Triangles, squares, circles, rectangles and symmetry.',
                'order_no' => 3,
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id' => 8,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 2, // Science
                'parent_id' => null,
                'title' => 'Unit 1: Living vs Non-Living Organisms',
                'description' => 'Characteristics of plants, animals, water, soil and minerals.',
                'order_no' => 1,
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(25),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'id' => 9,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 2,
                'parent_id' => null,
                'title' => 'Unit 2: Parts of a Plant and Photosynthesis',
                'description' => 'Roots, stem, leaves, flower, seed cycle, sunlight and water absorption.',
                'order_no' => 2,
                'status' => 'in_progress',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id' => 10,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 6, // Computer Applications
                'parent_id' => null,
                'title' => 'Unit 1: Introduction to Computer Hardware',
                'description' => 'Monitor, CPU, keyboard, mouse, and basic peripheral handling.',
                'order_no' => 1,
                'status' => 'completed',
                'created_at' => Carbon::now()->subDays(14),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        ];

        DB::table('study_syllabuses')->insert($units);

        // 2. Seed Study Materials
        $materials = [
            [
                'id' => 1,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'syllabus_id' => 1,
                'title' => 'Maths Unit 1 - Number Cards & Flashcards PDF',
                'type' => 'file',
                'file_path' => 'study_materials/maths_u1_flashcards.pdf',
                'file_name' => 'maths_u1_flashcards.pdf',
                'file_size' => '2.4 MB',
                'content_link' => null,
                'staff_id' => 1,
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(15),
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 1,
                'syllabus_id' => 5,
                'title' => 'Visual Number Line Jump Animation Video',
                'type' => 'video',
                'file_path' => null,
                'file_name' => null,
                'file_size' => null,
                'content_link' => 'https://www.youtube.com/watch?v=Fe8u2I3vmHU',
                'staff_id' => 1,
                'created_at' => Carbon::now()->subDays(8),
                'updated_at' => Carbon::now()->subDays(8),
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 2,
                'syllabus_id' => 9,
                'title' => 'Parts of a Plant Illustrated Worksheet & Diagram',
                'type' => 'file',
                'file_path' => 'study_materials/science_plant_parts.pdf',
                'file_name' => 'science_plant_parts.pdf',
                'file_size' => '3.8 MB',
                'content_link' => null,
                'staff_id' => 2,
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'id' => 4,
                'school_id' => 1,
                'school_class_id' => 4,
                'subject_id' => 6,
                'syllabus_id' => 10,
                'title' => 'Interactive Computer Peripherals Simulator',
                'type' => 'link',
                'file_path' => null,
                'file_name' => null,
                'file_size' => null,
                'content_link' => 'https://interactives.ck12.org/simulations/computers',
                'staff_id' => 4,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ]
        ];

        DB::table('study_materials')->insert($materials);

        // 3. Seed Classwork & Teacher Logbook Entries
        $classworks = [
            [
                'id' => 1,
                'school_id' => 1,
                'school_class_id' => 4,
                'section_id' => 1,
                'subject_id' => 1,
                'staff_id' => 1,
                'date' => Carbon::now()->format('Y-m-d'),
                'type' => 'Classwork',
                'topic' => 'Two-Digit Addition with Regrouping',
                'content' => 'Completed exercise 3.2 in textbook (problems 1 to 15). Students practiced on individual whiteboards. Quick quiz conducted at the end.',
                'file_path' => null,
                'created_at' => Carbon::now()->subHours(4),
                'updated_at' => Carbon::now()->subHours(4),
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'school_class_id' => 4,
                'section_id' => 1,
                'subject_id' => 2,
                'staff_id' => 2,
                'date' => Carbon::now()->subDay()->format('Y-m-d'),
                'type' => 'Activity',
                'topic' => 'Botanical Garden Leaf Specimen Collection',
                'content' => 'Took students to school botanical lawn. Collected 5 fallen leaf specimens of different leaf venation (parallel & reticulate). Glued in scrapbooks.',
                'file_path' => null,
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'school_class_id' => 4,
                'section_id' => 1,
                'subject_id' => 6,
                'staff_id' => 4,
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'type' => 'Practical',
                'topic' => 'Tux Paint Freehand Drawing & Toolset Exploration',
                'content' => 'Students practiced mouse coordination by drawing houses and trees using paint bucket and stamp tools in IT Lab 1.',
                'file_path' => null,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id' => 4,
                'school_id' => 1,
                'school_class_id' => 4,
                'section_id' => 1,
                'subject_id' => 3, // English
                'staff_id' => 3,
                'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'type' => 'Revision',
                'topic' => 'Nouns & Pronouns Story Re-telling Session',
                'content' => 'Read chapter 4 "The Clever Crow". Identified proper nouns, common nouns, and replaced repetitive names with pronouns he/she/it/they.',
                'file_path' => null,
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ]
        ];

        DB::table('study_classworks')->insert($classworks);

        // 4. Seed Homework Assignments
        $homeworks = [
            [
                'id' => 1,
                'school_id' => 1,
                'school_class_id' => 4,
                'section_id' => 1,
                'subject_id' => 1,
                'staff_id' => 1,
                'title' => 'Double Digit Addition Practice Sheet 4B',
                'description' => 'Solve problems 1 to 20 on page 42 of workbook. Show carry-over digits clearly in the top boxes.',
                'due_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'study_material_id' => 1,
                'file_path' => null,
                'status' => 'Active',
                'created_at' => Carbon::now()->subDay(),
                'updated_at' => Carbon::now()->subDay(),
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'school_class_id' => 4,
                'section_id' => 1,
                'subject_id' => 2,
                'staff_id' => 2,
                'title' => 'Draw and Label 5 Parts of a Flowering Plant',
                'description' => 'Draw a neat diagram of a flower plant in your science register. Label Roots, Stem, Leaf, Flower, and Bud. Colour neatly.',
                'due_date' => Carbon::now()->addDays(4)->format('Y-m-d'),
                'study_material_id' => 3,
                'file_path' => null,
                'status' => 'Active',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'school_class_id' => 4,
                'section_id' => 1,
                'subject_id' => 6,
                'staff_id' => 4,
                'title' => 'Input vs Output Devices Chart',
                'description' => 'Paste pictures of 3 input devices and 3 output devices on an A4 pastel sheet with short 1-line uses.',
                'due_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'study_material_id' => 4,
                'file_path' => null,
                'status' => 'Closed',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(1),
            ]
        ];

        DB::table('study_homeworks')->insert($homeworks);

        // 5. Seed Submissions & Evaluation
        $students = DB::table('students')->take(10)->get();
        if ($students->count() > 0) {
            $submissions = [];
            foreach ($students as $index => $std) {
                // For Homework 1 (Active)
                if ($index < 7) {
                    $submissions[] = [
                        'homework_id' => 1,
                        'student_id' => $std->id,
                        'submitted_at' => Carbon::now()->subHours(rand(1, 18)),
                        'file_path' => 'submissions/hw1_std_' . $std->id . '.pdf',
                        'file_name' => 'addition_hw_' . $std->first_name . '.pdf',
                        'marks' => $index < 4 ? rand(17, 20) : null,
                        'remarks' => $index < 4 ? 'Good neat presentation. Checked all steps.' : null,
                        'status' => $index < 4 ? 'Evaluated' : 'Submitted',
                        'created_at' => Carbon::now()->subHours(10),
                        'updated_at' => Carbon::now()->subHours(2),
                    ];
                }

                // For Homework 3 (Closed)
                $submissions[] = [
                    'homework_id' => 3,
                    'student_id' => $std->id,
                    'submitted_at' => Carbon::now()->subDays(rand(2, 5)),
                    'file_path' => 'submissions/hw3_std_' . $std->id . '.jpg',
                    'file_name' => 'peripherals_chart_' . $std->first_name . '.jpg',
                    'marks' => rand(15, 20),
                    'remarks' => 'Well labeled chart and neat pictures.',
                    'status' => 'Evaluated',
                    'created_at' => Carbon::now()->subDays(4),
                    'updated_at' => Carbon::now()->subDays(1),
                ];
            }

            DB::table('study_homework_submissions')->insert($submissions);
        }
    }
}

