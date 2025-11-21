<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/User.php';

class ProfileController extends BaseController
{
    public function show(): void
    {
        $user = $this->requireAuth();
        $this->render('profile/show', ['user' => $user]);
    }

    public function update(): void
    {
        $user = $this->requireAuth();
        $name = trim($_POST['name'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';

        $userModel = new User($this->db);
        $dbUser = $userModel->find($user['id']);
        $error = null;

        if ($name) {
            $userModel->updateName($user['id'], $name);
            $_SESSION['user']['name'] = $name;
        }

        if ($currentPassword && $newPassword) {
            if (password_verify($currentPassword, $dbUser['password'])) {
                $userModel->updatePassword($user['id'], $newPassword);
            } else {
                $error = 'Senha atual inválida.';
            }
        }

        $this->render('profile/show', ['user' => $_SESSION['user'], 'error' => $error, 'success' => $error ? null : 'Perfil atualizado.']);
    }
}
