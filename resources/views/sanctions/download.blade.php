<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb18030">
    <title>Sanction du travailleur - <?php echo $travailleur->nom ?></title>
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

		<div class="text-center">
			<img src="{{ asset('rhassets/images/CaptureSanction.PNG') }}">
		</div>

    <div class="row">

        <div class="col-md-12">
		
			<p>
			<br/>
				<span class="text-left" style="color:#000; font-weight: bold"> Date : {{ date('d') }} / {{ date('m') }} / {{ date('Y') }} </span>
				<br/>
				<br/>
				<br/>
				
					<span class="text-center" style="left: -50px">
                           <b style="color: black; font-weight: bold; font-size: 18px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;A</b> <br/><br/>
						   Nom & Prénoms du travailleur : <b><?php echo strtoupper($travailleur->nom).' '.strtoupper($travailleur->prenom) ?></b><br/><br/>
                           Unité : <b>{{ $unites->label }}</b> <br/><br/>
                           Matricule : <b><?php echo $travailleur->matricule ?></b> <br/>
					</span>
				<br/>
				
			 <b style="color: black; font-weight: bold; font-size: 18px"><u>Sanction Prise</u> </b>
				<br/>
				<br/>
				<?php $tabDebut = explode("-", $sanction->debut); ?>
				<?php $tabFin = explode("-", $sanction->fin); ?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Avertissement  @if($sanction->sanction_applique == 1) <b style="color: black; font-weight: bold; font-size: 20px">&nbsp; [X] </b> @endif <br/><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						Mise à pieds  @if($sanction->sanction_applique == 2) <b style="color: black; font-weight: bold; font-size: 20px">&nbsp; [X] </b> @endif &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   Durée :&nbsp;&nbsp; 
						<b style="color: black; font-weight: bold">{{ $sanction->nombre_jour }}</b> jours (s)   &nbsp;&nbsp;&nbsp;    
						Période : &nbsp; du &nbsp; .......... &nbsp; au &nbsp; ...........
						<br/><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Rupture du contrat @if($sanction->sanction_applique == 3) <b style="color: black; font-weight: bold; font-size: 20px">&nbsp; [X] </b> @endif <br/><br/>

			  <b style="color: black; font-weight: bold; font-size: 18px"><u>Motifs</u> </b>
			  <br/><br/>
			  
				   Le {{ $frdate }} {{ $sanction->expose_motif }}

					<br/>
					<br/>
					<br/>
					<br/>


							<span class="text-center" style="color: #000; font-size: 11px; padding-left: 50px">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								S. KOULIBALY<br/>
							    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
							    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
							    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
							    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
								DIRECTION DES RESSOURCES HUMAINES<br/>
						   </span>
						   
						   

					<br/><br/><br/><br/>
									   <b>Notification au travailleur</b><br/>
									   <b>Matricule, Date et signature</b><br/>
			
			</p>


            
        </div>

    </div>

</div>
</body>
</html>
