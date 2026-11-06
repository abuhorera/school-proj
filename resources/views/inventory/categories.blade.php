@extends('layouts.app')

@section('content')
<div class="px-4 py-3">
    @include('inventory.nav')

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-xl flex items-center justify-between">
        <span><i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i> {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">&times;</button>
    </div>
    @endif

    <div class="inv-card overflow-hidden">
        <div class="inv-section-title">
            <span class="flex items-center gap-2">
                <i class="fa-solid fa-tags text-slate-700"></i>
                Inventory Categories ({{ $categories->count() }})
            </span>
            <button onclick="document.getElementById('addCatModal').classList.remove('hidden')" class="px-3.5 py-1.5 bg-slate-900 text-white rounded-lg text-xs font-semibold hover:bg-slate-800 transition flex items-center gap-1.5">
                <i class="fa-solid fa-plus text-[10px]"></i> Add Category
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Items Count</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $idx => $cat)
                    <tr>
                        <td class="text-slate-400 text-xs font-mono">{{ $idx + 1 }}</td>
                        <td>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700 text-xs">
                                    <i class="fa-solid {{ $cat->icon ?: 'fa-tag' }}"></i>
                                </div>
                                <span class="font-bold text-slate-900">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $cat->type === 'Saleable' ? 'bg-purple-100 text-purple-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $cat->type }}
                            </span>
                        </td>
                        <td class="text-xs text-slate-500 max-w-xs truncate">{{ $cat->description ?: '—' }}</td>
                        <td>
                            <a href="{{ route('school.inventory.items', ['category_id' => $cat->id]) }}" class="text-xs font-bold text-sky-600 hover:underline bg-sky-50 px-2 py-0.5 rounded border border-sky-100">
                                {{ $cat->items_count }} items
                            </a>
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-2">
                                <button onclick="openEditModal({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ $cat->type }}', '{{ addslashes($cat->description ?? '') }}', '{{ $cat->icon ?? '' }}')" class="p-1.5 text-sky-600 hover:bg-sky-50 rounded transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <form action="{{ route('school.inventory.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete category {{ $cat->name }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-400">No categories found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Category -->
<div id="addCatModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">Add Item Category</h4>
            <button onclick="document.getElementById('addCatModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form action="{{ route('school.inventory.categories.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Category Name <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="e.g. Stationery, Uniforms, Lab Chemicals" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Type <span class="text-rose-500">*</span></label>
                <select name="type" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    <option value="Consumable">Consumable (Lab/Staff/Classroom)</option>
                    <option value="Saleable">Saleable (For Point of Sale Counter)</option>
                    <option value="Asset">Asset (Capital Equipment/Furniture)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Icon (FontAwesome class)</label>
                <input type="text" name="icon" placeholder="e.g. fa-pencil-ruler, fa-flask, fa-shirt" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="2" placeholder="Brief note about this category..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addCatModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-slate-900 text-white rounded-lg hover:bg-slate-800">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Category -->
<div id="editCatModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-100">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h4 class="font-bold text-slate-900 text-sm">Edit Category</h4>
            <button onclick="document.getElementById('editCatModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
        <form id="editCatForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Category Name <span class="text-rose-500">*</span></label>
                <input type="text" id="editCatName" name="name" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Type <span class="text-rose-500">*</span></label>
                <select id="editCatType" name="type" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
                    <option value="Consumable">Consumable</option>
                    <option value="Saleable">Saleable</option>
                    <option value="Asset">Asset</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Icon</label>
                <input type="text" id="editCatIcon" name="icon" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                <textarea id="editCatDesc" name="description" rows="2" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-sky-500"></textarea>
            </div>

            <div class="pt-3 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editCatModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-5 py-2 text-xs font-bold bg-sky-600 text-white rounded-lg hover:bg-sky-700">Update Category</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(id, name, type, desc, icon) {
    document.getElementById('editCatForm').action = "{{ url('school/inventory/categories') }}/" + id;
    document.getElementById('editCatName').value = name;
    document.getElementById('editCatType').value = type;
    document.getElementById('editCatDesc').value = desc;
    document.getElementById('editCatIcon').value = icon;
    document.getElementById('editCatModal').classList.remove('hidden');
}
</script>
@endsection

