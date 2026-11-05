@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4 max-w-7xl mx-auto">
    @include('classrooms.partials.subnav', [
        'pageTitle' => 'Room Bookings',
        'pageSubtitle' => 'Manage reservations for special rooms, science labs, conference chambers and the campus auditorium.'
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

    <!-- Action & Settings Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900 dark:text-white">Facility Reservations</h2>
            <p class="text-xs text-slate-500">Scheduled events, workshops and guest lecture bookings.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="document.getElementById('bookingSettingsModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-gear mr-1"></i> Booking Rules
            </button>
            <button type="button" onclick="document.getElementById('newBookingModal').classList.remove('hidden')" class="px-3.5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-lg transition shadow-sm">
                <i class="fa-solid fa-plus mr-1"></i> Book a Room
            </button>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3.5">#</th>
                        <th class="px-4 py-3.5">When</th>
                        <th class="px-4 py-3.5">Room</th>
                        <th class="px-4 py-3.5">Event Purpose</th>
                        <th class="px-4 py-3.5">Asked By</th>
                        <th class="px-4 py-3.5">Status</th>
                        <th class="px-4 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($bookings as $index => $bk)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-3.5 text-slate-400 font-medium">
                                {{ $bookings->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-900 dark:text-white block">{{ $bk->date->format('d M Y') }}</span>
                                <span class="text-[10px] text-slate-400">{{ substr($bk->start_time, 0, 5) }} – {{ substr($bk->end_time, 0, 5) }}</span>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-bold text-slate-900 dark:text-white block">{{ $bk->room->room_number ?? 'Room' }}</span>
                                <span class="text-[10px] text-slate-400">{{ $bk->room->name ?? '' }} ({{ $bk->room->building->name ?? '' }})</span>
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <strong class="text-slate-900 dark:text-white">{{ $bk->title }}</strong>
                            </td>
                            <td class="px-4 py-3.5 font-medium">
                                {{ $bk->booked_by }}
                            </td>
                            <td class="px-4 py-3.5">
                                @if($bk->status === 'Approved')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">Approved</span>
                                @elseif($bk->status === 'Pending')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">Pending</span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">{{ $bk->status }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    @if($bk->status === 'Pending')
                                        <form method="POST" action="{{ route('classrooms.bookings.status', $bk->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Approved">
                                            <button type="submit" class="px-2 py-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-md transition">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('classrooms.bookings.status', $bk->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="Cancelled">
                                            <button type="submit" class="px-2 py-1 text-[11px] text-red-600 hover:bg-red-50 rounded-md transition">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-slate-400 text-[11px]">—</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-slate-500">
                                No room reservations waiting for approval.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal: New Booking -->
<div id="newBookingModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Book a Classroom or Hall</h3>
        <p class="text-xs text-slate-500 mb-4">Request a space for co-curricular events or exam sittings.</p>

        <form method="POST" action="{{ route('classrooms.bookings.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Select Room *</label>
                <select name="room_id" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                    @foreach($rooms as $r)
                        <option value="{{ $r->id }}">{{ $r->room_number }} · {{ $r->name }} ({{ $r->capacity }} seats)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Event / Purpose Title *</label>
                <input type="text" name="title" required placeholder="e.g. Inter-House Debate Rehearsals" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Date *</label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Start Time *</label>
                    <input type="time" name="start_time" value="10:00" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">End Time *</label>
                    <input type="time" name="end_time" value="12:00" required class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Booked By / Organizer *</label>
                <input type="text" name="booked_by" required value="School Admin" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-200 p-2.5">
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('newBookingModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition">Submit Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Booking Preferences -->
<div id="bookingSettingsModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 max-w-md w-full p-6 shadow-2xl">
        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Room Booking Rules</h3>
        <p class="text-xs text-slate-500 mb-4">Set policies on who can request rooms and whether approvals are needed.</p>

        <form method="POST" action="{{ route('classrooms.bookings.settings') }}" class="space-y-4">
            @csrf
            <div class="space-y-3">
                <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer">
                    <input type="checkbox" name="teachers_can_request" value="1" {{ $settings->teachers_can_request ? 'checked' : '' }} class="rounded text-orange-600 mt-0.5">
                    <div>
                        <strong class="text-xs text-slate-800 dark:text-slate-200 block">Teachers Can Request Rooms</strong>
                        <span class="text-[11px] text-slate-400">Allow subject instructors to submit booking requests from their dashboard</span>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer">
                    <input type="checkbox" name="requests_need_approval" value="1" {{ $settings->requests_need_approval ? 'checked' : '' }} class="rounded text-orange-600 mt-0.5">
                    <div>
                        <strong class="text-xs text-slate-800 dark:text-slate-200 block">Requests Require Admin Approval</strong>
                        <span class="text-[11px] text-slate-400">New bookings remain in Pending state until explicitly approved by office</span>
                    </div>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-700">
                <button type="button" onclick="document.getElementById('bookingSettingsModal').classList.add('hidden')" class="px-4 py-2 text-xs font-medium text-slate-600 hover:bg-slate-100 rounded-xl">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-700 rounded-xl transition">Save Rules</button>
            </div>
        </form>
    </div>
</div>
@endsection

