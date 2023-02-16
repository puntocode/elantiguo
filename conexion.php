<?php

class Conexion extends PDO
{
    private $bd   = 'mysql';
    private $host = 'localhost';
    private $name = 'alantico_elantiguo';
    private $user = 'root'; 
    private $pass = 'password';

    public function __construct(){
        try {
            parent::__construct($this->bd . ':host=' . $this->host . ';dbname=' . $this->name.'; charset=utf8mb4', $this->user, $this->pass);
        } catch (PDOException $e) {
            echo 'Exception Connection: ' . $e->getMessage();
            exit;
        }
    }
}
