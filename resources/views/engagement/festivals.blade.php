@extends('layouts.app')

@section('content')
@include('engagement.nav')

<div class="eng-container">
    {{-- Info Banner --}}
    <div class="alert alert-info d-flex align-items-center mb-4" style="background-color: #eff6ff; border-color: #bfdbfe; color: #1e40af; border-radius: 8px; padding: 12px 16px;">
        <i class="fas fa-info-circle mr-3" style="font-size: 1.25rem;"></i>
        <div>
            Festival auto-send is on. A card is pushed on each festival below at <strong>09:00 AM</strong> to all parents &amp; staff.
        </div>
    </div>

    {{-- Upcoming Festivals Card --}}
    <div class="tpl-card no-hover">
        <div class="tpl-card-head d-flex align-items-center justify-content-between p-3" style="border-bottom: 1px solid #e2e8f0;">
            <div>
                <h6 class="tpl-card-title mb-0 font-weight-bold">
                    <i class="fas fa-gift text-danger mr-1"></i> Upcoming Festivals &amp; Special Days
                </h6>
                <small class="text-muted">Broadcast greetings scheduled for upcoming celebrations</small>
            </div>
            <button type="button" class="btn btn-sm tpl-btn-primary" data-toggle="modal" data-target="#addFestivalModal">
                <i class="fas fa-plus mr-1"></i> Add your own day
            </button>
        </div>

        <div class="table-responsive">
            <table class="table tpl-table mb-0">
                <thead>
                    <tr>
                        <th>Festival</th>
                        <th>Date</th>
                        <th>When</th>
                        <th>Region</th>
                        <th class="text-right">Card</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($festivals as $fest)
                        <tr>
                            <td>
                                <div class="font-weight-bold text-dark">
                                    {{ $fest->name }}
                                    @if($fest->is_custom)
                                        <span class="badge badge-light border ml-1 text-primary small">Custom</span>
                                    @endif
                                </div>
                                @if($fest->greeting_message)
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 280px;">
                                        {{ $fest->greeting_message }}
                                    </small>
                                @endif
                            </td>
                            <td class="text-nowrap">{{ $fest->festival_date ? $fest->festival_date->format('d M Y') : 'TBD' }}</td>
                            <td>
                                @if(str_contains($fest->when_text ?? '', 'Today'))
                                    <span class="tpl-chip is-amber font-weight-bold">{{ $fest->when_text }}</span>
                                @elseif(str_contains($fest->when_text ?? '', 'Tomorrow') || str_contains($fest->when_text ?? '', 'in 1') || str_contains($fest->when_text ?? '', 'in 2'))
                                    <span class="tpl-chip is-emerald">{{ $fest->when_text }}</span>
                                @else
                                    <span class="tpl-chip is-muted">{{ $fest->when_text }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light border text-muted">{{ $fest->region ?? 'ALL' }}</span>
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    @if($fest->design_id)
                                        <a href="{{ route('school.engagement.designs.preview', $fest->design_id) }}" class="btn btn-sm tpl-btn-ghost" title="Preview Festival Card">
                                            <i class="fas fa-image mr-1"></i> Card
                                        </a>
                                    @elseif($festivalDesigns->first())
                                        <a href="{{ route('school.engagement.designs.preview', $festivalDesigns->first()->id) }}" class="btn btn-sm tpl-btn-ghost" title="Preview Festival Card">
                                            <i class="fas fa-image mr-1"></i> Card
                                        </a>
                                    @endif

                                    {{-- Broadcast Greeting Button Form --}}
                                    <form action="{{ route('school.engagement.festivals.send') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="festival_id" value="{{ $fest->id }}">
                                        <input type="hidden" name="design_id" value="{{ $fest->design_id ?? $festivalDesigns->first()?->id }}">
                                        <button type="submit" class="btn btn-sm tpl-btn-primary" title="Broadcast greeting to all parents & staff" onclick="return confirm('Broadcast festival greeting for {{ $fest->name }} to all parents and staff?')">
                                            <i class="fas fa-paper-plane mr-1"></i> Broadcast
                                        </button>
                                    </form>

                                    @if($fest->is_custom)
                                        <form action="{{ route('school.engagement.festivals.destroy', $fest->id) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('Delete this custom special day?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-calendar-times mr-1"></i> No upcoming festivals registered.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Custom Special Day Modal --}}
<div class="modal fade" id="addFestivalModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
            <form action="{{ route('school.engagement.festivals.store') }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title font-weight-bold">
                        <i class="fas fa-gift text-danger mr-1"></i> Add Custom Special Day
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Special Day / Festival Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Annual Sports Day, Founder's Day" required>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group mb-3">
                                <label class="font-weight-semibold small text-muted">Date</label>
                                <input type="date" name="festival_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-3">
                                <label class="font-weight-semibold small text-muted">Region</label>
                                <select name="region" class="form-control">
                                    <option value="ALL">ALL</option>
                                    <option value="North">North</option>
                                    <option value="South">South</option>
                                    <option value="West">West</option>
                                    <option value="East">East</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="font-weight-semibold small text-muted">Card Design Template</label>
                        <select name="design_id" class="form-control">
                            <option value="">-- Choose Default Banner --</option>
                            @foreach($festivalDesigns as $fd)
                                <option value="{{ $fd->id }}">{{ $fd->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-semibold small text-muted">Greeting Broadcast Message</label>
                        <textarea name="greeting_message" class="form-control" rows="2" placeholder="Warm festive wishes from the school administration."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-light border" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm tpl-btn-primary">
                        <i class="fas fa-save mr-1"></i> Save to Calendar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

