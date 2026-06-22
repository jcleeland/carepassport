<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class ResidentRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @param array<string, string> $data
     */
    public function create(int $temporarySessionId, array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO residents (
                temporary_session_id,
                full_name,
                preferred_name,
                support_context,
                service_location_name,
                location_reference,
                primary_supporter_name,
                notes
            ) VALUES (
                :temporary_session_id,
                :full_name,
                :preferred_name,
                :support_context,
                :service_location_name,
                :location_reference,
                :primary_supporter_name,
                :notes
            )',
        );
        $statement->execute([
            'temporary_session_id' => $temporarySessionId,
            'full_name' => $data['full_name'],
            'preferred_name' => self::nullable($data['preferred_name']),
            'support_context' => self::nullable($data['support_context']),
            'service_location_name' => self::nullable($data['service_location_name']),
            'location_reference' => self::nullable($data['location_reference']),
            'primary_supporter_name' => self::nullable($data['primary_supporter_name']),
            'notes' => self::nullable($data['notes']),
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, string> $data
     */
    public function update(int $residentId, int $temporarySessionId, array $data): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE residents
                SET full_name = :full_name,
                    preferred_name = :preferred_name,
                    support_context = :support_context,
                    service_location_name = :service_location_name,
                    location_reference = :location_reference,
                    primary_supporter_name = :primary_supporter_name,
                    notes = :notes
                WHERE id = :id AND temporary_session_id = :temporary_session_id',
        );
        $statement->execute([
            'id' => $residentId,
            'temporary_session_id' => $temporarySessionId,
            'full_name' => $data['full_name'],
            'preferred_name' => self::nullable($data['preferred_name']),
            'support_context' => self::nullable($data['support_context']),
            'service_location_name' => self::nullable($data['service_location_name']),
            'location_reference' => self::nullable($data['location_reference']),
            'primary_supporter_name' => self::nullable($data['primary_supporter_name']),
            'notes' => self::nullable($data['notes']),
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findForTemporarySession(int $residentId, int $temporarySessionId): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM residents WHERE id = :id AND temporary_session_id = :temporary_session_id LIMIT 1',
        );
        $statement->execute([
            'id' => $residentId,
            'temporary_session_id' => $temporarySessionId,
        ]);

        $resident = $statement->fetch();

        return is_array($resident) ? $resident : null;
    }

    public function setQuestionPath(int $residentId, int $temporarySessionId, int $questionPathId): void
    {
        $statement = $this->pdo->prepare(
            'UPDATE residents
                SET question_path_id = :question_path_id
                WHERE id = :id AND temporary_session_id = :temporary_session_id',
        );
        $statement->execute([
            'id' => $residentId,
            'temporary_session_id' => $temporarySessionId,
            'question_path_id' => $questionPathId,
        ]);
    }

    private static function nullable(string $value): ?string
    {
        return $value !== '' ? $value : null;
    }
}
