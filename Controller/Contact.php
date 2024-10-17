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
        // Enable error reporting for better debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        // Debug: Check if form submission is received
        if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
            echo "Form submitted.<br>";

            // Use setters to assign values
            $this->setName($_POST['name']);
            $this->setEmail($_POST['email']);
            $this->setMessage($_POST['message']);

            // Check if the email is valid
            if (!$this->getEmail()) {
                $_SESSION['error'] = 'Adresse email invalide.';
                echo "Invalid email address.<br>";
                die();
            }

            // Debug: Check if the email validation passed
            echo "Email is valid, trying to send...<br>";

            // Load the email configuration (Mailtrap)
            $config = require ROOT_PATH . 'mail_config.php';

            // Debug: Check if mail config was loaded
            echo "Mail config loaded...<br>";

            $mail = new PHPMailer(true);

            try {
                // SMTP server configuration (Mailtrap)
                $mail->isSMTP();
                $mail->SMTPDebug  = 2;  // Enable verbose debug output
                $mail->Host       = $config['SMTP_HOST'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $config['SMTP_USER'];
                $mail->Password   = $config['SMTP_PASS'];
                $mail->SMTPSecure = $config['SMTP_SECURE'];
                $mail->Port       = $config['SMTP_PORT'];

                // Debug: Check SMTP configuration
                echo "SMTP configuration loaded, sending email...<br>";

                // Set email sender and recipient
                $mail->setFrom($this->getEmail(), $this->getName());

                // Use a valid recipient email for Mailtrap (or your own email for testing)
                $mail->addAddress('tcardo0606@gmail.com'); // Replace with your test email or Mailtrap inbox address

                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'New Contact Form Submission';
                $mail->Body    = '<strong>Name:</strong> ' . $this->getName() . '<br><strong>Email:</strong> ' . $this->getEmail() . '<br><strong>Message:</strong> ' . nl2br($this->getMessage());

                // Try to send the email
                if ($mail->send()) {
                    $_SESSION['message'] = 'Message envoyé avec succès!';
                    echo "Email sent successfully!<br>";
                    die(); // Stop here to verify email was sent
                } else {
                    $_SESSION['error'] = 'Le message n\'a pas pu être envoyé.';
                    echo "Failed to send email.<br>";
                    die(); // Stop here to check email sending failure
                }
            } catch (Exception $e) {
                // Catch any PHPMailer exceptions and log them
                $_SESSION['error'] = "Le message n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
                echo "Error while sending email: {$mail->ErrorInfo}<br>";
                die(); // Stop here to check error details
            }

            // Redirect to avoid form resubmission
            header('Location: ' . ROOT_URL . '?p=contact&a=form');
            exit;
        } else {
            echo "Form not submitted properly.<br>";
            die(); // Stop here to check if form was not submitted properly
        }
    }
}
