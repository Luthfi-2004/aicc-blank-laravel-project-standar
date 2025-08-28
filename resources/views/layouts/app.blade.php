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

    <script>
        // Custom scripts can be added here
        document.addEventListener("DOMContentLoaded", function() {
            toastr.options = {
                closeButton: true,
                progressBar: true,
                timeOut: 5000, // 5000 ms = 5 detik
                extendedTimeOut: 1000 // tambahan jika hover
            };
            // Your custom JavaScript code here
            @if (session()->has('message'))
                toastr.success("{{ session('message') }}");
            @endif

            @if (session()->has('error'))
                toastr.error("{{ session('error') }}");
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>
