<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class PhotoRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array{id:int,resident_id:int,type:string,original_file_path:string,processed_file_path:string|null,caption:string|null}|null
     */
    public function portraitForResident(int $residentId): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, resident_id, type, original_file_path, processed_file_path, caption
            FROM photos
            WHERE resident_id = :resident_id AND type = :type
            ORDER BY id DESC
            LIMIT 1',
        );
        $statement->execute([
            'resident_id' => $residentId,
            'type' => 'portrait',
        ]);
        $photo = $statement->fetch();

        if (! is_array($photo)) {
            return null;
        }

        return [
            'id' => (int) $photo['id'],
            'resident_id' => (int) $photo['resident_id'],
            'type' => (string) $photo['type'],
            'original_file_path' => (string) $photo['original_file_path'],
            'processed_file_path' => $photo['processed_file_path'] !== null ? (string) $photo['processed_file_path'] : null,
            'caption' => $photo['caption'] !== null ? (string) $photo['caption'] : null,
        ];
    }

    public function replacePortrait(int $residentId, string $originalPath, string $processedPath): void
    {
        $this->pdo->beginTransaction();

        $delete = $this->pdo->prepare('DELETE FROM photos WHERE resident_id = :resident_id AND type = :type');
        $delete->execute([
            'resident_id' => $residentId,
            'type' => 'portrait',
        ]);

        $insert = $this->pdo->prepare(
            'INSERT INTO photos (resident_id, type, original_file_path, processed_file_path)
            VALUES (:resident_id, :type, :original_file_path, :processed_file_path)',
        );
        $insert->execute([
            'resident_id' => $residentId,
            'type' => 'portrait',
            'original_file_path' => $originalPath,
            'processed_file_path' => $processedPath,
        ]);

        $this->pdo->commit();
    }
}
