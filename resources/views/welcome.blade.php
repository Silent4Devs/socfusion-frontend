<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
    @livewireStyles
    @vite('resources/css/app.css')
    @filamentStyles
    
    @livewireStyles
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/filament/filament.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto px-4 py-8">
        @livewire(\App\Livewire\Widgets\StatsOverview::class)
    </div>

    <div class="container mx-auto px-4 py-8">
        @livewire(\App\Livewire\UserGrowthChart::class)
    </div>

    @livewireScripts
</body>
</html>