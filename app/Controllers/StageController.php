<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Stage.php';
require_once __DIR__ . '/../Models/Lead.php';

class StageController extends BaseController
{
    public function store(): void
    {
        $user = $this->requireAuth();
        $stageModel = new Stage($this->db);
        $stageModel->create([
            'user_id' => $user['id'],
            'name' => trim($_POST['name'] ?? ''),
            'position' => (int)($_POST['position'] ?? 0),
        ]);
        $this->redirect('index.php?route=dashboard');
    }

    public function update(): void
    {
        $user = $this->requireAuth();
        $stageModel = new Stage($this->db);
        $id = (int)($_POST['id'] ?? 0);
        $stage = $stageModel->find($id);
        if ($stage && ($stage['user_id'] == $user['id'] || $user['role'] === 'admin')) {
            $stageModel->update([
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'position' => (int)($_POST['position'] ?? 0),
            ]);
        }
        $this->redirect('index.php?route=dashboard');
    }

    public function delete(): void
    {
        $user = $this->requireAuth();
        $id = (int)($_POST['id'] ?? 0);
        $stageModel = new Stage($this->db);
        $leadModel = new Lead($this->db);
        $stage = $stageModel->find($id);

        if (!$stage || ($stage['user_id'] != $user['id'] && $user['role'] !== 'admin')) {
            $this->redirect('index.php?route=dashboard');
            return;
        }

        // Prevent deletion if leads exist in stage
        $leads = $leadModel->allByUser($user['id'], $user['role']);
        foreach ($leads as $lead) {
            if ((int)$lead['stage_id'] === $id) {
                $this->redirect('index.php?route=dashboard&error=stage_has_leads');
                return;
            }
        }

        $stageModel->delete($id, $user['id'], $user['role']);
        $this->redirect('index.php?route=dashboard');
    }
}
