<?php

namespace core;

use PDO;

class Db
{

    use TSingleton;

    public object $db;

    private function __construct()
    {
        $db = require_once CONFIG . '/config_db.php';
        $this->db = new PDO(
            $db['dsn'],
            $db['username'],
            $db['password']
        );
    }

}