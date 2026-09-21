@extends('layouts.erh')
@section('content')

<div class="p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <h1 class="text-3xl font-bold text-text-primary">Ajouter Travailleur (Non inscrit)</h1>
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('liste_tous_travailleurs') }}"
               class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-text-primary rounded-lg text-sm font-medium transition flex items-center gap-2 self-start md:self-auto">
                <i class="fa fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-text-secondary">
            <a href="{{ route('dashboard') }}" class="hover:text-primary-accent flex items-center gap-1">
                <i class="icon-home"></i> Accueil
            </a>
            <span>/</span>
            <a href="{{ route('liste_tous_travailleurs') }}" class="hover:text-primary-accent">
                Travailleurs
            </a>
            <span>/</span>
            <span class="text-text-primary font-medium">Ajouter travailleur (non inscrit dans la base)</span>
        </nav>
    </div>

    <!-- Messages -->
    @include('success')
    @include('errors')

    <!-- Form Container -->
    <div class="bg-white rounded-lg shadow-lg-soft p-8">
        <h2 class="text-xl font-bold text-text-primary mb-6">Enregistrement</h2>

        <form action="{{ url('post_travailleur_autres') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Photo de Profil -->
            <div class="mb-6 p-4 bg-slate-50 rounded-lg">
                <label for="photo" class="block text-sm font-medium text-text-primary mb-2">Photo du travailleur (optionnel)</label>
                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/jpg"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent"
                    onchange="previewPhotoAdd(this)">
                <p class="text-xs text-text-secondary mt-2">Formats acceptés: JPG, PNG (max 2 Mo)</p>

                <!-- Aperçu -->
                <div class="mt-4" id="photoPreviewContainer" style="display: none;">
                    <p class="text-xs font-semibold text-text-primary mb-2">Aperçu :</p>
                    <img id="photoPreviewAdd" src="" alt="Aperçu" class="w-24 h-24 rounded-full object-cover border-2 border-slate-300">
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <!-- Matricule -->
                <div>
                    <label for="matricule" class="block text-sm font-medium text-text-primary mb-2">Matricule</label>
                    <input required type="text" name="matricule" id="matricule"
                        value="A000"
                        placeholder="Le matricule"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-bold text-text-primary"
                        maxlength="50" readonly>
                </div>

                <!-- Nom -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-text-primary mb-2">Nom</label>
                    <input required type="text" name="nom" id="nom"
                        placeholder="Nom"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase"
                        @input="$el.value = $el.value.toUpperCase()">
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-text-primary mb-2">Prénoms</label>
                    <input required type="text" name="prenom" id="prenom"
                        placeholder="Prénoms"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent uppercase"
                        maxlength="150"
                        @input="$el.value = $el.value.toUpperCase()">
                </div>

                <!-- Unité -->
                <div>
                    <label for="uniteid" class="block text-sm font-medium text-text-primary mb-2">Unité</label>
                    <select required name="uniteid" id="uniteid"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($data_unites as $unite)
                            <option value="{{ $unite->id }}">{{ $unite->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Département -->
                <div>
                    <label for="departementid" class="block text-sm font-medium text-text-primary mb-2">Département</label>
                    <select required name="departementid" id="departementid"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($data_departements as $depart)
                            <option value="{{ $depart->id }}">{{ $depart->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fonction occupée -->
                <div>
                    <label for="fonction_entrepriseid" class="block text-sm font-medium text-text-primary mb-2">Fonction occupée</label>
                    <select required name="fonction_entrepriseid" id="fonction_entrepriseid"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white font-medium">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($data_fonctions as $fonction)
                            <option value="{{ $fonction->id }}">{{ $fonction->label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-text-primary mb-2">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone"
                        placeholder="Ex: 08080000"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent font-medium"
                        maxlength="10">
                </div>

                <!-- Pays -->
                <div>
                    <label for="paysid" class="block text-sm font-medium text-text-primary mb-2">Pays</label>
                    <select required name="paysid" id="paysid"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-accent bg-white">
                        <option value="">-SÉLECTIONNER-</option>
                        @foreach($data_pays as $pays)
                            <option value="{{ $pays->id }}">{{ $pays->label }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-center gap-4">
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Enregistrer
                </button>
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('liste_tous_travailleurs') }}" class="px-8 py-3 bg-gray-200 hover:bg-gray-300 text-text-primary rounded-lg font-medium transition-colors flex items-center gap-2">
                    <i class="fa fa-arrow-left"></i> Retour
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhotoAdd(input) {
    const container = document.getElementById('photoPreviewContainer');
    const preview = document.getElementById('photoPreviewAdd');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        container.style.display = 'none';
    }
}
</script>

@endsection