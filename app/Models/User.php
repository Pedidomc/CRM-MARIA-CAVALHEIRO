<?php
class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (:name, :email, :password, :role, NOW(), NOW())');
        return $stmt->execute([
            'name' => $data['name'],
            'email' => strtolower($data['email']),
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'user',
        ]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => strtolower($email)]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function updateName(int $id, string $name): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET name = :name, updated_at = NOW() WHERE id = :id');
        return $stmt->execute(['name' => $name, 'id' => $id]);
    }

    public function updatePassword(int $id, string $password): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id');
        return $stmt->execute(['password' => password_hash($password, PASSWORD_DEFAULT), 'id' => $id]);
    }
}
