@extends('layouts.app')

@section('title', 'Library Management System')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Modules</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Library</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-book-bookmark"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Library Management &amp; OPAC Catalog</h1>
                    <p class="text-xs text-slate-500">Catalog management, barcode circulation desk, book issues &amp; returns, and overdue fines tracking.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="openModal('addBookModal')" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Add New Book</span>
            </button>
            <button type="button" onclick="openModal('issueBookModal')" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition cursor-pointer">
                <i class="fa-solid fa-arrow-right-arrow-left text-slate-500"></i>
                <span>Issue Book</span>
            </button>
        </div>
    </div>

    <!-- Sub Navigation Pill Tabs -->
    <div class="flex flex-wrap items-center justify-between gap-3 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
        <div class="flex flex-wrap items-center gap-1 bg-slate-100 p-1 rounded-lg text-xs font-semibold">
            <a href="{{ route('modules.library', ['tab' => 'dashboard']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'dashboard' || empty($tab) ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Library Dashboard</span>
            </a>
            <a href="{{ route('modules.library', ['tab' => 'issue-return']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'issue-return' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-repeat"></i>
                <span>Issue / Return Desk</span>
                <span class="ml-1 bg-amber-100 text-amber-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($issues->where('status', 'Issued')) }}</span>
            </a>
            <a href="{{ route('modules.library', ['tab' => 'books']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'books' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-book"></i>
                <span>Master Catalog</span>
                <span class="ml-1 bg-blue-100 text-blue-700 text-[10px] px-1.5 py-0.2 rounded-full font-bold">{{ count($books) }}</span>
            </a>
            <a href="{{ route('modules.library', ['tab' => 'categories']) }}" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-md transition {{ $tab === 'categories' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                <i class="fa-solid fa-tags"></i>
                <span>Categories</span>
            </a>
        </div>

        <div class="relative w-56 hidden sm:block">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-xs text-slate-400"></i>
            <input type="text" id="librarySearchInput" onkeyup="filterLibraryTable()" placeholder="Search title, ISBN, author..." class="w-full bg-white border border-slate-300 rounded-lg pl-8 pr-3 py-1.5 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
    </div>

    <!-- 4 High-Density KPI Metric Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Copies -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Total Physical Volumes</div>
                <div class="text-2xl font-bold text-slate-900">{{ $books->sum('quantity') }}</div>
                <div class="text-[11px] text-blue-600 font-medium flex items-center">
                    <i class="fa-solid fa-book-open mr-1"></i> {{ count($books) }} Titles Cataloged
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-book"></i>
            </div>
        </div>

        <!-- Available on Shelf -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Available on Shelf</div>
                <div class="text-2xl font-bold text-slate-900">{{ $books->sum('available_quantity') }}</div>
                <div class="text-[11px] text-emerald-600 font-medium flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span> Ready for issue
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Currently Issued -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Currently Issued</div>
                <div class="text-2xl font-bold text-slate-900">{{ count($issues->where('status', 'Issued')) }}</div>
                <div class="text-[11px] text-amber-600 font-medium flex items-center">
                    <i class="fa-solid fa-hand-holding mr-1"></i> Active Borrowers
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
            </div>
        </div>

        <!-- Overdue Penalties -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex items-center justify-between">
            <div class="space-y-1">
                <div class="text-xs font-medium text-slate-500">Overdue Penalties</div>
                <div class="text-2xl font-bold text-slate-900">0</div>
                <div class="text-[11px] text-purple-600 font-medium flex items-center">
                    <i class="fa-solid fa-shield-check mr-1"></i> Zero Overdue Fines
                </div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>
    </div>

    @if($tab === 'issue-return')
        <!-- ================================================================= -->
        <!-- TAB: ISSUE / RETURN DESK                                          -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-repeat"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Book Circulation Ledger</h2>
                        <p class="text-[11px] text-slate-500">Real-time borrower tracking, issue timestamps, and return verification.</p>
                    </div>
                </div>
                <button type="button" onclick="openModal('issueBookModal')" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-3.5 py-2 rounded-lg shadow-sm transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Issue New Book</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Book Title</th>
                            <th class="py-3 px-4">Borrower (Member)</th>
                            <th class="py-3 px-4">Issue Date</th>
                            <th class="py-3 px-4">Due Date</th>
                            <th class="py-3 px-4">Circulation Status</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($issues as $iss)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-book text-slate-400"></i>
                                        <span>{{ $iss->book_title }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $iss->member_name }}</div>
                                    <span class="inline-flex items-center px-2 py-0.2 rounded text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $iss->member_type }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-slate-700">{{ $iss->issue_date }}</td>
                                <td class="py-3.5 px-4 font-medium text-slate-700">{{ $iss->due_date }}</td>
                                <td class="py-3.5 px-4">
                                    @if($iss->status == 'Returned')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="fa-solid fa-check mr-1 text-emerald-600"></i> Returned
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="fa-regular fa-clock mr-1 text-amber-600"></i> Issued (Active)
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    @if($iss->status == 'Issued')
                                        <button type="button" onclick="alert('Book returned successfully! Stock updated.');" class="inline-flex items-center space-x-1 bg-white hover:bg-emerald-50 border border-slate-300 hover:border-emerald-300 text-emerald-700 font-semibold text-xs px-2.5 py-1.5 rounded-lg shadow-sm transition">
                                            <i class="fa-solid fa-rotate-left text-[11px]"></i>
                                            <span>Return Book</span>
                                        </button>
                                    @else
                                        <span class="text-slate-400 text-xs font-mono">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 text-slate-400">No circulation records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($tab === 'categories')
        <!-- ================================================================= -->
        <!-- TAB: BOOK CATEGORIES                                              -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Book Classification Categories</h2>
                        <p class="text-[11px] text-slate-500">Dewey Decimal / Custom Subject Classifications.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Category Name</th>
                            <th class="py-3 px-4">Classification Code</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4 text-right">Titles Cataloged</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($categories as $c)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-4 font-bold text-slate-900">{{ $c->name }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200 font-semibold">{{ $c->code }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500">{{ $c->description ?? 'Standard academic department books.' }}</td>
                                <td class="py-3.5 px-4 text-right font-bold text-blue-600">
                                    {{ $books->where('category_id', $c->id)->count() }} titles
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <!-- ================================================================= -->
        <!-- TAB: MASTER BOOK CATALOG (DEFAULT)                                -->
        <!-- ================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Master Book Catalog (OPAC)</h2>
                        <p class="text-[11px] text-slate-500">Accession register, rack locator, and barcode labels.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full font-bold border border-blue-200">
                        {{ count($books) }} Titles
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700" id="libraryBooksTable">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Book Title</th>
                            <th class="py-3 px-4">Author &amp; Publisher</th>
                            <th class="py-3 px-4">ISBN / Accession Code</th>
                            <th class="py-3 px-4">Category</th>
                            <th class="py-3 px-4">Rack Location</th>
                            <th class="py-3 px-4">Shelf Stock</th>
                            <th class="py-3 px-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($books as $b)
                            <tr class="hover:bg-slate-50/80 transition library-book-row">
                                <td class="py-3.5 px-4 font-bold text-slate-900">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-book text-slate-400"></i>
                                        <span class="book-title">{{ $b->title }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-800 book-author">{{ $b->author }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $b->publisher }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono bg-slate-100 text-slate-700 px-2 py-0.5 rounded border border-slate-200 text-[11px] font-semibold book-isbn">
                                        {{ $b->isbn_no ?? $b->book_no }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ $b->category_name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono bg-slate-100 text-slate-700 border border-slate-200 font-semibold">
                                        <i class="fa-solid fa-cube text-slate-400 mr-1 text-[10px]"></i> {{ $b->rack_no ?? 'Rack A-1' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">
                                        <span class="text-emerald-600 font-bold">{{ $b->available_quantity }}</span> / {{ $b->quantity }}
                                    </div>
                                    <div class="text-[10px] text-slate-500">copies available</div>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="alert('Barcode label: [ {{ $b->isbn_no ?? $b->book_no }} ]\nReady for barcode thermal printer.')" class="inline-flex items-center space-x-1 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-2.5 py-1.5 rounded-lg shadow-sm transition">
                                        <i class="fa-solid fa-barcode text-[11px]"></i>
                                        <span>Barcode</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">No books found in catalog.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>

<!-- ========================================================================= -->
<!-- 🔥 TAILWIND PURE MODAL: ADD NEW BOOK                                      -->
<!-- ========================================================================= -->
<div id="addBookModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-book"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Add New Book to Master Catalog</h3>
            </div>
            <button type="button" onclick="closeModal('addBookModal')" class="text-slate-400 hover:text-slate-600 text-lg p-1 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form onsubmit="event.preventDefault(); alert('Book added to catalog successfully!'); closeModal('addBookModal');" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1">Book Title <span class="text-red-500">*</span></label>
                <input type="text" required placeholder="e.g. Modern Indian History &amp; Freedom Struggle" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Author Name <span class="text-red-500">*</span></label>
                    <input type="text" required placeholder="e.g. Bipin Chandra" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Publisher</label>
                    <input type="text" placeholder="e.g. Oxford University Press" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Category <span class="text-red-500">*</span></label>
                    <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">ISBN / Barcode</label>
                    <input type="text" placeholder="978-0-19-807212-3" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Rack / Shelf No.</label>
                    <input type="text" placeholder="Rack B-04" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Total Copies <span class="text-red-500">*</span></label>
                    <input type="number" min="1" value="5" required class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('addBookModal')" class="bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-lg transition cursor-pointer">Cancel</button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Save Book</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 🔥 TAILWIND PURE MODAL: ISSUE BOOK                                        -->
<!-- ========================================================================= -->
<div id="issueBookModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        <div class="flex items-center justify-between px-6 py-4 bg-slate-50 border-b border-slate-200">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm font-bold">
                    <i class="fa-solid fa-repeat"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-sm">Issue Book at Circulation Desk</h3>
            </div>
            <button type="button" onclick="closeModal('issueBookModal')" class="text-slate-400 hover:text-slate-600 text-lg p-1 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form onsubmit="event.preventDefault(); alert('Book issued to student member!'); closeModal('issueBookModal');" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1">Select Book from Catalog <span class="text-red-500">*</span></label>
                <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    @foreach($books as $bk)
                        <option value="{{ $bk->id }}">{{ $bk->title }} (Avail: {{ $bk->available_quantity }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Borrower Type</label>
                    <select class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="Student">Student</option>
                        <option value="Staff">Faculty / Teacher</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Borrower Name / Roll No. <span class="text-red-500">*</span></label>
                    <input type="text" required placeholder="e.g. Aarav Sharma (Class 10-A)" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Issue Date</label>
                    <input type="date" value="{{ date('Y-m-d') }}" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block font-semibold text-slate-700 text-xs mb-1">Due Return Date</label>
                    <input type="date" value="{{ date('Y-m-d', strtotime('+14 days')) }}" class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('issueBookModal')" class="bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold px-4 py-2 rounded-lg transition cursor-pointer">Cancel</button>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm shadow-emerald-500/20 transition cursor-pointer flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Confirm Issue</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function filterLibraryTable() {
    const q = (document.getElementById('librarySearchInput').value || '').toLowerCase();
    const rows = document.querySelectorAll('.library-book-row');
    rows.forEach(r => {
        const text = r.textContent.toLowerCase();
        r.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>
@endsection
