<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset($settings ? $settings->favicon : '') }}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ asset('backend/js/authentication-main.js') }}"></script>
    
    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('backend/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('backend/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('backend/css/icons.min.css') }}" rel="stylesheet" >

    @stack('css')
</head>
<body>

    <div class="container">
        {{ $slot }}
    </div>

    <!-- Jquery JS -->
    <script src="{{ asset('backend/js/jquery.js') }}"></script>

    <!-- Custom-Switcher JS -->
    <script src="{{ asset('backend/js/custom-switcher.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Show Password JS -->
    <script src="{{ asset('backend/js/show-password.js') }}"></script>

    @stack('js')
</body>
</html>
