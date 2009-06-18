-- File: install_database.sql
-- This installs database. Database is ready to use, but it is totally empty of content.

-- galleria schema for our use.
CREATE SCHEMA galleria;

-- users table has all user data.
CREATE TABLE galleria.users (
    id serial PRIMARY KEY, -- this is user id.
    username character varying NOT NULL UNIQUE CHECK (char_length(username) > 0), -- this is username, which is used on log in.
    password character varying NOT NULL CHECK (char_length(password) > 0), -- this is password, which is used on log in.
    name character varying NOT NULL CHECK (char_length(name) > 0), -- this is name of user.
    active boolean DEFAULT TRUE -- this shows activity of user.
);

-- groups table has all group data.
CREATE TABLE galleria.groups (
    id serial PRIMARY KEY, -- this is group id.
    name character varying NOT NULL UNIQUE CHECK (char_length(name) > 0), -- this is name of group.
    admin boolean DEFAULT FALSE -- this is check for admin rights.
);

-- usergroups table hass all data of users groups relation.
CREATE TABLE galleria.usergroups (
    userid integer NOT NULL REFERENCES galleria.users(id) ON DELETE CASCADE ON UPDATE CASCADE, -- this is user id of relation.
    groupid integer NOT NULL REFERENCES galleria.groups(id) ON DELETE CASCADE ON UPDATE CASCADE, -- this is group id of relation.
    PRIMARY KEY (userid, groupid)
);

-- categories table has all category data.
CREATE TABLE galleria.categories (
    id serial PRIMARY KEY, -- this is category id
    parent integer REFERENCES galleria.categories(id) ON DELETE SET NULL ON UPDATE SET NULL, -- this is parent category.
    name character varying NOT NULL CHECK (char_length(name) > 0), -- this is name of category.
    weight integer DEFAULT 0
);

-- pictures table has all picture data.
CREATE TABLE galleria.pictures (
    id serial PRIMARY KEY, -- this is picture id, which is also used on path mapping.
    photographer character varying NOT NULL CHECK (char_length(photographer) > 0), -- this is name of photographer.
    year char(4) NOT NULL CHECK (char_length(year) = 4), -- this is year, when this picture was taken.
    description character varying NOT NULL CHECK (char_length(description) > 0), -- this is description of picture.
    categoryid integer REFERENCES galleria.categories(id) ON DELETE RESTRICT ON UPDATE RESTRICT, -- this is category id of relation.
    shown boolean DEFAULT FALSE -- this is value of showing picture.
);

-- categorygroups table has all data of categories groups relation.
CREATE TABLE galleria.categorygroups (
    categoryid integer NOT NULL REFERENCES galleria.categories(id) ON DELETE CASCADE ON UPDATE CASCADE, -- this is category id of relation.
    groupid integer NOT NULL REFERENCES galleria.groups(id) ON DELETE CASCADE ON UPDATE CASCADE, -- this is group id of relation.
    PRIMARY KEY (categoryid, groupid)
);

-- tags table has all tag data.
CREATE TABLE galleria.tags (
    id serial PRIMARY KEY, -- this is tag id.
    name character varying NOT NULL UNIQUE CHECK (char_length(name) > 0 AND char_length(name) <= 20) -- this is name of tag.
);

-- picturetags table has all data of pictures tags relation.
CREATE TABLE galleria.picturetags (
    pictureid integer NOT NULL REFERENCES galleria.pictures(id) ON DELETE CASCADE ON UPDATE CASCADE, -- this is picture id of relation.
    tagid integer NOT NULL REFERENCES galleria.tags(id) ON DELETE CASCADE ON UPDATE CASCADE, -- this is tag id of relation.
    PRIMARY KEY (pictureid, tagid)
);

-- picturemodifies table has all picture modify data.
CREATE TABLE galleria.picturemodifies (
    id serial PRIMARY KEY, -- this is modify id.
    pictureid integer NOT NULL REFERENCES galleria.pictures(id) ON DELETE CASCADE ON UPDATE CASCADE, -- this is picture id of relation.
    userid integer NOT NULL REFERENCES galleria.users(id) ON DELETE RESTRICT ON UPDATE RESTRICT, -- this is user id of relation.
    action character varying NOT NULL CHECK (char_length(action) > 0), -- this is action of modify.
    action_timestamp timestamp DEFAULT NOW() -- this is timestamp of action.
);
