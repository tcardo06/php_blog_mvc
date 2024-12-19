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
            $_SESSION = array();
            session_unset();
            session_destroy();
        }

        header('Location: ' . ROOT_URL);
        return;
    }

    public function dashboard()
    {
        if (!$this->oUtil->isLogged() || $this->oUtil->getRole() !== 'admin') {
            header('Location: ' . ROOT_URL);
            return;
        }

        // Pass session data to the View
        $this->oUtil->isLogged = $this->oUtil->isLogged();
        $this->oUtil->role = $this->oUtil->getRole();
        $this->oUtil->userName = $this->oUtil->getUserName();

        $this->oUtil->getView('dashboard');
    }
}
