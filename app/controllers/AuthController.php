<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/controllers/Controller.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/app/models/User.php';

class AuthController  extends  Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($password === $confirmPassword) {
                if ($this->user->register($username, $email, $password)) {
                    header("Location: ?url=auth/login");
                    exit;
                } else {
                    echo "Помилка при реєстрації.";
                }
            } else {
                echo "Паролі не співпадають.";
            }
        }
        $this->view('auth/register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->user->login($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: ?url=recipes/index");
                exit;
            } else {
                echo "Неправильний логін або пароль.";
            }
        }

        require_once $_SERVER['DOCUMENT_ROOT'] . '/app/views/auth/login.php';
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /");
        exit;
    }
}
