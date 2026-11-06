@extends('layouts.app')

@section('content')
@include('engagement.nav')

<div class="eng-container">
    {{-- Filter Pills --}}
    <div class="d-flex align-items-center mb-4 flex-wrap" style="gap: 8px;">
        <a href="{{ route('school.engagement.birthdays', ['window' => 'today']) }}" class="tpl-pill {{ $window === 'today' ? 'active' : '' }}">
            Today <span class="tpl-pill-count">{{ $todayCount }}</span>
        </a>
        <a href="{{ route('school.engagement.birthdays', ['window' => 'week']) }}" class="tpl-pill {{ $window === 'week' ? 'active' : '' }}">
            Next 7 days <span class="tpl-pill-count">{{ $weekCount }}</span>
        </a>
        <a href="{{ route('school.engagement.birthdays', ['window' => 'month']) }}" class="tpl-pill {{ $window === 'month' ? 'active' : '' }}">
            This month <span class="tpl-pill-count">{{ $monthCount }}</span>
        </a>
    </div>

    {{-- Upcoming Birthdays Table --}}
    <div class="tpl-card mb-4 no-hover">
        <div class="tpl-card-head d-flex align-items-center justify-content-between p-3" style="border-bottom: 1px solid #e2e8f0;">
            <h6 class="tpl-card-title mb-0 font-weight-bold">
                <i class="fas fa-birthday-cake text-warning mr-1"></i> Upcoming Birthdays
            </h6>
            <span class="text-muted small">Showing {{ $filteredBirthdays->count() }} birthdays in selected range</span>
        </div>
        <div class="table-responsive">
            <table class="table tpl-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Who</th>
                        <th>Birthday</th>
                        <th>Turning</th>
                        <th>When</th>
                        <th>Status</th>
                        <th class="text-right">Card</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($filteredBirthdays as $bday)
                        <tr>
                            <td class="font-weight-bold text-dark">
                                {{ $bday['name'] }}
                            </td>
                            <td>
                                @if($bday['type'] === 'Student')
                                    <span class="tpl-chip is-green">Student</span>
                                @else
                                    <span class="tpl-chip is-blue">Staff</span>
                                @endif
                                <span class="text-muted ml-1 small">{{ $bday['subtext'] }}</span>
                            </td>
                            <td>{{ $bday['birthday_str'] }}</td>
                            <td>
                                <span class="badge badge-light border font-weight-bold">{{ $bday['turning'] }}</span>
                            </td>
                            <td>
                                @if($bday['is_today'])
                                    <span class="tpl-chip is-amber font-weight-bold">Today 🎂</span>
                                @elseif($bday['diff_days'] === 1)
                                    <span class="tpl-chip is-purple">Tomorrow</span>
                                @else
                                    <span class="tpl-chip is-muted">in {{ $bday['diff_days'] }} days</span>
                                @endif
                            </td>
                            <td>
                                <span class="tpl-chip is-muted">{{ $bday['status'] }}</span>
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    @php
                                        $firstDesign = $birthdayDesigns->first();
                                    @endphp
                                    @if($firstDesign)
                                        <a href="{{ route('school.engagement.designs.preview', ['id' => $firstDesign->id, 'student_id' => $bday['id']]) }}" class="btn btn-sm tpl-btn-ghost" title="Preview & Personalise">
                                            <i class="fas fa-image mr-1"></i> Card
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-sm tpl-btn-primary" data-toggle="modal" data-target="#sendGreetingModal-{{ $bday['type'] }}-{{ $bday['id'] }}">
                                        <i class="fas fa-paper-plane mr-1"></i> Send
                                    </button>
                                </div>

                                {{-- Send Greeting Modal --}}
                                <div class="modal fade text-left" id="sendGreetingModal-{{ $bday['type'] }}-{{ $bday['id'] }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
                                            <form action="{{ route('school.engagement.birthdays.send') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="recipient_name" value="{{ $bday['name'] }}">
                                                <input type="hidden" name="recipient_type" value="{{ $bday['type'] }}">
                                                <input type="hidden" name="recipient_id" value="{{ $bday['id'] }}">

                                                <div class="modal-header border-bottom-0 pb-0">
                                                    <h5 class="modal-title font-weight-bold">
                                                        <i class="fas fa-birthday-cake text-warning mr-1"></i> Send Birthday Greeting
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-muted small mb-3">
                                                        Dispatch a personalised birthday card with blessings to <strong>{{ $bday['name'] }}</strong> ({{ $bday['subtext'] }}).
                                                    </p>
                                                    <div class="form-group mb-3">
                                                        <label class="font-weight-semibold small text-muted">Select Card Design</label>
                                                        <select name="design_id" class="form-control">
                                                            @foreach($birthdayDesigns as $d)
                                                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="font-weight-semibold small text-muted">Delivery Channels</label>
                                                        <div class="d-flex flex-wrap" style="gap: 10px;">
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="ch-wa-{{ $bday['type'] }}-{{ $bday['id'] }}" checked>
                                                                <label class="custom-control-label small" for="ch-wa-{{ $bday['type'] }}-{{ $bday['id'] }}">WhatsApp</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="ch-em-{{ $bday['type'] }}-{{ $bday['id'] }}" checked>
                                                                <label class="custom-control-label small" for="ch-em-{{ $bday['type'] }}-{{ $bday['id'] }}">Email</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="ch-sms-{{ $bday['type'] }}-{{ $bday['id'] }}" checked>
                                                                <label class="custom-control-label small" for="ch-sms-{{ $bday['type'] }}-{{ $bday['id'] }}">SMS</label>
                                                            </div>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="ch-push-{{ $bday['type'] }}-{{ $bday['id'] }}" checked>
                                                                <label class="custom-control-label small" for="ch-push-{{ $bday['type'] }}-{{ $bday['id'] }}">Mobile Push</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top-0 pt-0">
                                                    <button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-sm tpl-btn-primary">
                                                        <i class="fas fa-paper-plane mr-1"></i> Send Greeting Now
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-calendar-check mr-1"></i> No upcoming birthdays found in this time range.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent auto-send log --}}
    <div class="tpl-card no-hover">
        <div class="tpl-card-head d-flex align-items-center justify-content-between p-3" style="border-bottom: 1px solid #e2e8f0;">
            <h6 class="tpl-card-title mb-0 font-weight-bold">
                <i class="fas fa-history text-secondary mr-1"></i> Recent auto-send log
            </h6>
            <span class="text-muted small">Latest automated & manual dispatches</span>
        </div>
        <div class="table-responsive">
            <table class="table tpl-table mb-0">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Channels</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dispatches as $disp)
                        <tr>
                            <td class="text-nowrap small text-muted">
                                {{ $disp->dispatched_at ? $disp->dispatched_at->format('d M, H:i') : 'Just now' }}
                            </td>
                            <td>
                                @if($disp->type === 'Birthday')
                                    <span class="tpl-chip is-amber">Birthday</span>
                                @elseif($disp->type === 'Festival')
                                    <span class="tpl-chip is-emerald">Festival</span>
                                @else
                                    <span class="tpl-chip is-blue">{{ $disp->type }}</span>
                                @endif
                            </td>
                            <td class="font-weight-semibold text-dark">
                                {{ $disp->subject }}
                            </td>
                            <td>
                                @foreach(explode(',', $disp->channels) as $ch)
                                    <span class="badge badge-light border small text-muted mr-1">{{ trim($ch) }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="tpl-chip is-emerald">
                                    <i class="fas fa-check mr-1" style="font-size: 0.65rem;"></i> {{ $disp->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox mr-1"></i> No greetings dispatched yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

