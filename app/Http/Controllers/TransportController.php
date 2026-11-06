<?php

namespace App\Http\Controllers;

use App\Models\TransportVehicle;
use App\Models\TransportRoute;
use App\Models\TransportDriver;
use App\Models\TransportRouteSchedule;
use App\Models\TransportRouteStop;
use App\Models\TransportStudentAssignment;
use App\Models\TransportFeeSetting;
use App\Models\TransportBoardingLog;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransportController extends Controller
{
    // =========================================================================
    // 1. DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        $totalVehicles = TransportVehicle::count();
        $activeRoutes = TransportRoute::where('is_active', true)->count();
        $totalStudents = TransportStudentAssignment::where('status', 'Active')->count();
        $vehiclesOnDuty = TransportVehicle::whereIn('status', ['In Service', 'On a trip', 'Active'])->count();

        $vehicles = TransportVehicle::with('route', 'driver')->get();
        $routes = TransportRoute::with('vehicle', 'stops', 'studentAssignments')->get();

        return view('transport.dashboard', compact(
            'totalVehicles',
            'activeRoutes',
            'totalStudents',
            'vehiclesOnDuty',
            'vehicles',
            'routes'
        ));
    }

    // =========================================================================
    // 2. MANAGE VEHICLES
    // =========================================================================
    public function vehiclesIndex()
    {
        $vehicles = TransportVehicle::with('route', 'driver')->latest()->get();
        return view('transport.vehicles', compact('vehicles'));
    }

    public function vehiclesCreate()
    {
        $drivers = TransportDriver::where('status', 'Active')->orderBy('name')->get();
        return view('transport.vehicles_create', compact('drivers'));
    }

    public function vehiclesStore(Request $request)
    {
        $request->validate([
            'vehicle_number' => 'required|string|max:50',
            'capacity' => 'nullable|integer|min:1',
        ]);

        $v = TransportVehicle::create([
            'vehicle_no' => $request->vehicle_number,
            'vehicle_model' => $request->model,
            'seating_capacity' => $request->capacity ?? 40,
            'driver_name' => $request->driver_name,
            'driver_phone' => $request->driver_phone,
            'driver_password' => $request->driver_password,
            'gps_device_imei' => $request->gps_device_imei,
            'api_token' => $request->api_token ?: ('TKN-' . strtoupper(bin2hex(random_bytes(6)))),
            'tracking_enabled' => $request->has('tracking_enabled'),
            'note' => $request->note,
            'status' => 'In Service',
            'current_latitude' => 21.2514,
            'current_longitude' => 81.6296,
            'speed' => 0,
            'last_ping' => Carbon::now(),
        ]);

        return redirect()->route('school.transport.vehicles')->with('success', "Vehicle {$v->vehicle_no} registered successfully!");
    }

    public function vehiclesEdit($id)
    {
        $vehicle = TransportVehicle::findOrFail($id);
        $drivers = TransportDriver::where('status', 'Active')->orderBy('name')->get();
        return view('transport.vehicles_edit', compact('vehicle', 'drivers'));
    }

    public function vehiclesUpdate(Request $request, $id)
    {
        $vehicle = TransportVehicle::findOrFail($id);
        $request->validate([
            'vehicle_number' => 'required|string|max:50',
        ]);

        $vehicle->update([
            'vehicle_no' => $request->vehicle_number,
            'vehicle_model' => $request->model,
            'seating_capacity' => $request->capacity ?? $vehicle->seating_capacity,
            'driver_name' => $request->driver_name,
            'driver_phone' => $request->driver_phone,
            'gps_device_imei' => $request->gps_device_imei,
            'tracking_enabled' => $request->has('tracking_enabled'),
            'note' => $request->note,
            'status' => $request->status ?: $vehicle->status,
        ]);

        return redirect()->route('school.transport.vehicles')->with('success', "Vehicle {$vehicle->vehicle_no} updated successfully!");
    }

    public function vehiclesDestroy($id)
    {
        $vehicle = TransportVehicle::findOrFail($id);
        $num = $vehicle->vehicle_no;
        $vehicle->delete();

        return redirect()->route('school.transport.vehicles')->with('success', "Vehicle {$num} removed.");
    }

    // =========================================================================
    // 3. MANAGE ROUTES
    // =========================================================================
    public function routesIndex()
    {
        $routes = TransportRoute::with('vehicle', 'stops', 'studentAssignments')->get();
        return view('transport.routes', compact('routes'));
    }

    public function routesCreate()
    {
        $vehicles = TransportVehicle::orderBy('vehicle_no')->get();
        return view('transport.routes_create', compact('vehicles'));
    }

    public function routesStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'fare' => 'required|numeric|min:0',
        ]);

        $route = TransportRoute::create([
            'route_title' => $request->name,
            'name' => $request->name,
            'vehicle_id' => $request->vehicle_id,
            'fare' => $request->fare,
            'start_point' => 'School Campus',
            'end_point' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->route('school.transport.routes.show', $route->id)->with('success', "Route '{$route->name}' created! Please add stops and schedules.");
    }

    public function routesEdit($id)
    {
        $route = TransportRoute::findOrFail($id);
        $vehicles = TransportVehicle::orderBy('vehicle_no')->get();
        return view('transport.routes_edit', compact('route', 'vehicles'));
    }

    public function routesUpdate(Request $request, $id)
    {
        $route = TransportRoute::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:150',
            'fare' => 'required|numeric|min:0',
        ]);

        $route->update([
            'route_title' => $request->name,
            'name' => $request->name,
            'vehicle_id' => $request->vehicle_id,
            'fare' => $request->fare,
        ]);

        return redirect()->route('school.transport.routes')->with('success', "Route '{$route->name}' updated!");
    }

    public function routesDestroy($id)
    {
        $route = TransportRoute::findOrFail($id);
        $name = $route->name;
        $route->delete();

        return redirect()->route('school.transport.routes')->with('success', "Route '{$name}' deleted.");
    }

    // =========================================================================
    // 4. ROUTE DETAILS (STOPS, SCHEDULES, ASSIGNED STUDENTS)
    // =========================================================================
    public function routesShow($id)
    {
        $route = TransportRoute::with([
            'vehicle',
            'schedules',
            'stops.students.student',
            'studentAssignments.student',
            'studentAssignments.stop'
        ])->findOrFail($id);

        $students = Student::orderBy('first_name')->take(60)->get();

        return view('transport.routes_show', compact('route', 'students'));
    }

    public function scheduleStore(Request $request, $routeId)
    {
        $request->validate([
            'label' => 'required|string',
            'direction' => 'required|in:Inbound,Outbound,Both',
            'start_time' => 'required|string',
        ]);

        TransportRouteSchedule::create([
            'transport_route_id' => $routeId,
            'label' => $request->label,
            'direction' => $request->direction,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'days_of_week_json' => $request->days_of_week ?: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            'status' => 'Active',
        ]);

        return redirect()->route('school.transport.routes.show', $routeId)->with('success', 'Run schedule added!');
    }

    public function scheduleDestroy($id)
    {
        $s = TransportRouteSchedule::findOrFail($id);
        $rId = $s->transport_route_id;
        $s->delete();

        return redirect()->route('school.transport.routes.show', $rId)->with('success', 'Run schedule removed.');
    }

    public function stopStore(Request $request, $routeId)
    {
        $request->validate([
            'name' => 'required|string|max:150',
        ]);

        $route = TransportRoute::findOrFail($routeId);

        TransportRouteStop::create([
            'transport_route_id' => $routeId,
            'name' => $request->name,
            'latitude' => $request->latitude ?? 21.2480,
            'longitude' => $request->longitude ?? 81.6320,
            'fare' => $request->fare ?: $route->fare,
            'pickup_time' => $request->pickup_time,
            'drop_time' => $request->drop_time,
            'order_sequence' => TransportRouteStop::where('transport_route_id', $routeId)->count() + 1,
        ]);

        return redirect()->route('school.transport.routes.show', $routeId)->with('success', "Stop '{$request->name}' added!");
    }

    public function stopDestroy($id)
    {
        $stop = TransportRouteStop::findOrFail($id);
        $rId = $stop->transport_route_id;
        $stop->delete();

        return redirect()->route('school.transport.routes.show', $rId)->with('success', 'Stop deleted.');
    }

    public function assignStudent(Request $request, $routeId)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'route_stop_id' => 'required|exists:transport_route_stops,id',
        ]);

        TransportStudentAssignment::create([
            'student_id' => $request->student_id,
            'transport_route_id' => $routeId,
            'transport_route_stop_id' => $request->route_stop_id,
            'start_date' => Carbon::today(),
            'status' => 'Active',
            'fee_status' => 'Paid',
        ]);

        return redirect()->route('school.transport.routes.show', $routeId)->with('success', 'Student assigned to route and stop successfully!');
    }

    public function removeStudent($id)
    {
        $assign = TransportStudentAssignment::findOrFail($id);
        $rId = $assign->transport_route_id;
        $assign->delete();

        return redirect()->route('school.transport.routes.show', $rId)->with('success', 'Student removed from route.');
    }

    // =========================================================================
    // 5. LIVE OPERATIONS & FLEET MONITOR
    // =========================================================================
    public function liveOperations()
    {
        $vehicles = TransportVehicle::with('route', 'driver')->get();
        return view('transport.live', compact('vehicles'));
    }

    // =========================================================================
    // 6. LIVE VEHICLE TRACKING
    // =========================================================================
    public function liveTracking(Request $request)
    {
        $vehicles = TransportVehicle::where('tracking_enabled', true)->with('route', 'driver')->get();
        $selectedVehicle = null;

        if ($request->filled('vehicle_id')) {
            $selectedVehicle = TransportVehicle::with('route.stops', 'driver')->find($request->vehicle_id);
        }
        if (!$selectedVehicle && $vehicles->isNotEmpty()) {
            $selectedVehicle = $vehicles->first();
        }

        return view('transport.tracking', compact('vehicles', 'selectedVehicle'));
    }

    // =========================================================================
    // 7. DRIVERS DIRECTORY
    // =========================================================================
    public function driversIndex(Request $request)
    {
        $query = TransportDriver::with('vehicles');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('driver_id_code', 'like', "%{$s}%")
                  ->orWhere('license_number', 'like', "%{$s}%");
            });
        }

        $drivers = $query->latest()->paginate(15)->withQueryString();
        $counts = [
            'all' => TransportDriver::count(),
            'active' => TransportDriver::where('status', 'Active')->count(),
            'suspended' => TransportDriver::where('status', 'Suspended')->count(),
            'left' => TransportDriver::where('status', 'Left')->count(),
        ];

        return view('transport.drivers', compact('drivers', 'counts'));
    }

    public function driversStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
        ]);

        $code = '6649-' . rand(1000, 9999);

        TransportDriver::create([
            'name' => $request->name,
            'driver_id_code' => $code,
            'phone' => $request->phone,
            'license_number' => $request->license_number,
            'license_expiry' => $request->license_expiry,
            'address' => $request->address,
            'status' => $request->status ?: 'Active',
            'device_info' => $request->device_info ?: 'Android Device',
        ]);

        return redirect()->route('school.transport.drivers')->with('success', "Driver '{$request->name}' registered with ID {$code}!");
    }

    public function driversUpdate(Request $request, $id)
    {
        $driver = TransportDriver::findOrFail($id);
        $driver->update($request->only('name', 'phone', 'license_number', 'license_expiry', 'address', 'status', 'device_info'));

        return redirect()->route('school.transport.drivers')->with('success', "Driver '{$driver->name}' updated!");
    }

    public function driversDestroy($id)
    {
        $driver = TransportDriver::findOrFail($id);
        $driver->delete();

        return redirect()->route('school.transport.drivers')->with('success', 'Driver record deleted.');
    }

    // =========================================================================
    // 8. FEE SETTINGS
    // =========================================================================
    public function feeSettings()
    {
        $settings = TransportFeeSetting::first() ?? new TransportFeeSetting([
            'billing_months' => 10,
            'session_start_month' => 'April',
            'due_day_of_month' => 10,
            'auto_assign_on_route' => true,
            'is_active' => true,
        ]);

        $feeTypes = \App\Models\FeeType::all();
        $feeGroups = \App\Models\FeeGroup::all();
        $activeRidersCount = TransportStudentAssignment::where('status', 'Active')->count();

        return view('transport.fee_settings', compact('settings', 'feeTypes', 'feeGroups', 'activeRidersCount'));
    }

    public function feeSettingsStore(Request $request)
    {
        $settings = TransportFeeSetting::first();
        if (!$settings) {
            $settings = new TransportFeeSetting();
        }

        $settings->fill([
            'fee_type_id' => $request->fee_type_id,
            'fee_group_id' => $request->fee_group_id,
            'billing_months' => $request->billing_months ?? 10,
            'session_start_month' => $request->session_start_date ?? 'April',
            'due_day_of_month' => $request->due_day_of_month ?? 10,
            'auto_assign_on_route' => $request->has('auto_assign_on_route') || $request->auto_assign_on_route == '1',
            'is_active' => $request->has('is_active') || $request->is_active == '1',
        ]);
        $settings->save();

        return redirect()->route('school.transport.fee-settings')->with('success', 'Transport billing & fee settings updated successfully!');
    }

    // =========================================================================
    // 9. BOARDING REGISTER & GUIDE
    // =========================================================================
    public function boardingReport(Request $request)
    {
        $routes = TransportRoute::with('stops', 'studentAssignments.student')->get();
        $date = $request->date ?: Carbon::today()->format('Y-m-d');
        $selectedRouteId = $request->route_id ?: ($routes->first()->id ?? null);

        $selectedRoute = TransportRoute::with('stops', 'studentAssignments.student', 'vehicle')->find($selectedRouteId);

        return view('transport.boarding_report', compact('routes', 'date', 'selectedRoute'));
    }

    public function guide()
    {
        return view('transport.guide');
    }
}
