<?php

namespace TestProject\Controller;

class Home
{
    public function index()
    {
        require ROOT_PATH . 'View/home.php';
    }

    public function notFound()
    {
        require ROOT_PATH . 'View/not_found.php';
    }
}
