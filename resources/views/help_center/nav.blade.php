<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap');

    :root {
        --kb-primary: #0284c7;
        --kb-primary-dark: #0369a1;
        --kb-primary-light: #e0f2fe;
        --kb-accent: #6366f1;
        --kb-text-dark: #0f172a;
        --kb-text-muted: #64748b;
        --kb-border: #e2e8f0;
        --kb-card-bg: #ffffff;
    }

    .kb-title-area, .kb-tabs, .kb-tab, .btn-kb-primary, .btn-kb-outline {
        font-family: 'Inter', sans-serif;
    }

    .kb-title-area h3 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        color: #0f172a;
        font-size: 1.5rem;
    }

    .kb-tabs-wrap {
        border-bottom: 1px solid var(--kb-border);
        margin-bottom: 25px;
        overflow-x: auto;
    }

    .kb-tabs {
        display: flex;
        gap: 20px;
        flex-wrap: nowrap;
        min-width: 100%;
    }

    .kb-tab {
        padding: 10px 5px;
        color: var(--kb-text-muted);
        font-weight: 500;
        text-decoration: none;
        position: relative;
        font-size: 0.95rem;
        transition: color 0.2s;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .kb-tab:hover {
        color: #0f172a;
        text-decoration: none;
    }

    .kb-tab.active {
        color: var(--kb-primary);
        font-weight: 600;
    }

    .kb-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: var(--kb-primary);
    }

    .kb-card {
        background: #ffffff;
        border: 1px solid var(--kb-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }

    .btn-kb-primary {
        background-color: var(--kb-primary);
        border-color: var(--kb-primary);
        color: #fff !important;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        transition: all 0.2s;
    }
    .btn-kb-primary:hover {
        background-color: var(--kb-primary-dark);
        border-color: var(--kb-primary-dark);
        color: #fff !important;
        transform: translateY(-1px);
    }

    .btn-kb-outline {
        background-color: #fff;
        border: 1px solid var(--kb-border);
        color: #0f172a !important;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.88rem;
        transition: all 0.2s;
    }
    .btn-kb-outline:hover {
        background-color: #f8fafc;
        color: #0f172a !important;
    }

    .tpl-chip {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .tpl-chip.is-indigo { background-color: #e0e7ff; color: #4338ca; }
    .tpl-chip.is-amber { background-color: #fef3c7; color: #b45309; }
    .tpl-chip.is-blue { background-color: #e0f2fe; color: #0369a1; }
    .tpl-chip.is-emerald { background-color: #d1fae5; color: #047857; }
    .tpl-chip.is-purple { background-color: #f3e8ff; color: #7e22ce; }

    .tpl-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
    }
    .tpl-table th {
        background: #f8fafc;
        padding: 12px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #475569;
        border-bottom: 1px solid var(--kb-border);
    }
    .tpl-table td {
        padding: 14px 16px;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .tpl-table tr:hover td { background-color: #f8fafc; }
</style>

<!-- Header & Title Area -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2 pt-3">
    <div class="kb-title-area">
        <h3 class="flex items-center gap-2">
            <i class="fa-solid fa-circle-question text-sky-500"></i>
            Help Center & Knowledge Base
        </h3>
        <p class="text-sm text-slate-500 mt-0.5">Campus user guides, troubleshooting FAQs, category taxonomy, and AI chatbot.</p>
    </div>
    <div class="d-flex gap-2 mb-3 mb-md-0">
        <a href="{{ route('school.help-center.articles.create') }}" class="btn-kb-primary">
            <i class="fas fa-plus mr-1"></i> Add Your Article
        </a>
        <a href="{{ route('school.help-center.categories.create') }}" class="btn-kb-outline">
            <i class="fas fa-folder-plus mr-1"></i> Add Category
        </a>
    </div>
</div>

<!-- Sub-navigation Tabs -->
<div class="kb-tabs-wrap no-scrollbar">
    <div class="kb-tabs">
        <a href="{{ route('school.help-center.categories') }}" class="kb-tab {{ request()->routeIs('school.help-center.categories*') ? 'active' : '' }}">
            <i class="fas fa-folder mr-1"></i> Manage Categories
        </a>
        <a href="{{ route('school.help-center.articles') }}" class="kb-tab {{ request()->routeIs('school.help-center.articles*') && !request()->routeIs('school.help-center.articles.create') ? 'active' : '' }}">
            <i class="fas fa-book-open mr-1"></i> Browse Articles
        </a>
        <a href="{{ route('school.help-center.chatbot') }}" class="kb-tab {{ request()->routeIs('school.help-center.chatbot*') ? 'active text-purple-600 font-bold' : 'text-purple-600 font-bold' }}">
            <i class="fas fa-robot text-purple-500 mr-1"></i> AI Chatbot
        </a>
    </div>
</div>

