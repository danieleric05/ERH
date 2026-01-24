@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary mb-4">Modifier Travailleur</h1>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ route('dashboard') }}" class="hover:text-primary-accent">
                <i class="icon-home"></i> Accueil
            </a>
            <span>/</span>
            <span>Recrutement</span>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter travailleur : Étape 2</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Étape 2 : Enregistrement</h2>

        <form action="{{ url('post_edit_travailleur/' . $edit->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- SECTION 0: Photo de Profil -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-text-primary mb-4 pb-2 border-b border-slate-200">Photo de Profil</h3>
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <!-- Aperçu de la photo actuelle -->
                    <div class="flex-shrink-0">
                        <img id="photoPreview"
                             src="{{ $edit->photo ? asset('rhassets/images/travailleurs/' . $edit->photo) : asset('rhassets/images/travailleurs/default.png') }}"
                             alt="Photo du travailleur"
                             class="w-32 h-32 rounded-full object-cover border-4 border-slate-200 shadow-lg">
                    </div>

                    <!-- Champ upload -->
                    <div class="flex-1">
                        <label for="photo" class="block text-sm font-medium text-text-primary mb-2">Changer la photo</label>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                            onchange="previewPhoto(this)">
                        <p class="text-xs text-text-secondary mt-2">Formats acceptés: JPG, PNG (max 2 Mo)</p>
                        @if($edit->photo)
                            <div class="mt-3 flex items-center gap-3">
                                <p class="text-xs text-green-600">Photo actuelle : {{ $edit->photo }}</p>
                                <form action="{{ route('delete_photo_travailleur', $edit->id) }}" method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette photo ?');"
                                      class="inline-block">
                                    @csrf
                                    <button type="submit"
                                            class="px-3 py-1.5 text-xs bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                        <i class="fa fa-trash"></i> Supprimer la photo
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- SECTION 1: Informations de Base -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-text-primary mb-4 pb-2 border-b border-slate-200">Informations de Base</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Matricule -->
                    <div>
                        <label for="matricule" class="block text-sm font-medium text-text-primary mb-2">Matricule</label>
                        <input type="text" name="matricule" id="matricule"
                            value="{{ $edit->matricule }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-gray-50 text-text-primary font-bold"
                            readonly>
                    </div>

                    <!-- Nom -->
                    <div>
                        <label for="nom" class="block text-sm font-medium text-text-primary mb-2">Nom</label>
                        <input type="text" name="nom" id="nom"
                            value="{{ $edit->nom }}"
                            placeholder="Nom"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary uppercase"
                            @input="$el.value = $el.value.toUpperCase()">
                    </div>

                    <!-- Prénom -->
                    <div>
                        <label for="prenom" class="block text-sm font-medium text-text-primary mb-2">Prénoms</label>
                        <input type="text" name="prenom" id="prenom"
                            value="{{ $edit->prenom . $edit->prenom_suite }}"
                            placeholder="Prénoms"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary uppercase"
                            maxlength="150"
                            @input="$el.value = $el.value.toUpperCase()">
                    </div>

                    <!-- Date d'embauche -->
                    <div>
                        <label for="dateembauche" class="block text-sm font-medium text-text-primary mb-2">Date d'embauche</label>
                        <input type="date" name="dateembauche" id="dateembauche"
                            value="{{ $edit->date_debut_contrat }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary">
                    </div>

                    <!-- Date de fin de contrat -->
                    <div>
                        <label for="datefincontrat" class="block text-sm font-medium text-text-primary mb-2">Date de fin de contrat</label>
                        <input type="date" name="datefincontrat" id="datefincontrat"
                            value="{{ $edit->date_fin_contrat }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary">
                    </div>

                    <!-- Date de naissance -->
                    <div>
                        <label for="datenaissance" class="block text-sm font-medium text-text-primary mb-2">Date de naissance</label>
                        <input type="date" name="datenaissance" id="datenaissance"
                            value="{{ $edit->date_naissance }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary">
                    </div>

                    <!-- Situation matrimoniale -->
                    <div>
                        <label for="situation_mat" class="block text-sm font-medium text-text-primary mb-2">Situation matrimoniale</label>
                        <select name="situation_mat" id="situation_mat"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary">
                            <option @if($edit->situation_mat == 'Célibataire') selected @endif value="Celibataire">Célibataire</option>
                            <option @if($edit->situation_mat == 'Marié(e)') selected @endif value="Marie">Marié(e)</option>
                        </select>
                    </div>

                    <!-- Unité (readonly) -->
                    <div>
                        <label for="uniteid" class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                        <select name="uniteid" id="uniteid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-gray-50 font-bold text-text-primary"
                            readonly>
                            @foreach($unites as $unite)
                                <option @if($edit->uniteid == $unite->id) selected @endif value="{{ $unite->id }}">{{ $unite->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Département (readonly) -->
                    <div>
                        <label for="departementid" class="block text-sm font-medium text-text-primary mb-2">Département</label>
                        <select name="departementid" id="departementid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-gray-50 font-bold text-text-primary"
                            readonly>
                            @foreach($departements as $depart)
                                <option @if($edit->departementid == $depart->id) selected @endif value="{{ $depart->id }}">{{ $depart->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Équipe (readonly) -->
                    <div>
                        <label for="equipeid" class="block text-sm font-medium text-text-primary mb-2">Équipe</label>
                        <select name="equipeid" id="equipeid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-gray-50 font-bold text-text-primary"
                            readonly>
                            @foreach($equipes as $equipe)
                                <option @if($edit->equipeid == $equipe->id) selected @endif value="{{ $equipe->id }}">{{ $equipe->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Type Contrat -->
                    <div>
                        <label for="idtype_contrat" class="block text-sm font-medium text-text-primary mb-2">Type Contrat</label>
                        <select name="idtype_contrat" id="idtype_contrat"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary">
                            @foreach($data_typecontrat as $contrat)
                                <option @if($edit->idtype_contrat == $contrat->id) selected @endif value="{{ $contrat->id }}">{{ $contrat->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pays -->
                    <div>
                        <label for="paysid" class="block text-sm font-medium text-text-primary mb-2">Pays</label>
                        <select name="paysid" id="paysid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary">
                            @foreach($pays as $pay)
                                <option @if($edit->nationaliteid == $pay->id) selected @endif value="{{ $pay->id }}">{{ $pay->label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Pièce d'Identité -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-text-primary mb-4 pb-2 border-b border-slate-200">Pièce d'Identité</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- N° Pièce d'identité -->
                    <div>
                        <label for="pieceidentite" class="block text-sm font-medium text-text-primary mb-2">N° Pièce d'identité (ATT/CNI)</label>
                        <input type="text" name="pieceidentite" id="pieceidentite"
                            value="{{ $edit->pieceidentite }}"
                            placeholder="N° Pièce d'identité"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary uppercase"
                            maxlength="150"
                            @input="$el.value = $el.value.toUpperCase()">
                    </div>

                    <!-- Pièce d'identité Livrée le -->
                    <div>
                        <label for="pieceidentite_livrele" class="block text-sm font-medium text-text-primary mb-2">Pièce d'identité Livrée le</label>
                        <input type="date" name="pieceidentite_livrele" id="pieceidentite_livrele"
                            value="{{ $edit->pieceidentite_livrele }}"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary">
                    </div>

                    <!-- Lieu d'acquisition -->
                    <div>
                        <label for="pieceidentite_lieu" class="block text-sm font-medium text-text-primary mb-2">Lieu d'acquisition</label>
                        <input type="text" name="pieceidentite_lieu" id="pieceidentite_lieu"
                            value="{{ $edit->pieceidentite_lieu }}"
                            placeholder="Lieu d'acquisition"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary uppercase"
                            maxlength="150"
                            @input="$el.value = $el.value.toUpperCase()">
                    </div>

                    <!-- Lieu de naissance -->
                    <div>
                        <label for="lieunaissance" class="block text-sm font-medium text-text-primary mb-2">Lieu de naissance</label>
                        <input type="text" name="lieunaissance" id="lieunaissance"
                            value="{{ $edit->lieu_naissance }}"
                            placeholder="Lieu de naissance"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary uppercase"
                            maxlength="150"
                            @input="$el.value = $el.value.toUpperCase()">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-primary mb-2">Email</label>
                        <input type="email" name="email" id="email"
                            value="{{ $edit->email }}"
                            placeholder="E-mail"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary"
                            maxlength="150">
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="telephone" class="block text-sm font-medium text-text-primary mb-2">Téléphone</label>
                        <input type="tel" name="telephone" id="telephone"
                            value="{{ $edit->telephone }}"
                            placeholder="Ex: 08080000"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary"
                            maxlength="10">
                    </div>

                    <!-- Téléphone 2 -->
                    <div>
                        <label for="telephone2" class="block text-sm font-medium text-text-primary mb-2">Téléphone 2</label>
                        <input type="tel" name="telephone2" id="telephone2"
                            value="{{ $edit->telephone2 }}"
                            placeholder="Ex: 08080000"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary"
                            maxlength="10">
                    </div>

                    <!-- Numéro de sécurité -->
                    <div>
                        <label for="numero_securite" class="block text-sm font-medium text-text-primary mb-2">Numéro de sécurité</label>
                        <input type="text" name="numero_securite" id="numero_securite"
                            value="{{ $edit->numero_securite }}"
                            placeholder="Numéro de sécurité"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary uppercase"
                            maxlength="12"
                            @input="$el.value = $el.value.toUpperCase()">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Emploi -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-text-primary mb-4 pb-2 border-b border-slate-200">Informations Professionnelles</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Bulletin modèle -->
                    <div>
                        <label for="bulletin_modele_salarie" class="block text-sm font-medium text-text-primary mb-2">Bulletin modèle du salarié</label>
                        <input type="text" name="bulletin_modele_salarie" id="bulletin_modele_salarie"
                            value="{{ $edit->bulletin_modele_salarie }}"
                            placeholder="Bulletin modèle"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-gray-50 font-bold text-text-primary uppercase"
                            maxlength="150" readonly>
                    </div>

                    <!-- Fonction occupée -->
                    <div>
                        <label for="fonction_entrepriseid" class="block text-sm font-medium text-text-primary mb-2">Fonction occupée</label>
                        <select name="fonction_entrepriseid" id="fonction_entrepriseid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary"
                            required>
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($fonctions as $fonction)
                                <option @if($edit->fonction_entrepriseid == $fonction->id) selected @endif value="{{ $fonction->id }}">{{ $fonction->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Commune -->
                    <div>
                        <label for="communeid" class="block text-sm font-medium text-text-primary mb-2">Commune</label>
                        <select name="communeid" id="communeid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary">
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($commune as $comm)
                                <option @if($edit->communeid == $comm->id) selected @endif value="{{ $comm->id }}">{{ $comm->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="categorieid" class="block text-sm font-medium text-text-primary mb-2">Catégorie</label>
                        <select name="categorieid" id="categorieid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary">
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($categories as $category)
                                <option @if($edit->categorieid == $category->id) selected @endif value="{{ $category->id }}">{{ $category->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Niveau d'étude -->
                    <div>
                        <label for="niveau_etudeid" class="block text-sm font-medium text-text-primary mb-2">Niveau d'étude</label>
                        <select name="niveau_etudeid" id="niveau_etudeid"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary">
                            <option value="">-SÉLECTIONNER-</option>
                            @foreach($niveauEtudes as $niveau)
                                <option @if($edit->niveau_etudeid == $niveau->id) selected @endif value="{{ $niveau->id }}">{{ $niveau->label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Civilité -->
                    <div>
                        <label for="civilite" class="block text-sm font-medium text-text-primary mb-2">Civilité</label>
                        <select name="civilite" id="civilite"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-bold text-text-primary">
                            <option value="">-SÉLECTIONNER-</option>
                            <option @if($edit->civilite == 'Monsieur') selected @endif value="Monsieur">Monsieur</option>
                            <option @if($edit->civilite == 'Mademoiselle') selected @endif value="Mademoiselle">Mademoiselle</option>
                            <option @if($edit->civilite == 'Madame') selected @endif value="Madame">Madame</option>
                        </select>
                    </div>

                    <!-- Nombre d'enfants -->
                    <div>
                        <label for="nombre_enfant" class="block text-sm font-medium text-text-primary mb-2">Nombre d'enfants</label>
                        <input type="number" name="nombre_enfant" id="nombre_enfant"
                            value="{{ $edit->nombre_enfant }}"
                            placeholder="Nombre d'enfants"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary"
                            min="0">
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Documents -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-text-primary mb-4 pb-2 border-b border-slate-200">Documents (Facultatif)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- CNI -->
                    <div>
                        <label for="file_cni" class="block text-sm font-medium text-text-primary mb-2">CNI</label>
                        <input type="file" name="file_cni" id="file_cni"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary">
                        <p class="text-xs text-text-secondary mt-1">Formats acceptés: JPG, PNG, PDF</p>
                    </div>

                    <!-- Extrait de naissance -->
                    <div>
                        <label for="file_extrait_naiss" class="block text-sm font-medium text-text-primary mb-2">Extrait de naissance</label>
                        <input type="file" name="file_extrait_naiss" id="file_extrait_naiss"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary">
                        <p class="text-xs text-text-secondary mt-1">Formats acceptés: JPG, PNG, PDF</p>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Savoir-faire -->
            <div class="mb-8">
                <label for="mot" class="block text-sm font-medium text-text-primary mb-2">Un Mot Sur Le Journalier (Savoir-faire)</label>
                <textarea name="mot" id="mot" rows="4"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary"
                    placeholder="Décrivez les compétences, savoir-faire et qualifications...">{{ $edit->description }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-center gap-4 pt-6 border-t border-slate-200">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Finaliser l'inscription
                </button>
                <a href="{{ route('listetravailleurs') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors">
                    Liste
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

@endsection
