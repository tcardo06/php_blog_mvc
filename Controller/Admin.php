<?php

namespace TestProject\Controller;

class Admin extends Blog
{
  public function login()
  {
      if ($this->isLogged())
          header('Location: ' . ROOT_URL . '?p=blog&a=all');

      if (isset($_POST['email'], $_POST['password']))
      {
          $this->oUtil->getModel('User');
          $this->oModel = new \TestProject\Model\User;

          $oUser = $this->oModel->login($_POST['email']);
          if ($oUser && password_verify($_POST['password'], $oUser->password))
          {
              $_SESSION['is_logged'] = 1;
              $_SESSION['user_id'] = $oUser->id; // Store user_id in session
              $_SESSION['name'] = $oUser->name;
              $_SESSION['role'] = $oUser->role;

              header('Location: ' . ROOT_URL . '?p=admin&a=dashboard');
              exit;
          }
          else
          {
              $this->oUtil->sErrMsg = 'Incorrect Login!';
          }
      }

      $this->oUtil->getView('login');
  }

    public function logout()
    {
        if (!isset($_SESSION['is_logged']))
            exit;

        if (!empty($_SESSION)) {
            $_SESSION = array();
            session_unset();
            session_destroy();
        }

        header('Location: ' . ROOT_URL);
        exit;
    }

    public function dashboard()
    {
        // Check if the user is logged in and is an admin
        if (!isset($_SESSION['is_logged']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . ROOT_URL); // Redirect non-admins to the home page
            exit;
        }

        $this->oUtil->getView('dashboard');
    }
}
