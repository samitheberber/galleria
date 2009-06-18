-- File: configure_database.sql
-- This configures database. This creates test values.

-- Create admin user. Password: admin
INSERT INTO galleria.users (name, username, password) VALUES ('Demo Admin', 'demoadmin', '26c669cd0814ac40e5328752b21c4aa6450d16295e4eec30356a06a911c23983aaebe12d5da38eeebfc1b213be650498df8419194d5a26c7e0a50af156853c79');
INSERT INTO galleria.users (name, username, password) VALUES ('Demo User', 'demouser', '26c669cd0814ac40e5328752b21c4aa6450d16295e4eec30356a06a911c23983aaebe12d5da38eeebfc1b213be650498df8419194d5a26c7e0a50af156853c79');

-- Create admin group.
INSERT INTO galleria.groups (name, admin) VALUES
    ('Administrators', TRUE),
    ('Demo group', FALSE);

-- Insert admin user in admin group.
INSERT INTO galleria.usergroups (userid, groupid) VALUES
    (
        (SELECT id FROM galleria.users WHERE username = 'demoadmin'), -- gets id of admin user.
        (SELECT id FROM galleria.groups WHERE name = 'Demo group') -- gets id of admin group.
    ),
    (
        (SELECT id FROM galleria.users WHERE username = 'demoadmin'), -- gets id of admin user.
        (SELECT id FROM galleria.groups WHERE name = 'Administrators') -- gets id of admin group.
    ),
    (
        (SELECT id FROM galleria.users WHERE username = 'demouser'), -- gets id of admin user.
        (SELECT id FROM galleria.groups WHERE name = 'Demo group') -- gets id of admin group.
    );
