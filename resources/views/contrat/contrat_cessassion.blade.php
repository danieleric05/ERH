<html>
<head>
    <meta charset="utf-8">
    <title>Fiche d'inscription - <?php echo $travailleur->nom ?></title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body{
            font-family: Tahoma, Helvetica, Arial;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="container">

                 <div class="text-center">
                    <img src="{{ asset('rhassets/images/entete.jpg') }}"  height="95" width="720">
                 </div>
                 <br/>
                    <div class="text-center" style="text-align: center; font-weight: bold"> (1) &nbsp; &nbsp; &nbsp;  Embauche &nbsp; &nbsp; &nbsp; Modification &nbsp; <b>x</b> Cessation d’Emploi &nbsp;&nbsp;&nbsp;&nbsp;  </div>
                 <br/>
                <b style="font-size: 18px; font-weight: bold"><u>EMPLOYEUR</u></b>
                <br/>
                NUMERO C.N.P.S : <b style="color: black">______<u style="color: black">82408</u>________</b>   Nom, Raison sociale : ______<u style="color: black">PLASTICA</u>_____________  <br/>
            Adresse postale : <b><u>05 BP 2160 Abidjan 05</u></b> Téléphone :<b style="color: black">_<u>+225 21 75 73 80</u>___ </b> Fax: <b style="color: black"><u>_+225 21 36 43 13</u>_</b>  <br/>
            <i>Cel</i> : <b style="color: black">____________________________</b> <i>Email</i> : <b style="color: black">________________________________________</b><br/><br/>


                <b  style="font-size: 18px; font-weight: bold"><u>TRAVAILLEUR</u></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; N° C.N.P.S /__ /__ /__ /__ /__ /__ /__ /__ /__ /__ /__ /__ /  <br/>
                <br/>
                Nom : <b style="color: black"><u><?= $travailleur->nom ?></u></b>  &nbsp;&nbsp;&nbsp;&nbsp; Prénoms : <b><u><?= $travailleur->prenom ?></u></b><br/>

                Epouse : _________________________________________________________________________ <br/>

            Sexe (1)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   @if( ($travailleur->civilite == 'Mademoiselle') || ($travailleur->civilite == 'Madame') ) <b style="font-weight: bold; color: black">X</b> @endif <b>féminin</b>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  @if( ($travailleur->civilite == 'Monsieur') ) <b style="font-weight: bold; color: black">X</b> @endif <b><i>masculin</i></b>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i>Nationalité</i> : <b style="color: black">  ___<u>IVOIRIENNE</u>___ </b>  <br/>
                <?php $tabDate = explode("-", $travailleur->date_naissance); ?>
                <?php $tabDateP = explode("-", $travailleur->pieceidentite_livrele); ?>
                <?php $tabDateDebut = explode("-", $travailleur->date_debut_contrat); ?>
                <?php $tabDateFin = explode("-", $travailleur->date_fin_contrat); ?>
                <?php $tabDateJour = explode("-", date('Y-m-d')); ?>

                @if($travailleur->date_naissance)
                <i>Date de naissance</i> /_<u><?=$tabDate['2']?></u>_/ /_<u><?=$tabDate['1']?></u>_/ /_<u><?=$tabDate['0']?></u>_/ &nbsp;&nbsp;&nbsp;&nbsp;  Lieu de Naissance : _________<u><?= $travailleur->lieu_naissance ?></u>__________ <br/>
                @endif

                @if(!$travailleur->date_naissance)
                <i>Date de naissance</i> :  /___ / /___ / /___ / &nbsp;&nbsp;&nbsp;&nbsp;  <i>Lieu de Naissance</i> : <u><?= $travailleur->lieu_naissance ?></u><br/>
                @endif

                <b><i>Pièce d’identité </i>: </b>  <i>numéro</i>  <b>/_<u><?= $travailleur->pieceidentite ?></u>_/</b>   nature : __________________________________<br/>

                @if($travailleur->pieceidentite_livrele)
                   Date d’établissement : &nbsp;&nbsp;&nbsp; /_<u><?=$tabDateP['2']?></u>_/ /_<u><?=$tabDateP['1']?></u>_/ /_<u><?=$tabDateP['0']?></u>_/ &nbsp;&nbsp;&nbsp;  Autorité : _______________________ <br/>
                @endif

                @if(!$travailleur->pieceidentite_livrele)
                   Date d’établissement : &nbsp;&nbsp;&nbsp; /___ / /___ / /___ / &nbsp;&nbsp;&nbsp;  Autorité : _________<u>ONI</u>____________ <br/>
                @endif

            <b>Adresse :</b> &nbsp;ville : __<b><u>Abidjan</u></b>__&nbsp;&nbsp;&nbsp;Commune :
    <u>
        @if($travailleur->communeid)
        {{ $commune = optional(\App\Commune::where('id', $travailleur->communeid )->first())->label }}
            @endif
    </u>
    quartier: ______&nbsp;&nbsp;&nbsp;&nbsp;  ILot N°: _____ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Lot N°: ____<br/>

            Adresse postale : ___________ Téléphone : ___________ Céllulaire : ___<u><b>+225 <?= $travailleur->telephone ?></b></u>____ <br/>

            <i><b>Situation matrimoniale</b></i> : (1) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;@if($travailleur->situation_mat == 'Célibataire') <b style="font-weight: bold; color: black">X</b> @endif célibataire &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; @if($travailleur->situation_mat == 'Marié(e)') <b style="font-weight: bold; color: black">X</b> @endif marié(e) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; séparé(é) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; veuf(veuve) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; divorcé(e)
                <br/>
                <br/>
                <b style="font-size: 18px; font-weight: bold"><u>EMPLOI</u></b><br/>
                <b>. Emploi actuel</b>  <br/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date d’embauche</i>  &nbsp;&nbsp;&nbsp; /_<u><?=$tabDateDebut['2']?></u>_/ /_<u><?=$tabDateDebut['1']?></u>_/ /_<u><?=$tabDateDebut['0']?></u>_/ &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  Fonction ____<u style="font-weight: bold; color: black;">  @if($travailleur->fonction_entrepriseid) {{ $fonction = optional(\App\Fonction::where('id', $travailleur->fonction_entrepriseid )->first())->label }}@endif</u>____ <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Catégorie professionelle</i> : ________<u style="font-weight: bold; color: black;">{{ $categorie= optional(\App\Categories::where('id', $travailleur->categorieid )->first())->label }}</u>______  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Matricule Paie : ______<u style="font-weight: bold; color: black;"><b style="color: black; font-size: 15px"><?= $travailleur->matricule ?></b></u>______ <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de départ</i> <b style="color: black">&nbsp;&nbsp;&nbsp; /_<u><?=$tabDateFin['2']?></u>_/ /_<u><?=$tabDateFin['1']?></u>_/ /_<u><?=$tabDateFin['0']?></u>_/ &nbsp;&nbsp;&nbsp;</b>  Motif: ___<u style="font-weight: bold; color: black "><?= $travailleur->motif_fin_contrat ?></u>___ <br/>

            <br/>
            <b>. Emploi précédent</b><br/>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Numéro CNPS travailleur</i> /__ /__ /__ /__ /__ /__ /__ /__ /__ /__ /__ /__ / <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Numéro CNPS employeur</i> : /_____________/   Raison sociale : __________________ <br/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de départ de l’entreprise</i> /___ / /___ / /___ /  Motif de départ : ___________________ <br/><br/>


                Déclaration faite à , ________<u style="font-weight: bold; color: black">Abidjan</u>___________ , le /_<span style="text-decoration: underline"><?=$tabDateJour['2']?></span>_/ /_<u><?=$tabDateJour['1']?></u>_/ /_<u><?=$tabDateJour['0']?></u>_/
            <br/>
            <br/>
                        <div style="text-align: right">
                            Signature et cachet de l’employeur(2)
                        </div>
                <br/>

                <div class="text-center" style="text-align: center">
                    <b>NB :</b> cocher la case correspondante (2) Cachet obligatoire pour l'entreprise.
                </div>

                <div class="text-left" style="border: black double 1px; font-size: 10px; padding: 5px 5px 10px 10px">
                    <u><b>Pièces à joindre : </b></u> <b>Travailleur</b>: photocopie de la carte d'identité, carte consulaire pour les etrangers, 02
                    photos d'identité, un extrait d'acte de naissance. <br/>
                    <b>Eventuellement</b> fiche de déclaration des membre de la famille. <br/>
                    <b>Entreprise :</b> en cas de cessassion, joindre la <b>DISA</b>, dûment remplie et signé.<br/>
                </div>

                <div class="text-center" style="text-align: center; font-size: 10px;">
                    <b>imprimer <b style="font-size: 16px"> O </b> </b> CNPS 2 0 1 5 - KZGS
                </div>

        </div>

</body>
</html>
