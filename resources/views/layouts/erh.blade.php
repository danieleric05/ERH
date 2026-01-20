<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ERH - RH Digital')</title>

    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            },
            colors: {
              'slate-50': '#f8fafc',
              'slate-800': '#1e293b',
              'slate-900': '#0f172a',
              'white': '#ffffff',
              'plastica-blue': '#0A2463',
              'primary-accent': '#0e7490',
              'text-primary': '#0f172a',
              'text-secondary': '#64748b',
            },
            boxShadow: {
                'lg-soft': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1)',
            }
          }
        }
      }
    </script>

    <!-- Scripts Alpine.js via CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.10/dist/cdn.min.js"></script>

    @stack('styles')
</head>
<body class="font-sans bg-slate-50 antialiased">
    {{-- Le conteneur principal gère l'état de la sidebar avec Alpine.js --}}
    <div x-data="{ sidebarOpen: window.innerWidth >= 768 }"
         @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
         @resize.window="sidebarOpen = window.innerWidth >= 768"
         class="flex min-h-screen">

        <!-- Sidebar -->
        @include('layouts._sidebar')

        {{-- Overlay pour mobile quand la sidebar est ouverte --}}
        <div x-show="sidebarOpen && window.innerWidth < 768"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-40 bg-black opacity-50 transition-opacity duration-300 ease-in-out"></div>

        <!-- Contenu Principal Wrapper -->
        <div class="flex-1 flex flex-col">

            <!-- Header Partial -->
            @include('layouts._header')

            <!-- Zone de Contenu Principal -->
            <main class="flex-1 overflow-y-auto bg-slate-50">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
