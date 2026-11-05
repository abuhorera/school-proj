<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holistic CBC Progress Card - {{ $student->first_name }} {{ $student->last_name }}</title>
    <!-- Tailwind CSS CDN for printable document -->
        <!-- Local Compiled CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- FontAwesome 6 (Local primary with CDN fallback) -->
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">
    <!-- Tailwind Engine -->
    <script src="{{ asset('js/tailwind.cdn.js') }}"></script>
    <script>
        if (typeof tailwind === 'undefined') {
            document.write('<script src="https://cdn.tailwindcss.com"><\/script>');
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 text-slate-800 font-sans">

    <!-- Top Action Bar (hidden when printing) -->
    <div class="max-w-4xl mx-auto mb-6 px-4 no-print flex items-center justify-between">
        <a href="{{ route('cbc.reports') }}" class="px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold transition flex items-center space-x-1.5 shadow-xs">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to CBC Reports</span>
        </a>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-md">
                <i class="fa-solid fa-print"></i>
                <span>Print Progress Card</span>
            </button>
        </div>
    </div>

    <!-- Official Report Card Document Container -->
    <div class="max-w-4xl mx-auto bg-white border border-slate-200 rounded-3xl shadow-xl p-8 md:p-12">
        <!-- School Header Banner -->
        <div class="border-b-2 border-indigo-600 pb-6 mb-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-3xl font-black shadow-md">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ $school->name ?? 'MultiSchool International Academy' }}</h1>
                    <p class="text-xs text-slate-500">{{ $school->address ?? 'Central Campus, Sector 4' }} • Phone: {{ $school->phone ?? '+91 98765 43210' }}</p>
                    <p class="text-[11px] text-indigo-700 font-bold uppercase tracking-wider mt-1">Holistic Progress Card (HPC) • NEP 2020 Competency Framework</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-block px-3 py-1 bg-indigo-50 border border-indigo-200 rounded-full text-indigo-800 text-[10px] font-black uppercase tracking-wider">
                    Official Assessment Profile
                </span>
                <p class="text-[11px] text-slate-500 font-mono mt-1">Academic Year: 2025–2026</p>
            </div>
        </div>

        <!-- Student Biodata Card -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 mb-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Student Name</span>
                    <strong class="text-slate-800 text-sm">{{ $student->first_name }} {{ $student->last_name }}</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Admission No</span>
                    <span class="font-mono font-bold text-slate-700">{{ $student->admission_number ?? 'ST-'.$student->id }}</span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Class & Section</span>
                    <strong class="text-slate-800">{{ $student->latestEnrollment->schoolClass->name ?? 'Class 10' }} - {{ $student->latestEnrollment->section->name ?? 'A' }}</strong>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Guardian / Parent</span>
                    <span class="text-slate-700 font-semibold">{{ $student->guardian->name ?? 'Parent / Guardian' }}</span>
                </div>
            </div>
        </div>

        <!-- Rubric Performance Scale Legend -->
        <div class="bg-indigo-50/60 rounded-xl p-3 border border-indigo-100 mb-6 text-xs">
            <div class="font-bold text-indigo-900 text-[11px] uppercase tracking-wider mb-2 flex items-center space-x-1.5">
                <i class="fa-solid fa-shapes text-indigo-600"></i>
                <span>Competency Rubric Achievement Key</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[11px]">
                <div class="flex items-center space-x-2 bg-white px-2.5 py-1.5 rounded-lg border border-indigo-100 shadow-2xs">
                    <span class="px-2 py-0.5 bg-emerald-600 text-white rounded font-black text-[10px]">EE</span>
                    <div>
                        <div class="font-bold text-slate-800">Exceeding Expectations</div>
                        <div class="text-[9px] text-slate-400">Level 4: Advanced Mastery</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-white px-2.5 py-1.5 rounded-lg border border-indigo-100 shadow-2xs">
                    <span class="px-2 py-0.5 bg-blue-600 text-white rounded font-black text-[10px]">ME</span>
                    <div>
                        <div class="font-bold text-slate-800">Meeting Expectations</div>
                        <div class="text-[9px] text-slate-400">Level 3: Proficient</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-white px-2.5 py-1.5 rounded-lg border border-indigo-100 shadow-2xs">
                    <span class="px-2 py-0.5 bg-amber-500 text-white rounded font-black text-[10px]">AE</span>
                    <div>
                        <div class="font-bold text-slate-800">Approaching Expectations</div>
                        <div class="text-[9px] text-slate-400">Level 2: Developing</div>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-white px-2.5 py-1.5 rounded-lg border border-indigo-100 shadow-2xs">
                    <span class="px-2 py-0.5 bg-rose-600 text-white rounded font-black text-[10px]">BE</span>
                    <div>
                        <div class="font-bold text-slate-800">Below Expectations</div>
                        <div class="text-[9px] text-slate-400">Level 1: Needs Support</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Subject-wise Learning Outcomes & Demonstrable Competencies -->
        <div class="mb-8">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center space-x-2 border-b border-slate-200 pb-2">
                <i class="fa-solid fa-list-check text-indigo-600"></i>
                <span>Part I: Learning Outcomes & Curricular Strands Evaluation</span>
            </h3>

            @if($evaluations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border border-slate-200 rounded-xl overflow-hidden">
                    <thead class="bg-slate-100 text-slate-600 font-bold uppercase text-[10px]">
                        <tr>
                            <th class="py-2.5 px-3 border-b border-slate-200">Domain / Subject</th>
                            <th class="py-2.5 px-3 border-b border-slate-200">Learning Outcome & Strand</th>
                            <th class="py-2.5 px-3 border-b border-slate-200 text-center">Level</th>
                            <th class="py-2.5 px-3 border-b border-slate-200">Teacher Qualitative Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($evaluations as $ev)
                        @php
                            $badgeColor = match($ev->rubric_level) {
                                'EE' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                'ME' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'AE' => 'bg-amber-100 text-amber-800 border-amber-300',
                                'BE' => 'bg-rose-100 text-rose-800 border-rose-300',
                                default => 'bg-slate-100 text-slate-800 border-slate-300',
                            };
                        @endphp
                        <tr>
                            <td class="py-2.5 px-3 font-bold text-slate-800 align-top">
                                {{ $ev->assessment->subject->name ?? 'General' }}
                                <span class="block text-[10px] text-slate-400 font-normal">{{ $ev->assessment->title ?? '' }}</span>
                            </td>
                            <td class="py-2.5 px-3 align-top">
                                <span class="font-bold text-slate-800 block">{{ $ev->outcome->name ?? 'Core Learning Outcome' }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">Strand: {{ $ev->outcome->strand->name ?? 'Syllabus Strand' }} ({{ $ev->outcome->code ?? '' }})</span>
                            </td>
                            <td class="py-2.5 px-3 text-center align-top">
                                <span class="inline-block px-2.5 py-1 rounded-md font-black text-xs border {{ $badgeColor }}">
                                    {{ $ev->rubric_level }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-[11px] text-slate-600 italic align-top">
                                {{ $ev->teacher_feedback ?? 'Demonstrates solid conceptual understanding and applies skills effectively during class projects.' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-6 bg-slate-50 border border-slate-200 rounded-xl text-center text-slate-400 text-xs">
                No specific assessment outcomes recorded yet.
            </div>
            @endif
        </div>

        <!-- Section 2: 21st-Century Core Competencies Mastery Profile -->
        <div class="mb-8">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center space-x-2 border-b border-slate-200 pb-2">
                <i class="fa-solid fa-lightbulb text-violet-600"></i>
                <span>Part II: 21st-Century Core Competency Observational Profile</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($competencies as $comp)
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="flex items-center justify-between mb-1.5">
                        <h4 class="font-bold text-slate-800 text-xs flex items-center space-x-1.5">
                            <i class="fa-solid fa-star text-amber-500 text-[10px]"></i>
                            <span>{{ $comp->name }}</span>
                        </h4>
                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded-full">
                            Proficient
                        </span>
                    </div>
                    @if(is_array($comp->indicators) && count($comp->indicators) > 0)
                    <p class="text-[11px] text-slate-500 italic">"{{ $comp->indicators[0] }}"</p>
                    @else
                    <p class="text-[11px] text-slate-500 italic">{{ $comp->description ?? 'Consistent positive attitude and teamwork demonstrated.' }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <!-- Qualitative Holistic Reflection & Signatures -->
        <div class="border-t-2 border-slate-200 pt-6 mt-8 space-y-6">
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Class Teacher's Holistic Summary & Recommendations</h4>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-700 italic leading-relaxed">
                    {{ $student->first_name }} has demonstrated commendable diligence, critical inquiry, and proactive collaboration throughout the term. Highly recommended to continue exploring advanced investigative projects in STEM and active leadership in group seminars.
                </div>
            </div>

            <!-- Signatures Row -->
            <div class="grid grid-cols-3 gap-6 pt-10 text-center text-xs">
                <div>
                    <div class="border-b border-slate-400 w-36 mx-auto mb-1.5"></div>
                    <span class="font-bold text-slate-800 block">Class Teacher</span>
                    <span class="text-[10px] text-slate-400">Assessment Facilitator</span>
                </div>
                <div>
                    <div class="border-b border-slate-400 w-36 mx-auto mb-1.5"></div>
                    <span class="font-bold text-slate-800 block">CBC Coordinator</span>
                    <span class="text-[10px] text-slate-400">Curriculum Lead</span>
                </div>
                <div>
                    <div class="border-b border-slate-400 w-36 mx-auto mb-1.5"></div>
                    <span class="font-bold text-slate-800 block">Principal / Head of School</span>
                    <span class="text-[10px] text-slate-400">Official Seal</span>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

