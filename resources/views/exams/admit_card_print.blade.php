<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card - {{ $student->full_name }} ({{ $exam ? $exam->name : 'Examination' }})</title>
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

    <!-- Action Bar -->
    <div class="max-w-3xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="javascript:window.history.back()" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-50 transition flex items-center space-x-1.5">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back</span>
        </a>
        <button onclick="window.print()" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center space-x-2 cursor-pointer">
            <i class="fa-solid fa-print"></i>
            <span>Print Hall Ticket</span>
        </button>
    </div>

    <!-- Official Admit Card Document -->
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-slate-300 relative print-border">
        <!-- Header -->
        <div class="border-b-2 border-slate-900 pb-4 text-center">
            <div class="flex items-center justify-center space-x-3 mb-1.5">
                <div class="w-14 h-14 rounded-full bg-purple-900 text-white flex items-center justify-center font-black text-xl shadow-xs">
                    YIS
                </div>
                <div>
                    <h1 class="text-xl font-black tracking-wide text-slate-900 uppercase">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }}</h1>
                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Affiliated to CBSE, New Delhi • Affiliation No: 2130842</p>
                    <p class="text-[10px] text-slate-500">Knowledge Park Sector III, Delhi NCR • Helpline: +91 98111 22334</p>
                </div>
            </div>
            <div class="inline-block mt-1 px-4 py-0.5 bg-purple-900 text-white font-black text-[11px] uppercase tracking-widest rounded-full">
                Examination Admit Card / Hall Ticket • {{ $exam ? $exam->name : 'Summative Assessment' }}
            </div>
        </div>

        <!-- Candidate Particulars -->
        <div class="mt-5 flex items-start justify-between gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs">
            <div class="grid grid-cols-2 gap-x-6 gap-y-1.5 flex-1">
                <div><span class="text-slate-400 font-semibold">Candidate Name:</span> <strong class="text-slate-900 block text-sm">{{ $student->full_name }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Hall Ticket No:</span> <strong class="font-mono text-purple-700 block text-sm">HT-26-{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Admission No:</span> <strong>{{ $student->admission_number }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Roll Number:</span> <strong>{{ $student->roll_number ?? '12' }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Class & Section:</span> <strong>{{ $student->latestEnrollment->schoolClass->name ?? 'Class 10' }} - {{ $student->latestEnrollment->section->name ?? 'A' }}</strong></div>
                <div><span class="text-slate-400 font-semibold">Exam Center:</span> <strong>Main Academic Campus (Hall A)</strong></div>
            </div>

            <!-- Avatar / Photo Box -->
            <div class="w-24 h-28 border-2 border-dashed border-slate-400 rounded-lg flex flex-col items-center justify-center text-slate-400 text-[10px] text-center p-2 bg-white shrink-0">
                <i class="fa-regular fa-image text-2xl mb-1 text-slate-300"></i>
                <span>Candidate Photo</span>
            </div>
        </div>

        <!-- Examination Timetable Schedule -->
        <div class="mt-5">
            <h4 class="text-xs font-black uppercase tracking-wider text-purple-900 mb-2 flex items-center space-x-1.5">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Examination Papers Schedule</span>
            </h4>
            <table class="w-full text-left text-xs border border-slate-300">
                <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-300">
                    <tr>
                        <th class="p-2 border-r border-slate-300">#</th>
                        <th class="p-2 border-r border-slate-300">Subject Name</th>
                        <th class="p-2 border-r border-slate-300">Date</th>
                        <th class="p-2 border-r border-slate-300">Time Window</th>
                        <th class="p-2 border-r border-slate-300">Room No</th>
                        <th class="p-2 text-center">Invigilator Sign</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($schedules as $idx => $s)
                    <tr>
                        <td class="p-2 border-r border-slate-200 text-slate-500">{{ $idx + 1 }}</td>
                        <td class="p-2 border-r border-slate-200 font-bold text-slate-800">{{ $s->subject->name ?? 'Subject' }}</td>
                        <td class="p-2 border-r border-slate-200 text-slate-700 font-medium">{{ \Carbon\Carbon::parse($s->exam_date)->format('d-m-Y') }}</td>
                        <td class="p-2 border-r border-slate-200 text-slate-600">{{ $s->start_time ? substr($s->start_time, 0, 5) : '09:00' }} - {{ $s->end_time ? substr($s->end_time, 0, 5) : '12:00' }}</td>
                        <td class="p-2 border-r border-slate-200 font-medium text-slate-700">{{ $s->room_no ?? 'Hall A' }}</td>
                        <td class="p-2 border-r border-slate-200 text-center"></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-slate-400">No timetable papers assigned.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Rules & Instructions -->
        <div class="mt-5 p-3.5 bg-slate-50 border border-slate-200 rounded-xl text-[10px] text-slate-600 space-y-1">
            <strong class="text-slate-800 uppercase tracking-wider block text-[11px]">Instructions for Candidates:</strong>
            <p>1. Candidates must arrive at the examination room at least 15 minutes prior to the commencement of the exam.</p>
            <p>2. Possession of mobile phones, smartwatches, or programmable electronic devices is strictly prohibited inside the hall.</p>
            <p>3. This admit card must be presented along with the official student ID card for entry into every examination session.</p>
        </div>

        <!-- Signatures Row -->
        <div class="mt-10 pt-3 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
            <div>
                <div class="h-8"></div>
                <div class="border-t border-slate-400 pt-1 font-bold text-slate-800">Student's Signature</div>
            </div>
            <div>
                <div class="h-8"></div>
                <div class="border-t border-slate-400 pt-1 font-bold text-slate-800">Controller of Examinations</div>
            </div>
        </div>
    </div>
</body>
</html>

