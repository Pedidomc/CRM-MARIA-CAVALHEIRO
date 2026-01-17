<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kanban de Leads</h1>
        <p class="text-gray-500">Organize os atendimentos da Maria Cavalheiro</p>
    </div>
    <div class="flex space-x-2">
        <button id="openStageConfig" class="px-4 py-2 bg-white border border-purple-200 text-purple-700 rounded shadow-sm">Configurar Kanban</button>
        <button id="openLeadModal" class="px-4 py-2 bg-purple-600 text-white rounded shadow hover:bg-purple-700">Novo Lead</button>
    </div>
</div>

<div id="stageConfig" class="bg-white border rounded-lg p-4 mb-6 hidden">
    <h2 class="text-lg font-semibold text-gray-700 mb-3">Gerenciar Stages</h2>
    <form action="index.php?route=stage_store" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
        <div>
            <label class="block text-sm text-gray-600">Nome</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block text-sm text-gray-600">Posição</label>
            <input type="number" name="position" class="w-full border rounded px-3 py-2" value="0" min="0">
        </div>
        <button class="bg-purple-600 text-white py-2 rounded">Adicionar Stage</button>
    </form>
    <div class="mt-4 space-y-2">
        <?php foreach ($stages as $stage): ?>
            <form action="index.php?route=stage_update" method="POST" class="flex items-center space-x-2 bg-purple-50 border border-purple-100 rounded px-3 py-2">
                <input type="hidden" name="id" value="<?php echo (int)$stage['id']; ?>">
                <input type="text" name="name" value="<?php echo htmlspecialchars($stage['name']); ?>" class="flex-1 border rounded px-3 py-2">
                <input type="number" name="position" value="<?php echo (int)$stage['position']; ?>" class="w-24 border rounded px-3 py-2">
                <button class="px-3 py-2 bg-purple-600 text-white rounded">Salvar</button>
            </form>
            <form action="index.php?route=stage_delete" method="POST" class="flex items-center space-x-2 text-sm text-red-600">
                <input type="hidden" name="id" value="<?php echo (int)$stage['id']; ?>">
                <button type="submit">Excluir stage (apenas se vazio)</button>
            </form>
        <?php endforeach; ?>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($stages as $stage): ?>
        <div class="bg-white rounded-lg border shadow-sm flex flex-col">
            <div class="px-4 py-3 border-b flex items-center justify-between">
                <h3 class="font-semibold text-gray-800"><?php echo htmlspecialchars($stage['name']); ?></h3>
            </div>
            <div class="p-3 space-y-3 min-h-[120px]" data-stage-id="<?php echo (int)$stage['id']; ?>">
                <?php foreach ($leadsByStage[$stage['id']] ?? [] as $lead): ?>
                    <div class="bg-purple-50 border border-purple-100 rounded p-3 shadow-sm cursor-move lead-card" draggable="true" data-lead-id="<?php echo (int)$lead['id']; ?>">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-purple-800"><?php echo htmlspecialchars($lead['name']); ?></h4>
                                <p class="text-sm text-gray-600">Contato: <?php echo htmlspecialchars($lead['contact_name']); ?></p>
                                <p class="text-sm text-gray-600">Cidade: <?php echo htmlspecialchars($lead['city']); ?> / <?php echo htmlspecialchars($lead['state']); ?></p>
                            </div>
                            <button class="text-purple-600 text-sm open-edit" data-lead='<?php echo json_encode($lead); ?>'>Editar</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Lead Modal -->
<div id="leadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <button id="closeLeadModal" class="absolute right-4 top-4 text-gray-500">Fechar</button>
        <h3 class="text-xl font-semibold text-gray-800 mb-4" id="leadModalTitle">Novo Lead</h3>
        <form id="leadForm" action="index.php?route=lead_store" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="id" id="leadId">
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600">Nome da loja</label>
                <input type="text" name="name" id="leadName" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Contato</label>
                <input type="text" name="contact_name" id="leadContact" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm text-gray-600">WhatsApp</label>
                <input type="text" name="phone" id="leadPhone" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Email</label>
                <input type="email" name="email" id="leadEmail" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Instagram</label>
                <input type="text" name="instagram" id="leadInstagram" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Cidade</label>
                <input type="text" name="city" id="leadCity" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Estado</label>
                <input type="text" name="state" id="leadState" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">CNPJ</label>
                <input type="text" name="cnpj" id="leadCnpj" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Status</label>
                <input type="text" name="status" id="leadStatus" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Origem</label>
                <input type="text" name="origin" id="leadOrigin" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Stage</label>
                <select name="stage_id" id="leadStage" class="w-full border rounded px-3 py-2">
                    <?php foreach ($stages as $stage): ?>
                        <option value="<?php echo (int)$stage['id']; ?>"><?php echo htmlspecialchars($stage['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-600">Notas</label>
                <textarea name="notes" id="leadNotes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
            </div>
            <div class="md:col-span-2 flex justify-end space-x-2">
                <button type="button" id="cancelLead" class="px-4 py-2 border rounded">Cancelar</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
    const stageConfig = document.getElementById('stageConfig');
    document.getElementById('openStageConfig').addEventListener('click', () => {
        stageConfig.classList.toggle('hidden');
    });

    const modal = document.getElementById('leadModal');
    const openModalBtn = document.getElementById('openLeadModal');
    const closeModalBtn = document.getElementById('closeLeadModal');
    const cancelLead = document.getElementById('cancelLead');
    const form = document.getElementById('leadForm');
    const title = document.getElementById('leadModalTitle');

    function openModal(editData = null) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        form.reset();
        document.getElementById('leadId').value = '';
        form.action = 'index.php?route=lead_store';
        title.textContent = 'Novo Lead';
        if (editData) {
            title.textContent = 'Editar Lead';
            form.action = 'index.php?route=lead_update';
            document.getElementById('leadId').value = editData.id;
            document.getElementById('leadName').value = editData.name;
            document.getElementById('leadContact').value = editData.contact_name;
            document.getElementById('leadPhone').value = editData.phone;
            document.getElementById('leadEmail').value = editData.email;
            document.getElementById('leadInstagram').value = editData.instagram;
            document.getElementById('leadCity').value = editData.city;
            document.getElementById('leadState').value = editData.state;
            document.getElementById('leadCnpj').value = editData.cnpj;
            document.getElementById('leadStatus').value = editData.status;
            document.getElementById('leadOrigin').value = editData.origin;
            document.getElementById('leadStage').value = editData.stage_id;
            document.getElementById('leadNotes').value = editData.notes;
        }
    }

    openModalBtn.addEventListener('click', () => openModal());
    closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
    cancelLead.addEventListener('click', () => modal.classList.add('hidden'));

    document.querySelectorAll('.open-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const lead = JSON.parse(btn.getAttribute('data-lead'));
            openModal(lead);
        });
    });

    const cards = document.querySelectorAll('.lead-card');
    cards.forEach(card => {
        card.addEventListener('dragstart', (e) => {
            e.dataTransfer.setData('text/plain', card.getAttribute('data-lead-id'));
        });
    });

    document.querySelectorAll('[data-stage-id]').forEach(column => {
        column.addEventListener('dragover', (e) => {
            e.preventDefault();
            column.classList.add('bg-purple-50');
        });
        column.addEventListener('dragleave', () => {
            column.classList.remove('bg-purple-50');
        });
        column.addEventListener('drop', (e) => {
            e.preventDefault();
            column.classList.remove('bg-purple-50');
            const leadId = e.dataTransfer.getData('text/plain');
            column.appendChild(document.querySelector(`[data-lead-id="${leadId}"]`));
            fetch('index.php?route=lead_move', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `id=${leadId}&stage_id=${column.getAttribute('data-stage-id')}`
            });
        });
    });
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
