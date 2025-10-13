<x-auth-layout title="Sign Up">
    <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
            <div class="mt-4 mb-3 d-flex justify-content-center">
                <a href="{{ url('/') }}">
                    <img src="{{ asset($settings ? $settings->logo : '') }}" style="height: 75px" alt="logo" class="desktop-logo">
                    <img src="{{ asset($settings ? $settings->logo : '') }}" style="height: 75px" alt="logo" class="desktop-dark">
                </a>
            </div>

            <div class="card custom-card">
                <div class="card-body p-5">
                    <p class="h5 fw-semibold mb-2 text-center">Sign In</p>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Welcome back John!</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row gy-3">
                            <!-- Username / Email -->
                            <div class="col-xl-12">
                                <label for="email" class="form-label text-default">Email</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-xl-12 mb-2">
                                <label for="password" class="form-label text-default d-block">
                                    Password
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="float-end text-danger">Forgot password?</a>
                                    @endif
                                </label>

                                <div class="input-group">
                                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="signin-password" placeholder="password">
                                    <button class="btn btn-light" type="button" onclick="createpassword('signin-password',this)" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                </div>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror

                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-muted fw-normal" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-xl-12 d-grid mt-2">
                                <button type="submit" class="btn btn-lg btn-primary">Sign In</button>
                            </div>
                        </div>
                    </form>

                    <!-- Signup & Social -->
                    <div class="text-center">
                        <p class="fs-12 text-muted mt-3">Don't have an account? <a href="{{ route('register') }}" class="text-primary">Sign Up</a></p>
                    </div>

                    {{-- <div class="text-center my-3 authentication-barrier">
                        <span>OR</span>
                    </div>

                    <div class="btn-list text-center">
                        <button class="btn btn-icon btn-light">
                            <i class="ri-facebook-line fw-bold text-dark op-7"></i>
                        </button>
                        <button class="btn btn-icon btn-light">
                            <i class="ri-google-line fw-bold text-dark op-7"></i>
                        </button>
                        <button class="btn btn-icon btn-light">
                            <i class="ri-twitter-line fw-bold text-dark op-7"></i>
                        </button>
                    </div> --}}

                </div>
            </div>
        </div>
    </div>
</x-auth-layout>
