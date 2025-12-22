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
VALUES ('master-index', 2, 'Index Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-create', 2, 'Create Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-update', 2, 'Update Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-view', 2, 'View Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-delete', 2, 'Delete Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-report', 2, 'Report Master', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

/* PERMISSION: TRANSACTION GROUP */

INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('transaction-index', 2, 'Index Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-create', 2, 'Create Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-update', 2, 'Update Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-view', 2, 'View Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-delete', 2, 'Delete Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-report', 2, 'Report Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


/* PERMISSION: ACCESS ROUTE (MASTER DETAIL) */
INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('accessRoute-index', 2, 'Index Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-create', 2, 'Create Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-update', 2, 'Update Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-view', 2, 'View Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-delete', 2, 'Delete Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-report', 2, 'Report Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('accessRouteShelter-index', 2, 'Index Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-create', 2, 'Create Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-update', 2, 'Update Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-view', 2, 'View Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-delete', 2, 'Delete Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-report', 2, 'Report Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('accessRouteStatus-index', 2, 'Index Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-create', 2, 'Create Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-update', 2, 'Update Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-view', 2, 'View Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-delete', 2, 'Delete Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-report', 2, 'Report Access Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('accessRouteVehicle-index', 2, 'Index Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-create', 2, 'Create Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-update', 2, 'Update Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-view', 2, 'View Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-delete', 2, 'Delete Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-report', 2, 'Report Access Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


/* PERMISSION: DISASTER (MASTER DETAIL) */
INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('disasterType-index', 2, 'Index Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-create', 2, 'Create Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-update', 2, 'Update Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-view', 2, 'View Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-delete', 2, 'Delete Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-report', 2, 'Report Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('disasterStatus-index', 2, 'Index Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-create', 2, 'Create Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-update', 2, 'Update Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-view', 2, 'View Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-delete', 2, 'Delete Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-report', 2, 'Report Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
    (name, type, description, created_at, updated_at)
VALUES ('disaster-index', 2, 'Index Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-create', 2, 'Create Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-update', 2, 'Update Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-view', 2, 'View Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-delete', 2, 'Delete Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-report', 2, 'Report Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('aidPlan-index', 2, 'Index Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-create', 2, 'Create Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-update', 2, 'Update Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-view', 2, 'View Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-delete', 2, 'Delete Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-report', 2, 'Report Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('aidDistribution-index', 2, 'Index Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-create', 2, 'Create Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-update', 2, 'Update Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-view', 2, 'View Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-delete', 2, 'Delete Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-report', 2, 'Report Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('entityType-index', 2, 'Index Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-create', 2, 'Create Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-update', 2, 'Update Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-view', 2, 'View Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-delete', 2, 'Delete Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-report', 2, 'Report Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('itemCategory-index', 2, 'Index Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-create', 2, 'Create Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-update', 2, 'Update Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-view', 2, 'View Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-delete', 2, 'Delete Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-report', 2, 'Report Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('item-index', 2, 'Index Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-create', 2, 'Create Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-update', 2, 'Update Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-view', 2, 'View Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-delete', 2, 'Delete Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-report', 2, 'Report Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('mediaFile-index', 2, 'Index Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-create', 2, 'Create Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-update', 2, 'Update Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-view', 2, 'View Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-delete', 2, 'Delete Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-report', 2, 'Report Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('profile-index', 2, 'Index Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-create', 2, 'Create Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-update', 2, 'Update Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-view', 2, 'View Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-delete', 2, 'Delete Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-report', 2, 'Report Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('shelter.index', 2, 'Index Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter.create', 2, 'Create Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter.update', 2, 'Update Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter.view', 2, 'View Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter.delete', 2, 'Delete Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter.report', 2, 'Report Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('unit-index', 2, 'Index Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-create', 2, 'Create Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-update', 2, 'Update Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-view', 2, 'View Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-delete', 2, 'Delete Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-report', 2, 'Report Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('vehicleType-index', 2, 'Index Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-create', 2, 'Create Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-update', 2, 'Update Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-view', 2, 'View Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-delete', 2, 'Delete Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-report', 2, 'Report Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('verificationAction-index', 2, 'Index Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-create', 2, 'Create Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-update', 2, 'Update Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-view', 2, 'View Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-delete', 2, 'Delete Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-report', 2, 'Report Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('verification-index', 2, 'Index Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-create', 2, 'Create Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-update', 2, 'Update Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-view', 2, 'View Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-delete', 2, 'Delete Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-report', 2, 'Report Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('verificationVote-index', 2, 'Index Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-create', 2, 'Create Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-update', 2, 'Update Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-view', 2, 'View Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-delete', 2, 'Delete Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-report', 2, 'Report Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO t_auth_item
(name, type, description, created_at, updated_at)
VALUES ('user-index', 2, 'Index User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-create', 2, 'Create User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-update', 2, 'Update User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-view', 2, 'View User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-delete', 2, 'Delete User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-report', 2, 'Report User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


/* MASTER → DETAIL */
/* MASTER → ACCESS ROUTE */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master-index', 'accessRoute-index'),
       ('master-create', 'accessRoute-create'),
       ('master-update', 'accessRoute-update'),
       ('master-view', 'accessRoute-view'),
       ('master-delete', 'accessRoute-delete'),
       ('master-report', 'accessRoute-report');

/* MASTER → DISASTER */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master-index', 'disasterType-index'),
       ('master-create', 'disasterType-create'),
       ('master-update', 'disasterType-update'),
       ('master-view', 'disasterType-view'),
       ('master-delete', 'disasterType-delete'),
       ('master-report', 'disasterType-report');

INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master-index', 'disasterStatus-index'),
       ('master-create', 'disasterStatus-create'),
       ('master-update', 'disasterStatus-update'),
       ('master-view', 'disasterStatus-view'),
       ('master-delete', 'disasterStatus-delete'),
       ('master-report', 'disasterStatus-report');

INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('master-index', 'disaster-index'),
       ('master-create', 'disaster-create'),
       ('master-update', 'disaster-update'),
       ('master-view', 'disaster-view'),
       ('master-delete', 'disaster-delete'),
       ('master-report', 'disaster-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'verificationAction-index'),
       ('master-create', 'verificationAction-create'),
       ('master-update', 'verificationAction-update'),
       ('master-view', 'verificationAction-view'),
       ('master-delete', 'verificationAction-delete'),
       ('master-report', 'verificationAction-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'vehicleType-index'),
       ('master-create', 'vehicleType-create'),
       ('master-update', 'vehicleType-update'),
       ('master-view', 'vehicleType-view'),
       ('master-delete', 'vehicleType-delete'),
       ('master-report', 'vehicleType-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'unit-index'),
       ('master-create', 'unit-create'),
       ('master-update', 'unit-update'),
       ('master-view', 'unit-view'),
       ('master-delete', 'unit-delete'),
       ('master-report', 'unit-report');


INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'shelter.index'),
       ('master-create', 'shelter.create'),
       ('master-update', 'shelter.update'),
       ('master-view', 'shelter.view'),
       ('master-delete', 'shelter.delete'),
       ('master-report', 'shelter.report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'profile-index'),
       ('master-create', 'profile-create'),
       ('master-update', 'profile-update'),
       ('master-view', 'profile-view'),
       ('master-delete', 'profile-delete'),
       ('master-report', 'profile-report');



INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'item-index'),
       ('master-create', 'item-create'),
       ('master-update', 'item-update'),
       ('master-view', 'item-view'),
       ('master-delete', 'item-delete'),
       ('master-report', 'item-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'itemCategory-index'),
       ('master-create', 'itemCategory-create'),
       ('master-update', 'itemCategory-update'),
       ('master-view', 'itemCategory-view'),
       ('master-delete', 'itemCategory-delete'),
       ('master-report', 'itemCategory-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('master-index', 'entityType-index'),
       ('master-create', 'entityType-create'),
       ('master-update', 'entityType-update'),
       ('master-view', 'entityType-view'),
       ('master-delete', 'entityType-delete'),
       ('master-report', 'entityType-report');

/* TRANSACTION → DETAIL */
/* TRANSACTION → ACCESS ROUTE SHELTER*/
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('transaction-index', 'accessRouteShelter-index'),
       ('transaction-create', 'accessRouteShelter-create'),
       ('transaction-update', 'accessRouteShelter-update'),
       ('transaction-view', 'accessRouteShelter-view'),
       ('transaction-delete', 'accessRouteShelter-delete'),
       ('transaction-report', 'accessRouteShelter-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction-index', 'accessRouteStatus-index'),
       ('transaction-create', 'accessRouteStatus-create'),
       ('transaction-update', 'accessRouteStatus-update'),
       ('transaction-view', 'accessRouteStatus-view'),
       ('transaction-delete', 'accessRouteStatus-delete'),
       ('transaction-report', 'accessRouteStatus-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction-index', 'accessRouteVehicle-index'),
       ('transaction-create', 'accessRouteVehicle-create'),
       ('transaction-update', 'accessRouteVehicle-update'),
       ('transaction-view', 'accessRouteVehicle-view'),
       ('transaction-delete', 'accessRouteVehicle-delete'),
       ('transaction-report', 'accessRouteVehicle-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction-index', 'verificationVote-index'),
       ('transaction-create', 'verificationVote-create'),
       ('transaction-update', 'verificationVote-update'),
       ('transaction-view', 'verificationVote-view'),
       ('transaction-delete', 'verificationVote-delete'),
       ('transaction-report', 'verificationVote-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction-index', 'verification-index'),
       ('transaction-create', 'verification-create'),
       ('transaction-update', 'verification-update'),
       ('transaction-view', 'verification-view'),
       ('transaction-delete', 'verification-delete'),
       ('transaction-report', 'verification-report');

INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction-index', 'mediaFile-index'),
       ('transaction-create', 'mediaFile-create'),
       ('transaction-update', 'mediaFile-update'),
       ('transaction-view', 'mediaFile-view'),
       ('transaction-delete', 'mediaFile-delete'),
       ('transaction-report', 'mediaFile-report');


INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction-index', 'aidPlan-index'),
       ('transaction-create', 'aidPlan-create'),
       ('transaction-update', 'aidPlan-update'),
       ('transaction-view', 'aidPlan-view'),
       ('transaction-delete', 'aidPlan-delete'),
       ('transaction-report', 'aidPlan-report');


INSERT INTO t_auth_item_child
(parent, child)
VALUES ('transaction-index', 'aidDistribution-index'),
       ('transaction-create', 'aidDistribution-create'),
       ('transaction-update', 'aidDistribution-update'),
       ('transaction-view', 'aidDistribution-view'),
       ('transaction-delete', 'aidDistribution-delete'),
       ('transaction-report', 'aidDistribution-report');


/* ASSIGN MASTER & TRANSACTION KE ROLE */
/* ADMIN */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('admin', 'master-index'),
       ('admin', 'master-create'),
       ('admin', 'master-update'),
       ('admin', 'master-view'),
       ('admin', 'master-delete'),
       ('admin', 'master-report'),
       ('admin', 'transaction-index'),
       ('admin', 'transaction-create'),
       ('admin', 'transaction-update'),
       ('admin', 'transaction-view'),
       ('admin', 'transaction-delete'),
       ('admin', 'transaction-report'),
       ('admin', 'user-index'),
       ('admin', 'user-create'),
       ('admin', 'user-update'),
       ('admin', 'user-view'),
       ('admin', 'user-delete'),
       ('admin', 'user-report');

/* REGULAR → TRANSACTION */
INSERT INTO t_auth_item_child
    (parent, child)
VALUES ('regular', 'transaction-index'),
       ('regular', 'transaction-create'),
       ('regular', 'transaction-update'),
       ('regular', 'transaction-view');

/* ASSIGN USER KE ROLE */
INSERT INTO t_auth_assignment
    (item_name, user_id, created_at)
VALUES ('admin', 1, UNIX_TIMESTAMP());