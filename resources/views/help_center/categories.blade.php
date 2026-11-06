@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    @include('help_center.nav')

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="border-radius:10px;">
            <i class="fas fa-check-circle mr-2 fa-lg"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close" style="outline:none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Card 1: School's Own Categories -->
    <div class="tpl-card no-hover mb-4">
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
            <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center">
                <i class="fas fa-school mr-2 text-sky-600"></i>
                Your School Categories
            </h5>
            <span class="tpl-chip is-blue">
                {{ $schoolCategories->count() }}
            </span>
        </div>
        <div class="table-responsive">
            <table class="table tpl-table mb-0">
                <thead>
                    <tr>
                        <th width="70" class="text-center">Order</th>
                        <th>Name</th>
                        <th>Visibility</th>
                        <th class="text-center">Articles</th>
                        <th>Status</th>
                        <th class="text-right" width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schoolCategories as $cat)
                        <tr>
                            <td class="text-center font-weight-bold text-muted">{{ $cat->sort_order }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-2 text-sky-600">
                                        <i class="{{ $cat->icon ?: 'fas fa-folder' }} fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-600 text-dark">{{ $cat->name }}</div>
                                        @if($cat->description)
                                            <small class="text-muted">{{ Str::limit($cat->description, 60) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($cat->visibility == 'public')
                                    <span class="tpl-chip is-emerald">Public</span>
                                @elseif($cat->visibility == 'staff')
                                    <span class="tpl-chip is-indigo">Staff Only</span>
                                @else
                                    <span class="tpl-chip is-amber">Admin Only</span>
                                @endif
                            </td>
                            <td class="text-center font-weight-bold text-slate-700">
                                {{ $cat->articles_count ?? $cat->articles->count() }}
                            </td>
                            <td>
                                @if($cat->is_active)
                                    <span class="badge badge-success px-2 py-1" style="border-radius:6px;">Active</span>
                                @else
                                    <span class="badge badge-secondary px-2 py-1" style="border-radius:6px;">Inactive</span>
                                @endif
                            </td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('school.help-center.categories.edit', $cat->id) }}" class="tpl-icon-btn mr-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('school.help-center.categories.destroy', $cat->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tpl-icon-btn text-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="p-5 text-center" colspan="6">
                                <div class="text-center py-4">
                                    <div class="mb-3 text-slate-300">
                                        <i class="fas fa-layer-group fa-3x"></i>
                                    </div>
                                    <h6 class="font-weight-bold text-slate-700">No school categories yet</h6>
                                    <p class="text-muted text-sm mb-3">Create custom categories to organize help articles written specifically for your school campus.</p>
                                    <a class="btn-kb-primary" href="{{ route('school.help-center.categories.create') }}">
                                        <i class="fas fa-plus mr-1"></i> Add Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Card 2: Platform System Categories (Read-Only) -->
    <div class="tpl-card no-hover">
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom bg-slate-50">
            <h5 class="mb-0 font-weight-bold text-dark d-flex align-items-center">
                <i class="fas fa-globe mr-2 text-indigo-600"></i>
                Platform Standard Categories
            </h5>
            <span class="tpl-chip is-indigo">
                <i class="fas fa-lock mr-1"></i> Read-only Platform Taxonomy
            </span>
        </div>
        <div class="table-responsive">
            <table class="table tpl-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Visibility</th>
                        <th class="text-center">Articles</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $platformCategories = [
                            ['icon' => 'fas fa-rocket', 'name' => 'Getting Started', 'articles' => 3, 'desc' => 'Initial campus onboarding, ERP tour, and admin configuration guides.'],
                            ['icon' => 'fas fa-tachometer-alt', 'name' => 'Dashboard & Navigation', 'articles' => 2, 'desc' => 'Customizing shortcuts, metric widgets, and quick action bars.'],
                            ['icon' => 'fas fa-user-graduate', 'name' => 'Student Management & Admissions', 'articles' => 4, 'desc' => 'Student profile dossiers, biometric enrollment, and roll allocation.'],
                            ['icon' => 'fas fa-calendar-check', 'name' => 'Attendance & Leave Registry', 'articles' => 3, 'desc' => 'Daily roll calls, subject biometric logs, and staff approval flows.'],
                            ['icon' => 'fas fa-money-bill-wave', 'name' => 'Fee Collection & Finance Ledger', 'articles' => 5, 'desc' => 'Fee heads, automated discount waivers, receipt printing, and dues.'],
                            ['icon' => 'fas fa-graduation-cap', 'name' => 'Examinations & Report Cards', 'articles' => 3, 'desc' => 'Mark sheets, grade scaling, hall tickets, and report PDF templates.'],
                            ['icon' => 'fas fa-bus-alt', 'name' => 'Transport & Vehicle Tracking', 'articles' => 2, 'desc' => 'Routes, pickup stops, vehicle telemetry, and student driver logs.'],
                            ['icon' => 'fas fa-hotel', 'name' => 'Hostel & Residential Accommodation', 'articles' => 2, 'desc' => 'Block allocation, room inventory, warden logs, and vacate workflows.']
                        ];
                    @endphp
                    @foreach($platformCategories as $pCat)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="mr-3 text-indigo-500">
                                        <i class="{{ $pCat['icon'] }} fa-lg"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-600 text-dark">{{ $pCat['name'] }}</span>
                                        <br>
                                        <small class="text-muted">{{ $pCat['desc'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="tpl-chip is-emerald">Public</span>
                            </td>
                            <td class="text-center font-weight-bold text-slate-700">
                                {{ $pCat['articles'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

