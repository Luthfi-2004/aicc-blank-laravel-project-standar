<!doctype html>
<html lang="en">

<head>
    <title>{{ $title ?? 'Packing Palet' }}</title>
    @include('components.head')

</head>

<body class="auth-body-bg">
    {{ $slot }}
    @include('components.scripts')
</body>

</html>
