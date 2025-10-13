<x-auth-layout title="Sign In">
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
                    <p class="h5 fw-semibold mb-2 text-center">Sign Up</p>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Create your new account!</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row gy-3">
                            <!-- Name -->
                            <div class="col-xl-12">
                                <label for="name" class="form-label text-default">Name</label>
                                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" required autofocus>
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-xl-12">
                                <label for="email" class="form-label text-default">Email</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-xl-12">
                                <label for="phone" class="form-label text-default">Phone</label>
                                <input type="text" class="form-control form-control-lg @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone" required>
                                @error('phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-xl-12">
                                <label for="signup-password" class="form-label text-default">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="signup-password" name="password" placeholder="Enter your password" required>
                                    <button class="btn btn-light" onclick="createpassword('signup-password',this)" type="button" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                </div>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-xl-12">
                                <label for="signup-confirmpassword" class="form-label text-default">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control form-control-lg" id="signup-confirmpassword" name="password_confirmation" placeholder="Confirm your password" required >
                                    <button class="btn btn-light" onclick="createpassword('signup-confirmpassword',this)" type="button" id="button-addon21"><i class="ri-eye-off-line align-middle"></i></button>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-xl-12 d-grid mt-2">
                                <button type="submit" class="btn btn-lg btn-primary">Sign Up</button>
                            </div>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-3">
                        <p class="fs-12 text-muted">
                            Already registered?
                            <a href="{{ route('login') }}" class="text-primary">Sign In</a>
                        </p>
                    </div>

                    {{-- <!-- OR Separator -->
                    <div class="text-center my-3 authentication-barrier">
                        <span>OR</span>
                    </div>

                    <!-- Social Buttons -->
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
