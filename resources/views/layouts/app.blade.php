<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Perpustakaan' }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navigation')

    {{ $slot }}
    
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
