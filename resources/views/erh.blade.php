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
    <style>
        /* Styles pour une sidebar fixe et une gestion du scroll */
        .sidebar-fixed {
            height: 100vh; 
            overflow-y: auto; 
            position: fixed; 
            left: 0;
            top: 0;
            z-index: 10; 
        }
        .content-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            height: 100vh;
            overflow-y: auto; 
        }
        /* Responsive pour gérer la sidebar avec Alpine.js */
        /* La sidebar sera cachée par défaut sur mobile et apparaîtra quand sidebarOpen est true */
        /* Le margin-left du content-wrapper sera ajusté en conséquence */
        @media (max-width: 768px) { /* breakpoint 'md' de Tailwind par défaut */
            .sidebar-fixed {
                transform: translateX(-100%); /* Cache la sidebar par défaut sur mobile */
                transition: transform 0.3s ease-in-out;
            }
            .sidebar-fixed.hidden { /* Cette classe sera appliquée par Alpine.js quand sidebarOpen est false sur mobile */
                transform: translateX(0); /* Montre la sidebar */
            }
            .content-wrapper {
                margin-left: 0; /* Pas de marge gauche par défaut sur mobile */
            }
            /* Lorsque la sidebar est ouverte sur mobile, décaler le contenu */
            .content-wrapper.sidebar-open-mobile { /* Une classe personnalisée ou contrôlée par Alpine.js */
                margin-left: 16rem; /* Largeur de la sidebar */
            }
        }
    </style>
</head>
<body class="font-sans bg-slate-50 antialiased">
    {{-- Le conteneur principal gère l'état de la sidebar avec Alpine.js --}}
    <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar Partial -->
        {{-- La visibilité et la marge du contenu dépendent de sidebarOpen --}}
        {{-- Pour le mobile: hidden par défaut, affiché si sidebarOpen est true --}}
        {{-- Pour le desktop (md+): toujours visible (block), w-64 --}}
        <aside id="sidebar" 
               :class="{ 'hidden': !sidebarOpen && window.innerWidth < 768, 'w-64': sidebarOpen || window.innerWidth >= 768 }" 
               class="flex-shrink-0 bg-slate-800 text-slate-300 sidebar-fixed transition-all duration-300 ease-in-out md:block">
            <div class="flex items-center justify-center h-16 bg-slate-900 shadow-sm">
                <a href="{{ url('/') }}" class="flex items-center justify-center">
                    <span class="text-white font-bold uppercase text-lg">ERH</span>
                </a>
            </div>
            
            @include('layouts._sidebar') {{-- Inclusion réelle du contenu de la sidebar --}}
        </aside>

        <!-- Contenu Principal Wrapper -->
        {{-- La marge gauche est contrôlée par Alpine.js et les classes Tailwind --}}
        <div class="content-wrapper transition-all duration-300 ease-in-out" 
             :class="{ 'md:ml-0': !sidebarOpen && window.innerWidth >= 768, 'md:ml-64': sidebarOpen && window.innerWidth >= 768, 'ml-0': !sidebarOpen && window.innerWidth < 768, 'ml-64': sidebarOpen && window.innerWidth < 768 }">
            
            <!-- Header Partial -->
            @include('layouts._header')

            <!-- Zone de Contenu Principal -->
            <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>