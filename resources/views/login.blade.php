<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>ERH - Connexion</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('rhassets/images/logoo.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
</head>

<body class="font-sans antialiased" style="background-image: url('{{ asset('rhassets/images/auth_bg.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    <div class="min-h-screen flex items-center justify-center p-4">
        <!-- Background overlay for better readability -->
        <div class="absolute inset-0 bg-slate-900 opacity-30"></div>

        <!-- Login Box -->
        <div class="relative z-10 w-full max-w-sm bg-white rounded-lg shadow-lg-soft p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <img src="{{ asset('rhassets/images/logoERH.png') }}" alt="Logo ERH" class="mx-auto h-16 w-auto mb-4">
                <h1 class="text-3xl font-bold text-text-primary">Bienvenue sur ERH</h1>
                <p class="text-sm text-text-secondary mt-2">Connectez-vous pour accéder à votre espace</p>
            </div>

            <!-- Messages de succès et d'erreur -->
            @include('success')
            @include('errors')

            <!-- Formulaire de Connexion -->
            <form action="{{ url('post_login') }}" method="POST" role="form" class="space-y-6">
                @csrf

                <!-- Champ Pseudo -->
                <div>
                    <label for="pseudo" class="block text-sm font-medium text-text-secondary">Pseudo</label>
                    <input type="text" id="pseudo" name="pseudo" required
                           class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition"
                           placeholder="Votre pseudo">
                </div>

                <!-- Champ Mot de passe -->
                <div>
                    <label for="password" class="block text-sm font-medium text-text-secondary">Mot de passe</label>
                    <input type="password" id="password" name="password" required
                           class="mt-1 block w-full px-4 py-3 border border-slate-300 rounded-md shadow-sm text-sm focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-primary-accent transition"
                           placeholder="Votre mot de passe">
                </div>

                <!-- Se rappeler & Mot de passe oublié -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                               class="h-4 w-4 rounded border-slate-300 text-primary-accent focus:ring-2 focus:ring-primary-accent">
                        <label for="remember-me" class="ml-2 block text-sm text-text-secondary">Se rappeler de moi</label>
                    </div>
                    <button type="button" @click="$dispatch('open-modal', { modalId: 'forgot-password-modal' })"
                            class="text-sm font-medium text-primary-accent hover:text-plastica-blue focus:outline-none transition">
                        Mot de passe oublié ?
                    </button>
                </div>

                <!-- Bouton Se connecter -->
                <button type="submit"
                        class="w-full py-3 px-4 bg-primary-accent text-white rounded-md shadow-sm text-sm font-medium hover:bg-plastica-blue focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent transition">
                    Se connecter
                </button>
            </form>

            <!-- Section Mission -->
            <div class="mt-8 text-center border-t border-slate-200 pt-6">
                <p class="text-sm text-text-secondary mb-4">Vous partez en mission ?</p>
                <p class="text-xs text-text-secondary mb-4">Complétez le formulaire et le DRH vous contactera</p>
                <button type="button" @click="$dispatch('open-modal', { modalId: 'mission-form-modal' })"
                        class="w-full py-3 px-4 bg-red-600 text-white rounded-md shadow-sm text-sm font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                    <i class="fa fa-plane mr-2"></i> Formulaire de mission
                </button>
            </div>
        </div>
    </div>

    <!-- Modal: Mot de passe oublié -->
    <div x-data="{ open: false, modalId: 'forgot-password-modal' }"
         x-show="open"
         @open-modal.window="open = ($event.detail.modalId === modalId)"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <!-- Overlay -->
            <div @click="open = false" class="fixed inset-0 bg-slate-900 opacity-50" aria-hidden="true"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-text-primary">Mot de passe oublié</h3>
                </div>

                <!-- Body -->
                <div class="px-6 py-4">
                    <p class="text-sm text-text-secondary mb-4">
                        Entrez votre pseudo ou votre email pour réinitialiser votre mot de passe.
                    </p>
                    <input type="text"
                           class="w-full px-4 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-primary-accent"
                           placeholder="Pseudo ou Email">
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex gap-3 justify-end">
                    <button @click="open = false" type="button"
                            class="px-4 py-2 text-sm font-medium text-text-primary border border-slate-300 rounded-md hover:bg-slate-50 focus:outline-none transition">
                        Annuler
                    </button>
                    <button type="button"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-accent rounded-md hover:bg-plastica-blue focus:outline-none transition">
                        Envoyer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Formulaire Mission -->
    <div x-data="{ open: false, modalId: 'mission-form-modal' }"
         x-show="open"
         @open-modal.window="open = ($event.detail.modalId === modalId)"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <!-- Overlay -->
            <div @click="open = false" class="fixed inset-0 bg-slate-900 opacity-50" aria-hidden="true"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-text-primary">Formulaire de mission</h3>
                </div>

                <!-- Body -->
                <div class="px-6 py-4 space-y-4">
                    <p class="text-sm text-text-secondary">
                        Veuillez compléter ce formulaire pour une demande de mission.
                    </p>
                    <div>
                        <label for="mission-motif" class="block text-sm font-medium text-text-secondary mb-1">Motif</label>
                        <input type="text" id="mission-motif"
                               class="w-full px-4 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-primary-accent"
                               placeholder="Motif de la mission">
                    </div>
                    <div>
                        <label for="mission-date" class="block text-sm font-medium text-text-secondary mb-1">Date</label>
                        <input type="date" id="mission-date"
                               class="w-full px-4 py-2 border border-slate-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-primary-accent">
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex gap-3 justify-end">
                    <button @click="open = false" type="button"
                            class="px-4 py-2 text-sm font-medium text-text-primary border border-slate-300 rounded-md hover:bg-slate-50 focus:outline-none transition">
                        Annuler
                    </button>
                    <button type="button"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-accent rounded-md hover:bg-plastica-blue focus:outline-none transition">
                        Envoyer la demande
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>