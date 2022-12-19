<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Attendance Management') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    {{-- Refactor --}}
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/ts/app.ts', 'resources/ts/menuState.ts',
    'resources/ts/calendar.ts','resources/ts/modal.ts'])
</head>

<body class="font-sans antialiased">
    <div class="bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Content -->
        <main class="mt-8 bg-white min-h-screen w-full max-w-3xl m-auto">
            {{ $slot }}
        </main>
    </div>
    <x-modal />
</body>

</html>