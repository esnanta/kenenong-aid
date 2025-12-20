SET
FOREIGN_KEY_CHECKS = 0;

DELETE
FROM t_auth_assignment;
DELETE
FROM t_auth_item_child;
DELETE
FROM t_auth_item;

SET
FOREIGN_KEY_CHECKS = 1;

/* ROLE (type = 1) */
INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('admin', 1, 'Administrator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('coordinator', 1, 'Coordinator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('regular', 1, 'Regular User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('guest', 1, 'Guest', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

/* ROLE INHERITANCE */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('admin', 'coordinator'),
       ('coordinator', 'regular');


/* PERMISSION: MASTER GROUP */
INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('master.index', 2, 'Index Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master.create', 2, 'Create Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master.update', 2, 'Update Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master.view', 2, 'View Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master.delete', 2, 'Delete Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master.report', 2, 'Report Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

/* PERMISSION: TRANSACTION GROUP */

INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('transaction.index', 2, 'Index Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction.create', 2, 'Create Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction.update', 2, 'Update Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction.view', 2, 'View Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction.delete', 2, 'Delete Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction.report', 2, 'Report Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


/* PERMISSION: ACCESS ROUTE (MASTER DETAIL) */
INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('accessRoute.index', 2, 'Index Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute.create', 2, 'Create Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute.update', 2, 'Update Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute.view', 2, 'View Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute.delete', 2, 'Delete Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute.report', 2, 'Report Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


/* PERMISSION: DISASTER (MASTER DETAIL) */
INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('disaster.index', 2, 'Index Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster.create', 2, 'Create Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster.update', 2, 'Update Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster.view', 2, 'View Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster.delete', 2, 'Delete Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster.report', 2, 'Report Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

/* MASTER → DETAIL */
/* MASTER → ACCESS ROUTE */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master.index', 'accessRoute.index'),
       ('master.create', 'accessRoute.create'),
       ('master.update', 'accessRoute.update'),
       ('master.view', 'accessRoute.view'),
       ('master.delete', 'accessRoute.delete'),
       ('master.report', 'accessRoute.report');

/* MASTER → DISASTER */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master.index', 'disaster.index'),
       ('master.create', 'disaster.create'),
       ('master.update', 'disaster.update'),
       ('master.view', 'disaster.view'),
       ('master.delete', 'disaster.delete'),
       ('master.report', 'disaster.report');

/* ASSIGN MASTER & TRANSACTION KE ROLE */
/* ADMIN */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('admin', 'master.index'),
       ('admin', 'master.create'),
       ('admin', 'master.update'),
       ('admin', 'master.view'),
       ('admin', 'master.delete'),
       ('admin', 'master.report'),
       ('admin', 'transaction.index'),
       ('admin', 'transaction.create'),
       ('admin', 'transaction.update'),
       ('admin', 'transaction.view'),
       ('admin', 'transaction.delete'),
       ('admin', 'transaction.report');

/* REGULAR → TRANSACTION */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('regular', 'transaction.index'),
       ('regular', 'transaction.create'),
       ('regular', 'transaction.update'),
       ('regular', 'transaction.view');

/* ASSIGN USER KE ROLE */
INSERT INTO t_auth_assignment
    (item_name, user_id, created_at)
VALUES ('admin', 1, UNIX_TIMESTAMP());