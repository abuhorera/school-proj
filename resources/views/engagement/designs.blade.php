@extends('layouts.app')

@section('content')
@include('engagement.nav')

<div class="eng-container">
    {{-- Category Filter Pills --}}
    <div class="d-flex align-items-center mb-4 flex-wrap" style="gap: 8px;">
        <a href="{{ route('school.engagement.designs') }}" class="tpl-pill {{ $category === 'all' ? 'active' : '' }}">
            All <span class="tpl-pill-count">{{ $counts['all'] ?? 0 }}</span>
        </a>
        <a href="{{ route('school.engagement.designs', ['category' => 'birthday_card']) }}" class="tpl-pill {{ $category === 'birthday_card' ? 'active' : '' }}">
            Birthday Card <span class="tpl-pill-count">{{ $counts['birthday_card'] ?? 0 }}</span>
        </a>
        <a href="{{ route('school.engagement.designs', ['category' => 'festival_banner']) }}" class="tpl-pill {{ $category === 'festival_banner' ? 'active' : '' }}">
            Festival Banner <span class="tpl-pill-count">{{ $counts['festival_banner'] ?? 0 }}</span>
        </a>
        <a href="{{ route('school.engagement.designs', ['category' => 'popup_card']) }}" class="tpl-pill {{ $category === 'popup_card' ? 'active' : '' }}">
            Pop-up <span class="tpl-pill-count">{{ $counts['popup_card'] ?? 0 }}</span>
        </a>
        <a href="{{ route('school.engagement.designs', ['category' => 'story_card']) }}" class="tpl-pill {{ $category === 'story_card' ? 'active' : '' }}">
            Story <span class="tpl-pill-count">{{ $counts['story_card'] ?? 0 }}</span>
        </a>
    </div>

    {{-- Designs Grid --}}
    @if($designs->count() > 0)
        <div class="row">
            @foreach($designs as $design)
                @php
                    $isBirthday = $design->category === 'birthday_card';
                    $isFestival = $design->category === 'festival_banner';
                    $isPopup = $design->category === 'popup_card';
                    $isStory = $design->category === 'story_card';

                    $chipClass = 'is-amber';
                    $chipLabel = 'Birthday Card';
                    if ($isFestival) { $chipClass = 'is-emerald'; $chipLabel = 'Festival Banner'; }
                    elseif ($isPopup) { $chipClass = 'is-purple'; $chipLabel = 'Pop-up Notice'; }
                    elseif ($isStory) { $chipClass = 'is-blue'; $chipLabel = 'Mobile Story'; }

                    $bgGradient = $design->background_gradient ?: 'linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%)';
                    if ($isBirthday && empty($design->background_gradient)) {
                        $bgGradient = 'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)';
                    } elseif ($isFestival && empty($design->background_gradient)) {
                        $bgGradient = 'linear-gradient(135deg, #141e30 0%, #243b55 100%)';
                    }
                @endphp
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="tpl-manage-card h-100 d-flex flex-column">
                        {{-- Visual Card Preview --}}
                        <div class="tpl-preview-box" style="background: {{ $bgGradient }}; cursor: pointer;"
                             onclick="window.location.href='{{ route('school.engagement.designs.preview', $design->id) }}'">
                            <span class="tpl-designer-badge" title="Canvas Designer">
                                <i class="fas fa-magic"></i>
                            </span>

                            {{-- Rendered mini canvas simulation --}}
                            <div class="text-center p-3 w-100" style="color: {{ $isFestival ? '#fef08a' : '#1e293b' }};">
                                <div style="font-size: 1.6rem; margin-bottom: 4px;">
                                    @if($isBirthday) 🎂 ✨
                                    @elseif($isFestival) 🪔 🎆
                                    @elseif($isStory) 📱 🌟
                                    @else 📢 🎈
                                    @endif
                                </div>
                                <div style="font-weight: 700; font-size: 0.95rem; line-height: 1.2; text-shadow: {{ $isFestival ? '0 1px 3px rgba(0,0,0,0.6)' : 'none' }};">
                                    {{ Str::limit($design->greeting_title ?: $design->name, 28) }}
                                </div>
                                <div style="font-size: 0.72rem; opacity: 0.85; margin-top: 4px;">
                                    {{ Str::limit($design->greeting_message ?: 'Warm wishes and blessings', 36) }}
                                </div>
                                <div style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7; margin-top: 6px;">
                                    {{ $design->footer_text ?: 'Yug International School' }}
                                </div>
                            </div>
                        </div>

                        {{-- Card Details & Actions Footer --}}
                        <div class="tpl-card-footer mt-auto">
                            <div style="min-width: 0;">
                                <div class="tpl-card-name text-truncate" title="{{ $design->name }}">
                                    {{ $design->name }}
                                </div>
                                <span class="tpl-chip {{ $chipClass }}">
                                    {{ $chipLabel }}
                                </span>
                            </div>

                            <div class="tpl-card-actions">
                                <a href="{{ route('school.engagement.designs.preview', $design->id) }}" class="tpl-icon-btn" title="Preview & Personalise">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('school.engagement.designs.edit', $design->id) }}" class="tpl-icon-btn" title="Edit in Canvas">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form action="{{ route('school.engagement.designs.clone', $design->id) }}" method="POST" class="d-inline" title="Duplicate Creative">
                                    @csrf
                                    <button type="submit" class="tpl-icon-btn border-0 bg-transparent" style="cursor: pointer;">
                                        <i class="fas fa-clone"></i>
                                    </button>
                                </form>
                                <form action="{{ route('school.engagement.designs.destroy', $design->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this creative?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tpl-icon-btn border-0 bg-transparent text-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="tpl-card p-5 text-center">
            <div class="mb-3 text-muted" style="font-size: 2.5rem;">
                <i class="fas fa-palette"></i>
            </div>
            <h5 class="font-weight-bold text-dark">No Creatives Found</h5>
            <p class="text-muted mb-3">No templates or custom designs match this filter category.</p>
            <div>
                <a href="{{ route('school.engagement.designs.create') }}" class="btn btn-sm tpl-btn-primary mr-2">
                    <i class="fas fa-images mr-1"></i> Browse Ready Templates
                </a>
                <a href="{{ route('school.engagement.designs.create', ['category' => 'birthday_card', 'template_id' => 'scratch']) }}" class="btn btn-sm tpl-btn-ghost">
                    <i class="fas fa-plus mr-1"></i> Create from Scratch
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

