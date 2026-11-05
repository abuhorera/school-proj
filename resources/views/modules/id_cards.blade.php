@extends('layouts.app')

@section('title', 'ID Cards — Student & Staff Identity Cards')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('id-cards.students') }}" class="text-slate-700 hover:text-blue-600 font-medium">ID Cards</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">{{ ucfirst($tab) }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">ID Cards</h1>
                    <p class="text-xs text-slate-500">Print student and staff identity cards &mdash; pick people, pick a design, print the sheet.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="openModal('newTemplateModal')" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>New Card Design</span>
            </button>
        </div>
    </div>

    <!-- Alert / Flash Notification Banners -->
    @if(session('success'))
        <div class="flex items-center justify-between p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-medium">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 cursor-pointer">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center justify-between p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-sm"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- Sub Navigation Pill Tabs -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center gap-1.5 bg-slate-100/80 p-1 rounded-lg text-xs font-semibold">
            <a href="{{ route('id-cards.students') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'students' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Student ID Cards</span>
                <span class="ml-1 bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($students) }}</span>
            </a>
            <a href="{{ route('id-cards.staff') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'staff' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Staff ID Cards</span>
                <span class="ml-1 bg-emerald-100 text-emerald-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($staff) }}</span>
            </a>
            <a href="{{ route('id-cards.designs') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'designs' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-palette"></i>
                <span>Card Designs</span>
                <span class="ml-1 bg-purple-100 text-purple-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($templates) }}</span>
            </a>
        </div>
        <div class="flex items-center space-x-2 text-xs">
            <a href="{{ route('modules.certificates') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
                <i class="fa-solid fa-certificate"></i>
                <span>Certificates &rarr;</span>
            </a>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TAB 1: STUDENT ID CARDS --}}
    {{-- ========================================================================= --}}
    @if($tab === 'students')
        <!-- Choose a Class Filter Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Choose a Class</h2>
            <form id="filter-form" method="GET" action="{{ route('id-cards.students') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Class</label>
                    <select name="class_id" id="class-filter" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                        <option value="">-- All Classes --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Section</label>
                    <select name="section_id" id="section-filter" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                        <option value="">-- All Sections --</option>
                        @foreach($activeSections as $sec)
                            <option value="{{ $sec->id }}" {{ $sectionId == $sec->id ? 'selected' : '' }}>{{ $sec->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Search Student</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name, roll or admission no..." class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                </div>
                <div class="flex items-center space-x-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg transition cursor-pointer">
                        <i class="fa-solid fa-users"></i>
                        <span>Show Students</span>
                    </button>
                    <a href="{{ route('id-cards.students') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- 2-Column Generation & Print Layout -->
        <form id="generate-form" method="POST" action="{{ route('id-cards.students.generate') }}" target="_blank" class="space-y-6">
            @csrf
            <input type="hidden" name="output_type" id="output_type" value="preview">
            <input type="hidden" name="auto_images" id="auto_images" value="0">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left 8 Columns: Form Settings & Students Table -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-3">
                            <div class="flex items-center space-x-2.5">
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Print Cards</span>
                                <span id="selectedCountBadge" class="bg-blue-100 text-blue-700 text-xs px-2.5 py-0.5 rounded-full font-bold">
                                    {{ count($students) }} selected
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="togglePrintLayout('student-print-layout-panel', 'studentLayoutToggleArrow')" class="text-xs font-semibold text-blue-600 hover:text-blue-700 inline-flex items-center space-x-1 cursor-pointer">
                                    <i class="fa-solid fa-sliders"></i>
                                    <span>Print Layout &amp; Card Setup</span>
                                    <i id="studentLayoutToggleArrow" class="fa-solid fa-chevron-down text-[10px] ml-1 transition"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Card Design <span class="text-rose-500">*</span></label>
                                <select name="template_id" id="studentTemplateSelect" onchange="updateStudentPreviewDesign(this)" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                                    @foreach($studentTemplates as $tpl)
                                        <option value="{{ $tpl->id }}" 
                                                data-bg="{{ $tpl->bg_color }}" 
                                                data-layout="{{ $tpl->layout }}" 
                                                data-title="{{ $tpl->title }}"
                                                data-w="{{ $tpl->width_mm ?? 54 }}"
                                                data-h="{{ $tpl->height_mm ?? 85.6 }}"
                                                {{ $tpl->id == ($selectedTemplate->id ?? 1) ? 'selected' : '' }}>
                                            {{ $tpl->title }} ({{ $tpl->layout }}) {{ $tpl->is_default ? '★ Default' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Print As</label>
                                <select name="generation_mode" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                                    <option value="id_card_grid">Sheet of cards (many per page)</option>
                                    <option value="single">One card per page (Card Printer Direct)</option>
                                </select>
                            </div>

                            <div class="flex items-center sm:pt-5">
                                <label class="flex items-center space-x-2 cursor-pointer text-xs text-slate-600 font-medium">
                                    <input type="checkbox" name="archive_document" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>Archive a digital copy in student records</span>
                                </label>
                            </div>
                        </div>

                        <!-- Collapsible Advanced Print Layout Setup -->
                        <div id="student-print-layout-panel" class="p-4 bg-slate-50/75 rounded-xl border border-slate-200/80 space-y-3">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Print Layout &mdash; Sheet &amp; Card Setup</div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 text-xs">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Card Size</label>
                                    <select name="card_size" id="studentCardSizeSelect" onchange="toggleCustomDimensions('student', this.value)" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                        <option value="template">Use template size</option>
                                        <option value="cr80_portrait" selected>CR80 Portrait &mdash; 54 &times; 85.6 mm</option>
                                        <option value="cr80_landscape">CR80 Landscape &mdash; 85.6 &times; 54 mm</option>
                                        <option value="a7_portrait">A7 Badge &mdash; 74 &times; 105 mm</option>
                                        <option value="a6_portrait">A6 Portrait &mdash; 105 &times; 148 mm</option>
                                        <option value="a6_landscape">A6 Landscape &mdash; 148 &times; 105 mm</option>
                                        <option value="custom">Custom…</option>
                                    </select>
                                </div>
                                <div id="studentCustomW" class="hidden">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">W (mm)</label>
                                    <input type="number" step="0.1" name="custom_card_width_mm" value="54" min="20" max="500" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div id="studentCustomH" class="hidden">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">H (mm)</label>
                                    <input type="number" step="0.1" name="custom_card_height_mm" value="85.6" min="20" max="500" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Paper</label>
                                    <select name="paper" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                        <option value="a4" selected>A4 (210 &times; 297 mm)</option>
                                        <option value="a3">A3 (297 &times; 420 mm)</option>
                                        <option value="letter">Letter (8.5 &times; 11 in)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Orientation</label>
                                    <select name="paper_orientation" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                        <option value="portrait" selected>Portrait</option>
                                        <option value="landscape">Landscape</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Margin (mm)</label>
                                    <input type="number" name="margin_mm" value="10" min="0" max="50" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Gap (mm)</label>
                                    <input type="number" name="gap_mm" value="3" min="0" max="30" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Back Side</label>
                                    <select name="back_mode" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                        <option value="none">None</option>
                                        <option value="duplex">Duplex (flip short edge)</option>
                                        <option value="simplex">Separate simplex pages</option>
                                    </select>
                                </div>
                                <div class="col-span-2 flex items-center space-x-3 pt-2">
                                    <label class="flex items-center space-x-1.5 cursor-pointer text-[11px] text-slate-700">
                                        <input type="checkbox" name="cut_marks" value="1" checked class="rounded border-slate-300 text-blue-600">
                                        <span>Cut marks</span>
                                    </label>
                                    <label class="flex items-center space-x-1.5 cursor-pointer text-[11px] text-slate-700">
                                        <input type="checkbox" name="align_center" value="1" checked class="rounded border-slate-300 text-blue-600">
                                        <span>Align center</span>
                                    </label>
                                    <label class="flex items-center space-x-1.5 cursor-pointer text-[11px] text-slate-700">
                                        <input type="checkbox" name="cut_stack" value="1" checked class="rounded border-slate-300 text-blue-600">
                                        <span>Cut &amp; stack</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Client-Side Error Alert Box -->
                        <div id="student-idc-error" class="hidden p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold flex items-center space-x-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                            <span id="student-idc-error-msg">Please tick at least one student.</span>
                        </div>

                        <!-- Action Submit Buttons -->
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <div class="flex items-center space-x-2 text-xs text-slate-500">
                                <i class="fa-solid fa-circle-info text-blue-500"></i>
                                <span>Select the students below to include on the print sheet.</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" onclick="return submitGenerateForm('generate-form', 'preview', 0)" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Print Preview</span>
                                </button>
                                <button type="submit" onclick="return submitGenerateForm('generate-form', 'pdf', 0)" class="inline-flex items-center space-x-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm transition cursor-pointer">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span>Download PDF</span>
                                </button>
                                <button type="submit" onclick="return submitGenerateForm('generate-form', 'images', 1)" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm shadow-emerald-600/20 transition cursor-pointer" title="Generates individual 300DPI PNG identity cards inside a downloadable ZIP package.">
                                    <i class="fa-solid fa-file-zipper"></i>
                                    <span>Download as Images (ZIP)</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Students Selection Table -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Students List</h3>
                                <p class="text-xs text-slate-500">Showing {{ count($students) }} students matching criteria</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label class="text-xs font-semibold text-slate-600 flex items-center space-x-1.5 cursor-pointer">
                                    <input type="checkbox" id="check-all-students" onchange="toggleAllStudents(this)" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>Select All</span>
                                </label>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                        <th class="py-3 px-4 w-10 text-center">Select</th>
                                        <th class="py-3 px-4">Photo</th>
                                        <th class="py-3 px-4">Student Name</th>
                                        <th class="py-3 px-4">Admission No</th>
                                        <th class="py-3 px-4">Roll No</th>
                                        <th class="py-3 px-4">Class &amp; Section</th>
                                        <th class="py-3 px-4">Phone</th>
                                        <th class="py-3 px-4 text-right">Card Preview</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    @forelse($students as $std)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="py-3 px-4 text-center">
                                                <input type="checkbox" name="student_ids[]" value="{{ $std->id }}" checked onchange="updateSelectedCount()" class="student-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="w-8 h-8 rounded-full bg-amber-50 border border-amber-200 overflow-hidden flex items-center justify-center font-bold text-amber-700">
                                                    @if(!empty($std->photo))
                                                        <img src="{{ asset($std->photo) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($std->first_name ?? 'S', 0, 1)) }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 font-bold text-slate-900 whitespace-nowrap">
                                                {{ $std->first_name }} {{ $std->last_name }}
                                                @if(empty($std->photo))
                                                    <span class="ml-1 text-[9px] px-1.5 py-0.2 bg-amber-100 text-amber-800 rounded font-normal">no photo</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap font-mono text-slate-600">
                                                <code>{{ $std->admission_no ?? 'ADM-2026' }}</code>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap font-semibold text-slate-800">
                                                {{ $std->roll_no ?? '-' }}
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap font-semibold text-blue-600">
                                                {{ $std->class_name ?? ($std->schoolClass->name ?? 'Class') }} &bull; {{ $std->section_name ?? 'A' }}
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap font-mono text-slate-600">
                                                {{ $std->phone ?? '9876543210' }}
                                            </td>
                                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                                <button type="button" 
                                                    data-type="student"
                                                    data-name="{{ $std->first_name }} {{ $std->last_name }}"
                                                    data-subinfo="{{ $std->class_name ?? ($std->schoolClass->name ?? 'Class') }} &bull; {{ $std->section_name ?? 'A' }}"
                                                    data-adm="{{ $std->admission_no ?? 'ADM-2026' }}"
                                                    data-roll="{{ $std->roll_no ?? '-' }}"
                                                    data-blood="{{ $std->blood_group ?? 'O+' }}"
                                                    data-phone="{{ $std->phone ?? '9876543210' }}"
                                                    data-photo="{{ !empty($std->photo) ? asset($std->photo) : '' }}"
                                                    onclick="previewCardFromBtn(this)" 
                                                    class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition cursor-pointer">
                                                    <i class="fa-solid fa-id-card text-[10px]"></i>
                                                    <span>Card</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="py-12 text-center text-slate-400">
                                                <i class="fa-solid fa-users text-3xl mb-2 text-slate-300 block"></i>
                                                No students found matching your criteria. Pick a class from the top filter.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right 4 Columns: Sticky Live Interactive Card Preview -->
                <div class="lg:col-span-4 sticky top-6 space-y-3">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center space-x-1.5">
                                <i class="fa-regular fa-eye text-blue-600"></i>
                                <span>Live Card Preview</span>
                            </span>
                            <span id="studentLiveTplName" class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-full">
                                {{ $selectedTemplate->title ?? 'Classic' }}
                            </span>
                        </div>

                        <!-- Rendered Interactive Mockup -->
                        <div class="p-4 bg-slate-50/80 rounded-xl border border-dashed border-slate-300 flex items-center justify-center min-h-[320px]">
                            <div id="studentPreviewCardBox" class="w-48 h-72 rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transition-all duration-300">
                                <div id="studentPreviewTopBand" class="p-2.5 text-white text-center" style="background: {{ $selectedTemplate->bg_color ?? '#1e40af' }};">
                                    <div class="text-[8px] font-black uppercase tracking-wider">{{ $school->name ?? 'ACADEMY' }}</div>
                                    <div class="text-[6.5px] text-white/80 font-medium">STUDENT IDENTITY CARD</div>
                                </div>
                                <div class="p-3 flex-1 flex flex-col items-center justify-between text-center space-y-1">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-slate-400 text-xl font-bold">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="text-[11px] font-bold text-slate-900 leading-tight">Sample Student</div>
                                        <div class="text-[8px] font-semibold text-blue-600">Class 1 &bull; Sec A</div>
                                    </div>
                                    <div class="w-full text-[7.5px] text-left border-t border-slate-100 pt-1 space-y-0.5 text-slate-500 font-medium">
                                        <div class="flex justify-between"><span>Adm No:</span><span class="font-bold text-slate-800">YISADM-001</span></div>
                                        <div class="flex justify-between"><span>Roll No:</span><span class="font-bold text-slate-800">101</span></div>
                                        <div class="flex justify-between"><span>Blood Group:</span><span class="font-bold text-rose-600">O+</span></div>
                                        <div class="flex justify-between"><span>Emergency:</span><span class="font-mono text-slate-800">9876543210</span></div>
                                    </div>
                                </div>
                                <div class="px-2 py-1 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-[7px] text-slate-400 font-mono">
                                    <div class="flex items-center space-x-1">
                                        <i class="fa-solid fa-barcode text-xs text-slate-700"></i>
                                        <span>YIS-001</span>
                                    </div>
                                    <div class="font-serif italic text-[6px]">Principal Sign</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-500 text-center">
                            Standard CR-80 PVC (54 &times; 85.6 mm) &bull; 300 DPI Imposition
                        </div>
                    </div>
                </div>

            </div>
        </form>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 2: STAFF ID CARDS --}}
    {{-- ========================================================================= --}}
    @if($tab === 'staff')
        <!-- Select Criteria Filter Card -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <h2 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Select Criteria</h2>
            <form id="filter-form-staff" method="GET" action="{{ route('id-cards.staff') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Department</label>
                    <select name="department" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                        <option value="">-- All Departments --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ $departmentId == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Designation</label>
                    <select name="designation" onchange="this.form.submit()" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                        <option value="">-- All Designations --</option>
                        @foreach($designations as $desig)
                            <option value="{{ $desig }}" {{ $designationId == $desig ? 'selected' : '' }}>{{ $desig }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Search Staff</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name, employee code, phone..." class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white">
                </div>
                <div class="flex items-center space-x-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs py-2 px-4 rounded-lg transition cursor-pointer">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Search Staff</span>
                    </button>
                    <a href="{{ route('id-cards.staff') }}" class="px-3 py-2 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Generation & Print Layout Panel (Staff) -->
        <form id="generate-form-staff" method="POST" action="{{ route('id-cards.staff.generate') }}" target="_blank" class="space-y-6">
            @csrf
            <input type="hidden" name="output_type" id="output_type_staff" value="preview">
            <input type="hidden" name="auto_images" id="auto_images_staff" value="0">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left 8 Columns -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-3">
                            <div class="flex items-center space-x-2.5">
                                <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">Print Cards</span>
                                <span id="selectedStaffBadge" class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-0.5 rounded-full font-bold">
                                    {{ count($staff) }} selected
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="togglePrintLayout('staff-print-layout-panel', 'staffLayoutToggleArrow')" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 inline-flex items-center space-x-1 cursor-pointer">
                                    <i class="fa-solid fa-sliders"></i>
                                    <span>Print Layout &amp; Card Setup</span>
                                    <i id="staffLayoutToggleArrow" class="fa-solid fa-chevron-down text-[10px] ml-1 transition"></i>
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Card Design <span class="text-rose-500">*</span></label>
                                <select name="template_id" id="staffTemplateSelect" onchange="updateStaffPreviewDesign(this)" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                                    @foreach($staffTemplates as $tpl)
                                        <option value="{{ $tpl->id }}" 
                                                data-bg="{{ $tpl->bg_color }}" 
                                                data-layout="{{ $tpl->layout }}" 
                                                data-title="{{ $tpl->title }}"
                                                data-w="{{ $tpl->width_mm ?? 54 }}"
                                                data-h="{{ $tpl->height_mm ?? 85.6 }}"
                                                {{ $tpl->id == ($selectedTemplate->id ?? 6) ? 'selected' : '' }}>
                                            {{ $tpl->title }} ({{ $tpl->layout }}) {{ $tpl->is_default ? '★ Default' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Print As</label>
                                <select name="generation_mode" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-2 px-3 bg-white font-medium">
                                    <option value="id_card_grid">Sheet of cards (many per page)</option>
                                    <option value="single">One card per page (Card Printer Direct)</option>
                                </select>
                            </div>

                            <div class="flex items-center sm:pt-5">
                                <label class="flex items-center space-x-2 cursor-pointer text-xs text-slate-600 font-medium">
                                    <input type="checkbox" name="archive_document" value="1" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>Archive a digital copy in HR staff file</span>
                                </label>
                            </div>
                        </div>

                        <!-- Collapsible Layout Setup (Staff) -->
                        <div id="staff-print-layout-panel" class="p-4 bg-slate-50/75 rounded-xl border border-slate-200/80 space-y-3">
                            <div class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Print Layout &mdash; Sheet &amp; Card Setup</div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3 text-xs">
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Card Size</label>
                                    <select name="card_size" id="staffCardSizeSelect" onchange="toggleCustomDimensions('staff', this.value)" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                        <option value="template">Use template size</option>
                                        <option value="cr80_portrait" selected>CR80 Portrait &mdash; 54 &times; 85.6 mm</option>
                                        <option value="cr80_landscape">CR80 Landscape &mdash; 85.6 &times; 54 mm</option>
                                        <option value="a7_portrait">A7 Badge &mdash; 74 &times; 105 mm</option>
                                        <option value="a6_portrait">A6 Portrait &mdash; 105 &times; 148 mm</option>
                                        <option value="a6_landscape">A6 Landscape &mdash; 148 &times; 105 mm</option>
                                        <option value="custom">Custom…</option>
                                    </select>
                                </div>
                                <div id="staffCustomW" class="hidden">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">W (mm)</label>
                                    <input type="number" step="0.1" name="custom_card_width_mm" value="54" min="20" max="500" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div id="staffCustomH" class="hidden">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">H (mm)</label>
                                    <input type="number" step="0.1" name="custom_card_height_mm" value="85.6" min="20" max="500" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Paper</label>
                                    <select name="paper" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                        <option value="a4" selected>A4 (210 &times; 297 mm)</option>
                                        <option value="a3">A3 (297 &times; 420 mm)</option>
                                        <option value="letter">Letter (8.5 &times; 11 in)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Orientation</label>
                                    <select name="paper_orientation" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                        <option value="portrait" selected>Portrait</option>
                                        <option value="landscape">Landscape</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Margin (mm)</label>
                                    <input type="number" name="margin_mm" value="10" min="0" max="50" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Gap (mm)</label>
                                    <input type="number" name="gap_mm" value="3" min="0" max="30" class="w-full text-xs rounded-lg border-slate-200 bg-white py-1.5 px-2.5">
                                </div>
                                <div class="col-span-2 flex items-center space-x-3 pt-2">
                                    <label class="flex items-center space-x-1.5 cursor-pointer text-[11px] text-slate-700">
                                        <input type="checkbox" name="cut_marks" value="1" checked class="rounded border-slate-300 text-blue-600">
                                        <span>Cut marks</span>
                                    </label>
                                    <label class="flex items-center space-x-1.5 cursor-pointer text-[11px] text-slate-700">
                                        <input type="checkbox" name="align_center" value="1" checked class="rounded border-slate-300 text-blue-600">
                                        <span>Align center</span>
                                    </label>
                                    <label class="flex items-center space-x-1.5 cursor-pointer text-[11px] text-slate-700">
                                        <input type="checkbox" name="cut_stack" value="1" checked class="rounded border-slate-300 text-blue-600">
                                        <span>Cut &amp; stack</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Client-Side Error Box (Staff) -->
                        <div id="staff-idc-error" class="hidden p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-xs font-semibold flex items-center space-x-2">
                            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
                            <span id="staff-idc-error-msg">Please tick at least one staff member.</span>
                        </div>

                        <!-- Action Submit Buttons (Staff) -->
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                            <div class="flex items-center space-x-2 text-xs text-slate-500">
                                <i class="fa-solid fa-circle-info text-emerald-600"></i>
                                <span>Select faculty &amp; staff members below to include on the print sheet.</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="submit" onclick="return submitGenerateForm('generate-form-staff', 'preview', 0)" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Print Preview</span>
                                </button>
                                <button type="submit" onclick="return submitGenerateForm('generate-form-staff', 'pdf', 0)" class="inline-flex items-center space-x-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm transition cursor-pointer">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span>Download PDF</span>
                                </button>
                                <button type="submit" onclick="return submitGenerateForm('generate-form-staff', 'images', 1)" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm shadow-emerald-600/20 transition cursor-pointer" title="Generates individual 300DPI PNG staff badges inside a downloadable ZIP package.">
                                    <i class="fa-solid fa-file-zipper"></i>
                                    <span>Download as Images (ZIP)</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Selection Table -->
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800">Staff Members List</h3>
                                <p class="text-xs text-slate-500">Showing {{ count($staff) }} faculty and staff members</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <label class="text-xs font-semibold text-slate-600 flex items-center space-x-1.5 cursor-pointer">
                                    <input type="checkbox" id="check-all-staff" onchange="toggleAllStaff(this)" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>Select All</span>
                                </label>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                                        <th class="py-3 px-4 w-10 text-center">Select</th>
                                        <th class="py-3 px-4">Photo</th>
                                        <th class="py-3 px-4">Staff Name</th>
                                        <th class="py-3 px-4">Staff ID</th>
                                        <th class="py-3 px-4">Department</th>
                                        <th class="py-3 px-4">Designation</th>
                                        <th class="py-3 px-4">Phone</th>
                                        <th class="py-3 px-4 text-right">Card Preview</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                                    @forelse($staff as $stf)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="py-3 px-4 text-center">
                                                <input type="checkbox" name="staff_ids[]" value="{{ $stf->id }}" checked onchange="updateSelectedStaffCount()" class="staff-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center font-bold text-slate-600">
                                                    @if(!empty($stf->photo))
                                                        <img src="{{ asset($stf->photo) }}" class="w-full h-full object-cover">
                                                    @else
                                                        {{ strtoupper(substr($stf->first_name ?? 'S', 0, 1)) }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 font-bold text-slate-900 whitespace-nowrap">
                                                {{ $stf->first_name }} {{ $stf->last_name }}
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap font-mono text-slate-600">
                                                <code>{{ $stf->staff_id ?? ($stf->employee_id ?? 'STF-' . $stf->id) }}</code>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap font-semibold text-slate-800">
                                                {{ $stf->department ?? 'Academics' }}
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    {{ $stf->designation ?? 'Faculty' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 whitespace-nowrap font-mono text-slate-600">
                                                {{ $stf->phone ?? '9876543210' }}
                                            </td>
                                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                                <button type="button" 
                                                    data-type="staff"
                                                    data-name="{{ $stf->first_name }} {{ $stf->last_name }}"
                                                    data-subinfo="{{ $stf->designation ?? 'Faculty' }} &bull; {{ $stf->department ?? 'Academics' }}"
                                                    data-adm="{{ $stf->employee_id ?? 'STF-' . $stf->id }}"
                                                    data-roll="{{ $stf->department ?? 'General' }}"
                                                    data-blood="{{ $stf->blood_group ?? 'B+' }}"
                                                    data-phone="{{ $stf->phone ?? '9876543210' }}"
                                                    data-photo="{{ !empty($stf->photo) ? asset($stf->photo) : '' }}"
                                                    onclick="previewCardFromBtn(this)" 
                                                    class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition cursor-pointer">
                                                    <i class="fa-solid fa-id-card text-[10px]"></i>
                                                    <span>Card</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="py-12 text-center text-slate-400">
                                                <i class="fa-solid fa-chalkboard-user text-3xl mb-2 text-slate-300 block"></i>
                                                No staff members found matching criteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right 4 Columns: Sticky Live Card Preview (Staff) -->
                <div class="lg:col-span-4 sticky top-6 space-y-3">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center space-x-1.5">
                                <i class="fa-regular fa-eye text-emerald-600"></i>
                                <span>Live Card Preview</span>
                            </span>
                            <span id="staffLiveTplName" class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                {{ $selectedTemplate->title ?? 'Staff ID' }}
                            </span>
                        </div>

                        <!-- Rendered Interactive Mockup (Staff) -->
                        <div class="p-4 bg-slate-50/80 rounded-xl border border-dashed border-slate-300 flex items-center justify-center min-h-[320px]">
                            <div id="staffPreviewCardBox" class="w-48 h-72 rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transition-all duration-300">
                                <div id="staffPreviewTopBand" class="p-2.5 text-white text-center" style="background: {{ $selectedTemplate->bg_color ?? '#0f172a' }};">
                                    <div class="text-[8px] font-black uppercase tracking-wider">{{ $school->name ?? 'ACADEMY' }}</div>
                                    <div class="text-[6.5px] text-white/80 font-medium">FACULTY &amp; STAFF ID</div>
                                </div>
                                <div class="p-3 flex-1 flex flex-col items-center justify-between text-center space-y-1">
                                    <div class="w-14 h-14 rounded-full bg-slate-100 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center text-slate-400 text-xl font-bold">
                                        <i class="fa-solid fa-user-tie"></i>
                                    </div>
                                    <div>
                                        <div class="text-[11px] font-bold text-slate-900 leading-tight">Faculty Member</div>
                                        <div class="text-[8px] font-semibold text-emerald-700">Senior Teacher &bull; Mathematics</div>
                                    </div>
                                    <div class="w-full text-[7.5px] text-left border-t border-slate-100 pt-1 space-y-0.5 text-slate-500 font-medium">
                                        <div class="flex justify-between"><span>Staff ID:</span><span class="font-bold text-slate-800">STF-001</span></div>
                                        <div class="flex justify-between"><span>Dept:</span><span class="font-bold text-slate-800">Mathematics</span></div>
                                        <div class="flex justify-between"><span>Blood Group:</span><span class="font-bold text-rose-600">B+</span></div>
                                        <div class="flex justify-between"><span>Phone:</span><span class="font-mono text-slate-800">9876543210</span></div>
                                    </div>
                                </div>
                                <div class="px-2 py-1 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-[7px] text-slate-400 font-mono">
                                    <div class="flex items-center space-x-1">
                                        <i class="fa-solid fa-qrcode text-xs text-slate-700"></i>
                                        <span>STF-001</span>
                                    </div>
                                    <div class="font-serif italic text-[6px]">Director Sign</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-500 text-center">
                            Standard CR-80 PVC (54 &times; 85.6 mm) &bull; 300 DPI Imposition
                        </div>
                    </div>
                </div>

            </div>
        </form>
    @endif

    {{-- ========================================================================= --}}
    {{-- TAB 3: CARD DESIGNS GALLERY --}}
    {{-- ========================================================================= --}}
    @if($tab === 'designs')
        <div class="space-y-8">
            <!-- Section 1: Student Card Designs -->
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-user-graduate text-blue-600"></i>
                        <h2 class="text-sm font-bold text-slate-900">Student Card Designs</h2>
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ count($studentTemplates) }}</span>
                    </div>
                    <button type="button" onclick="openModal('newTemplateModal')" class="text-xs font-semibold text-blue-600 hover:text-blue-700 flex items-center space-x-1 cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Add Student Design</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($studentTemplates as $tpl)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:border-slate-300 transition group">
                            <!-- Live Miniature Card Mockup Preview -->
                            <div class="p-5 bg-slate-50 flex items-center justify-center border-b border-slate-100 min-h-[170px]">
                                <div class="w-32 {{ $tpl->layout === 'Horizontal' ? 'h-20' : 'h-36' }} rounded-xl shadow-md border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transform group-hover:scale-105 transition duration-200">
                                    <div class="p-1 text-white text-[7px] font-black uppercase tracking-wider" style="background: {{ $tpl->bg_color }};">
                                        {{ $school->name ?? 'ACADEMY' }}
                                    </div>
                                    <div class="flex-1 flex flex-col items-center justify-center p-1 space-y-0.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-400">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <div class="text-[8px] font-bold text-slate-900">Sample Student</div>
                                        <div class="text-[6.5px] text-blue-600 font-semibold">Class 1 &bull; Roll 101</div>
                                    </div>
                                    <div class="p-0.5 bg-slate-100 border-t border-slate-200 text-[6px] font-mono text-slate-400">
                                        ||| |||| |||
                                    </div>
                                </div>
                            </div>

                            <!-- Card Details & Actions -->
                            <div class="p-4 space-y-3">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-xs font-bold text-slate-900 truncate" title="{{ $tpl->title }}">{{ $tpl->title }}</h3>
                                        @if($tpl->is_default)
                                            <span class="text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.2 rounded-full">Default</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-500 flex items-center space-x-2 mt-1">
                                        <span class="bg-blue-50 text-blue-700 px-1.5 py-0.2 rounded text-[10px] font-semibold">{{ $tpl->card_type }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $tpl->width_mm ?? 54 }} &times; {{ $tpl->height_mm ?? 85.6 }} mm</span>
                                        <span>&bull;</span>
                                        <span>{{ $tpl->layout }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                                    <a href="{{ route('id-cards.students', ['template_id' => $tpl->id]) }}" class="font-semibold text-blue-600 hover:text-blue-700 flex items-center space-x-1">
                                        <span>Use for Students</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                    <div class="flex items-center space-x-1">
                                        @if(!$tpl->is_default)
                                            <form method="POST" action="{{ route('id-cards.templates.default', $tpl->id) }}">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 text-[11px] font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition cursor-pointer" title="Set as Default">
                                                    Set Default
                                                </button>
                                            </form>
                                        @endif
                                        @if($tpl->id > 5)
                                            <form method="POST" action="{{ route('id-cards.templates.delete', $tpl->id) }}" onsubmit="return confirm('Delete this card design?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded transition cursor-pointer" title="Delete">
                                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 2: Staff Card Designs -->
            <div class="space-y-4 pt-4">
                <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-chalkboard-user text-emerald-600"></i>
                        <h2 class="text-sm font-bold text-slate-900">Staff Card Designs</h2>
                        <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ count($staffTemplates) }}</span>
                    </div>
                    <button type="button" onclick="openModal('newTemplateModal')" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1 cursor-pointer">
                        <i class="fa-solid fa-plus text-[10px]"></i>
                        <span>Add Staff Design</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($staffTemplates as $tpl)
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col justify-between hover:border-slate-300 transition group">
                            <!-- Live Miniature Card Mockup Preview -->
                            <div class="p-5 bg-slate-50 flex items-center justify-center border-b border-slate-100 min-h-[170px]">
                                <div class="w-32 {{ $tpl->layout === 'Horizontal' ? 'h-20' : 'h-36' }} rounded-xl shadow-md border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transform group-hover:scale-105 transition duration-200">
                                    <div class="p-1 text-white text-[7px] font-black uppercase tracking-wider" style="background: {{ $tpl->bg_color }};">
                                        {{ $school->name ?? 'ACADEMY' }}
                                    </div>
                                    <div class="flex-1 flex flex-col items-center justify-center p-1 space-y-0.5">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-[10px] text-slate-400">
                                            <i class="fa-solid fa-user-tie"></i>
                                        </div>
                                        <div class="text-[8px] font-bold text-slate-900">Staff Member</div>
                                        <div class="text-[6.5px] text-emerald-700 font-semibold">Faculty &bull; STF-01</div>
                                    </div>
                                    <div class="p-0.5 bg-slate-100 border-t border-slate-200 text-[6px] font-mono text-slate-400">
                                        [QR CODE]
                                    </div>
                                </div>
                            </div>

                            <!-- Card Details & Actions -->
                            <div class="p-4 space-y-3">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-xs font-bold text-slate-900 truncate" title="{{ $tpl->title }}">{{ $tpl->title }}</h3>
                                        @if($tpl->is_default)
                                            <span class="text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.2 rounded-full">Default</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-500 flex items-center space-x-2 mt-1">
                                        <span class="bg-emerald-50 text-emerald-700 px-1.5 py-0.2 rounded text-[10px] font-semibold">{{ $tpl->card_type }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $tpl->width_mm ?? 54 }} &times; {{ $tpl->height_mm ?? 85.6 }} mm</span>
                                        <span>&bull;</span>
                                        <span>{{ $tpl->layout }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                                    <a href="{{ route('id-cards.staff', ['template_id' => $tpl->id]) }}" class="font-semibold text-emerald-700 hover:text-emerald-800 flex items-center space-x-1">
                                        <span>Use for Staff</span>
                                        <i class="fa-solid fa-arrow-right text-[10px]"></i>
                                    </a>
                                    <div class="flex items-center space-x-1">
                                        @if(!$tpl->is_default)
                                            <form method="POST" action="{{ route('id-cards.templates.default', $tpl->id) }}">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 text-[11px] font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded transition cursor-pointer" title="Set as Default">
                                                    Set Default
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

</div>

{{-- ========================================================================= --}}
{{-- MODALS --}}
{{-- ========================================================================= --}}

<!-- Modal 1: Add New Card Template Modal -->
<div id="newTemplateModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs items-center justify-center p-4" onclick="if(event.target === this) closeModal('newTemplateModal')">
    <div class="bg-white rounded-2xl max-w-md w-full border border-slate-200 shadow-2xl p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="text-sm font-bold text-slate-900">Create New Card Design</h3>
            <button type="button" onclick="closeModal('newTemplateModal')" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <form method="POST" action="{{ route('id-cards.templates.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Design Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Modern Minimalist Gold 2026" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Card Category <span class="text-rose-500">*</span></label>
                    <select name="card_type" required class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                        <option value="Student">Student ID Card</option>
                        <option value="Staff">Faculty &amp; Staff Badge</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Orientation <span class="text-rose-500">*</span></label>
                    <select name="layout" id="modalLayoutSelect" onchange="updateModalDimensions(this.value)" required class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                        <option value="Vertical">Vertical (Portrait 54x85.6mm)</option>
                        <option value="Horizontal">Horizontal (Landscape 85.6x54mm)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Width (mm)</label>
                    <input type="number" step="0.1" name="width_mm" id="modalWidthInput" value="54.0" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Height (mm)</label>
                    <input type="number" step="0.1" name="height_mm" id="modalHeightInput" value="85.6" class="w-full text-xs rounded-lg border-slate-200 focus:border-blue-500 py-2 px-3 bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Header Theme Accent Color</label>
                <div class="flex items-center space-x-2">
                    <input type="color" name="bg_color" value="#1e40af" class="w-9 h-9 p-0.5 rounded border border-slate-200 cursor-pointer">
                    <span class="text-xs text-slate-500">Pick theme accent for card header &amp; highlights</span>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModal('newTemplateModal')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg cursor-pointer">Cancel</button>
                <button type="submit" class="px-4 py-2 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm cursor-pointer">Save Design</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Single Card Fullscreen Preview Modal -->
<div id="singleCardPreviewModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs items-center justify-center p-4" onclick="if(event.target === this) closeModal('singleCardPreviewModal')">
    <div class="bg-white rounded-2xl max-w-sm w-full border border-slate-200 shadow-2xl p-6 space-y-4 text-center">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h3 class="text-sm font-bold text-slate-900">PVC Card Preview</h3>
            <button type="button" onclick="closeModal('singleCardPreviewModal')" class="text-slate-400 hover:text-slate-600 text-lg font-bold cursor-pointer">&times;</button>
        </div>

        <!-- Rendered Card Container (CR-80 Dimensions) -->
        <div class="mx-auto w-56 h-88 rounded-2xl shadow-xl border border-slate-300 overflow-hidden flex flex-col justify-between bg-white text-center" style="box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);">
            <div id="pvchTopBand" class="p-2.5 text-white bg-blue-600 text-center">
                <div class="text-[9px] font-black uppercase tracking-wider">{{ $school->name ?? 'INTERNATIONAL SCHOOL' }}</div>
                <div id="pvchCardSubtitle" class="text-[7.5px] text-white/80 font-medium">STUDENT IDENTITY CARD</div>
            </div>

            <div class="p-3 flex-1 flex flex-col items-center justify-between space-y-1.5">
                <div id="pvchPhotoWrap" class="w-16 h-16 rounded-full bg-slate-100 border-2 border-white shadow-sm overflow-hidden flex items-center justify-center">
                    <i class="fa-solid fa-user text-slate-400 text-2xl"></i>
                </div>
                <div>
                    <h4 id="pvchName" class="text-xs font-bold text-slate-900">Student Name</h4>
                    <p id="pvchSubinfo" class="text-[10px] text-blue-600 font-semibold">Class 1 &bull; Sec A</p>
                </div>
                <div class="w-full text-[9px] border-t border-slate-100 pt-1.5 space-y-1 text-slate-600 text-left font-medium">
                    <div class="flex justify-between">
                        <span id="pvchAdmLabel" class="text-slate-400">Admission No:</span>
                        <span id="pvchAdm" class="font-bold text-slate-800">ADM-001</span>
                    </div>
                    <div class="flex justify-between">
                        <span id="pvchRollLabel" class="text-slate-400">Roll No:</span>
                        <span id="pvchRoll" class="font-bold text-slate-800">101</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Blood Group:</span>
                        <span id="pvchBlood" class="font-bold text-rose-600">O+</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Emergency:</span>
                        <span id="pvchPhone" class="font-mono text-slate-800">9876543210</span>
                    </div>
                </div>
            </div>

            <div class="px-3 py-1.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
                <div class="font-mono text-[8px] text-slate-400 flex items-center space-x-1">
                    <i class="fa-solid fa-barcode text-base text-slate-700"></i>
                </div>
                <div class="text-[7px] text-slate-400 border-t border-slate-400 pt-0.5 font-serif italic">
                    Authorized Sign
                </div>
            </div>
        </div>

        <button type="button" onclick="closeModal('singleCardPreviewModal')" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl cursor-pointer">
            Close Preview
        </button>
    </div>
</div>

<script>
// Local Fallback Modal Controllers
function openModal(id) {
    const el = document.getElementById(id);
    if (!el) {
        console.warn('Modal element not found:', id);
        return;
    }
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeModal(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

// Update modal dimension defaults based on orientation
function updateModalDimensions(layout) {
    const wInput = document.getElementById('modalWidthInput');
    const hInput = document.getElementById('modalHeightInput');
    if (layout === 'Horizontal') {
        if (wInput) wInput.value = '85.6';
        if (hInput) hInput.value = '54.0';
    } else {
        if (wInput) wInput.value = '54.0';
        if (hInput) hInput.value = '85.6';
    }
}

// Class-to-Sections Dynamic Mapping
window.classSectionsMap = @json($classes->mapWithKeys(function($c) {
    return [$c->id => $c->sections->map(function($s) {
        return ['id' => $s->id, 'name' => $s->name];
    })];
}));

document.addEventListener('DOMContentLoaded', function() {
    // 1. Dynamic Section updates when Class changes
    const classFilter = document.getElementById('class-filter');
    const sectionFilter = document.getElementById('section-filter');

    if (classFilter && sectionFilter) {
        classFilter.addEventListener('change', function() {
            const cid = this.value;
            sectionFilter.innerHTML = '<option value="">-- All Sections --</option>';
            if (cid && window.classSectionsMap && window.classSectionsMap[cid]) {
                window.classSectionsMap[cid].forEach(function(sec) {
                    const opt = document.createElement('option');
                    opt.value = sec.id;
                    opt.textContent = sec.name;
                    sectionFilter.appendChild(opt);
                });
            }
            document.getElementById('filter-form').submit();
        });

        sectionFilter.addEventListener('change', function() {
            document.getElementById('filter-form').submit();
        });
    }

    // 2. Initial counts
    updateSelectedCount();
    updateSelectedStaffCount();
});

// Toggle Print Layout Setup Accordion
function togglePrintLayout(panelId, arrowId) {
    const panel = document.getElementById(panelId);
    const arrow = document.getElementById(arrowId);
    if (!panel) return;
    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
        if (arrow) arrow.classList.add('rotate-180');
    } else {
        panel.classList.add('hidden');
        if (arrow) arrow.classList.remove('rotate-180');
    }
}

// Toggle Custom Dimensions Inputs
function toggleCustomDimensions(prefix, val) {
    const wEl = document.getElementById(prefix + 'CustomW');
    const hEl = document.getElementById(prefix + 'CustomH');
    if (!wEl || !hEl) return;
    if (val === 'custom') {
        wEl.classList.remove('hidden');
        hEl.classList.remove('hidden');
    } else {
        wEl.classList.add('hidden');
        hEl.classList.add('hidden');
    }
}

// Checkboxes Student
function toggleAllStudents(master) {
    const boxes = document.querySelectorAll('.student-checkbox');
    boxes.forEach(b => b.checked = master.checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const checked = document.querySelectorAll('.student-checkbox:checked').length;
    const badge = document.getElementById('selectedCountBadge');
    if (badge) badge.textContent = checked + ' selected';
    const errBox = document.getElementById('student-idc-error');
    if (errBox && checked > 0) errBox.classList.add('hidden');
}

// Checkboxes Staff
function toggleAllStaff(master) {
    const boxes = document.querySelectorAll('.staff-checkbox');
    boxes.forEach(b => b.checked = master.checked);
    updateSelectedStaffCount();
}

function updateSelectedStaffCount() {
    const checked = document.querySelectorAll('.staff-checkbox:checked').length;
    const badge = document.getElementById('selectedStaffBadge');
    if (badge) badge.textContent = checked + ' selected';
    const errBox = document.getElementById('staff-idc-error');
    if (errBox && checked > 0) errBox.classList.add('hidden');
}

// Live Interactive Preview for Students
function updateStudentPreviewDesign(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt) return;
    const bg = opt.getAttribute('data-bg') || '#1e40af';
    const layout = opt.getAttribute('data-layout') || 'Vertical';
    const title = opt.getAttribute('data-title') || 'Card Design';

    const topBand = document.getElementById('studentPreviewTopBand');
    const cardBox = document.getElementById('studentPreviewCardBox');
    const tplName = document.getElementById('studentLiveTplName');

    if (topBand) topBand.style.background = bg;
    if (tplName) tplName.textContent = title;
    if (cardBox) {
        if (layout === 'Horizontal') {
            cardBox.className = 'w-64 h-44 rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transition-all duration-300';
        } else {
            cardBox.className = 'w-48 h-72 rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transition-all duration-300';
        }
    }
}

// Live Interactive Preview for Staff
function updateStaffPreviewDesign(select) {
    const opt = select.options[select.selectedIndex];
    if (!opt) return;
    const bg = opt.getAttribute('data-bg') || '#0f172a';
    const layout = opt.getAttribute('data-layout') || 'Vertical';
    const title = opt.getAttribute('data-title') || 'Staff ID';

    const topBand = document.getElementById('staffPreviewTopBand');
    const cardBox = document.getElementById('staffPreviewCardBox');
    const tplName = document.getElementById('staffLiveTplName');

    if (topBand) topBand.style.background = bg;
    if (tplName) tplName.textContent = title;
    if (cardBox) {
        if (layout === 'Horizontal') {
            cardBox.className = 'w-64 h-44 rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transition-all duration-300';
        } else {
            cardBox.className = 'w-48 h-72 rounded-2xl shadow-lg border border-slate-200 overflow-hidden flex flex-col justify-between bg-white text-center transition-all duration-300';
        }
    }
}

// Intercept form submission to set output_type and validate ticks
function submitGenerateForm(formId, outputType, autoImages) {
    const form = document.getElementById(formId);
    if (!form) return false;

    const isStaff = (formId === 'generate-form-staff');
    const checkClass = isStaff ? '.staff-checkbox:checked' : '.student-checkbox:checked';
    const errId = isStaff ? 'staff-idc-error' : 'student-idc-error';
    const errMsgId = isStaff ? 'staff-idc-error-msg' : 'student-idc-error-msg';
    const outInput = isStaff ? document.getElementById('output_type_staff') : document.getElementById('output_type');
    const autoInput = isStaff ? document.getElementById('auto_images_staff') : document.getElementById('auto_images');

    const errBox = document.getElementById(errId);
    const errMsg = document.getElementById(errMsgId);

    const checkedCount = document.querySelectorAll(checkClass).length;
    if (checkedCount === 0) {
        if (errBox && errMsg) {
            errMsg.textContent = isStaff 
                ? 'Please tick at least one staff member before printing.'
                : 'Please tick at least one student before printing.';
            errBox.classList.remove('hidden');
        }
        return false;
    }

    if (errBox) errBox.classList.add('hidden');
    if (outInput) outInput.value = outputType;
    if (autoInput) autoInput.value = autoImages;

    return true;
}

// Open Single Card Fullscreen Preview from Row Button Data Attributes
function previewCardFromBtn(btn) {
    const type = btn.getAttribute('data-type') || 'student';
    const name = btn.getAttribute('data-name') || 'Name';
    const subinfo = btn.getAttribute('data-subinfo') || '';
    const adm = btn.getAttribute('data-adm') || 'ADM-001';
    const roll = btn.getAttribute('data-roll') || '101';
    const blood = btn.getAttribute('data-blood') || 'O+';
    const phone = btn.getAttribute('data-phone') || '9876543210';
    const photo = btn.getAttribute('data-photo') || '';

    document.getElementById('pvchName').textContent = name;
    document.getElementById('pvchSubinfo').innerHTML = subinfo;
    document.getElementById('pvchAdm').textContent = adm;
    document.getElementById('pvchRoll').textContent = roll;
    document.getElementById('pvchBlood').textContent = blood;
    document.getElementById('pvchPhone').textContent = phone;

    const photoWrap = document.getElementById('pvchPhotoWrap');
    if (photoWrap) {
        if (photo) {
            photoWrap.innerHTML = '<img src="' + photo + '" class="w-full h-full object-cover">';
        } else {
            const initial = (name.trim()[0] || 'S').toUpperCase();
            photoWrap.innerHTML = '<span class="text-2xl font-bold text-slate-400">' + initial + '</span>';
        }
    }

    if (type === 'staff') {
        document.getElementById('pvchCardSubtitle').textContent = 'FACULTY & STAFF ID';
        document.getElementById('pvchTopBand').style.background = '#0f172a';
        document.getElementById('pvchSubinfo').className = 'text-[10px] text-emerald-600 font-semibold';
        document.getElementById('pvchAdmLabel').textContent = 'Staff ID:';
        document.getElementById('pvchRollLabel').textContent = 'Department:';
    } else {
        document.getElementById('pvchCardSubtitle').textContent = 'STUDENT IDENTITY CARD';
        document.getElementById('pvchTopBand').style.background = '#1e40af';
        document.getElementById('pvchSubinfo').className = 'text-[10px] text-blue-600 font-semibold';
        document.getElementById('pvchAdmLabel').textContent = 'Admission No:';
        document.getElementById('pvchRollLabel').textContent = 'Roll No:';
    }

    openModal('singleCardPreviewModal');
}
</script>
@endsection
