<?php

declare(strict_types=1);

namespace CarePassport\Repositories;

use PDO;

final class QuestionnaireRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * @return list<array{id:int,slug:string,title:string,description:string|null,question_count:int}>
     */
    public function activePaths(): array
    {
        $statement = $this->pdo->query(
            'SELECT
                path.id,
                path.slug,
                path.title,
                path.description,
                COUNT(path_question.question_id) AS question_count
            FROM question_paths AS path
            LEFT JOIN question_path_questions AS path_question
                ON path_question.question_path_id = path.id
            WHERE path.active = 1
            GROUP BY path.id, path.slug, path.title, path.description, path.sort_order
            ORDER BY path.sort_order ASC, path.title ASC',
        );

        return array_map(
            static fn (array $row): array => [
                'id' => (int) $row['id'],
                'slug' => (string) $row['slug'],
                'title' => (string) $row['title'],
                'description' => $row['description'] !== null ? (string) $row['description'] : null,
                'question_count' => (int) $row['question_count'],
            ],
            $statement->fetchAll(),
        );
    }

    /**
     * @return array{id:int,slug:string,title:string,description:string|null}|null
     */
    public function findActivePathBySlug(string $slug): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, slug, title, description FROM question_paths WHERE slug = :slug AND active = 1 LIMIT 1',
        );
        $statement->execute(['slug' => $slug]);
        $path = $statement->fetch();

        if (! is_array($path)) {
            return null;
        }

        return [
            'id' => (int) $path['id'],
            'slug' => (string) $path['slug'],
            'title' => (string) $path['title'],
            'description' => $path['description'] !== null ? (string) $path['description'] : null,
        ];
    }

    /**
     * @return array{id:int,slug:string,title:string,description:string|null}|null
     */
    public function findPathById(int $pathId): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, slug, title, description FROM question_paths WHERE id = :id AND active = 1 LIMIT 1',
        );
        $statement->execute(['id' => $pathId]);
        $path = $statement->fetch();

        if (! is_array($path)) {
            return null;
        }

        return [
            'id' => (int) $path['id'],
            'slug' => (string) $path['slug'],
            'title' => (string) $path['title'],
            'description' => $path['description'] !== null ? (string) $path['description'] : null,
        ];
    }

    public function questionCount(int $pathId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*) FROM question_path_questions WHERE question_path_id = :path_id',
        );
        $statement->execute(['path_id' => $pathId]);

        return (int) $statement->fetchColumn();
    }

    /**
     * @return array{
     *     id:int,
     *     canonical_number:int,
     *     question_text:string,
     *     help_text:string|null,
     *     answer_type:string,
     *     default_visibility:string,
     *     section_title:string,
     *     section_label:string|null,
     *     position:int
     * }|null
     */
    public function questionAt(int $pathId, int $position): ?array
    {
        $offset = max(0, $position - 1);
        $statement = $this->pdo->prepare(
            'SELECT
                question.id,
                question.canonical_number,
                question.question_text,
                question.help_text,
                question.answer_type,
                question.default_visibility,
                section.title AS section_title,
                section.module_label AS section_label,
                path_question.path_sort_order AS position
            FROM question_path_questions AS path_question
            INNER JOIN questions AS question ON question.id = path_question.question_id
            INNER JOIN question_sections AS section ON section.id = question.section_id
            WHERE path_question.question_path_id = :path_id
                AND question.active = 1
                AND section.active = 1
            ORDER BY path_question.path_sort_order ASC
            LIMIT 1 OFFSET ' . $offset,
        );
        $statement->execute(['path_id' => $pathId]);
        $question = $statement->fetch();

        if (! is_array($question)) {
            return null;
        }

        return [
            'id' => (int) $question['id'],
            'canonical_number' => (int) $question['canonical_number'],
            'question_text' => (string) $question['question_text'],
            'help_text' => $question['help_text'] !== null ? (string) $question['help_text'] : null,
            'answer_type' => (string) $question['answer_type'],
            'default_visibility' => (string) $question['default_visibility'],
            'section_title' => (string) $question['section_title'],
            'section_label' => $question['section_label'] !== null ? (string) $question['section_label'] : null,
            'position' => (int) $question['position'],
        ];
    }

    /**
     * @return array{answer_text:string|null,skipped:int,visibility:string}|null
     */
    public function answerForQuestion(int $residentId, int $questionId): ?array
    {
        $statement = $this->pdo->prepare(
            'SELECT answer_text, skipped, visibility FROM answers WHERE resident_id = :resident_id AND question_id = :question_id LIMIT 1',
        );
        $statement->execute([
            'resident_id' => $residentId,
            'question_id' => $questionId,
        ]);
        $answer = $statement->fetch();

        if (! is_array($answer)) {
            return null;
        }

        return [
            'answer_text' => $answer['answer_text'] !== null ? (string) $answer['answer_text'] : null,
            'skipped' => (int) $answer['skipped'],
            'visibility' => (string) $answer['visibility'],
        ];
    }

    public function saveAnswer(
        int $residentId,
        int $questionId,
        ?string $answerText,
        bool $skipped,
        string $visibility,
    ): void {
        $statement = $this->pdo->prepare(
            'INSERT INTO answers (resident_id, question_id, answer_text, skipped, visibility)
            VALUES (:resident_id, :question_id, :answer_text, :skipped, :visibility)
            ON DUPLICATE KEY UPDATE
                answer_text = VALUES(answer_text),
                skipped = VALUES(skipped),
                visibility = VALUES(visibility),
                updated_at = CURRENT_TIMESTAMP',
        );
        $statement->execute([
            'resident_id' => $residentId,
            'question_id' => $questionId,
            'answer_text' => $answerText,
            'skipped' => $skipped ? 1 : 0,
            'visibility' => in_array($visibility, ['poster', 'booklet', 'private'], true) ? $visibility : 'booklet',
        ]);
    }

    public function completedCount(int $residentId, int $pathId): int
    {
        $statement = $this->pdo->prepare(
            'SELECT COUNT(*)
            FROM answers AS answer
            INNER JOIN question_path_questions AS path_question
                ON path_question.question_id = answer.question_id
            WHERE answer.resident_id = :resident_id
                AND path_question.question_path_id = :path_id',
        );
        $statement->execute([
            'resident_id' => $residentId,
            'path_id' => $pathId,
        ]);

        return (int) $statement->fetchColumn();
    }
}
