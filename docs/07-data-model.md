# Data Model Specification

## Core Entities

### users

Stores registered users. Temporary sessions may be used before account creation.

Fields likely required:

- id
- name
- email
- password_hash or magic-link authentication fields
- created_at
- updated_at

### temporary_sessions

Supports MVP flow where a user starts without creating an account.

- id
- session_token_hash
- expires_at
- created_at
- updated_at

### residents

- id
- user_id nullable
- temporary_session_id nullable
- full_name
- preferred_name
- facility_name nullable
- room_number nullable
- created_at
- updated_at

### completion_modes

Seeded records:

- self
- assisted
- proxy

### consent_records

- id
- resident_id
- completion_mode_id
- helper_name nullable
- helper_relationship nullable
- consent_acknowledged_at
- consent_text_version
- created_at

### intro_pages

Database-driven introductory content.

- id
- slug
- title
- body_markdown
- sort_order
- active
- created_at
- updated_at

### question_paths

Seeded records:

- essential_12
- recommended_25
- full_37

### question_sections

- id
- module_label
- title
- description
- sort_order
- active

### questions

- id
- canonical_number
- section_id
- question_text
- help_text nullable
- answer_type default text
- default_visibility
- active
- sort_order
- created_at
- updated_at

### question_path_questions

Join table defining which questions belong to which path.

- id
- question_path_id
- question_id
- path_sort_order

### answers

- id
- resident_id
- question_id
- answer_text nullable
- skipped boolean
- visibility enum/poster/booklet/private
- created_at
- updated_at

### photos

- id
- resident_id
- type portrait
- original_file_path
- processed_file_path
- caption nullable
- created_at

### output_templates

Database-driven output definitions.

- id
- slug
- title
- description
- active

### output_template_zones

- id
- output_template_id
- zone_key
- label
- sort_order
- active

### poster_mappings

- id
- output_template_zone_id
- question_id
- sort_order

### generated_documents

- id
- resident_id
- document_type poster_a/poster_b/booklet
- file_path
- generated_at

## Visibility Rules

- `poster`: available for posters and booklet unless overridden in output logic.
- `booklet`: available for booklet only.
- `private`: excluded from all printed outputs.

## Important Implementation Note

Do not model questions as columns on the resident table. Questions must remain content records so wording and path membership can change after beta testing.
