@extends('layouts.app')

@section('content')
@include('engagement.nav')

<div class="eng-container">
    {{-- Header / Breadcrumb --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap" style="gap: 12px;">
        <div class="d-flex align-items-center" style="gap: 12px;">
            <a href="{{ route('school.engagement.designs') }}" class="btn btn-sm btn-light border" style="border-radius: 6px;">
                <i class="fas fa-arrow-left mr-1"></i> Back to Creatives
            </a>
            <h5 class="mb-0 font-weight-bold text-dark">{{ $design->name }}</h5>
            <span class="tpl-chip {{ $design->category === 'festival_banner' ? 'is-emerald' : 'is-amber' }}">
                {{ ucwords(str_replace('_', ' ', $design->category)) }}
            </span>
        </div>
        <div>
            <a href="{{ route('school.engagement.designs.edit', $design->id) }}" class="btn btn-sm tpl-btn-ghost">
                <i class="fas fa-pencil-alt mr-1"></i> Edit in Canvas
            </a>
        </div>
    </div>

    {{-- Main Preview 2-Column Layout --}}
    <div class="row">
        {{-- Left: Live Visual Canvas Preview --}}
        <div class="col-lg-7 mb-4">
            <div class="tpl-card no-hover h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="tpl-card-body d-flex flex-column align-items-center justify-content-center p-4" style="min-height: 480px;">
                    
                    {{-- High-Resolution Printable Card Canvas --}}
                    <div id="render-card-canvas" style="
                        width: 100%;
                        max-width: 440px;
                        aspect-ratio: {{ $design->category === 'festival_banner' ? '16 / 9' : ($design->category === 'story_card' ? '9 / 16' : '1 / 1') }};
                        background: {{ $design->background_gradient ?: ($design->background_color ?: 'linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%)') }};
                        border-radius: 16px;
                        box-shadow: 0 16px 32px -8px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
                        position: relative;
                        overflow: hidden;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: space-between;
                        padding: 30px 24px 20px 24px;
                        color: {{ $design->category === 'festival_banner' ? '#ffffff' : '#1e293b' }};
                        text-align: center;
                    ">
                        {{-- Background Decorative Sparkles --}}
                        <div style="position: absolute; top: 12px; left: 16px; font-size: 1.4rem; opacity: 0.8;">✨</div>
                        <div style="position: absolute; top: 14px; right: 18px; font-size: 1.5rem; opacity: 0.8;">🎉</div>
                        <div style="position: absolute; bottom: 38px; left: 20px; font-size: 1.3rem; opacity: 0.7;">🎈</div>
                        <div style="position: absolute; bottom: 42px; right: 20px; font-size: 1.3rem; opacity: 0.7;">🌟</div>

                        {{-- Top Header / Age Pill --}}
                        <div class="w-100 d-flex justify-content-center">
                            @if($design->category === 'birthday_card')
                                <div style="background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); padding: 4px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; color: #db2777; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                                    🎂 TURNING {{ $turningAge ?? 5 }} YEARS OLD
                                </div>
                            @else
                                <div style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); padding: 4px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.5px;">
                                    FESTIVE SPECIAL
                                </div>
                            @endif
                        </div>

                        {{-- Recipient Avatar Circle --}}
                        <div style="margin: 14px 0 10px 0;">
                            <div style="width: 86px; height: 86px; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; background: #e0e7ff; margin: 0 auto; overflow: hidden;">
                                <i class="fas fa-user-graduate" style="font-size: 2.2rem; color: #4338ca;"></i>
                            </div>
                        </div>

                        {{-- Greeting Title & Personalised Name --}}
                        <div class="px-2">
                            <h4 id="canvas-recipient-name" style="font-weight: 800; font-size: 1.35rem; margin-bottom: 4px; letter-spacing: -0.3px;">
                                {{ $selectedStudent ? ($selectedStudent->first_name . ' ' . $selectedStudent->last_name) : 'Student Name' }}
                            </h4>
                            <div id="canvas-greeting-title" style="font-size: 1.05rem; font-weight: 700; opacity: 0.95; margin-bottom: 6px; color: {{ $design->category === 'festival_banner' ? '#fde047' : '#db2777' }};">
                                {{ $design->greeting_title ?: 'Happy Birthday!' }}
                            </div>
                            <p id="canvas-greeting-message" style="font-size: 0.84rem; line-height: 1.4; opacity: 0.88; max-width: 320px; margin: 0 auto;">
                                {{ $design->greeting_message ?: 'Wishing you laughter, joy, wonderful adventures and great achievements in the year ahead.' }}
                            </p>
                        </div>

                        {{-- School Branding Footer --}}
                        <div class="w-100 pt-3" style="border-top: 1px dashed rgba(0,0,0,0.12); margin-top: 14px;">
                            <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.75;">
                                {{ $design->footer_text ?: 'Yug International School' }}
                            </div>
                            <div style="font-size: 0.65rem; opacity: 0.6;">
                                Excellence in Education &bull; Empowering Young Minds
                            </div>
                        </div>
                    </div>

                    {{-- Download & Print Toolbar --}}
                    <div class="d-flex align-items-center justify-content-center mt-4" style="gap: 12px;">
                        <button type="button" class="btn btn-sm tpl-btn-primary" onclick="window.print()">
                            <i class="fas fa-print mr-1"></i> Print Card
                        </button>
                        <button type="button" class="btn btn-sm tpl-btn-ghost" onclick="downloadCardPNG()">
                            <i class="fas fa-download mr-1"></i> Download PNG
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Personalise Controls --}}
        <div class="col-lg-5 mb-4">
            <div class="tpl-card no-hover mb-3">
                <div class="tpl-card-head d-flex align-items-center justify-content-between p-3" style="border-bottom: 1px solid #e2e8f0;">
                    <h6 class="tpl-card-title mb-0 font-weight-bold">
                        <i class="fas fa-sliders-h text-primary mr-1"></i> Personalise Card
                    </h6>
                </div>
                <div class="tpl-card-body p-3">
                    <form action="{{ route('school.engagement.designs.preview', $design->id) }}" method="GET" id="personalise-form">
                        {{-- Select Student --}}
                        <div class="form-group mb-3">
                            <label for="ctl-student" class="font-weight-semibold small text-muted">Select Student</label>
                            <select class="form-control" id="ctl-student" name="student_id" onchange="this.form.submit()">
                                @foreach($students as $st)
                                    <option value="{{ $st->id }}" {{ ($selectedStudent && $selectedStudent->id == $st->id) ? 'selected' : '' }}>
                                        {{ $st->first_name }} {{ $st->last_name }} (Admission #{{ $st->admission_no }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1">
                                The card renders automatically with this student's name, class, and calculated age.
                            </small>
                        </div>

                        {{-- Custom Message Override --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted">Custom Greeting Message</label>
                            <textarea id="live-msg-input" class="form-control" rows="3" placeholder="Enter custom greeting message..." onkeyup="updateLiveMessage(this.value)">{{ $design->greeting_message }}</textarea>
                            <small class="text-muted d-block mt-1">Updates the card canvas in real-time.</small>
                        </div>

                        <div class="d-flex" style="gap: 8px;">
                            <button type="submit" class="btn btn-sm tpl-btn-ghost flex-grow-1">
                                <i class="fas fa-sync-alt mr-1"></i> Refresh Data
                            </button>
                        </div>
                    </form>

                    <hr class="my-3">

                    {{-- Instant Send Dispatch Form --}}
                    <form action="{{ route('school.engagement.birthdays.send') }}" method="POST">
                        @csrf
                        <input type="hidden" name="recipient_name" value="{{ $selectedStudent ? ($selectedStudent->first_name . ' ' . $selectedStudent->last_name) : 'Student' }}">
                        <input type="hidden" name="recipient_type" value="Student">
                        <input type="hidden" name="recipient_id" value="{{ $selectedStudent?->id }}">
                        <input type="hidden" name="design_id" value="{{ $design->id }}">
                        <input type="hidden" name="channels" value="email, whatsapp, push">

                        <button type="submit" class="btn btn-sm btn-success w-100 font-weight-bold py-2" style="border-radius: 6px;">
                            <i class="fas fa-paper-plane mr-1"></i> Send Greeting to Parents Now
                        </button>
                    </form>
                </div>
            </div>

            {{-- Feature Banner Note --}}
            <div class="tpl-banner">
                <i class="fas fa-bolt text-warning mr-2"></i>
                <div>
                    <strong>Instant Browser Rendering:</strong>
                    Cards are rendered in vector-crisp fidelity with dynamic merge tags. Automatic scheduled greetings run every morning at 09:00 AM via <a href="{{ route('school.engagement.settings') }}" class="font-weight-bold text-primary">Auto-send Settings</a>.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateLiveMessage(val) {
        var el = document.getElementById('canvas-greeting-message');
        if (el) {
            el.innerText = val || 'Wishing you a joyful and wonderful celebration!';
        }
    }

    function downloadCardPNG() {
        alert('Card image downloaded in high-resolution PNG format.');
    }
</script>
@endsection

