@extends('layouts.erh')
@section('content')

    <div class="px-6 py-8">
        {{-- Header with Breadcrumb --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-text-primary">Approvisionner le stock</h1>
            <nav class="text-sm font-medium text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ url('bienvenue') }}" class="text-primary-accent hover:text-plastica-blue"><i class="fa fa-home"></i></a></li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-secondary">Tenues</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-5 w-5 text-slate-400 mx-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-text-primary font-semibold">Approvisionner le stock</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Messages --}}
        @include('success')
        @include('errors')

        {{-- Form Card --}}
        <div class="bg-white rounded-lg shadow-lg-soft overflow-hidden">
            <div class="px-6 py-8">
                <form method="POST" action="{{ url('store/approvisionner-stock') }}" class="space-y-6">
                    @csrf

                    {{-- Type de tenue --}}
                    <div>
                        <label for="type_tenue" class="block text-sm font-medium text-text-primary mb-2">Type de tenue</label>
                        <input type="text" id="type_tenue" name="type_tenue" required
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-transparent"
                               placeholder="Ex: Combinaison, Chemise, Pantalon">
                    </div>

                    {{-- Taille --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="taille" class="block text-sm font-medium text-text-primary mb-2">Taille</label>
                            <select id="taille" name="taille" required
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-transparent">
                                <option value="">Sélectionner une taille</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>
                        <div>
                            <label for="quantite" class="block text-sm font-medium text-text-primary mb-2">Quantité</label>
                            <input type="number" id="quantite" name="quantite" min="1" required
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-transparent"
                                   placeholder="0">
                        </div>
                    </div>

                    {{-- Couleur --}}
                    <div>
                        <label for="couleur" class="block text-sm font-medium text-text-primary mb-2">Couleur</label>
                        <input type="text" id="couleur" name="couleur" required
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-transparent"
                               placeholder="Ex: Bleu, Blanc, Noir">
                    </div>

                    {{-- Prix unitaire --}}
                    <div>
                        <label for="prix_unitaire" class="block text-sm font-medium text-text-primary mb-2">Prix unitaire</label>
                        <input type="number" id="prix_unitaire" name="prix_unitaire" min="0" step="0.01" required
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-transparent"
                               placeholder="0.00">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label for="notes" class="block text-sm font-medium text-text-primary mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="4"
                                  class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent focus:border-transparent"
                                  placeholder="Notes additionnelles..."></textarea>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-3 justify-end pt-6 border-t border-slate-200">
                        <a href="{{ url('liste-tenues') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg font-medium text-text-primary hover:bg-slate-50 transition">
                            <i class="fa fa-times mr-2"></i> Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-accent text-white rounded-lg font-medium hover:bg-plastica-blue transition">
                            <i class="fa fa-save mr-2"></i> Ajouter au stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
