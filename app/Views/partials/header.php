<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maria Cavalheiro CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="text-xl font-semibold text-purple-700">Maria Cavalheiro CRM</div>
            <nav class="flex items-center space-x-4 text-sm">
                <a class="text-gray-700 hover:text-purple-700" href="index.php?route=dashboard">Dashboard</a>
                <a class="text-gray-700 hover:text-purple-700" href="index.php?route=profile">Perfil</a>
                <span class="text-gray-500">|</span>
                <span class="text-gray-600">Olá, <?php echo htmlspecialchars($_SESSION['user']['name'] ?? ''); ?></span>
                <a class="text-red-500 hover:text-red-600" href="index.php?route=logout">Sair</a>
            </nav>
        </div>
    </header>
    <main class="max-w-6xl mx-auto px-4 py-6">
