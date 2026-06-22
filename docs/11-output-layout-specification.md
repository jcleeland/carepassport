# Care Passport - Output Layout Specification

## Purpose

This document defines the MVP PDF layout rules for:

1. Poster A - Who I Am
2. Poster B - Helpful Things to Know When Supporting Me
3. Full Booklet

The layouts must remain person-centred, respectful, readable and suitable across support contexts including residential aged care, home care, disability support, hospital stays, rehabilitation, respite care, palliative care, supported accommodation and community support.

This is a design specification only. It does not define application code, HTML, or PDF implementation details beyond rendering requirements.

## Shared Output Rules

### Source of Truth

Output content must be driven by database records:

- `output_templates`
- `output_template_zones`
- `poster_mappings`
- `questions`
- `answers`
- `photos`

Application code may render templates, but must not hardcode question text, output labels, zone names or question-to-output mappings.

### Visibility

Visibility is enforced before layout:

- `poster`: may appear on posters and in the booklet.
- `booklet`: may appear in the booklet only.
- `private`: must not appear in any generated PDF.

If an answer is marked `private`, it is excluded from Poster A, Poster B and Full Booklet even if its question is mapped to an output zone.

If an answer is marked `booklet`, it is excluded from Poster A and Poster B even if its question is mapped to a poster zone.

### Missing and Skipped Answers

Skipped answers and empty answers must be omitted.

A zone or section with no printable answers must be removed from the rendered output. The PDF must not show empty headings, blank answer boxes or placeholder text such as "not answered".

### Long Answers

Long answers must be handled differently by output type:

- Posters: prioritize glanceability. Use a maximum visible line count per zone, reduce font size only within the defined typography range, and then show a short continuation note such as "More detail in booklet" if overflow remains.
- Booklet: preserve the full non-private answer wherever possible. If an answer crosses a page boundary, keep the question heading with at least two answer lines on the same page or move the whole answer block to the next page.

The renderer must not rewrite, summarize or reinterpret answer text.

### Photographs

The MVP supports one portrait photo. If a portrait photo exists, use the processed portrait image, not the original upload. If no portrait photo exists, omit the photo area and let the surrounding content rebalance.

Photo rendering must avoid distortion:

- crop with object-fit cover semantics
- preserve face-safe positioning where the processed image provides it
- use a neutral fallback only if product design later approves one

### Disclaimer

Generated PDFs should include the concise clinical-boundary disclaimer from `docs/02-clinical-boundaries-and-language.md`. Poster layouts may use a compact footer version; the booklet should use the full disclaimer on an early page and a compact footer on subsequent pages.

### Print Settings

All outputs should be designed for home printing:

- page size: A4 by default
- margins that survive consumer printers
- no content closer than 10 mm to page edges
- avoid full-bleed requirements
- strong contrast in grayscale printing
- no essential meaning conveyed by colour alone

### Accessibility

PDF outputs should be readable by families and support workers:

- minimum body text size of 10 pt in booklet and 11 pt on posters
- large poster headings readable at a glance
- high contrast text
- logical reading order
- meaningful section headings
- no decorative text embedded as image-only content
- photo alt text metadata where the PDF library supports it

### PDF Generation

The PDF generator should prepare a clean output data model before layout:

1. Load active output template and zones.
2. Load mapped questions in zone order.
3. Load answers for the resident.
4. Exclude skipped, empty and private answers.
5. Apply poster/booklet visibility rules.
6. Remove empty zones and empty sections.
7. Render using fixed page dimensions and predictable typography.
8. Store generated PDFs outside the public web root unless download access is controlled.

The renderer should use embedded fonts or a dependable system font stack with stable metrics. PDF output should be deterministic for the same data.

## Poster A - Who I Am

### Purpose

Poster A gives quick personal orientation. It should help someone understand the person at a glance before reading detailed support information.

### Page Size

- A4 portrait
- 210 mm x 297 mm
- printable content area: approximately 186 mm x 273 mm
- outer margin: 12 mm
- internal grid gap: 5-7 mm

### Typography Hierarchy

- Document title: 28-34 pt, bold
- Preferred name: 34-44 pt, bold, largest text on page
- Photo caption/tagline: 16-20 pt, medium
- Zone headings: 12-14 pt, bold or semibold
- Zone body: 11-13 pt, regular
- Footer disclaimer: 6.5-7.5 pt, regular

Avoid condensed fonts. Use sentence case for headings unless a seeded label intentionally uses title case.

### Section Ordering

Poster A uses active zones from `poster_a` in database sort order:

1. Photo
2. Preferred name
3. Under my photo
4. What to call me
5. My life in brief
6. Talk to me about
7. Things that help me feel good or comfortable
8. Please know

Mapped question intent:

- Photo: Q35
- Preferred name: Q2
- Under my photo: Q36
- What to call me: Q2, Q3
- My life in brief: Q4, Q5, Q6, Q17
- Talk to me about: Q12, Q13, Q14
- Things that help me feel good or comfortable: Q10, Q16, Q27
- Please know: Q17, Q34, Q37, subject to visibility rules

### Layout

Poster A should use a two-column upper area and structured lower zones.

Recommended composition:

- top-left: portrait photo
- top-right: preferred name and photo caption
- middle: personal orientation zones
- lower: practical personal notes
- footer: compact disclaimer

Wireframe:

```text
+--------------------------------------------------+
| POSTER A: WHO I AM                               |
|                                                  |
| +-------------------+  Preferred name            |
| |                   |  Large name text           |
| |      PHOTO        |                            |
| |                   |  Under my photo            |
| +-------------------+  One sentence/tagline      |
|                                                  |
| What to call me                                  |
| [answers]                                        |
|                                                  |
| My life in brief                                 |
| [answers across 1-2 compact blocks]              |
|                                                  |
| Talk to me about        Things that help me feel |
| [answers]               good or comfortable      |
|                         [answers]                |
|                                                  |
| Please know                                      |
| [short important notes]                          |
|                                                  |
| Compact disclaimer footer                        |
+--------------------------------------------------+
```

### Handling Missing Answers

- If no photo exists, the name area expands into the photo column.
- If Q36 is missing, omit the caption area.
- If a lower zone has no printable answers, remove the zone and allow neighbouring zones to expand.
- If preferred name is missing, use full name only if Q1 is poster-visible; otherwise use a neutral title such as "Who I Am".

### Handling Private Answers

Private answers are excluded before layout. If a private answer was the only content in a zone, the zone is omitted.

Q37 must not appear on Poster A if marked private. If it remains poster-visible, render only as user-entered content and do not imply it has been hidden elsewhere.

### Handling Long Answers

Poster A is glance-first. Each zone should have a maximum rendered depth:

- Under my photo: 2 lines
- What to call me: 3 lines
- My life in brief: 6 lines total
- Talk to me about: 4 lines
- Things that help me feel good or comfortable: 4 lines
- Please know: 4 lines

If content exceeds the zone, reduce body text no lower than 10.5 pt. If it still overflows, show the first complete sentences that fit and add "More detail in booklet" only when a booklet will also be generated.

### Handling Photographs

- Preferred photo aspect ratio: 4:5 portrait
- Display size target: 70 mm wide x 88 mm high
- Rounded corners: subtle, no more than 3 mm radius
- Avoid heavy frames or decorative effects
- If the image is too dark, do not automatically apply artistic filters; use the processed upload as stored

### Print Considerations

- Must print clearly in grayscale.
- Avoid pale text on coloured backgrounds.
- Keep photo and preferred name in the top third of the page.
- Avoid page breaks; Poster A must be one page.

### Accessibility Considerations

- Preferred name must be visually prominent.
- Reading order should be title, preferred name, photo caption, zones top to bottom.
- Avoid small all-caps labels.
- Use plain section headings that match seeded output labels.

### PDF Generation Considerations

- Render as a single A4 portrait page.
- Fail gracefully if content overflows by applying defined poster overflow rules.
- Do not generate a second Poster A page.
- Keep footer disclaimer in a reserved fixed-height area.

## Poster B - Helpful Things to Know When Supporting Me

### Purpose

Poster B communicates practical person-centred support preferences. It must remain non-directive and framed as helpful information.

### Page Size

- A4 portrait
- 210 mm x 297 mm
- printable content area: approximately 186 mm x 273 mm
- outer margin: 12 mm
- internal grid gap: 5-7 mm

### Typography Hierarchy

- Document title: 24-30 pt, bold
- Optional preferred name subtitle: 18-24 pt, semibold
- Zone headings: 13-15 pt, bold or semibold
- Zone body: 11-13 pt, regular
- Footer disclaimer: 6.5-7.5 pt, regular

### Section Ordering

Poster B uses active zones from `poster_b` in database sort order:

1. Mornings
2. Evenings and sleep
3. Communication style
4. If I seem upset
5. Things to ask me first
6. Comfort items or important routines

Mapped question intent:

- Mornings: Q19, Q20, Q21
- Evenings and sleep: Q22, Q23, Q24, Q25, Q26, Q28
- Communication style: Q8, Q9, Q31
- If I seem upset: Q11, Q32
- Things to ask me first: Q34, Q37, subject to visibility rules
- Comfort items or important routines: Q27, Q29, Q30, Q33

### Layout

Poster B should use a balanced practical grid. It can be denser than Poster A but must still be readable at a glance.

Recommended composition:

- title and optional preferred name at top
- six equal-priority zones in a two-column grid
- footer disclaimer

Wireframe:

```text
+--------------------------------------------------+
| POSTER B: HELPFUL THINGS TO KNOW WHEN SUPPORTING |
| Preferred name / person name                     |
|                                                  |
| +----------------------+ +----------------------+ |
| | Mornings             | | Evenings and sleep   | |
| | [answers]            | | [answers]            | |
| +----------------------+ +----------------------+ |
|                                                  |
| +----------------------+ +----------------------+ |
| | Communication style  | | If I seem upset      | |
| | [answers]            | | [answers]            | |
| +----------------------+ +----------------------+ |
|                                                  |
| +----------------------+ +----------------------+ |
| | Things to ask first  | | Comfort items or     | |
| | [answers]            | | important routines   | |
| |                      | | [answers]            | |
| +----------------------+ +----------------------+ |
|                                                  |
| Compact disclaimer footer                        |
+--------------------------------------------------+
```

### Handling Missing Answers

- Empty zones are omitted.
- If one zone in a row is omitted, the remaining zone may span both columns.
- If fewer than three zones have content, use larger body text and more whitespace rather than leaving visible gaps.
- If all zones are empty, do not generate Poster B; show an application-level message before generation in future UI work.

### Handling Private Answers

Private answers are excluded before layout. Booklet-only answers are excluded from Poster B.

If Q37 is marked private, it must not appear in "Things to ask me first".

### Handling Long Answers

Each zone should support approximately 5-7 body lines. The renderer may reduce body text to 10.5 pt but should not go smaller on a poster.

If a zone still overflows, preserve the highest-priority mapped answers first according to mapping sort order and add "More detail in booklet" when appropriate.

### Handling Photographs

Poster B does not require a large photograph. If the design later includes a small thumbnail, it should be optional and should not displace practical content. MVP Poster B should prioritize text readability over image display.

### Print Considerations

- Poster B must remain one page.
- Two-column cards must have enough gutter for imperfect home printer alignment.
- Use borders or background tint sparingly and ensure readable grayscale output.
- Avoid relying on colour to distinguish zones.

### Accessibility Considerations

- Zone headings should be plain and descriptive.
- Minimum body text should remain readable at arm's length.
- The visual order must match the reading order: top-left to bottom-right.
- Avoid icons unless they have text labels or are purely decorative.

### PDF Generation Considerations

- Use fixed grid regions with flexible row height.
- Calculate available text area per zone before rendering.
- Remove empty zones before grid layout to avoid awkward gaps.
- Keep footer disclaimer reserved and non-overlapping.

## Full Booklet

### Purpose

The Full Booklet provides a fuller personal identity and support passport. It should be coherent when many questions are skipped and should include all non-private answered questions allowed for booklet output.

### Page Size

- A4 portrait pages
- 210 mm x 297 mm
- printable content area: approximately 180 mm x 267 mm
- outer margin: 15 mm
- optional inner margin: 18 mm if duplex printing is later supported

### Typography Hierarchy

- Cover title: 28-34 pt, bold
- Person name on cover: 24-30 pt, bold
- Section title: 18-22 pt, bold
- Question heading: 11-13 pt, semibold
- Answer body: 10-11.5 pt, regular
- Footer/page number: 8-9 pt
- Disclaimer/body note: 8.5-10 pt

The booklet should feel calm, practical and respectful. It should not look like a raw questionnaire export.

### Section Ordering

The booklet uses active zones from `full_booklet` in database sort order:

1. About me
2. My life and story
3. People and connections
4. Preferences and interests
5. Daily rhythm
6. Helpful support information
7. Photo, poster and privacy

Mapped question intent:

- About me: Q1, Q2, Q3, Q17
- My life and story: Q4, Q5, Q6
- People and connections: Q7, Q8, Q9, Q12
- Preferences and interests: Q10, Q11, Q13, Q14, Q15, Q16
- Daily rhythm: Q19, Q20, Q21, Q22, Q23, Q24, Q25, Q26, Q28
- Helpful support information: Q27, Q29, Q30, Q31, Q32, Q33, Q34
- Photo, poster and privacy: Q18, Q35, Q36, Q37, subject to visibility rules

### Layout

Recommended booklet structure:

1. Cover page
2. Disclaimer and sharing note
3. Optional contents page if more than six pages are generated
4. Answer sections in database order
5. Optional photo page or photo block if a portrait is present

Wireframe cover:

```text
+--------------------------------------------------+
| CARE PASSPORT                                    |
|                                                  |
| +-------------------+                            |
| |                   |                            |
| |      PHOTO        |   Person name              |
| |                   |   Preferred name/tagline   |
| +-------------------+                            |
|                                                  |
| A personal identity and support passport         |
|                                                  |
| Date generated                                   |
+--------------------------------------------------+
```

Wireframe section page:

```text
+--------------------------------------------------+
| Section title                                    |
| Short optional section description               |
|                                                  |
| Question heading                                 |
| Answer text wraps naturally across lines.        |
|                                                  |
| Question heading                                 |
| Longer answer text may continue onto the next    |
| page if needed, keeping heading and first lines   |
| together.                                        |
|                                                  |
|                                      Page number |
+--------------------------------------------------+
```

Wireframe disclaimer page:

```text
+--------------------------------------------------+
| About This Document                              |
|                                                  |
| This document is intended to help people         |
| understand the person behind the resident. It    |
| shares personal history, preferences and         |
| routines. It is not a clinical document and does |
| not replace assessment, planning, procedures or  |
| professional judgement.                          |
|                                                  |
| Privacy note                                     |
| Printed copies may be read by people who can     |
| access them in the support setting.              |
+--------------------------------------------------+
```

### Handling Missing Answers

- Omit skipped and empty answers.
- Omit sections with no printable answers.
- Do not show unanswered questions.
- If only one section has content, the booklet still gets a cover and disclaimer page, then that section.
- If no printable answers exist, do not generate the booklet; show an application-level message before generation in future UI work.

### Handling Private Answers

Private answers are excluded entirely. The booklet must not include private answers, even in a privacy appendix.

Booklet-visible and poster-visible answers may appear in the booklet.

### Handling Long Answers

The booklet should preserve full answers. Long answers may span pages, but the layout should avoid widows and orphaned headings:

- keep question heading with at least two answer lines
- avoid ending a page immediately after a heading
- allow answer blocks to continue cleanly across pages
- preserve paragraph breaks from user input
- avoid shrinking body text below 10 pt

### Handling Photographs

If a portrait exists:

- show it on the cover or first content page
- use the processed portrait image
- maintain a 4:5 portrait crop where possible
- keep image size moderate so text remains primary

If the user answered Q35 or Q36, render those answers as text according to their visibility, not as image metadata.

### Print Considerations

- Designed for A4 home printing.
- Should work single-sided by default.
- Page numbers should appear from the first content page onward.
- Avoid large ink-heavy background fills.
- Use page breaks before major sections when it improves readability.
- Keep margins generous for hole punching or folder storage.

### Accessibility Considerations

- Use semantic heading order in the PDF structure where supported.
- Ensure reading order follows visual order.
- Preserve text as selectable text, not flattened images.
- Use high contrast and readable body text.
- Include document title metadata.
- Include alt text for portrait photo where supported, such as "Portrait photo" plus the person's preferred name if available.

### PDF Generation Considerations

- Generate from a prepared booklet data structure grouped by active output zones.
- Use repeatable page headers or footers with page numbers.
- Reserve cover and disclaimer pages before flowing answer sections.
- Support dynamic pagination.
- Exclude empty sections before page layout.
- Keep generated files outside the public web root unless downloads are access-controlled.

## Layout QA Checklist

Before an output is accepted for MVP testing:

- Posters fit on one A4 page each.
- Poster text is readable at a glance.
- Booklet sections remain coherent when many questions are skipped.
- Private answers are absent from every PDF.
- Booklet-only answers are absent from posters.
- Empty headings and empty zones are absent.
- Portrait photos are cropped consistently and not distorted.
- PDFs print legibly in grayscale.
- The disclaimer is present.
- Language remains person-centred and non-directive.
