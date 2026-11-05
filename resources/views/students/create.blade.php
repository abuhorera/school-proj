@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Student SIS Subnav -->
    @include('students.partials.subnav')

    <!-- Header -->
    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-user-plus text-blue-600"></i>
                <span>Student Admission Wizard</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Enroll new candidate into the active academic session with demographic, health and parent records</p>
        </div>
        <a href="{{ route('students.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-xs transition">
            &larr; Back to Directory
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="space-y-6 text-xs">
        @csrf

        <!-- 1. Academic & Roll Identification -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2 pb-2 border-b border-slate-100">
                <i class="fa-solid fa-graduation-cap text-blue-600"></i>
                <span>1. Academic Enrollment & Class Allocation</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Admission No. <span class="text-red-500">*</span></label>
                    <input type="text" name="admission_no" value="{{ $nextAdmissionNo }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono font-bold text-blue-600 focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Roll Number</label>
                    <input type="text" name="roll_no" placeholder="e.g. 101" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Class <span class="text-red-500">*</span></label>
                    <select name="class_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                        <option value="">Select Class</option>
                        @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Section <span class="text-red-500">*</span></label>
                    <select name="section_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                        <option value="1">Section A</option>
                        <option value="2">Section B</option>
                        <option value="3">Section C</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Admission Date</label>
                    <input type="date" name="admission_date" value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Student House</label>
                    <select name="house_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                        <option value="">No House Assigned</option>
                        @foreach($houses as $h)
                        <option value="{{ $h->id }}">{{ $h->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Category / Quota</label>
                    <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                        <option value="">General Quota</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. Personal Student Profile -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2 pb-2 border-b border-slate-100">
                <i class="fa-solid fa-user text-blue-600"></i>
                <span>2. Candidate Personal Particulars</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" required placeholder="e.g. Aarav" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Last Name</label>
                    <input type="text" name="last_name" placeholder="e.g. Sharma" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Date of Birth</label>
                    <input type="date" name="dob" value="2014-05-10" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Blood Group</label>
                    <select name="blood_group" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Religion</label>
                    <input type="text" name="religion" placeholder="e.g. Hindu, Muslim, Sikh, Christian" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
            </div>
        </div>

        <!-- 3. Guardian & Parent Particulars -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2 pb-2 border-b border-slate-100">
                <i class="fa-solid fa-people-roof text-blue-600"></i>
                <span>3. Parent & Guardian Contact</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Father's Name <span class="text-red-500">*</span></label>
                    <input type="text" name="father_name" required placeholder="e.g. Rajesh Sharma" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Father's Mobile Phone</label>
                    <input type="tel" name="father_phone" placeholder="+91 98765-43210" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Mother's Name</label>
                    <input type="text" name="mother_name" placeholder="e.g. Sunita Sharma" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
            </div>

            <div>
                <label class="block font-bold text-slate-700 mb-1">Current Residential Address</label>
                <textarea name="address" rows="2" placeholder="House number, Street, City, Pincode..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500"></textarea>
            </div>
        </div>

        <!-- 4. Health & Pediatric Records -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="font-bold text-sm text-slate-800 flex items-center space-x-2 pb-2 border-b border-slate-100">
                <i class="fa-solid fa-heart-pulse text-blue-600"></i>
                <span>4. Health & Medical Background</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Height (cm)</label>
                    <input type="number" step="0.1" name="height" placeholder="142.5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Weight (kg)</label>
                    <input type="number" step="0.1" name="weight" placeholder="38.0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 font-mono focus:outline-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Allergies / Special Alerts</label>
                    <input type="text" name="allergies" placeholder="e.g. Dust allergy, Mild Asthmatic" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 focus:outline-blue-500">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 flex items-center justify-end space-x-3">
            <a href="{{ route('students.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-2.5 rounded-xl shadow-xs transition flex items-center space-x-2">
                <i class="fa-solid fa-check text-xs"></i>
                <span>Complete Student Enrollment</span>
            </button>
        </div>
    </form>
</div>
@endsection
