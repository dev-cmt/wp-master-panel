<x-backend-layout title="Create Role & Permissions">
    @push('css')
        <style>
            .permission-card {
                border: 1px solid #ddd;
                border-radius: 6px;
            }

            .permission-list {
                padding-left: 10px;
            }
        </style>
    @endpush

    <div class="row mt-4">
        <div class="col-md-12">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <!-- Role Name -->
                        <div class="mb-3">
                            <label class="form-label"><b>Role Name</b></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Role Name" required>
                        </div>

                        <!-- Select All Permissions -->
                        <div class="form-check mb-3">
                            <input type="checkbox" id="select_all" class="form-check-input">
                            <label for="select_all" class="form-check-label"><b>Select All Permissions</b></label>
                        </div>

                        <!-- Permissions Grid -->
                        <div class="row">
                            @foreach ($groupedPermissions as $module => $permissions)
                                <div class="col-md-4">
                                    <div class="card border rounded p-2 mb-3 permission-card">
                                        <div class="card-header bg-light">
                                            <input type="checkbox" class="form-check-input module_check" id="module-{{ $module }}">
                                            <label for="module-{{ $module }}" class="form-check-label mb-0 fw-bold">
                                                {{ ucfirst($module) }} ({{ count($permissions) }})
                                            </label>
                                        </div>
                                        <div class="card-body p-2 permission-list">
                                            @foreach ($permissions as $permission)
                                                <div class="form-check mb-1">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        class="form-check-input permission_item"
                                                        id="perm-{{ $permission->name }}">
                                                    <label for="perm-{{ $permission->name }}" class="form-check-label">
                                                        {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success mt-3">
                            <i class="fas fa-save me-2"></i>Save Role
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                const selectedPermissions = [];

                // Select All
                $('#select_all').on('change', function() {
                    const checked = $(this).prop('checked');
                    $('.module_check, .permission_item').prop('checked', checked);
                });

                // Module select/deselect
                $('.module_check').on('change', function() {
                    const card = $(this).closest('.permission-card');
                    const checked = $(this).prop('checked');
                    card.find('.permission_item').prop('checked', checked);
                    updateSelectAll();
                });

                // Individual permission check
                $('.permission_item').on('change', function() {
                    const card = $(this).closest('.permission-card');
                    const allChecked = card.find('.permission_item').length === card.find(
                        '.permission_item:checked').length;
                    card.find('.module_check').prop('checked', allChecked);
                    updateSelectAll();
                });

                function updateSelectAll() {
                    const total = $('.permission_item').length;
                    const checked = $('.permission_item:checked').length;
                    const selectAll = $('#select_all');
                    if (checked === total) selectAll.prop('checked', true).prop('indeterminate', false);
                    else if (checked > 0) selectAll.prop('checked', false).prop('indeterminate', true);
                    else selectAll.prop('checked', false).prop('indeterminate', false);
                }
            });
        </script>
    @endpush
    </x-backend-layout>