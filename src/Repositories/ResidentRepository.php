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
    public function create(?int $temporarySessionId, ?int $userId, array $data): int
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO residents (
                user_id,
                temporary_session_id,
                full_name,
                preferred_name,
                support_context,
                service_location_name,
                location_reference,
                primary_supporter_name,
                notes
            ) VALUES (
                :user_id,
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
            'user_id' => $userId,
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
        $this->updateAccessible($residentId, $temporarySessionId, null, $data);
    }

    /**
     * @param array<string, string> $data
     */
    public function updateAccessible(int $residentId, ?int $temporarySessionId, ?int $userId, array $data): void
    {
        $accessSql = self::accessSql($temporarySessionId, $userId);

        $statement = $this->pdo->prepare(
            'UPDATE residents
                SET full_name = :full_name,
                    preferred_name = :preferred_name,
                    support_context = :support_context,
                    service_location_name = :service_location_name,
                    location_reference = :location_reference,
                    primary_supporter_name = :primary_supporter_name,
                    notes = :notes
                WHERE id = :id AND (' . $accessSql['sql'] . ')',
        );
        $statement->execute($accessSql['params'] + [
            'id' => $residentId,
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

    /**
     * @return array<string, mixed>|null
     */
    public function findAccessible(int $residentId, ?int $temporarySessionId, ?int $userId): ?array
    {
        $accessSql = self::accessSql($temporarySessionId, $userId);
        $statement = $this->pdo->prepare(
            'SELECT * FROM residents WHERE id = :id AND (' . $accessSql['sql'] . ') LIMIT 1',
        );
        $statement->execute($accessSql['params'] + ['id' => $residentId]);
        $resident = $statement->fetch();

        return is_array($resident) ? $resident : null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function forUser(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT *
            FROM residents
            WHERE user_id = :user_id
            ORDER BY updated_at DESC, created_at DESC, id DESC',
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function dashboardForUser(int $userId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                resident.*,
                path.title AS question_path_title,
                COALESCE(path_counts.question_count, 0) AS question_count,
                COALESCE(answer_counts.answer_count, 0) AS answer_count,
                COALESCE(consent_counts.consent_count, 0) AS consent_count,
                CASE WHEN portrait.id IS NULL THEN 0 ELSE 1 END AS has_portrait,
                COALESCE(poster_counts.poster_answer_count, 0) AS poster_answer_count
            FROM residents AS resident
            LEFT JOIN question_paths AS path
                ON path.id = resident.question_path_id
            LEFT JOIN (
                SELECT question_path_id, COUNT(*) AS question_count
                FROM question_path_questions
                GROUP BY question_path_id
            ) AS path_counts
                ON path_counts.question_path_id = resident.question_path_id
            LEFT JOIN (
                SELECT answer.resident_id, COUNT(*) AS answer_count
                FROM answers AS answer
                GROUP BY answer.resident_id
            ) AS answer_counts
                ON answer_counts.resident_id = resident.id
            LEFT JOIN (
                SELECT resident_id, COUNT(*) AS consent_count
                FROM consent_records
                GROUP BY resident_id
            ) AS consent_counts
                ON consent_counts.resident_id = resident.id
            LEFT JOIN (
                SELECT resident_id, MAX(id) AS id
                FROM photos
                WHERE type = :portrait_type
                GROUP BY resident_id
            ) AS portrait
                ON portrait.resident_id = resident.id
            LEFT JOIN (
                SELECT answer.resident_id, COUNT(*) AS poster_answer_count
                FROM answers AS answer
                INNER JOIN poster_mappings AS mapping
                    ON mapping.question_id = answer.question_id
                INNER JOIN output_template_zones AS zone
                    ON zone.id = mapping.output_template_zone_id
                INNER JOIN output_templates AS template
                    ON template.id = zone.output_template_id
                WHERE template.slug = :poster_template_slug
                    AND template.active = 1
                    AND zone.active = 1
                    AND answer.visibility = :poster_visibility
                    AND answer.skipped = 0
                    AND answer.answer_text IS NOT NULL
                    AND TRIM(answer.answer_text) <> \'\'
                GROUP BY answer.resident_id
            ) AS poster_counts
                ON poster_counts.resident_id = resident.id
            WHERE resident.user_id = :user_id
            ORDER BY resident.updated_at DESC, resident.created_at DESC, resident.id DESC',
        );
        $statement->execute([
            'user_id' => $userId,
            'portrait_type' => 'portrait',
            'poster_template_slug' => 'poster_a',
            'poster_visibility' => 'poster',
        ]);

        return $statement->fetchAll();
    }

    public function attachToUserIfSafe(int $residentId, int $temporarySessionId, int $userId): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE residents
            SET user_id = :user_id
            WHERE id = :id
                AND temporary_session_id = :temporary_session_id
                AND (user_id IS NULL OR user_id = :existing_user_id)',
        );
        $statement->execute([
            'id' => $residentId,
            'temporary_session_id' => $temporarySessionId,
            'user_id' => $userId,
            'existing_user_id' => $userId,
        ]);

        return $statement->rowCount() > 0;
    }

    public function setQuestionPath(int $residentId, int $temporarySessionId, int $questionPathId): void
    {
        $this->setQuestionPathAccessible($residentId, $temporarySessionId, null, $questionPathId);
    }

    public function setQuestionPathAccessible(
        int $residentId,
        ?int $temporarySessionId,
        ?int $userId,
        int $questionPathId,
    ): void {
        $accessSql = self::accessSql($temporarySessionId, $userId);
        $statement = $this->pdo->prepare(
            'UPDATE residents
                SET question_path_id = :question_path_id
                WHERE id = :id AND (' . $accessSql['sql'] . ')',
        );
        $statement->execute($accessSql['params'] + [
            'id' => $residentId,
            'question_path_id' => $questionPathId,
        ]);
    }

    private static function nullable(string $value): ?string
    {
        return $value !== '' ? $value : null;
    }

    /**
     * @return array{sql:string,params:array<string,int>}
     */
    private static function accessSql(?int $temporarySessionId, ?int $userId): array
    {
        $clauses = [];
        $params = [];

        if ($userId !== null && $userId > 0) {
            $clauses[] = 'user_id = :access_user_id';
            $params['access_user_id'] = $userId;
        }

        if ($temporarySessionId !== null && $temporarySessionId > 0) {
            $clauses[] = 'temporary_session_id = :access_temporary_session_id';
            $params['access_temporary_session_id'] = $temporarySessionId;
        }

        if ($clauses === []) {
            return ['sql' => '0 = 1', 'params' => []];
        }

        return ['sql' => implode(' OR ', $clauses), 'params' => $params];
    }
}
