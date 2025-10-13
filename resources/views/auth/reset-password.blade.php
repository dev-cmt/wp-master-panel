<x-auth-layout title="Reset Password">
    <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
            <div class="mt-4 mb-3 d-flex justify-content-center">
                <a href="{{ url('/') }}">
                    <img src="{{ asset($settings ? $settings->logo : '') }}" style="height: 75px" alt="logo" class="desktop-logo">
                    <img src="{{ asset($settings ? $settings->logo : '') }}" style="height: 75px" alt="logo" class="desktop-dark">
                </a>
            </div>

            <div class="card custom-card shadow-sm">
                <div class="card-body p-5">
                    <h5 class="text-center fw-semibold mb-4">Reset Password</h5>
                    <p class="text-muted text-center mb-4">
                        Enter your email and new password to reset your account password.
                    </p>

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email', $request->email) }}"
                                   required autofocus autocomplete="username">
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="new-password" class="form-label text-default">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="new-password" name="password" placeholder="Enter new password" required autocomplete="new-password">
                                <button class="btn btn-light" onclick="createpassword('new-password',this)" type="button" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                            </div>
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label for="confirm-password" class="form-label text-default">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" id="confirm-password" name="password_confirmation" placeholder="Enter confirm password" required autocomplete="new-password">
                                <button class="btn btn-light" onclick="createpassword('confirm-password',this)" type="button" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Reset Password
                            </button>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-primary">Back to Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
