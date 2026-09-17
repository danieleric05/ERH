<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CONTRAT CDI - {{ $travailleur->nom }} {{ $travailleur->prenom }}</title>
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

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
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

        .page-break {
            page-break-before: always;
        }

        .obligations-list {
            margin-left: 20px;
        }
    </style>
</head>

<body>

    @php
    $tabDate = $travailleur->date_naissance ? explode("-", $travailleur->date_naissance) : ['0000', '00', '00'];
    $tabDateP = $travailleur->pieceidentite_livrele ? explode("-", $travailleur->pieceidentite_livrele) : ['0000', '00', '00'];
    $tabDateDebut = $travailleur->date_debut_contrat ? explode("-", $travailleur->date_debut_contrat) : ['0000', '00', '00'];

    $civilite = 'Monsieur';
    if(in_array($travailleur->civilite, ['Mlle', 'Mlle.', 'Mademoiselle'])) $civilite = 'Mademoiselle';
    elseif(in_array($travailleur->civilite, ['Mme', 'Mme.', 'Madame'])) $civilite = 'Madame';

    $nomComplet = strtoupper($travailleur->nom ?? '') . ' ' . strtoupper($travailleur->prenom ?? '') . ' ' . strtoupper($travailleur->prenom_suite ?? '');
    $fonction = $travailleur->fonction_entrepriseid ? (\App\Fonction::where('id', $travailleur->fonction_entrepriseid)->first()?->label ?? '') : '';
    $categorie = $travailleur->categorieid ? (\App\Categories::where('id', $travailleur->categorieid)->first()?->label ?? '2') : '2';
    $unite = $travailleur->uniteid ? (\App\Unites::where('id', $travailleur->uniteid)->first()?->label ?? '') : '';
    $commune = $travailleur->communeid ? (\App\Commune::where('id', $travailleur->communeid)->first()?->label ?? '') : '';
    $nationalite = $travailleur->nationaliteid ? (\App\Pays::where('id', $travailleur->nationaliteid)->value('nationalite') ?? 'IVOIRIENNE') : 'IVOIRIENNE';
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
                <span class="bold" style="font-size: 12px;">CONTRAT DE TRAVAIL À DURÉE INDÉTERMINÉE</span>
            </td>
            <td style="width: 25%;">
                Ref: ENR-RH-022<br>
                Version: 01<br>
                Du: 09/08/2021
            </td>
        </tr>
        <tr>
            <td>Gérer les ressources humaines</td>
            <td>Page: 1/4</td>
        </tr>
    </table>

    <div class="section">
        <span class="bold underline">Entre les soussignés,</span>
        <br><br>
        1) La Société dénommée &laquo; <span class="bold">PLASTICA CI</span> &raquo;, Société par Actions Simplifiée Unipersonnelle (SASU) au capital de deux milliards (2.000.000.000) FRANCS CFA, dont le siège social est fixé à ABIDJAN Zone Industrielle de KOUMASSI, 05 Boîte Postale 2160 Abidjan 05, immatriculée au Registre de Commerce et du Crédit Mobilier d'ABIDJAN sous le numéro CI-ABJ-1999-B-249382, déclarée à la CNPS sous le numéro <span class="bold">82408</span>.
        <br><br>
        Représentée par Monsieur <span class="bold">BADREDDINE Abbas</span>, Gérant, demeurant en son siège.
        <br><br>
        La SASU "<span class="bold">PLASTICA CI</span>" parfois dénommée dans le présent contrat et pour en faciliter sa rédaction "L'Employeur", ce terme s'entendant Monsieur BADREDDINE Abbas, ès qualité,
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
        <p class="article-title">Article 1er - TEXTES RÉGISSANT LE PRÉSENT CONTRAT</p>
        La SASU PLASTICA CI engage en qualité de <span class="bold">{{ $fonction }}</span> <span class="bold">{{ $civilite }} {{ $nomComplet }}</span> sous réserve des résultats de la visite médicale d'embauche et conformément aux :
        <br><br>
        a) dispositions de la loi N° 2015-532 du 20 juillet 2015 portant Code du Travail et des textes réglementaires pris pour son application.
        <br><br>
        b) dispositions de la Convention Collective Interprofessionnelle de Côte d'Ivoire en date du 20 Juillet 1977, ensemble ses avenants et annexes et décisions de commissions mixtes qui ont modifié et complété cette convention ou qui viendraient à la modifier ou à la compléter.
        <br><br>
        c) dispositions du Règlement Intérieur de PLASTICA CI dont le Travailleur reconnaît avoir pris connaissance.
        <br><br>
        d) Consignes techniques d'exploitation et de fabrication de PLASTICA CI, telles qu'elles lui seront inculquées par la Direction de PLASTICA CI et par ses supérieurs hiérarchiques.
    </div>

    <div class="section">
        <p class="article-title">Article 2 - NATURE DU CONTRAT</p>
        Le présent contrat est conclu pour une <span class="bold">durée indéterminée</span> à compter du <span class="bold">{{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</span>.
    </div>

    <div class="section">
        <p class="article-title">Article 3 - PÉRIODE D'ESSAI</p>
        Les trois (03) premiers mois du présent contrat sont considérés comme période d'essai, renouvelable une fois. Durant cette période, chacune des parties pourra rompre le contrat sans préavis ni indemnité, conformément aux dispositions du Code du Travail.
    </div>

    <div class="section">
        <p class="article-title">Article 4 - FONCTIONS</p>
        Le Travailleur est engagé en qualité de <span class="bold">{{ $fonction }}</span>, catégorie <span class="bold">{{ $categorie }}</span>.
        <br><br>
        Il exercera ses fonctions au sein de l'unité <span class="bold">{{ $unite }}</span> ou de tout autre service de l'entreprise où ses compétences seraient requises.
    </div>

    <div class="section">
        <p class="article-title">Article 5 - LIEU DE TRAVAIL</p>
        Le Travailleur exercera ses fonctions à ABIDJAN, Zone Industrielle de KOUMASSI, siège social de la SASU PLASTICA CI, ou en tout autre lieu où l'employeur pourrait être amené à exercer ses activités.
    </div>

    <div class="section">
        <p class="article-title">Article 6 - HORAIRES DE TRAVAIL</p>
        Le Travailleur exerce son activité en équipe tournante de 06H à 14H ou de 14H à 22H ou de 22H à 06H ou de 07H à 15H ou de 08H à 16H ou autres horaires variables, dans la limite de quarante (40) heures par semaine.
    </div>

    <div class="section">
        <p class="article-title">Article 7 - RÉMUNÉRATION</p>
        @php
            $primePhrase = $travailleur->prime_transport
                ? ', ainsi qu\'une prime mensuelle nette de transport de <span class="bold">' . number_format($travailleur->prime_transport, 0, ',', ' ') . ' F</span> CFA'
                : '';
        @endphp
        @if($travailleur->type_remuneration == 2)
            En contrepartie de son travail, le Travailleur percevra une rémunération mensuelle de base de <span class="bold">{{ number_format($travailleur->salaire_base ?? 0, 0, ',', ' ') }} F</span> CFA, à laquelle s'ajoute un sursalaire de <span class="bold">{{ number_format($travailleur->sursalaire ?? 0, 0, ',', ' ') }} F</span> CFA{!! $primePhrase !!}.
        @else
            En contrepartie de son travail, le Travailleur percevra une rémunération mensuelle nette de <span class="bold">{{ number_format($travailleur->salaire_base ?? 0, 0, ',', ' ') }} F</span> CFA{!! $primePhrase !!}.
        @endif
        <br><br>
        Le salaire sera payé mensuellement, par virement bancaire ou tout autre moyen de paiement légal.
    </div>

    <div class="section">
        <p class="article-title">Article 8 - CONGÉS PAYÉS</p>
        Le Travailleur bénéficiera d'un congé annuel payé conformément aux dispositions du Code du Travail et de la Convention Collective Interprofessionnelle.
    </div>

    <div class="section">
        <p class="article-title">Article 9 - OBLIGATIONS DU TRAVAILLEUR</p>
        Le Travailleur s'engage à :
        <div class="obligations-list">
            - Exécuter consciencieusement les tâches qui lui seront confiées ;<br>
            - Respecter les consignes de travail, de sécurité et d'hygiène ;<br>
            - Observer une discrétion absolue sur les affaires de l'entreprise ;<br>
            - Ne pas exercer d'activité concurrente pendant la durée du contrat ;<br>
            - Respecter le Règlement Intérieur de l'entreprise.
        </div>
    </div>

    <div class="section">
        <p class="article-title">Article 10 - RUPTURE DU CONTRAT</p>
        Le présent contrat pourra être rompu par l'une ou l'autre des parties moyennant le respect d'un préavis dont la durée est fixée par la Convention Collective en fonction de la catégorie professionnelle et de l'ancienneté du Travailleur.
        <br><br>
        Toutefois, il pourra être rompu sans préavis en cas de faute grave ou lourde de l'une des parties, conformément aux dispositions du Code du Travail.
    </div>

    <div class="section">
        <p class="article-title">Article 11 - CLAUSE DE NON-CONCURRENCE</p>
        Le Travailleur s'engage, pendant la durée du présent contrat et pendant une période de six (06) mois suivant sa cessation, à ne pas exercer directement ou indirectement une activité concurrente à celle de PLASTICA CI, dans un rayon géographique correspondant à la zone d'activité de l'entreprise.
    </div>

    <div class="section">
        <p class="article-title">Article 12 - AUTRES CONDITIONS ET MODALITÉS DE TRAVAIL</p>
        Toutes les autres conditions et modalités de l'engagement qui ne seraient pas prévues au présent contrat sont celles fixées par la Convention Collective Interprofessionnelle de Côte d'Ivoire et le Code du Travail, textes dont le travailleur déclare avoir également pris connaissance.
    </div>

    <div class="section">
        <p class="article-title">Article 13 - PROTECTION DES DONNÉES ET CONFIDENTIALITÉ DES INFORMATIONS</p>
        Au cours de l'exécution du présent contrat et après sa cessation pour quelque cause que ce soit, le salarié sera tenu à une discrétion absolue sur tous les faits, événements, documents ou renseignements portés à sa connaissance en raison de ses fonctions ou de son appartenance à la société PLASTICA CI SASU, et qui concerne tant sa gestion et son fonctionnement que sa situation et ses projets.
        <br><br>
        Cette clause constitue une clause essentielle du présent contrat et tout manquement à l'obligation de réserve et de confidentialité constitue une faute lourde entraînant la rupture immédiate du présent contrat sans préavis et sans préjudice d'éventuelles poursuites pénales.
    </div>

    <div class="section">
        <p class="article-title">Article 14 - DIFFÉRENDS</p>
        Tous différends qui pourraient s'élever à l'occasion de l'exécution du présent contrat seront réglés conformément aux dispositions des textes législatifs ou réglementaires en vigueur et à celles de la Convention Collective Interprofessionnelle de Côte d'Ivoire et des modifications éventuelles ultérieures de ces textes, et à défaut, aux usages locaux réglant les rapports des employeurs et salariés auxquels les parties déclarent formellement se soumettre.
        <br><br>
        Fait à Abidjan en deux (02) exemplaires originaux, le <span class="bold">{{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</span>
    </div>

    <table class="signatures">
        <tr>
            <td class="text-left">
                <span class="bold">LE TRAVAILLEUR</span><br>
                <i>(Lu et approuvé)</i><br><br><br><br>
                _______________________
            </td>
            <td class="text-right">
                <span class="bold">L'EMPLOYEUR</span><br>
                <i>Pour la SASU PLASTICA CI</i><br><br><br><br>
                _______________________
            </td>
        </tr>
    </table>

</body>

</html>