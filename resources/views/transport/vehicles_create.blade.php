@extends('layouts.app')

@section('content')
<div class="px-4 py-3 max-w-3xl mx-auto">
    @include('transport.nav')

    <div class="ts-card overflow-hidden">
        <div class="ts-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-van-shuttle text-amber-500"></i>
                Register New School Fleet Vehicle
            </span>
            <a href="{{ route('school.transport.vehicles') }}" class="text-xs text-slate-500 hover:text-slate-900 font-semibold">&larr; Back to Vehicles</a>
        </div>

        <form action="{{ route('school.transport.vehicles.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <!-- Section 1: Vehicle Details -->
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">1. Vehicle Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Vehicle Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="vehicle_number" required placeholder="e.g. CG04AB1234, DL1VB9900" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500 uppercase font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Model / Make</label>
                        <input type="text" name="model" placeholder="e.g. Tata Starbus, Ashok Leyland Lynx" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Seating Capacity</label>
                        <input type="number" name="capacity" min="1" value="40" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- Section 2: Driver Details -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">2. Assigned Driver & Mobile App Access</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Driver Name</label>
                        <input type="text" name="driver_name" placeholder="e.g. Ramesh Kumar" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Driver Mobile Number</label>
                        <input type="text" name="driver_phone" placeholder="e.g. 9826011001" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Set Driver App Password</label>
                        <input type="password" name="driver_password" placeholder="Passcode for Driver App" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <!-- Section 3: Telematics & GPS Tracking -->
            <div class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">3. GPS Hardware / Mobile Telematics</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">GPS Device IMEI (Optional)</label>
                        <input type="text" name="gps_device_imei" placeholder="15-digit hardware IMEI number" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500 font-mono">
                        <p class="text-[10px] text-slate-400 mt-1">Leave blank if driver will use the Mobile GPS tracking app.</p>
                    </div>

                    <div class="flex items-center pt-5">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="tracking_enabled" value="1" checked class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                            <span class="text-xs font-bold text-slate-800">Enable Live GPS Tracking for this vehicle</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section 4: Notes -->
            <div class="pt-4 border-t border-slate-100">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Vehicle Notes / Permit Details</label>
                <textarea name="note" rows="2" placeholder="Insurance expiry, fitness certificate, permit details..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('school.transport.vehicles') }}" class="px-5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold bg-amber-500 text-slate-900 hover:bg-amber-600 shadow-md transition flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Save Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

