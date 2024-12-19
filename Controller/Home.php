<?php

namespace TestProject\Controller;

class Home
{
    private $oUtil;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->oUtil = new \TestProject\Engine\Util;
        $sessionData = $this->oUtil->getSessionData();
        $this->oUtil->isLogged = $sessionData['isLogged'];
        $this->oUtil->userName = $sessionData['userName'];
        $this->oUtil->role = $sessionData['role'];
    }

    public function index()
    {
        // Pass session-related data to the Views
        $this->oUtil->isLogged = $this->oUtil->isLogged();
        $this->oUtil->role = $this->oUtil->getRole();
        $this->oUtil->userName = $this->oUtil->getUserName();

        // Load the home View
        $this->oUtil->getView('home');
    }

    public function notFound()
    {
        // Pass session-related data to the Views
        $this->oUtil->isLogged = $this->oUtil->isLogged();
        $this->oUtil->role = $this->oUtil->getRole();
        $this->oUtil->userName = $this->oUtil->getUserName();

        // Load the 404 not found View
        $this->oUtil->getView('not_found');
    }
}
