@extends('layouts.app')

@section('content')
<div class="content-wrapper p-3">
    @include('assets.nav')

    <div class="ast-card">
        <div class="ast-card-header flex-wrap" style="gap: 10px;">
            <h4 class="ast-card-title"><i class="fas fa-tags text-primary"></i> All Categories</h4>
            <button type="button" class="btn btn-sm btn-ast-primary" data-toggle="modal" data-target="#addCategoryModal">
                <i class="fas fa-plus mr-1"></i> Add Category
            </button>
        </div>

        <div class="ast-card-body p-0 table-responsive">
            <table class="ast-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Depreciation Method</th>
                        <th>Life (mo)</th>
                        <th>Assets</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                <strong>{{ $cat->name }}</strong>
                                @if($cat->description)
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($cat->description, 50) }}</div>
                                @endif
                            </td>
                            <td><span class="ast-code">{{ $cat->code ?? '—' }}</span></td>
                            <td>{{ $cat->default_depreciation_method }}</td>
                            <td>{{ $cat->default_useful_life_months ?? '—' }}</td>
                            <td>
                                <a href="{{ route('school.assets.assets', ['category' => $cat->id]) }}" class="badge badge-info" style="font-size: 0.8rem; text-decoration: none;">
                                    {{ $cat->assets_count }}
                                </a>
                            </td>
                            <td>
                                <span class="badge {{ $cat->is_active ? 'badge-success' : 'badge-secondary' }}">
                                    {{ $cat->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-right text-nowrap">
                                <button type="button" class="btn btn-xs btn-outline-primary btn-edit-cat"
                                    data-id="{{ $cat->id }}"
                                    data-name="{{ $cat->name }}"
                                    data-code="{{ $cat->code }}"
                                    data-method="{{ $cat->default_depreciation_method }}"
                                    data-life="{{ $cat->default_useful_life_months }}"
                                    data-salvage="{{ $cat->default_salvage_value_pct }}"
                                    data-rate="{{ $cat->default_depreciation_rate }}"
                                    data-desc="{{ $cat->description }}"
                                    data-active="{{ $cat->is_active ? '1' : '0' }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('school.assets.categories.destroy', $cat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete category {{ $cat->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger" {{ $cat->assets_count > 0 ? 'disabled title="Cannot delete category with assets"' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                No categories defined. Click "Add Category" above.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="POST" action="{{ route('school.assets.categories.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-600">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. IT Equipment" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. IT, FURN">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Default Depreciation Method <span class="text-danger">*</span></label>
                        <select name="default_depreciation_method" class="form-control" required>
                            <option value="Straight Line">Straight Line</option>
                            <option value="Written Down Value (WDV)">Written Down Value (WDV)</option>
                            <option value="Declining Balance">Declining Balance</option>
                            <option value="None">None</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Useful Life (mo)</label>
                            <input type="number" name="default_useful_life_months" class="form-control" placeholder="60">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Salvage Value %</label>
                            <input type="number" step="0.01" name="default_salvage_value_pct" class="form-control" placeholder="5.0">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Rate (%/yr)</label>
                            <input type="number" step="0.01" name="default_depreciation_rate" class="form-control" placeholder="20.0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief category scope..."></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_add" checked>
                        <label class="form-check-label font-weight-600" for="is_active_add">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ast-primary">Create Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Edit Category Modal --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="editCategoryForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="font-weight-600">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_cat_name" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Code</label>
                            <input type="text" name="code" id="edit_cat_code" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Default Depreciation Method <span class="text-danger">*</span></label>
                        <select name="default_depreciation_method" id="edit_cat_method" class="form-control" required>
                            <option value="Straight Line">Straight Line</option>
                            <option value="Written Down Value (WDV)">Written Down Value (WDV)</option>
                            <option value="Declining Balance">Declining Balance</option>
                            <option value="None">None</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Useful Life (mo)</label>
                            <input type="number" name="default_useful_life_months" id="edit_cat_life" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Salvage Value %</label>
                            <input type="number" step="0.01" name="default_salvage_value_pct" id="edit_cat_salvage" class="form-control">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="font-weight-600">Rate (%/yr)</label>
                            <input type="number" step="0.01" name="default_depreciation_rate" id="edit_cat_rate" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-600">Description</label>
                        <textarea name="description" id="edit_cat_desc" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="edit_cat_active">
                        <label class="form-check-label font-weight-600" for="edit_cat_active">Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-ast-primary">Update Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-edit-cat').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var name = this.getAttribute('data-name');
                var code = this.getAttribute('data-code');
                var method = this.getAttribute('data-method');
                var life = this.getAttribute('data-life');
                var salvage = this.getAttribute('data-salvage');
                var rate = this.getAttribute('data-rate');
                var desc = this.getAttribute('data-desc');
                var active = this.getAttribute('data-active') === '1';

                var form = document.getElementById('editCategoryForm');
                form.action = '/school/assets/categories/' + id;

                document.getElementById('edit_cat_name').value = name;
                document.getElementById('edit_cat_code').value = code || '';
                document.getElementById('edit_cat_method').value = method;
                document.getElementById('edit_cat_life').value = life || '';
                document.getElementById('edit_cat_salvage').value = salvage || '';
                document.getElementById('edit_cat_rate').value = rate || '';
                document.getElementById('edit_cat_desc').value = desc || '';
                document.getElementById('edit_cat_active').checked = active;

                $('#editCategoryModal').modal('show');
            });
        });
    });
</script>
@endpush
@endsection

