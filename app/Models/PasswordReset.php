<?php
class PasswordReset
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(int $userId, string $token, string $expiresAt): bool
    {
        $stmt = $this->db->prepare('INSERT INTO password_resets (user_id, token, expires_at, created_at) VALUES (:user_id, :token, :expires_at, NOW())');
        return $stmt->execute(['user_id' => $userId, 'token' => $token, 'expires_at' => $expiresAt]);
    }

    public function findValid(string $token): ?array
    {
        $stmt = $this->db->prepare('SELECT pr.*, u.email FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE token = :token AND expires_at > NOW() LIMIT 1');
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function deleteToken(string $token): bool
    {
        $stmt = $this->db->prepare('DELETE FROM password_resets WHERE token = :token');
        return $stmt->execute(['token' => $token]);
    }
}
