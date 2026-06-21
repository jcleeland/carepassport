CREATE TABLE answers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resident_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    answer_text MEDIUMTEXT NULL,
    skipped TINYINT(1) NOT NULL DEFAULT 0,
    visibility ENUM('poster', 'booklet', 'private') NOT NULL DEFAULT 'booklet',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY answers_resident_question_unique (resident_id, question_id),
    KEY answers_question_id_index (question_id),
    KEY answers_visibility_index (visibility),
    KEY answers_skipped_index (skipped),
    CONSTRAINT answers_resident_id_foreign
        FOREIGN KEY (resident_id) REFERENCES residents (id)
        ON DELETE CASCADE,
    CONSTRAINT answers_question_id_foreign
        FOREIGN KEY (question_id) REFERENCES questions (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE photos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resident_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(80) NOT NULL DEFAULT 'portrait',
    original_file_path VARCHAR(500) NOT NULL,
    processed_file_path VARCHAR(500) NULL,
    caption VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY photos_resident_id_index (resident_id),
    KEY photos_type_index (type),
    CONSTRAINT photos_resident_id_foreign
        FOREIGN KEY (resident_id) REFERENCES residents (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
