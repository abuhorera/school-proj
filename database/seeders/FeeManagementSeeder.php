<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FeeManagementSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 1. Fee Discounts
        DB::table('fee_discounts')->insertOrIgnore([
            [
                'id' => 1,
                'school_id' => 1,
                'name' => 'Sibling Concession',
                'code' => 'DISC-SIB-20',
                'type' => 'percentage',
                'amount' => 20.00,
                'description' => '20% discount on tuition fee for younger sibling',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'name' => 'Merit Scholarship (Top 5%)',
                'code' => 'DISC-MERIT-50',
                'type' => 'percentage',
                'amount' => 50.00,
                'description' => '50% academic scholarship for board exam toppers',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'name' => 'Staff Ward Concession',
                'code' => 'DISC-STAFF-100',
                'type' => 'percentage',
                'amount' => 100.00,
                'description' => '100% tuition concession for full-time faculty children',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'school_id' => 1,
                'name' => 'Early Bird Admission Waiver',
                'code' => 'DISC-EARLY-FLAT',
                'type' => 'fixed',
                'amount' => 2500.00,
                'description' => 'Flat ₹2500 off on admission fee during early enrollment window',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2. Extra Fee Types & Groups
        DB::table('fee_types')->insertOrIgnore([
            ['id' => 10, 'fee_group_id' => 1, 'name' => 'Computer Lab Fee', 'code' => 'FT-COMP-LAB', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'fee_group_id' => 1, 'name' => 'Sports & Athletic Fund', 'code' => 'FT-SPORTS', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'fee_group_id' => 1, 'name' => 'Library & Resource Access', 'code' => 'FT-LIB', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'fee_group_id' => 2, 'name' => 'Bus Route Transport Fee', 'code' => 'FT-TRANSPORT', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'fee_group_id' => 2, 'name' => 'Hostel Boarding & Mess Fee', 'code' => 'FT-HOSTEL', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'fee_group_id' => 1, 'name' => 'CBSE Examination Fee', 'code' => 'FT-EXAM', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 3. Fee Challans
        DB::table('fee_challans')->insertOrIgnore([
            [
                'id' => 1,
                'school_id' => 1,
                'student_id' => 1,
                'challan_no' => 'CH-2026-00101',
                'amount' => 12500.00,
                'due_date' => '2026-11-15',
                'challan_type' => 'Tuition & Lab',
                'bank_name' => 'HDFC Bank - School Branch',
                'account_no' => '50200088991122',
                'status' => 'Paid',
                'paid_date' => '2026-11-02',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'student_id' => 2,
                'challan_no' => 'CH-2026-00102',
                'amount' => 15000.00,
                'due_date' => '2026-11-15',
                'challan_type' => 'Quarter 3 Composite',
                'bank_name' => 'State Bank of India',
                'account_no' => '309988112233',
                'status' => 'Generated',
                'paid_date' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'student_id' => 3,
                'challan_no' => 'CH-2026-00103',
                'amount' => 8500.00,
                'due_date' => '2026-11-10',
                'challan_type' => 'Transport Fee',
                'bank_name' => 'HDFC Bank - School Branch',
                'account_no' => '50200088991122',
                'status' => 'Generated',
                'paid_date' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'school_id' => 1,
                'student_id' => 4,
                'challan_no' => 'CH-2026-00104',
                'amount' => 18000.00,
                'due_date' => '2026-11-20',
                'challan_type' => 'Quarter 3 Composite',
                'bank_name' => 'State Bank of India',
                'account_no' => '309988112233',
                'status' => 'Paid',
                'paid_date' => '2026-11-03',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 4. Late Fine Rules
        DB::table('fee_fine_rules')->insertOrIgnore([
            [
                'id' => 1,
                'school_id' => 1,
                'name' => 'Standard 5-Day Grace Daily Fine',
                'fine_type' => 'Daily',
                'grace_days' => 5,
                'amount' => 20.00,
                'description' => 'Charges ₹20/day after 5 days from the due date until paid.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'name' => 'One-time Slab Default Penalty',
                'fine_type' => 'Fixed',
                'grace_days' => 10,
                'amount' => 250.00,
                'description' => 'Charges flat ₹250 late fee if unpaid past 10 days of month.',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 5. Due Slips History
        DB::table('fee_due_slips')->insertOrIgnore([
            [
                'id' => 1,
                'school_id' => 1,
                'student_id' => 2,
                'slip_code' => 'DS-2026-11-002',
                'month' => 'November 2026',
                'basis' => 'Monthly Assessment',
                'previous_due' => 4500.00,
                'current_due' => 10500.00,
                'total_due' => 15000.00,
                'generated_on' => '2026-11-01',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'student_id' => 3,
                'slip_code' => 'DS-2026-11-003',
                'month' => 'November 2026',
                'basis' => 'Monthly Assessment',
                'previous_due' => 0.00,
                'current_due' => 8500.00,
                'total_due' => 8500.00,
                'generated_on' => '2026-11-01',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'student_id' => 5,
                'slip_code' => 'DS-2026-11-005',
                'month' => 'November 2026',
                'basis' => 'Monthly Assessment',
                'previous_due' => 2000.00,
                'current_due' => 11000.00,
                'total_due' => 13000.00,
                'generated_on' => '2026-11-01',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 6. Online Payments Logs
        DB::table('online_payments')->insertOrIgnore([
            [
                'id' => 1,
                'school_id' => 1,
                'student_id' => 1,
                'order_id' => 'ORD-2026-9921',
                'transaction_ref' => 'UPI-REF-4499112288',
                'amount' => 12500.00,
                'gateway_charge' => 0.00,
                'gateway' => 'Razorpay UPI',
                'payment_mode' => 'GooglePay / PhonePe',
                'status' => 'Success',
                'payment_date' => Carbon::parse('2026-11-02 11:30:00'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'school_id' => 1,
                'student_id' => 4,
                'order_id' => 'ORD-2026-9922',
                'transaction_ref' => 'NETB-HDFC-99112',
                'amount' => 18000.00,
                'gateway_charge' => 15.00,
                'gateway' => 'HDFC PG',
                'payment_mode' => 'Net Banking',
                'status' => 'Success',
                'payment_date' => Carbon::parse('2026-11-03 14:15:00'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'school_id' => 1,
                'student_id' => 2,
                'order_id' => 'ORD-2026-9923',
                'transaction_ref' => 'CARD-FAILED-7721',
                'amount' => 15000.00,
                'gateway_charge' => 0.00,
                'gateway' => 'PayU Money',
                'payment_mode' => 'Credit Card',
                'status' => 'Failed',
                'payment_date' => Carbon::parse('2026-11-04 18:45:00'),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}

