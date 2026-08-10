<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>CONTRAT-JOURNALIER - <?php echo $travailleur->nom ?></title>
    <style>
		.text-center {
		  text-align: center !important;
		}
		
		.container {
			min-width: 992px !important;
		  }
		  
		 .container {
		  width: 100%;
		  padding-right: 15px;
		  padding-left: 15px;
		  margin-right: auto;
		  margin-left: auto;
		}

		@media (min-width: 576px) {
		  .container {
			max-width: 540px;
		  }
		}

		@media (min-width: 768px) {
		  .container {
			max-width: 720px;
		  }
		}

		@media (min-width: 992px) {
		  .container {
			max-width: 960px;
		  }
		}

		@media (min-width: 1200px) {
		  .container {
			max-width: 1140px;
		  }
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
            

            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <div class="text-center">
                <img src="{{ asset('rhassets/images/contrat.PNG') }}">
            </div>

            <p>
                <br/>
                <br/>

                <b><u style="color: black">Entre les soussign&eacute;s</u>,</b>
                        <br/>
                1) La Soci&eacute;t&eacute; d&eacute;nomm&eacute;e &laquo; PLASTICA CI &raquo;, Soci&eacute;t&eacute; par Actions Simplifi&eacute;e Unipersonnelle (SASU) au capital social de deux milliards (2.000.000.000) FRANCS CFA,
				dont le si&egrave;ge social est fix&eacute; &agrave; ABIDJAN Zone Industrielle de KOUMASSI,
                05 BoÃ®te Postale  2160 Abidjan 05, immatricul&eacute;e au Registre de Commerce et du Cr&eacute;dit Mobilier d&apos;ABIDJAN
                sous le num&eacute;ro CI-ABJ-1999-B-249382, d&eacute;clar&eacute;e &agrave; la CNPS sous le num&eacute;ro 82408.
                <br/>
                <br/>
                Prise en la personne de son repr&eacute;sentant l&eacute;gal, demeurant es-qualit&eacute; audit si&egrave;ge,
                <br/>
                <br/>
                La <b style="font-size: 15px"> "PLASTICA CI" </b> parfois d&eacute;nomm&eacute;e dans le pr&eacute;sent contrat "L&apos;Employeur",
                <br/>
                <div style="color: black; font-weight: bold" class="text-right"><u style="color: black">D&apos;UNE PART</u></div>
                <br/>
                ET
                <br/>
                <br/>
                <?php $tabDate = explode("-", $travailleur->date_naissance); ?>
                <?php $tabDateP = explode("-", $travailleur->pieceidentite_livrele); ?>
                2) <b> @if($travailleur->civilite ==  'Monsieur') M. @endif @if($travailleur->civilite == 'Mademoiselle') Mlle. @endif @if($travailleur->civilite == 'Madame') Mme. @endif  <?= $travailleur->nom.' '.$travailleur->prenom.''.$travailleur->prenom_suite ?></b>  demeurant &agrave;  <b><?php echo $comm = optional(\App\Commune::where('id', $travailleur->communeid)->first())->label; ?></b> <br/>
                 Situation matrimoniale     &hellip;<b>{{ ucfirst(strtolower($travailleur->situation_mat)) }}</b>&hellip;    Nom du (de la) conjoint (e)&hellip;&hellip;. <br/>
                @if($travailleur->nationaliteid)
                    De nationalit&eacute;    <b>{{ \App\Pays::where('id', $travailleur->nationaliteid)->value('nationalite') ?? \App\Pays::where('id', $travailleur->nationaliteid)->value('label') }}</b> <br/>
                @endif
                @if(!$travailleur->nationaliteid)
                    De nationalit&eacute;     <br/>
                @endif
                 N&eacute; (e) le   <b><?=$tabDate['2']?>/<?=$tabDate['1']?>/<?=$tabDate['0']?>  &agrave;  <?= strtoupper($travailleur->lieu_naissance) ?> </b><br/>
                Pi&egrave;ce d&apos;identit&eacute; : (ATT/CNI, N&deg;

                @if($travailleur->pieceidentite)
                    <?= $travailleur->pieceidentite ?>
                @endif
                @if(!$travailleur->pieceidentite)

                @endif

                    d&eacute;livr&eacute;(e) le
                @if($travailleur->pieceidentite != null)
                    <b> <?=$tabDateP['2']?>/<?=$tabDateP['1']?>/<?=$tabDateP['0']?> </b>
                @endif
                    &agrave;
                @if($travailleur->pieceidentite)
                            <?= $travailleur->pieceidentite_lieu ?>
                @endif
                            @if(!$travailleur->pieceidentite)

                            @endif

                        </b> par ONI <br/>

            Cat&eacute;gorie Professionnelle : @if($travailleur->categorieid)<b>{{ $catego = optional(\App\Categories::where('id', $travailleur->categorieid)->first())->label }}</b> @endif<br/>

            Matricule : <b> <?= strtoupper($travailleur->matricule) ?> </b> <br/>
                N&deg;CNPS : <?= $travailleur->numero_securite ?> <br/>

                <br/>
                <br/>

                <b> @if($travailleur->civilite == 'Monsieur') M. @endif @if($travailleur->civilite == 'Mademoiselle') Mlle. @endif @if($travailleur->civilite == 'Madame') Mme. @endif <?= strtoupper($travailleur->nom).' '.strtoupper($travailleur->prenom).''.strtoupper($travailleur->prenom_suite) ?></b>  d&eacute;nomm&eacute;(e) dans le pr&eacute;sent contrat le "Travailleur occasionnel" ou &laquo; le Travailleur Journalier&raquo;,
                <br/>
                <br/>
                <span class="text-left">D&apos;AUTRE PART</span>
                <br/>
            <b style="color: black; font-weight: bold"><u style="color: black">Article 1</u> : Textes r&eacute;gissant le pr&eacute;sent contrat :</b>
                <br/>
                a) Dispositions de la loi N&deg; 2015-532 du 20 juillet 2015 portant Code du Travail et des textes r&eacute;glementaires pris pour son application <br/>
                b) Dispositions de la Convention Collective Interprofessionnelle de la C&ocirc;te d&apos;Ivoire en date du 20 Juillet 1977, ensemble les avenants,
                annexes et d&eacute;cisions de commissions mixtes qui ont modifi&eacute; et compl&eacute;t&eacute; cette convention ou qui viendraient &agrave; la
                modifier ou &agrave; la compl&eacute;ter<br/><br/>
                c) Dispositions du R&egrave;glement Int&eacute;rieur de la PLASTICA CI dont le Travailleur reconnaÃ®t avoir pris connaissance<br/><br/>
                d) Consignes techniques d&apos;exploitation et de fabrication de la SASU PLASTICA, telles qu&apos;elles lui seront inculqu&eacute;es par la
                Direction de PLASTICA CI et par ses sup&eacute;rieurs hi&eacute;rarchiques.<br/><br/>
            <b style="color: black; font-weight: bold"><u style="color: black">Article 2</u> : De l&apos;activit&eacute; du Travailleur</b>
                <br/><br/>
                Fonction : <b>{{ $fonction = optional(\App\Fonction::where('id', $travailleur->fonction_entrepriseid )->first())->label }}</b><br/>
                Unit&eacute; de rattachement : <b>{{ $unite = optional(\App\Equipes::where('id', $travailleur->equipeid )->first())->label }}</b><br/><br/>
                (Avant &eacute;ventuelles affectations pour n&eacute;cessit&eacute;s de service)
                <br/>
                <br/>
             <b><u>Article 3</u> : Dur&eacute;e du contrat et horaires de travail</b>
                <br/><br/>
                <u>Dur&eacute;e</u> : Le pr&eacute;sent contrat est &agrave; dur&eacute;e journali&egrave;re et peut, sauf d&eacute;nonciation par l&apos;une ou l&apos;autre des parties, Ãªtre renouvel&eacute; par tacite reconduction, en respect des dispositions du Code du Travail et de la Convention Collective.

                <br/><br/>

                <u>Horaires de travail</u> : Le Travailleur exerce son activit&eacute; en &eacute;quipe tournante de 06H &agrave; 14H
                ou de 14H &agrave; 22H ou de 22H &agrave; 06H ou de 07H &agrave; 15H ou de 08H &agrave; 16H ou autres horaires variables, dans la limite des huit (8) heures par jour.
                <br/><br/>

                <b><u>Article 5</u> : R&eacute;mun&eacute;ration</b>
            <br/>
            <br/>
                Le salaire ci-dessous d&eacute;taill&eacute; est la r&eacute;mun&eacute;ration des huit (08) heures de travail journalier :
            <br/>
            <br/>
                 <div class="text-left">
              <i>
              <p style="margin:0cm;"><b>&nbsp; &nbsp; 1/ R&eacute;mun&eacute;ration Brute imposable</b></p><br/>
              <p style="margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:86.25pt;font-size:13px;font-family:'Times New Roman',serif;font-weight:bold;text-align:justify;background:white;"><em><span style="font-family:'Calibri',sans-serif;color:black;font-weight:normal;">Salaire journalier (SMIG de 75 000F) : 433 X 8 heures &nbsp; = &nbsp; &nbsp; &nbsp;3 464 F</span></em></p>
              <p style="margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:86.25pt;font-size:13px;font-family:'Times New Roman',serif;font-weight:bold;text-align:justify;background:white;"><em><span style="font-family:'Calibri',sans-serif;color:black;font-weight:normal;">Gratification journali&egrave;re : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 27 X 8 heures = &nbsp; &nbsp; &nbsp; &nbsp;216 F</span></em></p>
              <p style="margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:86.25pt;font-size:13px;font-family:'Times New Roman',serif;font-weight:bold;text-align:justify;background:white;"><em><span style="font-family:'Calibri',sans-serif;color:black;font-weight:normal;">Cong&eacute; journalier : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;  38 X 8heures &nbsp; <u>=&nbsp; &nbsp;  &nbsp; 304 F</u></span></em></p>
              <p style="margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:50.25pt;line-height:105%;font-size:15px;text-align:justify;background:white;"><em><span style="font-size:13px;line-height:105%;color:black;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span></em><em><span style="font-size:13px;line-height:105%;color:black;">Sous total 1&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; = &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;3 984 F</span></em></p>
              <p style="margin:0cm;"><b>&nbsp; &nbsp; <strong>2/ R&eacute;mun&eacute;ration Non Imposable</b></p><br/>
              <p style="margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:86.25pt;font-size:13px;font-family:'Times New Roman',serif;font-weight:bold;text-align:justify;background:white;"><em><span style="font-family:'Calibri',sans-serif;color:black;font-weight:normal;">Transport journalier : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 1 154 F</span></em></p>
              <p style="margin-top:0cm;margin-right:0cm;margin-bottom:0cm;margin-left:86.25pt;font-size:13px;font-family:'Times New Roman',serif;font-weight:bold;text-align:justify;background:white;"><em><span style="font-family:'Calibri',sans-serif;color:black;font-weight:normal;">Pr&eacute;carit&eacute; (salaire + gratification) : &nbsp; &nbsp; &nbsp;3 680 &times; 3% &nbsp; &nbsp; &nbsp;= &nbsp; &nbsp; &nbsp; &nbsp;<u>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;110 F &nbsp; &nbsp; &nbsp;&nbsp;</u></span></em></p>
              <p style="margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:50.25pt;line-height:105%;font-size:15px;text-align:justify;background:white;"><em><span style="font-size:13px;line-height:105%;;color:black;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span></em><em><span style="font-size:13px;line-height:105%;color:black;">Sous total 2 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;=&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; 1 264 F</span></em></p>
                       </i>
            </div>
                <br/><br/>
                Le Travailleur qui n&apos;aura pas accompli ses huit heures de travail journalier ne sera r&eacute;mun&eacute;r&eacute; qu&apos;au prorata du nombre d&apos;heures effectivement ouvr&eacute;es, le relev&eacute; de la badgeuse faisant foi.
                <br/><br/>
            <b style="color: black; font-weight: bold"><u>Article 6</u> :   Rupture du contrat</b>
                <br/><br/>
                Le pr&eacute;sent contrat peut Ãªtre r&eacute;sili&eacute; &agrave; la fin de chaque journ&eacute;e par l&apos;une ou l&apos;autre des parties, ou d&apos;un commun accord, ou encore en cas de force majeure, de faute grave ou de faute lourde commise par  l&lsquo;une des parties, au regard des dispositions du Code du Travail,
            du Code P&eacute;nal, de la Convention Collective, du R&egrave;glement Int&eacute;rieur de la <span style="font-size: 16px"> PLASTICA CI</span> ou des Consignes de travail et d&apos;exploitation de l&apos;entreprise.
                <br/><br/>
            <b style="color: black; font-weight: bold"><u>Article 7</u>   Attribution de juridiction</b>
                <br/>
                Pour toutes contestations relatives au pr&eacute;sent contrat, les parties font attribution de juridiction au Tribunal du travail d&apos;Abidjan.
                <br/><br/>
                <?php $tabDateDebut = explode("-", $travailleur->date_debut_contrat); ?>
                Fait &agrave; Abidjan en 02 (deux) exemplaires originaux de 2 pages, le <?=$tabDateDebut['2']?>/<?=$tabDateDebut['1']?>/<?=$tabDateDebut['0']?>
                <br/><br/><br/><br/>

                <b style="color: black; font-weight: bold">L&apos;EMPLOYE(E)</b>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <b style="color: black; font-weight: bold">L&apos;EMPLOYEUR</b> <br/>
            <b>(Lu et approuv&eacute;)</b>

            </p>
			
        </div>

    </div>

</div>
</body>
</html>
