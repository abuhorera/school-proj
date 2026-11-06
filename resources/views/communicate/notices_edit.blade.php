@extends('layouts.app')

@section('title', 'Edit Notice')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('school.notices') }}" class="hover:text-blue-600 transition">Notice Board</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Edit Notice</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Notice</h1>
                    <p class="text-xs text-slate-500">Update announcement wording, priority, and expiration settings.</p>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('school.notices') }}" class="inline-flex items-center space-x-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Notice Board</span>
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('school.notices.update', $notice->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Notice Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" required value="{{ old('title', $notice->title) }}" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('title') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Recipient Group & Class -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Send To (Target Audience) <span class="text-rose-500">*</span>
                    </label>
                    <select name="recipient_type" id="recipientTypeSelect" required onchange="toggleNoticeClass(this.value)" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="all" {{ $notice->recipient_type === 'all' ? 'selected' : '' }}>All (Entire School)</option>
                        <option value="staff" {{ $notice->recipient_type === 'staff' ? 'selected' : '' }}>All Staff Only</option>
                        <option value="parents" {{ $notice->recipient_type === 'parents' ? 'selected' : '' }}>All Parents Only</option>
                        <option value="class" {{ $notice->recipient_type === 'class' ? 'selected' : '' }}>A Specific Class</option>
                    </select>
                </div>

                <div id="noticeClassWrap" class="{{ $notice->recipient_type === 'class' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Select Class <span class="text-rose-500">*</span>
                    </label>
                    <select name="class_id" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $notice->class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Publish Date & Priority -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Publish Date <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="published_at" required value="{{ old('published_at', $notice->published_date) }}" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                        Priority Level
                    </label>
                    <select name="priority" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="normal" {{ $notice->priority === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ $notice->priority === 'high' ? 'selected' : '' }}>High Priority</option>
                        <option value="urgent" {{ $notice->priority === 'urgent' ? 'selected' : '' }}>Urgent Alert</option>
                    </select>
                </div>
            </div>

            <!-- Content -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Notice Content / Description <span class="text-rose-500">*</span>
                </label>
                <textarea name="content" rows="6" required class="w-full p-3.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none leading-relaxed">{{ old('content', $notice->description) }}</textarea>
                @error('content') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Document Attachment -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Document Attachment
                </label>
                @if($notice->attachment)
                    <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded-lg text-xs flex items-center justify-between">
                        <span class="text-blue-800 font-semibold truncate">{{ basename($notice->attachment) }}</span>
                        <a href="{{ asset($notice->attachment) }}" target="_blank" class="text-blue-600 hover:underline">View Current File</a>
                    </div>
                @endif
                <input type="file" name="attachment" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- App Popup Alert Box -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <div class="flex items-center space-x-2.5">
                    <input type="checkbox" id="is_popup" name="is_popup" value="1" {{ $notice->is_popup ? 'checked' : '' }} onchange="togglePopupExpiry(this.checked)" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4">
                    <label for="is_popup" class="text-xs font-bold text-slate-800 cursor-pointer">
                        App Popup Alert (Show immediately on user screen when opening portal or parent app)
                    </label>
                </div>
                <div id="popupExpiryWrap" class="{{ $notice->is_popup ? '' : 'hidden' }} pl-6">
                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">
                        Popup Expires On (Optional auto-dismiss date)
                    </label>
                    <input type="date" name="popup_expires_at" value="{{ $notice->popup_expires_at }}" class="px-3 py-1.5 text-xs border border-slate-300 rounded-lg focus:outline-none max-w-xs">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
                <a href="{{ route('school.notices') }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-save mr-1.5"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleNoticeClass(val) {
    const wrap = document.getElementById('noticeClassWrap');
    if (val === 'class') {
        wrap.classList.remove('hidden');
    } else {
        wrap.classList.add('hidden');
    }
}

function togglePopupExpiry(checked) {
    const wrap = document.getElementById('popupExpiryWrap');
    if (checked) {
        wrap.classList.remove('hidden');
    } else {
        wrap.classList.add('hidden');
    }
}
</script>
@endsection

