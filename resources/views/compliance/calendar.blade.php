@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="content-header p-3 mb-2 border-bottom bg-white">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h1 class="m-0 text-dark font-weight-bold" style="font-size: 22px;">Compliance Calendar</h1>
                <p class="text-muted small mb-0">Upcoming statutory dates — document expiries, affiliation validity and reporting windows.</p>
            </div>
            <a href="{{ route('compliance.overview') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Overview
            </a>
        </div>
    </div>

    <section class="content px-3 pb-5">
        @include('compliance.partials.subnav')

        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="card-title font-weight-bold m-0 text-dark">Upcoming Statutory Deadlines</h5>
                    <span class="text-muted small ml-2">{{ $events->count() }} events tracked</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-2.5 pl-3" style="width: 140px;">Due Date</th>
                            <th class="py-2.5 text-center" style="width: 90px;">In</th>
                            <th class="py-2.5" style="width: 160px;">Event Type</th>
                            <th class="py-2.5">Event Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $ev)
                            <tr>
                                <td class="py-3 pl-3 font-weight-bold text-dark">
                                    {{ $ev->event_date->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-light border text-muted px-2 py-1 font-weight-bold" style="font-size: 11px;">
                                        {{ $ev->days_remaining }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $ev->type_badge }} px-2 py-1 text-uppercase" style="font-size: 10px;">
                                        {{ $ev->event_type }}
                                    </span>
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $ev->title }}</div>
                                    @if($ev->description)
                                        <div class="text-muted small mt-0.5">{{ $ev->description }}</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No upcoming compliance deadlines logged.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

