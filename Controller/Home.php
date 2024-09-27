<?php

namespace TestProject\Controller;

class Home
{
    public function index()
    {
        // This will load the home page view (about me + contact form)
        require ROOT_PATH . 'View/home.php';
    }

    public function notFound()
    {
        require ROOT_PATH . 'View/not_found.php';
    }
}
