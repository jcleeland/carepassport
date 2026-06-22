ALTER TABLE residents
    ADD COLUMN service_location_name VARCHAR(255) NULL AFTER support_context,
    ADD COLUMN location_reference VARCHAR(120) NULL AFTER service_location_name,
    ADD COLUMN primary_supporter_name VARCHAR(255) NULL AFTER location_reference,
    ADD COLUMN notes TEXT NULL AFTER primary_supporter_name;
