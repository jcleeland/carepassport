# Technical Architecture

## Recommended MVP Stack

- PHP 8.3+
- MySQL or MariaDB
- Tailwind CSS or compiled static CSS
- HTML-to-PDF generation
- Responsive web/PWA-first design

## Architecture Principles

- Keep MVP small and focused.
- Avoid hardcoded user-facing content where database-driven content is required.
- Keep future AI and transcription concerns out of the MVP implementation.
- Design a clean service boundary for future AI summarisation, but do not build AI now.
- Treat output generation as a first-class domain service.

## Suggested Application Layers

- public front controller
- routing layer
- controllers
- models/repositories
- services
- view templates
- database migrations and seeders
- PDF templates

## Required Services

### Questionnaire Service

Responsible for:

- loading question paths
- loading sections and questions
- saving answers
- handling skip state
- calculating progress

### Visibility Service

Responsible for:

- storing answer visibility
- validating valid visibility states
- ensuring private answers do not reach generated outputs

### Upload Service

Responsible for:

- portrait photo upload
- file size validation
- file type validation
- safe file naming
- thumbnail/resize processing

### PDF Generation Service

Responsible for:

- preparing output data
- excluding skipped/private answers
- rendering Poster A
- rendering Poster B
- rendering Full Booklet
- storing generated PDFs
- providing downloads

## Security Requirements

- Use environment variables for secrets.
- Do not commit `.env`.
- Validate all uploads.
- Store generated files outside public web root unless download access is controlled.
- Apply CSRF protection to forms.
- Escape output in HTML views.
- Use prepared statements for database queries.
- Avoid storing unnecessary sensitive data.

## PWA Requirements

PWA is optional for early MVP implementation. If added, keep it simple:

- manifest
- mobile-friendly layout
- installable shell

Do not add offline storage of sensitive answers in the MVP unless explicitly approved.
