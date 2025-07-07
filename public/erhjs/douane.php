<?php

/**
 * Created by PhpStorm.
 * User: oklastar277
 * Date: 26/01/18
 * Time: 15:18
 */
require_once 'AllinOne.php';

$inst = new AllinOne();


if(isset($_GET['Papier'])) {

    $var = null; $i = 0;

    $liste = $inst -> ListePapier();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}


if(isset($_GET['IdDepartement'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeDepartement();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['Idunite'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeUnites();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['NiveauEtude'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeNiveauEtude();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['IdEquipe'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeEquipes();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['fonctionId'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeFonctions();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['categoriesId'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeCategories();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['Idpays'])) {

    $var = null; $i = 0;

    $liste = $inst -> listePays();

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['departementID'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeEquipes(intval($_GET['departementID']));

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}


if(isset($_GET['UniteIdDepart'])) {

    $var = null; $i = 0;

    $liste = $inst -> listeDepartement(intval($_GET['UniteIdDepart']));

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id,

                'name' => utf8_encode(ucfirst($sql->label))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

if(isset($_GET['IdCategorie'])) {

    $var = null; $i = 0;

    $liste = $inst -> ListeSousCategorie(intval($_GET['IdCategorie']));

    if ($liste > 0) {

        foreach ($liste as $sql) {

            $var[$i] = array(

                'id' => $sql->id_souscategorie,

                'name' => utf8_encode(ucfirst($sql->libelle_souscategorie))
            );

            $i++;
        }

        $retour = json_encode($var);
    }
}

echo $retour;
