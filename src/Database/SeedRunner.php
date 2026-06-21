<?php

declare(strict_types=1);

namespace CarePassport\Database;

use PDO;

final class SeedRunner
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $path,
    ) {
    }

    /**
     * @return list<string>
     */
    public function run(): array
    {
        $ran = [];

        foreach (self::sqlFiles($this->path) as $file) {
            $sql = trim((string) file_get_contents($file));

            if ($sql === '') {
                continue;
            }

            $this->pdo->exec($sql);
            $ran[] = basename($file);
        }

        return $ran;
    }

    /**
     * @return list<string>
     */
    public static function sqlFiles(string $path): array
    {
        $files = glob(rtrim($path, '/') . '/*.sql') ?: [];
        sort($files, SORT_NATURAL);

        return array_values($files);
    }
}
