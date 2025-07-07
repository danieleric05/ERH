<?php

/**
 * Created by PhpStorm.
 * User: shurti
 * Date: 11/02/16
 * Time: 23:56
 */
require_once 'dbc_connect.php';

class AllinOne extends IdeaExpression {

    public function __construct() {

        $this->cnx = parent::__construct();
    }


    public function ListeSousCategorie($id_categorie) {

        $sql = 'SELECT * FROM  pl_souscategorie WHERE id_categorie = ? ORDER BY libelle_souscategorie ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->bindValue(1, $id_categorie, PDO::PARAM_INT);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }

    public function ListePrixTypeClient($equipe_id) {

        $sql = 'SELECT prixunitaire FROM  papier WHERE id = ? ORDER BY label ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->bindValue(1, $equipe_id, PDO::PARAM_INT);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }


    public function listeDepartement() {

        $sql = 'SELECT *  FROM e_departement ORDER BY id ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }


    public function listeEquipes() {

        $sql = 'SELECT *  FROM e_equipe ORDER BY id ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }

    public function listeUnites() {

        $sql = 'SELECT *  FROM e_unite';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }

    public function listePays() {

        $sql = 'SELECT *  FROM e_pays ORDER BY id ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }

    public function listeNiveauEtude() {

        $sql = 'SELECT *  FROM e_niveau_etude ORDER BY id ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }

    public function listeFonctions() {

        $sql = 'SELECT *  FROM e_fonction ORDER BY id ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }

    public function listeCategories() {

        $sql = 'SELECT *  FROM e_categorie ORDER BY id ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }

    public function listeEquipes($id_unite) {

        $sql = 'SELECT *  FROM e_equipe WHERE departementid = ? ORDER BY id ASC';

        try {

            $this->cnx->beginTransaction();

            $p = $this->cnx->prepare($sql);

            $p->bindValue(1, $id_unite, PDO::PARAM_INT);

            $p->execute();

            $retour = $p->fetchAll();

            $p->closeCursor();

            $this->cnx->commit();

            return $retour;

        } catch (PDOException $e) {

            $this->cnx->rollBack();

            echo $e->getMessage();
        }
    }


}
