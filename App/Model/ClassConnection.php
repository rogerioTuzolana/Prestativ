<?php

namespace App\Model;

abstract class ClassConnection {
    private static $con;
    public function connect_db(){
        try {
            $con = new \PDO("mysql:host=".HOST.";port=3306;dbname=".DB."","".USER."","".PASSWORD."");
            return $con;
        } catch (\PDOException $ex) {
            return $ex->getMessage();
        }
    }
}