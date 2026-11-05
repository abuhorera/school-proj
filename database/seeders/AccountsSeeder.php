<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\IncomeHead;
use App\Models\ExpenseHead;
use App\Models\Income;
use App\Models\Expense;
use App\Models\BankAccount;
use Carbon\Carbon;

class AccountsSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        if (!$school) return;

        // 1. Bank Accounts
        $bankHdfc = BankAccount::updateOrCreate(
            ['account_number' => '50200084930129'],
            [
                'school_id' => $school->id,
                'bank_name' => 'HDFC Bank',
                'account_name' => 'Primary Operations Account',
                'account_type' => 'Current',
                'ifsc_code' => 'HDFC0000021',
                'branch' => 'Civil Lines / Main Branch',
                'opening_balance' => 1000000.00,
                'current_balance' => 1245800.00
            ]
        );

        $bankSbi = BankAccount::updateOrCreate(
            ['account_number' => '39482019482'],
            [
                'school_id' => $school->id,
                'bank_name' => 'State Bank of India',
                'account_name' => 'Fee Collection & Escrow Account',
                'account_type' => 'Savings',
                'ifsc_code' => 'SBIN0001249',
                'branch' => 'Collectorate Branch',
                'opening_balance' => 1500000.00,
                'current_balance' => 1892450.00
            ]
        );

        $bankIcici = BankAccount::updateOrCreate(
            ['account_number' => '002105018392'],
            [
                'school_id' => $school->id,
                'bank_name' => 'ICICI Bank',
                'account_name' => 'Student Welfare & CSR Fund',
                'account_type' => 'Current',
                'ifsc_code' => 'ICIC0000021',
                'branch' => 'Gomti Nagar Branch',
                'opening_balance' => 300000.00,
                'current_balance' => 480000.00
            ]
        );

        // 2. Income Heads
        $ihCanteen = IncomeHead::firstOrCreate(
            ['name' => 'Canteen Monthly Rent', 'school_id' => $school->id],
            ['description' => 'Commercial rent received from cafeteria concessionaire vendor']
        );
        $ihSponsor = IncomeHead::firstOrCreate(
            ['name' => 'Sports & Annual Day Sponsorship', 'school_id' => $school->id],
            ['description' => 'Corporate sponsorships and banners for sports/annual celebrations']
        );
        $ihScrap = IncomeHead::firstOrCreate(
            ['name' => 'Sale of Old Assets & Scraps', 'school_id' => $school->id],
            ['description' => 'Auction of obsolete desktop monitors, scrap metal, old newsprint']
        );
        $ihProspectus = IncomeHead::firstOrCreate(
            ['name' => 'Admission Prospectus & Kit Fees', 'school_id' => $school->id],
            ['description' => 'Sale of new admission application vouchers and prospectus kits']
        );
        $ihGrants = IncomeHead::firstOrCreate(
            ['name' => 'CSR & Alumni Education Grants', 'school_id' => $school->id],
            ['description' => 'Philanthropic support from alumni association and CSR programs']
        );

        // 3. Incomes
        $incomesData = [
            [
                'school_id' => $school->id,
                'income_head_id' => $ihCanteen->id,
                'school_bank_account_id' => $bankHdfc->id,
                'name' => 'Canteen Quarter 3 Commercial Lease',
                'invoice_no' => 'INC-2026-001',
                'invoice_number' => 'INC-2026-001',
                'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'amount' => 45000.00,
                'payment_method' => 'Cheque',
                'description' => 'Quarterly lease rent cleared by Shanti Caterers',
                'note' => 'Quarterly lease rent cleared by Shanti Caterers',
            ],
            [
                'school_id' => $school->id,
                'income_head_id' => $ihSponsor->id,
                'school_bank_account_id' => $bankHdfc->id,
                'name' => 'Decathlon Sports Meet Co-Sponsorship',
                'invoice_no' => 'INC-2026-002',
                'invoice_number' => 'INC-2026-002',
                'date' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'amount' => 35000.00,
                'payment_method' => 'UPI',
                'description' => 'Direct NEFT transfer for sports day branding rights',
                'note' => 'Direct NEFT transfer for sports day branding rights',
            ],
            [
                'school_id' => $school->id,
                'income_head_id' => $ihProspectus->id,
                'school_bank_account_id' => $bankSbi->id,
                'name' => 'Session 2026-27 Admission Kits Counter Collection',
                'invoice_no' => 'INC-2026-003',
                'invoice_number' => 'INC-2026-003',
                'date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'amount' => 28000.00,
                'payment_method' => 'Cash',
                'description' => '56 prospectus kits sold at reception counter @ Rs 500',
                'note' => '56 prospectus kits sold at reception counter @ Rs 500',
            ],
            [
                'school_id' => $school->id,
                'income_head_id' => $ihScrap->id,
                'school_bank_account_id' => $bankHdfc->id,
                'name' => 'Disposal of IT Lab E-Waste & Monitors',
                'invoice_no' => 'INC-2026-004',
                'invoice_number' => 'INC-2026-004',
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'amount' => 18500.00,
                'payment_method' => 'Online',
                'description' => 'Sold to certified e-waste recycling agency',
                'note' => 'Sold to certified e-waste recycling agency',
            ]
        ];

        foreach ($incomesData as $inc) {
            Income::updateOrCreate(['invoice_number' => $inc['invoice_number']], $inc);
        }

        // 4. Expense Heads
        $ehElectricity = ExpenseHead::firstOrCreate(
            ['name' => 'Electricity & Power Utilities', 'school_id' => $school->id],
            ['description' => 'Monthly electricity board billing for academic blocks and AC plants']
        );
        $ehInternet = ExpenseHead::firstOrCreate(
            ['name' => 'Internet Leased Line & Cloud ERP', 'school_id' => $school->id],
            ['description' => 'High speed 200Mbps optical fiber leased line and server infrastructure']
        );
        $ehFuel = ExpenseHead::firstOrCreate(
            ['name' => 'Campus Diesel Generator Fuel', 'school_id' => $school->id],
            ['description' => 'Diesel procurement for 125 KVA silent backup generator unit']
        );
        $ehLab = ExpenseHead::firstOrCreate(
            ['name' => 'Science Lab Consumables & Reagents', 'school_id' => $school->id],
            ['description' => 'Glassware, chemical solutions, microscope slides and biology charts']
        );
        $ehRepairs = ExpenseHead::firstOrCreate(
            ['name' => 'Building Repairs & Civil Maintenance', 'school_id' => $school->id],
            ['description' => 'Plumbing, washroom renovation, classroom masonry and whitewashing']
        );
        $ehSports = ExpenseHead::firstOrCreate(
            ['name' => 'Sports Equipment & Court Maintenance', 'school_id' => $school->id],
            ['description' => 'Footballs, basketball court acrylic paint, cricket nets repair']
        );

        // 5. Expenses
        $expensesData = [
            [
                'school_id' => $school->id,
                'title' => 'State Electricity Board Commercial Bill Oct',
                'name' => 'State Electricity Board Commercial Bill Oct',
                'category' => 'Utilities',
                'expense_head_id' => $ehElectricity->id,
                'school_bank_account_id' => $bankHdfc->id,
                'amount' => 38500.00,
                'date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'invoice_no' => 'EXP-2026-089',
                'invoice_number' => 'EXP-2026-089',
                'description' => 'Substation consumer account #9821-4820 October energy consumption',
                'remarks' => 'Substation consumer account #9821-4820 October energy consumption',
            ],
            [
                'school_id' => $school->id,
                'title' => 'Airtel Enterprise 200Mbps Leased Line',
                'name' => 'Airtel Enterprise 200Mbps Leased Line',
                'category' => 'Technology',
                'expense_head_id' => $ehInternet->id,
                'school_bank_account_id' => $bankHdfc->id,
                'amount' => 12400.00,
                'date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'invoice_no' => 'EXP-2026-090',
                'invoice_number' => 'EXP-2026-090',
                'description' => 'Dedicated static IP optical fiber internet for smart classrooms',
                'remarks' => 'Dedicated static IP optical fiber internet for smart classrooms',
            ],
            [
                'school_id' => $school->id,
                'title' => 'Generator 500L Bulk Diesel Fuel Refill',
                'name' => 'Generator 500L Bulk Diesel Fuel Refill',
                'category' => 'Operations',
                'expense_head_id' => $ehFuel->id,
                'school_bank_account_id' => $bankHdfc->id,
                'amount' => 44000.00,
                'date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'invoice_no' => 'EXP-2026-091',
                'invoice_number' => 'EXP-2026-091',
                'description' => 'Indian Oil retail outlet delivery tanker for main backup generator',
                'remarks' => 'Indian Oil retail outlet delivery tanker for main backup generator',
            ],
            [
                'school_id' => $school->id,
                'title' => 'Chemistry & Biology Lab Glassware Batch #12',
                'name' => 'Chemistry & Biology Lab Glassware Batch #12',
                'category' => 'Academics',
                'expense_head_id' => $ehLab->id,
                'school_bank_account_id' => $bankHdfc->id,
                'amount' => 21500.00,
                'date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'invoice_no' => 'EXP-2026-092',
                'invoice_number' => 'EXP-2026-092',
                'description' => 'Borosilicate test tubes, beakers, pipettes and hydrochloric acid stock',
                'remarks' => 'Borosilicate test tubes, beakers, pipettes and hydrochloric acid stock',
            ],
            [
                'school_id' => $school->id,
                'title' => 'Sports Meet Trophies, Medals & Certificates',
                'name' => 'Sports Meet Trophies, Medals & Certificates',
                'category' => 'Events',
                'expense_head_id' => $ehSports->id,
                'school_bank_account_id' => $bankHdfc->id,
                'amount' => 15200.00,
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'invoice_no' => 'EXP-2026-093',
                'invoice_number' => 'EXP-2026-093',
                'description' => 'Custom engraved brass trophies and participation medals for athletes',
                'remarks' => 'Custom engraved brass trophies and participation medals for athletes',
            ]
        ];

        foreach ($expensesData as $exp) {
            Expense::updateOrCreate(['invoice_number' => $exp['invoice_number']], $exp);
        }
    }
}

