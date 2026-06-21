<?php

declare(strict_types=1);

namespace CarePassport\Database;

use PDO;

final class MigrationRunner
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly string $path,
    ) {
    }

    /**
     * @return list<array{file:string,status:string}>
     */
    public function status(): array
    {
        $this->ensureSchemaTable();
        $applied = $this->appliedMigrations();

        return array_map(
            static fn (string $file): array => [
                'file' => basename($file),
                'status' => in_array(basename($file), $applied, true) ? 'applied' : 'pending',
            ],
            self::sqlFiles($this->path),
        );
    }

    /**
     * @return list<string>
     */
    public function run(): array
    {
        $this->ensureSchemaTable();
        $applied = $this->appliedMigrations();
        $batch = $this->nextBatchNumber();
        $ran = [];

        foreach (self::sqlFiles($this->path) as $file) {
            $name = basename($file);

            if (in_array($name, $applied, true)) {
                continue;
            }

            $sql = trim((string) file_get_contents($file));

            if ($sql === '') {
                continue;
            }

            $this->pdo->exec($sql);

            $statement = $this->pdo->prepare(
                'INSERT INTO schema_migrations (migration, batch, applied_at) VALUES (:migration, :batch, NOW())',
            );
            $statement->execute([
                'migration' => $name,
                'batch' => $batch,
            ]);

            $ran[] = $name;
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

    private function ensureSchemaTable(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS schema_migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL UNIQUE,
                batch INT UNSIGNED NOT NULL,
                applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
        );
    }

    /**
     * @return list<string>
     */
    private function appliedMigrations(): array
    {
        $rows = $this->pdo
            ->query('SELECT migration FROM schema_migrations ORDER BY migration ASC')
            ->fetchAll(PDO::FETCH_COLUMN);

        return array_map('strval', $rows);
    }

    private function nextBatchNumber(): int
    {
        $batch = $this->pdo->query('SELECT MAX(batch) FROM schema_migrations')->fetchColumn();

        return ((int) $batch) + 1;
    }
}
