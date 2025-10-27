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
