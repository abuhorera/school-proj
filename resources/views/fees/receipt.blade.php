<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt #{{ $payment->invoice_no }} - {{ $school->name }}</title>
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
            .receipt-box { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-4 sm:p-8 flex flex-col items-center justify-center font-sans">

    <!-- Action Bar (Hidden in Print) -->
    <div class="max-w-2xl w-full mb-4 flex items-center justify-between no-print">
        <a href="{{ route('fees.index') }}" class="inline-flex items-center space-x-1.5 text-xs text-slate-600 hover:text-slate-900 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm font-semibold transition">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Fees Ledger</span>
        </a>
        <button onclick="window.print()" class="inline-flex items-center space-x-1.5 text-xs text-white bg-emerald-600 hover:bg-emerald-700 px-4 py-2 rounded-xl shadow-md shadow-emerald-500/20 font-bold transition">
            <i class="fa-solid fa-print"></i>
            <span>Print Receipt</span>
        </button>
    </div>

    <!-- Official Printable Receipt Container -->
    <div class="receipt-box max-w-2xl w-full bg-white p-8 rounded-2xl border border-slate-200/80 shadow-md space-y-6">
        <!-- School Header -->
        <div class="flex items-center justify-between border-b-2 border-slate-900 pb-5">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xl font-black">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="font-black text-lg text-slate-900 tracking-tight">{{ $school->name }}</h1>
                    <p class="text-xs text-slate-500">{{ $school->address }} • Phone: {{ $school->phone }}</p>
                    <p class="text-[10px] text-slate-400">Affiliation Code: {{ $school->code }} • Session: 2026-2027</p>
                </div>
            </div>
            <div class="text-right">
                <div class="inline-block bg-emerald-50 text-emerald-800 text-xs font-black uppercase px-2.5 py-1 rounded-md border border-emerald-200">
                    PAID RECEIPT
                </div>
                <div class="text-[11px] font-mono font-bold text-slate-700 mt-1">{{ $payment->invoice_no }}</div>
                <div class="text-[10px] text-slate-400">Date: {{ date('d M Y', strtotime($payment->payment_date)) }}</div>
            </div>
        </div>

        <!-- Student & Bill Meta -->
        <div class="grid grid-cols-2 gap-4 text-xs bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Student Name</span>
                <span class="font-bold text-slate-900 text-sm">{{ $payment->student->full_name }}</span>
                <span class="text-slate-500 block text-[11px]">Adm No: {{ $payment->student->admission_no }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Class & Section</span>
                <span class="font-bold text-slate-800 text-sm">
                    {{ $payment->student->latestEnrollment->schoolClass->name ?? 'Class' }} ({{ $payment->student->latestEnrollment->section->name ?? 'A' }})
                </span>
                <span class="text-slate-500 block text-[11px]">Roll No: {{ $payment->student->roll_no ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Father's Name</span>
                <span class="font-semibold text-slate-800">{{ $payment->student->guardian->father_name ?? '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Payment Mode</span>
                <span class="font-semibold text-slate-800">{{ $payment->payment_method }} (Ref: {{ $payment->transaction_id ?? 'N/A' }})</span>
            </div>
        </div>

        <!-- Itemized Fee Table -->
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500 font-bold uppercase text-[10px]">
                    <th class="py-2.5">Description</th>
                    <th class="py-2.5 text-center">Session</th>
                    <th class="py-2.5 text-right">Amount (₹)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr>
                    <td class="py-3">
                        <div class="font-bold text-slate-900">{{ $payment->allocation->feeMaster->feeType->name ?? 'Quarterly Academic Tuition Fee' }}</div>
                        <div class="text-[10px] text-slate-400">{{ $payment->remarks ?? 'Regular academic tuition & laboratory dues' }}</div>
                    </td>
                    <td class="py-3 text-center text-slate-600">2026-2027</td>
                    <td class="py-3 text-right font-black text-slate-900 text-sm">₹{{ number_format($payment->amount_paid, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="border-t-2 border-slate-900 font-bold">
                    <td colspan="2" class="py-3 text-slate-700">Total Amount Paid</td>
                    <td class="py-3 text-right text-base font-black text-emerald-600">₹{{ number_format($payment->amount_paid, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Signatures & Footer -->
        <div class="pt-8 flex items-end justify-between text-xs text-slate-500">
            <div>
                <div class="w-32 border-b border-slate-300 mb-1"></div>
                <div class="text-[10px] uppercase font-semibold text-slate-400">Cashier / Accountant</div>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 rounded-full border border-dashed border-slate-300 flex items-center justify-center text-[10px] text-slate-300 mx-auto mb-1">
                    SEAL
                </div>
                <div class="text-[9px] uppercase font-semibold text-slate-400">School Stamp</div>
            </div>
            <div class="text-right">
                <div class="w-32 border-b border-slate-300 mb-1 ml-auto"></div>
                <div class="text-[10px] uppercase font-semibold text-slate-400">Authorized Signatory</div>
            </div>
        </div>

        <div class="text-center text-[10px] text-slate-400 border-t border-slate-100 pt-3">
            This is a computer-generated official receipt. Multi School ERP v3.8.0.
        </div>
    </div>

</body>
</html>

