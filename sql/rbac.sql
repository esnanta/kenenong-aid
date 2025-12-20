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

INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('accessRouteShelter.index', 2, 'Index Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter.create', 2, 'Create Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter.update', 2, 'Update Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter.view', 2, 'View Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter.delete', 2, 'Delete Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter.report', 2, 'Report Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('accessRouteStatus.index', 2, 'Index Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus.create', 2, 'Create Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus.update', 2, 'Update Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus.view', 2, 'View Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus.delete', 2, 'Delete Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus.report', 2, 'Report Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('accessRouteVehicles.index', 2, 'Index Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicles.create', 2, 'Create Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicles.update', 2, 'Update Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicles.view', 2, 'View Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicles.delete', 2, 'Delete Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicles.report', 2, 'Report Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


/* PERMISSION: DISASTER (MASTER DETAIL) */
INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('disasterType.index', 2, 'Index Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType.create', 2, 'Create Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType.update', 2, 'Update Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType.view', 2, 'View Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType.delete', 2, 'Delete Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType.report', 2, 'Report Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('disasterStatus.index', 2, 'Index Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus.create', 2, 'Create Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus.update', 2, 'Update Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus.view', 2, 'View Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus.delete', 2, 'Delete Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus.report', 2, 'Report Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

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
VALUES ('master.index', 'disasterType.index'),
       ('master.create', 'disasterType.create'),
       ('master.update', 'disasterType.update'),
       ('master.view', 'disasterType.view'),
       ('master.delete', 'disasterType.delete'),
       ('master.report', 'disasterType.report');

INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master.index', 'disasterStatus.index'),
       ('master.create', 'disasterStatus.create'),
       ('master.update', 'disasterStatus.update'),
       ('master.view', 'disasterStatus.view'),
       ('master.delete', 'disasterStatus.delete'),
       ('master.report', 'disasterStatus.report');

INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master.index', 'disaster.index'),
       ('master.create', 'disaster.create'),
       ('master.update', 'disaster.update'),
       ('master.view', 'disaster.view'),
       ('master.delete', 'disaster.delete'),
       ('master.report', 'disaster.report');

/* TRANSACTION → DETAIL */
/* TRANSACTION → ACCESS ROUTE SHELTER*/
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('transaction.index', 'accessRouteShelter.index'),
       ('transaction.create', 'accessRouteShelter.create'),
       ('transaction.update', 'accessRouteShelter.update'),
       ('transaction.view', 'accessRouteShelter.view'),
       ('transaction.delete', 'accessRouteShelter.delete'),
       ('transaction.report', 'accessRouteShelter.report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction.index', 'accessRouteStatus.index'),
       ('transaction.create', 'accessRouteStatus.create'),
       ('transaction.update', 'accessRouteStatus.update'),
       ('transaction.view', 'accessRouteStatus.view'),
       ('transaction.delete', 'accessRouteStatus.delete'),
       ('transaction.report', 'accessRouteStatus.report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction.index', 'accessRouteVehicles.index'),
       ('transaction.create', 'accessRouteVehicles.create'),
       ('transaction.update', 'accessRouteVehicles.update'),
       ('transaction.view', 'accessRouteVehicles.view'),
       ('transaction.delete', 'accessRouteVehicles.delete'),
       ('transaction.report', 'accessRouteVehicles.report');



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