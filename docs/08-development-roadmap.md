# Development Roadmap

## MVP Only

All development work should remain inside MVP scope until the acceptance criteria are met.

## Phase 0 — Planning and Setup

Deliverables:

- repository created
- documentation committed
- environment configured
- database created
- local/dev deployment working

## Phase 1 — Application Scaffold

Deliverables:

- PHP application bootstrap
- routing
- layout template
- database connection
- environment config
- basic error handling
- basic responsive styling

## Phase 2 — Data Model and Seed Content

Deliverables:

- migrations for core tables
- seed intro pages
- seed completion modes
- seed question paths
- seed all 37 questions
- seed Essential 12 and Recommended 25 mappings
- seed poster templates, zones and mappings

## Phase 3 — Resident Profile and Consent Flow

Deliverables:

- create resident profile
- start temporary session
- optional user account path
- intro pages
- completion mode selection
- consent record creation

## Phase 4 — Questionnaire Flow

Deliverables:

- choose path: Essential 12, Recommended 25, Full 37
- one-question-at-a-time interface
- answer save
- skip support
- back/next navigation
- progress indicator
- no required questions

## Phase 5 — Photo Upload

Deliverables:

- upload one portrait photo
- validate file type and size
- resize/crop to standard portrait format
- store original and processed version

## Phase 6 — Review and Visibility

Deliverables:

- grouped answer review
- edit answers
- visibility selector for each answer
- clear explanation of poster/booklet/private
- warning that bedside booklet is not truly private

## Phase 7 — PDF Generation

Deliverables:

- Poster A PDF
- Poster B PDF
- Full Booklet PDF
- skipped/private answers omitted cleanly
- polished printable layouts
- download links

## Phase 8 — MVP Acceptance Testing

Deliverables:

- verify all acceptance criteria
- test completion with skipped answers
- test privacy exclusions
- test print/readability
- test with sample resident data

## Out of Scope Until MVP Is Accepted

- AI summarisation
- AI-generated follow-ups
- voice transcription
- native apps
- payments
- print-service integration
- organisational dashboard
