<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PKU Asset Monitoring') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Main Content dengan margin left --}}
    <main class="lg:ml-64 min-h-screen pt-16 lg:pt-0">
        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="mx-4 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mx-4 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Content Slot --}}
        {{ $slot }}
    </main>

    {{-- Stack untuk scripts tambahan --}}
    @stack('scripts')
</body>
</html>