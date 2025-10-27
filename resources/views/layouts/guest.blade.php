<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Prep Academy') }}</title>

    <!-- Site icons / favicons -->
    <link rel="icon" type="image/png" href="{{ asset('img/prep-academy-favicon.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('img/prep-academy-favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/prep-academy-favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Tailwind CSS desde CDN en lugar de Vite -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                50: '#f0f9ff',
                                100: '#e0f2fe',
                                200: '#bae6fd',
                                300: '#7dd3fc',
                                400: '#38bdf8',
                                500: '#0ea5e9',
                                600: '#0284c7',
                                700: '#0369a1',
                                800: '#075985',
                                900: '#0c4a6e',
                            },
                        },
                        boxShadow: {
                            input: '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
                        },
                    }
                }
            }
        </script>
        
        <!-- Google Icon Font -->
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        
        <!-- Custom CSS & Script -->
        <script src="{{ asset('js/auth.js') }}" defer></script>
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-row">
            <!-- Usando la imagen completa como fondo para pantallas pequeñas -->
            <div class="fixed inset-0 bg-blue-50 z-0 lg:hidden"></div>
            
            <!-- Lado izquierdo con la imagen para pantallas grandes -->
            <div class="hidden lg:block lg:w-1/2 bg-cover bg-center relative" style="background-color: #f0f7ff;">
                <div class="absolute inset-0 flex items-center justify-center">
                    <img src="{{ asset('img/fondo_login.png') }}" alt="Estudiantes" class="w-full h-auto max-w-full object-cover" />
                </div>
            </div>
            
            <!-- Lado derecho con el formulario -->
            <div class="w-full lg:w-1/2 flex items-center justify-center relative z-10">
                <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-md sm:rounded-lg">
                    <!-- Logo centrado -->
                    <div class="flex justify-center mb-6">
                        <div class="flex items-center">
                            <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                <!-- Gorro de graduación -->
                                <path d="M12,3L1,9L12,15L21,10.09V17H23V9M5,13.18V17.18L12,21L19,17.18V13.18L12,17L5,13.18Z" />
                            </svg>
                            <span class="ml-2 text-xl font-bold text-gray-800">PREP ACADEMY</span>
                        </div>
                    </div>
                    
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
    @auth
        @include('partials.ai-chat')
    @endauth
</html>
