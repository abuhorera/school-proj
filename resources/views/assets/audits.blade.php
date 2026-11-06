@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="ast-card">
        <div class="ast-card-header flex-wrap" style="gap: 10px;">
            <h4 class="ast-card-title"><i class="fas fa-clipboard-check text-primary"></i> Physical Verification Sessions</h4>
            <a href="{{ route('school.assets.audits.create') }}" class="btn btn-sm btn-ast-primary">
                <i class="fas fa-plus mr-1"></i> New Audit Session
            </a>
        </div>

        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Scope</th>
                        <th>Assets</th>
                        <th>Status</th>
                        <th>Conducted By</th>
                        <th>Started</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($audits as $audit)
                        <tr>
                            <td>
                                <a href="{{ route('school.assets.audits.show', $audit->id) }}" class="font-weight-600 text-dark" style="text-decoration: none;">
                                    {{ $audit->title }}
                                </a>
                                @if($audit->notes)
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($audit->notes, 40) }}</div>
                                @endif
                            </td>
                            <td><span class="badge badge-light border">{{ $audit->scope }}</span></td>
                            <td>
                                <strong>{{ $audit->verified_count }}</strong> / {{ $audit->total_assets }}
                                @if($audit->missing_count > 0)
                                    <span class="badge badge-danger ml-1">{{ $audit->missing_count }} missing</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $audit->status === 'Completed' ? 'badge-secondary' : 'badge-success' }}">
                                    {{ $audit->status }}
                                </span>
                            </td>
                            <td>{{ $audit->conducted_by }}</td>
                            <td>{{ $audit->started_at ? $audit->started_at->format('d M Y H:i') : '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('school.assets.audits.show', $audit->id) }}" class="btn btn-xs {{ $audit->status === 'Open' ? 'btn-ast-primary' : 'btn-outline-secondary' }}">
                                    {{ $audit->status === 'Open' ? 'Verify' : 'View' }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                No physical verification audits created yet. Click "New Audit Session" above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($audits->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $audits->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

