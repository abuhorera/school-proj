<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IdCardTemplateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('id_card_templates')->delete();

        $templates = [
            // Student Templates
            [
                'id' => 1,
                'title' => 'Classic Portrait ID Card (CR80)',
                'card_type' => 'Student',
                'layout' => 'Vertical',
                'width_mm' => 54.00,
                'height_mm' => 85.60,
                'is_default' => 1,
                'bg_color' => '#1e40af',
                'fields_json' => json_encode(['Photo', 'Student Name', 'Admission No', 'Roll No', 'Class & Section', 'Blood Group', 'Father Name', 'Phone', 'Barcode']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'Golden Crest (Canvas) ✨',
                'card_type' => 'Student',
                'layout' => 'Vertical',
                'width_mm' => 54.00,
                'height_mm' => 85.60,
                'is_default' => 0,
                'bg_color' => '#d97706',
                'fields_json' => json_encode(['Photo', 'Student Name', 'Admission No', 'Class & Section', 'DOB', 'Emergency Contact', 'QR Code']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'title' => 'Professional Landscape ID Card',
                'card_type' => 'Student',
                'layout' => 'Horizontal',
                'width_mm' => 85.60,
                'height_mm' => 54.00,
                'is_default' => 0,
                'bg_color' => '#0f172a',
                'fields_json' => json_encode(['Photo', 'Student Name', 'Admission No', 'Class & Section', 'Roll No', 'Valid Upto', 'Barcode']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'title' => 'Pastel Kids Primary Badge ✨',
                'card_type' => 'Student',
                'layout' => 'Vertical',
                'width_mm' => 54.00,
                'height_mm' => 85.60,
                'is_default' => 0,
                'bg_color' => '#0284c7',
                'fields_json' => json_encode(['Photo', 'Student Name', 'Class & Section', 'Parent Phone', 'Blood Group', 'QR Code']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'title' => 'Smart RFID Campus Pass',
                'card_type' => 'Student',
                'layout' => 'Vertical',
                'width_mm' => 54.00,
                'height_mm' => 85.60,
                'is_default' => 0,
                'bg_color' => '#059669',
                'fields_json' => json_encode(['Photo', 'Student Name', 'RFID Chip UID', 'Library Code', 'Emergency Contact', 'Barcode']),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Staff Templates
            [
                'id' => 6,
                'title' => 'Corporate Blue Staff ID (V2)',
                'card_type' => 'Staff',
                'layout' => 'Vertical',
                'width_mm' => 54.00,
                'height_mm' => 85.60,
                'is_default' => 1,
                'bg_color' => '#1e3a8a',
                'fields_json' => json_encode(['Photo', 'Staff Name', 'Employee Code', 'Department', 'Designation', 'Phone', 'Blood Group', 'Barcode']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'title' => 'Staff Navy Pro (Canvas) ✨',
                'card_type' => 'Staff',
                'layout' => 'Horizontal',
                'width_mm' => 85.60,
                'height_mm' => 54.00,
                'is_default' => 0,
                'bg_color' => '#0f172a',
                'fields_json' => json_encode(['Photo', 'Staff Name', 'Staff ID', 'Designation', 'Department', 'Emergency Phone', 'QR Code']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'title' => 'Executive Faculty Badge',
                'card_type' => 'Staff',
                'layout' => 'Vertical',
                'width_mm' => 54.00,
                'height_mm' => 85.60,
                'is_default' => 0,
                'bg_color' => '#881337',
                'fields_json' => json_encode(['Photo', 'Staff Name', 'Designation', 'Joining Date', 'Blood Group', 'Authorized Signature']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('id_card_templates')->insert($templates);
    }
}

