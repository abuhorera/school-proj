@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap">
            <div>
                <h3 class="font-weight-bold text-dark mb-0">Biometric Devices &mdash; Recognition Readiness</h3>
                <p class="text-muted small mb-0">Face biometric image quality audit and enrollment readiness breakdown by class &amp; section.</p>
            </div>
            <div>
                <a href="{{ route('biometric.devices') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Devices
                </a>
            </div>
        </div>

        @include('biometric.partials.subnav')
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Top Metrics Cards -->
        @php
            $totalPupils = $readinessData->sum('pupils');
            $totalEnrolled = $readinessData->sum('enrolled');
            $totalNoPhoto = $readinessData->sum('no_photo');
            $totalNotUsable = $readinessData->sum('not_usable');
            $overallPercent = $totalPupils > 0 ? round(($totalEnrolled / $totalPupils) * 100) : 0;
        @endphp

        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #007bff !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Total Pupils</span>
                    <h3 class="font-weight-bold text-dark mb-0 mt-1">{{ number_format($totalPupils) }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #28a745 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Enrolled (Ready)</span>
                    <h3 class="font-weight-bold text-success mb-0 mt-1">{{ number_format($totalEnrolled) }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #dc3545 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Needs Attention</span>
                    <h3 class="font-weight-bold text-danger mb-0 mt-1">{{ number_format($totalNoPhoto + $totalNotUsable) }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="card border-0 shadow-sm text-center py-3" style="border-radius: 12px; border-left: 4px solid #17a2b8 !important;">
                    <span class="text-muted small font-weight-bold text-uppercase">Overall Readiness</span>
                    <h3 class="font-weight-bold text-info mb-0 mt-1">{{ $overallPercent }}%</h3>
                </div>
            </div>
        </div>

        <!-- Class-by-Class Table -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title font-weight-bold text-dark mb-0">By Class &mdash; Least Ready First</h5>
                    <small class="text-muted">Biometric face template enrollment coverage</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="pl-4">Class</th>
                                <th>Pupils</th>
                                <th>Enrolled</th>
                                <th>No Photo</th>
                                <th>Photo Not Usable</th>
                                <th style="width: 220px;">Readiness</th>
                                <th class="pr-4 text-right">Needs Attention</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($readinessData->sortBy('percent') as $item)
                                <tr>
                                    <td class="pl-4 font-weight-bold text-dark">{{ $item['class_name'] }}</td>
                                    <td>{{ $item['pupils'] }}</td>
                                    <td><span class="text-success font-weight-bold">{{ $item['enrolled'] }}</span></td>
                                    <td><span class="{{ $item['no_photo'] > 0 ? 'text-danger' : 'text-muted' }}">{{ $item['no_photo'] }}</span></td>
                                    <td><span class="{{ $item['not_usable'] > 0 ? 'text-warning' : 'text-muted' }}">{{ $item['not_usable'] }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="small font-weight-bold mr-2 text-dark" style="width: 38px;">{{ $item['percent'] }}%</span>
                                            <div class="progress flex-fill" style="height: 7px; border-radius: 4px;">
                                                <div class="progress-bar {{ $item['percent'] >= 85 ? 'bg-success' : ($item['percent'] >= 60 ? 'bg-warning' : 'bg-danger') }}" 
                                                     style="width: {{ $item['percent'] }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="pr-4 text-right">
                                        @if($item['needs_attention'] > 0)
                                            <span class="badge badge-light border text-danger font-weight-bold px-2 py-1">
                                                {{ $item['needs_attention'] }} pupils
                                            </span>
                                        @else
                                            <span class="badge badge-light border text-success px-2 py-1">
                                                <i class="fas fa-check mr-1"></i> Complete
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

