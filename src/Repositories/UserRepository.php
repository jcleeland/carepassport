<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class UserRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function create(string $name, string $email, string $passwordHash): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (name, email, password_hash)
            VALUES (:name, :email, :password_hash)',
        );
        $statement->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @return array{id:int,name:string|null,email:string|null,password_hash:string|null}|null
     */
    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, email, password_hash FROM users WHERE email = :email LIMIT 1',
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return is_array($user) ? [
            'id' => (int) $user['id'],
            'name' => $user['name'] !== null ? (string) $user['name'] : null,
            'email' => $user['email'] !== null ? (string) $user['email'] : null,
            'password_hash' => $user['password_hash'] !== null ? (string) $user['password_hash'] : null,
        ] : null;
    }
}
