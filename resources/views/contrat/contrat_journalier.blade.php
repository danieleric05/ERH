<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CONTRAT-JOURNALIER - <?php echo $travailleur->nom ?></title>
    <style>
		@page {
			margin-top: 30mm;
			margin-left: 12mm;
			margin-right: 12mm;
		}
		.text-center {
		  text-align: center !important;
		}
		
		 .container {
		  width: 100%;
		  padding-right: 15px;
		  padding-left: 15px;
		  margin-right: auto;
		  margin-left: auto;
		}

				  .row {
		  display: -ms-flexbox;
		  display: flex;
		  -ms-flex-wrap: wrap;
		  flex-wrap: wrap;
		  margin-right: -15px;
		  margin-left: -15px;
		}

		.col-md-12 {
			-ms-flex: 0 0 100%;
			flex: 0 0 100%;
			max-width: 100%;
		  }
		  
		  .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
		.col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
		.col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
		.col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
		.col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
		.col-xl-auto {
		  position: relative;
		  width: 100%;
		  min-height: 1px;
		  padding-right: 15px;
		  padding-left: 15px;
		}

		.text-left {
		  text-align: left !important;
		}

		.text-right {
		  text-align: right !important;
		}
			
        body{
            font-family: Tahoma, Helvetica, Arial;
            font-size: 13px;
            color: black;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="row">

        <div class="col-md-12">
            

            <div>
                <img src="{{ base_path('../rhassets/images/contrat.PNG') }}" style="width: 100%;">
            </div>

            <p>
                <br/>
                <br/>

                <b><u style="color: black">Entre les soussignés</u>,</b>
                        <br/>
                1) La Société dénommée « PLASTICA CI », Société par Actions Simplifiée Unipersonnelle (SASU) au capital social de deux milliards (2.000.000.000) FRANCS CFA,
				dont le siège social est fixé à ABIDJAN Zone Industrielle de KOUMASSI,
                05 Boîte Postale  2160 Abidjan 05, immatriculée au Registre de Commerce et du Crédit Mobilier d’ABIDJAN
                sous le numéro CI-ABJ-1999-B-249382, déclarée à la CNPS sous le numéro 82408.
                <br/>
                <br/>
                Prise en la personne de son représentant légal, demeurant es-qualité audit siège,
                <br/>
                <br/>
                La <b style="font-size: 15px"> "PLASTICA CI" </b> parfois dénommée dans le présent contrat "L’Employeur",
                <br/>
                <div style="color: black; font-weight: bold" class="text-right"><u style="color: black">D’UNE PART</u></div>
                <br/>
                ET
                <br/>
                <br/>
                <?php $tabDate = explode("-", $travailleur->date_naissance); ?>
                <?php $tabDateP = explode("-", $travailleur->pieceidentite_livrele); ?>
                2) <b> @if($travailleur->civilite == 'Monsieur') M. @endif @if($travailleur->civilite == 'Mademoiselle') Mlle. @endif @if($travailleur->civilite == 'Madame') Mme. @endif  <?= $travailleur->nom.' '.$travailleur->prenom.''.$travailleur->prenom_suite ?></b>  demeurant à  <b><?php echo $comm = \App\Commune::where('id', $travailleur->communeid)->first()->label; ?></b> <br/>
                Situation matrimoniale     …<b>{{ ucfirst(strtolower($travailleur->situation_mat)) }}</b>…    Nom du (de la) conjoint (e)…………. <br/>
                @if($travailleur->nationaliteid)
                    De nationalité    <b>IVOIRIENNE</b> <br/>
                @endif
                @if(!$travailleur->nationaliteid)
                    De nationalité     <br/>
                @endif
                Né (e) le   <b><?=$tabDate['2']?>/<?=$tabDate['1']?>/<?=$tabDate['0']?>  à  <?= strtoupper($travailleur->lieu_naissance) ?> </b><br/>

                Pièce d’identité : (ATT/CNI, N°

                @if($travailleur->pieceidentite)
                    <?= $travailleur->pieceidentite ?>
                @endif
                @if(!$travailleur->pieceidentite)

                @endif

                    délivré(e) le
                @if($travailleur->pieceidentite != null)
                    <b> <?=$tabDateP['2']?>/<?=$tabDateP['1']?>/<?=$tabDateP['0']?> </b>
                @endif
                    à
                @if($travailleur->pieceidentite)
                            <?= $travailleur->pieceidentite_lieu ?>
                @endif
                            @if(!$travailleur->pieceidentite)

                            @endif

                        </b> par ONI <br/>

            Catégorie Professionnelle : @if($travailleur->categorieid)<b>{{ $catego = \App\Categories::where('id', $travailleur->categorieid)->first()->label }}</b> @endif<br/>

            Matricule : <b> <?= strtoupper($travailleur->matricule) ?> </b> <br/>
                N°CNPS : <?= $travailleur->numero_securite ?> <br/>

                <br/>
                <br/>

                <b> @if($travailleur->civilite == 'Monsieur') M. @endif @if($travailleur->civilite == 'Mademoiselle') Mlle. @endif @if($travailleur->civilite == 'Madame') Mme. @endif <?= strtoupper($travailleur->nom).' '.strtoupper($travailleur->prenom).''.strtoupper($travailleur->prenom_suite) ?></b>  dénommé(e) dans le présent contrat le "Travailleur occasionnel" ou « le Travailleur Journalier»,
                <br/>
                <br/>
                <span class="text-left">D’AUTRE PART</span>
                <br/>
            <b style="color: black; font-weight: bold"><u style="color: black">Article 1</u> : Textes régissant le présent contrat :</b>
                <br/>
                a) Dispositions de la loi N° 2015 – 532 du 20 juillet 2015 portant Code du Travail et des textes réglementaires pris pour son application <br/>
                b) Dispositions de la Convention Collective Interprofessionnelle de la Côte d’Ivoire en date du 20 Juillet 1977, ensemble les avenants,
                annexes et décisions de commissions mixtes qui ont modifié et complété cette convention ou qui viendraient à la
                modifier ou à la compléter<br/><br/>
                c) Dispositions du Règlement Intérieur de la PLASTICA CI dont le Travailleur reconnaît avoir pris connaissance<br/><br/>
                d) Consignes techniques d’exploitation et de fabrication de la PLASTICA CI, telles qu’elles lui seront inculquées par la
                Direction de PLASTICA CI et par ses supérieurs hiérarchiques.<br/><br/>
            <b style="color: black; font-weight: bold"><u style="color: black">Article 2</u> : De l’activité du Travailleur</b>
                <br/><br/>
                Fonction : <b>{{ $fonction = \App\Fonction::where('id', $travailleur->fonction_entrepriseid )->first()->label }}</b><br/>
                Unité de rattachement : <b>{{ $unite = \App\Equipes::where('id', $travailleur->equipeid )->first()->label }}</b><br/>
                (Avant éventuelles affectations pour nécessités de service)
                <br/>
                <br/>
             <div style="page-break-before: always;"></div>
             <br/>
             <b><u>Article 3</u> : Durée du contrat et horaires de travail</b>
                <br/>
                <u>Durée</u> : Le présent contrat est à durée journalière et peut, sauf dénonciation par l’une ou l’autre des parties, être renouvelé par tacite reconduction, en respect des dispositions du Code du Travail et de la Convention Collective.

                <br/><br/>

                <u>Horaires de travail</u> : Le Travailleur exerce son activité en équipe tournante de 06H à 14H
                ou de 14H à 22H ou de 22H à 06H ou de 07H à 15H ou de 08H à 16H ou autres horaires variables, dans la limite des huit (8) heures par jour.
                <br/><br/>

                <b><u>Article 5</u> : Rémunération</b>
            <br/>
            <br/>
                Le salaire ci-dessous détaillé est la rémunération des huit (08) heures de travail journalier :
            <br/>
            <br/>
                 <div>
                <i>
                    <table style="width: auto; margin: 0; border-collapse: collapse;">
                        <tr><td colspan="3" style="text-align: left; padding-top: 4px; padding-bottom: 3px; font-weight: bold;">1/ Rémunération Brute imposable</td></tr>
                        <tr>
                            <td style="text-align: left; padding-left: 40px; white-space: nowrap;">Salaire journalier (SMIG de 75 000F) : 433 X 8 heures</td>
                            <td style="text-align: center; width: 15px;">=</td>
                            <td style="text-align: right; white-space: nowrap; width: 70px;">3 464 F</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding-left: 40px; white-space: nowrap;">Gratification journalière : 27 X 8 heures</td>
                            <td style="text-align: center; width: 15px;">=</td>
                            <td style="text-align: right; white-space: nowrap; width: 70px;">216 F</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding-left: 40px; white-space: nowrap;">Congé journalier : 38 X 8heures</td>
                            <td style="text-align: center; width: 15px;">=</td>
                            <td style="text-align: right; white-space: nowrap; width: 70px; border-bottom: 1px solid black;">304 F</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding-left: 40px; font-weight: bold; white-space: nowrap;">Sous total 1</td>
                            <td style="text-align: center; width: 15px; font-weight: bold;">=</td>
                            <td style="text-align: right; white-space: nowrap; width: 70px; font-weight: bold;">3 984 F</td>
                        </tr>
                        <tr><td colspan="3" style="text-align: left; padding-top: 10px; padding-bottom: 3px; font-weight: bold;">2/ Rémunération Non Imposable</td></tr>
                        <tr>
                            <td style="text-align: left; padding-left: 40px; white-space: nowrap;">Transport journalier :</td>
                            <td style="text-align: center; width: 15px;">=</td>
                            <td style="text-align: right; white-space: nowrap; width: 70px;">1 154 F</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding-left: 40px; white-space: nowrap;">Précarité (salaire + gratification) : 3 680 X 3%</td>
                            <td style="text-align: center; width: 15px;">=</td>
                            <td style="text-align: right; white-space: nowrap; width: 70px; border-bottom: 1px solid black;">110 F</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; padding-left: 40px; font-weight: bold; white-space: nowrap;">Sous total 2</td>
                            <td style="text-align: center; width: 15px; font-weight: bold;">=</td>
                            <td style="text-align: right; white-space: nowrap; width: 70px; font-weight: bold;">1 264 F</td>
                        </tr>
                    </table>
                    </i>
                </div>
                <br/><br/>
                Le Travailleur qui n’aura pas accompli ses huit heures de travail journalier ne sera rémunéré qu’au prorata du nombre d’heures effectivement ouvrées, le relevé de la badgeuse faisant foi.
                <br/><br/>
            <b style="color: black; font-weight: bold"><u>Article 6</u> :   Rupture du contrat</b>
                <br/><br/>
                Le présent contrat peut être résilié à la fin de chaque journée par l’une ou l’autre des parties, ou d’un commun accord, ou encore en cas de force majeure, de faute grave ou de faute lourde commise par  l'une des parties, au regard des dispositions du Code du Travail,
            du Code Pénal, de la Convention Collective, du Règlement Intérieur de la <span style="font-size: 16px"> PLASTICA CI</span> ou des Consignes de travail et d’exploitation de l’entreprise.
                <br/><br/>
            <b style="color: black; font-weight: bold"><u>Article 7</u>   Attribution de juridiction</b>
                <br/>
                Pour toutes contestations relatives au présent contrat, les parties font attribution de juridiction au Tribunal du travail d’Abidjan.
                <br/><br/>
                <?php $tabDateDebut = explode("-", $travailleur->date_debut_contrat); ?>
                Fait à Abidjan en 02 (deux) exemplaires originaux de 2 pages, le <?=$tabDateDebut['2']?>/<?=$tabDateDebut['1']?>/<?=$tabDateDebut['0']?>
                <br/>
                <br/>

                <b style="color: black; font-weight: bold">L’EMPLOYE(E)</b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b style="color: black; font-weight: bold">L’EMPLOYEUR</b> <br/>
            <b>(Lu et approuvé)</b>

            </p>
			
        </div>

    </div>

</div>
</body>
</html>
