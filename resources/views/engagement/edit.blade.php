@extends('layouts.app')

@section('content')
@include('engagement.nav')

<div class="eng-container">
    {{-- Header Action Bar --}}
    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap" style="gap: 12px;">
        <div class="d-flex align-items-center" style="gap: 12px;">
            <a href="{{ route('school.engagement.designs') }}" class="btn btn-sm btn-light border" style="border-radius: 6px;">
                <i class="fas fa-arrow-left mr-1"></i> Back to Creatives
            </a>
            <h5 class="mb-0 font-weight-bold text-dark">Canvas Designer: {{ $design->name }}</h5>
            <span class="tpl-chip {{ $design->category === 'festival_banner' ? 'is-emerald' : 'is-amber' }}">
                {{ ucwords(str_replace('_', ' ', $design->category)) }}
            </span>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
            <a href="{{ route('school.engagement.designs.preview', $design->id) }}" class="btn btn-sm tpl-btn-ghost">
                <i class="fas fa-eye mr-1"></i> Preview with Student
            </a>
            <button type="button" class="btn btn-sm tpl-btn-primary" onclick="document.getElementById('designer-form').submit()">
                <i class="fas fa-save mr-1"></i> Save Changes
            </button>
        </div>
    </div>

    {{-- 2-Column Designer Layout --}}
    <div class="row">
        {{-- Left Column: Designer Form Controls --}}
        <div class="col-lg-5 mb-4">
            <div class="tpl-card no-hover">
                <div class="tpl-card-head p-3" style="border-bottom: 1px solid #e2e8f0;">
                    <h6 class="tpl-card-title mb-0 font-weight-bold">
                        <i class="fas fa-sliders-h text-primary mr-1"></i> Design Properties
                    </h6>
                </div>
                <div class="tpl-card-body p-3">
                    <form action="{{ route('school.engagement.designs.update', $design->id) }}" method="POST" id="designer-form">
                        @csrf
                        @method('PUT')

                        {{-- Creative Name --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted">Creative Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $design->name) }}" required>
                        </div>

                        {{-- Category --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted">Category</label>
                            <select name="category" id="ctl-category" class="form-control" onchange="applyCategoryPreset(this.value)">
                                <option value="birthday_card" {{ $design->category === 'birthday_card' ? 'selected' : '' }}>Birthday Card</option>
                                <option value="festival_banner" {{ $design->category === 'festival_banner' ? 'selected' : '' }}>Festival Banner</option>
                                <option value="popup_card" {{ $design->category === 'popup_card' ? 'selected' : '' }}>Pop-up Notice</option>
                                <option value="story_card" {{ $design->category === 'story_card' ? 'selected' : '' }}>Mobile Story</option>
                            </select>
                        </div>

                        {{-- Size Presets --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted d-block">Dimensions Preset</label>
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="setSize(1080, 1080)">Square (1:1)</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="setSize(1200, 630)">Banner (16:9)</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="setSize(1080, 1920)">Story (9:16)</button>
                            </div>
                            <input type="hidden" name="card_width_mm" id="ctl-width" value="{{ $design->width ?: 1080 }}">
                            <input type="hidden" name="card_height_mm" id="ctl-height" value="{{ $design->height ?: 1080 }}">
                        </div>

                        {{-- Background Styling & Gradients --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted d-block">Background Style</label>
                            <div class="d-flex align-items-center mb-2" style="gap: 8px;">
                                <input type="color" id="ctl-bg-color" name="background_color" class="form-control form-control-color p-1" style="width: 44px; height: 38px; border-radius: 6px;" value="{{ $design->background_color ?: '#ffffff' }}" onchange="setSolidBg(this.value)">
                                <input type="text" id="ctl-bg-gradient" name="background_gradient" class="form-control small" placeholder="CSS gradient code..." value="{{ $design->background_gradient }}" oninput="setCustomGradient(this.value)">
                            </div>
                            <small class="text-muted d-block mb-2">Preset Themes:</small>
                            <div class="d-flex flex-wrap" style="gap: 6px;">
                                <button type="button" class="btn btn-xs border" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); font-size: 0.72rem; padding: 3px 8px;" onclick="applyGradient('linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)', '#1e293b')">Soft Peach</button>
                                <button type="button" class="btn btn-xs border" style="background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%); font-size: 0.72rem; padding: 3px 8px;" onclick="applyGradient('linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%)', '#1e293b')">Warm Amber</button>
                                <button type="button" class="btn btn-xs border text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); font-size: 0.72rem; padding: 3px 8px;" onclick="applyGradient('linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%)', '#ffffff')">Midnight Fest</button>
                                <button type="button" class="btn btn-xs border text-white" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); font-size: 0.72rem; padding: 3px 8px;" onclick="applyGradient('linear-gradient(135deg, #065f46 0%, #047857 100%)', '#ffffff')">Emerald</button>
                                <button type="button" class="btn btn-xs border text-white" style="background: linear-gradient(135deg, #be185d 0%, #db2777 100%); font-size: 0.72rem; padding: 3px 8px;" onclick="applyGradient('linear-gradient(135deg, #be185d 0%, #db2777 100%)', '#ffffff')">Rose Party</button>
                            </div>
                        </div>

                        {{-- Greeting Title --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted">Greeting Headline</label>
                            <input type="text" name="greeting_title" id="ctl-title" class="form-control" value="{{ old('greeting_title', $design->greeting_title) }}" oninput="updateCanvasTitle(this.value)">
                        </div>

                        {{-- Greeting Message & Merge Tags --}}
                        <div class="form-group mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="font-weight-semibold small text-muted mb-0">Greeting Message</label>
                            </div>
                            <textarea name="greeting_message" id="ctl-msg" class="form-control" rows="3" oninput="updateCanvasMsg(this.value)">{{ old('greeting_message', $design->greeting_message) }}</textarea>
                            
                            {{-- Merge Tags chips --}}
                            <div class="mt-2">
                                <small class="text-muted d-block mb-1">Click to insert merge tag:</small>
                                <div class="d-flex flex-wrap" style="gap: 4px;">
                                    <span class="badge badge-light border" style="cursor: pointer;" onclick="insertTag('{name}')">{name}</span>
                                    <span class="badge badge-light border" style="cursor: pointer;" onclick="insertTag('{first_name}')">{first_name}</span>
                                    <span class="badge badge-light border" style="cursor: pointer;" onclick="insertTag('{class}')">{class}</span>
                                    <span class="badge badge-light border" style="cursor: pointer;" onclick="insertTag('{age}')">{age}</span>
                                    <span class="badge badge-light border" style="cursor: pointer;" onclick="insertTag('{school_name}')">{school_name}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Branding --}}
                        <div class="form-group mb-3">
                            <label class="font-weight-semibold small text-muted">Footer Branding / School Name</label>
                            <input type="text" name="footer_text" id="ctl-footer" class="form-control" value="{{ old('footer_text', $design->footer_text) }}" oninput="updateCanvasFooter(this.value)">
                        </div>

                        <button type="submit" class="btn btn-sm tpl-btn-primary w-100 py-2">
                            <i class="fas fa-save mr-1"></i> Save Design to Library
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Live Interactive Canvas --}}
        <div class="col-lg-7 mb-4">
            <div class="tpl-card no-hover h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="tpl-card-head p-3 d-flex align-items-center justify-content-between" style="border-bottom: 1px solid #e2e8f0; background: #ffffff;">
                    <div class="font-weight-bold small text-muted">
                        <i class="fas fa-desktop mr-1"></i> Live Canvas Preview
                    </div>
                    <span class="badge badge-light border" id="canvas-dim-badge">1080 &times; 1080 px</span>
                </div>
                <div class="tpl-card-body d-flex flex-column align-items-center justify-content-center p-4" style="min-height: 480px;">
                    <div id="live-canvas" style="
                        width: 100%;
                        max-width: 440px;
                        aspect-ratio: 1 / 1;
                        background: {{ $design->background_gradient ?: ($design->background_color ?: 'linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%)') }};
                        border-radius: 16px;
                        box-shadow: 0 16px 32px -8px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
                        position: relative;
                        overflow: hidden;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: space-between;
                        padding: 28px 24px 20px 24px;
                        color: #1e293b;
                        text-align: center;
                        transition: all 0.3s ease;
                    ">
                        {{-- Canvas Sparkles --}}
                        <div style="position: absolute; top: 12px; left: 16px; font-size: 1.4rem;">✨</div>
                        <div style="position: absolute; top: 14px; right: 18px; font-size: 1.4rem;">🎉</div>
                        <div style="position: absolute; bottom: 38px; left: 20px; font-size: 1.3rem;">🎈</div>
                        <div style="position: absolute; bottom: 42px; right: 20px; font-size: 1.3rem;">🌟</div>

                        {{-- Age / Category pill --}}
                        <div style="background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); padding: 4px 14px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; color: #db2777;">
                            ✨ CELEBRATIONS
                        </div>

                        {{-- Avatar Circle --}}
                        <div style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.12); display: flex; align-items: center; justify-content: center; background: #e0e7ff; margin: 10px auto;">
                            <i class="fas fa-user-graduate" style="font-size: 2rem; color: #4338ca;"></i>
                        </div>

                        {{-- Text Elements --}}
                        <div class="px-2">
                            <h5 style="font-weight: 800; font-size: 1.25rem; margin-bottom: 4px;">Kabir Singh</h5>
                            <div id="live-canvas-title" style="font-size: 1.05rem; font-weight: 700; color: #db2777; margin-bottom: 6px;">
                                {{ $design->greeting_title ?: 'Happy Birthday!' }}
                            </div>
                            <p id="live-canvas-msg" style="font-size: 0.84rem; line-height: 1.4; opacity: 0.9; max-width: 320px; margin: 0 auto;">
                                {{ $design->greeting_message ?: 'Wishing you laughter, joy, wonderful adventures and great achievements!' }}
                            </p>
                        </div>

                        {{-- Footer Text --}}
                        <div class="w-100 pt-2" style="border-top: 1px dashed rgba(0,0,0,0.12); margin-top: 10px;">
                            <div id="live-canvas-footer" style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; opacity: 0.75;">
                                {{ $design->footer_text ?: 'Yug International School' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateCanvasTitle(val) {
        document.getElementById('live-canvas-title').innerText = val || 'Happy Celebrations!';
    }
    function updateCanvasMsg(val) {
        document.getElementById('live-canvas-msg').innerText = val || 'Warm wishes from the school.';
    }
    function updateCanvasFooter(val) {
        document.getElementById('live-canvas-footer').innerText = val || 'Yug International School';
    }
    function setSize(w, h) {
        document.getElementById('ctl-width').value = w;
        document.getElementById('ctl-height').value = h;
        document.getElementById('canvas-dim-badge').innerText = w + ' × ' + h + ' px';
        var canvas = document.getElementById('live-canvas');
        if (w > h) {
            canvas.style.aspectRatio = '16 / 9';
        } else if (h > w) {
            canvas.style.aspectRatio = '9 / 16';
        } else {
            canvas.style.aspectRatio = '1 / 1';
        }
    }
    function applyGradient(gradient, textColor) {
        document.getElementById('ctl-bg-gradient').value = gradient;
        var canvas = document.getElementById('live-canvas');
        canvas.style.background = gradient;
        canvas.style.color = textColor;
    }
    function setSolidBg(color) {
        document.getElementById('ctl-bg-gradient').value = '';
        var canvas = document.getElementById('live-canvas');
        canvas.style.background = color;
    }
    function setCustomGradient(gradient) {
        var canvas = document.getElementById('live-canvas');
        canvas.style.background = gradient;
    }
    function insertTag(tag) {
        var textarea = document.getElementById('ctl-msg');
        textarea.value += ' ' + tag;
        updateCanvasMsg(textarea.value);
    }
</script>
@endsection

