# Care Passport — Technical Architecture

## MVP Architecture

Care Passport is a responsive web app/PWA-first project.

Recommended MVP stack:

- PHP 8.3+
- MySQL/MariaDB
- Tailwind CSS
- Server-rendered templates
- HTML-to-PDF generation
- Secure local file storage for uploaded photos and generated PDFs

Do not build native iOS or Android apps in the MVP.

## Data-Driven Content

The following must be database-driven, not hardcoded:

- intro pages
- consent pages
- questions
- question sections
- question pathways
- output labels
- poster mappings
- visibility labels

## Support Context

The data model should support a support context field.

Examples:

- aged_care
- disability_support
- home_care
- hospital
- rehabilitation
- respite
- palliative_care
- other

This field is informational only in the MVP.

It may be used in future versions to customise wording, layouts and question packs.

Suggested schema:

```sql
support_context VARCHAR(50)
```

on the person/resident profile.

## Core Entities

Indicative entities:

- users
- sessions
- people/residents
- support_contexts
- intro_pages
- consent_acknowledgements
- question_sections
- questions
- question_pathways
- question_pathway_items
- answers
- answer_visibility
- photos
- output_templates
- poster_mappings
- generated_documents

## Future AI Layer

Do not build AI into the MVP.

The architecture should nevertheless allow a future AI processing layer between answers and outputs.

Future flow:

```text
Answers → AI summarisation → user review → output generation
```

The MVP flow is:

```text
Answers → review/visibility selection → output generation
```
