# Care Passport — MVP Acceptance Criteria

## Primary Acceptance Test

A family member must be able to:

1. Create a person/resident profile.
2. Select a completion mode.
3. Complete the 37-question pathway.
4. Skip any questions they do not wish to answer.
5. Upload a portrait photo.
6. Review and edit answers.
7. Assign visibility to answers:
   - Poster
   - Bedside/support booklet
   - Private family record
8. Generate and download three PDFs:
   - Poster A — Who I Am
   - Poster B — Helpful Things to Know When Supporting Me
   - Full Booklet

The entire process must be completable without administrator assistance.

## Functional Acceptance Criteria

The MVP passes only if:

- All questions are stored in database records.
- All intro pages are stored in database records.
- All poster mappings are stored in database records.
- All output labels are stored in database records.
- No question is mandatory.
- Users can skip any question.
- Users can return and edit answers.
- Visibility can be controlled per answer.
- Portrait photo uploads function correctly.
- PDF generation functions correctly.
- Generated PDFs are downloadable.

## Output Acceptance Criteria

The MVP passes only if:

- Skipped questions do not create empty headings or awkward gaps.
- Poster content remains concise and readable at a glance.
- Poster layouts fit comfortably on a printable page.
- Booklet content remains coherent when many questions are skipped.
- Private answers never appear in printed outputs.
- Poster-visible answers may appear in posters.
- Booklet-visible answers may appear in the booklet.
- Photos are automatically resized and displayed consistently.
- Generated PDFs are suitable for home printing.

## Language Acceptance Criteria

The MVP passes only if:

- Outputs do not describe themselves as a care plan.
- Outputs do not present themselves as instructions to staff, carers or support workers.
- Outputs do not use directive language such as:
  - staff must
  - required care
  - care directives
- Outputs consistently use language such as:
  - helpful things to know
  - preferences and routines
  - what helps me feel comfortable
  - information that may help people understand me
  - helpful things to know when supporting me

## Portability Acceptance Criteria

The MVP passes only if:

- Outputs do not assume the user lives in a residential aged care facility.
- Outputs remain understandable in multiple support settings.
- Language remains person-centred and context-neutral.
- Outputs focus on the person rather than the service setting.

## Product Acceptance Criteria

The MVP passes only if:

- A family member can complete the process without training.
- The resulting documents appear professional and respectful.
- The resulting documents clearly communicate the person's identity, preferences and routines.
- The resulting documents would be suitable to hand to carers, support workers, healthcare providers, or a residential aged care facility.

## Explicit Non-Requirements

The MVP does not require:

- AI summarisation
- AI-generated questions
- Voice transcription
- Native mobile applications
- Payment systems
- Subscription systems
- Print-service integration
- Organisational licensing features
- Facility dashboards
