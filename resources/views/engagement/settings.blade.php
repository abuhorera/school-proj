@extends('layouts.app')

@section('content')
@include('engagement.nav')

<div class="eng-container">
    <div class="row">
        {{-- Left: Main Settings Form --}}
        <div class="col-lg-8 mb-4">
            <form action="{{ route('school.engagement.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- 1. Birthday Cards Section --}}
                <div class="tpl-card mb-3 no-hover">
                    <div class="tpl-card-head p-3" style="border-bottom: 1px solid #e2e8f0;">
                        <h6 class="tpl-card-title mb-0 font-weight-bold">
                            <i class="fas fa-birthday-cake text-warning mr-1"></i> Birthday Cards
                        </h6>
                    </div>
                    <div class="tpl-card-body p-3">
                        {{-- Student Toggle --}}
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom: 1px solid #f1f5f9;">
                            <div>
                                <strong class="text-dark">Students</strong><br>
                                <small class="text-muted">Send a personalised card to parents on a student's birthday</small>
                            </div>
                            <label class="tpl-switch mb-0">
                                <input type="checkbox" name="birthday_students_enabled" value="1" {{ $settings->birthday_students_enabled ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="form-group mb-4">
                            <label for="bsd" class="font-weight-semibold small text-muted">Default Student Birthday Design</label>
                            <select class="form-control" id="bsd" name="birthday_student_design_id">
                                <option value="">-- Choose Design --</option>
                                @foreach($birthdayDesigns as $d)
                                    <option value="{{ $d->id }}" {{ $settings->birthday_student_design_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Staff Toggle --}}
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom: 1px solid #f1f5f9;">
                            <div>
                                <strong class="text-dark">Staff Members</strong><br>
                                <small class="text-muted">Send a congratulatory card to staff members on their birthday</small>
                            </div>
                            <label class="tpl-switch mb-0">
                                <input type="checkbox" name="birthday_staff_enabled" value="1" {{ $settings->birthday_staff_enabled ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="form-group mb-0">
                            <label for="bstf" class="font-weight-semibold small text-muted">Default Staff Birthday Design</label>
                            <select class="form-control" id="bstf" name="birthday_staff_design_id">
                                <option value="">-- Choose Design --</option>
                                @foreach($birthdayDesigns as $d)
                                    <option value="{{ $d->id }}" {{ $settings->birthday_staff_design_id == $d->id ? 'selected' : '' }}>
                                        {{ $d->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 2. Festival Greetings Section --}}
                <div class="tpl-card mb-3 no-hover">
                    <div class="tpl-card-head p-3" style="border-bottom: 1px solid #e2e8f0;">
                        <h6 class="tpl-card-title mb-0 font-weight-bold">
                            <i class="fas fa-gift text-danger mr-1"></i> Festival Greetings
                        </h6>
                    </div>
                    <div class="tpl-card-body p-3">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3" style="border-bottom: 1px solid #f1f5f9;">
                            <div>
                                <strong class="text-dark">Auto-send Festival Cards</strong><br>
                                <small class="text-muted">Broadcast festive greetings to all parents and staff on upcoming festival days</small>
                            </div>
                            <label class="tpl-switch mb-0">
                                <input type="checkbox" name="festivals_enabled" value="1" {{ $settings->festivals_enabled ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                        <div class="form-group mb-0">
                            <label for="fsd" class="font-weight-semibold small text-muted">Default Festival Banner Design</label>
                            <select class="form-control" id="fsd" name="festival_design_id">
                                <option value="">-- Choose Design --</option>
                                @foreach($festivalDesigns as $fd)
                                    <option value="{{ $fd->id }}" {{ $settings->festival_design_id == $fd->id ? 'selected' : '' }}>
                                        {{ $fd->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 3. Timing & Delivery Channels --}}
                <div class="tpl-card mb-3 no-hover">
                    <div class="tpl-card-head p-3" style="border-bottom: 1px solid #e2e8f0;">
                        <h6 class="tpl-card-title mb-0 font-weight-bold">
                            <i class="fas fa-clock text-primary mr-1"></i> Timing &amp; Delivery Channels
                        </h6>
                    </div>
                    <div class="tpl-card-body p-3">
                        @php
                            $activeChannels = explode(',', $settings->channels ?? 'push,whatsapp,email,sms');
                            $activeChannels = array_map('trim', $activeChannels);
                        @endphp
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted d-block">Enabled Channels</label>
                            <div class="d-flex flex-wrap" style="gap: 16px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ch-push" name="channels[]" value="push" {{ in_array('push', $activeChannels) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-semibold small" for="ch-push">
                                        <i class="fas fa-bell text-info mr-1"></i> Mobile Push
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ch-wa" name="channels[]" value="whatsapp" {{ in_array('whatsapp', $activeChannels) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-semibold small" for="ch-wa">
                                        <i class="fab fa-whatsapp text-success mr-1"></i> WhatsApp
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ch-em" name="channels[]" value="email" {{ in_array('email', $activeChannels) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-semibold small" for="ch-em">
                                        <i class="fas fa-envelope text-primary mr-1"></i> Email
                                    </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="ch-sms" name="channels[]" value="sms" {{ in_array('sms', $activeChannels) ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-semibold small" for="ch-sms">
                                        <i class="fas fa-sms text-secondary mr-1"></i> SMS
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <label for="send_time" class="font-weight-semibold small text-muted">Daily Dispatch Time</label>
                            <input type="time" class="form-control" style="max-width: 200px;" id="send_time" name="send_time" value="{{ $settings->send_time ?? '09:00' }}">
                            <small class="text-muted d-block mt-1">Cards are automatically dispatched each morning at this local time.</small>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn tpl-btn-primary px-4 py-2 font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Save Auto-send Preferences
                    </button>
                </div>
            </form>
        </div>

        {{-- Right: Status & Test Dispatch --}}
        <div class="col-lg-4 mb-4">
            <div class="tpl-card no-hover mb-3">
                <div class="tpl-card-head p-3" style="border-bottom: 1px solid #e2e8f0;">
                    <h6 class="tpl-card-title mb-0 font-weight-bold">
                        <i class="fas fa-check-circle text-success mr-1"></i> Automation Status
                    </h6>
                </div>
                <div class="tpl-card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small font-weight-semibold text-muted">Student Birthdays</span>
                        @if($settings->birthday_students_enabled)
                            <span class="tpl-chip is-emerald">Active</span>
                        @else
                            <span class="tpl-chip is-muted">Disabled</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small font-weight-semibold text-muted">Staff Birthdays</span>
                        @if($settings->birthday_staff_enabled)
                            <span class="tpl-chip is-emerald">Active</span>
                        @else
                            <span class="tpl-chip is-muted">Disabled</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="small font-weight-semibold text-muted">Festival Broadcasts</span>
                        @if($settings->festivals_enabled)
                            <span class="tpl-chip is-emerald">Active</span>
                        @else
                            <span class="tpl-chip is-muted">Disabled</span>
                        @endif
                    </div>

                    <hr class="my-3">

                    {{-- Send Test Card Form --}}
                    <div class="text-center">
                        <small class="text-muted d-block mb-2 font-weight-semibold">Verify Notification Gateways</small>
                        <form action="{{ route('school.engagement.settings.test') }}" method="POST">
                            @csrf
                            <input type="hidden" name="design_id" value="{{ $birthdayDesigns->first()?->id }}">
                            <button type="submit" class="btn btn-sm btn-outline-primary w-100 font-weight-bold py-2">
                                <i class="fas fa-vial mr-1"></i> Send Test Card to Admin
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Best Practices Info --}}
            <div class="tpl-banner">
                <i class="fas fa-lightbulb text-warning mr-2"></i>
                <div>
                    <strong>Pro-Tip:</strong>
                    Make sure WhatsApp and SMS gateway credentials are configured under <em>Communicate &gt; SMS / Notification Settings</em> to guarantee high delivery rates.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

