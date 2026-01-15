<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CONTRAT JOURNALIER - {{ $travailleur->nom }} {{ $travailleur->prenom }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100%;
            height: auto;
        }
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
        }
        .section {
            margin-bottom: 10px;
            text-align: justify;
        }
        .article-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .underline {
            text-decoration: underline;
        }
        table.remuneration {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        table.remuneration td {
            padding: 3px 5px;
            vertical-align: top;
        }
        table.remuneration .label {
            width: 60%;
        }
        table.remuneration .amount {
            width: 40%;
            text-align: right;
        }
        table.remuneration .subtotal {
            font-weight: bold;
            border-top: 1px solid #000;
        }
        table.remuneration .section-title {
            font-weight: bold;
            padding-top: 10px;
        }
        table.signatures {
            width: 100%;
            margin-top: 50px;
        }
        table.signatures td {
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <!-- EN-TETE - Remplacer par l'image scannee -->
    <div class="header">
        <img src="{{ base_path('../rhassets/images/contrat.PNG') }}">
    </div>

    <div class="section">
        <span class="bold underline">Entre les soussignés,</span>
        <br><br>
        1) La Société dénommée &laquo; PLASTICA &raquo;, Société par Actions Simplifiée Unipersonnelle (SASU) au capital social de deux milliards (2.000.000.000) FRANCS CFA, dont le siège social est fixé à ABIDJAN Zone Industrielle de KOUMASSI, 05 Boîte Postale 2160 Abidjan 05, immatriculée au Registre de Commerce et du Crédit Mobilier d'ABIDJAN sous le numéro CI-ABJ-1999-B-249382, déclarée à la CNPS sous le numéro 82408.
        <br><br>
        Prise en la personne de son représentant légal, demeurant ès-qualité audit siège,
        <br><br>
        La <span class="bold">SASU "PLASTICA"</span> parfois dénommée dans le présent contrat "L'Employeur",
        <p class="text-right bold underline">D'UNE PART</p>
        ET
        <br><br>
        @php
            $tabDate = $travailleur->date_naissance ? explode("-", $travailleur->date_naissance) : ['', '', ''];
            $tabDateP = $travailleur->pieceidentite_livrele ? explode("-", $travailleur->pieceidentite_livrele) : ['', '', ''];
            $civilite = '';
            if($travailleur->civilite == 'Monsieur') $civilite = 'M.';
            elseif($travailleur->civilite == 'Mademoiselle') $civilite = 'Mlle.';
            elseif($travailleur->civilite == 'Madame') $civilite = 'Mme.';
        @endphp
        2) <span class="bold">{{ $civilite }} {{ $travailleur->nom }} {{ $travailleur->prenom }} {{ $travailleur->prenom_suite }}</span> demeurant à <span class="bold">{{ \App\Commune::where('id', $travailleur->communeid)->first()?->label ?? '' }}</span>
        <br>
        Situation matrimoniale : <span class="bold">{{ ucfirst(strtolower($travailleur->situation_mat ?? '')) }}</span> - Nom du (de la) conjoint(e) : ...............
        <br>
        @if($travailleur->nationaliteid)
            De nationalité : <span class="bold">{{ \App\Pays::where('id', $travailleur->nationaliteid)->value('nationalite') ?? '' }}</span>
        @else
            De nationalité : ...............
        @endif
        <br>
        Né(e) le : <span class="bold">{{ isset($tabDate[2]) ? $tabDate[2].'/'.$tabDate[1].'/'.$tabDate[0] : '' }}</span> à <span class="bold">{{ strtoupper($travailleur->lieu_naissance ?? '') }}</span>
        <br>
        Pièce d'identité : (ATT/CNI) N° <span class="bold">{{ $travailleur->pieceidentite ?? '' }}</span>
        @if($travailleur->pieceidentite_livrele && isset($tabDateP[2]))
            délivrée le <span class="bold">{{ $tabDateP[2].'/'.$tabDateP[1].'/'.$tabDateP[0] }}</span>
        @endif
        à <span class="bold">{{ $travailleur->pieceidentite_lieu ?? '' }}</span> par ONI
        <br>
        Catégorie Professionnelle : <span class="bold">{{ $travailleur->categorieid ? (\App\Categories::where('id', $travailleur->categorieid)->first()?->label ?? '') : '' }}</span>
        <br>
        Matricule : <span class="bold">{{ strtoupper($travailleur->matricule ?? '') }}</span>
        <br>
        N° CNPS : <span class="bold">{{ $travailleur->numero_securite ?? '' }}</span>
        <br><br>
        <span class="bold">{{ $civilite }} {{ strtoupper($travailleur->nom ?? '') }} {{ strtoupper($travailleur->prenom ?? '') }} {{ strtoupper($travailleur->prenom_suite ?? '') }}</span> dénommé(e) dans le présent contrat le "Travailleur occasionnel" ou &laquo; le Travailleur Journalier &raquo;,
        <p class="text-right bold underline">D'AUTRE PART</p>
    </div>

    <div class="section">
        <p class="article-title">Article 1 : Textes régissant le présent contrat</p>
        a) Dispositions de la loi N° 2015-532 du 20 juillet 2015 portant Code du Travail et des textes réglementaires pris pour son application.
        <br>
        b) Dispositions de la Convention Collective Interprofessionnelle de la Côte d'Ivoire en date du 20 Juillet 1977, ensemble les avenants, annexes et décisions de commissions mixtes qui ont modifié et complété cette convention ou qui viendraient à la modifier ou à la compléter.
        <br>
        c) Dispositions du Règlement Intérieur de la SASU PLASTICA dont le Travailleur reconnaît avoir pris connaissance.
        <br>
        d) Consignes techniques d'exploitation et de fabrication de la SASU PLASTICA, telles qu'elles lui seront inculquées par la Direction de PLASTICA et par ses supérieurs hiérarchiques.
    </div>

    <div class="section">
        <p class="article-title">Article 2 : De l'activité du Travailleur</p>
        Fonction : <span class="bold">{{ \App\Fonction::where('id', $travailleur->fonction_entrepriseid)->first()?->label ?? '' }}</span>
        <br>
        Unité de rattachement : <span class="bold">{{ \App\Equipes::where('id', $travailleur->equipeid)->first()?->label ?? '' }}</span>
        <br>
        (Avant éventuelles affectations pour nécessités de service)
    </div>

    <div class="section">
        <p class="article-title">Article 3 : Durée du contrat et horaires de travail</p>
        <span class="underline">Durée</span> : Le présent contrat est à durée journalière et peut, sauf dénonciation par l'une ou l'autre des parties, être renouvelé par tacite reconduction, en respect des dispositions du Code du Travail et de la Convention Collective.
        <br><br>
        <span class="underline">Horaires de travail</span> : Le Travailleur exerce son activité en équipe tournante de 06H à 14H ou de 14H à 22H ou de 22H à 06H ou de 07H à 15H ou de 08H à 16H ou autres horaires variables, dans la limite des huit (8) heures par jour.
    </div>

    <div class="section">
        <p class="article-title">Article 5 : Rémunération</p>
        Le salaire ci-dessous détaillé est la rémunération des huit (08) heures de travail journalier :

        <table class="remuneration">
            <tr>
                <td colspan="2" class="section-title">1/ Rémunération Brute imposable</td>
            </tr>
            <tr>
                <td class="label">Salaire journalier (SMIG de 75 000F) : 433 x 8 heures</td>
                <td class="amount">3 464 F</td>
            </tr>
            <tr>
                <td class="label">Gratification journalière : 27 x 8 heures</td>
                <td class="amount">216 F</td>
            </tr>
            <tr>
                <td class="label">Congé journalier : 38 x 8 heures</td>
                <td class="amount" style="border-bottom: 1px solid #000;">304 F</td>
            </tr>
            <tr>
                <td class="label bold">Sous total 1</td>
                <td class="amount bold">3 984 F</td>
            </tr>
            <tr>
                <td colspan="2" class="section-title">2/ Rémunération Non Imposable</td>
            </tr>
            <tr>
                <td class="label">Transport journalier</td>
                <td class="amount">1 154 F</td>
            </tr>
            <tr>
                <td class="label">Précarité (salaire + gratification) : 3 680 x 3%</td>
                <td class="amount" style="border-bottom: 1px solid #000;">110 F</td>
            </tr>
            <tr>
                <td class="label bold">Sous total 2</td>
                <td class="amount bold">1 264 F</td>
            </tr>
        </table>

        Le Travailleur qui n'aura pas accompli ses huit heures de travail journalier ne sera rémunéré qu'au prorata du nombre d'heures effectivement ouvrées, le relevé de la badgeuse faisant foi.
    </div>

    <div class="section">
        <p class="article-title">Article 6 : Rupture du contrat</p>
        Le présent contrat peut être résilié à la fin de chaque journée par l'une ou l'autre des parties, ou d'un commun accord, ou encore en cas de force majeure, de faute grave ou de faute lourde commise par l'une des parties, au regard des dispositions du Code du Travail, du Code Pénal, de la Convention Collective, du Règlement Intérieur de la SASU PLASTICA ou des Consignes de travail et d'exploitation de l'entreprise.
    </div>

    <div class="section">
        <p class="article-title">Article 7 : Attribution de juridiction</p>
        Pour toutes contestations relatives au présent contrat, les parties font attribution de juridiction au Tribunal du travail d'Abidjan.
        <br><br>
        @php
            $tabDateDebut = $travailleur->date_debut_contrat ? explode("-", $travailleur->date_debut_contrat) : ['', '', ''];
        @endphp
        Fait à Abidjan en 02 (deux) exemplaires originaux de 2 pages, le <span class="bold">{{ isset($tabDateDebut[2]) ? $tabDateDebut[2].'/'.$tabDateDebut[1].'/'.$tabDateDebut[0] : '' }}</span>
    </div>

    <table class="signatures">
        <tr>
            <td>
                <span class="bold">L'EMPLOYÉ(E)</span>
                <br>
                (Lu et approuvé)
                <br><br><br><br>
            </td>
            <td class="text-right">
                <span class="bold">L'EMPLOYEUR</span>
                <br><br><br><br>
            </td>
        </tr>
    </table>

</body>
</html>
