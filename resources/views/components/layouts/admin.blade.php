<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kaazilink Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col md:flex-row">
    
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-[#006B3F] text-white flex flex-col shadow-lg shrink-0 h-auto md:h-screen sticky top-0">
        <div class="p-6 flex items-center justify-center border-b border-[#005a35]">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold tracking-tight" style="font-family: 'Outfit', sans-serif;">
                Kaazilink <span class="text-[#E8A317]">Admin</span>
            </a>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-[#005a35] font-semibold' : 'hover:bg-[#005a35]' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.kyc.index') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.kyc.*') ? 'bg-[#005a35] font-semibold' : 'hover:bg-[#005a35]' }}">
                KYC Reviews
            </a>
            <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-[#005a35] font-semibold' : 'hover:bg-[#005a35]' }}">
                Site Settings
            </a>
            <a href="{{ route('admin.metrics.index') }}" class="block px-4 py-2 rounded transition-colors {{ request()->routeIs('admin.metrics.*') ? 'bg-[#005a35] font-semibold' : 'hover:bg-[#005a35]' }}">
                Business Metrics <span class="text-xs bg-[#E8A317] text-black px-1.5 py-0.5 rounded ml-1">Soon</span>
            </a>
        </nav>
        <div class="p-4 border-t border-[#005a35]">
            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-center hover:underline">Back to Main Site</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2 text-center">
                @csrf
                <button type="submit" class="w-full text-sm hover:underline text-gray-200 hover:text-white px-4 py-2">Log Out</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 md:p-10 overflow-y-auto w-full">
        <div class="max-w-6xl mx-auto">
            {{ $slot }}
        </div>
    </main>

</body>
</html>
