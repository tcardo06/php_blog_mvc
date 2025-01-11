<?php

namespace TestProject\Controller;

class Admin extends Blog
{
    public function logout()
    {
        if (!isset($_SESSION['is_logged'])) {
            return;
        }

        if (!empty($_SESSION)) {
            $_SESSION = [];
            session_unset();
            session_destroy();
        }

        header('Location: ' . ROOT_URL);
        return;
    }

    public function dashboard()
    {
        if (!$this->oUtil->isLogged() || $_SESSION['role'] !== 'admin') {
            header('Location: ' . ROOT_URL);
            return;
        }

        // Pass session data to the View
        $this->oUtil->isLogged = $this->oUtil->isLogged();
        $this->oUtil->role = $_SESSION['role'];
        $this->oUtil->userName = $_SESSION['name'];

        $this->oUtil->getView('dashboard');
    }
}

