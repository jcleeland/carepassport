CREATE TABLE support_contexts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL,
    label VARCHAR(255) NOT NULL,
    description TEXT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY support_contexts_slug_unique (slug),
    KEY support_contexts_active_sort_order_index (active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE question_sections
    ADD COLUMN slug VARCHAR(120) NULL AFTER id,
    ADD UNIQUE KEY question_sections_slug_unique (slug);

ALTER TABLE questions
    ADD COLUMN slug VARCHAR(120) NULL AFTER id,
    ADD UNIQUE KEY questions_slug_unique (slug);
