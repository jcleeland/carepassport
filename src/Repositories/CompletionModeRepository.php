<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class CompletionModeRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<array{id:int,slug:string,label:string,description:string|null}>
     */
    public function active(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, slug, label, description FROM completion_modes WHERE active = 1 ORDER BY sort_order ASC, label ASC',
        );

        return $statement->fetchAll();
    }

    /**
     * @return array{id:int,slug:string,label:string,description:string|null}|null
     */
    public function findActiveBySlug(string $slug): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, slug, label, description FROM completion_modes WHERE slug = :slug AND active = 1 LIMIT 1',
        );
        $statement->execute(['slug' => $slug]);
        $mode = $statement->fetch();

        return is_array($mode) ? $mode : null;
    }
}
