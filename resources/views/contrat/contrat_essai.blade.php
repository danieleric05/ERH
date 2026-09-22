<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CONTRAT D'ESSAI - {{ $travailleur->nom }} {{ $travailleur->prenom }}</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 10px;
            vertical-align: middle;
        }

        .header-table img {
            max-width: 80px;
            height: auto;
        }

        .section {
            margin-bottom: 8px;
            text-align: justify;
        }

        .article-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 12px;
            margin-bottom: 5px;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        table.signatures {
            width: 100%;
            margin-top: 30px;
        }

        table.signatures td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
    </style>
</head>

<body>

    @php
    $tabDate = $travailleur->date_naissance ? explode("-", $travailleur->date_naissance) : ['0000', '00', '00'];
    $tabDateP = $travailleur->pieceidentite_livrele ? explode("-", $travailleur->pieceidentite_livrele) : ['0000', '00', '00'];
    $tabDateDebut = $travailleur->date_debut_contrat ? explode("-", $travailleur->date_debut_contrat) : ['0000', '00', '00'];
    $tabDateFinEssai = $travailleur->date_fin_essai ? explode("-", $travailleur->date_fin_essai) : ['0000', '00', '00'];

    $civilite = 'Monsieur';
    if(in_array($travailleur->civilite, ['Mlle', 'Mlle.', 'Mademoiselle'])) $civilite = 'Mademoiselle';
    elseif(in_array($travailleur->civilite, ['Mme', 'Mme.', 'Madame'])) $civilite = 'Madame';

    $nomComplet = trim(strtoupper($travailleur->nom ?? '') . ' ' . strtoupper($travailleur->prenoms_complets));
    $fonction = $travailleur->fonction_entrepriseid ? (\App\Fonction::where('id', $travailleur->fonction_entrepriseid)->first()?->label ?? '') : '';
    $commune = $travailleur->communeid ? (\App\Commune::where('id', $travailleur->communeid)->first()?->label ?? '') : '';
    $nationalite = $travailleur->nationaliteid ? (\App\Pays::where('id', $travailleur->nationaliteid)->value('nationalite') ?? 'IVOIRIENNE') : 'IVOIRIENNE';

    $primePhrase = $travailleur->prime_transport
        ? ' Une indemnité forfaitaire mensuelle de <span class="bold">' . number_format($travailleur->prime_transport, 0, ',', ' ') . ' F</span> CFA vous sera versée au titre des divers frais y compris le transport.'
        : '';

    $moisEnLettres = [1=>'Un',2=>'Deux',3=>'Trois',4=>'Quatre',5=>'Cinq',6=>'Six'];
    $dureeEssai = (int) ($travailleur->mois_essai ?? 1);
    $dureeEssaiLettres = $moisEnLettres[$dureeEssai] ?? null;
    @endphp

    <!-- En-tete du document -->
    <table class="header-table">
        <tr>
            <td rowspan="2" style="width: 20%;">
                <img src="{{ base_path('../rhassets/images/contrat.PNG') }}" style="width: 100%;">
            </td>
            <td style="width: 55%;">
                <span class="bold">Direction des Ressources Humaines</span><br>
                Enregistrement<br>
                <span class="bold" style="font-size: 12px;">CONTRAT D'ENGAGEMENT À L'ESSAI</span>
            </td>
            <td style="width: 25%;">
                Ref: ENR-RH-022<br>
                Version: 01<br>
                Du: 09/08/2021
            </td>
        </tr>
        <tr>
            <td>Gérer les ressources humaines</td>
            <td>Page: 1/2</td>
        </tr>
    </table>

    <div class="section">
        <span class="bold underline">Entre les soussignés,</span>
        <br><br>
        1) La Société dénommée &laquo; <span class="bold">PLASTICA CI</span> &raquo;, Société par Actions Simplifiée Unipersonnelle (SASU) au capital de deux milliards (2 000.000.000) FRANCS CFA, dont le siège social est fixé à ABIDJAN Zone Industrielle de KOUMASSI, 05 Boîte Postale 2160 Abidjan 05, immatriculée au Registre de Commerce et du Crédit Mobilier d'ABIDJAN sous le numéro CI-ABJ-1999-B-249382, déclarée à la CNPS sous le numéro <span class="bold">82408</span>.
        <br><br>
        Représentée par Monsieur <span class="bold">KOUAKOU Komenan</span>, Directeur Général, demeurant en son siège.
        <br><br>
        La SASU "<span class="bold">PLASTICA CI</span>" parfois dénommée dans le présent contrat et pour en faciliter sa rédaction "L'Employeur", ce terme s'entendant Monsieur KOUAKOU Komenan, ès qualité,
        <p class="text-right bold underline">D'UNE PART</p>
        Et
        <br><br>
        2) <span class="bold">{{ $civilite }} {{ $nomComplet }}</span>
        <br>
        @if($commune)
        demeurant à : <span class="bold">{{ $commune }}</span>
        @endif
        <br>
        Situation matrimoniale : <span class="bold">{{ ucfirst(strtolower($travailleur->situation_mat ?? '')) }}</span>
        <br>
        Nom du conjoint et régime matrimonial si possible : ...............................
        <br>
        De nationalité : <span class="bold">{{ $nationalite }}</span>
        <br>
        Né(e) le : <span class="bold">{{ $tabDate[2] }}/{{ $tabDate[1] }}/{{ $tabDate[0] }}</span> à <span class="bold">{{ strtoupper($travailleur->lieu_naissance ?? '') }}</span>
        <br>
        Titulaire - CNI n° : <span class="bold">{{ $travailleur->pieceidentite ?? '' }}</span> délivré(e) le <span class="bold">{{ $tabDateP[2] }}/{{ $tabDateP[1] }}/{{ $tabDateP[0] }}</span> à <span class="bold">{{ strtoupper($travailleur->pieceidentite_lieu ?? 'ABIDJAN') }}</span>
        <br>
        Nombre d'enfants à charge : <span class="bold">{{ $travailleur->nombre_enfant ?? '00' }}</span>
        <br>
        N° d'immatriculation CNPS : <span class="bold">{{ $travailleur->numero_securite ?? '' }}</span>
        <br>
        Matricule : <span class="bold">{{ strtoupper($travailleur->matricule ?? '') }}</span>
        <br><br>
        <span class="bold">{{ $civilite }} {{ $nomComplet }}</span> parfois dénommé(e) dans le présent contrat et pour en faciliter sa rédaction le "Travailleur" ou &laquo; le salarié &raquo;,
        <p class="text-left bold underline">D'AUTRE PART</p>
        Ensemble l'Employeur et le salarié pouvant être désignés, &laquo; Les Parties &raquo;
        <br><br>
        <span class="bold">Il a été convenu ce qui suit :</span>
    </div>

    <div class="section">
        <p class="article-title">Définition de la Fonction</p>
        <span class="bold">{{ $civilite }} {{ $nomComplet }}</span> est engagé(e) à l'essai par la SASU PLASTICA CI en qualité de <span class="bold">{{ $fonction }}</span> pour une période
        @if($dureeEssaiLettres)
            de <span class="bold">{{ $dureeEssai }} ({{ $dureeEssaiLettres }}) mois</span>
        @else
            de <span class="bold">{{ $dureeEssai }} mois</span>
        @endif
        qui commencera à courir à partir du <span class="bold">{{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</span> pour se terminer le <span class="bold">{{ $tabDateFinEssai[2] }}/{{ $tabDateFinEssai[1] }}/{{ $tabDateFinEssai[0] }}</span>.
    </div>

    <div class="section">
        <p class="article-title">Rémunération</p>
        {!! $primePhrase !!}
    </div>

    <div class="section">
        <p class="article-title">Obligations</p>
        En votre qualité de travailleur à l'essai, vous êtes soumis(e) aux horaires de travail et au règlement intérieur de notre entreprise dont vous déclarez avoir pris connaissance.
    </div>

    <div class="section">
        Pour la bonne règle, vous voudrez bien nous retourner le deuxième exemplaire de la présente convention revêtue de votre signature précédée de la mention manuscrite &laquo; Lu et Approuvée &raquo;.
    </div>

    <div class="section">
        Fait à Abidjan, le <span class="bold">{{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</span> en deux (02) exemplaires originaux de deux (02) pages.
    </div>

    <table class="signatures">
        <tr>
            <td class="text-left">
                <span class="bold">Le Travailleur</span><br>
                <span class="bold">{{ $civilite }} {{ $nomComplet }}</span><br>
                <i>(Signature précédée de la mention "Date, Lu et approuvé")</i><br><br><br><br>
                _______________________
            </td>
            <td class="text-right">
                <span class="bold">L'Employeur</span><br>
                <span class="bold">KOUAKOU Komenan</span><br>
                <i>(Le D.G.)</i><br><br><br><br>
                _______________________
            </td>
        </tr>
    </table>

</body>

</html>
