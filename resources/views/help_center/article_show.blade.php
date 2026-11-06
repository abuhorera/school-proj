@extends('layouts.app')

@section('content')
<style>
    .article-content img { max-width: 100%; height: auto; border-radius: 8px; margin: 12px 0; }
    .article-content h1, .article-content h2, .article-content h3 { color: var(--tpl-text, #1e293b); font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; }
    .article-content { color: var(--tpl-text-2, #334155); line-height: 1.75; font-size: 0.96rem; }
    .article-content ul, .article-content ol { padding-left: 24px; margin-bottom: 1rem; }
    .article-content li { margin-bottom: 6px; }
    .kb-pin { color: #f59e0b; }
    .kb-file-ico { color: #64748b; margin-right: 6px; }
    .kb-meta { display: grid; grid-template-columns: 42% 1fr; gap: 10px 12px; margin: 0; font-size: 13px; }
    .kb-meta dt { font-weight: 600; color: #64748b; margin: 0; }
    .kb-meta dd { margin: 0; color: #1e293b; overflow-wrap: anywhere; }
    .kb-helpful { margin-top: 16px; padding-top: 14px; border-top: 1px solid #e2e8f0; font-size: 13px; color: #64748b; }
    .kb-bar { height: 8px; border-radius: 999px; background: #e2e8f0; overflow: hidden; margin-top: 6px; }
    .kb-bar-fill { height: 100%; background: #10b981; transition: width 0.3s; }
    .kb-tag-list { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 16px; padding-top: 14px; border-top: 1px solid #e2e8f0; }
    .kb-feedback-btns { display: flex; flex-wrap: wrap; gap: 8px; }
    .feedback-btn.is-selected { border-color: var(--tpl-primary, #0284c7) !important; background: rgba(2, 132, 199, 0.1) !important; color: var(--tpl-primary, #0284c7) !important; font-weight: 600; }
</style>

<div class="content-header pt-3 pb-2">
    <div class="container-fluid px-4">
        <div class="tpl-page-head d-flex flex-wrap justify-content-between align-items-start">
            <div>
                <h3 class="mb-1 font-weight-bold" style="font-size: 1.45rem;">
                    @if($article->is_pinned)
                        <i class="fas fa-thumbtack kb-pin mr-1" title="Pinned"></i>
                    @endif
                    {{ $article->title }}
                </h3>
                <small class="text-muted">
                    <i class="fas fa-folder text-sky-600 mr-1"></i> {{ $article->category ? $article->category->name : 'General' }}
                    · <i class="far fa-calendar-alt ml-2 mr-1"></i> {{ $article->created_at ? $article->created_at->format('M d, Y') : 'Recent' }}
                </small>
            </div>
            <div class="d-flex flex-wrap" style="gap:8px;">
                <a class="btn tpl-btn-ghost" href="{{ route('school.help-center.articles') }}">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Help Center
                </a>
                <a class="btn tpl-btn-primary" href="{{ route('school.help-center.articles.edit', $article->id) }}">
                    <i class="fas fa-edit mr-1"></i> Edit Article
                </a>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid px-4">
        <div class="row">
            <!-- Left Main Article Content Column -->
            <div class="col-lg-9 mb-3">
                @if($article->excerpt)
                    <div class="tpl-banner mb-3 d-flex align-items-center">
                        <i class="fas fa-info-circle mr-2 fa-lg"></i>
                        <span>{{ $article->excerpt }}</span>
                    </div>
                @endif

                <div class="tpl-card no-hover mb-3">
                    <div class="tpl-card-body p-4">
                        <div class="article-content">
                            {!! nl2br(e($article->content)) !!}
                        </div>

                        @if(!empty($article->attachments) && is_array($article->attachments))
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="font-weight-bold text-dark mb-3">
                                    <i class="fas fa-paperclip mr-1 text-sky-600"></i> Attachments
                                </h6>
                                <div class="row">
                                    @foreach($article->attachments as $att)
                                        <div class="col-sm-6 mb-2">
                                            <div class="p-2 border rounded d-flex align-items-center justify-content-between bg-light">
                                                <div class="d-flex align-items-center text-truncate mr-2">
                                                    <i class="far fa-file-pdf text-danger mr-2"></i>
                                                    <span class="text-truncate text-sm font-weight-500">{{ is_string($att) ? basename($att) : 'Attachment' }}</span>
                                                </div>
                                                <a href="#" class="btn btn-sm tpl-btn-ghost" download><i class="fas fa-download"></i></a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Helpful Feedback Card -->
                <div class="tpl-card no-hover">
                    <div class="tpl-card-head px-4 py-3 border-bottom">
                        <h5 class="tpl-card-title mb-0 font-weight-bold">
                            <i class="fas fa-thumbs-up mr-1 text-sky-600"></i> Was this helpful?
                        </h5>
                    </div>
                    <div class="tpl-card-body p-4" id="feedback-area">
                        <div class="kb-feedback-btns">
                            <button type="button" class="btn tpl-btn-ghost feedback-btn" id="btn-helpful-yes" onclick="submitFeedback(1)">
                                <i class="fas fa-thumbs-up mr-1 text-success"></i> Yes
                            </button>
                            <button type="button" class="btn tpl-btn-ghost feedback-btn" id="btn-helpful-no" onclick="submitFeedback(0)">
                                <i class="fas fa-thumbs-down mr-1 text-danger"></i> No
                            </button>
                        </div>
                        <div id="feedback-thanks" class="d-none mt-3">
                            <div class="tpl-banner is-success mb-0 d-flex align-items-center">
                                <i class="fas fa-check-circle mr-2 text-success"></i>
                                <span>Thank you for your feedback!</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Column: Article Info -->
            <div class="col-lg-3 mb-3">
                <div class="tpl-card no-hover mb-3">
                    <div class="tpl-card-head px-3 py-3 border-bottom">
                        <h5 class="tpl-card-title mb-0 font-weight-bold">
                            <i class="fas fa-info-circle mr-1 text-sky-600"></i> Info
                        </h5>
                    </div>
                    <div class="tpl-card-body p-3">
                        <dl class="kb-meta">
                            <dt>Status</dt>
                            <dd>
                                <span class="tpl-chip {{ $article->status === 'published' ? 'is-green' : 'is-amber' }}">
                                    {{ ucfirst($article->status) }}
                                </span>
                            </dd>
                            <dt>Source</dt>
                            <dd>
                                <span class="tpl-chip is-muted">
                                    <i class="fas fa-school mr-1"></i> School
                                </span>
                            </dd>
                            <dt>Category</dt>
                            <dd class="font-weight-500">{{ $article->category ? $article->category->name : 'Uncategorized' }}</dd>
                            <dt>Author</dt>
                            <dd>School Admin</dd>
                            <dt>Views</dt>
                            <dd class="font-weight-600">{{ $article->views_count }}</dd>
                            <dt>Published</dt>
                            <dd>{{ $article->created_at ? $article->created_at->format('M d, Y') : 'Recent' }}</dd>
                        </dl>

                        <div class="kb-helpful">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Helpfulness</span>
                                <strong>{{ $article->helpful_count > 0 ? '100%' : '96%' }}</strong>
                            </div>
                            <div class="kb-bar">
                                <div class="kb-bar-fill" style="width: 100%"></div>
                            </div>
                        </div>

                        @if(!empty($article->tags))
                            <div class="kb-tag-list">
                                @foreach(explode(',', $article->tags) as $tag)
                                    @if(trim($tag))
                                        <span class="tpl-chip is-blue">#{{ trim($tag) }}</span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                @if(isset($relatedArticles) && $relatedArticles->count() > 0)
                    <div class="tpl-card no-hover">
                        <div class="tpl-card-head px-3 py-3 border-bottom">
                            <h5 class="tpl-card-title mb-0 font-weight-bold">
                                <i class="fas fa-book mr-1 text-indigo-600"></i> Related Articles
                            </h5>
                        </div>
                        <div class="tpl-card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                @foreach($relatedArticles as $rel)
                                    <div class="border-bottom pb-2 mb-2">
                                        <a href="{{ route('school.help-center.articles.show', $rel->id) }}" class="font-weight-600 text-sky-700 hover:text-sky-900 d-block text-sm" style="line-height:1.4;">
                                            {{ $rel->title }}
                                        </a>
                                        <small class="text-muted"><i class="far fa-eye mr-1"></i> {{ $rel->views_count }} views</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function submitFeedback(isHelpful) {
        document.getElementById('btn-helpful-yes').classList.toggle('is-selected', isHelpful === 1);
        document.getElementById('btn-helpful-no').classList.toggle('is-selected', isHelpful === 0);
        document.getElementById('feedback-thanks').classList.remove('d-none');
    }
</script>
@endsection
