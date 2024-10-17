<?php

namespace TestProject\Controller;

// Load PHPMailer
require ROOT_DIR . '/vendor/phpmailer/PHPMailer.php';
require ROOT_DIR . '/vendor/phpmailer/SMTP.php';
require ROOT_DIR . '/vendor/phpmailer/Exception.php';
require ROOT_DIR . '/mail_config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Contact
{
    private $name;
    private $email;
    private $message;

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

            if (!$this->getEmail()) {
                $_SESSION['error'] = 'Adresse email invalide.';
                header('Location: ' . ROOT_URL . '?p=contact&a=form');
                exit;
            }

            // Load PHPMailer
            require 'path_to_PHPMailer/PHPMailer.php';
            require 'path_to_PHPMailer/SMTP.php';
            require 'path_to_PHPMailer/Exception.php';

            $mail = new PHPMailer(true);

            try {
                // SMTP server configuration
                $mail->isSMTP();
                $mail->Host       = $config['SMTP_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $config['SMTP_USER'];
                $mail->Password   = $config['SMTP_PASS'];
                $mail->SMTPSecure = $config['SMTP_SECURE'];
                $mail->Port       = $config['SMTP_PORT'];

                // Set email sender and recipient
                $mail->setFrom($this->getEmail(), $this->getName());
                $mail->addAddress('your_gmail@gmail.com'); // Your Gmail to receive the message

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'New Contact Form Submission';
                $mail->Body    = '<strong>Name:</strong> ' . $this->getName() . '<br><strong>Email:</strong> ' . $this->getEmail() . '<br><strong>Message:</strong> ' . nl2br($this->getMessage());

                $mail->send();
                $_SESSION['message'] = 'Message envoyé avec succès!';
            } catch (Exception $e) {
                $_SESSION['error'] = "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
            }

            // Redirect to avoid resubmission
            header('Location: ' . ROOT_URL . '?p=contact&a=form');
            exit;
        }
    }
}
