<!doctype html>
<html lang="en">

<head>
    <title>{{ $title ?? 'SandLab' }}</title>
    @include('components.head')
    
</head>

<body data-sidebar="dark">

    <!-- Begin page -->
    <div id="layout-wrapper">

        @livewire('components.topbar')

        <!-- ========== Left Sidebar Start ========== -->
        @livewire('components.sidebar')
        <!-- Left Sidebar End -->
            
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->

        <!-- main content -->
        <div class="main-content">

            {{ $slot }}

            @include('components.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    @include('components.rightbar')
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    @include('components.scripts')
    @stack('scripts')
</body>

</html>
