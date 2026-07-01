<?php

class Database {
    private $connection;

    public function __construct() {
        $config = require __DIR__ . '/config.php';

        $this->connection = new mysqli(
            $config['HOST'],
            $config['USER'],
            $config['PASSWORD'],
            $config['DATABASE'],
        );

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }


    }
    public function getConnection(){
        return $this->connection;
    }
}

