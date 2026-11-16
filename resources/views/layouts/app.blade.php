@props([
    'hasHeader' => true
])
    <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', config('app.name'))</title>
    <meta name="theme-color" content="#171717">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @yield('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michroma&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @stack('styles')
    @livewireStyles
</head>
<body class="font-sans antialiased text-neutral-900">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-white focus:p-2">{{__('Skip to content')}}</a>
    @if($hasHeader)
        @include('components.menu')
    @endif
    <main id="main">
        @yield('content')
    </main>
    @if($hasHeader)
        <footer class="border-t mt-10 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </footer>
    @endif
    @livewireScripts
    <script>
        Alpine.magic('clipboard', () => {
            return subject => navigator.clipboard.writeText(subject)
        })
    </script>
@stack('scripts')
</body>
</html>
