<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class OutputRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return array{
     *     template:array{id:int,slug:string,title:string,description:string|null},
     *     zones:list<array{id:int,zone_key:string,label:string,sort_order:int,answers:list<array{question_id:int,canonical_number:int,answer_text:string}>}>
     * }|null
     */
    public function posterTemplateForResident(string $templateSlug, int $residentId): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                template.id AS template_id,
                template.slug AS template_slug,
                template.title AS template_title,
                template.description AS template_description,
                zone.id AS zone_id,
                zone.zone_key,
                zone.label AS zone_label,
                zone.sort_order AS zone_sort_order,
                question.id AS question_id,
                question.canonical_number,
                answer.answer_text
            FROM output_templates AS template
            INNER JOIN output_template_zones AS zone
                ON zone.output_template_id = template.id
            LEFT JOIN poster_mappings AS mapping
                ON mapping.output_template_zone_id = zone.id
            LEFT JOIN questions AS question
                ON question.id = mapping.question_id
                AND question.active = 1
            LEFT JOIN answers AS answer
                ON answer.question_id = question.id
                AND answer.resident_id = :resident_id
                AND answer.visibility = :visibility
                AND answer.skipped = 0
                AND answer.answer_text IS NOT NULL
                AND TRIM(answer.answer_text) <> \'\'
            WHERE template.slug = :template_slug
                AND template.active = 1
                AND zone.active = 1
            ORDER BY zone.sort_order ASC, mapping.sort_order ASC',
        );
        $statement->execute([
            'resident_id' => $residentId,
            'visibility' => 'poster',
            'template_slug' => $templateSlug,
        ]);
        $rows = $statement->fetchAll();

        if ($rows === []) {
            return null;
        }

        $firstRow = $rows[0];
        $zones = [];

        foreach ($rows as $row) {
            $zoneId = (int) $row['zone_id'];

            if (! isset($zones[$zoneId])) {
                $zones[$zoneId] = [
                    'id' => $zoneId,
                    'zone_key' => (string) $row['zone_key'],
                    'label' => (string) $row['zone_label'],
                    'sort_order' => (int) $row['zone_sort_order'],
                    'answers' => [],
                ];
            }

            if ($row['answer_text'] !== null && $row['question_id'] !== null) {
                $zones[$zoneId]['answers'][] = [
                    'question_id' => (int) $row['question_id'],
                    'canonical_number' => (int) $row['canonical_number'],
                    'answer_text' => (string) $row['answer_text'],
                ];
            }
        }

        return [
            'template' => [
                'id' => (int) $firstRow['template_id'],
                'slug' => (string) $firstRow['template_slug'],
                'title' => (string) $firstRow['template_title'],
                'description' => $firstRow['template_description'] !== null ? (string) $firstRow['template_description'] : null,
            ],
            'zones' => array_values($zones),
        ];
    }
}
