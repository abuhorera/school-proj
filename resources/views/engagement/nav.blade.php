@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .eng-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
    }
    .eng-title-area {
        margin-bottom: 20px;
    }
    .eng-title-area h3 {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.5rem;
        margin-bottom: 3px;
    }
    .eng-title-area small {
        color: #64748b;
        font-size: 0.9rem;
    }

    .eng-tabs-wrap {
        display: flex;
        align-items: stretch;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 22px;
        overflow-x: auto;
    }
    .eng-tabs {
        display: flex;
        flex-wrap: nowrap;
        align-items: stretch;
        gap: 0 16px;
        flex: 1 1 auto;
        min-width: 0;
    }
    .eng-tab {
        padding: 10px 4px;
        color: #64748b;
        font-weight: 500;
        text-decoration: none !important;
        position: relative;
        font-size: 0.88rem;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: color 0.15s ease;
    }
    .eng-tab:hover {
        color: #db2777;
    }
    .eng-tab.active {
        color: #db2777;
        font-weight: 600;
    }
    .eng-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2.5px;
        background-color: #db2777;
        border-radius: 2px 2px 0 0;
    }

    /* Template / Creative Manage Card */
    .tpl-manage-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        position: relative;
    }
    .tpl-manage-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px -4px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .tpl-preview-box {
        height: 180px;
        background: #f8fafc;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .tpl-designer-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6366f1;
        font-size: 0.75rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        z-index: 2;
    }
    .tpl-card-footer {
        padding: 12px 14px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }
    .tpl-card-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .tpl-card-actions {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Pills filter */
    .tpl-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 0.82rem;
        font-weight: 500;
        color: #475569;
        background: #f1f5f9;
        text-decoration: none !important;
        transition: all 0.15s ease;
    }
    .tpl-pill:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .tpl-pill.active {
        background: #db2777;
        color: #ffffff;
        font-weight: 600;
    }
    .tpl-pill-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1px 6px;
        font-size: 0.72rem;
        font-weight: 700;
        border-radius: 9999px;
        background: rgba(0, 0, 0, 0.08);
    }
    .tpl-pill.active .tpl-pill-count {
        background: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    /* Chips */
    .tpl-chip {
        display: inline-block;
        padding: 2px 8px;
        font-size: 0.72rem;
        font-weight: 600;
        border-radius: 4px;
        text-transform: capitalize;
    }
    .tpl-chip.is-amber { background: #fef3c7; color: #b45309; }
    .tpl-chip.is-emerald { background: #d1fae5; color: #047857; }
    .tpl-chip.is-sky { background: #e0f2fe; color: #0369a1; }
    .tpl-chip.is-purple { background: #ede9fe; color: #6d28d9; }
    .tpl-chip.is-slate { background: #f1f5f9; color: #475569; }

    /* Buttons */
    .tpl-btn-primary {
        background: #db2777 !important;
        border-color: #db2777 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .tpl-btn-primary:hover {
        background: #be185d !important;
        border-color: #be185d !important;
        color: #ffffff !important;
    }
    .tpl-btn-ghost {
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        color: #334155 !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .tpl-btn-ghost:hover {
        background: #f8fafc !important;
        border-color: #94a3b8 !important;
    }
    .tpl-icon-btn {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: #64748b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 0.78rem;
        transition: all 0.15s ease;
    }
    .tpl-icon-btn:hover {
        color: #0f172a;
        background: #e2e8f0;
    }

    /* Switch */
    .tpl-switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
        margin-bottom: 0;
    }
    .tpl-switch input { opacity: 0; width: 0; height: 0; }
    .tpl-switch .slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1; transition: .3s; border-radius: 24px;
    }
    .tpl-switch .slider:before {
        position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px;
        background-color: white; transition: .3s; border-radius: 50%;
    }
    .tpl-switch input:checked + .slider { background-color: #db2777; }
    .tpl-switch input:checked + .slider:before { transform: translateX(20px); }

    /* Table */
    .eng-table {
        width: 100%;
        margin-bottom: 0;
        color: #334155;
        font-size: 0.88rem;
    }
    .eng-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 10px 14px;
        border-top: none;
        border-bottom: 1px solid #e2e8f0;
    }
    .eng-table td {
        padding: 11px 14px;
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
    }
    .eng-table tbody tr:hover {
        background-color: #f8fafc;
    }
</style>
@endpush

<div class="eng-container">
    {{-- Top Header Area --}}
    <div class="d-flex justify-content-between align-items-center mb-2 pt-2 flex-wrap" style="gap: 12px;">
        <div class="eng-title-area">
            <h3>Engagement</h3>
            <small>Personalised birthday cards, festival greetings, and scheduled automated dispatches for parents & staff</small>
        </div>
        <div class="d-flex align-items-center" style="gap: 8px;">
            <a href="{{ route('school.engagement.designs.create') }}" class="btn btn-sm tpl-btn-primary">
                <i class="fas fa-images mr-1"></i> Browse Templates
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-sm tpl-btn-ghost dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-plus mr-1"></i> Blank
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('school.engagement.designs.create', ['category' => 'birthday_card', 'template_id' => 'scratch']) }}">
                        <i class="fas fa-birthday-cake text-warning mr-2"></i> Birthday Card
                    </a>
                    <a class="dropdown-item" href="{{ route('school.engagement.designs.create', ['category' => 'festival_banner', 'template_id' => 'scratch']) }}">
                        <i class="fas fa-holly-berry text-danger mr-2"></i> Festival Banner
                    </a>
                    <a class="dropdown-item" href="{{ route('school.engagement.designs.create', ['category' => 'popup_card', 'template_id' => 'scratch']) }}">
                        <i class="fas fa-window-maximize text-info mr-2"></i> Pop-up Notice
                    </a>
                    <a class="dropdown-item" href="{{ route('school.engagement.designs.create', ['category' => 'story_card', 'template_id' => 'scratch']) }}">
                        <i class="fas fa-mobile-alt text-success mr-2"></i> Mobile Story
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sub-navigation Tabs --}}
    <div class="eng-tabs-wrap">
        <div class="eng-tabs">
            <a href="{{ route('school.engagement.designs') }}" class="eng-tab {{ request()->routeIs('school.engagement.designs*') ? 'active' : '' }}">
                <i class="fas fa-palette mr-1"></i> Creatives
            </a>
            <a href="{{ route('school.engagement.birthdays') }}" class="eng-tab {{ request()->routeIs('school.engagement.birthdays*') ? 'active' : '' }}">
                <i class="fas fa-birthday-cake mr-1"></i> Birthday Manager
            </a>
            <a href="{{ route('school.engagement.festivals') }}" class="eng-tab {{ request()->routeIs('school.engagement.festivals*') ? 'active' : '' }}">
                <i class="fas fa-gift mr-1"></i> Festival Greetings
            </a>
            <a href="{{ route('school.engagement.settings') }}" class="eng-tab {{ request()->routeIs('school.engagement.settings*') ? 'active' : '' }}">
                <i class="fas fa-sliders-h mr-1"></i> Auto-send Settings
            </a>
        </div>
    </div>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="border-radius: 6px;">
            <i class="fas fa-check-circle mr-2"></i>
            <div class="flex-grow-1">{{ session('success') }}</div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert" style="border-radius: 6px;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <div class="flex-grow-1">{{ session('error') }}</div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="outline: none;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>

