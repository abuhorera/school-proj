<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $student->full_name }} ({{ $exam ? $exam->name : 'Term Assessment' }})</title>
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
            body { background: white !important; padding: 0 !important; }
            .print-border { border: 2px solid #000 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 md:p-8 font-sans antialiased text-slate-800">

    <!-- Top Action Bar -->
    <div class="max-w-4xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="javascript:window.history.back()" class="px-3.5 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50 transition flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Marksheets</span>
        </a>
        <button onclick="window.print()" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center space-x-2 cursor-pointer">
            <i class="fa-solid fa-print"></i>
            <span>Print Official Report Card</span>
        </button>
    </div>

    <!-- Official Report Card Document Container -->
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-slate-200 relative print-border">
        <!-- Watermark / Decorative background -->
        <div class="absolute inset-0 flex items-center justify-center opacity-3 pointer-events-none">
            <i class="fa-solid fa-graduation-cap text-9xl text-slate-900"></i>
        </div>

        <!-- School Official Header -->
        <div class="border-b-2 border-slate-900 pb-5 text-center">
            <div class="flex items-center justify-center space-x-4 mb-2">
                <div class="w-16 h-16 rounded-full bg-purple-900 text-white flex items-center justify-center font-black text-2xl shadow-sm">
                    YIS
                </div>
                <div>
                    <h1 class="text-2xl font-black tracking-wide text-slate-900 uppercase">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }}</h1>
                    <p class="text-xs font-bold text-slate-600 uppercase tracking-widest mt-0.5">Affiliated to CBSE, New Delhi • Affiliation No: 2130842 • School Code: 71092</p>
                    <p class="text-[11px] text-slate-500">Knowledge Park Sector III, Delhi NCR • Phone: +91 98111 22334 • Email: info@yugschool.com</p>
                </div>
            </div>
            <div class="inline-block mt-2 px-5 py-1 bg-purple-900 text-white font-black text-xs uppercase tracking-widest rounded-full">
                Progress Report Card • {{ $exam ? $exam->name : 'Term Examination' }} (Session 2026-2027)
            </div>
        </div>

        <!-- Candidate Demographics Grid -->
        <div class="mt-6 grid grid-cols-3 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs">
            <div class="space-y-1.5">
                <div><span class="text-slate-400 font-semibold">Student Name:</span> <strong class="text-slate-900">{{ $student->full_name }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Admission No:</span> <strong class="font-mono text-purple-700">{{ $student->admission_number }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Roll Number:</span> <strong>{{ $student->roll_number ?? '12' }}</strong></div>
            </div>
            <div class="space-y-1.5">
                <div><span class="text-slate-400 font-semibold">Class & Section:</span> <strong>{{ $student->latestEnrollment->schoolClass->name ?? 'Class 10' }} - {{ $student->latestEnrollment->section->name ?? 'A' }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Father's Name:</span> <strong>{{ $student->guardian->father_name ?? 'Vikram Patel' }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Mother's Name:</span> <strong>{{ $student->guardian->mother_name ?? 'Anjali Patel' }}</strong></div>
            </div>
            <div class="space-y-1.5">
                <div><span class="text-slate-400 font-semibold">Date of Birth:</span> <strong>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d-m-Y') : '14-08-2011' }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Attendance:</span> <strong class="text-emerald-700">{{ $remark->attendance_percentage ?? 94.5 }}%</strong></div>
                <div><span class="text-slate-400 font-semibold">Overall Result:</span> <strong class="px-2 py-0.5 rounded text-[10px] bg-emerald-100 text-emerald-800">PASSED</strong></div>
            </div>
        </div>

        <!-- Part 1: Scholastic Performance Table -->
        <div class="mt-6">
            <h4 class="text-xs font-black uppercase tracking-wider text-purple-900 mb-2 flex items-center space-x-1.5">
                <i class="fa-solid fa-book-open"></i>
                <span>Part 1: Scholastic Performance (Curriculum Subjects)</span>
            </h4>
            <table class="w-full text-left text-xs border border-slate-300">
                <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-300">
                    <tr>
                        <th class="p-2.5 border-r border-slate-300">#</th>
                        <th class="p-2.5 border-r border-slate-300">Subject Name</th>
                        <th class="p-2.5 border-r border-slate-300 text-center">Max Marks</th>
                        <th class="p-2.5 border-r border-slate-300 text-center">Marks Obtained</th>
                        <th class="p-2.5 border-r border-slate-300 text-center">Grade</th>
                        <th class="p-2.5">Faculty Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($marks as $idx => $m)
                    <tr>
                        <td class="p-2.5 border-r border-slate-200 text-slate-500">{{ $idx + 1 }}</td>
                        <td class="p-2.5 border-r border-slate-200 font-bold text-slate-800">{{ $m->schedule->subject->name ?? 'Subject' }}</td>
                        <td class="p-2.5 border-r border-slate-200 text-center font-medium">{{ $m->schedule->max_marks ?? 100 }}</td>
                        <td class="p-2.5 border-r border-slate-200 text-center font-bold {{ $m->is_absent ? 'text-rose-600' : 'text-slate-900' }}">
                            {{ $m->is_absent ? 'AB' : $m->marks_obtained }}
                        </td>
                        <td class="p-2.5 border-r border-slate-200 text-center font-black text-purple-700">{{ $m->grade ?? 'A1' }}</td>
                        <td class="p-2.5 text-slate-600">{{ $m->remarks ?? 'Good conceptual understanding' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400">No marks recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-purple-50 font-bold border-t-2 border-slate-300">
                    <tr>
                        <td colspan="2" class="p-2.5 text-right uppercase tracking-wider">Grand Total:</td>
                        <td class="p-2.5 text-center">{{ $totalMax }}</td>
                        <td class="p-2.5 text-center text-sm font-black text-purple-900">{{ $totalScored }}</td>
                        <td class="p-2.5 text-center text-sm font-black text-purple-900">{{ $overallGrade }}</td>
                        <td class="p-2.5 text-emerald-700">Percentage: {{ $percentage }}%</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Part 2: Co-Scholastic & Cocurricular Areas -->
        <div class="mt-6">
            <h4 class="text-xs font-black uppercase tracking-wider text-purple-900 mb-2 flex items-center space-x-1.5">
                <i class="fa-solid fa-palette"></i>
                <span>Part 2: Co-Scholastic Domains (3-Point / 5-Point Scale)</span>
            </h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @forelse($cocurricular as $c)
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-center">
                    <span class="text-[10px] text-slate-500 font-semibold block truncate" title="{{ $c->area_name }}">{{ $c->area_name }}</span>
                    <strong class="text-xl font-black text-purple-800 mt-1 block">{{ $c->grade }}</strong>
                </div>
                @empty
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-center">
                    <span class="text-[10px] text-slate-500 font-semibold block">Work Education</span>
                    <strong class="text-xl font-black text-purple-800 mt-1 block">A+</strong>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-center">
                    <span class="text-[10px] text-slate-500 font-semibold block">Art Education</span>
                    <strong class="text-xl font-black text-purple-800 mt-1 block">A</strong>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-center">
                    <span class="text-[10px] text-slate-500 font-semibold block">Health & Physical</span>
                    <strong class="text-xl font-black text-purple-800 mt-1 block">A+</strong>
                </div>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-center">
                    <span class="text-[10px] text-slate-500 font-semibold block">Discipline</span>
                    <strong class="text-xl font-black text-purple-800 mt-1 block">A</strong>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Part 3: Teacher Observations & Remarks -->
        <div class="mt-6 p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-2">
            <div>
                <strong class="text-purple-900 uppercase tracking-wider text-[11px]">Class Teacher's Remark:</strong>
                <p class="text-slate-700 mt-0.5 italic">"{{ $remark->class_teacher_remarks ?? 'Shows exemplary academic enthusiasm and disciplined conduct in all co-curricular activities. Promoted to maintain highest academic standards.' }}"</p>
            </div>
        </div>

        <!-- Official Signatures Row -->
        <div class="mt-14 pt-4 border-t border-slate-200 grid grid-cols-3 gap-6 text-center text-xs">
            <div>
                <div class="h-10"></div>
                <div class="border-t border-slate-400 pt-1 font-bold text-slate-800">Class Teacher</div>
            </div>
            <div>
                <div class="h-10 flex items-center justify-center">
                    <span class="text-[10px] text-slate-400 uppercase tracking-widest font-mono">[ School Official Seal ]</span>
                </div>
                <div class="border-t border-slate-400 pt-1 font-bold text-slate-800">Exam Controller</div>
            </div>
            <div>
                <div class="h-10"></div>
                <div class="border-t border-slate-400 pt-1 font-bold text-slate-800">Principal</div>
            </div>
        </div>
    </div>
</body>
</html>

