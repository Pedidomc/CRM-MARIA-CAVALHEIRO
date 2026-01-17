<?php
class Stage
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all(int $userId, string $role = 'user'): array
    {
        if ($role === 'admin') {
            $stmt = $this->db->query('SELECT * FROM stages ORDER BY position ASC');
            return $stmt->fetchAll();
        }
        $stmt = $this->db->prepare('SELECT * FROM stages WHERE user_id = :user_id ORDER BY position ASC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM stages WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $stage = $stmt->fetch();
        return $stage ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO stages (user_id, name, position, created_at, updated_at) VALUES (:user_id, :name, :position, NOW(), NOW())');
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
            'position' => $data['position'] ?? 0,
        ]);
    }

    public function update(array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE stages SET name = :name, position = :position, updated_at = NOW() WHERE id = :id');
        return $stmt->execute([
            'name' => $data['name'],
            'position' => $data['position'],
            'id' => $data['id'],
        ]);
    }

    public function delete(int $id, int $userId, string $role = 'user'): bool
    {
        if ($role === 'admin') {
            $stmt = $this->db->prepare('DELETE FROM stages WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        }

        $stmt = $this->db->prepare('DELETE FROM stages WHERE id = :id AND user_id = :user_id');
        return $stmt->execute(['id' => $id, 'user_id' => $userId]);
    }
}
