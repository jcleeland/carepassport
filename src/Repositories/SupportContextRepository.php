<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class SupportContextRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<array{slug:string,label:string}>
     */
    public function active(): array
    {
        $statement = $this->pdo->query(
            'SELECT slug, label FROM support_contexts WHERE active = 1 ORDER BY sort_order ASC, label ASC',
        );

        return $statement->fetchAll();
    }

    public function exists(string $slug): bool
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM support_contexts WHERE slug = :slug AND active = 1');
        $statement->execute(['slug' => $slug]);

        return (int) $statement->fetchColumn() > 0;
    }
}
