@extends('layouts.app')

@section('title', 'PTM Guide - Parent Teacher Meetings')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    @include('ptm.partials.subnav')

    <!-- Header bar -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-book-open-reader"></i>
                </span>
                <h1 class="text-lg font-black text-slate-800">PTM Operational Guide</h1>
            </div>
            <p class="text-xs text-slate-400 mt-1">Lifecycle walkthrough, role capability permissions, and conference management best practices</p>
        </div>
        <a href="{{ route('ptm.meetings.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs">
            <i class="fa-solid fa-plus"></i>
            <span>Schedule Meeting</span>
        </a>
    </div>

    <!-- Section 1: Workflow Lifecycle -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6">
        <div class="flex items-center space-x-2.5 mb-4">
            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs">1</span>
            <h3 class="font-bold text-slate-800 text-sm">How the PTM Workflow Runs</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs mb-2">1</div>
                <h4 class="font-bold text-slate-900 text-xs">Schedule</h4>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Admin or Academic Head sets date, time, mode (in-person/hybrid/online), and conference scope.</p>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-sky-500 text-white flex items-center justify-center font-bold text-xs mb-2">2</div>
                <h4 class="font-bold text-slate-900 text-xs">Notify</h4>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">System generates individual guardian slots and dispatches notifications via portal and SMS/WhatsApp.</p>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-indigo-500 text-white flex items-center justify-center font-bold text-xs mb-2">3</div>
                <h4 class="font-bold text-slate-900 text-xs">Record</h4>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Faculty marks guardian check-in status (Present/Absent/Rescheduled) and logs holistic student feedback.</p>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-purple-500 text-white flex items-center justify-center font-bold text-xs mb-2">4</div>
                <h4 class="font-bold text-slate-900 text-xs">Follow-up</h4>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Assign remedial action plans, extra study sessions, or parental intervention tasks with target due dates.</p>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-xs mb-2">5</div>
                <h4 class="font-bold text-slate-900 text-xs">Report</h4>
                <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">Review class-wise attendance rates, star rating distribution, and generate printable audit reports.</p>
            </div>
        </div>
    </div>

    <!-- Section 2: Roles & Capabilities Matrix (Matching Live Demo) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center space-x-2.5">
            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs">2</span>
            <h3 class="font-bold text-slate-800 text-sm">Roles & What They Can Do</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-5">Capability</th>
                        <th class="py-3 px-5 text-center">Admin</th>
                        <th class="py-3 px-5 text-center">Teacher / Faculty</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-5 font-semibold text-slate-900">Schedule meetings & send invitations</td>
                        <td class="py-3 px-5 text-center text-emerald-600 font-bold text-sm">✓</td>
                        <td class="py-3 px-5 text-center text-slate-300 font-bold text-sm">—</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-5 font-semibold text-slate-900">View dashboard & operational guide</td>
                        <td class="py-3 px-5 text-center text-emerald-600 font-bold text-sm">✓</td>
                        <td class="py-3 px-5 text-center text-emerald-600 font-bold text-sm">✓</td>
                    </tr>
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-5 font-semibold text-slate-900">Record parent attendance</td>
                        <td class="py-3 px-5 text-center text-emerald-600 font-bold text-sm">✓</td>
                        <td class="py-3 px-5 text-center text-slate-700 font-medium">✓ <span class="text-slate-400 text-[10px]">(own classes)</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-5 font-semibold text-slate-900">Write student remarks & assign ratings</td>
                        <td class="py-3 px-5 text-center text-emerald-600 font-bold text-sm">✓</td>
                        <td class="py-3 px-5 text-center text-slate-700 font-medium">✓ <span class="text-slate-400 text-[10px]">(own classes)</span></td>
                    </tr>
                    <tr class="hover:bg-slate-50/50">
                        <td class="py-3 px-5 font-semibold text-slate-900">Manage follow-ups & view reports</td>
                        <td class="py-3 px-5 text-center text-emerald-600 font-bold text-sm">✓</td>
                        <td class="py-3 px-5 text-center text-slate-700 font-medium">✓ <span class="text-slate-400 text-[10px]">(own classes)</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 3: Good to Know & Best Practices -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-6 space-y-4">
        <div class="flex items-center space-x-2.5">
            <span class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center font-black text-xs">3</span>
            <h3 class="font-bold text-slate-800 text-sm">Good to Know</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 bg-amber-50/50 rounded-xl border border-amber-100 space-y-1.5">
                <h4 class="font-bold text-amber-900 flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrows-split-up-and-left text-amber-600"></i>
                    <span>Flexible Scope</span>
                </h4>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    A single section, an entire class, or a whole-school PTM — pick whatever matches your academic calendar.
                </p>
            </div>

            <div class="p-4 bg-sky-50/50 rounded-xl border border-sky-100 space-y-1.5">
                <h4 class="font-bold text-sky-900 flex items-center space-x-1.5">
                    <i class="fa-solid fa-clock-rotate-left text-sky-600"></i>
                    <span>Time Slot Allotment</span>
                </h4>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    When auto-roster generation is active, staggered 15-minute meeting windows prevent crowded waiting lounges.
                </p>
            </div>

            <div class="p-4 bg-emerald-50/50 rounded-xl border border-emerald-100 space-y-1.5">
                <h4 class="font-bold text-emerald-900 flex items-center space-x-1.5">
                    <i class="fa-solid fa-eye text-emerald-600"></i>
                    <span>Parent Portal Visibility</span>
                </h4>
                <p class="text-slate-600 text-[11px] leading-relaxed">
                    Toggle individual remarks to be visible or internal so teachers can note sensitive feedback safely.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

