-- Care Passport MVP seed content.
-- This file is intentionally idempotent so it can be rerun during local setup.

START TRANSACTION;

INSERT INTO completion_modes (slug, label, description, sort_order, active)
VALUES
('self', 'I am completing this for myself', 'Use this when the person is completing their own passport.', 1, 1),
('assisted', 'I am helping someone complete this', 'Use this when someone is helping the person participate as much as they are able.', 2, 1),
('proxy', 'I am completing this on behalf of someone who cannot complete it themselves', 'Use this when answers are based on what a trusted person knows about the person.', 3, 1)
ON DUPLICATE KEY UPDATE
    label = VALUES(label),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    active = VALUES(active);

INSERT INTO support_contexts (slug, label, description, sort_order, active)
VALUES
('residential_aged_care', 'Residential aged care', 'For a person moving into or living in residential aged care.', 1, 1),
('home_care', 'Home care', 'For a person receiving support at home.', 2, 1),
('disability_support', 'Disability support', 'For a person receiving disability support.', 3, 1),
('hospital_stay', 'Hospital stay', 'For a person spending time in hospital.', 4, 1),
('rehabilitation', 'Rehabilitation', 'For a person receiving rehabilitation support.', 5, 1),
('respite_care', 'Respite care', 'For a person receiving short-term support.', 6, 1),
('palliative_care', 'Palliative care', 'For a person receiving palliative support.', 7, 1),
('supported_accommodation', 'Supported accommodation', 'For a person living in supported accommodation.', 8, 1),
('community_support', 'Community support', 'For a person receiving support in the community.', 9, 1),
('other', 'Other', 'For another support context.', 10, 1)
ON DUPLICATE KEY UPDATE
    label = VALUES(label),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    active = VALUES(active);

INSERT INTO intro_pages (slug, title, body_markdown, sort_order, active)
VALUES
('welcome', 'Welcome', 'Care Passport helps create a personal identity and support passport that can be shared with people involved in support. It focuses on the person, their preferences, routines, communication style and things that matter to them.', 1, 1),
('how_it_works', 'How It Works', 'You can answer as many or as few questions as you like. Questions can be skipped, answers can be edited later, and each answer can be marked for a poster, booklet or private family record.', 2, 1),
('note_for_families_and_supporters', 'A Note for Families and Supporters', 'If you are helping someone, include them as much as they are able and comfortable. If you are answering on their behalf, use what you know about them and treat sensitive or uncertain information carefully.', 3, 1),
('before_you_begin', 'Before You Begin', 'You may want a quiet moment, a portrait photo, and any family notes or memories that help describe the person. Every question is optional.', 4, 1),
('consent_and_privacy', 'Consent and Privacy', 'Printable outputs may be seen by people in the room or support setting. A bedside or support booklet is not truly private if it is kept nearby. Mark sensitive answers as private if you do not want them included in printed outputs.', 5, 1)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    body_markdown = VALUES(body_markdown),
    sort_order = VALUES(sort_order),
    active = VALUES(active);

INSERT INTO question_paths (slug, title, description, sort_order, active)
VALUES
('essential_12', 'Essential 12', 'A short pathway for quickly capturing the most useful identity and support information.', 1, 1),
('recommended_25', 'Recommended 25', 'A balanced pathway for a fuller portrait while keeping completion manageable.', 2, 1),
('full_37', 'Full 37', 'The full MVP question set for a richer personal identity and support passport.', 3, 1)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    active = VALUES(active);

UPDATE question_sections SET active = 0;

INSERT INTO question_sections (slug, module_label, title, description, sort_order, active)
VALUES
('identity', 'About Me', 'About Me', 'Names, identity and first things to know.', 1, 1),
('life_story', 'My Life', 'My Life and Story', 'Personal history, roles and experiences.', 2, 1),
('relationships', 'People', 'People and Connections', 'Important people and conversation preferences.', 3, 1),
('preferences', 'Preferences', 'Preferences and Interests', 'Mood, interests, food, drink and a good day.', 4, 1),
('daily_rhythm', 'Daily Rhythm', 'Daily Rhythm', 'Morning, evening, sleep and rest preferences.', 5, 1),
('support', 'Support', 'Helpful Support Information', 'Comfort items, practical considerations and what helps.', 6, 1),
('poster_privacy', 'Poster', 'Photo, Poster and Privacy', 'Photo choice, poster sentence and privacy preferences.', 7, 1)
ON DUPLICATE KEY UPDATE
    module_label = VALUES(module_label),
    title = VALUES(title),
    description = VALUES(description),
    sort_order = VALUES(sort_order),
    active = VALUES(active);

UPDATE questions
SET slug = CONCAT('legacy_', id)
WHERE canonical_number BETWEEN 1 AND 37;

INSERT INTO questions (slug, canonical_number, section_id, question_text, help_text, answer_type, default_visibility, active, sort_order)
VALUES
('full_name', 1, (SELECT id FROM question_sections WHERE slug = 'identity'), 'What is your full name?', NULL, 'text', 'booklet', 1, 1),
('preferred_name', 2, (SELECT id FROM question_sections WHERE slug = 'identity'), 'What do you like to be called?', NULL, 'text', 'poster', 1, 2),
('disliked_name', 3, (SELECT id FROM question_sections WHERE slug = 'identity'), 'Is there a name you dislike being called?', NULL, 'text', 'booklet', 1, 3),
('grew_up', 4, (SELECT id FROM question_sections WHERE slug = 'life_story'), 'Where did you grow up, and what was it like?', NULL, 'textarea', 'booklet', 1, 1),
('life_work_roles', 5, (SELECT id FROM question_sections WHERE slug = 'life_story'), 'What work or roles defined your life? (paid work, parenting, volunteering)', NULL, 'textarea', 'booklet', 1, 2),
('proud_of', 6, (SELECT id FROM question_sections WHERE slug = 'life_story'), 'What are you most proud of?', NULL, 'textarea', 'booklet', 1, 3),
('important_people', 7, (SELECT id FROM question_sections WHERE slug = 'relationships'), 'Who are the most important people in your life?', NULL, 'textarea', 'booklet', 1, 1),
('talker_or_quiet', 8, (SELECT id FROM question_sections WHERE slug = 'relationships'), 'Are you a talker or do you prefer quiet?', NULL, 'textarea', 'poster', 1, 2),
('personal_support_chat_preference', 9, (SELECT id FROM question_sections WHERE slug = 'relationships'), 'Do you like to be chatted with during personal care or support, or do you prefer calm?', NULL, 'textarea', 'booklet', 1, 3),
('good_mood', 10, (SELECT id FROM question_sections WHERE slug = 'preferences'), 'What puts you in a good mood?', NULL, 'textarea', 'poster', 1, 1),
('frustrate_or_upset', 11, (SELECT id FROM question_sections WHERE slug = 'preferences'), 'What is likely to frustrate or upset you?', NULL, 'textarea', 'booklet', 1, 2),
('easy_conversation_topic', 12, (SELECT id FROM question_sections WHERE slug = 'relationships'), 'One easy conversation topic you love?', NULL, 'textarea', 'poster', 1, 4),
('passions_hobbies', 13, (SELECT id FROM question_sections WHERE slug = 'preferences'), 'What have been your biggest passions or hobbies?', NULL, 'textarea', 'booklet', 1, 3),
('media_sport_creative', 14, (SELECT id FROM question_sections WHERE slug = 'preferences'), 'What music, TV, sport, or creative pursuits do you love?', NULL, 'textarea', 'booklet', 1, 4),
('foods_drinks_love_hate', 15, (SELECT id FROM question_sections WHERE slug = 'preferences'), 'What foods and drinks do you love — and hate?', NULL, 'textarea', 'booklet', 1, 5),
('good_day', 16, (SELECT id FROM question_sections WHERE slug = 'preferences'), 'What does a good day look like for you?', NULL, 'textarea', 'poster', 1, 6),
('most_important_thing', 17, (SELECT id FROM question_sections WHERE slug = 'identity'), 'What''s the most important thing someone should know about who you are?', NULL, 'textarea', 'poster', 1, 4),
('anything_else', 18, (SELECT id FROM question_sections WHERE slug = 'poster_privacy'), 'Anything else you''d like to add?', NULL, 'textarea', 'booklet', 1, 1),
('wake_up_style', 19, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'Do you wake up ready to go, or do you need time to ease in?', NULL, 'textarea', 'poster', 1, 1),
('get_up_time', 20, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'What time do you like to get up?', NULL, 'text', 'booklet', 1, 2),
('ideal_morning', 21, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'What does your ideal morning look like, step by step?', NULL, 'textarea', 'poster', 1, 3),
('bedtime', 22, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'What time do you go to bed?', NULL, 'text', 'booklet', 1, 4),
('wind_down_at_night', 23, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'What helps you wind down at night?', NULL, 'textarea', 'poster', 1, 5),
('sleep_depth_hours', 24, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'Light or heavy sleeper? How many hours do you need?', NULL, 'textarea', 'booklet', 1, 6),
('wake_overnight_toilet', 25, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'How often do you wake overnight? Do you get up to toilet?', NULL, 'textarea', 'booklet', 1, 7),
('naps', 26, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'Do you nap? When and for how long?', NULL, 'textarea', 'booklet', 1, 8),
('nearby_comfort_items', 27, (SELECT id FROM question_sections WHERE slug = 'support'), 'What comfort items or important things do you like to keep nearby?', NULL, 'textarea', 'poster', 1, 1),
('overnight_temperature_light', 28, (SELECT id FROM question_sections WHERE slug = 'daily_rhythm'), 'Preferred room temperature and light level overnight?', NULL, 'textarea', 'booklet', 1, 9),
('personal_products_comfort_items', 29, (SELECT id FROM question_sections WHERE slug = 'support'), 'Any personal care products or comfort items supporters should know about?', NULL, 'textarea', 'booklet', 1, 2),
('mobility_physical_considerations', 30, (SELECT id FROM question_sections WHERE slug = 'support'), 'Any mobility aids or physical considerations supporters should know?', NULL, 'textarea', 'booklet', 1, 3),
('hearing_aids_glasses', 31, (SELECT id FROM question_sections WHERE slug = 'support'), 'Do you use hearing aids or glasses?', NULL, 'textarea', 'booklet', 1, 4),
('distressed_or_confused', 32, (SELECT id FROM question_sections WHERE slug = 'support'), 'If you''re ever distressed or confused, what helps you feel calm?', NULL, 'textarea', 'poster', 1, 5),
('supported_well', 33, (SELECT id FROM question_sections WHERE slug = 'support'), 'What does it look like when someone is supporting you well?', NULL, 'textarea', 'booklet', 1, 6),
('ask_first', 34, (SELECT id FROM question_sections WHERE slug = 'support'), 'Is there anything you never want done without being asked first?', NULL, 'textarea', 'poster', 1, 7),
('representative_photo', 35, (SELECT id FROM question_sections WHERE slug = 'poster_privacy'), 'Which photo best represents you as you''d like to be known?', NULL, 'textarea', 'poster', 1, 2),
('poster_sentence', 36, (SELECT id FROM question_sections WHERE slug = 'poster_privacy'), 'One sentence to sit under your photo on your poster?', NULL, 'textarea', 'poster', 1, 3),
('private_booklet_not_poster', 37, (SELECT id FROM question_sections WHERE slug = 'poster_privacy'), 'Anything from above you''d prefer kept private — booklet only, not the poster?', NULL, 'textarea', 'private', 1, 4)
ON DUPLICATE KEY UPDATE
    slug = VALUES(slug),
    canonical_number = VALUES(canonical_number),
    section_id = VALUES(section_id),
    question_text = VALUES(question_text),
    help_text = VALUES(help_text),
    answer_type = VALUES(answer_type),
    default_visibility = VALUES(default_visibility),
    active = VALUES(active),
    sort_order = VALUES(sort_order);

DELETE qpq
FROM question_path_questions AS qpq
INNER JOIN question_paths AS path ON path.id = qpq.question_path_id
WHERE path.slug IN ('essential_12', 'recommended_25', 'full_37');

INSERT INTO question_path_questions (question_path_id, question_id, path_sort_order)
SELECT path.id, question.id, mapping.path_sort_order
FROM (
    SELECT 'essential_12' AS path_slug, 2 AS canonical_number, 1 AS path_sort_order UNION ALL
    SELECT 'essential_12', 3, 2 UNION ALL
    SELECT 'essential_12', 5, 3 UNION ALL
    SELECT 'essential_12', 6, 4 UNION ALL
    SELECT 'essential_12', 7, 5 UNION ALL
    SELECT 'essential_12', 8, 6 UNION ALL
    SELECT 'essential_12', 9, 7 UNION ALL
    SELECT 'essential_12', 10, 8 UNION ALL
    SELECT 'essential_12', 12, 9 UNION ALL
    SELECT 'essential_12', 19, 10 UNION ALL
    SELECT 'essential_12', 32, 11 UNION ALL
    SELECT 'essential_12', 34, 12 UNION ALL
    SELECT 'recommended_25', 1, 1 UNION ALL
    SELECT 'recommended_25', 2, 2 UNION ALL
    SELECT 'recommended_25', 3, 3 UNION ALL
    SELECT 'recommended_25', 4, 4 UNION ALL
    SELECT 'recommended_25', 5, 5 UNION ALL
    SELECT 'recommended_25', 6, 6 UNION ALL
    SELECT 'recommended_25', 7, 7 UNION ALL
    SELECT 'recommended_25', 8, 8 UNION ALL
    SELECT 'recommended_25', 9, 9 UNION ALL
    SELECT 'recommended_25', 10, 10 UNION ALL
    SELECT 'recommended_25', 11, 11 UNION ALL
    SELECT 'recommended_25', 12, 12 UNION ALL
    SELECT 'recommended_25', 13, 13 UNION ALL
    SELECT 'recommended_25', 14, 14 UNION ALL
    SELECT 'recommended_25', 15, 15 UNION ALL
    SELECT 'recommended_25', 16, 16 UNION ALL
    SELECT 'recommended_25', 17, 17 UNION ALL
    SELECT 'recommended_25', 19, 18 UNION ALL
    SELECT 'recommended_25', 20, 19 UNION ALL
    SELECT 'recommended_25', 21, 20 UNION ALL
    SELECT 'recommended_25', 22, 21 UNION ALL
    SELECT 'recommended_25', 23, 22 UNION ALL
    SELECT 'recommended_25', 27, 23 UNION ALL
    SELECT 'recommended_25', 32, 24 UNION ALL
    SELECT 'recommended_25', 34, 25 UNION ALL
    SELECT 'full_37', canonical_number, canonical_number FROM questions
) AS mapping
INNER JOIN question_paths AS path ON path.slug = mapping.path_slug
INNER JOIN questions AS question ON question.canonical_number = mapping.canonical_number;

INSERT INTO output_templates (slug, title, description, active)
VALUES
('poster_a', 'Poster A - Who I Am', 'A quick personal orientation poster.', 1),
('poster_b', 'Poster B - Helpful Things to Know When Supporting Me', 'A practical person-centred support preferences poster.', 1),
('full_booklet', 'Full Booklet', 'A fuller personal identity and support passport.', 1)
ON DUPLICATE KEY UPDATE
    title = VALUES(title),
    description = VALUES(description),
    active = VALUES(active);

UPDATE output_template_zones AS zone
INNER JOIN output_templates AS template ON template.id = zone.output_template_id
SET zone.active = 0
WHERE template.slug IN ('poster_a', 'poster_b', 'full_booklet');

INSERT INTO output_template_zones (output_template_id, zone_key, label, sort_order, active)
SELECT template.id, zone.zone_key, zone.label, zone.sort_order, 1
FROM (
    SELECT 'poster_a' AS template_slug, 'photo' AS zone_key, 'Photo' AS label, 1 AS sort_order UNION ALL
    SELECT 'poster_a', 'preferred_name', 'Preferred name', 2 UNION ALL
    SELECT 'poster_a', 'photo_caption', 'Under my photo', 3 UNION ALL
    SELECT 'poster_a', 'what_to_call_me', 'What to call me', 4 UNION ALL
    SELECT 'poster_a', 'life_in_brief', 'My life in brief', 5 UNION ALL
    SELECT 'poster_a', 'talk_to_me_about', 'Talk to me about', 6 UNION ALL
    SELECT 'poster_a', 'feel_good_or_comfortable', 'Things that help me feel good or comfortable', 7 UNION ALL
    SELECT 'poster_a', 'please_know', 'Please know', 8 UNION ALL
    SELECT 'poster_b', 'mornings', 'Mornings', 1 UNION ALL
    SELECT 'poster_b', 'evenings_and_sleep', 'Evenings and sleep', 2 UNION ALL
    SELECT 'poster_b', 'communication_style', 'Communication style', 3 UNION ALL
    SELECT 'poster_b', 'if_i_seem_upset', 'If I seem upset', 4 UNION ALL
    SELECT 'poster_b', 'things_to_ask_first', 'Things to ask me first', 5 UNION ALL
    SELECT 'poster_b', 'comfort_items_or_routines', 'Comfort items or important routines', 6 UNION ALL
    SELECT 'full_booklet', 'about_me', 'About me', 1 UNION ALL
    SELECT 'full_booklet', 'my_life_and_story', 'My life and story', 2 UNION ALL
    SELECT 'full_booklet', 'people_and_connections', 'People and connections', 3 UNION ALL
    SELECT 'full_booklet', 'preferences_and_interests', 'Preferences and interests', 4 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 'Daily rhythm', 5 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 'Helpful support information', 6 UNION ALL
    SELECT 'full_booklet', 'photo_poster_and_privacy', 'Photo, poster and privacy', 7
) AS zone
INNER JOIN output_templates AS template ON template.slug = zone.template_slug
ON DUPLICATE KEY UPDATE
    label = VALUES(label),
    sort_order = VALUES(sort_order),
    active = VALUES(active);

DELETE mapping
FROM poster_mappings AS mapping
INNER JOIN output_template_zones AS zone ON zone.id = mapping.output_template_zone_id
INNER JOIN output_templates AS template ON template.id = zone.output_template_id
WHERE template.slug IN ('poster_a', 'poster_b', 'full_booklet');

INSERT INTO poster_mappings (output_template_zone_id, question_id, sort_order)
SELECT zone.id, question.id, mapping.sort_order
FROM (
    SELECT 'poster_a' AS template_slug, 'photo' AS zone_key, 35 AS canonical_number, 1 AS sort_order UNION ALL
    SELECT 'poster_a', 'preferred_name', 2, 1 UNION ALL
    SELECT 'poster_a', 'photo_caption', 36, 1 UNION ALL
    SELECT 'poster_a', 'what_to_call_me', 2, 1 UNION ALL
    SELECT 'poster_a', 'what_to_call_me', 3, 2 UNION ALL
    SELECT 'poster_a', 'life_in_brief', 4, 1 UNION ALL
    SELECT 'poster_a', 'life_in_brief', 5, 2 UNION ALL
    SELECT 'poster_a', 'life_in_brief', 6, 3 UNION ALL
    SELECT 'poster_a', 'life_in_brief', 17, 4 UNION ALL
    SELECT 'poster_a', 'talk_to_me_about', 12, 1 UNION ALL
    SELECT 'poster_a', 'talk_to_me_about', 13, 2 UNION ALL
    SELECT 'poster_a', 'talk_to_me_about', 14, 3 UNION ALL
    SELECT 'poster_a', 'feel_good_or_comfortable', 10, 1 UNION ALL
    SELECT 'poster_a', 'feel_good_or_comfortable', 16, 2 UNION ALL
    SELECT 'poster_a', 'feel_good_or_comfortable', 27, 3 UNION ALL
    SELECT 'poster_a', 'please_know', 17, 1 UNION ALL
    SELECT 'poster_a', 'please_know', 34, 2 UNION ALL
    SELECT 'poster_a', 'please_know', 37, 3 UNION ALL
    SELECT 'poster_b', 'mornings', 19, 1 UNION ALL
    SELECT 'poster_b', 'mornings', 20, 2 UNION ALL
    SELECT 'poster_b', 'mornings', 21, 3 UNION ALL
    SELECT 'poster_b', 'evenings_and_sleep', 22, 1 UNION ALL
    SELECT 'poster_b', 'evenings_and_sleep', 23, 2 UNION ALL
    SELECT 'poster_b', 'evenings_and_sleep', 24, 3 UNION ALL
    SELECT 'poster_b', 'evenings_and_sleep', 25, 4 UNION ALL
    SELECT 'poster_b', 'evenings_and_sleep', 26, 5 UNION ALL
    SELECT 'poster_b', 'evenings_and_sleep', 28, 6 UNION ALL
    SELECT 'poster_b', 'communication_style', 8, 1 UNION ALL
    SELECT 'poster_b', 'communication_style', 9, 2 UNION ALL
    SELECT 'poster_b', 'communication_style', 31, 3 UNION ALL
    SELECT 'poster_b', 'if_i_seem_upset', 11, 1 UNION ALL
    SELECT 'poster_b', 'if_i_seem_upset', 32, 2 UNION ALL
    SELECT 'poster_b', 'things_to_ask_first', 34, 1 UNION ALL
    SELECT 'poster_b', 'things_to_ask_first', 37, 2 UNION ALL
    SELECT 'poster_b', 'comfort_items_or_routines', 27, 1 UNION ALL
    SELECT 'poster_b', 'comfort_items_or_routines', 29, 2 UNION ALL
    SELECT 'poster_b', 'comfort_items_or_routines', 30, 3 UNION ALL
    SELECT 'poster_b', 'comfort_items_or_routines', 33, 4 UNION ALL
    SELECT 'full_booklet', 'about_me', 1, 1 UNION ALL
    SELECT 'full_booklet', 'about_me', 2, 2 UNION ALL
    SELECT 'full_booklet', 'about_me', 3, 3 UNION ALL
    SELECT 'full_booklet', 'about_me', 17, 4 UNION ALL
    SELECT 'full_booklet', 'my_life_and_story', 4, 1 UNION ALL
    SELECT 'full_booklet', 'my_life_and_story', 5, 2 UNION ALL
    SELECT 'full_booklet', 'my_life_and_story', 6, 3 UNION ALL
    SELECT 'full_booklet', 'people_and_connections', 7, 1 UNION ALL
    SELECT 'full_booklet', 'people_and_connections', 8, 2 UNION ALL
    SELECT 'full_booklet', 'people_and_connections', 9, 3 UNION ALL
    SELECT 'full_booklet', 'people_and_connections', 12, 4 UNION ALL
    SELECT 'full_booklet', 'preferences_and_interests', 10, 1 UNION ALL
    SELECT 'full_booklet', 'preferences_and_interests', 11, 2 UNION ALL
    SELECT 'full_booklet', 'preferences_and_interests', 13, 3 UNION ALL
    SELECT 'full_booklet', 'preferences_and_interests', 14, 4 UNION ALL
    SELECT 'full_booklet', 'preferences_and_interests', 15, 5 UNION ALL
    SELECT 'full_booklet', 'preferences_and_interests', 16, 6 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 19, 1 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 20, 2 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 21, 3 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 22, 4 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 23, 5 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 24, 6 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 25, 7 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 26, 8 UNION ALL
    SELECT 'full_booklet', 'daily_rhythm', 28, 9 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 27, 1 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 29, 2 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 30, 3 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 31, 4 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 32, 5 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 33, 6 UNION ALL
    SELECT 'full_booklet', 'helpful_support_information', 34, 7 UNION ALL
    SELECT 'full_booklet', 'photo_poster_and_privacy', 18, 1 UNION ALL
    SELECT 'full_booklet', 'photo_poster_and_privacy', 35, 2 UNION ALL
    SELECT 'full_booklet', 'photo_poster_and_privacy', 36, 3 UNION ALL
    SELECT 'full_booklet', 'photo_poster_and_privacy', 37, 4
) AS mapping
INNER JOIN output_templates AS template ON template.slug = mapping.template_slug
INNER JOIN output_template_zones AS zone
    ON zone.output_template_id = template.id
    AND zone.zone_key = mapping.zone_key
INNER JOIN questions AS question ON question.canonical_number = mapping.canonical_number;

COMMIT;
