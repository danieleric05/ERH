<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bienvenue sur ERH</title>

    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
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
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen flex flex-col items-center justify-center relative p-4">
        {{-- Navigation (Login/Accueil) dans le coin supérieur droit --}}
        @if (Route::has('login'))
            <nav class="absolute top-0 right-0 px-6 py-4">
                @auth
                    {{-- Si l'utilisateur est authentifié, le lien est vers la page d'accueil de l'application --}}
                    <a href="{{ url('/bienvenue') }}" class="text-sm text-text-secondary hover:text-primary-accent underline px-4">Accueil</a>
                @else
                    {{-- Si l'utilisateur n'est pas authentifié, le lien est vers la page de connexion --}}
                    <a href="{{ route('login') }}" class="text-sm text-text-secondary hover:text-primary-accent underline px-4">Se connecter</a>
                @endauth
            </nav>
        @endif

        {{-- Contenu principal de la page de bienvenue --}}
        <div class="text-center bg-white p-10 rounded-lg shadow-lg-soft max-w-lg">
            <div class="mb-6">
                <i class="fa fa-briefcase text-6xl text-plastica-blue"></i>
            </div>
            <h1 class="text-5xl font-bold text-text-primary mb-4">Bienvenue sur ERH</h1>
            <p class="text-xl text-text-secondary mb-8">Système complet de gestion des ressources humaines</p>

            {{-- Call-to-action buttons --}}
            <div class="flex flex-col gap-4 justify-center">
                @auth
                    <a href="{{ url('/bienvenue') }}"
                       class="px-6 py-3 bg-plastica-blue text-white rounded-lg hover:bg-primary-accent transition font-medium flex items-center justify-center gap-2">
                        <i class="fa fa-arrow-right"></i> Accéder au tableau de bord
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-6 py-3 bg-plastica-blue text-white rounded-lg hover:bg-primary-accent transition font-medium flex items-center justify-center gap-2">
                        <i class="fa fa-sign-in"></i> Se connecter
                    </a>
                @endauth
            </div>

            <hr class="my-6 border-slate-200">

            {{-- Liens de documentation --}}
            <div class="text-sm text-text-secondary">
                <p class="mb-3">Documentation et ressources:</p>
                <div class="space-y-2">
                    <div>
                        <a href="https://laravel.com/docs" target="_blank" rel="noopener noreferrer" class="text-plastica-blue hover:text-primary-accent underline font-medium">
                            <i class="fa fa-external-link text-xs"></i> Documentation Laravel
                        </a>
                    </div>
                    <div>
                        <a href="https://laracasts.com" target="_blank" rel="noopener noreferrer" class="text-plastica-blue hover:text-primary-accent underline font-medium">
                            <i class="fa fa-external-link text-xs"></i> Laracasts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
