@extends('layouts.app')

@section('content')
<style>
    /* ── Responsive Flex Grid ── */
    .row { display: flex; flex-wrap: wrap; margin-right: -12px; margin-left: -12px; }
    .col-lg-8 { flex: 0 0 66.666667%; max-width: 66.666667%; padding: 0 12px; }
    .col-lg-4 { flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 12px; }
    .col-md-6 { flex: 0 0 50%; max-width: 50%; padding: 0 12px; }
    .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; padding: 0 12px; }
    @media (max-width: 991.98px) {
        .col-lg-8, .col-lg-4 { flex: 0 0 100%; max-width: 100%; }
    }
    @media (max-width: 767.98px) {
        .col-md-6, .col-md-4 { flex: 0 0 100%; max-width: 100%; }
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

    /* ── Input Group with Icon Prepend ── */
    .input-group {
        position: relative; display: flex; flex-wrap: wrap; align-items: stretch; width: 100%;
    }
    .input-group-prepend { display: flex; margin-right: -1px; }
    .input-group-text {
        display: flex; align-items: center; padding: 0.5rem 0.85rem; font-size: 0.92rem;
        color: #0284c7; background-color: #f8fafc; border: 1px solid #cbd5e1;
        border-radius: 8px 0 0 8px;
    }
    .input-group > .form-control {
        position: relative; flex: 1 1 auto; width: 1%; min-width: 0; margin-bottom: 0;
        border-top-left-radius: 0; border-bottom-left-radius: 0;
    }

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

    /* ── Tips Aside Card ── */
    .tpl-aside-title {
        display: flex; align-items: center; gap: 10px; font-weight: 700;
        color: #1e293b; font-size: .95rem; margin: 0 0 12px;
    }
    .tpl-tips { list-style: none; margin: 0; padding: 0; }
    .tpl-tips li {
        position: relative; padding: 10px 0 10px 22px; font-size: 13.5px;
        line-height: 1.5; color: #475569; border-bottom: 1px solid #f1f5f9;
    }
    .tpl-tips li:last-child { border-bottom: none; }
    .tpl-tips li:before {
        content: "•"; position: absolute; left: 6px; top: 8px;
        color: #6366f1; font-size: 18px; line-height: 1;
    }
</style>

<div class="content-header pt-3 pb-2">
    <div class="container-fluid px-4">
        <div class="tpl-page-head d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h3 class="mb-0 font-weight-bold" style="font-size: 1.45rem;">
                    <i class="fas fa-layer-group mr-2 text-sky-600"></i>
                    Add Category
                </h3>
                <small class="text-muted">
                    Group help articles so readers can find them
                </small>
            </div>
            <a class="btn tpl-btn-ghost" href="{{ route('school.help-center.categories') }}">
                <i class="fas fa-arrow-left mr-1"></i> Back to Categories
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

        <div class="row">
            <div class="col-lg-8 mb-3">
                <div class="tpl-card no-hover tpl-form">
                    <div class="tpl-card-head px-4 py-3 border-bottom">
                        <h5 class="tpl-card-title mb-0 font-weight-bold">
                            <i class="fas fa-layer-group mr-1 text-sky-600"></i> Category Details
                        </h5>
                    </div>
                    <form action="{{ route('school.help-center.categories.store') }}" method="POST">
                        @csrf
                        <div class="tpl-card-body p-4">
                            <div class="form-group mb-3">
                                <label for="name" class="font-weight-600 text-dark">
                                    Category Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Campus Onboarding & Timetable" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="parent_id" class="font-weight-600 text-dark">Parent Category</label>
                                    <select class="form-control" id="parent_id" name="parent_id">
                                        <option value="">— None (Root) —</option>
                                        @foreach($parentCategories as $p)
                                            <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">Leave as Root unless this sits under another category.</small>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="icon" class="font-weight-600 text-dark">Icon Class</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="fas fa-book" id="icon-preview"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', 'fas fa-book') }}" placeholder="fas fa-book">
                                    </div>
                                    <small class="form-text text-muted">Any FontAwesome class — preview updates as you type.</small>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="font-weight-600 text-dark">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief outline of guides in this category...">{{ old('description') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group mb-0">
                                    <label for="visibility" class="font-weight-600 text-dark">
                                        Visibility <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control" id="visibility" name="visibility" required>
                                        <option value="public" {{ old('visibility', 'public') == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="staff" {{ old('visibility') == 'staff' ? 'selected' : '' }}>Staff Only</option>
                                        <option value="admin" {{ old('visibility') == 'admin' ? 'selected' : '' }}>Admin Only</option>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group mb-0">
                                    <label for="sort_order" class="font-weight-600 text-dark">Sort Order</label>
                                    <input type="number" min="0" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                                    <small class="form-text text-muted">Lower numbers appear first.</small>
                                </div>

                                <div class="col-md-4 form-group mb-0">
                                    <label class="font-weight-600 text-dark">Status</label>
                                    <div class="d-flex align-items-center mt-1" style="gap:10px;">
                                        <input type="hidden" name="is_active" value="0">
                                        <label class="tpl-switch mb-0">
                                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                        <label class="mb-0 font-weight-normal cursor-pointer" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tpl-card-foot px-4 py-3 bg-light border-top d-flex justify-content-between align-items-center">
                            <a class="btn tpl-btn-ghost" href="{{ route('school.help-center.categories') }}">
                                Cancel
                            </a>
                            <button class="btn tpl-btn-primary" type="submit">
                                <i class="fas fa-save mr-1"></i> Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Sidebar: Tips -->
            <div class="col-lg-4 mb-3">
                <div class="tpl-card no-hover">
                    <div class="tpl-card-body p-4">
                        <div class="tpl-aside-title">
                            <div class="tpl-ico is-violet" style="background:#ede9fe; color:#6d28d9; width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            About visibility
                        </div>
                        <ul class="tpl-tips">
                            <li>
                                <strong>Public</strong> — visible to everyone, including parents and students in the app.
                            </li>
                            <li>
                                <strong>Staff Only</strong> — hidden from parents; useful for internal teacher procedures and guidelines.
                            </li>
                            <li>
                                <strong>Admin Only</strong> — restricted strictly to campus administrators.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('icon-preview');
        if (iconInput && iconPreview) {
            iconInput.addEventListener('input', function() {
                iconPreview.className = this.value || 'fas fa-book';
            });
        }
    });
</script>
@endsection
