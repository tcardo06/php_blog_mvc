<?php

namespace TestProject\Model;

class User extends Blog
{
    public function isEmailRegistered($sEmail)
    {
        $oStmt = $this->oDb->prepare('SELECT email FROM users WHERE email = :email LIMIT 1');
        $oStmt->bindValue(':email', $sEmail, \PDO::PARAM_STR);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }

    public function register($sEmail, $sPassword)
    {
        $oStmt = $this->oDb->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $oStmt->bindValue(':email', $sEmail, \PDO::PARAM_STR);
        $oStmt->bindValue(':password', $sPassword, \PDO::PARAM_STR);
        $oStmt->execute();
    }

    public function login($sEmail)
    {
        $oStmt = $this->oDb->prepare('SELECT email, password, role FROM users WHERE email = :email LIMIT 1');
        $oStmt->bindValue(':email', $sEmail, \PDO::PARAM_STR);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }
}
