<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Data -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ $title ?? config('app.name', 'Laravel') }}</title> --}}

    {!! $seotags ?? '' !!}
	{!! $breadcrumbs ?? '' !!}
	{!! $jsonld ?? '' !!}

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    @stack('css')
</head>

<body class="{{ session('theme', 'light') }}">
    <!-- Navbar / Header -->
    @include('frontend.partials.navbar')

    <!-- Main Page Content -->
    <main class="py-4">
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('frontend.partials.footer')

    @stack('js')
</body>
</html>
