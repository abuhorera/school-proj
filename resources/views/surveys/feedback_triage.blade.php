@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Feedback Triage</h1>
                <p class="text-muted small mb-0">Turn feedback into action — assign an owner, set a status, close the loop.</p>
            </div>
            <a href="{{ route('survey.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> All surveys
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('surveys.partials.subnav')

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <!-- 3 KPI Status Counters -->
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Open Action Items</div>
                    <div class="h2 font-weight-bold text-primary my-1">{{ $stats['open'] }}</div>
                    <div class="small text-muted">Awaiting or in progress</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Urgent Open</div>
                    <div class="h2 font-weight-bold text-danger my-1">{{ $stats['urgent_open'] }}</div>
                    <div class="small text-muted">Critical concerns flagged</div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius: 10px;">
                    <div class="small text-muted font-weight-bold text-uppercase">Assigned to Me</div>
                    <div class="h2 font-weight-bold text-warning my-1">{{ $stats['assigned_to_me'] }}</div>
                    <div class="small text-muted">Tasks under your ownership</div>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 8px;">
            <div class="card-body p-3">
                <form action="{{ route('feedback.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                            <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                            <option value="Reviewed" {{ request('status') == 'Reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="In progress" {{ request('status') == 'In progress' ? 'selected' : '' }}>In progress</option>
                            <option value="Actioned" {{ request('status') == 'Actioned' ? 'selected' : '' }}>Actioned</option>
                            <option value="Dismissed" {{ request('status') == 'Dismissed' ? 'selected' : '' }}>Dismissed</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Priority</label>
                        <select name="priority" class="form-control form-control-sm">
                            <option value="All" {{ request('priority') == 'All' ? 'selected' : '' }}>All</option>
                            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
                            <option value="Normal" {{ request('priority') == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
                            <option value="Urgent" {{ request('priority') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="small text-muted font-weight-bold mb-1">Assignee</label>
                        <select name="assigned_to" class="form-control form-control-sm">
                            <option value="Anyone">Anyone</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ request('assigned_to') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->first_name }} {{ $staff->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6 d-flex align-items-center gap-2 mt-auto" style="padding-top: 24px;">
                        <div class="custom-control custom-checkbox mr-2">
                            <input type="checkbox" name="open_only" value="1" class="custom-control-input" id="open_only" {{ request('open_only') ? 'checked' : '' }}>
                            <label class="custom-control-label small text-muted" for="open_only">Open only</label>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm px-3 flex-grow-1">Filter</button>
                        <a href="{{ route('feedback.index') }}" class="btn btn-light btn-sm border" title="Reset"><i class="fas fa-undo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Triage Items Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">Feedback Actions</h5>
                    <span class="text-muted small ml-2">{{ $items->total() }} items flagged</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3" style="width: 40px;">#</th>
                            <th class="py-2.5">Title</th>
                            <th class="py-2.5">Survey Source</th>
                            <th class="py-2.5 text-center">Priority</th>
                            <th class="py-2.5 text-center">Status</th>
                            <th class="py-2.5">Owner / Assignee</th>
                            <th class="py-2.5">Flagged Date</th>
                            <th class="py-2.5 text-right pr-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $idx => $item)
                            <tr>
                                <td class="py-3 pl-3 text-muted">{{ $idx + 1 + ($items->currentPage() - 1) * $items->perPage() }}</td>
                                <td>
                                    <a href="{{ route('feedback.show', $item->id) }}" class="font-weight-bold text-dark text-decoration-none hover-text-primary">
                                        {{ $item->title }}
                                    </a>
                                    @if($item->tags)
                                        <div class="mt-1">
                                            @foreach(explode(',', $item->tags) as $t)
                                                <span class="badge badge-light border text-muted mr-1" style="font-size: 10px;">{{ trim($t) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($item->survey)
                                        <a href="{{ route('survey.show', $item->survey->id) }}" class="text-secondary small">
                                            {{ $item->survey->title }}
                                        </a>
                                    @else
                                        <span class="text-muted small">Direct Feedback</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->priority_badge }} px-2 py-1 text-uppercase" style="font-size: 10px;">
                                        {{ $item->priority }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $item->status_badge }} px-2 py-1 text-uppercase" style="font-size: 10px;">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->assignee)
                                        <span class="font-weight-bold text-dark small"><i class="fas fa-user-circle mr-1 text-muted"></i> {{ $item->assignee->first_name }} {{ $item->assignee->last_name }}</span>
                                    @else
                                        <span class="text-muted small">— Unassigned —</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $item->flagged_at ? $item->flagged_at->diffForHumans() : $item->created_at->diffForHumans() }}
                                </td>
                                <td class="text-right pr-3">
                                    <a href="{{ route('feedback.show', $item->id) }}" class="btn btn-outline-primary btn-xs px-2.5 py-1">
                                        Manage <i class="fas fa-chevron-right ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No feedback items match the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($items->hasPages())
                <div class="card-footer bg-white border-top py-2">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

