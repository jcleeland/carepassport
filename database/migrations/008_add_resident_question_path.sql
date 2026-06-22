ALTER TABLE residents
    ADD COLUMN question_path_id BIGINT UNSIGNED NULL AFTER notes,
    ADD KEY residents_question_path_id_index (question_path_id),
    ADD CONSTRAINT residents_question_path_id_foreign
        FOREIGN KEY (question_path_id) REFERENCES question_paths (id)
        ON DELETE SET NULL;
