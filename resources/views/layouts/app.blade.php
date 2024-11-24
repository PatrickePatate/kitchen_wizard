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
    @yield('meta')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Michroma&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @stack('styles')
    @livewireStyles
</head>
<body class="font-sans antialiased">
    @if($hasHeader)
        @include('components.menu')
    @endif
    @yield('content')
    @livewireScripts
    <script>
        Alpine.magic('clipboard', () => {
            return subject => navigator.clipboard.writeText(subject)
        })
        Alpine.magic('flash', () => {
            return (subject, duration=1000) => {
                subject = document.querySelector(subject);
                subject.style.transition = 'ease-in-out 0.2s';
                subject.style.display = 'block';
                setTimeout(() => {
                    subject.style.display = 'none';
                    subject.style.transition = 'ease-in-out 0.5s';
                }, duration)
            }
        })
    </script>
@stack('scripts')
</body>
</html>
