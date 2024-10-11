<?php

namespace TestProject\Controller;

class User extends Blog
{
    public function register()
    {
        if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirm_password']))
        {
            if ($_POST['password'] !== $_POST['confirm_password']) {
                $this->oUtil->sErrMsg = 'Passwords do not match!';
            } else {
                $this->oUtil->getModel('User');
                $this->oModel = new \TestProject\Model\User;

                // Check if the email is already registered
                if ($this->oModel->isEmailRegistered($_POST['email'])) {
                    $this->oUtil->sErrMsg = 'Email already exists!';
                } else {
                    // Hash the password and create the user
                    $sHashPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $this->oModel->register($_POST['name'], $_POST['email'], $sHashPassword);
                    $_SESSION['is_logged'] = 1; // Log the user in
                    $_SESSION['name'] = $_POST['name']; // Store the user's name
                    $_SESSION['role'] = 'user'; // Default role is 'user' for basic users
                    header('Location: ' . ROOT_URL . '?p=blog&a=all');
                    exit;
                }
            }
        }
        $this->oUtil->getView('register');
    }

    public function login()
    {
        if (isset($_POST['email'], $_POST['password']))
        {
            $this->oUtil->getModel('User');
            $this->oModel = new \TestProject\Model\User;

            $oUser = $this->oModel->login($_POST['email']);
            if ($oUser && password_verify($_POST['password'], $oUser->password))
            {
                $_SESSION['is_logged'] = 1;
                $_SESSION['user_id'] = $oUser->id; // Store user_id in session
                $_SESSION['name'] = $oUser->name; // Store user's name in the session
                $_SESSION['role'] = $oUser->role; // 'admin' or 'user'

                // Redirect based on user role
                if ($oUser->role === 'admin') {
                    header('Location: ' . ROOT_URL . '?p=admin&a=dashboard');
                } else {
                    header('Location: ' . ROOT_URL . '?p=blog&a=all');
                }
                exit;
            }
            else
            {
                $this->oUtil->sErrMsg = 'Incorrect Email or Password!';
            }
        }

        // Load the login view
        $this->oUtil->getView('login');
    }
}
