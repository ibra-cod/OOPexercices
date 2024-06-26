<?php 

namespace App;
use PDO;

class Database
{
    
    private static $pdo;
    private static $auth;

    public static function getPDO() : PDO {

        if (is_null(self::$pdo)) {
            self::$pdo = new PDO('sqlite:../data.sqlite',null,null,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        }
        return self::$pdo;
    }

     public static function getAuth() : Auth {

        if (is_null(self::$auth)) {
             self::$auth = new Auth(self::getPDO(), '/login');
        }
        return self::$auth;
    }

}
