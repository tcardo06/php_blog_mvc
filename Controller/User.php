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
      if (isset($_POST['email'], $_POST['password'])) {
          $this->oUtil->getModel('User');
          $this->oModel = new \TestProject\Model\User;

          // Set the email in the model
          $this->oModel->setEmail($_POST['email']);

          // Fetch the user
          $oUser = $this->oModel->login();

          if ($oUser && password_verify($_POST['password'], $oUser->password)) {
              // Set session variables
              $_SESSION['is_logged'] = 1;
              $_SESSION['user_id'] = $oUser->id;
              $_SESSION['name'] = $oUser->name;
              $_SESSION['role'] = $oUser->role;

              // Redirect based on role
              try {
                  if ($oUser->role === 'admin') {
                      $this->redirect(ROOT_URL . '?p=admin&a=dashboard');
                  } else {
                      $this->redirect(ROOT_URL . '?p=blog');
                  }
              } catch (\Exception $e) {
                  $this->oUtil->sErrMsg = $e->getMessage();
              }
          } else {
              $this->oUtil->sErrMsg = 'Email ou mot de passe incorrect!';
          }
      }

      // Load the login view
      $this->oUtil->getView('login');
  }

  private function redirect($url)
  {
      header('Location: ' . $url);
      throw new \Exception('Redirection to ' . $url);
  }
}
