<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\LeadStage;
use App\Models\LeadSource;
use App\Models\Lead;
use App\Models\LeadFollowup;
use App\Models\SchoolClass;
use App\Models\Staff;
use Carbon\Carbon;

class LeadManagementSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $schoolId = $school ? $school->id : 1;
        $classes = SchoolClass::all();
        $staff = Staff::all();

        // 1. Seed Lead Stages
        LeadStage::truncate();
        $stages = [
            ['name' => 'New Enquiry', 'color' => '#64748b', 'sort_order' => 1],
            ['name' => 'Contacted / Qualified', 'color' => '#0ea5e9', 'sort_order' => 2],
            ['name' => 'Application Received', 'color' => '#6366f1', 'sort_order' => 3],
            ['name' => 'Document Verification', 'color' => '#8b5cf6', 'sort_order' => 4],
            ['name' => 'Entrance Test', 'color' => '#f59e0b', 'sort_order' => 5],
            ['name' => 'Counselling / Interview', 'color' => '#ec4899', 'sort_order' => 6],
            ['name' => 'Offer / Selected', 'color' => '#14b8a6', 'sort_order' => 7],
            ['name' => 'Enrolled', 'color' => '#22c55e', 'sort_order' => 8],
            ['name' => 'Lost / Rejected', 'color' => '#ef4444', 'sort_order' => 9],
        ];

        $stageModels = [];
        foreach ($stages as $st) {
            $stageModels[] = LeadStage::create([
                'school_id' => $schoolId,
                'name' => $st['name'],
                'color' => $st['color'],
                'sort_order' => $st['sort_order'],
                'is_active' => true,
            ]);
        }

        // 2. Seed Lead Sources
        LeadSource::truncate();
        $sources = [
            'Walk In',
            'Website Form',
            'Phone Call',
            'Social Media (Meta/Instagram)',
            'Parent Referral',
            'Newspaper & Print Ad',
            'Education Fair / Expo',
        ];

        $sourceModels = [];
        foreach ($sources as $src) {
            $sourceModels[] = LeadSource::create([
                'school_id' => $schoolId,
                'name' => $src,
                'is_active' => true,
            ]);
        }

        // 3. Seed Leads across pipeline stages
        Lead::truncate();
        $leadsData = [
            ['student' => 'Shaurya Pratap', 'parent' => 'Devendra Pratap', 'phone' => '9811100001', 'class' => 'Nursery', 'stage_idx' => 0, 'src_idx' => 0, 'follow_days' => 1],
            ['student' => 'Ananya Iyer', 'parent' => 'Subramanian Iyer', 'phone' => '9811100002', 'class' => 'KG', 'stage_idx' => 0, 'src_idx' => 1, 'follow_days' => 2],
            ['student' => 'Advait Kulkarni', 'parent' => 'Nitin Kulkarni', 'phone' => '9811100003', 'class' => 'Class 1', 'stage_idx' => 1, 'src_idx' => 2, 'follow_days' => 0],
            ['student' => 'Rhea Singhania', 'parent' => 'Vijay Singhania', 'phone' => '9811100004', 'class' => 'Class 2', 'stage_idx' => 1, 'src_idx' => 3, 'follow_days' => -1],
            ['student' => 'Aarush Saxena', 'parent' => 'Anurag Saxena', 'phone' => '9811100005', 'class' => 'Class 3', 'stage_idx' => 2, 'src_idx' => 0, 'follow_days' => 3],
            ['student' => 'Meera Nambiar', 'parent' => 'Madhavan Nambiar', 'phone' => '9811100006', 'class' => 'Class 4', 'stage_idx' => 2, 'src_idx' => 4, 'follow_days' => 1],
            ['student' => 'Devansh Jain', 'parent' => 'Pradeep Jain', 'phone' => '9811100007', 'class' => 'Class 5', 'stage_idx' => 3, 'src_idx' => 1, 'follow_days' => 2],
            ['student' => 'Kavya Deshmukh', 'parent' => 'Ajay Deshmukh', 'phone' => '9811100008', 'class' => 'Class 6', 'stage_idx' => 3, 'src_idx' => 5, 'follow_days' => 4],
            ['student' => 'Aryan Chopra', 'parent' => 'Sunil Chopra', 'phone' => '9811100009', 'class' => 'Class 7', 'stage_idx' => 4, 'src_idx' => 0, 'follow_days' => 0],
            ['student' => 'Tanvi Rastogi', 'parent' => 'Sanjay Rastogi', 'phone' => '9811100010', 'class' => 'Class 8', 'stage_idx' => 4, 'src_idx' => 2, 'follow_days' => 1],
            ['student' => 'Yashvardhan Rao', 'parent' => 'Prakash Rao', 'phone' => '9811100011', 'class' => 'Class 9', 'stage_idx' => 5, 'src_idx' => 3, 'follow_days' => 2],
            ['student' => 'Sneha Bhattacharya', 'parent' => 'Soumya Bhattacharya', 'phone' => '9811100012', 'class' => 'Class 10', 'stage_idx' => 5, 'src_idx' => 4, 'follow_days' => -2],
            ['student' => 'Harshvardhan Mittal', 'parent' => 'Girish Mittal', 'phone' => '9811100013', 'class' => 'Class 11 Science', 'stage_idx' => 6, 'src_idx' => 1, 'follow_days' => 1],
            ['student' => 'Diya Sen', 'parent' => 'Debabrata Sen', 'phone' => '9811100014', 'class' => 'Nursery', 'stage_idx' => 6, 'src_idx' => 0, 'follow_days' => 3],
            ['student' => 'Kabir Oberoi', 'parent' => 'Raman Oberoi', 'phone' => '9811100015', 'class' => 'Class 1', 'stage_idx' => 7, 'src_idx' => 4, 'follow_days' => 0],
            ['student' => 'Avani Patel', 'parent' => 'Ketan Patel', 'phone' => '9811100016', 'class' => 'KG', 'stage_idx' => 7, 'src_idx' => 1, 'follow_days' => 0],
            ['student' => 'Rohan Bhatia', 'parent' => 'Alok Bhatia', 'phone' => '9811100017', 'class' => 'Class 6', 'stage_idx' => 8, 'src_idx' => 5, 'follow_days' => -5],
            ['student' => 'Pooja Hegde', 'parent' => 'Raghav Hegde', 'phone' => '9811100018', 'class' => 'Class 8', 'stage_idx' => 8, 'src_idx' => 2, 'follow_days' => -7],
        ];

        LeadFollowup::truncate();

        foreach ($leadsData as $idx => $ld) {
            $cls = $classes->where('name', $ld['class'])->first() ?? $classes->first();
            $stg = $stageModels[$ld['stage_idx']];
            $src = $sourceModels[$ld['src_idx']];
            $counselor = $staff->isNotEmpty() ? $staff[$idx % $staff->count()] : null;

            $status = 'active';
            if ($stg->name == 'Enrolled') $status = 'converted';
            if ($stg->name == 'Lost / Rejected') $status = 'lost';

            $lead = Lead::create([
                'school_id' => $schoolId,
                'student_name' => $ld['student'],
                'parent_name' => $ld['parent'],
                'phone' => $ld['phone'],
                'email' => strtolower(str_replace(' ', '.', $ld['student'])) . '@example.com',
                'school_class_id' => $cls ? $cls->id : null,
                'class_name' => $cls ? $cls->name : $ld['class'],
                'lead_source_id' => $src->id,
                'source_name' => $src->name,
                'lead_stage_id' => $stg->id,
                'assigned_to' => $counselor ? $counselor->id : null,
                'follow_up_date' => Carbon::now()->addDays($ld['follow_days'])->format('Y-m-d'),
                'notes' => 'Parent interested in CBSE English medium curriculum and school bus transportation.',
                'status' => $status,
            ]);

            // Add sample follow-up response for some leads
            if ($ld['stage_idx'] > 0) {
                LeadFollowup::create([
                    'lead_id' => $lead->id,
                    'user_id' => $counselor ? $counselor->id : 1,
                    'follow_up_date' => Carbon::now()->subDays(rand(1, 3))->format('Y-m-d'),
                    'type' => $ld['stage_idx'] >= 5 ? 'Campus Tour' : 'Phone Call',
                    'response_notes' => 'Spoke with parent ' . $ld['parent'] . '. Clarified transport fees and scheduled counseling session.',
                    'next_follow_up_date' => $lead->follow_up_date,
                ]);
            }
        }
    }
}

