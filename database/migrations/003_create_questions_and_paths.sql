CREATE TABLE question_paths (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY question_paths_slug_unique (slug),
    KEY question_paths_active_sort_order_index (active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE question_sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    module_label VARCHAR(120) NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY question_sections_active_sort_order_index (active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    canonical_number INT UNSIGNED NOT NULL,
    section_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    help_text TEXT NULL,
    answer_type VARCHAR(80) NOT NULL DEFAULT 'text',
    default_visibility ENUM('poster', 'booklet', 'private') NOT NULL DEFAULT 'booklet',
    active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY questions_canonical_number_unique (canonical_number),
    KEY questions_section_sort_order_index (section_id, sort_order),
    KEY questions_active_index (active),
    CONSTRAINT questions_section_id_foreign
        FOREIGN KEY (section_id) REFERENCES question_sections (id)
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE question_path_questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_path_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    path_sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY question_path_questions_path_question_unique (question_path_id, question_id),
    UNIQUE KEY question_path_questions_path_sort_unique (question_path_id, path_sort_order),
    KEY question_path_questions_question_id_index (question_id),
    CONSTRAINT question_path_questions_path_id_foreign
        FOREIGN KEY (question_path_id) REFERENCES question_paths (id)
        ON DELETE CASCADE,
    CONSTRAINT question_path_questions_question_id_foreign
        FOREIGN KEY (question_id) REFERENCES questions (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
