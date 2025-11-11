<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
     {{-- @include('components.sidebar', ['active' => 'sales']) --}}
    {{ $slot }}
    @livewireScripts
</body>
</html>
