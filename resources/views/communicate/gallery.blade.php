@extends('layouts.app')

@section('title', 'Image Gallery')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <span class="text-slate-700 font-semibold">Communicate</span>
                <span>/</span>
                <span class="text-blue-600 font-semibold">Image Gallery</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-pink-600 to-rose-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-images"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 tracking-tight">Campus Image Gallery</h1>
                    <p class="text-xs text-slate-500">Photo albums of sports events, annual functions, robotics exhibitions, and student achievements.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('school.gallery.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Create Album</span>
            </a>
        </div>
    </div>

    <!-- Albums Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($albums as $album)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden group hover:shadow-md transition flex flex-col justify-between">
            <div>
                <!-- Cover Image Thumbnail -->
                <div class="h-44 w-full bg-slate-100 relative overflow-hidden">
                    @if($album->cover_image && file_exists(public_path($album->cover_image)))
                        <img src="{{ asset($album->cover_image) }}" alt="{{ $album->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-tr from-slate-100 to-blue-50 text-slate-400">
                            <i class="fa-solid fa-camera-retro text-4xl mb-2 text-slate-300"></i>
                            <span class="text-[11px] font-semibold text-slate-400">No cover photo</span>
                        </div>
                    @endif

                    <!-- Top Status Badge -->
                    <div class="absolute top-3 left-3">
                        @if($album->is_published)
                            <span class="bg-emerald-600/90 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-xs">
                                <i class="fa-solid fa-check mr-1"></i> Published
                            </span>
                        @else
                            <span class="bg-slate-700/80 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-xs">
                                Draft
                            </span>
                        @endif
                    </div>

                    <!-- Photo Count Pill -->
                    <div class="absolute bottom-3 right-3 bg-slate-900/80 backdrop-blur-xs text-white text-[11px] font-bold px-2.5 py-0.5 rounded-full shadow-xs flex items-center space-x-1">
                        <i class="fa-solid fa-image text-[10px]"></i>
                        <span>{{ $album->photos_count }} {{ Str::plural('photo', $album->photos_count) }}</span>
                    </div>
                </div>

                <!-- Album Details -->
                <div class="p-4 space-y-1.5">
                    <h3 class="text-sm font-bold text-slate-900 line-clamp-1 group-hover:text-blue-600 transition">
                        <a href="{{ route('school.gallery.show', $album->id) }}">
                            {{ $album->title }}
                        </a>
                    </h3>
                    @if($album->description)
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $album->description }}
                        </p>
                    @endif
                    <div class="text-[10px] text-slate-400 pt-1 font-mono">
                        Created: {{ date('d M Y', strtotime($album->created_at)) }}
                    </div>
                </div>
            </div>

            <!-- Card Footer Actions -->
            <div class="px-4 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                <a href="{{ route('school.gallery.show', $album->id) }}" class="font-bold text-blue-600 hover:text-blue-800 transition flex items-center space-x-1">
                    <span>View Photos</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('school.gallery.edit', $album->id) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded transition" title="Edit Album">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="{{ route('school.gallery.destroy', $album->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this album and all its photos?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded transition" title="Delete Album">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
            <i class="fa-regular fa-images text-5xl mb-3 block text-slate-300"></i>
            <p class="font-semibold text-slate-600 text-base">No photo albums created yet.</p>
            <p class="text-xs text-slate-400 mt-1 mb-4">Create your first photo album to showcase school celebrations, sports meets, and science fairs.</p>
            <a href="{{ route('school.gallery.create') }}" class="inline-flex items-center space-x-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-plus"></i>
                <span>Create Album</span>
            </a>
        </div>
        @endforelse
    </div>

    @if($albums->hasPages())
    <div class="pt-4 border-t border-slate-200">
        {{ $albums->links() }}
    </div>
    @endif
</div>
@endsection

