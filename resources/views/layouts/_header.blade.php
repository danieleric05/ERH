<header class="flex items-center justify-between px-6 py-4 bg-white shadow-sm border-b border-slate-200">
    <!-- Bouton Menu Mobile -->
    <div class="md:hidden">
        {{-- Le clic sur ce bouton bascule l'état sidebarOpen du parent (erh.blade.php) --}}
        <button id="mobile-menu-button" @click="$dispatch('toggle-sidebar')" class="text-slate-500 hover:text-primary-accent focus:outline-none">
            {{-- L'icône changera potentiellement en fonction de l'état sidebarOpen, mais pour l'instant, c'est un hamburger --}}
            <svg id="mobile-menu-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Barre de Recherche (optionnelle) -->
    <div class="flex-grow max-w-sm px-4">
        <input type="text" class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-accent focus:border-primary-accent sm:text-sm" placeholder="Rechercher...">
    </div>

    <!-- Profil Utilisateur Dropdown -->
    <div class="ml-4 flex items-center md:ml-6" x-data="{ profileMenuOpen: false }">
        <div class="relative">
            <button @click="profileMenuOpen = !profileMenuOpen" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-accent" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                <span class="sr-only">Ouvrir le menu utilisateur</span>
                @php
                    $user = Auth::user();
                    $nom = $user->nom ?? 'User';
                    $prenom = $user->prenom ?? '';

                    // Extraire les initiales
                    $initiale1 = mb_substr($prenom ?: $nom, 0, 1);
                    $initiale2 = $prenom ? mb_substr($nom, 0, 1) : mb_substr($nom, 1, 1);
                    $initiales = strtoupper($initiale1 . $initiale2);

                    // Générer une couleur basée sur le nom (pour cohérence)
                    $colors = [
                        'bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-pink-500',
                        'bg-indigo-500', 'bg-red-500', 'bg-yellow-500', 'bg-teal-500',
                        'bg-orange-500', 'bg-cyan-500'
                    ];
                    $colorIndex = abs(crc32($nom)) % count($colors);
                    $bgColor = $colors[$colorIndex];
                @endphp
                <div class="h-8 w-8 rounded-full {{ $bgColor }} flex items-center justify-center text-white font-semibold text-xs">
                    {{ $initiales }}
                </div>
            </button>
            
            <div x-show="profileMenuOpen" @click.outside="profileMenuOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg-soft bg-white ring-1 ring-black ring-opacity-5 divide-y divide-slate-200 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                <div class="py-1" role="none">
                    <a href="{{ route('mon-profil') }}" class="block px-4 py-2 text-sm text-text-primary hover:bg-slate-100" role="menuitem">Profil</a>
                    <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-text-primary hover:bg-slate-100" role="menuitem">Paramètres</a>
                </div>
                <div class="py-1" role="none">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block px-4 py-2 text-sm text-text-secondary hover:bg-slate-100 w-full text-left" role="menuitem">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>