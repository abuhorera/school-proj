@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    <!-- Back & Header -->
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('hpc.cards') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Progress Cards</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('hpc.cards.preview', $card->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-print"></i>
                <span>Preview Official Card</span>
            </a>
            <a href="{{ route('hpc.cards.colour', $card->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-violet-700 dark:text-violet-300 bg-violet-50 dark:bg-violet-950/40 border border-violet-200 dark:border-violet-800 rounded-lg hover:bg-violet-100 transition shadow-sm">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Colour Card (Parents)</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-4 p-4 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-600"></i>
                <span>{{ session('info') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700">&times;</button>
        </div>
    @endif

    <!-- Student Banner & Actions -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-700 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-md">
                    {{ substr($card->student->first_name ?? 'S', 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white">
                            {{ $card->student->full_name ?? ($card->student->first_name . ' ' . $card->student->last_name) }}
                        </h1>
                        @if($card->status === 'published')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Published</span>
                        @elseif($card->status === 'reviewed')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">Reviewed</span>
                        @elseif($card->status === 'teacher_submitted')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Submitted</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">Draft</span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-3 flex-wrap">
                        <span><strong>Stage:</strong> {{ $card->stage }} Stage</span>
                        <span>&middot;</span>
                        <span><strong>Term:</strong> {{ $card->term }}</span>
                        <span>&middot;</span>
                        <span><strong>Class:</strong> {{ $card->schoolClass->name ?? 'Class' }} (Roll {{ $card->student->roll_no ?? '-' }})</span>
                        <span>&middot;</span>
                        <span><strong>Admission:</strong> {{ $card->student->admission_no ?? 'ADM-' . $card->student_id }}</span>
                    </div>
                </div>
            </div>

            <!-- Review & Workflow Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                @if($card->status === 'teacher_submitted')
                    <form method="POST" action="{{ route('hpc.cards.review', $card->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-stamp mr-1"></i> Mark Reviewed
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('returnModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 rounded-xl transition">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Send Back to Teacher
                    </button>
                @elseif($card->status === 'reviewed')
                    <form method="POST" action="{{ route('hpc.cards.publish', $card->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-cloud-arrow-up mr-1"></i> Publish Card
                        </button>
                    </form>
                    <button type="button" onclick="document.getElementById('returnModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 rounded-xl transition">
                        <i class="fa-solid fa-rotate-left mr-1"></i> Return for Revisions
                    </button>
                @elseif($card->status === 'draft')
                    <form method="POST" action="{{ route('hpc.cards.review', $card->id) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                            <i class="fa-solid fa-paper-plane mr-1"></i> Submit for Review
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if($card->return_reason)
            <div class="mt-4 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-200 flex items-start gap-2">
                <i class="fa-solid fa-message text-amber-600 mt-0.5"></i>
                <div>
                    <strong>Teacher Revision Notes:</strong> {{ $card->return_reason }}
                </div>
            </div>
        @endif
    </div>

    <!-- Main Card Body: Sidebar Navigation + Section Form -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Left: Parts Navigation -->
        <div class="lg:col-span-1 space-y-2">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-4 shadow-sm">
                <div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">
                    Parts of this Card
                </div>

                <div class="space-y-1">
                    <a href="{{ route('hpc.cards.show', ['id' => $card->id, 'part' => 'A1']) }}" class="flex items-center justify-between p-3 rounded-xl text-xs font-medium transition {{ $activePart === 'A1' ? 'bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300 font-bold border border-violet-200 dark:border-violet-800' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/40' }}">
                        <div>
                            <div>Part A(1) — Learner & School</div>
                            <span class="text-[10px] text-slate-400 font-normal">Filled by Teacher</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[10px] {{ $activePart === 'A1' ? 'text-violet-600' : 'text-slate-400' }}"></i>
                    </a>

                    <a href="{{ route('hpc.cards.show', ['id' => $card->id, 'part' => 'A2']) }}" class="flex items-center justify-between p-3 rounded-xl text-xs font-medium transition {{ $activePart === 'A2' ? 'bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300 font-bold border border-violet-200 dark:border-violet-800' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/40' }}">
                        <div>
                            <div>Part A(2) — Self Reflection</div>
                            <span class="text-[10px] text-slate-400 font-normal">Child's Voice</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[10px] {{ $activePart === 'A2' ? 'text-violet-600' : 'text-slate-400' }}"></i>
                    </a>

                    <a href="{{ route('hpc.cards.show', ['id' => $card->id, 'part' => 'A3']) }}" class="flex items-center justify-between p-3 rounded-xl text-xs font-medium transition {{ $activePart === 'A3' ? 'bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300 font-bold border border-violet-200 dark:border-violet-800' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/40' }}">
                        <div>
                            <div>Part A(3) — Peer Voice</div>
                            <span class="text-[10px] text-slate-400 font-normal">Peer Assessment</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[10px] {{ $activePart === 'A3' ? 'text-violet-600' : 'text-slate-400' }}"></i>
                    </a>

                    <a href="{{ route('hpc.cards.show', ['id' => $card->id, 'part' => 'A4']) }}" class="flex items-center justify-between p-3 rounded-xl text-xs font-medium transition {{ $activePart === 'A4' ? 'bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300 font-bold border border-violet-200 dark:border-violet-800' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/40' }}">
                        <div>
                            <div>Part A(4) — Parent Feedback</div>
                            <span class="text-[10px] text-slate-400 font-normal">Caregiver Voice</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[10px] {{ $activePart === 'A4' ? 'text-violet-600' : 'text-slate-400' }}"></i>
                    </a>

                    <a href="{{ route('hpc.cards.show', ['id' => $card->id, 'part' => 'B']) }}" class="flex items-center justify-between p-3 rounded-xl text-xs font-medium transition {{ $activePart === 'B' ? 'bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300 font-bold border border-violet-200 dark:border-violet-800' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/40' }}">
                        <div>
                            <div>Part B — Domain Rubrics</div>
                            <span class="text-[10px] text-slate-400 font-normal">Progress Summary</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[10px] {{ $activePart === 'B' ? 'text-violet-600' : 'text-slate-400' }}"></i>
                    </a>

                    <a href="{{ route('hpc.cards.show', ['id' => $card->id, 'part' => 'C']) }}" class="flex items-center justify-between p-3 rounded-xl text-xs font-medium transition {{ $activePart === 'C' ? 'bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300 font-bold border border-violet-200 dark:border-violet-800' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/40' }}">
                        <div>
                            <div>Part C — Annual Summary</div>
                            <span class="text-[10px] text-slate-400 font-normal">Holistic Narrative & Signatures</span>
                        </div>
                        <i class="fa-solid fa-chevron-right text-[10px] {{ $activePart === 'C' ? 'text-violet-600' : 'text-slate-400' }}"></i>
                    </a>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 text-[11px] text-slate-400">
                    <p>A card can be sent for review with parts still blank — the instrument accommodates missing voices when needed.</p>
                </div>
            </div>
        </div>

        <!-- Right: Active Part Editor -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                @if($activePart === 'A1')
                    <!-- Part A(1): General Information & Health Profile -->
                    @php
                        $gen = $card->general_info_json ?? [];
                        $att = $card->attendance_json ?? [];
                    @endphp
                    <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Part A(1) — School & Learner Information</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Demographics, health metrics and monthly attendance records.</p>
                    </div>

                    <form method="POST" action="{{ route('hpc.cards.update-section', ['id' => $card->id, 'section' => 'A1']) }}" class="p-6 space-y-6">
                        @csrf

                        <!-- School Information -->
                        <div>
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-3">School Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Name of School</label>
                                    <input type="text" name="fields[school_name]" value="{{ $gen['school_name'] ?? 'YUG INTERNATIONAL SCHOOL' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">School Address</label>
                                    <input type="text" name="fields[school_address]" value="{{ $gen['school_address'] ?? 'Bhatagaon, Raipur, Chhattisgarh 490021' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">UDISE+ Code (11 digits)</label>
                                    <input type="text" name="fields[udise_code]" value="{{ $gen['udise_code'] ?? '22100405102' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Block Resource Centre (BRC)</label>
                                    <input type="text" name="fields[brc]" value="{{ $gen['brc'] ?? 'Raipur Urban BRC' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Cluster Resource Centre (CRC)</label>
                                    <input type="text" name="fields[crc]" value="{{ $gen['crc'] ?? 'Bhatagaon CRC' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">District</label>
                                    <input type="text" name="fields[district]" value="{{ $gen['district'] ?? 'Raipur' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">State</label>
                                    <input type="text" name="fields[state]" value="{{ $gen['state'] ?? 'Chhattisgarh' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Pin Code</label>
                                    <input type="text" name="fields[pin_code]" value="{{ $gen['pin_code'] ?? '490021' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                            </div>
                        </div>

                        <!-- Learner Demographics & Health Profile -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-3">Learner & Health Profile</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">APAAR ID</label>
                                    <input type="text" name="fields[apaar_id]" value="{{ $gen['apaar_id'] ?? '9845-1234-8765' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Blood Group</label>
                                    <input type="text" name="fields[blood_group]" value="{{ $gen['blood_group'] ?? ($card->student->blood_group ?? 'B+') }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Height (cm)</label>
                                    <input type="number" name="fields[height_cm]" value="{{ $gen['height_cm'] ?? 115 }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Weight (kg)</label>
                                    <input type="number" step="0.1" name="fields[weight_kg]" value="{{ $gen['weight_kg'] ?? 19.5 }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Attendance Table -->
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-2">Academic Year Attendance</h3>
                            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                                <table class="w-full text-center text-xs text-slate-700 dark:text-slate-300">
                                    <thead class="bg-slate-50 dark:bg-slate-900/50 font-semibold text-[10px] uppercase text-slate-500">
                                        <tr>
                                            <th class="p-2 border-r border-slate-200 dark:border-slate-700 text-left">Metric</th>
                                            @foreach(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'] as $m)
                                                <th class="p-2 border-r border-slate-200 dark:border-slate-700">{{ $m }}</th>
                                            @endforeach
                                            <th class="p-2">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                        <tr>
                                            <td class="p-2 border-r border-slate-200 dark:border-slate-700 text-left font-medium">Days Present</td>
                                            @foreach(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'] as $m)
                                                <td class="p-2 border-r border-slate-200 dark:border-slate-700">{{ $att[$m]['present'] ?? 20 }}</td>
                                            @endforeach
                                            <td class="p-2 font-bold text-violet-600">{{ $att['total_present'] ?? 230 }}</td>
                                        </tr>
                                        <tr class="bg-slate-50/50 dark:bg-slate-900/30">
                                            <td class="p-2 border-r border-slate-200 dark:border-slate-700 text-left font-medium">Working Days</td>
                                            @foreach(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'] as $m)
                                                <td class="p-2 border-r border-slate-200 dark:border-slate-700">{{ $att[$m]['working'] ?? 22 }}</td>
                                            @endforeach
                                            <td class="p-2 font-bold">{{ $att['total_working'] ?? 240 }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                                Save Section A(1)
                            </button>
                        </div>
                    </form>

                @elseif($activePart === 'A2')
                    <!-- Part A(2): Self-Assessment (Child's Voice) -->
                    @php $self = $card->self_assessment_json ?? []; @endphp
                    <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Part A(2) — Self-Assessment (Child's Voice)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Captured in discussion with learner: their reflections, passions and goals.</p>
                    </div>

                    <form method="POST" action="{{ route('hpc.cards.update-section', ['id' => $card->id, 'section' => 'A2']) }}" class="p-6 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">What I like doing most at school</label>
                            <textarea name="self_assessment[likes]" rows="3" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $self['likes'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Things I am proud of / My strengths</label>
                            <textarea name="self_assessment[strengths]" rows="3" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $self['strengths'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Things I want to learn or get better at</label>
                            <textarea name="self_assessment[goals]" rows="3" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $self['goals'] ?? '' }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">My Favorite Classroom Corner</label>
                                <input type="text" name="self_assessment[favorite_corner]" value="{{ $self['favorite_corner'] ?? 'Art & Story Corner' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">How I feel about my learning journey</label>
                                <select name="self_assessment[self_emoji]" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                    <option value="Happy & Enthusiastic" {{ ($self['self_emoji'] ?? '') == 'Happy & Enthusiastic' ? 'selected' : '' }}>😊 Happy & Enthusiastic</option>
                                    <option value="Curious & Creative" {{ ($self['self_emoji'] ?? '') == 'Curious & Creative' ? 'selected' : '' }}>🌟 Curious & Creative</option>
                                    <option value="Calm & Thoughtful" {{ ($self['self_emoji'] ?? '') == 'Calm & Thoughtful' ? 'selected' : '' }}>🌿 Calm & Thoughtful</option>
                                    <option value="Excited to Explore More" {{ ($self['self_emoji'] ?? '') == 'Excited to Explore More' ? 'selected' : '' }}>🚀 Excited to Explore More</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                                Save Self-Assessment
                            </button>
                        </div>
                    </form>

                @elseif($activePart === 'A3')
                    <!-- Part A(3): Peer Assessment (Peer's Voice) -->
                    @php $peer = $card->peer_assessment_json ?? []; @endphp
                    <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Part A(3) — Peer Assessment (Peer's Voice)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Cooperative reflection by a peer or classmate on kindness, sharing, and teamwork.</p>
                    </div>

                    <form method="POST" action="{{ route('hpc.cards.update-section', ['id' => $card->id, 'section' => 'A3']) }}" class="p-6 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Peer Name & Roll No.</label>
                            <input type="text" name="peer_assessment[peer_name]" value="{{ $peer['peer_name'] ?? '' }}" placeholder="e.g. Aarav Sharma (Roll 1)" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">What I like about working with my friend</label>
                            <textarea name="peer_assessment[peer_remarks]" rows="3" placeholder="Share observations about playing, sharing materials, taking turns, and solving tasks together..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $peer['peer_remarks'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Cooperation & Helpful Rating</label>
                            <select name="peer_assessment[cooperation_rating]" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                                <option value="Always Helpful & Cooperative" {{ ($peer['cooperation_rating'] ?? '') == 'Always Helpful & Cooperative' ? 'selected' : '' }}>Always Helpful & Cooperative</option>
                                <option value="Frequently Shares & Plays Well" {{ ($peer['cooperation_rating'] ?? '') == 'Frequently Shares & Plays Well' ? 'selected' : '' }}>Frequently Shares & Plays Well</option>
                                <option value="Learning to Take Turns" {{ ($peer['cooperation_rating'] ?? '') == 'Learning to Take Turns' ? 'selected' : '' }}>Learning to Take Turns</option>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                                Save Peer Assessment
                            </button>
                        </div>
                    </form>

                @elseif($activePart === 'A4')
                    <!-- Part A(4): Parent & Caregiver Feedback -->
                    @php $parent = $card->parent_feedback_json ?? []; @endphp
                    <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Part A(4) — Parent & Caregiver Perspective</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Family observations about habits at home, curiosity, and emotional well-being.</p>
                    </div>

                    <form method="POST" action="{{ route('hpc.cards.update-section', ['id' => $card->id, 'section' => 'A4']) }}" class="p-6 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Parent / Guardian Name</label>
                            <input type="text" name="parent_feedback[parent_name]" value="{{ $parent['parent_name'] ?? '' }}" placeholder="e.g. Mrs. & Mr. Sharma" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Habits and activities observed at home</label>
                            <textarea name="parent_feedback[habits_at_home]" rows="3" placeholder="Describe routine, reading habits, self-care, and creative play at home..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $parent['habits_at_home'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Child's curiosity and emotional well-being</label>
                            <textarea name="parent_feedback[parent_observation]" rows="3" placeholder="How does the child express feelings, curiosity and empathy with family and neighbors?" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $parent['parent_observation'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Suggestions & Comments for School</label>
                            <textarea name="parent_feedback[suggestions]" rows="2" placeholder="Any special areas of interest or assistance requested from educators..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $parent['suggestions'] ?? '' }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                                Save Parent Feedback
                            </button>
                        </div>
                    </form>

                @elseif($activePart === 'B')
                    <!-- Part B: Developmental Domain Competencies -->
                    @php
                        $scores = $card->domain_scores_json ?? [];
                        $domains = [
                            'Physical Development' => ['Gross motor balance', 'Fine motor coordination', 'Personal hygiene habits', 'Nutritional awareness'],
                            'Socio-Emotional and Ethical Development' => ['Empathy towards peers', 'Emotional self-regulation', 'Cooperative play', 'Kindness and sharing'],
                            'Cognitive Development' => ['Sensory inquiry and observation', 'Problem-solving persistence', 'Pattern recognition', 'Logical reasoning'],
                            'Language and Literacy Development' => ['Active listening', 'Expressive vocabulary', 'Phonemic awareness', 'Early mark-making/writing'],
                            'Aesthetic and Cultural Development' => ['Rhythmic movement and music', 'Visual arts and craft', 'Dramatic roleplay', 'Appreciation of beauty'],
                            'Positive Learning Habits' => ['Curiosity to explore', 'Concentration on task', 'Care for environment and materials'],
                        ];
                    @endphp
                    <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Part B — Progress Summary (Developmental Domains)</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Rate learner competency across foundational developmental domains without numerical marks.</p>
                    </div>

                    <form method="POST" action="{{ route('hpc.cards.update-section', ['id' => $card->id, 'section' => 'B']) }}" class="p-6 space-y-6">
                        @csrf

                        @foreach($domains as $domTitle => $criteriaList)
                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/40 dark:bg-slate-900/20">
                                <h3 class="text-xs font-bold text-violet-700 dark:text-violet-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-dot text-[9px]"></i>
                                    <span>{{ $domTitle }}</span>
                                </h3>

                                <div class="space-y-2.5">
                                    @foreach($criteriaList as $crit)
                                        @php
                                            $curr = $scores[$domTitle][$crit] ?? '';
                                        @endphp
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-2.5 rounded-lg bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700">
                                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">{{ $crit }}</span>
                                            <div class="flex items-center gap-2">
                                                <label class="inline-flex items-center gap-1 text-[11px] cursor-pointer">
                                                    <input type="radio" name="domain_scores[{{ $domTitle }}][{{ $crit }}]" value="B" {{ $curr === 'B' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                                                    <span class="text-amber-700 dark:text-amber-300 font-semibold">B (Beginner)</span>
                                                </label>
                                                <label class="inline-flex items-center gap-1 text-[11px] cursor-pointer">
                                                    <input type="radio" name="domain_scores[{{ $domTitle }}][{{ $crit }}]" value="P" {{ $curr === 'P' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                                                    <span class="text-blue-700 dark:text-blue-300 font-semibold">P (Proficient)</span>
                                                </label>
                                                <label class="inline-flex items-center gap-1 text-[11px] cursor-pointer">
                                                    <input type="radio" name="domain_scores[{{ $domTitle }}][{{ $crit }}]" value="A" {{ $curr === 'A' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                                                    <span class="text-emerald-700 dark:text-emerald-300 font-semibold">A (Advanced)</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                                Save Domain Progress Summary
                            </button>
                        </div>
                    </form>

                @elseif($activePart === 'C')
                    <!-- Part C: Summary for the Academic Year -->
                    @php $narrative = $card->teacher_summary_json ?? []; @endphp
                    <div class="p-5 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Part C — Summary for the Academic Year</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Teacher's holistic narrative, areas of growth, and principal endorsements.</p>
                    </div>

                    <form method="POST" action="{{ route('hpc.cards.update-section', ['id' => $card->id, 'section' => 'C']) }}" class="p-6 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Class Teacher Name</label>
                            <input type="text" name="teacher_summary[teacher_name]" value="{{ $narrative['teacher_name'] ?? 'Ms. Ananya Sen' }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Teacher's Holistic Narrative (Comprehensive Growth Reflection)</label>
                            <textarea name="teacher_summary[holistic_remarks]" rows="4" placeholder="Provide a narrative of the whole child: habits, empathy, creativity, persistence..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $narrative['holistic_remarks'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Identified Areas for Future Growth & Scaffolding</label>
                            <textarea name="teacher_summary[areas_of_growth]" rows="3" placeholder="Targeted steps for next academic session..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $narrative['areas_of_growth'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Principal / Head of Institution Remarks & Promotion Endorsement</label>
                            <textarea name="teacher_summary[principal_remarks]" rows="3" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-3 focus:ring-violet-500">{{ $narrative['principal_remarks'] ?? 'Promoted with distinction to next stage.' }}</textarea>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                            <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                                Save Year-End Summary
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Return to Teacher Modal -->
<div id="returnModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Return Card to Teacher</h3>
        <p class="text-xs text-slate-500 mb-4">Provide guidance on what sections or evaluations need review before final sign-off.</p>
        <form method="POST" action="{{ route('hpc.cards.return', $card->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Reason / Revision Notes</label>
                <textarea name="reason" rows="3" required placeholder="E.g. Please complete Part A(3) peer observation and check attendance total..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-violet-500"></textarea>
            </div>
            <div class="flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition">Return to Teacher</button>
            </div>
        </form>
    </div>
</div>
@endsection

