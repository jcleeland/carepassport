# Care Passport - Question Analysis

## Purpose

This document reviews the approved 37-question MVP framework for output design and PDF rendering.

It identifies:

- questions likely to produce long answers
- questions likely to produce short answers
- questions suitable for posters
- questions that may create privacy concerns
- questions that may require special formatting in PDFs

This is a design and content-analysis document only. It does not define UI, application code, HTML or PDF templates.

## Summary

The question set mixes identity, life story, communication, preferences, daily rhythm, practical support information, photo selection and privacy controls.

For output design, the main risks are:

- long narrative answers overwhelming posters
- sensitive personal information appearing on visible outputs
- routine or timing answers needing compact formatting
- practical support details needing clear labels without sounding directive
- photo and poster-caption answers requiring special placement

Poster outputs should prefer short, high-signal answers. The booklet should preserve fuller context while still omitting private, skipped and empty answers.

## Length Classification

### Likely Long Answers

These questions are likely to produce narrative or multi-part answers and should be treated as booklet-friendly by default unless deliberately mapped to a concise poster zone:

- Q4: Where did you grow up, and what was it like?
- Q5: What work or roles defined your life? (paid work, parenting, volunteering)
- Q6: What are you most proud of?
- Q7: Who are the most important people in your life?
- Q13: What have been your biggest passions or hobbies?
- Q14: What music, TV, sport, or creative pursuits do you love?
- Q15: What foods and drinks do you love - and hate?
- Q16: What does a good day look like for you?
- Q17: What's the most important thing someone should know about who you are?
- Q21: What does your ideal morning look like, step by step?
- Q23: What helps you wind down at night?
- Q25: How often do you wake overnight? Do you get up to toilet?
- Q29: Any personal care products or comfort items supporters should know about?
- Q30: Any mobility aids or physical considerations supporters should know?
- Q32: If you're ever distressed or confused, what helps you feel calm?
- Q33: What does it look like when someone is supporting you well?
- Q34: Is there anything you never want done without being asked first?
- Q37: Anything from above you'd prefer kept private - booklet only, not the poster?

PDF handling:

- Use full-width answer blocks in the booklet.
- Preserve paragraph breaks.
- Avoid placing the question heading alone at the bottom of a page.
- For posters, cap visible text and prefer the first complete sentence or first short paragraph.

### Likely Short Answers

These questions are likely to produce names, times, brief preferences or short phrases:

- Q1: What is your full name?
- Q2: What do you like to be called?
- Q3: Is there a name you dislike being called?
- Q8: Are you a talker or do you prefer quiet?
- Q10: What puts you in a good mood?
- Q12: One easy conversation topic you love?
- Q19: Do you wake up ready to go, or do you need time to ease in?
- Q20: What time do you like to get up?
- Q22: What time do you go to bed?
- Q24: Light or heavy sleeper? How many hours do you need?
- Q26: Do you nap? When and for how long?
- Q27: What comfort items or important things do you like to keep nearby?
- Q28: Preferred room temperature and light level overnight?
- Q31: Do you use hearing aids or glasses?
- Q35: Which photo best represents you as you'd like to be known?
- Q36: One sentence to sit under your photo on your poster?

PDF handling:

- These can render as compact callouts, chips, short bullet lines or small answer blocks.
- Timing questions can be paired visually in the booklet.
- Poster caption content should have a strict line limit.

## Poster Suitability

### Strong Poster Candidates

These answers are usually clear, useful at a glance and suitable for Poster A or Poster B when visibility allows:

- Q2: preferred name
- Q3: disliked name
- Q8: talker or quiet preference
- Q9: chat or calm preference during personal support
- Q10: good mood triggers
- Q12: easy conversation topic
- Q16: good day summary
- Q17: most important thing to know
- Q19: waking style
- Q21: ideal morning, if concise
- Q23: winding down at night, if concise
- Q27: comfort items nearby
- Q32: what helps when distressed or confused
- Q34: ask-first preference
- Q35: representative photo
- Q36: poster sentence

Poster handling:

- Prefer concise display zones over full question-and-answer rendering.
- Combine related answers where the mapped output zone supports it.
- Omit empty zones instead of showing missing fields.

### Conditional Poster Candidates

These may work on posters if short, but can become too long or sensitive:

- Q4: grew up story
- Q5: defining work or roles
- Q6: proud of
- Q7: important people
- Q11: frustration or upset triggers
- Q13: passions or hobbies
- Q14: music, TV, sport or creative pursuits
- Q15: foods and drinks loved or disliked
- Q24: sleep depth and hours
- Q25: overnight waking and toileting
- Q28: overnight temperature and light
- Q29: personal products or comfort items
- Q30: mobility aids or physical considerations
- Q31: hearing aids or glasses
- Q33: what good support looks like
- Q37: poster privacy preference

Poster handling:

- Use only if answer visibility is `poster` and content remains readable.
- Give users clear visibility controls before generation.
- Prefer booklet placement when answers include sensitive, medical-adjacent or highly personal details.

### Booklet-First Questions

These are better suited to the booklet because they are likely detailed, contextual or personal:

- Q1: full name
- Q4-Q7: life story and people
- Q11: frustration or upset triggers
- Q13-Q15: interests and food details
- Q20, Q22, Q24-Q26, Q28: timing and sleep details
- Q29-Q31: personal products, physical considerations, aids and glasses
- Q33: what good support looks like
- Q37: privacy preference

## Privacy Concerns

### Higher Privacy Sensitivity

These questions may reveal health, disability, family, personal routine, distress or dignity-related information:

- Q3: disliked name
- Q7: important people
- Q9: preferences during personal support
- Q11: what frustrates or upsets the person
- Q15: food and drink dislikes, if linked to health or culture
- Q24: sleep pattern
- Q25: waking overnight and toileting
- Q26: naps
- Q28: overnight room preferences
- Q29: personal products or comfort items
- Q30: mobility aids or physical considerations
- Q31: hearing aids or glasses
- Q32: distress or confusion support
- Q34: never done without being asked
- Q35: representative photo
- Q37: privacy preference itself

Recommended handling:

- Default to conservative placement where possible.
- Ensure per-answer visibility controls are prominent before PDF generation.
- Remind users that posters may be seen by people in the room or support setting.
- Exclude private answers from all PDFs.

### Medium Privacy Sensitivity

These questions are usually safe but may contain sensitive details depending on the answer:

- Q4: grew up story
- Q5: defining work or roles
- Q6: proud of
- Q13: passions or hobbies
- Q14: music, TV, sport or creative pursuits
- Q16: good day
- Q17: most important thing to know
- Q18: anything else
- Q33: what good support looks like

Recommended handling:

- Let users mark these private if the answer contains personal history, family information or difficult memories.
- Do not infer sensitivity automatically from the question alone.

## PDF Formatting Concerns

### Names and Identity

Q1-Q3 should support short text, long names, pronunciation notes and name preferences.

PDF needs:

- large display treatment for Q2 on Poster A
- line wrapping for long names
- compact treatment for Q3, because it can sound negative if overemphasized

### Narrative Life Story

Q4-Q7 and Q13-Q17 can produce paragraphs.

PDF needs:

- booklet paragraph layout
- optional multi-answer section grouping
- poster truncation rules where mapped
- avoid dense blocks on posters

### Conversation and Communication Preferences

Q8-Q12 often produce high-value poster content.

PDF needs:

- short labels or grouped callouts
- preserve nuance for Q9 and Q11 in the booklet
- avoid styling that makes preferences appear like demands

### Timing and Daily Rhythm

Q19-Q28 include time, sequence and sleep preferences.

PDF needs:

- compact time formatting for Q20 and Q22
- paired morning/evening presentation in Poster B
- support for step-by-step answer layout for Q21
- careful treatment of Q25 because toileting details may be sensitive

### Practical Support Information

Q29-Q34 may include physical, sensory, product or consent-related preferences.

PDF needs:

- booklet-first rendering for detailed answers
- concise poster rendering only when visibility allows
- clear headings that remain non-directive
- avoid over-compressing details that affect dignity or comfort

### Photo and Poster Caption

Q35-Q36 are not ordinary text answers.

PDF needs:

- Q35 should inform photo selection or caption context, not be treated as a long body paragraph on posters
- Q36 should be rendered as a short caption under or near the portrait
- enforce a strict line limit for Q36 on posters

### Privacy Preference

Q37 is both content and a visibility signal.

PDF needs:

- do not treat Q37 as a substitute for per-answer visibility controls
- render Q37 only if its answer visibility allows it
- consider booklet placement by default
- never let Q37 expose content that the user marked private elsewhere

## Question-by-Question Matrix

| Q | Likely length | Poster suitability | Privacy concern | PDF formatting notes |
|---|---|---|---|---|
| 1 | Short | Low | Medium | Use for booklet identity; fallback display only if allowed. |
| 2 | Short | High | Low | Primary Poster A name treatment; allow wrapping. |
| 3 | Short | Medium | Medium | Useful but sensitive; keep compact and avoid emphasis. |
| 4 | Long | Conditional | Medium | Booklet paragraph; poster only as short excerpt. |
| 5 | Long | Conditional | Medium | Booklet narrative; may support life-in-brief zone. |
| 6 | Medium-long | Conditional | Medium | Good identity content; trim carefully for poster. |
| 7 | Medium-long | Conditional | High | Names/relationships may be sensitive; booklet-first. |
| 8 | Short | High | Low | Good Poster B communication cue. |
| 9 | Medium | High | High | Practical but personal; visibility must be clear. |
| 10 | Short-medium | High | Low | Strong poster content. |
| 11 | Medium-long | Conditional | High | May be sensitive; useful in booklet and Poster B if allowed. |
| 12 | Short | High | Low | Strong poster conversation starter. |
| 13 | Medium-long | Conditional | Medium | Good for booklet; poster if concise. |
| 14 | Medium | Conditional | Low | Can be list-like; use compact bullets if long. |
| 15 | Medium-long | Conditional | Medium | May need loved/disliked split formatting. |
| 16 | Medium-long | High | Medium | Strong poster content if concise. |
| 17 | Medium-long | High | Medium | Strong Poster A content; cap lines. |
| 18 | Medium-long | Low | Medium | Booklet catch-all; may require paragraph flow. |
| 19 | Short | High | Low | Strong Poster B morning cue. |
| 20 | Short | Medium | Low | Time formatting; pair with Q22 in booklet. |
| 21 | Long | Conditional | Low | Step-by-step formatting; poster excerpt only. |
| 22 | Short | Medium | Low | Time formatting; pair with Q20. |
| 23 | Medium-long | High | Low | Strong evenings/sleep poster content if concise. |
| 24 | Short-medium | Conditional | Medium | Compact sleep details; may be personal. |
| 25 | Medium-long | Low | High | Booklet-first; toileting detail needs privacy caution. |
| 26 | Short-medium | Low | Medium | Compact schedule style. |
| 27 | Short-medium | High | Medium | Strong comfort-item poster content. |
| 28 | Short-medium | Conditional | Medium | Compact environment preferences. |
| 29 | Medium-long | Conditional | High | Personal products may be sensitive; booklet-first. |
| 30 | Medium-long | Conditional | High | Practical detail; booklet-first unless explicitly poster-visible. |
| 31 | Short-medium | Conditional | High | Aids/glasses may be sensitive; compact formatting. |
| 32 | Medium-long | High | High | High-value support content; visibility caution. |
| 33 | Medium-long | Conditional | Medium | Booklet-first; avoid directive framing. |
| 34 | Medium-long | High | High | Strong consent/preference content; visibility caution. |
| 35 | Short-medium | High | High | Photo selection guidance; handle with portrait rules. |
| 36 | Short | High | Low | Poster caption; strict line limit. |
| 37 | Medium-long | Conditional | High | Privacy-related; do not override per-answer visibility. |

## Implementation Implications For Later Work

- The answer review screen should warn users before placing high-privacy answers on posters.
- Poster rendering should enforce per-zone text limits.
- Booklet rendering should support paragraphs, lists and page breaks.
- Timing answers should support compact formatting without changing the user's wording.
- Photo-related answers should be treated as output metadata or caption content where appropriate.
- Question-level defaults are useful, but per-answer visibility remains the final control.
