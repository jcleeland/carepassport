<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class IntroPageRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<array{slug:string,title:string,body_markdown:string}>
     */
    public function active(): array
    {
        $statement = $this->pdo->query(
            'SELECT slug, title, body_markdown FROM intro_pages WHERE active = 1 ORDER BY sort_order ASC, title ASC',
        );

        return $statement->fetchAll();
    }
}
