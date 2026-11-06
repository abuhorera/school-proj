@extends('layouts.app')

@section('title', $album->title . ' - Image Gallery')

@section('content')
<div class="space-y-6">

    <!-- Top Breadcrumb & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="space-y-1">
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition"><i class="fa-solid fa-house"></i></a>
                <span>/</span>
                <a href="{{ route('school.gallery') }}" class="hover:text-blue-600 transition">Image Gallery</a>
                <span>/</span>
                <span class="text-blue-600 font-semibold">{{ $album->title }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-pink-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-images"></i>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $album->title }}</h1>
                        @if($album->is_published)
                            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                Published
                            </span>
                        @else
                            <span class="bg-slate-200 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                Draft
                            </span>
                        @endif
                    </div>
                    @if($album->description)
                        <p class="text-xs text-slate-500 mt-0.5 max-w-2xl">{{ $album->description }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('school.gallery') }}" class="inline-flex items-center space-x-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg transition">
                <i class="fa-solid fa-arrow-left"></i>
                <span>All Albums</span>
            </a>
            <a href="{{ route('school.gallery.edit', $album->id) }}" class="inline-flex items-center space-x-1.5 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-semibold text-xs px-3.5 py-2.5 rounded-lg shadow-sm transition">
                <i class="fa-solid fa-pen-to-square text-slate-500"></i>
                <span>Edit Album</span>
            </a>
        </div>
    </div>

    <!-- Drag & Drop Uploader Card -->
    <div class="bg-white rounded-2xl border-2 border-dashed border-slate-300 hover:border-blue-500 transition p-8 text-center shadow-sm">
        <form action="{{ route('school.gallery.photos.upload', $album->id) }}" method="POST" enctype="multipart/form-data" id="photoUploadForm">
            @csrf
            <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl mb-3">
                <i class="fa-solid fa-cloud-arrow-up"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900 mb-1">Drop images here or click to upload</h3>
            <p class="text-xs text-slate-500 mb-4">JPEG, PNG, WebP, GIF &bull; Max 5 MB each &bull; Up to 20 at a time</p>
            
            <input type="file" name="photos[]" id="photosInput" multiple accept="image/*" class="hidden" onchange="document.getElementById('photoUploadForm').submit()">
            
            <button type="button" onclick="document.getElementById('photosInput').click()" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-5 py-2.5 rounded-lg shadow-sm shadow-blue-500/20 transition cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Select Images from Computer</span>
            </button>
        </form>
    </div>

    <!-- Photos Grid Header -->
    <div class="flex items-center justify-between pt-2">
        <div class="flex items-center space-x-2">
            <h2 class="text-base font-bold text-slate-900">Album Images</h2>
            <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2 py-0.5 rounded-full">
                {{ $album->photos->count() }}
            </span>
        </div>
    </div>

    <!-- Photos Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($album->photos as $photo)
        <div class="group relative rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm aspect-square flex items-center justify-center">
            @if(file_exists(public_path($photo->image_path)))
                <img src="{{ asset($photo->image_path) }}" alt="{{ $photo->caption }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            @else
                <div class="text-center p-3 text-slate-400">
                    <i class="fa-solid fa-image text-3xl mb-1 text-slate-300"></i>
                    <div class="text-[10px] truncate max-w-full font-mono">{{ $photo->caption }}</div>
                </div>
            @endif

            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover:opacity-100 transition duration-200 flex flex-col justify-between p-3">
                <div class="flex justify-end">
                    <form action="{{ route('school.gallery.photos.destroy', [$album->id, $photo->id]) }}" method="POST" onsubmit="return confirm('Delete this image?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-7 h-7 rounded-lg bg-rose-600 hover:bg-rose-700 text-white flex items-center justify-center text-xs transition cursor-pointer shadow-sm" title="Delete Photo">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
                <div class="text-white text-left">
                    <div class="text-xs font-bold truncate">{{ $photo->caption ?: 'Campus Photo' }}</div>
                    @if($photo->file_size)
                        <div class="text-[10px] text-slate-300 font-mono">{{ $photo->file_size }}</div>
                    @endif
                    <button type="button" onclick="openLightbox('{{ asset($photo->image_path) }}', '{{ addslashes($photo->caption) }}')" class="mt-1 text-[11px] text-blue-300 hover:text-white font-semibold flex items-center space-x-1">
                        <i class="fa-solid fa-expand text-[10px]"></i>
                        <span>Enlarge</span>
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200">
            <i class="fa-regular fa-images text-5xl mb-3 block text-slate-300"></i>
            <p class="font-semibold text-slate-600 text-sm">No photos uploaded to this album yet.</p>
            <p class="text-xs text-slate-400 mt-1">Use the upload box above to add photographs.</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal: Image Lightbox -->
<div id="imageLightboxModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-sm flex items-center justify-center p-4" onclick="closeModal('imageLightboxModal')">
    <div class="max-w-4xl max-h-[90vh] flex flex-col items-center" onclick="event.stopPropagation()">
        <div class="w-full flex items-center justify-between text-white pb-3">
            <h4 class="text-sm font-bold truncate" id="lightboxTitle">Photo Preview</h4>
            <button onclick="closeModal('imageLightboxModal')" class="text-white/80 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <img src="" id="lightboxImg" alt="Enlarged photo" class="max-h-[80vh] w-auto max-w-full rounded-xl shadow-2xl object-contain">
    </div>
</div>

<script>
function openLightbox(src, title) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightboxTitle').textContent = title || 'Photo Preview';
    openModal('imageLightboxModal');
}
</script>
@endsection

