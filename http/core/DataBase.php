<?php

namespace Core;

use PDO;
use PDO_Exception;

class DataBase
{
    /**
     * Obtendo database
     */    
    public static function getDatabase()
    {
        //Variavel de configuração
        $conf = include_once __DIR__ . "/../app/database.php";

        if ($conf['driver'] == 'sqlite') {
            //Obtendo arquivo do sqlite e dados conexão
            $sqlite = __DIR__ . "/../storage/database/" . $conf['sqlite']['database'];
            $sqlite = "sqlite:" . $sqlite;
            
            //Tentando conexão
            try{
                $pdo = new PDO($sqlite);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                return $pdo;
            } catch (PDO_Exception $e){
                echo $e->getMessage();
            }
        } elseif ($conf['driver'] == 'mysql') {
            //Obtendo dados conexão
            $host = $conf['mysql']['host'];
            $db = $conf['mysql']['database'];
            $user = $conf['mysql']['user'];
            $pass = $conf['mysql']['pass'];
            $charset = $conf['mysql']['charset'];
            $collation = $conf['mysql']['collation'];
            
            //Tentando conexão
            try{
                $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES '$charset' COLLATE '$collation'");
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                return $pdo;
            } catch (PDO_Exception $e){
                echo $e->getMessage();
            }
        } elseif ($conf['driver'] == 'mysqlProducao') {
            //Obtendo dados conexão
            $host = $conf['mysqlProducao']['host'];
            $db = $conf['mysqlProducao']['database'];
            $user = $conf['mysqlProducao']['user'];
            $pass = $conf['mysqlProducao']['pass'];
            $charset = $conf['mysqlProducao']['charset'];
            $collation = $conf['mysqlProducao']['collation'];
            
            //Tentando conexão
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES '$charset' COLLATE '$collation'");
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                return $pdo;
            } catch (PDO_Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}