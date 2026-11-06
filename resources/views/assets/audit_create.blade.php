@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="ast-card" style="max-width: 800px; margin: 0 auto;">
        <div class="ast-card-header">
            <h4 class="ast-card-title"><i class="fas fa-clipboard-list text-primary"></i> Start New Verification Session</h4>
        </div>
        <div class="ast-card-body">
            <form method="POST" action="{{ route('school.assets.audits.store') }}">
                @csrf

                <div class="form-group">
                    <label class="font-weight-600">Audit Session Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Annual Stock Verification 2026, Q4 IT Lab Sweep" required>
                </div>

                <div class="form-group">
                    <label class="font-weight-600">Audit Scope <span class="text-danger">*</span></label>
                    <select name="scope" class="form-control" required>
                        <option value="All">All Campus Assets</option>
                        <optgroup label="By Physical Location">
                            @foreach($locations as $loc)
                                <option value="Location · {{ $loc }}">Location · {{ $loc }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="By Category">
                            @foreach($categories as $cat)
                                <option value="Category · {{ $cat->name }}">Category · {{ $cat->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <small class="text-muted">Only active and in-store assets matching this scope will be populated into the audit items list.</small>
                </div>

                <div class="form-group">
                    <label class="font-weight-600">Auditor Notes / Instructions</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Target rooms, verification instructions or team members assigned..."></textarea>
                </div>

                <div class="d-flex justify-content-end align-items-center mt-4" style="gap: 10px;">
                    <a href="{{ route('school.assets.audits') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-ast-primary">
                        <i class="fas fa-play mr-1"></i> Start Verification Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

