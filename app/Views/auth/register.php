<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar - Maria Cavalheiro CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-purple-700 mb-6">Criar conta</h1>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="index.php?route=register" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-600">Nome</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Senha</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Papel</label>
                <select name="role" class="w-full border rounded px-3 py-2">
                    <option value="user">Usuário</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700">Registrar</button>
        </form>
        <div class="text-sm text-center text-gray-600 mt-4">
            Já tem conta? <a href="index.php?route=login" class="text-purple-600">Faça login</a>
        </div>
    </div>
</body>
</html>
