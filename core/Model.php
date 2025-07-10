<?php

namespace core;

abstract class Model
{

    public object $db;

    public function __construct()
    {
        $this->db = Db::getInstance()->db;
    }

}