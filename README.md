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
