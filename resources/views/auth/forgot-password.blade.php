<x-auth-layout title="Forgot Password">
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
                    <p class="h5 fw-semibold mb-2 text-center">Forgot Password</p>
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Enter your email address and we'll send you a link to reset your password.</p>

                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" id="forgot-password-form">
                        @csrf
                        <div class="row gy-3">
                            <div class="col-xl-12">
                                <label for="email" class="form-label text-default">Email</label>
                                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-xl-12 d-grid mt-2">
                                <button type="submit" class="btn btn-lg btn-primary" id="submit-btn">Send Password Reset Link</button>
                            </div>

                            <div class="col-xl-12 text-center mt-3">
                                <a href="{{ route('login') }}" class="text-primary">Back to Sign In</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function startCountdown(duration) {
            let $btn = $('#submit-btn'),
                originalText = $btn.text(),
                timer = duration;

            $btn.prop('disabled', true).text(`${originalText} (${timer}s)`);

            let countdown = setInterval(function() {
                timer--;
                $btn.text(`${originalText} (${timer}s)`);

                if(timer <= 0) {
                    clearInterval(countdown);
                    $btn.prop('disabled', false).text(originalText);
                }
            }, 1000);
        }

        $(function(){
            const countdownTime = 120; // 2 minutes

            // Start countdown if session status exists (after page reload)
            @if(session('status'))
                startCountdown(countdownTime);
            @endif

            // Start countdown on form submit
            $('#forgot-password-form').on('submit', function(){
                startCountdown(countdownTime);
            });
        });
    </script>
    @endpush
</x-auth-layout>
