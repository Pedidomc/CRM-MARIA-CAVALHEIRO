<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/PasswordReset.php';
require_once __DIR__ . '/BaseController.php';

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        $this->render('auth/login', ['title' => 'Login']);
    }

    public function showRegister(): void
    {
        $this->render('auth/register', ['title' => 'Criar conta']);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User($this->db);
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
            $this->redirect('index.php?route=dashboard');
        }

        $this->render('auth/login', ['error' => 'Credenciais inválidas.']);
    }

    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'user';

        if (!$name || !$email || !$password) {
            $this->render('auth/register', ['error' => 'Preencha todos os campos.']);
            return;
        }

        $userModel = new User($this->db);
        if ($userModel->findByEmail($email)) {
            $this->render('auth/register', ['error' => 'Email já cadastrado.']);
            return;
        }

        $userModel->create(['name' => $name, 'email' => $email, 'password' => $password, 'role' => $role]);
        $this->redirect('index.php?route=login');
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect('index.php');
    }

    public function forgotPassword(): void
    {
        $this->render('auth/forgot', ['title' => 'Esqueci minha senha']);
    }

    public function sendReset(): void
    {
        $email = trim($_POST['email'] ?? '');
        if (!$email) {
            $this->render('auth/forgot', ['error' => 'Informe seu email.']);
            return;
        }

        $userModel = new User($this->db);
        $user = $userModel->findByEmail($email);
        if (!$user) {
            $this->render('auth/forgot', ['error' => 'Email não encontrado.']);
            return;
        }

        $token = bin2hex(random_bytes(16));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $resetModel = new PasswordReset($this->db);
        $resetModel->create($user['id'], $token, $expiresAt);

        $resetLink = $this->config['app']['base_url'] . 'index.php?route=reset&token=' . $token;
        $message = 'Link de redefinição (simulado): <a class="text-purple-600" href="' . htmlspecialchars($resetLink) . '">' . htmlspecialchars($resetLink) . '</a>';
        $this->render('auth/forgot', ['message' => $message]);
    }

    public function resetPasswordForm(): void
    {
        $token = $_GET['token'] ?? '';
        $this->render('auth/reset', ['token' => $token]);
    }

    public function resetPassword(): void
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!$token || !$password) {
            $this->render('auth/reset', ['error' => 'Dados inválidos.', 'token' => $token]);
            return;
        }

        $resetModel = new PasswordReset($this->db);
        $reset = $resetModel->findValid($token);
        if (!$reset) {
            $this->render('auth/reset', ['error' => 'Token inválido ou expirado.', 'token' => $token]);
            return;
        }

        $userModel = new User($this->db);
        $userModel->updatePassword((int)$reset['user_id'], $password);
        $resetModel->deleteToken($token);
        $this->render('auth/login', ['message' => 'Senha redefinida com sucesso.']);
    }
}
