<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CONTRAT CDD - {{ $travailleur->nom }} {{ $travailleur->prenom }}</title>
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
    $tabDateFin = $travailleur->date_fin_contrat ? explode("-", $travailleur->date_fin_contrat) : ['0000', '00', '00'];

    $civilite = 'Monsieur';
    if(in_array($travailleur->civilite, ['Mlle', 'Mlle.', 'Mademoiselle'])) $civilite = 'Mademoiselle';
    elseif(in_array($travailleur->civilite, ['Mme', 'Mme.', 'Madame'])) $civilite = 'Madame';

    $nomComplet = trim(strtoupper($travailleur->nom ?? '') . ' ' . strtoupper($travailleur->prenoms_complets));
    $fonction = $travailleur->fonction_entrepriseid ? (\App\Fonction::where('id', $travailleur->fonction_entrepriseid)->first()?->label ?? '') : '';
    $categorie = $travailleur->categorieid ? (\App\Categories::where('id', $travailleur->categorieid)->first()?->label ?? '2') : '2';
    $commune = $travailleur->communeid ? (\App\Commune::where('id', $travailleur->communeid)->first()?->label ?? '') : '';
    $nationalite = $travailleur->nationaliteid ? (\App\Pays::where('id', $travailleur->nationaliteid)->value('nationalite') ?? 'IVOIRIENNE') : 'IVOIRIENNE';

    $primePhrase = $travailleur->prime_transport
        ? ' Une prime mensuelle nette de transport de <span class="bold">' . number_format($travailleur->prime_transport, 0, ',', ' ') . ' F</span> CFA.'
        : '';

    // Durée du CDD en mois, écrite en toutes lettres (Code du Travail exige une durée déterminée précise)
    $moisEnLettres = [1=>'Un',2=>'Deux',3=>'Trois',4=>'Quatre',5=>'Cinq',6=>'Six',7=>'Sept',8=>'Huit',9=>'Neuf',10=>'Dix',11=>'Onze',12=>'Douze',13=>'Treize',14=>'Quatorze',15=>'Quinze',16=>'Seize',17=>'Dix-sept',18=>'Dix-huit',19=>'Dix-neuf',20=>'Vingt',21=>'Vingt-et-un',22=>'Vingt-deux',23=>'Vingt-trois',24=>'Vingt-quatre',25=>'Vingt-cinq',26=>'Vingt-six',27=>'Vingt-sept',28=>'Vingt-huit',29=>'Vingt-neuf',30=>'Trente',31=>'Trente-et-un',32=>'Trente-deux',33=>'Trente-trois',34=>'Trente-quatre',35=>'Trente-cinq',36=>'Trente-six'];
    $dureeMois = 0;
    if ($travailleur->date_debut_contrat && $travailleur->date_fin_contrat) {
        try {
            $dureeMois = (int) round(\Carbon\Carbon::parse($travailleur->date_debut_contrat)->diffInMonths(\Carbon\Carbon::parse($travailleur->date_fin_contrat)));
        } catch (\Throwable $e) {
            $dureeMois = 0;
        }
    }
    $dureeMoisLettres = $moisEnLettres[$dureeMois] ?? null;
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
                <span class="bold" style="font-size: 12px;">CONTRAT DE TRAVAIL À DURÉE DÉTERMINÉE À TERME PRÉCIS</span>
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
        1) La Société dénommée &laquo; <span class="bold">PLASTICA CI</span> &raquo;, Société par Actions Simplifiée Unipersonnelle (SASU) au capital de deux milliards (2 000.000.000) FRANCS CFA, dont le siège social est fixé à ABIDJAN Zone Industrielle de KOUMASSI, 05 Boîte Postale 2160 Abidjan 05, immatriculée au Registre de Commerce et du Crédit Mobilier d'ABIDJAN sous le numéro CI-ABJ-1999-B-249382, déclarée à la CNPS sous le numéro <span class="bold">82408</span>.
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
        a) dispositions de la loi N° 2015 – 532 du 20 juillet 2015 portant Code du Travail et des textes réglementaires pris pour son application.
        <br><br>
        b) dispositions de la Convention Collective Interprofessionnelle de Côte d'Ivoire en date du 20 Juillet 1977, ensemble les avenants et décisions de commissions mixtes qui ont modifié et complété cette convention ou qui viendraient à la modifier ou à la compléter.
        <br><br>
        c) dispositions du Règlement Intérieur de la SASU PLASTICA CI dont le Travailleur déclare reconnaître avoir pris connaissance.
        <br><br>
        d) Consignes techniques d'exploitation et de fabrication de la SASU PLASTICA CI, telles qu'elles lui seront inculquées par la Direction de PLASTICA CI et par ses supérieurs hiérarchiques.
    </div>

    <div class="section">
        <p class="article-title">Article 2 - DURÉE DU CONTRAT ET PÉRIODE D'ESSAI</p>
        Le présent contrat de Travail est à <span class="bold">DURÉE DÉTERMINÉE</span> de
        @if($dureeMoisLettres)
            <span class="bold">{{ $dureeMois }} ({{ $dureeMoisLettres }}) mois</span>
        @else
            <span class="bold">{{ $dureeMois }} mois</span>
        @endif
        qui commenceront à courir à partir du <span class="bold">{{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</span> pour se terminer le <span class="bold">{{ $tabDateFin[2] }}/{{ $tabDateFin[1] }}/{{ $tabDateFin[0] }}</span>.
    </div>

    <div class="section">
        <p class="article-title">Article 3 - CLASSEMENT CATÉGORIEL</p>
        Le salarié est classé en Catégorie <span class="bold">{{ $categorie }}</span> des Ouvriers et Employés de la Convention Collective Interprofessionnelle de Côte d'Ivoire en date du 20 Juillet 1977.
    </div>

    <div class="section">
        <p class="article-title">Article 4 - RÉMUNÉRATION MENSUELLE</p>
        @if($travailleur->type_remuneration == 2)
            La rémunération mensuelle de base de <span class="bold">{{ $civilite }} {{ $nomComplet }}</span> est fixée à <span class="bold">{{ number_format($travailleur->salaire_base ?? 0, 0, ',', ' ') }} F</span> CFA.
            <br>
            Un sursalaire de <span class="bold">{{ number_format($travailleur->sursalaire ?? 0, 0, ',', ' ') }} F</span> CFA.
        @else
            La rémunération mensuelle nette de <span class="bold">{{ $civilite }} {{ $nomComplet }}</span> est fixée à <span class="bold">{{ number_format($travailleur->salaire_base ?? 0, 0, ',', ' ') }} F</span> CFA.
        @endif
        {!! $primePhrase !!}
    </div>

    <div class="section">
        <p class="article-title">Article 5 - HORAIRES DE TRAVAIL</p>
        Le salarié s'engage par les présentes à respecter les horaires de travail de l'employeur comme ci-dessous définis :
        <br>
        Les jours de travail : Lundi au Samedi ;
        <br>
        Durée maximale légale de la journée de travail : 08 heures par jour (article 21-1 du Code du Travail).
        <br><br>
        Toutefois, l'Employeur peut à tout moment demander au travailleur d'effectuer des heures supplémentaires pour nécessité de service.
    </div>

    <div class="section">
        <p class="article-title">Article 6 - EXERCICE DES FONCTIONS DU SALARIE</p>
        Le salarié exercera ses fonctions dans le respect du présent contrat de travail, du règlement intérieur, des consignes et ordres de services qui lui seront donnés par l'employeur.
        <br><br>
        Le salarié exercera son travail sur le site du siège social de la société PLASTICA CI. Il peut cependant avoir à exercer sur tout autre site auquel l'employeur pourra l'affecter pour des nécessités de service dont l'employeur est seul Juge.
    </div>

    <div class="section">
        <p class="article-title">Article 7 - OBLIGATIONS PROFESSIONNELLES DU SALARIE</p>
        Le salarié s'engage expressément à exécuter toutes les obligations qui lui sont prescrites par son employeur dans le cadre de ses fonctions :
        <div class="obligations-list">
            * Dans les limites de son contrat, le travailleur doit toute son activité professionnelle à la SASU PLASTICA CI. Il doit notamment fournir le travail pour lequel il a été embauché, l'exécuter lui-même et avec soin (Article 16.3 Code du Travail) et réaliser les objectifs professionnels qui lui seront fixés par l'employeur.<br>
            * Respecter et appliquer consciencieusement toutes les instructions de travail, tous les plans de travail, les missions et consignes particulières de travail, qui lui seront données verbalement ou par écrit, par l'employeur.<br>
            * Toujours avoir au service de la SASU PLASTICA CI, un comportement exemplaire et honorable.<br>
            * Se conformer strictement aux notes de service et au Règlement Intérieur de la SASU PLASTICA CI.<br>
            * Observer une absolue obligation de confidentialité en s'interdisant la divulgation de toute information, procédure ou procédé de fabrication ou de commercialisation qui serait de nature à servir ou favoriser des intérêts concurrentiels à la SASU PLASTICA CI, et s'interdire, en dehors de son temps de travail à PLASTICA CI, d'exercer toute activité à caractère professionnel susceptible de concurrencer son employeur PLASTICA CI ou de nuire à la bonne exécution des services contractuellement convenus avec la SASU PLASTICA CI.<br>
            * Se soumettre une fois par an, à la visite médicale systématique prévue par le Code du Travail.
        </div>
        <br>
        Tout manquement à une ou l'autre de ses obligations professionnelles par l'Employé(e) au cours du présent contrat de travail, constituera une faute dont la gravité, à l'appréciation de l'employeur, peut justifier le licenciement immédiat du salarié.
    </div>

    <div class="section">
        <p class="article-title">Article 8 - RUPTURE ANTICIPÉE</p>
        À l'appréciation de l'employeur, toute faute lourde du salarié peut justifier la rupture anticipée du présent contrat de travail par l'employeur, sans préavis ni indemnité d'aucune sorte.
        <br><br>
        Au demeurant, chacune des parties pourra, en cours d'exécution du présent contrat, prendre l'initiative de sa rupture anticipée sous réserve d'observer et notifier à l'autre partie, le préavis applicable à la catégorie professionnelle dont relève l'employé.
    </div>

    <div class="section">
        <p class="article-title">Article 9 - CONGÉS PAYÉS</p>
        Le travailleur aura droit à un congé annuel de 2,2 jours ouvrables par mois de travail, le droit de jouissance aux congés étant acquis après une durée de service effectif égale à un (01) an, soit douze (12) mois et conformément à la réglementation du travail en Côte d'Ivoire.
    </div>

    <div class="section">
        <p class="article-title">Article 10 - ACCIDENT DU TRAVAIL ET MALADIES PROFESSIONNELLES</p>
        En cas d'accident survenu dans le travail ou de maladie professionnelle, les droits et obligations de chacune des parties seront réglés conformément aux textes qui régissent la matière. Il en est de même pour les maladies non professionnelles occasionnant l'absence du travailleur à son poste.
    </div>

    <div class="section">
        <p class="article-title">Article 11 - AUTRES CONDITIONS ET MODALITÉS DE TRAVAIL</p>
        Toutes les autres conditions et modalités de l'engagement qui ne seraient pas prévues au présent contrat sont celles fixées par la Convention Collective Interprofessionnelle de Côte d'Ivoire et le Code du Travail, textes dont le travailleur déclare avoir également pris connaissance.
    </div>

    <div class="section">
        <p class="article-title">Article 12 - PROTECTION DES DONNÉES ET CONFIDENTIALITÉ DES INFORMATIONS</p>
        Au cours de l'exécution du présent contrat et après sa cessation pour quelque cause que ce soit, le salarié sera tenu à une discrétion absolue sur tous les faits, événements, documents ou renseignements portés à sa connaissance en raison de ses fonctions ou de son appartenance à la société PLASTICA CI, et qui concerne tant sa gestion et son fonctionnement que sa situation et ses projets.
        <br><br>
        Cette clause constitue une clause essentielle du présent contrat et tout manquement à l'obligation de réserve et de confidentialité constitue une faute lourde entraînant la rupture immédiate du présent contrat sans préavis et sans préjudice d'éventuelles poursuites pénales.
    </div>

    <div class="section">
        <p class="article-title">Article 13 - DIFFÉRENDS</p>
        Tous différends qui pourraient s'élever à l'occasion de l'exécution du présent contrat seront réglés conformément aux dispositions des textes législatifs ou réglementaires en vigueur et à celles de la Convention Collective Interprofessionnelle de Côte d'Ivoire et des modifications éventuelles ultérieures de ces textes, et à défaut, aux usages locaux réglant les rapports des employeurs et salariés auxquels les parties déclarent formellement se soumettre.
    </div>

    <div class="section">
        Fait à Abidjan, le <span class="bold">{{ $tabDateDebut[2] }}/{{ $tabDateDebut[1] }}/{{ $tabDateDebut[0] }}</span> en deux (02) exemplaires originaux de quatre (04) pages.
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
                <span class="bold">Abbas BADREDDINE</span><br>
                <i>(Le P.D.G)</i><br><br><br><br>
                _______________________
            </td>
        </tr>
    </table>

</body>

</html>
