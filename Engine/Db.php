<?php

namespace TestProject\Engine;

class Db extends \PDO
{
    public function __construct()
    {
        try {
            parent::__construct(
                'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8',
                Config::DB_USR,
                Config::DB_PWD,
                [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]
            );
        } catch (\PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }        
    }
}
