# Migrations

Codex should create ordered migration files here when implementation begins.

Expected naming pattern:

- `001_create_users_and_sessions.sql`
- `002_create_residents_and_consent.sql`
- `003_create_questions_and_paths.sql`
- `004_create_answers_and_visibility.sql`
- `005_create_photos_and_outputs.sql`

Run migrations with:

```sh
php bin/migrate
```

The runner creates a `schema_migrations` table automatically and records each applied SQL file by filename.
