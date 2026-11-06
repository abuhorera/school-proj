@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="ast-card">
        <div class="ast-card-header flex-wrap" style="gap: 10px;">
            <div>
                <h4 class="ast-card-title"><i class="fas fa-file-alt text-primary"></i> {{ $title }}</h4>
                <div class="text-muted" style="font-size: 0.8rem;">
                    Generated on {{ date('d M Y H:i') }} · <strong>{{ count($rows) }}</strong> records found
                </div>
            </div>
            <div class="d-flex align-items-center" style="gap: 8px;">
                <input type="text" id="reportFilter" class="form-control form-control-sm" placeholder="Search report..." style="width: 200px;">
                <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-print mr-1"></i> Print
                </button>
                <a href="{{ route('school.assets.reports.export', $reportType) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-file-csv mr-1"></i> Export CSV
                </a>
            </div>
        </div>

        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table" id="reportTable">
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            <th>{{ $col }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            @foreach($row as $cell)
                                <td>{{ $cell }}</td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}" class="text-center text-muted py-5">
                                No records found for this report.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('reportFilter');
        var table = document.getElementById('reportTable');
        if (input && table) {
            input.addEventListener('keyup', function() {
                var filter = this.value.toLowerCase();
                var trs = table.getElementsByTagName('tr');
                for (var i = 1; i < trs.length; i++) {
                    var txt = trs[i].innerText.toLowerCase();
                    trs[i].style.display = txt.includes(filter) ? '' : 'none';
                }
            });
        }
    });
</script>
@endpush
@endsection

