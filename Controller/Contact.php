<?php

namespace TestProject\Controller;

// Load PHPMailer
require ROOT_PATH . 'vendor/phpmailer/src/PHPMailer.php';
require ROOT_PATH . 'vendor/phpmailer/src/SMTP.php';
require ROOT_PATH . 'vendor/phpmailer/src/Exception.php';
require ROOT_PATH . 'mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Contact
{
    private $name;
    private $email;
    private $message;
    private $oUtil;

    public function __construct()
    {
        // Initialize $oUtil to load views
        $this->oUtil = new \TestProject\Engine\Util();
    }

    // Setters
    public function setName($name)
    {
        $this->name = htmlspecialchars(trim($name)); // Sanitize input
    }

    public function setEmail($email)
    {
        $this->email = filter_var(trim($email), FILTER_VALIDATE_EMAIL) ? htmlspecialchars($email) : null; // Validate and sanitize email
    }

    public function setMessage($message)
    {
        $this->message = htmlspecialchars(trim($message)); // Sanitize input
    }

    // Getters
    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getMessage()
    {
        return $this->message;
    }

    // Function to handle form submission
    public function submit()
    {
        if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {

            // Use setters to assign values
            $this->setName($_POST['name']);
            $this->setEmail($_POST['email']);
            $this->setMessage($_POST['message']);

            // Redirect to the confirmation page with form data as query parameters
            header('Location: ' . ROOT_URL . '?p=contact&a=confirmation&name=' . urlencode($this->getName()) . '&email=' . urlencode($this->getEmail()) . '&message=' . urlencode($this->getMessage()));
            exit;
        }
    }

    // Function to display the confirmation page
    public function confirmation()
    {
        // Load the view to display the submitted form data
        $this->oUtil->getView('confirmation');
    }
}
