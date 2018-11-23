<?php
// TODO Use a namespace

class LoginController
{
    protected $userPasswords = [];

    public function __construct($users)
    {
        session_start();
        foreach ($users as $user) {
            $this->userPasswords[$user['login']] = $user['password'];
        }
    }

    public function loginAction()
    {
        if (
            (true === array_key_exists($_POST['login'], $this->userPasswords))
            && ($_POST['password'] === $this->userPasswords[$_POST['login']])
            && (strlen($this->userPasswords[$_POST['login']]) > 0)
        )
        {
            $_SESSION['userLogin'] = $_POST['login'];
            echo 'Authentication successful !';
        } else {
            require_once(__DIR__.'/../view/login_form.phtml');
        }
    }

    public function logoutAction()
    {
        $_SESSION['userLogin'] = null;
        unset($_SESSION['userLogin']);
    }
}
