<x-backend-layout title="Developer API">
@push('css')
<style>
    .code-block {
        position: relative;
        background: #1e1e1e;
        color: #ff0022;
        padding: 1rem;
        border-radius: 8px;
        font-family: Consolas, Monaco, monospace;
        margin-bottom: 1.5rem;
        overflow-x: auto;
        font-size: 14px;
        white-space: pre-wrap;
    }
    .copy-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #0d6efd;
        color: #fff;
        border: none;
        padding: 4px 8px;
        font-size: 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.2s;
    }
    .copy-btn:hover { background: #0b5ed7; }
    .section-title {
        margin-top: 2rem;
        font-weight: 600;
        font-size: 18px;
        border-left: 4px solid #0d6efd;
        padding-left: 8px;
    }
    .api-card { margin-bottom: 2rem; }
    .api-card-header { background: #f8f9fa; font-weight: 600; }
</style>
@endpush

<div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
    <h1 class="page-title fw-semibold fs-18 mb-0">Developer API</h1>
    <div class="ms-md-1 ms-0">
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Developer API</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- API Token + Quick Links Section -->
    <div class="col-xl-4 mb-4">
        <div class="card custom-card h-100">
            <div class="card-header">API Token</div>
            <div class="card-body">
                <p class="text-muted">Use this token to access our developer API.</p>
                <form action="{{ route('developer-api.generate-token') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your API Token</label>
                        <input type="text" class="form-control mb-2"
                               value="{{ auth()->user()->api_token ?? 'No token generated yet.' }}" readonly
                               id="api-token">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyText('#api-token')">Copy Token</button>
                            <button type="submit" class="btn btn-sm btn-primary">Generate New Token</button>
                        </div>
                    </div>
                </form>

                <!-- Quick Links -->
                <div class="mt-4">
                    <label class="form-label fw-bold">Quick Links</label>
                    <div id="list-example" class="list-group">
                        <a class="list-group-item list-group-item-action" href="#register">Register</a>
                        <a class="list-group-item list-group-item-action" href="#login">Login</a>
                        <a class="list-group-item list-group-item-action" href="#check-courier">Check Courier</a>
                        <a class="list-group-item list-group-item-action" href="#token-check-courier">Token Check Courier</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- API Documentation Section -->
    <div class="col-xl-8">
        <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-smooth-scroll="true" tabindex="0" style="height: calc(100vh - 130px); overflow-y: auto;">
            <h4 class="section-title mb-4">API Documentation</h4>

            {{-- Base URL --}}
            <div class="card api-card">
                <div class="card-header api-card-header">Base URL</div>
                <div class="card-body">
                    <div class="code-block">
    <pre>https://frodlybd.com</pre>
                    </div>
                </div>
            </div>

            {{-- Register --}}
            <div class="card api-card" id="register">
                <div class="card-header api-card-header">1️⃣ Register</div>
                <div class="card-body">
                    <p><strong>POST:</strong> <code>{base_url}/api/register</code></p>
                    <h6>Request JSON</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre>{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "123456",
    "password_confirmation": "123456"
}</pre>
                    </div>
                    <h6>Response</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre>{
    "status": true,
    "message": "User registered successfully",
    "user": {
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-09-27T19:39:02.000000Z",
        "updated_at": "2025-09-27T19:39:02.000000Z",
        "id": 2
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}</pre>
                    </div>
                </div>
            </div>

            {{-- Login --}}
            <div class="card api-card" id="login">
                <div class="card-header api-card-header">2️⃣ Login</div>
                <div class="card-body">
                    <p><strong>POST:</strong> <code>{base_url}/api/login</code></p>
                    <h6>Request JSON</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre>{
    "email": "admin@gmail.com",
    "password": "admin1234"
}</pre>
                    </div>
                    <h6>Response</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre>{
    "status": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "user": {
        "id": 2,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null,
        "created_at": "2025-09-27T19:39:02.000000Z",
        "updated_at": "2025-09-27T19:39:02.000000Z"
    }
}</pre>
                    </div>
                </div>
            </div>

            {{-- Check Courier (Bearer Token) --}}
            <div class="card api-card" id="check-courier">
                <div class="card-header api-card-header">3️⃣ Check Courier (Bearer Token)</div>
                <div class="card-body">
                    <p><strong>POST:</strong> <code>{base_url}/api/check-courier</code></p>
                    <p>Headers: <code>Authorization: Bearer {token}</code></p>
                    <h6>Request JSON</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre>{ "phone": "01712345678" }</pre>
                    </div>
                    <h6>Response</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre>{
    "status": true,
    "message": "Courier info retrieved successfully.",
    "data": {
        "Summaries": {
            "Redx": {"logo": "images/logo.svg","total": "4","success": "4","cancel": 0},
            "SteadFast": {"logo": "images/logo.svg","total": 7,"success": 4,"cancel": 3},
            "Pathao": {"logo": "images/logo.svg","total": 22,"success": 22,"cancel": 0},
            "Paperfly": {"logo": "images/logo.svg","total": 0,"success": 0,"cancel": 0}
        },
        "totalSummary": {"total": 33,"success": 30,"cancel": 3,"successRate": 91,"cancelRate": 9}
    }
}</pre>
                    </div>
                </div>
            </div>

            {{-- Check Courier (API Token Header) --}}
            <div class="card api-card" id="token-check-courier">
                <div class="card-header api-card-header">4️⃣ Check Courier (API Token Header)</div>
                <div class="card-body">
                    <p><strong>POST:</strong> <code>{base_url}/api/token/check-courier</code></p>
                    <p>Headers:</p>
                    <ul>
                        <li><code>X-API-TOKEN: 1234567890abcdef</code></li>
                        <li><code>Content-Type: application/json</code></li>
                    </ul>
                    <h6>Request JSON</h6>
                    <div class="code-block">
                        <button class="copy-btn" onclick="copyCode(this)">Copy</button>
<pre>{ "phone": "01712345678" }</pre>
                    </div>
                </div>
            </div>



        </div>
    </div>

</div>

<!-- Toast -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055">
    <div id="liveToast" class="toast align-items-center text-white bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
        <div class="d-flex flex-column">
            <div class="toast-header text-white">
                <img src="{{ asset($settings ? $settings->logo : '') }}" class="rounded me-2" width="20" height="20" alt="...">
                <strong class="me-auto">Frodly</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">Hello, world! This is a toast message.</div>
            <div class="progress rounded-0" style="height: 4px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-light" role="progressbar" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    function showToast(message = 'Copied to clipboard!') {
        const toastEl = document.getElementById('liveToast');
        toastEl.querySelector('.toast-body').textContent = message;

        const progressBar = toastEl.querySelector('.progress-bar');
        progressBar.style.width = '100%';
        let width = 100;
        const interval = setInterval(() => {
            width -= 5;
            progressBar.style.width = width + '%';
            if(width <= 0) clearInterval(interval);
        }, 100);

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    function copyCode(btn) {
        const code = btn.parentElement.querySelector('pre').innerText;
        navigator.clipboard.writeText(code).then(() => showToast('Code copied!'));
    }

    function copyText(selector) {
        const el = document.querySelector(selector);
        navigator.clipboard.writeText(el.value).then(() => showToast('API token copied!'));
    }

    function scrollToSection(id) {
        const section = document.getElementById(id);
        if(section) {
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
</script>
@endpush
</x-backend-layout>