<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $school->name ?? 'Multi School ERP' }}</title>
    <!-- Local Compiled CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- FontAwesome 6 Icons (Local primary with CDN fallback) -->
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
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 min-h-screen flex flex-col items-center justify-center p-4">

    <div class="max-w-md w-full space-y-6">

        <!-- Logo & Branding -->
        <div class="text-center space-y-2">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-2xl font-black mx-auto shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }}</h1>
            <p class="text-xs text-slate-400">Multi School ERP v3.8.0 • Institutional Operations Cloud</p>
        </div>

        @if($errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 p-3.5 rounded-xl text-rose-300 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Standard Login Card -->
        <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl space-y-4">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Email or Admission ID</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3 top-3 text-slate-500 text-xs"></i>
                        <input type="text" name="email" value="{{ old('email', 'admin@yugschool.com') }}" required placeholder="Enter email address" class="w-full pl-9 pr-3 py-2.5 text-xs bg-slate-950 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-300">Password</label>
                        <a href="#" class="text-[11px] text-blue-400 hover:underline">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3 top-3 text-slate-500 text-xs"></i>
                        <input type="password" name="password" value="password" required placeholder="Enter your password" class="w-full pl-9 pr-3 py-2.5 text-xs bg-slate-950 border border-slate-700 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer text-slate-400">
                        <input type="checkbox" name="remember" class="rounded bg-slate-800 border-slate-700 text-blue-600 focus:ring-0">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md shadow-blue-500/20 transition">
                    Sign In
                </button>
            </form>
        </div>

        <!-- 1-Click Demo Profiles (Matching the official demo login screen) -->
        <div class="space-y-3">
            <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 text-center">
                Explore Instant Demo Logins
            </div>

            <!-- School Admin (Most Explored) -->
            <a href="{{ route('demo.login', 'schooladmin') }}" class="group block p-3.5 rounded-xl bg-slate-900 hover:bg-blue-900/30 border border-slate-800 hover:border-blue-500/50 transition">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-school text-blue-400 text-sm"></i>
                        <span class="font-bold text-xs text-white">School Admin</span>
                    </div>
                    <span class="bg-blue-500/20 text-blue-300 text-[10px] font-bold px-2 py-0.5 rounded-full border border-blue-400/30">
                        Most explored
                    </span>
                </div>
                <div class="text-[11px] text-slate-400">Runs one school — staff, students, fees and settings</div>
                <div class="text-[10px] text-blue-400 font-semibold mt-1 flex items-center space-x-1 group-hover:translate-x-1 transition">
                    <span>Open panel</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </div>
            </a>

            <!-- Platform Owner -->
            <a href="{{ route('demo.login', 'superadmin') }}" class="group block p-3.5 rounded-xl bg-slate-900 hover:bg-purple-900/30 border border-slate-800 hover:border-purple-500/50 transition">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-shield-halved text-purple-400 text-sm"></i>
                        <span class="font-bold text-xs text-white">Platform Owner (Superadmin)</span>
                    </div>
                    <span class="text-slate-500 text-[10px]"><i class="fa-solid fa-crown text-amber-400"></i></span>
                </div>
                <div class="text-[11px] text-slate-400">All schools, billing, multi-tenancy & servers</div>
                <div class="text-[10px] text-purple-400 font-semibold mt-1 flex items-center space-x-1 group-hover:translate-x-1 transition">
                    <span>Open panel</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </div>
            </a>

            <!-- Teacher -->
            <a href="{{ route('demo.login', 'teacher') }}" class="group block p-3.5 rounded-xl bg-slate-900 hover:bg-emerald-900/30 border border-slate-800 hover:border-emerald-500/50 transition">
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-chalkboard-user text-emerald-400 text-sm"></i>
                        <span class="font-bold text-xs text-white">Teacher Portal (Sunita Sharma)</span>
                    </div>
                </div>
                <div class="text-[11px] text-slate-400">Classroom attendance, grading & syllabus planning</div>
                <div class="text-[10px] text-emerald-400 font-semibold mt-1 flex items-center space-x-1 group-hover:translate-x-1 transition">
                    <span>Open panel</span>
                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                </div>
            </a>
        </div>

    </div>

</body>
</html>

