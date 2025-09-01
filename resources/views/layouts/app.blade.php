<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'SandLab' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Head --}}
    @include('components.head')
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
            {{-- Footer --}}
            @include('components.footer')
        </div>
    </div>

    {{-- Rightbar --}}
    @include('components.rightbar')

    {{-- Overlay --}}
    <div class="rightbar-overlay"></div>

    {{-- Scripts --}}
    @include('components.scripts')

    {{-- Stack --}}
    @stack('scripts')
</body>
</html>
