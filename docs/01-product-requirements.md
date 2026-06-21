# Care Passport — Product Requirements

## Vision

Care Passport helps people create a personal identity and support passport that can be shared with carers, support workers, healthcare providers, family members, and others involved in their support.

The purpose is to help people understand the individual behind the care or support needs.

The system captures identity, preferences, routines, communication style, comfort needs, and personal history, then produces printable outputs that help others quickly understand the person.

The MVP will be validated primarily with people entering residential aged care and their families, but the platform is intentionally designed to support broader care and support contexts.

## Purpose

Care Passport is a responsive web application that helps people and families create a personal identity and support passport.

The generated outputs are intended to:

- introduce the person
- support relationship-building
- communicate preferences and routines
- preserve personhood during periods of care or support

The outputs are not clinical records and do not replace professional assessment or care planning.

## Initial Target Audience

The MVP is initially targeted toward:

- people entering residential aged care
- family members assisting with aged care transitions

Future audiences may include:

- disability support participants
- home care recipients
- hospital patients
- rehabilitation clients
- respite care recipients
- palliative care patients
- supported accommodation residents
- others receiving ongoing support

## MVP Only

This project is for the MVP only. Do not implement future-phase features unless explicitly requested.

## MVP User Journey

1. User creates an account or starts a temporary session.
2. User creates a resident/person profile.
3. User reads intro and consent pages.
4. User chooses completion mode:
   - I am completing this for myself
   - I am helping someone complete this
   - I am completing this on behalf of someone who cannot complete it themselves
5. User answers questions in one of three paths:
   - Essential 12
   - Recommended 25
   - Full 37
6. User uploads one portrait photo.
7. User reviews and edits answers.
8. User chooses visibility for each answer:
   - poster
   - bedside/support booklet
   - private family record / exclude from printed outputs
9. User generates:
   - Poster A: Who I Am
   - Poster B: Helpful Things to Know When Supporting Me
   - Full booklet PDF

## Build Constraints

- Web app/PWA first.
- Do not build native iOS or Android apps.
- No payments.
- No subscriptions.
- No AI follow-up questions.
- No voice transcription in MVP.
- No print-service integration.
- No organisational dashboard.
- Questions must be database-driven, not hardcoded.
- Intro pages must be database-driven, not hardcoded.
- Poster mappings must be database-driven, not hardcoded.
- Output labels must be database-driven, not hardcoded.

## Core Technical Requirement

The generated PDFs must look polished, readable, and suitable for families to print and give to carers, support workers, healthcare providers, or a residential aged care facility.

Output quality is a core MVP requirement, not a later enhancement.

## Language Rules

Avoid phrases that imply staff, carers or support workers must follow instructions.

Use phrases such as:

- helpful things to know
- what helps me feel comfortable
- information that may help people understand me
- preferences and routines
- helpful things to know when supporting me

Do not use:

- care plan
- instructions for staff
- required care
- staff must
- care directives

## MVP Features to Build

Build:

- Data model
- Seed content
- Person/resident profile
- Support context field
- Intro and consent flow
- Completion mode selection
- Question path selection
- Interview flow
- Review and edit screen
- Per-answer visibility controls
- Portrait photo upload
- Poster A PDF generation
- Poster B PDF generation
- Full booklet PDF generation

## Future Features — Do Not Build Yet

Do not build these in the MVP:

- AI summarisation
- AI follow-up questions
- Voice transcription
- Native mobile apps
- Payments
- Subscriptions
- Print-service integration
- Organisational licensing
- Facility dashboard
