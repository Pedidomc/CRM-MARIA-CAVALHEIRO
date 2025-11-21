<?php
session_start();

$config = require __DIR__ . '/../config/config.php';
$pdo = require __DIR__ . '/../config/database.php';

$route = $_GET['route'] ?? 'home';

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/LeadController.php';
require_once __DIR__ . '/../app/Controllers/StageController.php';
require_once __DIR__ . '/../app/Controllers/ProfileController.php';

$authController = new AuthController($pdo, $config);
$dashboardController = new DashboardController($pdo, $config);
$leadController = new LeadController($pdo, $config);
$stageController = new StageController($pdo, $config);
$profileController = new ProfileController($pdo, $config);

if (!isset($_SESSION['user']) && !in_array($route, ['home', 'login', 'register', 'forgot', 'reset'])) {
    header('Location: index.php');
    exit;
}

switch ($route) {
    case 'home':
        if (isset($_SESSION['user'])) {
            header('Location: index.php?route=dashboard');
            exit;
        }
        include __DIR__ . '/../app/Views/landing.php';
        break;
    case 'login':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $authController->login() : $authController->showLogin();
        break;
    case 'register':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $authController->register() : $authController->showRegister();
        break;
    case 'forgot':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $authController->sendReset() : $authController->forgotPassword();
        break;
    case 'reset':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $authController->resetPassword() : $authController->resetPasswordForm();
        break;
    case 'dashboard':
        $dashboardController->index();
        break;
    case 'lead_store':
        $leadController->store();
        break;
    case 'lead_update':
        $leadController->update();
        break;
    case 'lead_move':
        $leadController->move();
        break;
    case 'stage_store':
        $stageController->store();
        break;
    case 'stage_update':
        $stageController->update();
        break;
    case 'stage_delete':
        $stageController->delete();
        break;
    case 'profile':
        $_SERVER['REQUEST_METHOD'] === 'POST' ? $profileController->update() : $profileController->show();
        break;
    case 'logout':
        $authController->logout();
        break;
    default:
        include __DIR__ . '/../app/Views/landing.php';
        break;
}
