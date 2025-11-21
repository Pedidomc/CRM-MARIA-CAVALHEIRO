<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Lead.php';

class LeadController extends BaseController
{
    public function store(): void
    {
        $user = $this->requireAuth();
        $leadModel = new Lead($this->db);

        $leadModel->create([
            'user_id' => $user['id'],
            'name' => trim($_POST['name'] ?? ''),
            'contact_name' => trim($_POST['contact_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'instagram' => trim($_POST['instagram'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'cnpj' => trim($_POST['cnpj'] ?? ''),
            'status' => trim($_POST['status'] ?? ''),
            'stage_id' => (int)($_POST['stage_id'] ?? 0),
            'origin' => trim($_POST['origin'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ]);

        $this->redirect('index.php?route=dashboard');
    }

    public function update(): void
    {
        $user = $this->requireAuth();
        $leadModel = new Lead($this->db);
        $data = [
            'id' => (int)($_POST['id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'contact_name' => trim($_POST['contact_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'instagram' => trim($_POST['instagram'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'cnpj' => trim($_POST['cnpj'] ?? ''),
            'status' => trim($_POST['status'] ?? ''),
            'stage_id' => (int)($_POST['stage_id'] ?? 0),
            'origin' => trim($_POST['origin'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
        ];

        $leadModel->update($data, $user['id'], $user['role']);
        $this->redirect('index.php?route=dashboard');
    }

    public function move(): void
    {
        $user = $this->requireAuth();
        $id = (int)($_POST['id'] ?? 0);
        $stageId = (int)($_POST['stage_id'] ?? 0);

        $leadModel = new Lead($this->db);
        $success = $leadModel->move($id, $stageId, $user['id'], $user['role']);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }
}
