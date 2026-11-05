@extends('layouts.app')

@section('title', 'Engagement')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-magic text-primary mr-2"></i> Engagement &amp; Creatives</h3>
            <p class="text-muted mb-0 small">Automated birthday wishes, festival greetings cards, and social media creative broadcasts.</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" onclick="alert('Triggering automated birthday wishes...')">
                <i class="fas fa-birthday-cake mr-1"></i> Send Today's Wishes
            </button>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'designs' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.engagement', ['tab' => 'designs']) }}">
                <i class="fas fa-palette mr-1"></i> Creatives &amp; Graphics
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'birthdays' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.engagement', ['tab' => 'birthdays']) }}">
                <i class="fas fa-birthday-cake mr-1"></i> Birthday Manager
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'festivals' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.engagement', ['tab' => 'festivals']) }}">
                <i class="fas fa-gift mr-1"></i> Festival Greetings
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'settings' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.engagement', ['tab' => 'settings']) }}">
                <i class="fas fa-cog mr-1"></i> Auto-Send Settings
            </a>
        </li>
    </ul>

    @if($tab === 'birthdays')
        {{-- Birthday Manager View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-birthday-cake text-danger mr-2"></i> Birthdays Celebrating Today</h5>
                <span class="badge badge-success px-3 py-1 font-weight-bold"><i class="fas fa-robot mr-1"></i> Auto-Wish Scheduled 08:00 AM</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Celebrant Name</th>
                                <th>Role</th>
                                <th>Class / Department</th>
                                <th>Contact No</th>
                                <th>Automated Wish Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($studentsToday as $st)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-user-graduate text-primary mr-1"></i> {{ $st->first_name }} {{ $st->last_name }}</td>
                                    <td><span class="badge badge-info">Student</span></td>
                                    <td>Class 10-A</td>
                                    <td>+91 98765 11223</td>
                                    <td><span class="badge badge-success px-2 py-1"><i class="fab fa-whatsapp mr-1"></i> Sent via WhatsApp</span></td>
                                    <td class="text-right"><button class="btn btn-outline-primary btn-sm">Resend Wish</button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No birthdays today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        {{-- Creatives & Greetings Grid --}}
        <div class="row">
            @php
                $creatives = [
                    ['title' => 'Happy Independence Day Greetings', 'cat' => 'National Festival', 'bg' => 'linear-gradient(135deg, #FF9933 0%, #138808 100%)'],
                    ['title' => 'Warm Diwali Wishes to Families', 'cat' => 'Festival', 'bg' => 'linear-gradient(135deg, #f12711 0%, #f5af19 100%)'],
                    ['title' => 'Teachers Day Special Tribute', 'cat' => 'Academic Celebration', 'bg' => 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)'],
                    ['title' => 'Annual Sports Day Banner', 'cat' => 'Campus Event', 'bg' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)'],
                    ['title' => 'Student Birthday Special Template', 'cat' => 'Personal Wishes', 'bg' => 'linear-gradient(135deg, #8e2de2 0%, #4a00e0 100%)'],
                    ['title' => 'New Year 2027 Welcome Card', 'cat' => 'New Year', 'bg' => 'linear-gradient(135deg, #00c6ff 0%, #0072ff 100%)'],
                ];
            @endphp

            @foreach($creatives as $c)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <div class="p-5 text-white text-center d-flex flex-column justify-content-center" style="background: {{ $c['bg'] }}; min-height: 180px;">
                            <h5 class="font-weight-bold mb-1">{{ $c['title'] }}</h5>
                            <small class="text-light">{{ $c['cat'] }} &middot; YUG INTERNATIONAL SCHOOL</small>
                        </div>
                        <div class="card-body p-3 d-flex justify-content-between align-items-center bg-white">
                            <span class="badge badge-light border text-muted">Ready to Broadcast</span>
                            <div>
                                <button class="btn btn-outline-success btn-sm mr-1"><i class="fab fa-whatsapp mr-1"></i> Send</button>
                                <button class="btn btn-outline-secondary btn-sm"><i class="fas fa-download"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

