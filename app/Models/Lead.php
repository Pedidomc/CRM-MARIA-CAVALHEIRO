<?php
class Lead
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function allByUser(int $userId, string $role = 'user'): array
    {
        if ($role === 'admin') {
            $stmt = $this->db->query('SELECT leads.*, stages.name as stage_name FROM leads LEFT JOIN stages ON leads.stage_id = stages.id ORDER BY leads.updated_at DESC');
            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare('SELECT leads.*, stages.name as stage_name FROM leads LEFT JOIN stages ON leads.stage_id = stages.id WHERE leads.user_id = :user_id ORDER BY leads.updated_at DESC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function find(int $id, int $userId, string $role = 'user'): ?array
    {
        if ($role === 'admin') {
            $stmt = $this->db->prepare('SELECT * FROM leads WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => $id]);
        } else {
            $stmt = $this->db->prepare('SELECT * FROM leads WHERE id = :id AND user_id = :user_id LIMIT 1');
            $stmt->execute(['id' => $id, 'user_id' => $userId]);
        }

        $lead = $stmt->fetch();
        return $lead ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO leads (user_id, name, contact_name, phone, email, instagram, city, state, cnpj, status, stage_id, origin, notes, created_at, updated_at) VALUES (:user_id, :name, :contact_name, :phone, :email, :instagram, :city, :state, :cnpj, :status, :stage_id, :origin, :notes, NOW(), NOW())');
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'contact_name' => $data['contact_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'instagram' => $data['instagram'],
            'city' => $data['city'],
            'state' => $data['state'],
            'cnpj' => $data['cnpj'] ?? null,
            'status' => $data['status'],
            'stage_id' => $data['stage_id'],
            'origin' => $data['origin'],
            'notes' => $data['notes'],
        ]);
    }

    public function update(array $data, int $userId, string $role = 'user'): bool
    {
        $lead = $this->find($data['id'], $userId, $role);
        if (!$lead) {
            return false;
        }

        $stmt = $this->db->prepare('UPDATE leads SET name = :name, contact_name = :contact_name, phone = :phone, email = :email, instagram = :instagram, city = :city, state = :state, cnpj = :cnpj, status = :status, stage_id = :stage_id, origin = :origin, notes = :notes, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'name' => $data['name'],
            'contact_name' => $data['contact_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'instagram' => $data['instagram'],
            'city' => $data['city'],
            'state' => $data['state'],
            'cnpj' => $data['cnpj'] ?? null,
            'status' => $data['status'],
            'stage_id' => $data['stage_id'],
            'origin' => $data['origin'],
            'notes' => $data['notes'],
            'id' => $data['id'],
        ]);
    }

    public function move(int $id, int $stageId, int $userId, string $role = 'user'): bool
    {
        $lead = $this->find($id, $userId, $role);
        if (!$lead) {
            return false;
        }

        $stmt = $this->db->prepare('UPDATE leads SET stage_id = :stage_id, updated_at = NOW() WHERE id = :id');
        return $stmt->execute(['stage_id' => $stageId, 'id' => $id]);
    }
}
