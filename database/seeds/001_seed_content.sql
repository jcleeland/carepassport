-- Life & Care Passport MVP seed content draft.
-- This file is documentation-quality SQL and may need adjustment to match final migrations.

-- Completion modes
INSERT INTO completion_modes (slug, label, description, sort_order) VALUES
('self', 'I am completing this for myself', 'The resident is completing their own Life & Care Passport.', 1),
('assisted', 'I am helping someone complete this', 'A family member or trusted helper is assisting the resident.', 2),
('proxy', 'I am completing this on behalf of someone who cannot complete it themselves', 'A family member or trusted person is answering based on what they know about the resident.', 3);

-- Question paths
INSERT INTO question_paths (slug, title, description, sort_order) VALUES
('essential_12', 'Essential 12', 'A shorter pathway for creating useful posters quickly.', 1),
('recommended_25', 'Recommended 25', 'A fuller pathway for a richer booklet and useful posters.', 2),
('full_37', 'Full 37', 'The complete Life & Care Passport question set.', 3);
