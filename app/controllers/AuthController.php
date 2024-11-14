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
        $errors = []; // Масив для зберігання помилок

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirm_password']);

            // Перевірка полів
            if (empty($username)) {
                $errors['name'] = "Ім'я користувача не може бути порожнім.";
            }

            if (empty($email)) {
                $errors['email'] = "Електронна пошта не може бути порожньою.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Невірний формат електронної пошти.";
            }

            if (empty($password)) {
                $errors['password'] = "Пароль не може бути порожнім.";
            }

            if (empty($confirmPassword)) {
                $errors['confirm_password'] = "Підтвердження пароля не може бути порожнім.";
            } elseif ($password !== $confirmPassword) {
                $errors['confirm_password'] = "Паролі не співпадають.";
            }

            // Якщо помилок немає, реєструємо користувача
            if (empty($errors)) {
                if ($this->user->register($username, $email, $password)) {
                    header("Location: ?url=auth/login");
                    exit;
                } else {
                    $errors['general'] = "Помилка при реєстрації.";
                }
            }
        }

        // Відображаємо форму з помилками
        $this->view('auth/register', ['errors' => $errors]);
    }


    public function login()
    {
        $errors = []; // Масив для зберігання помилок

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Перевірка полів
            if (empty($email)) {
                $errors['email'] = "Електронна пошта не може бути порожньою.";
            }

            if (empty($password)) {
                $errors['password'] = "Пароль не може бути порожнім.";
            }

            // Якщо немає помилок, продовжуємо з логіном
            if (empty($errors)) {
                $user = $this->user->login($email, $password);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    header("Location: ?url=recipes/index");
                    exit;
                } else {
                    $errors['general'] = "Неправильний логін або пароль.";
                }
            }
        }

        // Відображаємо форму з помилками
        $this->view('auth/login', ['errors' => $errors]);
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
