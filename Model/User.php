<?php

namespace TestProject\Model;

class User extends Blog
{
    private $name;
    private $email;
    private $password;

    // Getters and setters for name, email, and password
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    // Check if an email is already registered
    public function isEmailRegistered()
    {
        $oStmt = $this->oDb->prepare('SELECT email FROM users WHERE email = :email LIMIT 1');
        $oStmt->bindValue(':email', $this->getEmail(), \PDO::PARAM_STR);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }

    // Register a new user
    public function register()
    {
        $oStmt = $this->oDb->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
        $oStmt->bindValue(':name', $this->getName(), \PDO::PARAM_STR);
        $oStmt->bindValue(':email', $this->getEmail(), \PDO::PARAM_STR);
        $oStmt->bindValue(':password', $this->getPassword(), \PDO::PARAM_STR);
        $oStmt->execute();
    }

    // Login function
    public function login()
    {
        $oStmt = $this->oDb->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1');
        $oStmt->bindValue(':email', $this->getEmail(), \PDO::PARAM_STR);
        $oStmt->execute();
        return $oStmt->fetch(\PDO::FETCH_OBJ);
    }
}
