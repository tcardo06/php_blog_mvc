<?php

namespace TestProject\Controller;

class Admin extends Blog
{
    public function login()
    {
        if ($this->isLogged())
            header('Location: ' . ROOT_URL . '?p=blog&a=all');

        if (isset($_POST['email'], $_POST['password']))
        {
            $this->oUtil->getModel('User');
            $this->oModel = new \TestProject\Model\User;

            $oUser = $this->oModel->login($_POST['email']);
            if ($oUser && password_verify($_POST['password'], $oUser->password))
            {
                $_SESSION['is_logged'] = 1; // User is logged in
                $_SESSION['role'] = $oUser->role; // Store role (admin/user)

                header('Location: ' . ROOT_URL . '?p=blog&a=all');
                exit;
            }
            else
            {
                $this->oUtil->sErrMsg = 'Incorrect Login!';
            }
        }

        $this->oUtil->getView('login');
    }

    public function logout()
    {
        if (!$this->isLogged())
            exit;

        if (!empty($_SESSION))
        {
            $_SESSION = array();
            session_unset();
            session_destroy();
        }

        header('Location: ' . ROOT_URL);
        exit;
    }
}
