@extends('layouts.app')

@section('title', 'Communicate')

@section('content')
<div class="container-fluid px-3 py-3">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-1 font-weight-bold"><i class="fas fa-bullhorn text-primary mr-2"></i> Communicate &amp; Broadcast Hub</h3>
            <p class="text-muted mb-0 small">Campus announcements, holiday calendar, multi-channel SMS/Email broadcasts, and media gallery.</p>
        </div>
        <div>
            <a href="{{ route('modules.communicate', ['tab' => 'broadcast_create']) }}" class="btn btn-primary btn-sm font-weight-bold shadow-sm">
                <i class="fas fa-paper-plane mr-1"></i> Compose Broadcast
            </a>
        </div>
    </div>

    {{-- Sub Navigation Tabs --}}
    <ul class="nav nav-pills mb-3 border-bottom pb-2">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'notices' || empty($tab) ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.communicate', ['tab' => 'notices']) }}">
                <i class="fas fa-clipboard-list mr-1"></i> Notice Board
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'events' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.communicate', ['tab' => 'events']) }}">
                <i class="fas fa-calendar-alt mr-1"></i> Events &amp; Holidays
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'broadcast_create' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.communicate', ['tab' => 'broadcast_create']) }}">
                <i class="fas fa-edit mr-1"></i> Compose Broadcast
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'broadcast_history' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.communicate', ['tab' => 'broadcast_history']) }}">
                <i class="fas fa-history mr-1"></i> Broadcast History
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'gallery' ? 'active font-weight-bold' : 'text-dark' }}" href="{{ route('modules.communicate', ['tab' => 'gallery']) }}">
                <i class="fas fa-images mr-1"></i> Image Gallery
            </a>
        </li>
    </ul>

    @if($tab === 'events')
        {{-- Events View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-calendar-check text-primary mr-2"></i> Campus Events &amp; Academic Holidays</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Event Title</th>
                                <th>Date</th>
                                <th>Location / Venue</th>
                                <th>Description</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $ev)
                                <tr>
                                    <td class="font-weight-bold text-dark"><i class="fas fa-star text-warning mr-1"></i> {{ $ev->title }}</td>
                                    <td><span class="badge badge-light border">{{ $ev->event_date }}</span></td>
                                    <td>{{ $ev->location ?? 'Campus Main' }}</td>
                                    <td class="text-muted small">{{ $ev->description }}</td>
                                    <td><span class="badge badge-success px-2 py-1">{{ ucfirst($ev->status ?? 'Upcoming') }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No events listed.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif($tab === 'broadcast_create')
        {{-- Compose Broadcast View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-paper-plane text-primary mr-2"></i> Compose Multi-Channel Notification</h5>
            </div>
            <div class="card-body">
                <form onsubmit="event.preventDefault(); alert('Broadcast queued successfully to all selected channels!');">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold small text-muted">Audience Segment</label>
                            <select class="form-control form-control-sm">
                                <option>All Students &amp; Parents</option>
                                <option>Only Teaching Faculty</option>
                                <option>Class 10 Board Candidates</option>
                                <option>Bus Route 1 &amp; 2 Passengers</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="font-weight-bold small text-muted">Dispatch Channels</label>
                            <div class="d-flex align-items-center mt-1">
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="chSms" checked>
                                    <label class="custom-control-label small" for="chSms">SMS Gateway</label>
                                </div>
                                <div class="custom-control custom-checkbox mr-3">
                                    <input type="checkbox" class="custom-control-input" id="chWa" checked>
                                    <label class="custom-control-label small text-success" for="chWa">WhatsApp API</label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chEmail" checked>
                                    <label class="custom-control-label small" for="chEmail">Email</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Subject / Broadcast Header</label>
                        <input type="text" class="form-control form-control-sm" placeholder="e.g. Weather Advisory: School Closure tomorrow">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold small text-muted">Message Content</label>
                        <textarea class="form-control form-control-sm" rows="4" placeholder="Type notification message..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm px-4 font-weight-bold">
                        <i class="fas fa-paper-plane mr-1"></i> Send Immediate Broadcast
                    </button>
                </form>
            </div>
        </div>
    @else
        {{-- Notices View --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-clipboard-list text-primary mr-2"></i> Official Digital Notice Board</h5>
                <span class="badge badge-primary px-3 py-1 font-weight-bold">{{ count($notices) }} Active Notices</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 text-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Notice Headline</th>
                                <th>Target Group</th>
                                <th>Publish Date</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notices as $idx => $n)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $n->title }}</div>
                                        <small class="text-muted">{{ Str::limit($n->description, 80) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light border text-uppercase font-weight-bold">{{ $n->target_role ?? 'ALL' }}</span>
                                    </td>
                                    <td><small class="text-muted">{{ $n->published_date }}</small></td>
                                    <td>
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle"></i> Published</span>
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-outline-primary btn-sm px-2"><i class="fas fa-eye mr-1"></i> View</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No notices on notice board.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

