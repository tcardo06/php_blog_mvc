<?php

namespace TestProject\Model;

class Admin extends Blog
{
    private $email;
    private $password;

    // Getter for email
    public function getEmail()
    {
        return $this->email;
    }

    // Setter for email
    public function setEmail($email)
    {
        $this->email = $email;
    }

    // Getter for password
    public function getPassword()
    {
        return $this->password;
    }

    // Setter for password
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function login($sEmail)
    {
        $this->setEmail($sEmail); // Use the setter for email

        $oStmt = $this->oDb->prepare('SELECT email, password FROM Admins WHERE email = :email LIMIT 1');
        $oStmt->bindValue(':email', $this->getEmail(), \PDO::PARAM_STR); // Use the getter for email
        $oStmt->execute();
        $oRow = $oStmt->fetch(\PDO::FETCH_OBJ);

        // Check if the user was found, and if so, set the password using the setter
        if ($oRow) {
            $this->setPassword($oRow->password);
        }

        // Return the password using the getter
        return $this->getPassword();
    }
}
