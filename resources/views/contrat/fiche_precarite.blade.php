<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <title>Fiche de précarité - <?php echo $travailleur->nom ?></title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        h1 {
            text-shadow: 10px 0px 9px #000;
            color: #545b62;
        }
        body{
            font-family: Tahoma, Helvetica, Arial;
            font-size: 15px;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="row">

        <div class="col-md-12">


            <p>
                <br/>
                <br/>
                <br/><br/>
                <br/>
                <br/>
                <br/>
                    <div class="text-center">
                        <span style="font-size: 33px; color: black;">PRIME DE PRECARITE</span>
                    </div>
                <br/>
                <br/>


                Nom et Prénoms:			<b><?= $travailleur->nom ?> <?= $travailleur->prenom ?> </b>
                <br/>
                <br/>

                Fonction:	<b>{{ $fonction = optional(\App\Fonction::where('id', $travailleur->fonction_entrepriseid )->first())->label }}</b>
                <br/>
                <br/>
                <?php

                $precarite = optional(\App\Precarites::where('matricule', $travailleur->matricule )->first())->valeur;

                $debut = strtotime($travailleur->date_debut_contrat);
                $fin = strtotime($travailleur->date_fin_contrat);
                $dif = ceil(abs($fin - $debut) / 86400);
                $nbrmois = $dif/30;
                $calculs = 2768 + 176	* 0.03 * $precarite;
                ?>
                <?php $tabDateDebut = explode("-", $travailleur->date_debut_contrat); ?>
                <?php $tabDateFin = explode("-", $travailleur->date_fin_contrat); ?>
                Ancienneté (Période):	<b> <?=$tabDateDebut['2']?>/<?=$tabDateDebut['1']?>/<?=$tabDateDebut['0']?> &nbsp;&nbsp;&nbsp; au &nbsp;&nbsp;&nbsp; <?=$tabDateFin['2']?>/<?=$tabDateFin['1']?>/<?=$tabDateFin['0']?> &nbsp;&nbsp;&nbsp; </b>	=	&nbsp;&nbsp;&nbsp;  <?= number_format($nbrmois, '1', ',', ' ') ?>    		mois
<br/>
<br/>
                <b>Prime forfaitaire &nbsp;&nbsp;&nbsp;	2768	+	176	x	3%	x	{{ $precarite }}	 &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;  = &nbsp;&nbsp;&nbsp; <?= 2768 + 176	* 0.03 * $precarite ?> </b>
                <br/>
                <br/>
                <b>Salaire de présence:		</b>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <br/>
                <br/>
                Total net à payer	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	=	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <b> <?= number_format($calculs, '2', ',', '.') ?>   			Francs</b>
                <br/>
                <br/>
                <br/>

            <?php
            // créer l'objet
            $calcul = 2768 + 176	* 0.03 * $precarite;
                $f = new NumberFormatter("fr", NumberFormatter::SPELLOUT);
                $lecture = ($f->format($calcul));

            ?>

            Je soussigné Monsieur	<b><?= $travailleur->nom ?> <?= $travailleur->prenom ?></b>
            atteste avoir travaillé effectivement {{ $precarite }} jours au sein de la société <b>PLASTICA</b> et reçu
            la somme de <b><?= number_format($calculs, '2', ',', '.') ?> F CFA ( <?= $lecture ?> francs CFA)</b>
                au titre de la prime de fin de contrat pour le temps passé dans la société.<br/>
                Je signe cette présence pour servir et valoir ce que de droit.<br/>
                <br/>
                <br/>

                {{ date('d') }} / {{ date('m') }} / {{ date('Y') }}

                <br/>
                <br/>
                <br/>
                Travailleur<br/>
                (Bon pour solde de tout  compte)<br/>


            </p>


        </div>

    </div>

</div>
</body>
</html>
