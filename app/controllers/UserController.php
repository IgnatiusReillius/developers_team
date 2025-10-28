<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class UserController extends ApplicationController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    public function signupAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirmPassword'] ?? '');

            $isValid = $this->validations($name, $email, $password, $confirmPassword);

            if (!$isValid) {
                return;
            }
            $userCreated = $this->userModel->createUsers($email, $password, $name); 
            if (!$userCreated) {
                $this->view->error = "Email duplicado.";
                return;
            }

            $user = $this->findUserByEmail($email);
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];
            header('Location:  ' . BASE_URL . '/registeredUser');
            exit;
        }
    }
    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $user = $this->findUserByEmail($email);
            if ($user && isset($user['password']) && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name'  => $user['name']
                ];
                header('Location: ' . BASE_URL . '/home');
                exit;
            }
        

            $this->view->error = "Email o contraseña incorrectos.";   // manda mensaje si el correo o la contraseña a la hora de iniciar session son incorrectos
        }
    }
    public function homeAction()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->view->user = $_SESSION['user'];
    }

    public function logoutAction()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
    public function validations(string $name, string $email, string $password, string $confirmPassword): bool
    {
        if (strlen($name) < 3) {
            $this->view->error = "Nombre inválido.";
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view->error = "Email inválido.";
            return false;
        }

        if (strlen($password) < 6) {
            $this->view->error = "Contraseña muy corta.";
            return false;
        }
        if ($password !== $confirmPassword) {
            $this->view->error = "Las contraseñas no coinciden";
            return false;
        }
        return true;
    }
    private function findUserByEmail(string $email): ?array
    {
        foreach ($this->userModel->getAllUsers() as $user) {
            if (isset($user['email']) && $user['email'] === $email) {
                return $user;
            }
        }
        return null;
    }
}
