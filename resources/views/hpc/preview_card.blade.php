<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holistic Progress Card — {{ $card->student->full_name ?? ($card->student->first_name . ' ' . $card->student->last_name) }}</title>
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
            body { background: white !important; padding: 0 !important; color: black !important; }
            .page-break { page-break-before: always; }
            .print-border { border-color: #000 !important; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased p-4 md:p-8">

    <!-- Top Action Bar (hidden in print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-white rounded-2xl shadow-sm border border-slate-200">
        <a href="{{ route('hpc.cards.show', $card->id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Card Editor</span>
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-slate-800 hover:bg-slate-900 rounded-xl transition shadow-sm">
                <i class="fa-solid fa-print"></i>
                <span>Print Official Card</span>
            </button>
            <a href="{{ route('hpc.cards.colour', $card->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-violet-700 bg-violet-50 hover:bg-violet-100 border border-violet-200 rounded-xl transition">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>Colour Version for Parents &rarr;</span>
            </a>
        </div>
    </div>

    <!-- Official Report Card Paper (A4 Style) -->
    <div class="max-w-4xl mx-auto bg-white border border-slate-300 p-8 sm:p-12 shadow-md rounded-xl print:border-none print:shadow-none print:p-0">
        @php
            $gen = $card->general_info_json ?? [];
            $att = $card->attendance_json ?? [];
            $self = $card->self_assessment_json ?? [];
            $peer = $card->peer_assessment_json ?? [];
            $parent = $card->parent_feedback_json ?? [];
            $scores = $card->domain_scores_json ?? [];
            $narrative = $card->teacher_summary_json ?? [];
        @endphp

        <!-- Official Header -->
        <div class="text-center pb-6 border-b-2 border-slate-800 mb-6 relative">
            @if($setting && $setting->emblem_path)
                <div class="absolute left-0 top-0">
                    <img src="{{ asset('storage/' . $setting->emblem_path) }}" alt="School Emblem" class="w-16 h-16 object-contain">
                </div>
            @endif
            <div class="text-xs font-bold tracking-widest text-slate-500 uppercase mb-1">Government of India &middot; Ministry of Education &middot; NEP 2020 / PARAKH</div>
            <h1 class="text-2xl font-black text-slate-900 tracking-wide uppercase">{{ $gen['school_name'] ?? 'YUG INTERNATIONAL SCHOOL' }}</h1>
            <p class="text-xs text-slate-600 mt-0.5">{{ $gen['school_address'] ?? 'Bhatagaon, Raipur, Chhattisgarh 490021' }}</p>
            <div class="mt-2 text-xs text-slate-600 flex items-center justify-center gap-4 flex-wrap">
                <span><strong>UDISE+ Code:</strong> {{ $gen['udise_code'] ?? '22100405102' }}</span>
                <span>&bull;</span>
                <span><strong>BRC:</strong> {{ $gen['brc'] ?? 'Raipur Urban BRC' }}</span>
                <span>&bull;</span>
                <span><strong>CRC:</strong> {{ $gen['crc'] ?? 'Bhatagaon CRC' }}</span>
            </div>

            <div class="mt-4 inline-block px-4 py-1.5 bg-slate-900 text-white text-sm font-black tracking-widest uppercase rounded">
                HOLISTIC PROGRESS CARD &middot; {{ strtoupper($card->stage) }} STAGE
            </div>
            <div class="text-xs text-slate-600 mt-1">Academic Session {{ date('Y') }}-{{ date('Y')+1 }} &middot; {{ $card->term }}</div>
        </div>

        @if($card->status !== 'published')
            <div class="mb-6 p-2.5 bg-amber-50 border border-amber-300 rounded text-center text-xs text-amber-800 font-semibold uppercase tracking-wider">
                &mdash; DRAFT / PREVIEW &mdash; This card has not been officially published.
            </div>
        @endif

        <!-- Learner Profile Matrix -->
        <div class="border border-slate-300 rounded mb-6 overflow-hidden text-xs">
            <div class="bg-slate-100 px-3 py-1.5 font-bold uppercase tracking-wider border-b border-slate-300 text-slate-700">
                1. Learner Profile & Health Metrics
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-y divide-slate-200">
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">Student Name</span>
                    <strong class="text-slate-900 text-sm">{{ $card->student->full_name ?? ($card->student->first_name . ' ' . $card->student->last_name) }}</strong>
                </div>
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">Admission No.</span>
                    <strong class="text-slate-900">{{ $card->student->admission_no ?? 'ADM-' . $card->student_id }}</strong>
                </div>
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">Class & Roll No.</span>
                    <strong class="text-slate-900">{{ $card->schoolClass->name ?? 'Class' }} &middot; Roll {{ $card->student->roll_no ?? '-' }}</strong>
                </div>
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">Date of Birth / Gender</span>
                    <strong class="text-slate-900">{{ $card->student->dob ?? '2019-05-14' }} / {{ $card->student->gender ?? 'Male' }}</strong>
                </div>
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">APAAR ID</span>
                    <strong class="text-slate-900">{{ $gen['apaar_id'] ?? '9845-1234-8765' }}</strong>
                </div>
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">Blood Group</span>
                    <strong class="text-slate-900">{{ $gen['blood_group'] ?? 'B+' }}</strong>
                </div>
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">Height / Weight</span>
                    <strong class="text-slate-900">{{ $gen['height_cm'] ?? 115 }} cm / {{ $gen['weight_kg'] ?? 19.5 }} kg</strong>
                </div>
                <div class="p-2.5">
                    <span class="text-slate-500 block text-[10px]">Attendance %</span>
                    <strong class="text-emerald-700 font-black">{{ $att['percentage'] ?? 95.8 }}% ({{ $att['total_present'] ?? 230 }}/{{ $att['total_working'] ?? 240 }} days)</strong>
                </div>
            </div>
        </div>

        <!-- Monthly Attendance Matrix -->
        <div class="border border-slate-300 rounded mb-6 overflow-hidden text-xs">
            <div class="bg-slate-100 px-3 py-1.5 font-bold uppercase tracking-wider border-b border-slate-300 text-slate-700">
                2. Monthly Attendance Breakdown
            </div>
            <table class="w-full text-center text-[11px] border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 font-semibold text-slate-600">
                    <tr>
                        <th class="p-1.5 border-r border-slate-200 text-left">Month</th>
                        @foreach(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'] as $m)
                            <th class="p-1.5 border-r border-slate-200">{{ $m }}</th>
                        @endforeach
                        <th class="p-1.5">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <tr>
                        <td class="p-1.5 border-r border-slate-200 text-left font-medium">Attended</td>
                        @foreach(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'] as $m)
                            <td class="p-1.5 border-r border-slate-200">{{ $att[$m]['present'] ?? 20 }}</td>
                        @endforeach
                        <td class="p-1.5 font-bold text-slate-900">{{ $att['total_present'] ?? 230 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 360-Degree Multidimensional Voices -->
        <div class="border border-slate-300 rounded mb-6 overflow-hidden text-xs">
            <div class="bg-slate-100 px-3 py-1.5 font-bold uppercase tracking-wider border-b border-slate-300 text-slate-700">
                3. Multidimensional Voices (Self, Peer, Parent)
            </div>
            <div class="divide-y divide-slate-200">
                <!-- Self -->
                <div class="p-3">
                    <strong class="text-violet-900 uppercase tracking-wider text-[11px] block mb-1">
                        Child's Reflection (Self-Assessment)
                    </strong>
                    <p class="text-slate-700 leading-relaxed italic">
                        "{{ $self['likes'] ?? 'I love drawing and listening to stories in circle time.' }} {{ $self['strengths'] ?? 'I help my friends clean up after activities.' }}"
                    </p>
                </div>
                <!-- Peer -->
                <div class="p-3">
                    <strong class="text-indigo-900 uppercase tracking-wider text-[11px] block mb-1">
                        Peer's Voice ({{ $peer['peer_name'] ?? 'Classmate' }})
                    </strong>
                    <p class="text-slate-700 leading-relaxed italic">
                        "{{ $peer['peer_remarks'] ?? 'Very kind, shares colors and plays nicely.' }}"
                    </p>
                </div>
                <!-- Parent -->
                <div class="p-3">
                    <strong class="text-blue-900 uppercase tracking-wider text-[11px] block mb-1">
                        Parent & Caregiver Observations ({{ $parent['parent_name'] ?? 'Family' }})
                    </strong>
                    <p class="text-slate-700 leading-relaxed italic">
                        "{{ $parent['habits_at_home'] ?? 'Reads bedtime books enthusiastically.' }} {{ $parent['parent_observation'] ?? 'Very caring with siblings.' }}"
                    </p>
                </div>
            </div>
        </div>

        <!-- Developmental Domain Competency Grid -->
        <div class="border border-slate-300 rounded mb-6 overflow-hidden text-xs">
            <div class="bg-slate-100 px-3 py-1.5 font-bold uppercase tracking-wider border-b border-slate-300 text-slate-700 flex items-center justify-between">
                <span>4. Developmental Competency Evaluation</span>
                <span class="text-[10px] font-normal lowercase">Key: <strong>B</strong> = Beginner, <strong>P</strong> = Proficient, <strong>A</strong> = Advanced</span>
            </div>

            <table class="w-full text-left border-collapse text-[11px]">
                <thead class="bg-slate-50 border-b border-slate-200 font-semibold text-slate-600">
                    <tr>
                        <th class="p-2 border-r border-slate-200">Developmental Domain</th>
                        <th class="p-2 border-r border-slate-200">Competency Descriptor</th>
                        <th class="p-2 text-center w-28">Attainment Level</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($scores as $dom => $crits)
                        @foreach($crits as $crit => $lvl)
                            <tr>
                                @if($loop->first)
                                    <td rowspan="{{ count($crits) }}" class="p-2 border-r border-slate-200 font-bold bg-slate-50/50 align-top text-slate-800">
                                        {{ $dom }}
                                    </td>
                                @endif
                                <td class="p-2 border-r border-slate-200 text-slate-700">{{ $crit }}</td>
                                <td class="p-2 text-center font-bold">
                                    @if($lvl === 'A')
                                        <span class="text-emerald-700">Advanced (A)</span>
                                    @elseif($lvl === 'P')
                                        <span class="text-blue-700">Proficient (P)</span>
                                    @elseif($lvl === 'B')
                                        <span class="text-amber-700">Beginner (B)</span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-400 italic">Competency rubrics pending completion.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Teacher's Holistic Narrative & Promotion -->
        <div class="border border-slate-300 rounded mb-8 overflow-hidden text-xs">
            <div class="bg-slate-100 px-3 py-1.5 font-bold uppercase tracking-wider border-b border-slate-300 text-slate-700">
                5. Annual Holistic Narrative & Institution Remarks
            </div>
            <div class="p-4 space-y-3">
                <div>
                    <span class="font-bold text-slate-800 block mb-0.5">Teacher's Comprehensive Summary:</span>
                    <p class="text-slate-700 leading-relaxed">
                        {{ $narrative['holistic_remarks'] ?? 'The learner has shown commendable progress across socio-emotional and cognitive domains, demonstrating curiosity and cooperative spirit.' }}
                    </p>
                </div>
                <div>
                    <span class="font-bold text-slate-800 block mb-0.5">Principal / Head Endorsement:</span>
                    <p class="text-slate-700 leading-relaxed font-semibold">
                        {{ $narrative['principal_remarks'] ?? 'Promoted with distinction to next stage under NEP 2020 guidelines.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Official Signatures -->
        <div class="grid grid-cols-3 gap-6 pt-10 text-center text-xs text-slate-700">
            <div>
                <div class="border-t border-slate-500 pt-1.5 font-bold">
                    {{ $narrative['teacher_name'] ?? 'Class Teacher' }}
                </div>
                <div class="text-[10px] text-slate-500">Class Teacher</div>
            </div>
            <div>
                <div class="border-t border-slate-500 pt-1.5 font-bold">Parent / Guardian</div>
                <div class="text-[10px] text-slate-500">Signature / Thumb Impression</div>
            </div>
            <div>
                <div class="border-t border-slate-500 pt-1.5 font-bold">
                    {{ $setting->principal_name ?? 'Dr. Rajesh Sharma' }}
                </div>
                <div class="text-[10px] text-slate-500">Principal & Official Seal</div>
            </div>
        </div>
    </div>

</body>
</html>

