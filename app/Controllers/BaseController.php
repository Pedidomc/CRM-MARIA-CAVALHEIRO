<?php
class BaseController
{
    protected PDO $db;
    protected array $config;

    public function __construct(PDO $db, array $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../Views/' . $view . '.php';
    }

    protected function redirect(string $route): void
    {
        $base = $this->config['app']['base_url'] ?? '/';
        header('Location: ' . $base . $route);
        exit;
    }

    protected function requireAuth(): array
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('index.php');
        }
        return $_SESSION['user'];
    }
}
