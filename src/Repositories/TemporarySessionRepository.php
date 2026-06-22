<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use DateTimeImmutable;
use PDO;

final class TemporarySessionRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array{id:int,token:string}
     */
    public function create(): array
    {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expiresAt = (new DateTimeImmutable('+7 days'))->format('Y-m-d H:i:s');

        $statement = $this->pdo->prepare(
            'INSERT INTO temporary_sessions (session_token_hash, expires_at) VALUES (:hash, :expires_at)',
        );
        $statement->execute([
            'hash' => $hash,
            'expires_at' => $expiresAt,
        ]);

        return [
            'id' => (int) $this->pdo->lastInsertId(),
            'token' => $token,
        ];
    }

    public function findValidIdByToken(?string $token): ?int
    {
        if ($token === null || $token === '') {
            return null;
        }

        $statement = $this->pdo->prepare(
            'SELECT id FROM temporary_sessions WHERE session_token_hash = :hash AND expires_at > NOW() LIMIT 1',
        );
        $statement->execute(['hash' => hash('sha256', $token)]);
        $id = $statement->fetchColumn();

        return $id !== false ? (int) $id : null;
    }
}
