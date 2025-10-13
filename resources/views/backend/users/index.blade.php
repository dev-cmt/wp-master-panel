<x-backend-layout title="User List">
    @push('css')
        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--open {
                z-index: 100000 !important;
            }
        </style>
    @endpush

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Users Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Users List</div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap align-middle">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $key => $user)
                                <tr class="{{ $user->id == 1 ? 'd-none' : ''}}">
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        @if($user->photo_path)
                                            <img src="{{ asset($user->photo_path) }}" alt="photo" class="rounded-circle" width="40" height="40">
                                        @else
                                            <span class="badge bg-secondary">No Photo</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->roles->pluck('name')->first() ?? '-' }}</td>
                                    <td>
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-sm btn-warning-light btn-icon edit-user"
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->roles->pluck('name')->first() ?? '' }}"
                                                data-photo="{{ $user->photo_path }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editUserModal">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No users found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="editUserModalLabel">Edit User</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('users.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="edit_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_role" class="form-label">Role</label>
                                <select class="form-select select2" id="edit_role" name="role">
                                    <option value="" disabled selected>Select role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_photo" class="form-label">Photo</label>
                                <input type="file" class="form-control" id="edit_photo" name="photo">
                                <small class="text-muted">Upload new photo to replace existing.</small>
                                <div class="mt-2" id="current_photo_preview"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    @push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function(){
            // Initialize Select2
            function initSelect2() {
                $('select.select2').select2({
                    placeholder: "Select role", // placeholder text
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#editUserModal') // fixes modal dropdown issue
                });
            }
            initSelect2();

            // Populate edit modal with user data
            $(document).on('click', '.edit-user', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const role = $(this).data('role'); // may be empty
                const photo = $(this).data('photo');

                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_email').val(email);

                // Set role value for Select2
                if(role) {
                    $('#edit_role').val(role).trigger('change');
                } else {
                    $('#edit_role').val(null).trigger('change'); // show placeholder
                }

                if(photo) {
                    $('#current_photo_preview').html(`<img src="{{ asset('/') }}${photo}" alt="photo" class="rounded-circle" width="40" height="40">`);
                } else {
                    $('#current_photo_preview').html('');
                }
            });
        });
    </script>

    @endpush
</x-backend-layout>
