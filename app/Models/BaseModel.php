<?php

require_once '../config/database.php';

abstract class BaseModel {
    protected $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }
}