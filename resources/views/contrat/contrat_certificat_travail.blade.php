<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <title>Certificat de travail - <?php echo $travailleur->nom ?></title>
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
                <br/>
                <br/>
                <br/>
                <br/>

                    <div class="text-center" style="font-size: 40px;"> <h1>CERTIFICAT DE TRAVAIL</h1></div>

                <br/>
                <br/>

                Nous soussignés, Société <b style="color: black; font-size: 15px">PLASTICA</b>, 
				SARL au capital de 2.000.000.000 XOF, RC n° 249382,  dont le siège social, sis, ZI de Koumassi, 05 BP 2160 ABIDJAN 05, certifions que :
                <br/>
                <br/>

                Monsieur <b style="color: black; font-size: 15px"><?= $travailleur->nom.' '.$travailleur->prenom.''.$travailleur->prenom_suite ?></b><br>
                Matricule : <b style="color: black; font-size: 15px"><?= $travailleur->matricule ?></b> <br>
                N°CNPS : <b style="color: black; font-size: 15px"><?= $travailleur->numero_securite ?></b> <br>
                <?php $tabDateDebut = explode("-", $travailleur->date_debut_contrat); ?>
                <?php $tabDateFin = explode("-", $travailleur->date_fin_contrat); ?>
                A été  employé dans notre entreprise du <?=$tabDateDebut['2']?>/<?=$tabDateDebut['1']?>/<?=$tabDateDebut['0']?> AU <?=$tabDateFin['2']?>/<?=$tabDateFin['1']?>/<?=$tabDateFin['0']?><br><br>

                En qualité	: @if($travailleur->fonction_entrepriseid) <b>{{ $fonction = \App\Fonction::where('id', $travailleur->fonction_entrepriseid )->first()->label }}</b>@endif <br>
                Catégorie 	: {{ $categorie = \App\Categories::where('id', $travailleur->categorieid )->first()->label }}<br><br>

                Monsieur <b><?= strtoupper($travailleur->nom).' '.strtoupper($travailleur->prenom).''.strtoupper($travailleur->prenom_suite) ?></b> quitte notre entreprise ce jour libre de tout engagement.<br><br>

                Le présent Certificat est délivré pour servir et valoir ce que de droit.<br><br><br><br>




                <div class="text-right">Fait à Abidjan le {{ date('d') }} / {{ date('m') }} / {{ date('Y') }} <br/><br/><br/>


                <u>La Direction</u>
                </div>





            </p>


        </div>

    </div>

</div>
</body>
</html>
