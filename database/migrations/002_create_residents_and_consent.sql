CREATE TABLE residents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    temporary_session_id BIGINT UNSIGNED NULL,
    full_name VARCHAR(255) NOT NULL,
    preferred_name VARCHAR(255) NULL,
    support_context VARCHAR(120) NULL,
    facility_name VARCHAR(255) NULL,
    room_number VARCHAR(120) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY residents_user_id_index (user_id),
    KEY residents_temporary_session_id_index (temporary_session_id),
    KEY residents_support_context_index (support_context),
    CONSTRAINT residents_user_id_foreign
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE SET NULL,
    CONSTRAINT residents_temporary_session_id_foreign
        FOREIGN KEY (temporary_session_id) REFERENCES temporary_sessions (id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE completion_modes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(80) NOT NULL,
    label VARCHAR(255) NOT NULL,
    description TEXT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY completion_modes_slug_unique (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE consent_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    resident_id BIGINT UNSIGNED NOT NULL,
    completion_mode_id BIGINT UNSIGNED NOT NULL,
    helper_name VARCHAR(255) NULL,
    helper_relationship VARCHAR(255) NULL,
    acknowledgement_data JSON NULL,
    consent_acknowledged_at TIMESTAMP NOT NULL,
    consent_text_version VARCHAR(80) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY consent_records_resident_id_index (resident_id),
    KEY consent_records_completion_mode_id_index (completion_mode_id),
    CONSTRAINT consent_records_resident_id_foreign
        FOREIGN KEY (resident_id) REFERENCES residents (id)
        ON DELETE CASCADE,
    CONSTRAINT consent_records_completion_mode_id_foreign
        FOREIGN KEY (completion_mode_id) REFERENCES completion_modes (id)
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE intro_pages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(120) NOT NULL,
    title VARCHAR(255) NOT NULL,
    body_markdown MEDIUMTEXT NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY intro_pages_slug_unique (slug),
    KEY intro_pages_active_sort_order_index (active, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
