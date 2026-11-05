@extends('layouts.app')

@section('title', 'Website Pages')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="text-xs font-semibold text-sky-600 uppercase tracking-wider mb-1">School Website</div>
            <h1 class="text-2xl font-bold text-slate-800">Website CMS Pages</h1>
            <p class="text-xs text-slate-500 mt-1">Manage public landing pages, SEO meta descriptions, and live contents.</p>
        </div>
        <button onclick="alert('Create page')" class="bg-sky-600 hover:bg-sky-700 text-white font-medium text-xs px-3.5 py-2 rounded-lg shadow-sm transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Page</span>
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-100 text-slate-600 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4">Page Title</th>
                    <th class="py-3 px-4">URL Slug</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Last Updated</th>
                    <th class="py-3 px-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($pages as $p)
                <tr class="hover:bg-slate-50 transition">
                    <td class="py-3 px-4 font-bold text-slate-800 flex items-center space-x-2">
                        <i class="fa-regular fa-file-code text-sky-600"></i>
                        <span>{{ $p['title'] }}</span>
                    </td>
                    <td class="py-3 px-4 text-slate-500 font-mono">{{ $p['slug'] }}</td>
                    <td class="py-3 px-4">
                        <span class="bg-emerald-100 text-emerald-800 font-semibold px-2 py-0.5 rounded-full text-[10px]">{{ $p['status'] }}</span>
                    </td>
                    <td class="py-3 px-4 text-slate-600">{{ $p['last_updated'] }}</td>
                    <td class="py-3 px-4 text-right">
                        <button class="text-sky-600 hover:text-sky-800 font-semibold mr-2">Edit Page</button>
                        <button class="text-slate-400 hover:text-slate-600">View</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

