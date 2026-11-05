@extends('layouts.app')

@section('title', 'Generated Documents Preview')

@push('styles')
<style>
    /* Desk Viewport (matches live demo https://demo.multischoolerp.com) */
    .imp-viewport {
        background-color: #e9ecef;
        padding: 24px 16px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: calc(100vh - 220px);
    }
    
    .imp-sheet-info {
        text-align: center;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-bottom: 16px;
    }

    /* Physical Paper Sheet Canvas */
    .imp-page {
        position: relative;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15), 0 1px 4px rgba(0, 0, 0, 0.1);
        margin: 0 auto 28px auto;
        overflow: hidden;
        page-break-after: always;
        break-after: page;
        box-sizing: border-box;
    }

    .imp-page:last-child {
        margin-bottom: 0;
        page-break-after: auto;
        break-after: auto;
    }

    /* Imposition Cells & Crop Marks */
    .imp-cell {
        position: absolute;
        overflow: hidden;
        box-sizing: border-box;
    }

    .imp-card {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
    }

    .imp-mark {
        position: absolute;
        background: #94a3b8;
        pointer-events: none;
    }

    /* Physical Card Styling */
    .erp-card-container,
    .cr80-card {
        width: 100%;
        height: 100%;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 3.2mm;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 2px 4px rgba(0,0,0,0.06);
    }

    /* Header Band */
    .card-head-band,
    .card-header-band {
        color: #ffffff;
        padding: 2mm 2.2mm;
        text-align: center;
        flex-shrink: 0;
    }

    .card-school-title {
        font-size: 7.5pt;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        line-height: 1.1;
    }

    .card-badge-title {
        font-size: 5pt;
        font-weight: 700;
        opacity: 0.9;
        margin-top: 0.6mm;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    /* Body Details */
    .card-main-content {
        padding: 1.8mm 2.5mm;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
    }

    .card-avatar-wrap {
        width: 16mm;
        height: 16mm;
        border-radius: 50%;
        border: 1.5pt solid #ffffff;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        background: #f1f5f9;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: #475569;
        font-size: 9pt;
        margin-top: 0.5mm;
        flex-shrink: 0;
    }

    .card-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .card-holder-name {
        font-size: 8.2pt;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.15;
        margin-top: 1mm;
        text-transform: uppercase;
    }

    .card-holder-sub {
        font-size: 6pt;
        font-weight: 700;
        color: #2563eb;
        margin-top: 0.4mm;
    }

    /* Info Table */
    .card-table {
        width: 100%;
        border-top: 0.5pt solid #e2e8f0;
        padding-top: 1mm;
        margin-top: 1mm;
        font-size: 5.8pt;
        line-height: 1.35;
        text-align: left;
        color: #334155;
    }

    .card-table-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.35mm;
    }

    .card-table-label {
        color: #64748b;
        font-weight: 600;
    }

    .card-table-value {
        font-weight: 700;
        color: #0f172a;
    }

    /* Footer Band */
    .card-bottom-band {
        padding: 1.2mm 2.2mm;
        background: #f8fafc;
        border-top: 0.5pt solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 5pt;
        color: #64748b;
        flex-shrink: 0;
    }

    .card-scan-code {
        font-family: monospace;
        display: flex;
        align-items: center;
        gap: 1mm;
        font-weight: 700;
        color: #1e293b;
    }

    .card-signatory {
        font-family: Georgia, serif;
        font-style: italic;
        font-size: 5pt;
        border-top: 0.5pt solid #94a3b8;
        padding-top: 0.3mm;
        color: #334155;
    }

    /* Landscape Layout Alternative */
    .erp-card-container.is-landscape {
        flex-direction: row;
    }
    .erp-card-container.is-landscape .card-head-band {
        width: 32%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2mm 1.5mm;
    }
    .erp-card-container.is-landscape .card-main-content {
        width: 68%;
        padding: 2mm 2.5mm;
        justify-content: center;
        text-align: left;
        align-items: flex-start;
    }

    /* ============================================================ */
    /* Exact Print Rules (Hides chrome, renders pristine sheets)    */
    /* ============================================================ */
    @page {
        size: {{ $paper }} {{ $paperOrientation }};
        margin: 0;
    }

    @media print {
        header,
        #main-sidebar,
        .print-controls,
        .imp-sheet-info,
        .content-header,
        footer,
        nav,
        aside,
        .no-print {
            display: none !important;
        }

        body,
        html,
        main,
        .min-h-screen,
        .content-wrapper,
        .card,
        .card-body {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: #ffffff !important;
            overflow: visible !important;
        }

        .imp-viewport {
            background-color: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
            overflow: visible !important;
            display: block !important;
            min-height: auto !important;
        }

        .imp-page {
            margin: 0 !important;
            box-shadow: none !important;
            page-break-after: always !important;
            break-after: page !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .imp-page:last-child {
            page-break-after: auto !important;
            break-after: auto !important;
        }
    }
</style>
@endpush

@section('content')
<div class="space-y-4">
    <!-- Breadcrumb & Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 no-print">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Generated Documents Preview</h1>
            <div class="flex items-center space-x-2 text-xs text-slate-500 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <span>/</span>
                <a href="{{ $type === 'staff' ? route('id-cards.staff') : route('id-cards.students') }}" class="hover:text-blue-600 transition">ID Cards</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Generated Documents Preview</span>
            </div>
        </div>
    </div>

    <!-- Main Card with Print Controls Toolbar & Desk Viewport -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Print Controls Header Toolbar -->
        <div class="p-4 bg-slate-50/80 border-b border-slate-200 print-controls">
            <p class="text-xs text-slate-600 mb-3 flex items-center space-x-1.5">
                <i class="fa-solid fa-circle-info text-blue-500"></i>
                <span>This page is optimized for printing. Use the button below or your browser's print function (Ctrl+P or Cmd+P).</span>
            </p>
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- 1. Print Documents -->
                <button type="button" onclick="window.print();" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Documents</span>
                </button>

                <!-- 2. Download PDF -->
                <button type="button" onclick="window.print();" class="inline-flex items-center space-x-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-emerald-600/20 transition cursor-pointer" title="Direct high-resolution vector PDF print">
                    <i class="fa-solid fa-file-pdf"></i>
                    <span>Download PDF ({{ strtoupper($paper) }} {{ ucfirst($paperOrientation) }})</span>
                </button>

                <!-- 3. Download as Images (ZIP) -->
                <form action="{{ $type === 'staff' ? route('id-cards.staff.generate') : route('id-cards.students.generate') }}" method="POST" class="inline-block m-0">
                    @csrf
                    <input type="hidden" name="template_id" value="{{ $template->id ?? 1 }}">
                    @if($type === 'staff')
                        @foreach($records as $rec)
                            <input type="hidden" name="staff_ids[]" value="{{ $rec->id }}">
                        @endforeach
                    @else
                        @foreach($records as $rec)
                            <input type="hidden" name="student_ids[]" value="{{ $rec->id }}">
                        @endforeach
                    @endif
                    <input type="hidden" name="card_size" value="{{ $cardSize }}">
                    <input type="hidden" name="paper" value="{{ $paper }}">
                    <input type="hidden" name="paper_orientation" value="{{ $paperOrientation }}">
                    <input type="hidden" name="margin_mm" value="{{ $marginMm }}">
                    <input type="hidden" name="gap_mm" value="{{ $gapMm }}">
                    <input type="hidden" name="output_type" value="images">
                    <input type="hidden" name="auto_images" value="1">
                    <button type="submit" class="inline-flex items-center space-x-1.5 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-cyan-600/20 transition cursor-pointer" title="For PVC/plastic card printers (Evolis, Zebra, Fargo). Each card is saved as a separate high-resolution PNG inside a ZIP.">
                        <i class="fa-solid fa-images"></i>
                        <span>Download as Images (ZIP)</span>
                    </button>
                </form>

                <!-- 4. Back to ID Cards -->
                <a href="{{ $type === 'staff' ? route('id-cards.staff') : route('id-cards.students') }}" class="inline-flex items-center space-x-1.5 bg-slate-600 hover:bg-slate-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to ID Cards</span>
                </a>
            </div>
        </div>

        <!-- Desk Viewport with Imposed Sheets -->
        <div class="imp-viewport">
            <div class="imp-sheet-info">
                {{ strtoupper($paper) }} {{ ucfirst($paperOrientation) }}
                &middot; Card {{ $cardWidthMm }} &times; {{ $cardHeightMm }} mm
                &middot; {{ $sheetSpecs['cols'] }} &times; {{ $sheetSpecs['rows'] }} = {{ $sheetSpecs['perSheet'] }} cards/sheet
                &middot; {{ $pages->count() }} sheet{{ $pages->count() > 1 ? 's' : '' }}
            </div>

            @foreach($pages as $pageIndex => $pageItems)
                <div class="imp-page" style="width: {{ $sheetSpecs['sheetW'] }}mm; min-height: {{ $sheetSpecs['sheetH'] }}mm; height: {{ $sheetSpecs['sheetH'] }}mm;">
                    
                    {{-- Cutting Guide Marks --}}
                    @if($cutMarks)
                        {{-- Vertical Crop Marks (Top & Bottom) --}}
                        @foreach($sheetSpecs['colCuts'] as $cut)
                            <div class="imp-mark" style="left: {{ $cut['left'] }}mm; top: 0; width: 0.25mm; height: 5mm;"></div>
                            <div class="imp-mark" style="left: {{ $cut['left'] }}mm; bottom: 0; width: 0.25mm; height: 5mm;"></div>
                            <div class="imp-mark" style="left: {{ $cut['right'] }}mm; top: 0; width: 0.25mm; height: 5mm;"></div>
                            <div class="imp-mark" style="left: {{ $cut['right'] }}mm; bottom: 0; width: 0.25mm; height: 5mm;"></div>
                        @endforeach

                        {{-- Horizontal Crop Marks (Left & Right) --}}
                        @foreach($sheetSpecs['rowCuts'] as $cut)
                            <div class="imp-mark" style="top: {{ $cut['top'] }}mm; left: 0; height: 0.25mm; width: 5mm;"></div>
                            <div class="imp-mark" style="top: {{ $cut['top'] }}mm; right: 0; height: 0.25mm; width: 5mm;"></div>
                            <div class="imp-mark" style="top: {{ $cut['bottom'] }}mm; left: 0; height: 0.25mm; width: 5mm;"></div>
                            <div class="imp-mark" style="top: {{ $cut['bottom'] }}mm; right: 0; height: 0.25mm; width: 5mm;"></div>
                        @endforeach
                    @endif

                    {{-- Cards Imposed in Current Page Grid --}}
                    @foreach($pageItems->values() as $i => $item)
                        @php
                            $colIndex = $i % $sheetSpecs['cols'];
                            $rowIndex = (int) floor($i / $sheetSpecs['cols']);
                            $cellLeft = $sheetSpecs['startX'] + $colIndex * ($cardWidthMm + $gapMm);
                            $cellTop = $sheetSpecs['startY'] + $rowIndex * ($cardHeightMm + $gapMm);
                            $isStaff = ($type === 'staff');
                            $headerBg = $template->bg_color ?? ($isStaff ? '#1e3a8a' : '#1e40af');
                            $fullName = ($item->first_name ?? '') . ' ' . ($item->last_name ?? '');
                            $initial = strtoupper(substr(trim($fullName ?: 'A'), 0, 1));
                            $isLandscape = ($cardWidthMm > $cardHeightMm);
                        @endphp

                        <div class="imp-cell" style="left: {{ round($cellLeft, 1) }}mm; top: {{ round($cellTop, 1) }}mm; width: {{ $cardWidthMm }}mm; height: {{ $cardHeightMm }}mm;">
                            <div class="imp-card">
                                <div class="erp-card-container cr80-card {{ $isLandscape ? 'is-landscape' : '' }}">
                                    <!-- Header Band -->
                                    <div class="card-head-band card-header-band" style="background-color: {{ $headerBg }};">
                                        <div class="card-school-title">{{ $school->name ?? 'YUG INTERNATIONAL SCHOOL' }}</div>
                                        <div class="card-badge-title">{{ $isStaff ? 'FACULTY & STAFF IDENTITY CARD' : 'STUDENT IDENTITY CARD' }}</div>
                                    </div>

                                    <!-- Main Body -->
                                    <div class="card-main-content">
                                        <!-- Photo / Avatar Circle -->
                                        <div class="card-avatar-wrap">
                                            @if(!empty($item->photo) && file_exists(public_path('uploads/' . $item->photo)))
                                                <img src="{{ asset('uploads/' . $item->photo) }}" alt="{{ $fullName }}" class="card-avatar-img">
                                            @else
                                                <span>{{ $initial }}</span>
                                            @endif
                                        </div>

                                        <!-- Name & Role / Class -->
                                        <div>
                                            <div class="card-holder-name">{{ $fullName ?: ($isStaff ? 'Staff Member' : 'Student Name') }}</div>
                                            <div class="card-holder-sub">
                                                @if($isStaff)
                                                    {{ $item->designation ?? 'Faculty' }} &bull; {{ $item->department ?? 'General' }}
                                                @else
                                                    {{ $item->class_name ?? 'Class' }} &bull; {{ $item->section_name ? 'Section ' . $item->section_name : 'Section A' }}
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Detail Table -->
                                        <div class="card-table">
                                            @if($isStaff)
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Employee ID:</span>
                                                    <span class="card-table-value">{{ $item->employee_id ?? ('STF-' . str_pad($item->id, 3, '0', STR_PAD_LEFT)) }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Department:</span>
                                                    <span class="card-table-value">{{ $item->department ?? 'Academics' }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Joining Date:</span>
                                                    <span class="card-table-value">{{ $item->joining_date ? date('d M, Y', strtotime($item->joining_date)) : '15 Jun, 2020' }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Blood Group:</span>
                                                    <span class="card-table-value" style="color: #e11d48;">{{ $item->blood_group ?? 'B+' }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Phone:</span>
                                                    <span class="card-table-value" style="font-family: monospace;">{{ $item->phone ?? '9876543210' }}</span>
                                                </div>
                                            @else
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Admission No:</span>
                                                    <span class="card-table-value">{{ $item->admission_no ?? ('YISADM-' . str_pad($item->id, 3, '0', STR_PAD_LEFT)) }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Roll No:</span>
                                                    <span class="card-table-value">{{ $item->roll_no ?? str_pad($item->id, 2, '0', STR_PAD_LEFT) }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Father's Name:</span>
                                                    <span class="card-table-value">{{ $item->father_name ?? 'Parent / Guardian' }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">D.O.B:</span>
                                                    <span class="card-table-value">{{ $item->dob ? date('d M, Y', strtotime($item->dob)) : '01 Jan, 2015' }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Blood Group:</span>
                                                    <span class="card-table-value" style="color: #e11d48;">{{ $item->blood_group ?? 'O+' }}</span>
                                                </div>
                                                <div class="card-table-row">
                                                    <span class="card-table-label">Emergency:</span>
                                                    <span class="card-table-value" style="font-family: monospace;">{{ $item->phone ?? '9876543210' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Bottom Band (Barcode & Signature) -->
                                    <div class="card-bottom-band">
                                        <div class="card-scan-code">
                                            <i class="fa-solid fa-barcode" style="font-size: 11pt; color: #1e293b;"></i>
                                            <span>{{ $isStaff ? ($item->employee_id ?? ('STF-' . $item->id)) : ($item->admission_no ?? ('YISADM-' . $item->id)) }}</span>
                                        </div>
                                        <div class="card-signatory">
                                            {{ $isStaff ? 'Director' : 'Principal' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
