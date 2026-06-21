CREATE TABLE output_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY output_templates_slug_unique (slug),
    KEY output_templates_active_index (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE output_template_zones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    output_template_id BIGINT UNSIGNED NOT NULL,
    zone_key VARCHAR(120) NOT NULL,
    label VARCHAR(255) NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY output_template_zones_template_zone_unique (output_template_id, zone_key),
    KEY output_template_zones_template_sort_index (output_template_id, sort_order),
    CONSTRAINT output_template_zones_template_id_foreign
        FOREIGN KEY (output_template_id) REFERENCES output_templates (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE poster_mappings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    output_template_zone_id BIGINT UNSIGNED NOT NULL,
    question_id BIGINT UNSIGNED NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY poster_mappings_zone_question_unique (output_template_zone_id, question_id),
    KEY poster_mappings_zone_sort_index (output_template_zone_id, sort_order),
    KEY poster_mappings_question_id_index (question_id),
    CONSTRAINT poster_mappings_zone_id_foreign
        FOREIGN KEY (output_template_zone_id) REFERENCES output_template_zones (id)
        ON DELETE CASCADE,
    CONSTRAINT poster_mappings_question_id_foreign
        FOREIGN KEY (question_id) REFERENCES questions (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE generated_documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resident_id BIGINT UNSIGNED NOT NULL,
    output_template_id BIGINT UNSIGNED NULL,
    document_type ENUM('poster_a', 'poster_b', 'booklet') NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    generated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY generated_documents_resident_id_index (resident_id),
    KEY generated_documents_output_template_id_index (output_template_id),
    KEY generated_documents_document_type_index (document_type),
    CONSTRAINT generated_documents_resident_id_foreign
        FOREIGN KEY (resident_id) REFERENCES residents (id)
        ON DELETE CASCADE,
    CONSTRAINT generated_documents_output_template_id_foreign
        FOREIGN KEY (output_template_id) REFERENCES output_templates (id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
