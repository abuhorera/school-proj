@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Inspection Checklists</h1>
                <p class="text-muted small mb-0">Prepare for statutory board inspections, affiliation renewals and safety audits.</p>
            </div>
            <a href="{{ route('compliance.overview') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Overview
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('compliance.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- Progress Banner -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: #f8fafc;">
            <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-3 bg-white rounded-circle shadow-sm text-purple-600 text-center" style="width: 54px; height: 54px;">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-dark mb-1">
                            {{ $checklistType === 'cbse_renewal' ? 'CBSE Affiliation Renewal Progress' : 'Annual Inspection Readiness' }}
                        </h5>
                        <p class="text-muted small mb-0">
                            <strong>{{ $doneCount }}/{{ $totalCount }}</strong> items verified or N/A
                        </p>
                    </div>
                </div>
                <div class="text-right" style="min-width: 180px;">
                    <div class="h2 font-weight-bold text-dark mb-1">{{ $progressPct }}%</div>
                    <div class="progress" style="height: 8px; border-radius: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $progressPct }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checklist Form -->
        <form action="{{ route('compliance.inspections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="checklist" value="{{ $checklistType }}">

            <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title font-weight-bold m-0 text-dark">Statutory Audit Items</h5>
                    <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Progress
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="py-2.5 pl-3" style="width: 45%;">Statutory Checklist Item</th>
                                <th class="py-2.5 text-center" style="width: 20%;">Status</th>
                                <th class="py-2.5" style="width: 35%;">Auditor / Internal Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $it)
                                <tr>
                                    <td class="py-3 pl-3 font-weight-bold text-dark">
                                        {{ $it->item_title }}
                                    </td>
                                    <td class="text-center">
                                        <select name="items[{{ $it->item_key }}][status]" class="form-control form-control-sm text-center font-weight-bold">
                                            <option value="Done" {{ $it->status === 'Done' ? 'selected' : '' }} class="text-success">Done</option>
                                            <option value="Pending" {{ $it->status === 'Pending' ? 'selected' : '' }} class="text-warning">Pending</option>
                                            <option value="N/A" {{ $it->status === 'N/A' ? 'selected' : '' }} class="text-secondary">N/A</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="items[{{ $it->item_key }}][note]" value="{{ $it->notes }}" class="form-control form-control-sm" placeholder="Add observation or certificate reference…">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No checklist items configured for this inspection type.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white border-top py-3 text-right">
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                        <i class="fas fa-save mr-1"></i> Save All Items
                    </button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection

