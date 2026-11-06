@extends('layouts.app')

@section('title', 'Edit Album')

@section('content')
<div class="space-y-6 max-w-3xl mx-auto">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('school.gallery') }}" class="hover:text-blue-600 transition">Image Gallery</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Edit Album</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Album Details</h1>
                    <p class="text-xs text-slate-500">Update album name, description, and visibility settings.</p>
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('school.gallery.show', $album->id) }}" class="inline-flex items-center space-x-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Album</span>
            </a>
        </div>
    </div>

    <!-- Edit Album Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <form action="{{ route('school.gallery.update', $album->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Album Title -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Album Title <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" required value="{{ old('title', $album->title) }}" class="w-full px-3.5 py-2.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('title') <p class="text-rose-500 text-[11px] mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Description / Event Details
                </label>
                <textarea name="description" rows="4" class="w-full p-3.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none leading-relaxed">{{ old('description', $album->description) }}</textarea>
            </div>

            <!-- Cover Image File -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Update Cover Image
                </label>
                @if($album->cover_image)
                    <div class="mb-3 flex items-center space-x-3 p-2 bg-slate-50 rounded-lg border border-slate-200">
                        <img src="{{ asset($album->cover_image) }}" alt="Current cover" class="w-16 h-12 object-cover rounded">
                        <span class="text-xs text-slate-600 font-medium">Current cover photo active</span>
                    </div>
                @endif
                <input type="file" name="cover_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <!-- Visibility Switch -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center justify-between">
                <div>
                    <label for="is_published" class="text-xs font-bold text-slate-800 cursor-pointer block">
                        Publish to Public &amp; Parent Portal
                    </label>
                    <span class="text-[11px] text-slate-500">Visible to students and parents</span>
                </div>
                <input type="checkbox" id="is_published" name="is_published" value="1" {{ $album->is_published ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500 h-5 w-5">
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
                <a href="{{ route('school.gallery.show', $album->id) }}" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                    <i class="fa-solid fa-save mr-1.5"></i> Save Album Details
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

