@extends('layouts.app')

@section('content')
<style>
    :root {
        --bg-primary: #0a0a1a;
        --bg-sidebar: rgba(15, 15, 35, 0.85);
        --bg-topbar: rgba(15,15,35,0.7);
        --bg-input-area: rgba(15,15,35,0.6);
        --bg-input: rgba(255,255,255,0.05);
        --bg-input-focus: rgba(255,255,255,0.08);
        --bg-msg-ai: rgba(255,255,255,0.06);
        --bg-conv-hover: rgba(255,255,255,0.06);
        --bg-conv-active: rgba(108,99,255,0.2);
        --bg-chip: rgba(108,99,255,0.1);
        --bg-chip-hover: rgba(108,99,255,0.25);
        --bg-feedback: rgba(255,255,255,0.06);
        --bg-typing: rgba(255,255,255,0.05);
        --border-main: rgba(255,255,255,0.08);
        --border-input: rgba(255,255,255,0.12);
        --border-chip: rgba(108,99,255,0.3);
        --border-conv-active: rgba(108,99,255,0.4);
        --text-primary: #f3f4f6;
        --text-secondary: #9ca3af;
        --text-muted: #6b7280;
        --text-conv-title: #d1d5db;
        --text-input: #f3f4f6;
        --text-placeholder: #6b7280;
        --text-welcome-sub: #9ca3af;
        --text-chip: #a78bfa;
        --avatar-bg: #1e1b4b;
        --orb-opacity: 0.25;
        --mode-badge-bg: rgba(108,99,255,0.15);
        --mode-badge-text: #c4b5fd;
    }

    .light-mode {
        --bg-primary: #f8fafc;
        --bg-sidebar: #ffffff;
        --bg-topbar: #ffffff;
        --bg-input-area: #ffffff;
        --bg-input: #f1f5f9;
        --bg-input-focus: #e2e8f0;
        --bg-msg-ai: #f1f5f9;
        --bg-conv-hover: #f8fafc;
        --bg-conv-active: #ede9fe;
        --bg-chip: #f5f3ff;
        --bg-chip-hover: #ede9fe;
        --bg-feedback: #f1f5f9;
        --bg-typing: #f1f5f9;
        --border-main: #e2e8f0;
        --border-input: #cbd5e1;
        --border-chip: #c4b5fd;
        --border-conv-active: #8b5cf6;
        --text-primary: #0f172a;
        --text-secondary: #475569;
        --text-muted: #64748b;
        --text-conv-title: #1e293b;
        --text-input: #0f172a;
        --text-placeholder: #94a3b8;
        --text-welcome-sub: #64748b;
        --text-chip: #6366f1;
        --avatar-bg: #e0e7ff;
        --orb-opacity: 0.08;
        --mode-badge-bg: #ede9fe;
        --mode-badge-text: #6d28d9;
    }

    .ai-container {
        position: relative;
        background: var(--bg-primary);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-main);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .ai-bg {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        z-index: 0; overflow: hidden; pointer-events: none;
    }
    .ai-bg .orb {
        position: absolute; border-radius: 50%;
        filter: blur(80px); opacity: var(--orb-opacity);
        animation: orbFloat 14s ease-in-out infinite;
    }
    .ai-bg .orb-1 { width: 350px; height: 350px; background: radial-gradient(circle, #6366f1, #3730a3); top: -80px; right: -60px; }
    .ai-bg .orb-2 { width: 300px; height: 300px; background: radial-gradient(circle, #0284c7, #0369a1); bottom: -60px; left: -40px; animation-delay: -4s; }
    .ai-bg .orb-3 { width: 220px; height: 220px; background: radial-gradient(circle, #8b5cf6, #ec4899); top: 35%; left: 35%; animation-delay: -8s; }

    @keyframes orbFloat {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(25px, -30px) scale(1.08); }
        66% { transform: translate(-20px, 20px) scale(0.95); }
    }

    .chat-wrapper {
        position: relative;
        z-index: 1;
        display: flex;
        height: 720px;
        min-height: 520px;
    }

    /* Sidebar */
    .chat-sidebar {
        width: 280px;
        background: var(--bg-sidebar);
        backdrop-filter: blur(20px);
        border-right: 1px solid var(--border-main);
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }
    .sidebar-top { padding: 16px; border-bottom: 1px solid var(--border-main); }
    .btn-new-chat {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid rgba(108, 99, 255, 0.4);
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(2, 132, 199, 0.2));
        color: var(--mode-badge-text);
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    .btn-new-chat:hover {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.4), rgba(2, 132, 199, 0.4));
        color: #ffffff;
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
    }
    .conversation-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
    }
    .conversation-item {
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        margin-bottom: 4px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .conversation-item:hover {
        background: var(--bg-conv-hover);
    }
    .conversation-item.active {
        background: var(--bg-conv-active);
        border-color: var(--border-conv-active);
    }
    .conv-title {
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--text-conv-title);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .conv-time {
        font-size: 0.7rem;
        color: var(--text-muted);
        margin-top: 2px;
    }
    .sidebar-bottom {
        padding: 14px;
        border-top: 1px solid var(--border-main);
    }
    .mode-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        background: var(--mode-badge-bg);
        color: var(--mode-badge-text);
        font-size: 0.75rem;
        font-weight: 600;
    }
    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 8px #10b981;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.85); }
    }

    /* Main Chat Area */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: transparent;
        overflow: hidden;
    }
    .chat-topbar {
        height: 60px;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-main);
        background: var(--bg-topbar);
        backdrop-filter: blur(10px);
    }
    .topbar-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ai-icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #0284c7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1rem;
        box-shadow: 0 0 10px rgba(99,102,241,0.4);
    }
    .topbar-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-primary);
    }
    .topbar-status {
        font-size: 0.72rem;
        color: #10b981;
        font-weight: 600;
    }
    .btn-theme-toggle {
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        color: var(--text-secondary);
        width: 34px;
        height: 34px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-theme-toggle:hover {
        color: var(--text-primary);
        border-color: #6366f1;
    }

    /* Messages Pane */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .welcome-screen {
        margin: auto;
        text-align: center;
        max-width: 540px;
        padding: 20px;
    }
    .ai-avatar-ring {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #0284c7);
        padding: 3px;
        margin: 0 auto 16px;
        box-shadow: 0 0 25px rgba(99,102,241,0.4);
    }
    .ai-avatar-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--avatar-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #818cf8;
        font-size: 2rem;
    }
    .welcome-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 6px;
    }
    .welcome-subtitle {
        font-size: 0.9rem;
        color: var(--text-welcome-sub);
        margin-bottom: 24px;
    }
    .suggestion-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }
    .suggestion-chip {
        padding: 9px 16px;
        border-radius: 20px;
        background: var(--bg-chip);
        border: 1px solid var(--border-chip);
        color: var(--text-chip);
        font-size: 0.83rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .suggestion-chip:hover {
        background: var(--bg-chip-hover);
        transform: translateY(-2px);
    }

    /* Message Bubbles */
    .message {
        display: flex;
        gap: 12px;
        max-width: 85%;
    }
    .message.user-msg {
        align-self: flex-end;
        flex-direction: row-reverse;
    }
    .message.assistant-msg {
        align-self: flex-start;
    }
    .msg-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }
    .user-msg .msg-avatar {
        background: #0284c7;
        color: #fff;
    }
    .assistant-msg .msg-avatar {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
    }
    .msg-bubble {
        padding: 12px 16px;
        font-size: 0.9rem;
        line-height: 1.6;
        word-wrap: break-word;
    }
    .user-msg .msg-bubble {
        background: linear-gradient(135deg, #0284c7, #0369a1);
        color: #ffffff;
        border-radius: 16px 16px 4px 16px;
    }
    .assistant-msg .msg-bubble {
        background: var(--bg-msg-ai);
        border: 1px solid var(--border-input);
        color: var(--text-primary);
        border-radius: 16px 16px 16px 4px;
        backdrop-filter: blur(10px);
    }

    /* Typing indicator */
    .typing-area {
        padding: 0 24px;
        min-height: 24px;
    }
    .typing-indicator {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: var(--bg-typing);
        border-radius: 12px;
        width: fit-content;
    }
    .typing-dots {
        display: flex;
        gap: 4px;
    }
    .typing-dots span {
        width: 6px;
        height: 6px;
        background: #8b5cf6;
        border-radius: 50%;
        animation: typingDot 1.4s infinite ease-in-out both;
    }
    .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
    .typing-dots span:nth-child(2) { animation-delay: -0.16s; }
    @keyframes typingDot {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    /* Input Area */
    .chat-input-area {
        padding: 16px 20px;
        background: var(--bg-input-area);
        border-top: 1px solid var(--border-main);
    }
    .input-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-input);
        border: 1px solid var(--border-input);
        border-radius: 14px;
        padding: 8px 14px;
        transition: border-color 0.2s;
    }
    .input-wrapper:focus-within {
        border-color: #6366f1;
        background: var(--bg-input-focus);
    }
    #chat-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: var(--text-input);
        font-size: 0.92rem;
        resize: none;
        max-height: 120px;
        line-height: 1.4;
    }
    #chat-input::placeholder {
        color: var(--text-placeholder);
    }
    .btn-send-ai {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #0284c7);
        border: none;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, opacity 0.2s;
    }
    .btn-send-ai:hover {
        transform: scale(1.05);
    }
    .btn-send-ai:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<div class="container-fluid px-4 py-3">
    @include('help_center.nav')

    <div class="ai-container" id="chatbot-container">
        <!-- Floating Ambient Glow Orbs -->
        <div class="ai-bg">
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>
        </div>

        <div class="chat-wrapper">
            <!-- Left Sidebar -->
            <div class="chat-sidebar">
                <div class="sidebar-top">
                    <button class="btn-new-chat" id="btn-new-chat" onclick="startNewChat()">
                        <i class="fas fa-plus"></i> New Chat
                    </button>
                </div>

                <div class="conversation-list" id="conversation-list">
                    @forelse($conversations as $c)
                        <div class="conversation-item" data-id="{{ $c->id }}" onclick="loadConversation({{ $c->id }}, this)">
                            <div class="conv-title">{{ $c->title }}</div>
                            <div class="conv-time">{{ $c->created_at ? $c->created_at->diffForHumans() : 'Recently' }}</div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted" style="font-size: 0.8rem;">
                            <i class="far fa-comments fa-2x mb-2 d-block opacity-50"></i>
                            No past threads yet
                        </div>
                    @endforelse
                </div>

                <div class="sidebar-bottom">
                    <div id="usage-stats" style="margin-bottom: 10px; font-size: 0.75rem; color: var(--text-muted); text-align: center;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <span>AI Limit</span>
                            <span>Unlimited ∞</span>
                        </div>
                        <div style="height:4px;background:rgba(255,255,255,0.1);border-radius:2px;overflow:hidden;">
                            <div style="width:25%;height:100%;background:#10b981;"></div>
                        </div>
                    </div>
                    <div class="mode-badge">
                        <div class="pulse-dot"></div>
                        <span id="model-display-name">Intent AI Assistant</span>
                    </div>
                </div>
            </div>

            <!-- Right Main Chat Pane -->
            <div class="chat-main">
                <div class="chat-topbar">
                    <div class="topbar-left">
                        <div class="ai-icon-circle">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div>
                            <div class="topbar-title">Multi-School ERP Assistant</div>
                            <div class="topbar-status">● Live Campus Engine</div>
                        </div>
                    </div>
                    <div class="topbar-actions">
                        <button class="btn-theme-toggle" id="btn-theme-toggle" title="Toggle Light/Dark Mode">
                            <i class="fas fa-sun" id="theme-icon"></i>
                        </button>
                    </div>
                </div>

                <div class="chat-messages" id="chat-messages">
                    <div class="welcome-screen" id="welcome-screen">
                        <div class="ai-avatar-ring">
                            <div class="ai-avatar-inner">
                                <i class="fas fa-brain"></i>
                            </div>
                        </div>
                        <div class="welcome-title">How can I help you today?</div>
                        <div class="welcome-subtitle">Ask questions about student strength, fee collections, timetable, attendance, or hostel/transport fleets.</div>
                        <div class="suggestion-chips">
                            <div class="suggestion-chip" onclick="sendSuggestion(this)">
                                📊 Today's attendance summary
                            </div>
                            <div class="suggestion-chip" onclick="sendSuggestion(this)">
                                💰 Fee collection this month
                            </div>
                            <div class="suggestion-chip" onclick="sendSuggestion(this)">
                                👨‍🎓 Total student enrollment
                            </div>
                            <div class="suggestion-chip" onclick="sendSuggestion(this)">
                                🚌 Transport fleet status
                            </div>
                            <div class="suggestion-chip" onclick="sendSuggestion(this)">
                                🏨 Hostel room occupancy
                            </div>
                        </div>
                    </div>
                </div>

                <div class="typing-area">
                    <div class="typing-indicator" id="typing-indicator">
                        <div class="typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span style="color:var(--text-muted);font-size:0.75rem;margin-left:4px;">Thinking...</span>
                    </div>
                </div>

                <div class="chat-input-area">
                    <div class="input-wrapper">
                        <textarea id="chat-input" rows="1" placeholder="Ask about school data, attendance, fees, exams..."></textarea>
                        <button class="btn-send-ai" id="btn-send" onclick="sendMessage()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentConversationId = null;
    const csrfToken = '{{ csrf_token() }}';
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const typingIndicator = document.getElementById('typing-indicator');
    const welcomeScreen = document.getElementById('welcome-screen');
    const btnSend = document.getElementById('btn-send');
    const container = document.getElementById('chatbot-container');

    // Theme toggler
    const btnThemeToggle = document.getElementById('btn-theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    if (localStorage.getItem('kb_ai_theme') === 'light') {
        container.classList.add('light-mode');
        themeIcon.className = 'fas fa-moon';
    }

    btnThemeToggle.addEventListener('click', () => {
        container.classList.toggle('light-mode');
        const isLight = container.classList.contains('light-mode');
        localStorage.setItem('kb_ai_theme', isLight ? 'light' : 'dark');
        themeIcon.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
    });

    // Handle suggestion chip click
    function sendSuggestion(chip) {
        const text = chip.innerText.replace(/^[^\w\s]+/, '').trim();
        chatInput.value = text;
        sendMessage();
    }

    // Auto-expand textarea
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    chatInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function startNewChat() {
        currentConversationId = null;
        chatMessages.innerHTML = '';
        chatMessages.appendChild(welcomeScreen);
        welcomeScreen.style.display = 'block';
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
    }

    function appendMessage(sender, text) {
        if (welcomeScreen) {
            welcomeScreen.style.display = 'none';
        }

        const msgDiv = document.createElement('div');
        msgDiv.className = `message ${sender === 'user' ? 'user-msg' : 'assistant-msg'}`;

        const avatar = document.createElement('div');
        avatar.className = 'msg-avatar';
        avatar.innerHTML = sender === 'user' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';

        const bubble = document.createElement('div');
        bubble.className = 'msg-bubble';
        
        // Simple markdown parsing for bold and line breaks
        let formatted = text
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
        
        bubble.innerHTML = formatted;

        msgDiv.appendChild(avatar);
        msgDiv.appendChild(bubble);
        chatMessages.appendChild(msgDiv);

        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async function sendMessage() {
        const text = chatInput.value.trim();
        if (!text) return;

        appendMessage('user', text);
        chatInput.value = '';
        chatInput.style.height = 'auto';
        btnSend.disabled = true;
        typingIndicator.style.display = 'flex';
        chatMessages.scrollTop = chatMessages.scrollHeight;

        try {
            const res = await fetch("{{ route('school.help-center.chatbot.message') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: text,
                    conversation_id: currentConversationId
                })
            });

            const data = await res.json();
            typingIndicator.style.display = 'none';
            btnSend.disabled = false;

            if (data.success) {
                appendMessage('assistant', data.response);
                if (data.conversation_id) {
                    currentConversationId = data.conversation_id;
                    updateConversationList(data.conversation_id, text);
                }
            } else {
                appendMessage('assistant', 'Sorry, I encountered an issue while retrieving that information. Please try again.');
            }
        } catch (err) {
            typingIndicator.style.display = 'none';
            btnSend.disabled = false;
            appendMessage('assistant', 'Connection error. Please ensure you are connected to the network.');
        }
    }

    function updateConversationList(convId, firstMsg) {
        let existing = document.querySelector(`.conversation-item[data-id="${convId}"]`);
        if (!existing) {
            const list = document.getElementById('conversation-list');
            const item = document.createElement('div');
            item.className = 'conversation-item active';
            item.setAttribute('data-id', convId);
            item.onclick = function() { loadConversation(convId, this); };
            item.innerHTML = `
                <div class="conv-title">${firstMsg.substring(0, 30)}</div>
                <div class="conv-time">Just now</div>
            `;
            list.insertBefore(item, list.firstChild);
        }
    }

    async function loadConversation(id, element) {
        currentConversationId = id;
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active'));
        if (element) element.classList.add('active');

        try {
            const res = await fetch(`{{ url('/school/knowledge-base/chatbot/conversations') }}/${id}`);
            const data = await res.json();
            if (data.success && data.conversation) {
                chatMessages.innerHTML = '';
                const msgs = data.conversation.messages || [];
                msgs.forEach(m => {
                    appendMessage(m.role === 'user' ? 'user' : 'assistant', m.content);
                });
            }
        } catch (e) {
            console.error('Error loading thread', e);
        }
    }
</script>
@endsection

