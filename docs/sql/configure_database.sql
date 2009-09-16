-- File: configure_database.sql
-- This configures database. This creates for example views.

CREATE OR REPLACE VIEW galleria.picturedata AS
    SELECT p.id AS pid,
           p.photographer AS pg,
           p.year AS year,
           p.description AS desc,
           p.shown as shown,
           c.id AS cid,
           c.name AS cname,
           cg.groupid AS gid
    FROM galleria.pictures AS p
    LEFT JOIN galleria.categories AS c
        ON p.categoryid = c.id 
    LEFT JOIN galleria.categorygroups AS cg
        ON cg.categoryid = c.id
    ORDER BY pid DESC;

CREATE OR REPLACE VIEW galleria.tagdata AS
    SELECT t.id AS tid,
           t.name AS name,
           COUNT(pt.tagid) AS weight,
           (
                SELECT COUNT(*)
                FROM galleria.picturetags
           ) AS sum 
    FROM galleria.tags AS t 
    LEFT JOIN galleria.picturetags AS pt 
         ON pt.tagid = t.id 
    GROUP BY t.id,
             t.name 
    ORDER BY name ASC;

CREATE OR REPLACE VIEW galleria.tagsofpictures AS
    SELECT t.id AS tid,
           t.name AS name,
           pt.pictureid AS pid
    FROM galleria.tags AS t
    JOIN galleria.picturetags AS pt
         ON pt.tagid = t.id
    ORDER BY tid ASC;

CREATE OR REPLACE VIEW galleria.groupsofuser AS
    SELECT ug.userid AS uid,
           g.id AS gid,
           g.name AS name,
           g.admin AS adm
    FROM galleria.groups AS g
    JOIN galleria.usergroups AS ug
         ON ug.groupid = g.id
    ORDER BY uid;

CREATE OR REPLACE VIEW galleria.groupsofcategory AS
    SELECT cg.categoryid AS cid,
           g.id AS gid,
           g.name AS name,
           g.admin AS adm
    FROM galleria.groups AS g
    JOIN galleria.categorygroups AS cg
         ON cg.groupid = g.id
    ORDER BY cid;

CREATE OR REPLACE VIEW galleria.userdata AS
    SELECT u.id AS uid,
           u.username AS username,
           u.name AS name,
           u.active AS active,
           ug.groupid AS gid
    FROM galleria.users AS u
    LEFT JOIN galleria.usergroups AS ug
         ON ug.userid = u.id
    ORDER BY uid;

CREATE OR REPLACE VIEW galleria.categorylist AS
    SELECT c.id AS cid,
           c.parent AS cpar,
           c.name AS name,
           (SELECT count(*)>0 FROM galleria.pictures AS p WHERE p.categoryid = c.id AND p.shown = TRUE) AS haspics,
           cg.groupid AS gid
    FROM galleria.categories AS c
    LEFT JOIN galleria.categorygroups AS cg
              ON cg.categoryid = c.id
    ORDER BY cpar ASC, c.weight ASC;

CREATE OR REPLACE VIEW galleria.changelog AS
    SELECT pm.pictureid AS pid,
           u.name AS user,
           pm.action AS action,
           pm.action_timestamp AS timestamp
    FROM galleria.picturemodifies AS pm
    JOIN galleria.users AS u
         ON u.id = pm.userid
    ORDER BY timestamp ASC;

-- Functions
CREATE OR REPLACE FUNCTION galleria.prevpic(INTEGER, INTEGER)
    RETURNS INTEGER AS $$
            SELECT pid
            FROM galleria.picturedata
            WHERE pid < $1 AND cid = $2
            ORDER BY pid DESC
            LIMIT 1
    $$ LANGUAGE SQL;

CREATE OR REPLACE FUNCTION galleria.nextpic(INTEGER, INTEGER)
    RETURNS INTEGER AS $$
            SELECT pid
            FROM galleria.picturedata
            WHERE pid > $1 AND cid = $2
            ORDER BY pid ASC
            LIMIT 1
    $$ LANGUAGE SQL;
