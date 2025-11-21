<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Stage.php';
require_once __DIR__ . '/../Models/Lead.php';

class DashboardController extends BaseController
{
    public function index(): void
    {
        $user = $this->requireAuth();
        $stageModel = new Stage($this->db);
        $leadModel = new Lead($this->db);

        $stages = $stageModel->all($user['id'], $user['role']);
        if (empty($stages)) {
            $defaultStages = ['Novo', 'Contato Iniciado', 'Catálogo Enviado', 'Em Negociação', 'Pedido Realizado', 'Inativo'];
            foreach ($defaultStages as $index => $name) {
                $stageModel->create([
                    'user_id' => $user['id'],
                    'name' => $name,
                    'position' => $index,
                ]);
            }
            $stages = $stageModel->all($user['id'], $user['role']);
        }

        $leads = $leadModel->allByUser($user['id'], $user['role']);
        $grouped = [];
        foreach ($stages as $stage) {
            $grouped[$stage['id']] = [];
        }
        foreach ($leads as $lead) {
            if (!isset($grouped[$lead['stage_id']])) {
                $grouped[$lead['stage_id']] = [];
            }
            $grouped[$lead['stage_id']][] = $lead;
        }

        $this->render('dashboard/index', [
            'user' => $user,
            'stages' => $stages,
            'leadsByStage' => $grouped,
        ]);
    }
}
