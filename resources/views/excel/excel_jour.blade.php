<?php
if($recherches){

    $tabMots = explode("+", $code);
    $debut = $tabMots['0'];
    $fin = $tabMots['1'];

    if($tabMots['2'] == 2 ){
        $contrat = 'EMBAUCHES';
    }elseif ($tabMots['2'] == 3){
        $contrat = 'CESSATIONS';
    }elseif ($tabMots['2'] == 4){
        $contrat = 'CERTIFICAT DE TRAVAIL';
    }elseif ($tabMots['2'] == 5){
        $contrat = 'DECLARATIONS';
    }elseif ($tabMots['2'] == 6){
        $contrat = 'RECONDUIRE';
    }

    $output = "";
    $output .=  "NOM\tNOM\tNOM\tNOM\tNOM\n";

    foreach ($recherches as $dats) {
        $output .=  "$dats->nom\t$dats->prenom\t$dats->prenom\t$dats->prenom\t$dats->prenom\n";
    }

    header("Content-type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=Travailleur-$contrat-$debut-$fin.xlsx");
    print  $output;


}else{
    echo 'VIDE';
}
