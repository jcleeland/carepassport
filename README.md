# Life & Care Passport — MVP Starter Project

Life & Care Passport is a responsive web app MVP that helps families create a person-centred aged care identity package for someone entering residential aged care.

This repository currently contains development documentation, seed content, database planning files, and a starter directory structure for implementation with Codex.

## MVP Boundary

This project is for the MVP only.

The MVP builds:
- resident profile
- intro and consent flow
- completion mode selection
- Essential / Recommended / Full question paths
- database-driven question content
- answer review and visibility controls
- portrait photo upload
- three downloadable PDFs:
  - Poster A — Who I Am
  - Poster B — Helpful Things to Know When Supporting Me
  - Full Booklet

The MVP does not build:
- native mobile apps
- payments
- subscriptions
- voice transcription
- AI follow-up questions
- print-service integration
- organisational dashboard
- facility portal

## Documentation

Read these first:

1. `docs/01-product-requirements.md`
2. `docs/02-clinical-boundaries-and-language.md`
3. `docs/03-consent-and-capacity.md`
4. `docs/04-question-framework.md`
5. `docs/05-output-specification.md`
6. `docs/06-technical-architecture.md`
7. `docs/07-data-model.md`
8. `docs/08-development-roadmap.md`
9. `docs/09-mvp-acceptance-criteria.md`
10. `docs/10-beta-evaluation-plan.md`
11. `docs/codex-prompts/00-project-brief.md`

## Suggested Stack

- PHP 8.3+
- MySQL or MariaDB
- Tailwind CSS
- HTML-to-PDF generation
- Responsive web/PWA-first implementation

## Implementation Rule

Questions, intro pages, poster mappings and output labels must be database-driven, not hardcoded.

## Local Foundation Setup

The current implementation is a minimal PHP foundation only. It does not yet include resident profiles, questions, answers, uploads or PDF generation.

1. Copy the environment template:

   ```sh
   cp .env.example .env
   ```

2. Edit `.env` for your local database:

   ```dotenv
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=care_passport
   DB_USERNAME=care_passport
   DB_PASSWORD=change-me
   ```

3. Create the database and user in MySQL or MariaDB using your local admin account.

4. Check the PHP bootstrap:

   ```sh
   php -S 127.0.0.1:8000 -t public
   ```

   Then open `http://127.0.0.1:8000`.

## Migrations and Seeds

Migration files live in `database/migrations` and are executed in filename order.

The migration runner creates and uses a `schema_migrations` table to track applied migrations:

```sh
php bin/migrate --dry-run
php bin/migrate --status
php bin/migrate
```

Seed files live in `database/seeds` and are executed in filename order:

```sh
php bin/seed --dry-run
php bin/seed
```

The current seed file is starter content for the future data-model phase. Run real seeds only after the matching product tables have been created by migrations.

## Development Sample Passport

For local preview testing, create one fictional sample passport:

```sh
php bin/sample-passport create
```

This command is development-only. It refuses to create sample data unless `APP_ENV` is `local`, `development` or `testing`.

It creates:

- one sample user: `sample.passport@example.test`
- password: `SamplePass123!`
- one fictional resident profile
- consent/completion context
- one portrait photo file under `storage/app/sample-passport`
- a mix of poster-visible, booklet-visible, private and skipped answers

After creating the sample, log in with the sample account and open the dashboard or `/output` to preview:

- Poster A
- Poster B
- Full Booklet

Remove the sample data and sample portrait file with:

```sh
php bin/sample-passport remove
```

The command uses a fixed sample email and replaces any previous copy of the sample when rerun. Do not use it for production data.
