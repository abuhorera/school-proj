@extends('layouts.app')

@section('content')
<!-- Summernote WYSIWYG Editor Styles -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

<style>
    /* ── Responsive Flex Grid ── */
    .row { display: flex; flex-wrap: wrap; margin-right: -12px; margin-left: -12px; }
    .col-lg-9 { flex: 0 0 75%; max-width: 75%; padding: 0 12px; }
    .col-lg-3 { flex: 0 0 25%; max-width: 25%; padding: 0 12px; }
    @media (max-width: 991.98px) {
        .col-lg-9, .col-lg-3 { flex: 0 0 100%; max-width: 100%; }
    }

    /* ── Form Controls & Typography ── */
    .form-group { margin-bottom: 1.15rem; }
    .form-group label { display: inline-block; margin-bottom: 0.4rem; font-size: 0.85rem; font-weight: 600; color: #334155; }
    .form-control {
        display: block; width: 100%; padding: 0.5rem 0.85rem; font-size: 0.92rem;
        font-weight: 400; line-height: 1.5; color: #1e293b; background-color: #fff;
        background-clip: padding-box; border: 1px solid #cbd5e1; border-radius: 8px;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        box-sizing: border-box;
    }
    .form-control:focus {
        color: #1e293b; background-color: #fff; border-color: #0284c7; outline: 0;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .form-control-lg {
        padding: 0.65rem 1rem; font-size: 1.1rem; font-weight: 600; border-radius: 8px;
    }
    .btn-block { display: block; width: 100%; }

    /* ── Switch Slider ── */
    .tpl-switch { position: relative; display: inline-block; width: 38px; height: 21px; flex-shrink: 0; cursor: pointer; margin: 0; }
    .tpl-switch input { opacity: 0; width: 0; height: 0; }
    .tpl-switch .slider {
        position: absolute; inset: 0; background-color: #cbd5e1;
        border-radius: 999px; transition: background-color .2s;
    }
    .tpl-switch .slider:before {
        position: absolute; content: ""; height: 15px; width: 15px; left: 3px; top: 3px;
        background-color: #fff; border-radius: 50%; transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .tpl-switch input:checked + .slider { background-color: #0284c7; }
    .tpl-switch input:checked + .slider:before { transform: translateX(17px); }

    /* ── Summernote Overrides for Clean Theme ── */
    .note-editor.note-frame {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    .note-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 6px 8px !important;
    }
    .note-btn {
        background: #fff !important;
        border: 1px solid #e2e8f0 !important;
        color: #334155 !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
    }
    .note-btn:hover {
        background: #f1f5f9 !important;
        color: #0284c7 !important;
    }
</style>

<div class="content-header pt-3 pb-2">
    <div class="container-fluid px-4">
        <div class="tpl-page-head d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h3 class="mb-0 font-weight-bold" style="font-size: 1.45rem;">
                    <i class="fas fa-book-open mr-2 text-sky-600"></i>
                    Create Article
                </h3>
                <small class="text-muted">
                    Written for your school — platform articles cannot be edited here
                </small>
            </div>
            <a class="btn tpl-btn-ghost" href="{{ route('school.help-center.articles') }}">
                <i class="fas fa-arrow-left mr-1"></i> Back to Help Center
            </a>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid px-4">
        @if ($errors->any())
            <div class="alert alert-danger" style="border-radius:10px;">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('school.help-center.articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <!-- Left 9-column content card -->
                <div class="col-lg-9 mb-3">
                    <div class="tpl-card no-hover tpl-form mb-3">
                        <div class="tpl-card-body p-4">
                            <div class="form-group mb-3">
                                <label for="title" class="font-weight-600 text-dark">
                                    Article Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="title" name="title" value="{{ old('title') }}" placeholder="Enter article title" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="summernote" class="font-weight-600 text-dark">
                                    Content <span class="text-danger">*</span>
                                </label>
                                <textarea id="summernote" name="content" class="form-control">{!! old('content') !!}</textarea>
                            </div>

                            <div class="form-group mb-0">
                                <label for="excerpt" class="font-weight-600 text-dark">Excerpt</label>
                                <textarea class="form-control" id="excerpt" name="excerpt" rows="2" maxlength="500" placeholder="Short summary shown in search results and listings...">{{ old('excerpt') }}</textarea>
                                <small class="form-text text-muted">Short summary shown in search results and listings.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="tpl-card no-hover tpl-form">
                        <div class="tpl-card-head px-4 py-3 border-bottom">
                            <h5 class="tpl-card-title mb-0 font-weight-bold">
                                <i class="fas fa-paperclip mr-1 text-sky-600"></i> Attachments
                            </h5>
                        </div>
                        <div class="tpl-card-body p-4">
                            <input type="file" class="form-control-file" name="attachments[]" multiple>
                            <small class="form-text text-muted mt-2">Max 10MB per file. Upload policy docs, circulars, or step-by-step screenshots.</small>
                        </div>
                    </div>
                </div>

                <!-- Right 3-column Publish Settings card -->
                <div class="col-lg-3 mb-3">
                    <div class="tpl-card no-hover tpl-form">
                        <div class="tpl-card-head px-3 py-3 border-bottom">
                            <h5 class="tpl-card-title mb-0 font-weight-bold">
                                <i class="fas fa-paper-plane mr-1 text-sky-600"></i> Publish Settings
                            </h5>
                        </div>
                        <div class="tpl-card-body p-3">
                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="visibility">Visibility</label>
                                <select class="form-control" id="visibility" name="visibility">
                                    <option value="staff" {{ old('visibility', 'staff') == 'staff' ? 'selected' : '' }}>Staff & Admin</option>
                                    <option value="admin" {{ old('visibility') == 'admin' ? 'selected' : '' }}>Admin Only</option>
                                </select>
                                <small class="form-text text-muted">Articles are visible within your school only.</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="category_id">Category</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">— Uncategorized —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="tags">
                                    Tags <span class="text-muted font-weight-normal">(comma separated)</span>
                                </label>
                                <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}" placeholder="fees, admission">
                            </div>

                            <div class="form-group mb-0">
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    <input type="hidden" name="is_pinned" value="0">
                                    <label class="tpl-switch mb-0">
                                        <input type="checkbox" id="is_pinned" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                    <label class="mb-0 font-weight-normal cursor-pointer" for="is_pinned">
                                        <i class="fas fa-thumbtack mr-1 text-amber-500"></i> Pin to top
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="tpl-card-foot flex-column p-3 bg-light border-top" style="gap:8px;">
                            <button type="submit" class="btn tpl-btn-primary btn-block">
                                <i class="fas fa-save mr-1"></i> Publish
                            </button>
                            <a class="btn tpl-btn-ghost btn-block m-0 text-center" href="{{ route('school.help-center.articles') }}">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- jQuery & Summernote WYSIWYG Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 380,
            placeholder: 'Write your article guidelines, step-by-step procedures...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'strikethrough', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endsection
