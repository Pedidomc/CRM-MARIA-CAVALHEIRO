<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="max-w-3xl mx-auto bg-white border rounded-lg shadow-sm p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Perfil</h1>
    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <form action="index.php?route=profile" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm text-gray-600">Nome</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600">Email</label>
            <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full border rounded px-3 py-2 bg-gray-100" disabled>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-600">Senha atual</label>
                <input type="password" name="current_password" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Nova senha</label>
                <input type="password" name="new_password" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div class="flex justify-end">
            <button class="px-4 py-2 bg-purple-600 text-white rounded">Salvar alterações</button>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
