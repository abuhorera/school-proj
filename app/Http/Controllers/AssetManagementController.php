<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssetCategory;
use App\Models\SchoolAsset;
use App\Models\AssetAssignment;
use App\Models\AssetDepreciation;
use App\Models\AssetMaintenanceSchedule;
use App\Models\AssetMaintenanceLog;
use App\Models\AssetDisposal;
use App\Models\AssetAudit;
use App\Models\AssetAuditItem;
use App\Models\AssetMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AssetManagementController extends Controller
{
    // =========================================================================
    // 1. ASSET DASHBOARD
    // =========================================================================
    public function dashboard()
    {
        $totalAssets = SchoolAsset::count();
        $totalCost = SchoolAsset::sum('purchase_cost');
        $currentBookValue = SchoolAsset::sum('current_book_value');
        $underMaintenanceCount = SchoolAsset::where('status', 'under_maintenance')->count();

        // Status counts
        $statusCounts = [
            'in_store' => SchoolAsset::where('status', 'in_store')->count(),
            'in_use' => SchoolAsset::where('status', 'in_use')->count(),
            'under_maintenance' => $underMaintenanceCount,
            'retired' => SchoolAsset::where('status', 'retired')->count(),
            'disposed' => SchoolAsset::where('status', 'disposed')->count(),
            'lost' => SchoolAsset::where('status', 'lost')->count(),
        ];

        // Maintenance due & overdue
        $maintenanceDue = AssetMaintenanceSchedule::with('asset')
            ->where('is_active', true)
            ->orderBy('next_due_date', 'asc')
            ->take(5)
            ->get();

        $openLogsCount = AssetMaintenanceLog::whereIn('status', ['reported', 'in_progress'])->count();
        $overdueCount = AssetMaintenanceSchedule::where('is_active', true)
            ->where('next_due_date', '<', Carbon::today())
            ->count();

        // Assets by category
        $categoryBreakdown = AssetCategory::withCount('assets')->get();

        // Warranty expiring in next 30 days
        $warrantyExpiring = SchoolAsset::whereNotNull('warranty_expiry_date')
            ->where('warranty_expiry_date', '>=', Carbon::today())
            ->where('warranty_expiry_date', '<=', Carbon::today()->addDays(60))
            ->orderBy('warranty_expiry_date', 'asc')
            ->take(5)
            ->get();

        // Recently registered assets
        $recentAssets = SchoolAsset::with('category')
            ->orderBy('id', 'desc')
            ->take(6)
            ->get();

        return view('assets.dashboard', compact(
            'totalAssets',
            'totalCost',
            'currentBookValue',
            'underMaintenanceCount',
            'openLogsCount',
            'overdueCount',
            'statusCounts',
            'maintenanceDue',
            'categoryBreakdown',
            'warrantyExpiring',
            'recentAssets'
        ));
    }

    // =========================================================================
    // 2. ASSET REGISTER (INDEX, CREATE, STORE, SHOW, EDIT, UPDATE, DESTROY, TAGS)
    // =========================================================================
    public function assetsIndex(Request $request)
    {
        $query = SchoolAsset::with(['category', 'activeAssignment']);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('asset_tag', 'like', "%{$q}%")
                    ->orWhere('serial_number', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('asset_category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        $assets = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();

        return view('assets.index', compact('assets', 'categories'));
    }

    public function assetCreate()
    {
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();

        // Next tag suggestion
        $lastId = SchoolAsset::max('id') ?? 0;
        $suggestedTag = 'AST-' . str_pad($lastId + 200, 5, '0', STR_PAD_LEFT);

        return view('assets.create', compact('categories', 'suggestedTag'));
    }

    public function assetStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_tag' => 'nullable|string|max:100|unique:school_assets,asset_tag',
            'asset_category_id' => 'nullable|exists:asset_categories,id',
            'brand' => 'nullable|string|max:100',
            'model_number' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'condition' => 'required|string',
            'location' => 'nullable|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
            'funding_source' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'invoice_number' => 'nullable|string|max:100',
            'depreciation_method' => 'nullable|string',
            'depreciation_start_date' => 'nullable|date',
            'useful_life_months' => 'nullable|integer|min:1',
            'salvage_value' => 'nullable|numeric|min:0',
            'depreciation_rate' => 'nullable|numeric|min:0',
            'opening_book_value' => 'nullable|numeric|min:0',
            'warranty_provider' => 'nullable|string|max:255',
            'warranty_expiry_date' => 'nullable|date',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
            'insurance_expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if (empty($validated['asset_tag'])) {
            $lastId = SchoolAsset::max('id') ?? 0;
            $validated['asset_tag'] = 'AST-' . str_pad($lastId + 200, 5, '0', STR_PAD_LEFT);
        }

        $purchaseCost = floatval($validated['purchase_cost'] ?? 0);
        $openingValue = floatval($validated['opening_book_value'] ?? 0);
        $validated['current_book_value'] = $openingValue > 0 ? $openingValue : $purchaseCost;

        $asset = SchoolAsset::create($validated);

        // Movement record
        AssetMovement::create([
            'asset_id' => $asset->id,
            'event_type' => 'Registered',
            'event_date' => $asset->purchase_date ?? Carbon::today(),
            'details' => "Asset registered into inventory with tag {$asset->asset_tag}.",
            'actor' => 'school admin',
        ]);

        return redirect()->route('school.assets.assets.show', $asset->id)
            ->with('success', "Asset {$asset->name} ({$asset->asset_tag}) registered successfully.");
    }

    public function assetShow($id)
    {
        $asset = SchoolAsset::with([
            'category',
            'assignments' => fn($q) => $q->orderBy('id', 'desc'),
            'depreciations' => fn($q) => $q->orderBy('depreciation_date', 'desc'),
            'maintenanceSchedules',
            'maintenanceLogs' => fn($q) => $q->orderBy('reported_date', 'desc'),
            'disposals',
            'movements',
        ])->findOrFail($id);

        return view('assets.show', compact('asset'));
    }

    public function assetEdit($id)
    {
        $asset = SchoolAsset::findOrFail($id);
        $categories = AssetCategory::where('is_active', true)->orderBy('name')->get();

        return view('assets.edit', compact('asset', 'categories'));
    }

    public function assetUpdate(Request $request, $id)
    {
        $asset = SchoolAsset::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_tag' => 'required|string|max:100|unique:school_assets,asset_tag,' . $id,
            'asset_category_id' => 'nullable|exists:asset_categories,id',
            'brand' => 'nullable|string|max:100',
            'model_number' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'condition' => 'required|string',
            'location' => 'nullable|string|max:255',
            'supplier_name' => 'nullable|string|max:255',
            'funding_source' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'invoice_number' => 'nullable|string|max:100',
            'depreciation_method' => 'nullable|string',
            'depreciation_start_date' => 'nullable|date',
            'useful_life_months' => 'nullable|integer|min:1',
            'salvage_value' => 'nullable|numeric|min:0',
            'depreciation_rate' => 'nullable|numeric|min:0',
            'opening_book_value' => 'nullable|numeric|min:0',
            'current_book_value' => 'nullable|numeric|min:0',
            'warranty_provider' => 'nullable|string|max:255',
            'warranty_expiry_date' => 'nullable|date',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
            'insurance_expiry_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $asset->status;
        $asset->update($validated);

        if ($oldStatus !== $asset->status) {
            AssetMovement::create([
                'asset_id' => $asset->id,
                'event_type' => 'Status Changed',
                'event_date' => Carbon::today(),
                'details' => "Status changed from {$oldStatus} to {$asset->status}.",
                'actor' => 'school admin',
            ]);
        }

        return redirect()->route('school.assets.assets.show', $asset->id)
            ->with('success', "Asset {$asset->name} updated successfully.");
    }

    public function assetDestroy($id)
    {
        $asset = SchoolAsset::findOrFail($id);
        $name = $asset->name;
        $asset->delete();

        return redirect()->route('school.assets.assets')
            ->with('success', "Asset {$name} deleted successfully.");
    }

    public function assetTagSingle($id)
    {
        $asset = SchoolAsset::with('category')->findOrFail($id);
        return view('assets.tag_print', ['assets' => collect([$asset])]);
    }

    public function assetTagsPrintSheet(Request $request)
    {
        $query = SchoolAsset::with('category');
        if ($request->filled('category')) {
            $query->where('asset_category_id', $request->category);
        }
        $assets = $query->orderBy('asset_tag')->get();
        return view('assets.tag_print', compact('assets'));
    }

    // =========================================================================
    // 3. CATEGORIES
    // =========================================================================
    public function categoriesIndex()
    {
        $categories = AssetCategory::withCount('assets')->orderBy('name')->get();
        return view('assets.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'default_depreciation_method' => 'required|string',
            'default_useful_life_months' => 'nullable|integer',
            'default_salvage_value_pct' => 'nullable|numeric',
            'default_depreciation_rate' => 'nullable|numeric',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        AssetCategory::create($validated);

        return redirect()->route('school.assets.categories')
            ->with('success', "Category {$validated['name']} created successfully.");
    }

    public function categoryUpdate(Request $request, $id)
    {
        $category = AssetCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'default_depreciation_method' => 'required|string',
            'default_useful_life_months' => 'nullable|integer',
            'default_salvage_value_pct' => 'nullable|numeric',
            'default_depreciation_rate' => 'nullable|numeric',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $category->update($validated);

        return redirect()->route('school.assets.categories')
            ->with('success', "Category {$category->name} updated successfully.");
    }

    public function categoryDestroy($id)
    {
        $category = AssetCategory::withCount('assets')->findOrFail($id);
        if ($category->assets_count > 0) {
            return redirect()->route('school.assets.categories')
                ->with('error', "Cannot delete category with associated assets.");
        }
        $category->delete();
        return redirect()->route('school.assets.categories')
            ->with('success', "Category deleted successfully.");
    }

    // =========================================================================
    // 4. ASSIGNMENTS / ALLOCATIONS
    // =========================================================================
    public function assignmentsIndex(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = AssetAssignment::with('asset');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $assignments = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $availableAssets = SchoolAsset::whereIn('status', ['in_store', 'in_use'])->orderBy('name')->get();

        return view('assets.assignments', compact('assignments', 'availableAssets', 'status'));
    }

    public function assignmentStore(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:school_assets,id',
            'assignable_type' => 'required|string',
            'holder_name' => 'required|string|max:255',
            'assigned_date' => 'required|date',
            'expected_return_date' => 'nullable|date',
            'condition_on_assign' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $validated['status'] = 'active';
        $assignment = AssetAssignment::create($validated);

        // Update asset status
        $asset = SchoolAsset::find($validated['asset_id']);
        $asset->update(['status' => 'in_use']);

        // Log movement
        AssetMovement::create([
            'asset_id' => $asset->id,
            'event_type' => 'Allocated',
            'event_date' => $validated['assigned_date'],
            'details' => "Allocated to {$validated['holder_name']} ({$validated['assignable_type']}).",
            'actor' => 'school admin',
        ]);

        return redirect()->route('school.assets.assignments')
            ->with('success', "Asset allocated successfully to {$validated['holder_name']}.");
    }

    public function assignmentReturn(Request $request, $id)
    {
        $assignment = AssetAssignment::findOrFail($id);

        $validated = $request->validate([
            'returned_date' => 'required|date',
            'condition_on_return' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $assignment->update([
            'returned_date' => $validated['returned_date'],
            'condition_on_return' => $validated['condition_on_return'],
            'status' => 'returned',
            'remarks' => $assignment->remarks . ($validated['remarks'] ? ' | Return note: ' . $validated['remarks'] : ''),
        ]);

        $asset = $assignment->asset;
        if ($asset) {
            $asset->update([
                'status' => 'in_store',
                'condition' => $validated['condition_on_return'],
            ]);

            AssetMovement::create([
                'asset_id' => $asset->id,
                'event_type' => 'Returned',
                'event_date' => $validated['returned_date'],
                'details' => "Returned by {$assignment->holder_name} in {$validated['condition_on_return']} condition.",
                'actor' => 'school admin',
            ]);
        }

        return redirect()->route('school.assets.assignments')
            ->with('success', "Asset custody return marked successfully.");
    }

    public function assignmentTransfer(Request $request, $id)
    {
        $assignment = AssetAssignment::findOrFail($id);

        $validated = $request->validate([
            'assignable_type' => 'required|string',
            'holder_name' => 'required|string|max:255',
            'assigned_date' => 'required|date',
            'expected_return_date' => 'nullable|date',
            'condition_on_assign' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        // Close old assignment as transferred
        $assignment->update([
            'returned_date' => $validated['assigned_date'],
            'status' => 'transferred',
            'remarks' => $assignment->remarks . " | Transferred to {$validated['holder_name']}",
        ]);

        // Create new assignment
        $newAssignment = AssetAssignment::create([
            'asset_id' => $assignment->asset_id,
            'assignable_type' => $validated['assignable_type'],
            'holder_name' => $validated['holder_name'],
            'assigned_date' => $validated['assigned_date'],
            'expected_return_date' => $validated['expected_return_date'],
            'condition_on_assign' => $validated['condition_on_assign'],
            'status' => 'active',
            'remarks' => $validated['remarks'],
        ]);

        $asset = $assignment->asset;
        if ($asset) {
            AssetMovement::create([
                'asset_id' => $asset->id,
                'event_type' => 'Transferred',
                'event_date' => $validated['assigned_date'],
                'details' => "Custody transferred from {$assignment->holder_name} to {$validated['holder_name']}.",
                'actor' => 'school admin',
            ]);
        }

        return redirect()->route('school.assets.assignments')
            ->with('success', "Custody transferred successfully to {$validated['holder_name']}.");
    }

    // =========================================================================
    // 5. DEPRECIATION
    // =========================================================================
    public function depreciationIndex(Request $request)
    {
        $depreciableAssetsCount = SchoolAsset::whereNotIn('status', ['disposed', 'lost'])
            ->where('depreciation_method', '!=', 'None')
            ->count();

        $accumulatedDepreciation = AssetDepreciation::sum('depreciation_amount');
        $netBookValue = SchoolAsset::sum('current_book_value');

        $query = AssetDepreciation::with('asset');
        if ($request->filled('fiscal_year')) {
            $query->where('fiscal_year', $request->fiscal_year);
        }

        $records = $query->orderBy('depreciation_date', 'desc')->paginate(20)->withQueryString();
        $fiscalYears = AssetDepreciation::select('fiscal_year')->distinct()->pluck('fiscal_year');

        return view('assets.depreciation', compact(
            'depreciableAssetsCount',
            'accumulatedDepreciation',
            'netBookValue',
            'records',
            'fiscalYears'
        ));
    }

    public function depreciationRun(Request $request)
    {
        $asOf = $request->input('as_of', Carbon::today()->format('Y-m-d'));
        $date = Carbon::parse($asOf);
        $periodName = $date->format('M Y');
        $fiscalYear = ($date->month >= 4)
            ? $date->year . '-' . ($date->year + 1)
            : ($date->year - 1) . '-' . $date->year;

        $assets = SchoolAsset::whereNotIn('status', ['disposed', 'lost'])
            ->where('depreciation_method', '!=', 'None')
            ->where('current_book_value', '>', DB::raw('salvage_value'))
            ->get();

        $count = 0;
        foreach ($assets as $asset) {
            $opening = $asset->current_book_value;
            $rate = $asset->depreciation_rate > 0 ? $asset->depreciation_rate : 20.0;
            $life = $asset->useful_life_months > 0 ? $asset->useful_life_months : 60;
            $salvage = $asset->salvage_value;

            // Monthly depreciation calculation
            if (str_contains(strtolower($asset->depreciation_method), 'written') || str_contains(strtolower($asset->depreciation_method), 'declining')) {
                // WDV Monthly = Opening * (Rate / 100) / 12
                $depAmount = round(($opening * ($rate / 100)) / 12, 2);
            } else {
                // SLM Monthly = (Cost - Salvage) / Life
                $cost = $asset->purchase_cost > 0 ? $asset->purchase_cost : $opening;
                $depAmount = round(($cost - $salvage) / $life, 2);
            }

            if ($depAmount <= 0) continue;

            $closing = max($salvage, $opening - $depAmount);
            $actualDep = $opening - $closing;

            // Accumulated
            $prevAccumulated = AssetDepreciation::where('asset_id', $asset->id)->sum('depreciation_amount');
            $newAccumulated = $prevAccumulated + $actualDep;

            AssetDepreciation::create([
                'asset_id' => $asset->id,
                'period_name' => $periodName,
                'fiscal_year' => $fiscalYear,
                'depreciation_date' => $asOf,
                'method' => $asset->depreciation_method,
                'opening_value' => $opening,
                'depreciation_amount' => $actualDep,
                'accumulated_depreciation' => $newAccumulated,
                'closing_value' => $closing,
                'posted_by' => 'school admin',
            ]);

            $asset->update(['current_book_value' => $closing]);

            AssetMovement::create([
                'asset_id' => $asset->id,
                'event_type' => 'Depreciated',
                'event_date' => $asOf,
                'details' => "Depreciation of ₹" . number_format($actualDep, 2) . " posted for {$periodName}. Closing book value: ₹" . number_format($closing, 2),
                'actor' => 'school admin',
            ]);

            $count++;
        }

        return redirect()->route('school.assets.depreciation')
            ->with('success', "Depreciation successfully run for {$count} assets for {$periodName}.");
    }

    // =========================================================================
    // 6. MAINTENANCE
    // =========================================================================
    public function maintenanceIndex()
    {
        $schedules = AssetMaintenanceSchedule::with('asset')->orderBy('next_due_date', 'asc')->get();
        $logs = AssetMaintenanceLog::with(['asset', 'schedule'])->orderBy('reported_date', 'desc')->paginate(20);
        $assets = SchoolAsset::whereNotIn('status', ['disposed', 'lost'])->orderBy('name')->get();

        return view('assets.maintenance', compact('schedules', 'logs', 'assets'));
    }

    public function scheduleStore(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:school_assets,id',
            'title' => 'required|string|max:255',
            'maintenance_type' => 'required|string',
            'frequency' => 'required|string',
            'interval_days' => 'required|integer|min:1',
            'next_due_date' => 'required|date',
            'assigned_to' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'estimated_cost' => 'nullable|numeric|min:0',
            'reminder_days_before' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        AssetMaintenanceSchedule::create($validated);

        return redirect()->route('school.assets.maintenance')
            ->with('success', "Maintenance schedule {$validated['title']} added successfully.");
    }

    public function scheduleUpdate(Request $request, $id)
    {
        $schedule = AssetMaintenanceSchedule::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'maintenance_type' => 'required|string',
            'frequency' => 'required|string',
            'interval_days' => 'required|integer|min:1',
            'next_due_date' => 'required|date',
            'assigned_to' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'estimated_cost' => 'nullable|numeric|min:0',
            'reminder_days_before' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $schedule->update($validated);

        return redirect()->route('school.assets.maintenance')
            ->with('success', "Maintenance schedule updated successfully.");
    }

    public function scheduleDestroy($id)
    {
        $schedule = AssetMaintenanceSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('school.assets.maintenance')
            ->with('success', "Maintenance schedule deleted.");
    }

    public function logStore(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:school_assets,id',
            'schedule_id' => 'nullable|exists:asset_maintenance_schedules,id',
            'maintenance_type' => 'required|string',
            'status' => 'required|string',
            'problem_description' => 'required|string',
            'reported_date' => 'required|date',
            'completed_date' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'vendor_name' => 'nullable|string|max:255',
            'action_taken' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $log = AssetMaintenanceLog::create($validated);

        // Update asset status
        $asset = SchoolAsset::find($validated['asset_id']);
        if ($validated['status'] === 'reported' || $validated['status'] === 'in_progress') {
            $asset->update(['status' => 'under_maintenance']);
        }

        AssetMovement::create([
            'asset_id' => $asset->id,
            'event_type' => 'Maintained',
            'event_date' => $validated['reported_date'],
            'details' => "Maintenance log: {$validated['maintenance_type']} - {$validated['problem_description']}",
            'actor' => 'school admin',
        ]);

        return redirect()->route('school.assets.maintenance')
            ->with('success', "Maintenance log created successfully.");
    }

    public function logComplete(Request $request, $id)
    {
        $log = AssetMaintenanceLog::findOrFail($id);

        $validated = $request->validate([
            'completed_date' => 'required|date',
            'cost' => 'nullable|numeric|min:0',
            'action_taken' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $log->update([
            'status' => 'completed',
            'completed_date' => $validated['completed_date'],
            'cost' => $validated['cost'] ?? $log->cost,
            'action_taken' => $validated['action_taken'],
            'remarks' => $log->remarks . ($validated['remarks'] ? ' | ' . $validated['remarks'] : ''),
        ]);

        // Restore asset status if not still under maintenance
        $asset = $log->asset;
        if ($asset) {
            $otherOpen = AssetMaintenanceLog::where('asset_id', $asset->id)
                ->where('id', '!=', $log->id)
                ->whereIn('status', ['reported', 'in_progress'])
                ->exists();

            if (!$otherOpen) {
                $hasActiveAsg = $asset->activeAssignment()->exists();
                $asset->update(['status' => $hasActiveAsg ? 'in_use' : 'in_store']);
            }

            AssetMovement::create([
                'asset_id' => $asset->id,
                'event_type' => 'Maintained',
                'event_date' => $validated['completed_date'],
                'details' => "Maintenance work completed: {$validated['action_taken']}. Cost: ₹" . number_format($log->cost, 2),
                'actor' => 'school admin',
            ]);
        }

        return redirect()->route('school.assets.maintenance')
            ->with('success', "Maintenance log marked as completed.");
    }

    // =========================================================================
    // 7. DISPOSALS
    // =========================================================================
    public function disposalsIndex()
    {
        $disposals = AssetDisposal::with('asset')->orderBy('disposal_date', 'desc')->paginate(20);
        $assets = SchoolAsset::whereNotIn('status', ['disposed', 'lost'])->orderBy('name')->get();

        return view('assets.disposals', compact('disposals', 'assets'));
    }

    public function disposalStore(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:school_assets,id',
            'disposal_method' => 'required|string',
            'disposal_date' => 'required|date',
            'realised_value' => 'nullable|numeric|min:0',
            'recipient' => 'nullable|string|max:255',
            'reason' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $asset = SchoolAsset::findOrFail($validated['asset_id']);
        $bookValue = $asset->current_book_value;
        $realised = floatval($validated['realised_value'] ?? 0);
        $gainLoss = $realised - $bookValue;

        $validated['book_value_at_disposal'] = $bookValue;
        $validated['realised_value'] = $realised;
        $validated['gain_loss'] = $gainLoss;
        $validated['status'] = 'Completed';
        $validated['requested_by'] = 'school admin';

        $disposal = AssetDisposal::create($validated);

        // Update asset
        $asset->update([
            'status' => 'disposed',
            'current_book_value' => 0,
        ]);

        // Release any active assignment
        if ($asset->activeAssignment) {
            $asset->activeAssignment->update([
                'status' => 'returned',
                'returned_date' => $validated['disposal_date'],
                'remarks' => 'Returned due to asset disposal.',
            ]);
        }

        AssetMovement::create([
            'asset_id' => $asset->id,
            'event_type' => 'Disposed',
            'event_date' => $validated['disposal_date'],
            'details' => "Disposed via {$validated['disposal_method']}. Realised: ₹" . number_format($realised, 2) . ", Gain/Loss: ₹" . number_format($gainLoss, 2),
            'actor' => 'school admin',
        ]);

        return redirect()->route('school.assets.disposals')
            ->with('success', "Asset {$asset->name} disposal registered successfully.");
    }

    // =========================================================================
    // 8. AUDITS (PHYSICAL VERIFICATION)
    // =========================================================================
    public function auditsIndex()
    {
        $audits = AssetAudit::withCount('items')->orderBy('id', 'desc')->paginate(20);
        return view('assets.audits', compact('audits'));
    }

    public function auditCreate()
    {
        $locations = SchoolAsset::whereNotNull('location')->distinct()->pluck('location');
        $categories = AssetCategory::orderBy('name')->get();
        return view('assets.audit_create', compact('locations', 'categories'));
    }

    public function auditStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'scope' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $query = SchoolAsset::with('activeAssignment')->whereNotIn('status', ['disposed', 'lost']);
        if ($validated['scope'] !== 'All') {
            if (str_starts_with($validated['scope'], 'Location · ')) {
                $loc = substr($validated['scope'], 11);
                $query->where('location', $loc);
            } elseif (str_starts_with($validated['scope'], 'Category · ')) {
                $cat = substr($validated['scope'], 11);
                $catId = AssetCategory::where('name', $cat)->value('id');
                if ($catId) $query->where('asset_category_id', $catId);
            }
        }

        $assets = $query->get();

        $audit = AssetAudit::create([
            'title' => $validated['title'],
            'scope' => $validated['scope'],
            'total_assets' => $assets->count(),
            'status' => 'Open',
            'conducted_by' => 'school admin',
            'started_at' => Carbon::now(),
            'notes' => $validated['notes'],
        ]);

        foreach ($assets as $asset) {
            AssetAuditItem::create([
                'asset_audit_id' => $audit->id,
                'asset_id' => $asset->id,
                'expected_location' => $asset->location ?? 'Unspecified',
                'expected_holder' => $asset->activeAssignment ? $asset->activeAssignment->holder_name : '—',
                'result' => 'Pending',
                'found_location' => $asset->location,
                'remarks' => null,
            ]);
        }

        return redirect()->route('school.assets.audits.show', $audit->id)
            ->with('success', "Audit session created with {$assets->count()} assets in scope.");
    }

    public function auditShow($id)
    {
        $audit = AssetAudit::with(['items.asset.category'])->findOrFail($id);
        return view('assets.audit_show', compact('audit'));
    }

    public function auditItemVerify(Request $request, $auditId, $itemId)
    {
        $audit = AssetAudit::findOrFail($auditId);
        $item = AssetAuditItem::where('asset_audit_id', $auditId)->findOrFail($itemId);

        $validated = $request->validate([
            'result' => 'required|in:Present,Missing,Damaged,Moved',
            'found_location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $item->update([
            'result' => $validated['result'],
            'found_location' => $validated['found_location'] ?? $item->expected_location,
            'remarks' => $validated['remarks'],
            'verified_by' => 'school admin',
            'verified_at' => Carbon::now(),
        ]);

        // Recompute audit statistics
        $items = $audit->items;
        $audit->update([
            'verified_count' => $items->where('result', '!=', 'Pending')->count(),
            'present_count' => $items->where('result', 'Present')->count(),
            'missing_count' => $items->where('result', 'Missing')->count(),
            'damaged_count' => $items->where('result', 'Damaged')->count(),
            'moved_count' => $items->where('result', 'Moved')->count(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Verification recorded successfully.',
            'audit' => $audit->fresh(),
        ]);
    }

    public function auditComplete(Request $request, $id)
    {
        $audit = AssetAudit::findOrFail($id);
        $audit->update([
            'status' => 'Completed',
            'completed_at' => Carbon::now(),
        ]);

        return redirect()->route('school.assets.audits.show', $audit->id)
            ->with('success', "Audit session marked as Completed.");
    }

    // =========================================================================
    // 9. REPORTS
    // =========================================================================
    public function reportsIndex()
    {
        return view('assets.reports');
    }

    public function reportShow($type)
    {
        $data = $this->getReportData($type);
        return view('assets.report_view', array_merge($data, ['reportType' => $type]));
    }

    public function reportExport($type)
    {
        $data = $this->getReportData($type);
        $filename = "asset_report_{$type}_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $data['columns']);
            foreach ($data['rows'] as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getReportData($type)
    {
        switch ($type) {
            case 'register':
                $items = SchoolAsset::with('category')->orderBy('id')->get();
                $columns = ['Tag', 'Name', 'Category', 'Brand', 'Model', 'Status', 'Condition', 'Location', 'Purchase Cost', 'Book Value'];
                $rows = $items->map(fn($a) => [
                    $a->asset_tag,
                    $a->name,
                    $a->category ? $a->category->name : '—',
                    $a->brand ?? '—',
                    $a->model_number ?? '—',
                    $a->status_label,
                    $a->condition,
                    $a->location ?? '—',
                    number_format($a->purchase_cost, 2),
                    number_format($a->current_book_value, 2),
                ])->toArray();
                return ['title' => 'Asset Register Report', 'columns' => $columns, 'rows' => $rows];

            case 'depreciation':
                $items = AssetDepreciation::with('asset')->orderBy('depreciation_date', 'desc')->get();
                $columns = ['Tag', 'Asset', 'Period', 'FY', 'Method', 'Opening Value', 'Depreciation', 'Accumulated', 'Closing Value'];
                $rows = $items->map(fn($d) => [
                    $d->asset ? $d->asset->asset_tag : '—',
                    $d->asset ? $d->asset->name : '—',
                    $d->period_name,
                    $d->fiscal_year,
                    $d->method,
                    number_format($d->opening_value, 2),
                    number_format($d->depreciation_amount, 2),
                    number_format($d->accumulated_depreciation, 2),
                    number_format($d->closing_value, 2),
                ])->toArray();
                return ['title' => 'Depreciation Register Report', 'columns' => $columns, 'rows' => $rows];

            case 'custody':
                $items = AssetAssignment::with('asset')->orderBy('assigned_date', 'desc')->get();
                $columns = ['Tag', 'Asset', 'Holder', 'Holder Type', 'Assigned Date', 'Expected Return', 'Returned Date', 'Status'];
                $rows = $items->map(fn($c) => [
                    $c->asset ? $c->asset->asset_tag : '—',
                    $c->asset ? $c->asset->name : '—',
                    $c->holder_name,
                    $c->assignable_type,
                    $c->assigned_date ? $c->assigned_date->format('d M Y') : '—',
                    $c->expected_return_date ? $c->expected_return_date->format('d M Y') : '—',
                    $c->returned_date ? $c->returned_date->format('d M Y') : '—',
                    ucfirst($c->status),
                ])->toArray();
                return ['title' => 'Custody / Allocation Register Report', 'columns' => $columns, 'rows' => $rows];

            case 'maintenance':
                $items = AssetMaintenanceLog::with('asset')->orderBy('reported_date', 'desc')->get();
                $columns = ['Tag', 'Asset', 'Type', 'Problem Description', 'Reported Date', 'Completed Date', 'Cost', 'Performed By', 'Status'];
                $rows = $items->map(fn($m) => [
                    $m->asset ? $m->asset->asset_tag : '—',
                    $m->asset ? $m->asset->name : '—',
                    $m->maintenance_type,
                    $m->problem_description,
                    $m->reported_date ? $m->reported_date->format('d M Y') : '—',
                    $m->completed_date ? $m->completed_date->format('d M Y') : '—',
                    number_format($m->cost, 2),
                    $m->performed_by ?? $m->vendor_name ?? '—',
                    ucfirst($m->status),
                ])->toArray();
                return ['title' => 'Maintenance History Report', 'columns' => $columns, 'rows' => $rows];

            case 'warranty':
                $items = SchoolAsset::whereNotNull('warranty_expiry_date')->orderBy('warranty_expiry_date', 'asc')->get();
                $columns = ['Tag', 'Asset', 'Provider', 'Expiry Date', 'Status', 'Condition', 'Location'];
                $rows = $items->map(fn($w) => [
                    $w->asset_tag,
                    $w->name,
                    $w->warranty_provider ?? '—',
                    $w->warranty_expiry_date ? $w->warranty_expiry_date->format('d M Y') : '—',
                    $w->status_label,
                    $w->condition,
                    $w->location ?? '—',
                ])->toArray();
                return ['title' => 'Warranty Expiry Report', 'columns' => $columns, 'rows' => $rows];

            case 'disposals':
                $items = AssetDisposal::with('asset')->orderBy('disposal_date', 'desc')->get();
                $columns = ['Tag', 'Asset', 'Method', 'Disposal Date', 'Realised Value', 'Book Value', 'Gain/Loss', 'Recipient', 'Reason'];
                $rows = $items->map(fn($dp) => [
                    $dp->asset ? $dp->asset->asset_tag : '—',
                    $dp->asset ? $dp->asset->name : '—',
                    $dp->disposal_method,
                    $dp->disposal_date ? $dp->disposal_date->format('d M Y') : '—',
                    number_format($dp->realised_value, 2),
                    number_format($dp->book_value_at_disposal, 2),
                    number_format($dp->gain_loss, 2),
                    $dp->recipient ?? '—',
                    $dp->reason,
                ])->toArray();
                return ['title' => 'Disposal Register Report', 'columns' => $columns, 'rows' => $rows];

            case 'movements':
            default:
                $items = AssetMovement::with('asset')->orderBy('event_date', 'desc')->get();
                $columns = ['Date', 'Tag', 'Asset', 'Event Type', 'Details', 'Actor'];
                $rows = $items->map(fn($mv) => [
                    $mv->event_date ? $mv->event_date->format('d M Y') : '—',
                    $mv->asset ? $mv->asset->asset_tag : '—',
                    $mv->asset ? $mv->asset->name : '—',
                    $mv->event_type,
                    $mv->details,
                    $mv->actor,
                ])->toArray();
                return ['title' => 'Movement Ledger Report', 'columns' => $columns, 'rows' => $rows];
        }
    }
}

