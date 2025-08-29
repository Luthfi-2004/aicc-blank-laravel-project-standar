<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'SandLab' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Head partial (CSS tema, icons, dll.) --}}
    @include('components.head')

    {{-- Vite CSS (jika ada resources/css/app.css) --}}
    <!-- @vite(['resources/css/app.css']) -->
</head>

<body data-sidebar="dark">
    <div id="layout-wrapper">
        {{-- Topbar --}}
        @livewire('components.topbar')

        {{-- Sidebar --}}
        @livewire('components.sidebar')

        {{-- Content --}}
        <div class="main-content">
            {{ $slot }}
            @include('components.footer')
        </div>
    </div>

    {{-- Rightbar --}}
    @include('components.rightbar')

    {{-- Overlay --}}
    <div class="rightbar-overlay"></div>

    {{-- Vendor scripts + Livewire scripts (sesuai file kamu) --}}
    @include('components.scripts')

    {{-- Vite JS entry: pastikan app.js meng-import ./greensand --}}
    @vite(['resources/js/app.js'])

    {{-- Stack untuk script halaman spesifik --}}
    @stack('scripts')
</body>

</html>