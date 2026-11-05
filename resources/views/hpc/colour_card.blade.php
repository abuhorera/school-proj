<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Holistic Progress Card (Family Keepsake) — {{ $card->student->full_name ?? ($card->student->first_name . ' ' . $card->student->last_name) }}</title>
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
        }
    </style>
</head>
<body class="bg-gradient-to-br from-violet-50 via-pink-50 to-amber-50 text-slate-800 font-sans antialiased p-4 md:p-8 min-h-screen">

    @php
        $gen = $card->general_info_json ?? [];
        $att = $card->attendance_json ?? [];
        $self = $card->self_assessment_json ?? [];
        $peer = $card->peer_assessment_json ?? [];
        $parent = $card->parent_feedback_json ?? [];
        $scores = $card->domain_scores_json ?? [];
        $narrative = $card->teacher_summary_json ?? [];
    @endphp

    <!-- Top Toolbar (Hidden in Print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 bg-white/90 backdrop-blur-md rounded-2xl shadow-sm border border-violet-100">
        <a href="{{ route('hpc.cards.show', $card->id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Editor</span>
        </a>

        <!-- Theme selector & Actions -->
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-bold text-slate-500">Theme:</span>
            <a href="?theme=playful" class="px-2.5 py-1 text-xs rounded-lg font-semibold {{ $theme === 'playful' ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Playful</a>
            <a href="?theme=bright" class="px-2.5 py-1 text-xs rounded-lg font-semibold {{ $theme === 'bright' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Bright</a>
            <a href="?theme=classic" class="px-2.5 py-1 text-xs rounded-lg font-semibold {{ $theme === 'classic' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">Classic</a>

            <span class="mx-1 text-slate-300">|</span>

            <a href="{{ route('hpc.cards.preview', $card->id) }}" class="px-3 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                Official Card
            </a>
            <button onclick="window.print()" class="px-4 py-1.5 text-xs font-semibold text-white bg-gradient-to-r from-violet-600 to-pink-600 hover:opacity-90 rounded-xl transition shadow-md">
                <i class="fa-solid fa-print mr-1"></i> Print / Save PDF
            </button>
        </div>
    </div>

    <!-- Illustrated Keepsake Card Canvas -->
    <div class="max-w-4xl mx-auto bg-white rounded-3xl p-8 sm:p-12 shadow-xl border-4 border-violet-200 relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-pink-100 rounded-full blur-2xl opacity-60 pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-violet-100 rounded-full blur-2xl opacity-60 pointer-events-none"></div>

        <!-- School Header -->
        <div class="text-center pb-6 border-b border-violet-100 relative">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-100 text-violet-700 font-bold text-xs uppercase tracking-wider mb-2">
                <i class="fa-solid fa-star text-amber-500"></i>
                <span>NEP 2020 &middot; 360&deg; Holistic Keepsake Card</span>
                <i class="fa-solid fa-star text-amber-500"></i>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $gen['school_name'] ?? 'YUG INTERNATIONAL SCHOOL' }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">{{ $gen['school_address'] ?? 'Bhatagaon, Raipur, Chhattisgarh 490021' }}</p>
            <div class="mt-2 text-xs font-semibold text-violet-600 uppercase tracking-widest">
                Academic Session {{ date('Y') }}-{{ date('Y')+1 }} &middot; {{ $card->stage }} Stage
            </div>
        </div>

        <!-- Child Hero Banner -->
        <div class="my-6 p-6 rounded-2xl bg-gradient-to-r from-violet-500 via-purple-500 to-pink-500 text-white shadow-lg flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-center sm:text-left">
                <div class="w-16 h-16 rounded-2xl bg-white text-violet-600 font-black text-2xl flex items-center justify-center shadow-md shrink-0">
                    {{ substr($card->student->first_name ?? 'S', 0, 1) }}
                </div>
                <div>
                    <h2 class="text-2xl font-black tracking-tight">
                        {{ $card->student->full_name ?? ($card->student->first_name . ' ' . $card->student->last_name) }}
                    </h2>
                    <p class="text-xs opacity-90 mt-0.5">
                        {{ $card->schoolClass->name ?? 'Class' }} &middot; Roll {{ $card->student->roll_no ?? '-' }} &middot; Adm: {{ $card->student->admission_no ?? 'ADM-' . $card->student_id }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <div class="text-center px-4 py-2 rounded-xl bg-white/20 backdrop-blur-sm">
                    <div class="text-xl font-black">{{ $att['percentage'] ?? 95.8 }}%</div>
                    <div class="text-[10px] uppercase font-bold tracking-wider">Attendance</div>
                </div>
                <div class="text-center px-4 py-2 rounded-xl bg-white/20 backdrop-blur-sm">
                    <div class="text-xl font-black">🌟🌟🌟</div>
                    <div class="text-[10px] uppercase font-bold tracking-wider">Holistic Star</div>
                </div>
            </div>
        </div>

        <!-- A Little About Me (Child's Keepsake Voice) -->
        <div class="mb-8 p-6 rounded-2xl bg-amber-50/70 border border-amber-200">
            <h3 class="text-sm font-black text-amber-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-heart text-pink-500"></i>
                <span>A Little About Me (In My Own Words)</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="p-4 rounded-xl bg-white shadow-sm border border-amber-100">
                    <span class="font-bold text-amber-800 block mb-1">💖 What I love doing most at school:</span>
                    <p class="text-slate-700 italic leading-relaxed">
                        "{{ $self['likes'] ?? 'I love painting peacocks with watercolors and listening to animal stories with my friends.' }}"
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-white shadow-sm border border-amber-100">
                    <span class="font-bold text-amber-800 block mb-1">💪 Things I am proud of:</span>
                    <p class="text-slate-700 italic leading-relaxed">
                        "{{ $self['strengths'] ?? 'I always take turns, help clean the toy corner, and share my colors with everyone.' }}"
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-white shadow-sm border border-amber-100">
                    <span class="font-bold text-amber-800 block mb-1">🎯 My goals for next year:</span>
                    <p class="text-slate-700 italic leading-relaxed">
                        "{{ $self['goals'] ?? 'I want to read long fairy tale books by myself and learn how to ride a bicycle.' }}"
                    </p>
                </div>

                <div class="p-4 rounded-xl bg-white shadow-sm border border-amber-100 flex items-center justify-between">
                    <div>
                        <span class="font-bold text-amber-800 block mb-1">🎨 Favorite Classroom Corner:</span>
                        <strong class="text-slate-800 text-sm">{{ $self['favorite_corner'] ?? 'Art & Story Corner' }}</strong>
                    </div>
                    <div class="text-3xl">
                        {{ ($self['self_emoji'] ?? '') == 'Curious & Creative' ? '🌟' : '😊' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Developmental Domain Competency Badges -->
        <div class="mb-8">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <i class="fa-solid fa-award text-amber-500"></i>
                <span>Developmental Abilities & Competency Medals</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($scores as $dom => $crits)
                    <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 hover:shadow-md transition">
                        <div class="font-bold text-xs text-violet-700 uppercase tracking-wide mb-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-sparkles text-amber-500 text-[10px]"></i>
                            <span>{{ $dom }}</span>
                        </div>
                        <div class="space-y-1.5">
                            @foreach($crits as $crit => $lvl)
                                <div class="flex items-center justify-between text-xs p-1.5 rounded-lg bg-white">
                                    <span class="text-slate-700 truncate pr-2 text-[11px]">{{ $crit }}</span>
                                    @if($lvl === 'A')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-800 shrink-0">🌟 Advanced</span>
                                    @elseif($lvl === 'P')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-blue-100 text-blue-800 shrink-0">👍 Proficient</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-100 text-amber-800 shrink-0">🌱 Beginner</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 p-6 text-center text-slate-400 bg-slate-50 rounded-2xl italic text-xs">
                        Domain evaluations are being recorded.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 360-Degree Feedback Cards (Peer, Parent, Teacher) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Peer -->
            <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-200">
                <span class="text-[10px] uppercase font-bold text-indigo-700 tracking-wider block mb-1">
                    <i class="fa-solid fa-user-group mr-1"></i> Peer's Voice
                </span>
                <p class="text-xs text-slate-700 italic leading-relaxed">
                    "{{ $peer['peer_remarks'] ?? 'Very supportive friend who always shares supplies kindly.' }}"
                </p>
                <div class="mt-2 text-[10px] font-bold text-indigo-900">
                    &mdash; {{ $peer['peer_name'] ?? 'Classmate' }}
                </div>
            </div>

            <!-- Parent -->
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200">
                <span class="text-[10px] uppercase font-bold text-emerald-700 tracking-wider block mb-1">
                    <i class="fa-solid fa-house-chimney mr-1"></i> Home Observations
                </span>
                <p class="text-xs text-slate-700 italic leading-relaxed">
                    "{{ $parent['habits_at_home'] ?? 'Extremely curious about nature and books.' }}"
                </p>
                <div class="mt-2 text-[10px] font-bold text-emerald-900">
                    &mdash; {{ $parent['parent_name'] ?? 'Family' }}
                </div>
            </div>

            <!-- Teacher -->
            <div class="p-4 rounded-2xl bg-violet-50 border border-violet-200">
                <span class="text-[10px] uppercase font-bold text-violet-700 tracking-wider block mb-1">
                    <i class="fa-solid fa-chalkboard-user mr-1"></i> Teacher's Note
                </span>
                <p class="text-xs text-slate-700 italic leading-relaxed">
                    "{{ $narrative['holistic_remarks'] ?? 'Remarkable holistic growth, gentle empathy and joy of learning!' }}"
                </p>
                <div class="mt-2 text-[10px] font-bold text-violet-900">
                    &mdash; {{ $narrative['teacher_name'] ?? 'Class Teacher' }}
                </div>
            </div>
        </div>

        <!-- Sign-off & Stamp -->
        <div class="pt-6 border-t-2 border-dashed border-violet-200 flex flex-col sm:flex-row items-center justify-between text-center sm:text-left gap-4 text-xs text-slate-500">
            <div>
                <strong class="text-slate-800 block text-sm">{{ $setting->principal_name ?? 'Dr. Rajesh Sharma' }}</strong>
                <span>Head of School &middot; YUG INTERNATIONAL SCHOOL</span>
            </div>
            <div class="px-4 py-2 rounded-xl bg-violet-50 text-violet-700 font-bold border border-violet-200 uppercase text-[11px] tracking-wider">
                NEP 2020 Holistic Progress Certified
            </div>
        </div>
    </div>

</body>
</html>

