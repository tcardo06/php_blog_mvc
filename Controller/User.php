<?php

namespace TestProject\Controller;

class User extends Blog
{
  public function register()
  {
      if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['confirm_password'])) {
          if ($_POST['password'] !== $_POST['confirm_password']) {
              $this->oUtil->sErrMsg = 'Passwords do not match!';
          } else {
              $this->oUtil->getModel('User');
              $this->oModel = new \TestProject\Model\User;

              // Set email
              $this->oModel->setEmail($_POST['email']);

              // Check if the email is already registered
              if ($this->oModel->isEmailRegistered()) {
                  $this->oUtil->sErrMsg = 'Email already exists!';
              } else {
                  // Set name and password
                  $this->oModel->setName($_POST['name']);
                  $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                  $this->oModel->setPassword($hashedPassword);

                  // Register the user
                  $this->oModel->register();

                  // Log the user in and set session variables
                  $_SESSION['is_logged'] = 1;
                  $_SESSION['name'] = $this->oModel->getName(); // Use the getter method for name
                  $_SESSION['role'] = 'user'; // Default role

                  header('Location: ' . ROOT_URL . '?p=blog');
                  return;
              }
          }
      }

      // Load the register view
      $this->oUtil->getView('register');
  }

  public function login()
  {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $this->oUtil->getModel('User');
          $this->oModel = new \TestProject\Model\User;

          $this->oModel->setEmail($_POST['email']);
          $oUser = $this->oModel->login();

          if ($oUser && password_verify($_POST['password'], $oUser->password)) {
              $this->oUtil->setSessionData([
                  'is_logged' => true,
                  'user_id' => $oUser->id,
                  'name' => $oUser->name,
                  'role' => $oUser->role,
              ]);

              $redirectUrl = $oUser->role === 'admin' ? ROOT_URL . '?p=admin&a=dashboard' : ROOT_URL . '?p=blog';
              header('Location: ' . $redirectUrl);
              return;
          }

          $this->oUtil->sErrMsg = 'Email ou mot de passe incorrect!';
      }

      // Pass session data to the View
      $this->oUtil->isLogged = $this->oUtil->isLogged();
      $this->oUtil->getView('login');
  }

  private function redirect($url)
  {
      header('Location: ' . $url);
      throw new \Exception('Redirection to ' . $url);
  }
}
