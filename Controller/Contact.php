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

    // Send Email using PHPMailer
    private function sendEmail()
    {
        // Retrieve mail configuration
        $mailConfig = require ROOT_PATH . 'mail_config.php';

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // Enable verbose debug output
            $mail->SMTPDebug = 4; // Most detailed debug level
            $mail->Debugoutput = function ($str, $level) {
                file_put_contents(ROOT_PATH . 'logs/email_debug.log', date('[Y-m-d H:i:s]') . " Level $level: $str\n", FILE_APPEND);
            };

            // Configure SMTP settings
            $mail->isSMTP();
            $mail->Host = $mailConfig['SMTP_HOST'];
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig['SMTP_USER'];
            $mail->Password = $mailConfig['SMTP_PASS'];
            $mail->SMTPSecure = $mailConfig['SMTP_SECURE'];
            $mail->Port = $mailConfig['SMTP_PORT'];

            // Set sender and recipient
            $mail->setFrom($this->getEmail(), $this->getName());
            $mail->addAddress($mailConfig['SMTP_USER']); // Your email address

            // Email subject and body
            $mail->Subject = 'Nouveau message via le formulaire de contact';
            $mail->Body = "Nom: {$this->getName()}\nEmail: {$this->getEmail()}\nMessage:\n{$this->getMessage()}";

            // Send the email
            return $mail->send();
        } catch (Exception $e) {
            // Log the error message for debugging
            file_put_contents(
                ROOT_PATH . 'logs/email_log.txt',
                date('[Y-m-d H:i:s]') . ' Exception: ' . $mail->ErrorInfo . PHP_EOL,
                FILE_APPEND
            );
            return false;
        }
    }


    // Function to handle form submission
    public function submit()
      {
          if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['message'])) {
              // Set form data
              $this->setName($_POST['name']);
              $this->setEmail($_POST['email']);
              $this->setMessage($_POST['message']);

              // Attempt to send the email
              $isSent = $this->sendEmail();

              // Set session messages
              if ($isSent) {
                  $_SESSION['message'] = 'Votre message a été envoyé avec succès.';
              } else {
                  $_SESSION['error'] = 'Erreur lors de l\'envoi du message.';
              }
          } else {
              $_SESSION['error'] = 'Veuillez remplir tous les champs.';
      }

      // Redirect back to the home page
      header('Location: ' . ROOT_URL);
      exit;
  }

    public function confirmation()
    {
        // Load the confirmation view
        $this->oUtil->getView('confirmation');
    }

    public function notFound()
    {
        $this->oUtil->getView('not_found');
    }
}
