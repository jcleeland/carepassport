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

    /**
     * @return array{
     *     template:array{id:int,slug:string,title:string,description:string|null},
     *     sections:list<array{id:int,zone_key:string,label:string,sort_order:int,answers:list<array{question_id:int,canonical_number:int,question_text:string,answer_text:string,visibility:string,question_section_title:string}>>}
     * }|null
     */
    public function bookletTemplateForResident(int $residentId): ?array
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
                question.question_text,
                section.title AS question_section_title,
                answer.answer_text,
                answer.visibility
            FROM output_templates AS template
            INNER JOIN output_template_zones AS zone
                ON zone.output_template_id = template.id
            LEFT JOIN poster_mappings AS mapping
                ON mapping.output_template_zone_id = zone.id
            LEFT JOIN questions AS question
                ON question.id = mapping.question_id
                AND question.active = 1
            LEFT JOIN question_sections AS section
                ON section.id = question.section_id
                AND section.active = 1
            LEFT JOIN answers AS answer
                ON answer.question_id = question.id
                AND answer.resident_id = :resident_id
                AND answer.visibility IN (\'poster\', \'booklet\')
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
            'template_slug' => 'full_booklet',
        ]);
        $rows = $statement->fetchAll();

        if ($rows === []) {
            return null;
        }

        $firstRow = $rows[0];
        $sections = [];

        foreach ($rows as $row) {
            $zoneId = (int) $row['zone_id'];

            if (! isset($sections[$zoneId])) {
                $sections[$zoneId] = [
                    'id' => $zoneId,
                    'zone_key' => (string) $row['zone_key'],
                    'label' => (string) $row['zone_label'],
                    'sort_order' => (int) $row['zone_sort_order'],
                    'answers' => [],
                ];
            }

            if ($row['answer_text'] !== null && $row['question_id'] !== null) {
                $sections[$zoneId]['answers'][] = [
                    'question_id' => (int) $row['question_id'],
                    'canonical_number' => (int) $row['canonical_number'],
                    'question_text' => (string) $row['question_text'],
                    'answer_text' => (string) $row['answer_text'],
                    'visibility' => (string) $row['visibility'],
                    'question_section_title' => (string) $row['question_section_title'],
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
            'sections' => array_values($sections),
        ];
    }

    /**
     * @return array{label:string|null}|null
     */
    public function supportContext(string $slug): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT label FROM support_contexts WHERE slug = :slug AND active = 1 LIMIT 1',
        );
        $statement->execute(['slug' => $slug]);
        $context = $statement->fetch();

        if (! is_array($context)) {
            return null;
        }

        return [
            'label' => $context['label'] !== null ? (string) $context['label'] : null,
        ];
    }

    /**
     * @return array{completion_mode_label:string,helper_name:string|null,helper_relationship:string|null,consent_acknowledged_at:string}|null
     */
    public function latestCompletionContextForResident(int $residentId): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT
                mode.label AS completion_mode_label,
                consent.helper_name,
                consent.helper_relationship,
                consent.consent_acknowledged_at
            FROM consent_records AS consent
            INNER JOIN completion_modes AS mode
                ON mode.id = consent.completion_mode_id
            WHERE consent.resident_id = :resident_id
            ORDER BY consent.consent_acknowledged_at DESC, consent.id DESC
            LIMIT 1',
        );
        $statement->execute(['resident_id' => $residentId]);
        $context = $statement->fetch();

        if (! is_array($context)) {
            return null;
        }

        return [
            'completion_mode_label' => (string) $context['completion_mode_label'],
            'helper_name' => $context['helper_name'] !== null ? (string) $context['helper_name'] : null,
            'helper_relationship' => $context['helper_relationship'] !== null ? (string) $context['helper_relationship'] : null,
            'consent_acknowledged_at' => (string) $context['consent_acknowledged_at'],
        ];
    }
}
