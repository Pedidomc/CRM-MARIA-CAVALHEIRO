<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow rounded-lg p-8 w-full max-w-md">
        <h1 class="text-2xl font-bold text-center text-purple-700 mb-6">Recuperar senha</h1>
        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (!empty($message)): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="index.php?route=forgot" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-600">Email cadastrado</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700">Enviar link</button>
        </form>
        <div class="text-sm text-center text-gray-600 mt-4">
            <a href="index.php?route=login" class="text-purple-600">Voltar para login</a>
        </div>
    </div>
</body>
</html>
