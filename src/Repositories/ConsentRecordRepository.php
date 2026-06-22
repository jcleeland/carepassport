<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class ConsentRecordRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @param array<string, mixed> $acknowledgementData
     */
    public function create(
        int $residentId,
        int $completionModeId,
        ?string $helperName,
        ?string $helperRelationship,
        string $consentTextVersion,
        array $acknowledgementData,
    ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO consent_records (
                resident_id,
                completion_mode_id,
                helper_name,
                helper_relationship,
                acknowledgement_data,
                consent_acknowledged_at,
                consent_text_version
            ) VALUES (
                :resident_id,
                :completion_mode_id,
                :helper_name,
                :helper_relationship,
                :acknowledgement_data,
                NOW(),
                :consent_text_version
            )',
        );
        $statement->execute([
            'resident_id' => $residentId,
            'completion_mode_id' => $completionModeId,
            'helper_name' => $helperName,
            'helper_relationship' => $helperRelationship,
            'acknowledgement_data' => json_encode($acknowledgementData, JSON_THROW_ON_ERROR),
            'consent_text_version' => $consentTextVersion,
        ]);
    }
}
