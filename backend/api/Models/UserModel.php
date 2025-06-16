<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Database;

class UserModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function createUser(string $name, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $hash]);

        return (int) $this->pdo->lastInsertId();
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetch();
    }

    public function verifyUser(string $email, string $password): array
    {
        $user = $this->getUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return [];
        }

        return $user;
    }
}
