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

    public function register($sName, $sEmail, $sPassword)
    {
        $oStmt = $this->oDb->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $oStmt->bindValue(':name', $sName, \PDO::PARAM_STR);
        $oStmt->bindValue(':email', $sEmail, \PDO::PARAM_STR);
        $oStmt->bindValue(':password', $sPassword, \PDO::PARAM_STR);
        $oStmt->execute();
    }

    public function login($email)
    {
        $oStmt = $this->oDb->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1');
        $oStmt->bindValue(':email', $email, \PDO::PARAM_STR);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }
