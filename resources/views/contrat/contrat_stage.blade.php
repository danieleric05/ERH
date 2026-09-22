<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CONVENTION DE STAGE - {{ $travailleur->nom }} {{ $travailleur->prenom }}</title>
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

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .fill-line {
            display: inline-block;
            min-width: 220px;
            border-bottom: 1px solid #000;
        }

        table.signatures {
            width: 100%;
            margin-top: 30px;
        }

        table.signatures td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    @php
    $tabDateDebut = $travailleur->date_debut_contrat ? explode("-", $travailleur->date_debut_contrat) : ['0000', '00', '00'];
    $tabDateFin = $travailleur->date_fin_contrat ? explode("-", $travailleur->date_fin_contrat) : ['0000', '00', '00'];

    $civilite = 'Monsieur';
    if(in_array($travailleur->civilite, ['Mlle', 'Mlle.', 'Mademoiselle'])) $civilite = 'Mademoiselle';
    elseif(in_array($travailleur->civilite, ['Mme', 'Mme.', 'Madame'])) $civilite = 'Madame';

    $nomComplet = trim(strtoupper($travailleur->nom ?? '') . ' ' . strtoupper($travailleur->prenoms_complets));

    $indemniteMensuelle = $travailleur->prime_transport
        ? number_format($travailleur->prime_transport, 0, ',', ' ') . ' F CFA'
        : '..........................';
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
                <span class="bold" style="font-size: 12px;">CONVENTION DE STAGE</span>
            </td>
            <td style="width: 25%;">
                Ref: ENR-RH-022<br>
                Version: 01<br>
                Du: 09/08/2021
            </td>
        </tr>
        <tr>
            <td>Gérer les ressources humaines</td>
            <td>Page: 1/1</td>
        </tr>
    </table>

    <p class="text-right">Objet : Convention de Stage</p>
    <p class="text-right">Abidjan, le {{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</p>

    <div class="section">
        {{ $civilite }} <span class="bold">{{ $nomComplet }}</span>,
        <br><br>
        École / Établissement de formation : <span class="fill-line">&nbsp;</span>
        <br><br>
        Formation suivie : <span class="fill-line">&nbsp;</span>
        <br><br>
        Nous avons le plaisir de vous confirmer notre accord de vous admettre comme stagiaire dans notre Société du
        <span class="bold">{{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</span> au
        <span class="bold">{{ $tabDateFin[2] }}/{{ $tabDateFin[1] }}/{{ $tabDateFin[0] }}</span>.
        <br><br>
        Durant votre stage, une indemnité forfaitaire mensuelle de <span class="bold">{{ $indemniteMensuelle }}</span> vous sera versée au titre des divers frais y compris le transport.
        <br><br>
        <span class="bold">Obligations :</span> En votre qualité de Stagiaire, vous êtes soumis(e) aux horaires de travail et au règlement intérieur de notre entreprise dont vous déclarez avoir pris connaissance.
        <br><br>
        <span class="bold">Déroulement du Stage :</span> Monsieur / Madame <span class="fill-line">&nbsp;</span>, chef du département <span class="fill-line">&nbsp;</span>, est chargé(e) d'élaborer le programme de votre stage.
        <br><br>
        Pour la bonne règle, vous voudrez bien nous retourner le deuxième exemplaire de la présente convention revêtue de votre signature précédée de la mention manuscrite &laquo; Lu et Approuvée &raquo;.
        <br><br>
        Vous souhaitant pleine réussite dans nos services, nous vous prions de croire, {{ $civilite }}, en l'assurance de nos salutations distinguées.
    </div>

    <table class="signatures">
        <tr>
            <td>
                <span class="bold">La Direction</span><br><br><br><br>
                _______________________
            </td>
            <td>
                <span class="bold">Stagiaire</span><br>
                &laquo; Lu et Approuvé &raquo;<br><br><br>
                _______________________
            </td>
        </tr>
    </table>

</body>

</html>
