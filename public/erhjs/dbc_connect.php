<?php

/**
 * Created by PhpStorm.
 * User: oklastar277
 * Date: 26/01/18
 * Time: 15:18
 */
class IdeaExpression
{
    private $host = '127.0.0.1';

    private $username = 'c5userapp';

    private $password = 'n!aFk3qcSLE';

    private $dataBase = 'c5appstat';

    private $db;

    protected $cnx;

    public function __construct($host=null, $username = null, $password = null, $dataBase = null)
    {

        if( $host != null){

            $this-> host = $host;

            $this-> username = $username;

            $this-> password = $password;

            $this-> dataBase = $dataBase;

        }
        
        try{

            $strConnection = 'mysql:host='.$this-> host.';dbname='.$this-> dataBase.'';

            $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAME utf8");

            $this-> db = new PDO($strConnection, $this-> username, $this-> password);

            $this -> db ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this -> db ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            return $this -> db;

        }

        catch (PDOException $e){

            $Msg = 'ERREUR PDO dans '.$e->getFile() . ' Ligne. '.$e->getLine(). ' : '.$e->getMessage();

            die($Msg);

        }
    }
}
