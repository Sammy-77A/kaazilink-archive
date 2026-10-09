<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        <!-- Google Fonts: Inter and Outfit -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
        @wirekitStyles
    </head>
    <body class="bg-[var(--color-wk-bg)] text-[var(--color-wk-text)] font-sans antialiased min-h-screen">
        
        <x-wirekit::app-shell>
            <x-slot:header>
                <x-wirekit::navbar>
                    <x-wirekit::brand name="Kaazilink" />
                    
                    <div class="flex gap-4">
                        <x-wirekit::navbar.item href="/" :active="request()->is('/')">Home</x-wirekit::navbar.item>
                        <x-wirekit::navbar.item href="/services" :active="request()->is('services*')">Services</x-wirekit::navbar.item>
                        <x-wirekit::navbar.item href="/how-it-works" :active="request()->is('how-it-works')">How it Works</x-wirekit::navbar.item>
                        
                        @auth
                            @if(auth()->user()->isProvider())
                                <x-wirekit::navbar.item href="/provider/service-areas" :active="request()->is('provider/service-areas')">My Areas</x-wirekit::navbar.item>
                            @endif
                        @endauth
                    </div>
                </x-wirekit::navbar>
            </x-slot:header>
            
            <x-wirekit::main>
                {{ $slot }}
            </x-wirekit::main>
        </x-wirekit::app-shell>

        @wirekitScripts
        @livewireScripts
    </body>
</html>
