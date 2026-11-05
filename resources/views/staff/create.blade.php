@extends('layouts.app')

@section('title', 'Add New Staff Member - Human Resource')

@section('content')
<div class="space-y-6">
    @include('staff.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-user-plus"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">Add New Staff Member</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Enroll faculty or operational staff, setup compensation baseline, and create institutional user profile</p>
        </div>
        <a href="{{ route('staff.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 border border-slate-200 self-start md:self-auto">
            <i class="fa-solid fa-arrow-left text-slate-500"></i>
            <span>Back to Staff Directory</span>
        </a>
    </div>

    <!-- Staff Onboarding Form -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <form action="{{ route('staff.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Basic Particulars -->
            <div>
                <h3 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3 flex items-center space-x-2 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-id-badge text-pink-500"></i>
                    <span>1. Employee Identity & Personal Details</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Staff / Employee ID <span class="text-rose-500">*</span></label>
                        <input type="text" name="employee_id" value="{{ $nextEmployeeId }}" required class="w-full text-xs font-mono font-bold rounded-xl border border-slate-200 px-3 py-2 bg-slate-50 focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">First Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="first_name" required placeholder="e.g. Ramesh" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Last Name</label>
                        <input type="text" name="last_name" placeholder="e.g. Verma" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Official Email Address <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required placeholder="e.g. ramesh.verma@multischoolerp.com" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" required placeholder="e.g. +91 98765 43210" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Date of Joining</label>
                        <input type="date" name="joining_date" value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    </div>
                </div>
            </div>

            <!-- Section 2: Department & Designation Allocation -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3 flex items-center space-x-2 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-briefcase text-pink-500"></i>
                    <span>2. Department, Role & Qualifications</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Department <span class="text-rose-500">*</span></label>
                        <select name="department" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                            @foreach($departments as $d)
                            <option value="{{ $d->name }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Designation <span class="text-rose-500">*</span></label>
                        <select name="designation" required class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                            @foreach($designations as $des)
                            <option value="{{ $des->name }}">{{ $des->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Academic Qualification</label>
                        <input type="text" name="qualification" placeholder="e.g. M.Sc. Mathematics, B.Ed." class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Compensation & Payroll Setup -->
            <div class="pt-4 border-t border-slate-100">
                <h3 class="text-xs font-black uppercase tracking-wider text-pink-600 mb-3 flex items-center space-x-2 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-wallet text-pink-500"></i>
                    <span>3. Compensation Baseline & Salary Template</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Assign Salary Template</label>
                        <select name="salary_template_id" class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                            <option value="">-- Custom Individual Structure --</option>
                            @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}">{{ $tpl->name }} (Basic: ₹{{ number_format($tpl->basic_salary) }}, Net: ₹{{ number_format($tpl->net_salary) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Monthly Basic Pay (₹) <span class="text-rose-500">*</span></label>
                        <input type="number" name="basic_salary" value="35000" min="1000" required class="w-full text-xs font-bold rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-pink-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <a href="{{ route('staff.index') }}" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50">Cancel</a>
                <button type="submit" class="px-6 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Save & Onboard Staff</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
