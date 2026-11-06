@extends('layouts.app')

@section('content')
@include('engagement.nav')

<div class="eng-container">
    {{-- Header Action Bar --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center" style="gap: 12px;">
            <a href="{{ route('school.engagement.designs') }}" class="btn btn-sm btn-light border" style="border-radius: 6px;">
                <i class="fas fa-arrow-left mr-1"></i> Back to Creatives
            </a>
            <h5 class="mb-0 font-weight-bold text-dark">Template Library</h5>
        </div>
    </div>

    {{-- Category Filter Pills --}}
    <div class="d-flex align-items-center mb-4 flex-wrap" style="gap: 8px;">
        <a href="{{ route('school.engagement.designs.create', ['category' => 'birthday_card']) }}" class="tpl-pill {{ $category === 'birthday_card' ? 'active' : '' }}">
            <i class="fas fa-birthday-cake mr-1"></i> Birthday Card
        </a>
        <a href="{{ route('school.engagement.designs.create', ['category' => 'festival_banner']) }}" class="tpl-pill {{ $category === 'festival_banner' ? 'active' : '' }}">
            <i class="fas fa-holly-berry mr-1"></i> Festival Banner
        </a>
        <a href="{{ route('school.engagement.designs.create', ['category' => 'popup_card']) }}" class="tpl-pill {{ $category === 'popup_card' ? 'active' : '' }}">
            <i class="fas fa-window-maximize mr-1"></i> Pop-up Notice
        </a>
        <a href="{{ route('school.engagement.designs.create', ['category' => 'story_card']) }}" class="tpl-pill {{ $category === 'story_card' ? 'active' : '' }}">
            <i class="fas fa-mobile-alt mr-1"></i> Mobile Story
        </a>
    </div>

    {{-- Template Cards Grid --}}
    <div class="row">
        {{-- 1. Start from blank card --}}
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <a href="{{ route('school.engagement.designs.create', ['category' => $category, 'template_id' => 'scratch']) }}"
               class="tpl-manage-card h-100 d-flex flex-column text-decoration-none border-dashed"
               style="border: 2px dashed #cbd5e1; background: #f8fafc; min-height: 290px;">
                <div class="d-flex flex-column align-items-center justify-content-center flex-grow-1 p-4 text-center">
                    <div style="width: 56px; height: 56px; border-radius: 50%; background: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.06); margin-bottom: 14px;">
                        <i class="fas fa-plus text-primary" style="font-size: 1.3rem;"></i>
                    </div>
                    <div class="font-weight-bold text-dark" style="font-size: 1.05rem;">Start from blank</div>
                    <div class="text-muted small mt-1">Design it yourself in the canvas designer</div>
                    <span class="btn btn-sm btn-outline-primary mt-3" style="border-radius: 6px;">
                        <i class="fas fa-magic mr-1"></i> Open Blank Canvas
                    </span>
                </div>
            </a>
        </div>

        {{-- 2. Pre-made Design Templates --}}
        @foreach($templates->where('category', $category) as $tpl)
            @php
                $isBirthday = $tpl->category === 'birthday_card';
                $isFestival = $tpl->category === 'festival_banner';
                $bgGradient = $tpl->background_gradient ?: ($isFestival ? 'linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%)' : 'linear-gradient(135deg, #fef3c7 0%, #fde68a 100%)');
            @endphp
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                <div class="tpl-manage-card h-100 d-flex flex-column">
                    {{-- Visual Card Graphic --}}
                    <div class="tpl-preview-box" style="background: {{ $bgGradient }}; min-height: 190px;">
                        <span class="tpl-designer-badge" title="Verified Layout">
                            <i class="fas fa-check"></i>
                        </span>
                        <div class="text-center p-3 w-100" style="color: {{ $isFestival ? '#fef08a' : '#1e293b' }};">
                            <div style="font-size: 1.8rem; margin-bottom: 6px;">
                                @if($isBirthday) 🎈 🍰 🎁
                                @elseif($isFestival) 🪔 ✨ 🎆
                                @else 📢 🔔
                                @endif
                            </div>
                            <div style="font-weight: 700; font-size: 1rem; line-height: 1.2;">
                                {{ $tpl->greeting_title ?: $tpl->name }}
                            </div>
                            <div style="font-size: 0.75rem; opacity: 0.85; margin-top: 4px;">
                                {{ Str::limit($tpl->greeting_message, 45) }}
                            </div>
                            <div style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; margin-top: 8px; opacity: 0.7;">
                                {{ $tpl->footer_text }}
                            </div>
                        </div>
                    </div>

                    {{-- Footer with Use Button --}}
                    <div class="tpl-card-footer mt-auto d-flex align-items-center justify-content-between p-3">
                        <div style="min-width: 0;">
                            <div class="tpl-card-name text-truncate font-weight-bold" title="{{ $tpl->name }}">
                                {{ $tpl->name }}
                            </div>
                            <span class="text-muted small">
                                {{ $tpl->width ?? 1080 }} &times; {{ $tpl->height ?? 1080 }} px
                            </span>
                        </div>
                        <form action="{{ route('school.engagement.designs.clone', $tpl->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm tpl-btn-primary" style="font-weight: 600;">
                                <i class="fas fa-plus-circle mr-1"></i> Use
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

