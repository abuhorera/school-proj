@extends('layouts.app')

@section('title', 'School Website CMS')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-sky-600 uppercase tracking-wider mb-1">Public Portal CMS</div>
            <h1 class="text-2xl font-bold text-slate-800">School Website & Public Portal</h1>
            <p class="text-xs text-slate-500 mt-1">Manage public website pages, banner hero sliders, facility showcases, and CBSE disclosures.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="https://demo.multischoolerp.com/school/landing-page" target="_blank" class="bg-sky-600 hover:bg-sky-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span>Live Website Preview</span>
            </a>
        </div>
    </div>

    <!-- Quick Links Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('website.pages') }}" class="bg-white p-5 rounded-xl border border-slate-200 hover:border-sky-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-file-lines"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Website Pages</h3>
            <p class="text-xs text-slate-500 mt-1">7 Live Pages (About, Curriculum, Contact)</p>
        </a>

        <a href="{{ route('website.slides') }}" class="bg-white p-5 rounded-xl border border-slate-200 hover:border-purple-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-images"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Hero Banner Slides</h3>
            <p class="text-xs text-slate-500 mt-1">3 Interactive homepage sliders</p>
        </a>

        <a href="{{ route('website.facilities') }}" class="bg-white p-5 rounded-xl border border-slate-200 hover:border-emerald-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-dumbbell"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Campus Facilities</h3>
            <p class="text-xs text-slate-500 mt-1">Robotics lab, Sports complex, Library</p>
        </a>

        <a href="{{ route('website.disclosure') }}" class="bg-white p-5 rounded-xl border border-slate-200 hover:border-amber-400 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xl mb-3">
                <i class="fa-solid fa-certificate"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">CBSE Disclosure</h3>
            <p class="text-xs text-slate-500 mt-1">Statutory certificates and NOCs</p>
        </a>
    </div>

    <!-- Website Status Card -->
    <div class="bg-gradient-to-r from-sky-900 to-indigo-900 rounded-2xl p-6 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-sky-300 uppercase tracking-widest mb-1">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Portal Online & Responsive</span>
            </div>
            <h2 class="text-xl font-bold">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }} Website</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-xl leading-relaxed">
                Integrated with online admission inquiries, parent inquiries, fee payment gateway redirects, and mandatory government disclosure vault.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 shrink-0">
            <a href="{{ route('website.pages') }}" class="bg-white text-slate-900 hover:bg-slate-100 font-bold text-xs px-4 py-2.5 rounded-xl transition text-center shadow">
                Manage CMS Pages
            </a>
            <a href="{{ route('website.disclosure') }}" class="bg-sky-700/80 hover:bg-sky-600 text-white font-bold text-xs px-4 py-2.5 rounded-xl border border-sky-500/40 transition text-center">
                CBSE Documents
            </a>
        </div>
    </div>
</div>
@endsection

