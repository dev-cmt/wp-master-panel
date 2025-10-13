<x-backend-layout title="Change Password">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Change Password</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Change Password</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8">
            <div class="card custom-card shadow-sm">
                <div class="card-header justify-content-between">
                    <div class="card-title">Update Your Password</div>
                </div>
                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Current Password</label>
                            <div class="input-group">
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    id="current-password" placeholder="Enter your current password" required>
                                <button type="button" class="btn btn-light" onclick="togglePassword('current-password', this)">
                                    <i class="ri-eye-off-line align-middle"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">New Password</label>
                            <div class="input-group">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="new-password" placeholder="Enter new password" required>
                                <button type="button" class="btn btn-light" onclick="togglePassword('new-password', this)">
                                    <i class="ri-eye-off-line align-middle"></i>
                                </button>
                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation"
                                    class="form-control"
                                    id="confirm-password" placeholder="Re-enter new password" required>
                                <button type="button" class="btn btn-light" onclick="togglePassword('confirm-password', this)">
                                    <i class="ri-eye-off-line align-middle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-lock me-1"></i> Update Password
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            } else {
                input.type = "password";
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            }
        }
    </script>
    @endpush

</x-backend-layout>
