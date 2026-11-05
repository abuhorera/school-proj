<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Building;
use App\Models\ClassroomRoom;
use App\Models\RoomBooking;
use App\Models\RoomMaintenance;
use App\Models\RoomLabelMatch;
use App\Models\RoomBookingSetting;
use App\Models\Section;

class ClassroomsSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first() ?? School::create([
            'name' => 'YUG INTERNATIONAL SCHOOL',
            'email' => 'admin@yugschool.com',
            'phone' => '9876543210',
            'address' => 'Bhatagaon, Raipur, Chhattisgarh',
        ]);

        // 1. Buildings
        $b1 = Building::firstOrCreate(['code' => 'A'], [
            'school_id' => $school->id,
            'name' => 'Main Academic Block',
            'floors' => 3,
            'notes' => 'Primary & Junior wing classrooms with smart boards',
            'is_active' => true,
        ]);

        $b2 = Building::firstOrCreate(['code' => 'SCI'], [
            'school_id' => $school->id,
            'name' => 'Science & Technology Wing',
            'floors' => 2,
            'notes' => 'Advanced laboratories, robotics lab, and computer center',
            'is_active' => true,
        ]);

        $b3 = Building::firstOrCreate(['code' => 'ADM'], [
            'school_id' => $school->id,
            'name' => 'Administration Block',
            'floors' => 1,
            'notes' => 'Front office, council chambers, auditorium and principal cabin',
            'is_active' => true,
        ]);

        $b4 = Building::firstOrCreate(['code' => 'B'], [
            'school_id' => $school->id,
            'name' => 'Senior Secondary Wing',
            'floors' => 2,
            'notes' => 'Classrooms for Grades 9 through 12',
            'is_active' => true,
        ]);

        // 2. Sections
        $sec1 = Section::first();
        $sec2 = Section::skip(1)->first();

        // 3. Rooms
        $rooms = [
            [
                'room_number' => 'LAB-PHY',
                'name' => 'Physics Lab',
                'room_type' => 'Science lab',
                'building_id' => $b2->id,
                'floor' => 1,
                'capacity' => 34,
                'exam_capacity' => 20,
                'facilities_json' => ['Fans', 'Lab benches', 'Drinking water', 'Safety goggles', 'Fume hood'],
                'status' => 'Available',
                'home_section_id' => null,
            ],
            [
                'room_number' => 'LAB-CHEM',
                'name' => 'Chemistry Lab',
                'room_type' => 'Science lab',
                'building_id' => $b2->id,
                'floor' => 1,
                'capacity' => 32,
                'exam_capacity' => 18,
                'facilities_json' => ['Fans', 'Lab benches', 'Fume hood', 'Fire extinguisher', 'Acid cabinet'],
                'status' => 'Available',
                'home_section_id' => null,
            ],
            [
                'room_number' => 'LAB-BIO',
                'name' => 'Biology Lab',
                'room_type' => 'Science lab',
                'building_id' => $b2->id,
                'floor' => 2,
                'capacity' => 30,
                'exam_capacity' => 18,
                'facilities_json' => ['Microscopes', 'Specimen displays', 'Fans', 'Lab benches'],
                'status' => 'Available',
                'home_section_id' => null,
            ],
            [
                'room_number' => 'LAB-COMP',
                'name' => 'Computer Lab',
                'room_type' => 'Computer lab',
                'building_id' => $b2->id,
                'floor' => 2,
                'capacity' => 40,
                'exam_capacity' => 30,
                'facilities_json' => ['30 PCs', 'Air Conditioning', 'Projector', 'Gigabit LAN', 'UPS Backup'],
                'status' => 'Available',
                'home_section_id' => null,
            ],
            [
                'room_number' => 'R-101',
                'name' => 'Classroom 101',
                'room_type' => 'Classroom',
                'building_id' => $b1->id,
                'floor' => 1,
                'capacity' => 45,
                'exam_capacity' => 30,
                'facilities_json' => ['Smart board', 'Fans', 'Whiteboard', 'Speakers'],
                'status' => 'Available',
                'home_section_id' => $sec1 ? $sec1->id : null,
            ],
            [
                'room_number' => 'R-102',
                'name' => 'Classroom 102',
                'room_type' => 'Classroom',
                'building_id' => $b1->id,
                'floor' => 1,
                'capacity' => 45,
                'exam_capacity' => 30,
                'facilities_json' => ['Projector', 'Fans', 'Whiteboard'],
                'status' => 'Available',
                'home_section_id' => $sec2 ? $sec2->id : null,
            ],
            [
                'room_number' => 'R-201',
                'name' => 'Classroom 201',
                'room_type' => 'Classroom',
                'building_id' => $b1->id,
                'floor' => 2,
                'capacity' => 42,
                'exam_capacity' => 28,
                'facilities_json' => ['Smart board', 'Fans', 'Display Pinboards'],
                'status' => 'Available',
                'home_section_id' => null,
            ],
            [
                'room_number' => 'AUD-01',
                'name' => 'Main Auditorium',
                'room_type' => 'Hall',
                'building_id' => $b3->id,
                'floor' => 1,
                'capacity' => 250,
                'exam_capacity' => 120,
                'facilities_json' => ['Stage lighting', 'Surround Sound', 'Central AC', '4K Projector'],
                'status' => 'Available',
                'home_section_id' => null,
            ],
        ];

        $createdRooms = [];
        foreach ($rooms as $r) {
            $createdRooms[] = ClassroomRoom::updateOrCreate(
                ['room_number' => $r['room_number']],
                array_merge($r, ['school_id' => $school->id])
            );
        }

        // 4. Room Label Matches
        RoomLabelMatch::updateOrCreate(['label' => '1'], [
            'room_id' => $createdRooms[0]->id,
            'timetable_count' => 0,
            'exam_count' => 8,
        ]);
        RoomLabelMatch::updateOrCreate(['label' => 'Room 101'], [
            'room_id' => $createdRooms[4]->id,
            'timetable_count' => 15,
            'exam_count' => 4,
        ]);
        RoomLabelMatch::updateOrCreate(['label' => 'Chemistry Lab'], [
            'room_id' => $createdRooms[1]->id,
            'timetable_count' => 6,
            'exam_count' => 2,
        ]);
        RoomLabelMatch::updateOrCreate(['label' => 'CompLab'], [
            'room_id' => $createdRooms[3]->id,
            'timetable_count' => 10,
            'exam_count' => 6,
        ]);
        RoomLabelMatch::updateOrCreate(['label' => 'Auditorium Hall'], [
            'room_id' => $createdRooms[7]->id,
            'timetable_count' => 2,
            'exam_count' => 12,
        ]);

        // 5. Room Bookings
        RoomBooking::firstOrCreate(['title' => 'Annual Science Quiz & Debate Competition'], [
            'room_id' => $createdRooms[7]->id,
            'booked_by' => 'Ms. Ananya Sen (Science HOD)',
            'date' => now()->addDays(2)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '12:30:00',
            'status' => 'Approved',
        ]);

        RoomBooking::firstOrCreate(['title' => 'Cyber Safety & AI Ethics Workshop'], [
            'room_id' => $createdRooms[3]->id,
            'booked_by' => 'Mr. Vikram Joshi (IT Coordinator)',
            'date' => now()->addDays(3)->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:30:00',
            'status' => 'Pending',
        ]);

        // 6. Maintenance Tickets
        RoomMaintenance::firstOrCreate(['problem' => 'Projector HDMI display flickering & ceiling fan regulator replacement'], [
            'room_id' => $createdRooms[5]->id,
            'priority' => 'High',
            'given_to' => 'Ramesh (Campus Electrician)',
            'status' => 'Being fixed',
            'is_unusable' => false,
            'reported_by' => 'School Admin',
            'reported_at' => now()->subDays(2)->toDateString(),
            'notes' => 'New HDMI splitter ordered; fan regulator replaced.',
        ]);

        RoomMaintenance::firstOrCreate(['problem' => 'Drainage blockage in laboratory wash sink 4'], [
            'room_id' => $createdRooms[1]->id,
            'priority' => 'Normal',
            'given_to' => 'City Plumbing Services',
            'status' => 'Reported',
            'is_unusable' => false,
            'reported_by' => 'Chemistry Lab Assistant',
            'reported_at' => now()->toDateString(),
            'notes' => 'Scheduled for inspection this evening.',
        ]);

        // 7. Booking Settings
        RoomBookingSetting::firstOrCreate(['id' => 1], [
            'teachers_can_request' => true,
            'requests_need_approval' => true,
        ]);
    }
}

