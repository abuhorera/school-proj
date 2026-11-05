@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-4xl mx-auto">
    @include('hpc.partials.subnav', [
        'pageTitle' => 'Card Appearance',
        'pageSubtitle' => 'Customize visual elements, school emblems, official stamps and colour themes for print & digital cards'
    ])

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
        </div>
    @endif

    <div class="space-y-6">
        <!-- 1. School Emblem / Badge Upload -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                <i class="fa-solid fa-shield text-violet-600"></i>
                <span>School Emblem / Badge</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                Appears on the top left of the official printed progress cards. Recommended format: PNG or SVG with transparent background.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center p-2 bg-slate-50 dark:bg-slate-900/40 shrink-0">
                    @if($setting->emblem_path)
                        <img src="{{ asset('storage/' . $setting->emblem_path) }}" alt="Emblem" class="w-full h-full object-contain">
                    @else
                        <i class="fa-solid fa-school text-3xl text-slate-300"></i>
                    @endif
                </div>

                <div class="space-y-3 flex-1">
                    <form method="POST" action="{{ route('hpc.card-appearance.emblem-store') }}" enctype="multipart/form-data" class="flex items-center gap-2 flex-wrap">
                        @csrf
                        <input type="file" name="emblem" accept="image/*" required class="text-xs text-slate-600 dark:text-slate-300 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                        <button type="submit" class="px-4 py-2 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                            Upload Badge
                        </button>
                    </form>

                    @if($setting->emblem_path)
                        <form method="POST" action="{{ route('hpc.card-appearance.emblem-delete') }}" onsubmit="return confirm('Remove current emblem?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline inline-flex items-center gap-1">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                                <span>Remove current emblem</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Edition Stamp Customizer -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                <i class="fa-solid fa-stamp text-blue-600"></i>
                <span>Edition Label Stamp</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                Printed on the header banner of the card. Leave blank to use each card's default edition name.
            </p>

            <form method="POST" action="{{ route('hpc.card-appearance.edition-label') }}" class="flex items-center gap-3">
                @csrf
                <input type="text" name="edition_label" value="{{ $setting->edition_label }}" placeholder="e.g. PARAKH NEP-2020 Validated" class="flex-1 text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5 focus:ring-violet-500">
                <button type="submit" class="px-5 py-2.5 text-xs font-semibold text-white bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 rounded-xl transition">
                    Save Stamp
                </button>
            </form>
        </div>

        <!-- 3. Overall Theme & Display Toggles -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm">
            <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-2">
                <i class="fa-solid fa-palette text-pink-600"></i>
                <span>Styling & Display Options</span>
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                Configure default parent card theme and visible sections on the official printouts.
            </p>

            <form method="POST" action="{{ route('hpc.card-appearance.update') }}" class="space-y-5">
                @csrf

                <!-- Color Theme -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Default Parent Card Colour Theme</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <label class="p-3 rounded-xl border {{ $setting->theme === 'playful' ? 'border-violet-500 bg-violet-50/50 dark:bg-violet-950/30' : 'border-slate-200 dark:border-slate-700' }} cursor-pointer flex items-center gap-3">
                            <input type="radio" name="theme" value="playful" {{ $setting->theme === 'playful' ? 'checked' : '' }} class="text-violet-600 focus:ring-violet-500">
                            <div>
                                <strong class="text-xs text-slate-900 dark:text-white block">Playful Violet & Pink</strong>
                                <span class="text-[10px] text-slate-400">Warm and friendly for Primary</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border {{ $setting->theme === 'bright' ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/30' : 'border-slate-200 dark:border-slate-700' }} cursor-pointer flex items-center gap-3">
                            <input type="radio" name="theme" value="bright" {{ $setting->theme === 'bright' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <strong class="text-xs text-slate-900 dark:text-white block">Bright Emerald</strong>
                                <span class="text-[10px] text-slate-400">Fresh and vibrant</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border {{ $setting->theme === 'classic' ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/30' : 'border-slate-200 dark:border-slate-700' }} cursor-pointer flex items-center gap-3">
                            <input type="radio" name="theme" value="classic" {{ $setting->theme === 'classic' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <strong class="text-xs text-slate-900 dark:text-white block">Classic Navy</strong>
                                <span class="text-[10px] text-slate-400">Subtle and formal</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Display Checkboxes -->
                <div class="space-y-3 pt-3 border-t border-slate-100 dark:border-slate-700">
                    <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="show_monthly_attendance" value="1" {{ $setting->show_monthly_attendance ? 'checked' : '' }} class="rounded text-violet-600 focus:ring-violet-500">
                        <span>Show full 12-month attendance breakdown matrix</span>
                    </label>

                    <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="show_health_metrics" value="1" {{ $setting->show_health_metrics ? 'checked' : '' }} class="rounded text-violet-600 focus:ring-violet-500">
                        <span>Show student health & anthropometric data (Height, Weight, Blood Group)</span>
                    </label>

                    <label class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="show_radar_chart" value="1" {{ $setting->show_radar_chart ? 'checked' : '' }} class="rounded text-violet-600 focus:ring-violet-500">
                        <span>Include developmental domain competence radar visualization</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 text-xs font-semibold text-white bg-violet-600 hover:bg-violet-700 rounded-xl transition shadow-sm">
                        Save Preferences
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

