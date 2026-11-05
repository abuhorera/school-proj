@extends('layouts.app')

@section('title', 'New Visitor Entry - MultiSchool ERP')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    @include('front_office.partials.subnav')

    <div class="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div>
                <h3 class="text-sm font-bold text-slate-800">New Visitor Check-In Terminal</h3>
                <p class="text-xs text-slate-500">Log incoming visitor at front security reception desk</p>
            </div>
            <a href="{{ route('front-office.visitor-logs') }}" class="text-xs text-slate-500 hover:text-slate-800 font-medium">
                &larr; Back to Visitor Book
            </a>
        </div>

        <form method="POST" action="{{ route('front-office.visitor-logs.store') }}" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Visitor Full Name <span class="text-rose-500">*</span></label>
                <input type="text" name="visitor_name" required placeholder="Full Name" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mobile / Phone <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required placeholder="10-digit number" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Card / Badge Number</label>
                    <input type="text" name="id_card_number" placeholder="e.g. V-904" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Purpose of Visit <span class="text-rose-500">*</span></label>
                <input type="text" name="purpose" required placeholder="e.g. PTM, Official Meeting, Delivery" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Person / Department to Meet <span class="text-rose-500">*</span></label>
                <input type="text" name="person_to_meet" required placeholder="e.g. Principal Office, Class 10 Coordinator, Accounts" class="w-full text-xs rounded-lg border border-slate-300 p-2.5 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                <a href="{{ route('front-office.visitor-logs') }}" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs transition">
                    Authorize Check-In
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

