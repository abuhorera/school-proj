@extends('layouts.app')

@section('title', 'Log Admission Enquiry - MultiSchool ERP')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    @include('front_office.partials.subnav')

    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Log New Admission Enquiry</h3>
                <p class="text-xs text-slate-500">Record prospective candidate details and counseling requirements</p>
            </div>
            <a href="{{ route('front-office.admission-enquiries') }}" class="text-xs text-slate-500 hover:text-slate-800 font-medium">
                &larr; Back to Enquiries
            </a>
        </div>

        <form method="POST" action="{{ route('front-office.admission-enquiries.store') }}" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Student / Candidate Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="student_name" required placeholder="Full Name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Parent / Guardian Name</label>
                    <input type="text" name="parent_name" placeholder="Father or Mother Name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile / Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required placeholder="10-digit mobile number" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email Address</label>
                    <input type="email" name="email" placeholder="parent@example.com" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Class of Interest</label>
                    <select name="school_class_id" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <option value="">-- Choose Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Enquiry Source</label>
                    <select name="admission_source" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <option value="Walk In">Walk In</option>
                        <option value="Website">Website</option>
                        <option value="Phone Call">Phone Call</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Referral">Referral</option>
                        <option value="Newspaper Ad">Newspaper Ad</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Enquiry Date <span class="text-rose-500">*</span></label>
                    <input type="date" name="enquiry_date" required value="{{ date('Y-m-d') }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Next Follow-up Date</label>
                    <input type="date" name="follow_up_date" value="{{ date('Y-m-d', strtotime('+3 days')) }}" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Assigned Counselor / Staff</label>
                <select name="assigned_to" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                    <option value="">-- Assign Counselor --</option>
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->first_name }} {{ $st->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Discussion Notes / Queries</label>
                <textarea name="notes" rows="3" placeholder="Enter remarks regarding fees, curriculum query, bus route..." class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                <a href="{{ route('front-office.admission-enquiries') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-cyan-600 hover:bg-cyan-700 rounded-lg shadow-xs transition">
                    Save Admission Enquiry
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

