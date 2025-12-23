/*
=============================================================================
RBAC SYSTEM – CROWDSOURCED DISASTER MANAGEMENT
=============================================================================

FILOSOFI DESAIN
-----------------------------------------------------------------------------
1. Bottom-Up / Crowdsource
   - Data lapangan berasal dari relawan / warga (Regular)
   - Validasi dilakukan secara kolektif (Verification + Vote)
   - Moderasi oleh Koordinator wilayah
   - Tata kelola oleh Admin

2. Separation of Concern
   - Admin    : Governance & Master Data
   - Coordinator : Kurasi & Moderasi
   - Regular  : Input & Update Data Lapangan
   - Guest    : Transparansi Publik (Read-only)

3. Prinsip Keamanan
   - Delete adalah aksi kritis → dibatasi
   - Group permission (transaction-*) menjadi pengendali utama
   - Module permission adalah child dari group

4. Kontrak Controller
   - SETIAP modul tetap memiliki:
     index | create | update | view | delete | report
=============================================================================
*/


-- =========================================================================
-- A. RESET RBAC (BERSIH & AMAN)
-- =========================================================================
-- Semua data RBAC dikosongkan terlebih dahulu
-- FK dimatikan sementara agar tidak terjadi constraint error

SET
FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE t_auth_assignment;
TRUNCATE TABLE t_auth_item_child;
TRUNCATE TABLE t_auth_item;

SET
FOREIGN_KEY_CHECKS = 1;


-- =========================================================================
-- B. DEFINISI ROLE (type = 1)
-- =========================================================================
-- Role merepresentasikan aktor dalam sistem

INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('guest', 1, 'Public / Observer (Read-only)', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('regular', 1, 'Crowd / Relawan Lapangan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('coordinator', 1, 'Koordinator / Moderator Wilayah', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('admin', 1, 'Administrator Sistem / Governance', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- =========================================================================
-- C. INHERITANCE ROLE (HIRARKI)
-- =========================================================================
-- Admin memiliki seluruh hak Coordinator
-- Coordinator memiliki seluruh hak Regular

INSERT INTO t_auth_item_child (parent, child)
VALUES ('admin', 'coordinator'),
       ('coordinator', 'regular');


-- =========================================================================
-- D. PERMISSION GROUP (ABSTRAKSI) – type = 2
-- =========================================================================
-- Group permission digunakan untuk menyederhanakan assignment ke role

-- -------------------------------------------------------------------------
-- 1. MASTER GROUP
-- -------------------------------------------------------------------------
-- Master = data referensi, TIDAK berasal dari crowd
-- Hanya Admin yang berhak

INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('master-index', 2, 'Index Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-create', 2, 'Create Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-update', 2, 'Update Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-view', 2, 'View Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-delete', 2, 'Delete Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('master-report', 2, 'Report Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- -------------------------------------------------------------------------
-- 2. TRANSACTION GROUP
-- -------------------------------------------------------------------------
-- Transaction = data operasional lapangan
-- Ini adalah JANTUNG sistem crowdsource

INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('transaction-index', 2, 'Index Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-create', 2, 'Create Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-update', 2, 'Update Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-view', 2, 'View Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-delete', 2, 'Delete Transaction (Critical)', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('transaction-report', 2, 'Report Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- =========================================================================
-- E. DETAIL PER MODULE (SEMUA KONSISTEN controller-action)
-- =========================================================================

-- -------------------------------------------------------------------------
-- 1. DISASTER (Kejadian Bencana – Data Lapangan & Master)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('disaster-index', 2, 'Index Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-create', 2, 'Create Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-update', 2, 'Update Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-view', 2, 'View Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-delete', 2, 'Delete Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disaster-report', 2, 'Report Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('disasterType-index', 2, 'Index Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-create', 2, 'Create Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-update', 2, 'Update Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-view', 2, 'View Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-delete', 2, 'Delete Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterType-report', 2, 'Report Type Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('disasterStatus-index', 2, 'Index Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-create', 2, 'Create Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-update', 2, 'Update Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-view', 2, 'View Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-delete', 2, 'Delete Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('disasterStatus-report', 2, 'Report Status Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- -------------------------------------------------------------------------
-- 2. AID PLAN & DISTRIBUTION (Bantuan)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('aidPlan-index', 2, 'Index Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-create', 2, 'Create Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-update', 2, 'Update Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-view', 2, 'View Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-delete', 2, 'Delete Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidPlan-report', 2, 'Report Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('aidDistribution-index', 2, 'Index Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-create', 2, 'Create Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-update', 2, 'Update Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-view', 2, 'View Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-delete', 2, 'Delete Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('aidDistribution-report', 2, 'Report Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- -------------------------------------------------------------------------
-- 3. VERIFICATION (Validasi Crowd / Anti Hoax)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('verification-index', 2, 'Index Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-create', 2, 'Create Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-update', 2, 'Update Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-view', 2, 'View Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-delete', 2, 'Delete Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verification-report', 2, 'Report Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('verificationVote-index', 2, 'Index Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-create', 2, 'Create Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-update', 2, 'Update Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-view', 2, 'View Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-delete', 2, 'Delete Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationVote-report', 2, 'Report Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('verificationAction-index', 2, 'Index Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-create', 2, 'Create Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-update', 2, 'Update Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-view', 2, 'View Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-delete', 2, 'Delete Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('verificationAction-report', 2, 'Report Verification Action', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- -------------------------------------------------------------------------
-- 4. MEDIA FILE (Bukti Lapangan)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('mediaFile-index', 2, 'Index Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-create', 2, 'Create Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-update', 2, 'Update Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-view', 2, 'View Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-delete', 2, 'Delete Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('mediaFile-report', 2, 'Report Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- -------------------------------------------------------------------------
-- 5. ACCESS ROUTE (Akses & Rute)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('accessRoute-index', 2, 'Index Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-create', 2, 'Create Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-update', 2, 'Update Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-view', 2, 'View Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-delete', 2, 'Delete Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRoute-report', 2, 'Report Access Route', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('accessRouteShelter-index', 2, 'Index Access Route Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-create', 2, 'Create Access Route Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-update', 2, 'Update Access Route Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-view', 2, 'View Access Route Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-delete', 2, 'Delete Access Route Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteShelter-report', 2, 'Report Access Route Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('accessRouteStatus-index', 2, 'Index Access Route Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-create', 2, 'Create Access Route Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-update', 2, 'Update Access Route Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-view', 2, 'View Access Route Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-delete', 2, 'Delete Access Route Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteStatus-report', 2, 'Report Access Route Status', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('accessRouteVehicle-index', 2, 'Index Access Route Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-create', 2, 'Create Access Route Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-update', 2, 'Update Access Route Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-view', 2, 'View Access Route Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-delete', 2, 'Delete Access Route Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('accessRouteVehicle-report', 2, 'Report Access Route Vehicle', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- -------------------------------------------------------------------------
-- 6. SHELTER (Pengungsian)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('shelter-index', 2, 'Index Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter-create', 2, 'Create Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter-update', 2, 'Update Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter-view', 2, 'View Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter-delete', 2, 'Delete Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('shelter-report', 2, 'Report Shelter', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- -------------------------------------------------------------------------
-- 7. ITEM MANAGEMENT (Master & Transaksi)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('item-index', 2, 'Index Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-create', 2, 'Create Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-update', 2, 'Update Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-view', 2, 'View Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-delete', 2, 'Delete Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('item-report', 2, 'Report Item', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('itemCategory-index', 2, 'Index Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-create', 2, 'Create Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-update', 2, 'Update Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-view', 2, 'View Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-delete', 2, 'Delete Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('itemCategory-report', 2, 'Report Item Category', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('unit-index', 2, 'Index Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-create', 2, 'Create Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-update', 2, 'Update Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-view', 2, 'View Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-delete', 2, 'Delete Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('unit-report', 2, 'Report Unit', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- -------------------------------------------------------------------------
-- 8. MISC MASTER DATA
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('entityType-index', 2, 'Index Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-create', 2, 'Create Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-update', 2, 'Update Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-view', 2, 'View Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-delete', 2, 'Delete Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('entityType-report', 2, 'Report Entity Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('vehicleType-index', 2, 'Index Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-create', 2, 'Create Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-update', 2, 'Update Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-view', 2, 'View Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-delete', 2, 'Delete Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('vehicleType-report', 2, 'Report Vehicle Type', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- -------------------------------------------------------------------------
-- 9. USER MANAGEMENT & PROFILE (KHUSUS ADMIN)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES ('user-index', 2, 'Index User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-create', 2, 'Create User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-update', 2, 'Update User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-view', 2, 'View User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-delete', 2, 'Delete User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('user-report', 2, 'Report User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

       ('profile-index', 2, 'Index Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-create', 2, 'Create Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-update', 2, 'Update Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-view', 2, 'View Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-delete', 2, 'Delete Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
       ('profile-report', 2, 'Report Profile', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- =========================================================================
-- F. RELASI GROUP → DETAIL (INTI RBAC)
-- =========================================================================

-- -------------------------------------------------------------------------
-- 1. MASTER GROUP → DETAIL
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item_child (parent, child)
VALUES
-- Disaster Master
('master-index', 'disasterType-index'),
('master-create', 'disasterType-create'),
('master-update', 'disasterType-update'),
('master-view', 'disasterType-view'),
('master-delete', 'disasterType-delete'),
('master-report', 'disasterType-report'),

('master-index', 'disasterStatus-index'),
('master-create', 'disasterStatus-create'),
('master-update', 'disasterStatus-update'),
('master-view', 'disasterStatus-view'),
('master-delete', 'disasterStatus-delete'),
('master-report', 'disasterStatus-report'),

-- Verification Master
('master-index', 'verificationAction-index'),
('master-create', 'verificationAction-create'),
('master-update', 'verificationAction-update'),
('master-view', 'verificationAction-view'),
('master-delete', 'verificationAction-delete'),
('master-report', 'verificationAction-report'),

-- Item Master
('master-index', 'itemCategory-index'),
('master-create', 'itemCategory-create'),
('master-update', 'itemCategory-update'),
('master-view', 'itemCategory-view'),
('master-delete', 'itemCategory-delete'),
('master-report', 'itemCategory-report'),

('master-index', 'unit-index'),
('master-create', 'unit-create'),
('master-update', 'unit-update'),
('master-view', 'unit-view'),
('master-delete', 'unit-delete'),
('master-report', 'unit-report'),

-- Misc Master
('master-index', 'entityType-index'),
('master-create', 'entityType-create'),
('master-update', 'entityType-update'),
('master-view', 'entityType-view'),
('master-delete', 'entityType-delete'),
('master-report', 'entityType-report'),

('master-index', 'vehicleType-index'),
('master-create', 'vehicleType-create'),
('master-update', 'vehicleType-update'),
('master-view', 'vehicleType-view'),
('master-delete', 'vehicleType-delete'),
('master-report', 'vehicleType-report'),

('master-index', 'accessRoute-index'),
('master-create', 'accessRoute-create'),
('master-update', 'accessRoute-update'),
('master-view', 'accessRoute-view'),
('master-delete', 'accessRoute-delete'),
('master-report', 'accessRoute-report');


-- -------------------------------------------------------------------------
-- 2. TRANSACTION GROUP → DETAIL
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item_child (parent, child)
VALUES
-- Disaster
('transaction-index', 'disaster-index'),
('transaction-create', 'disaster-create'),
('transaction-update', 'disaster-update'),
('transaction-view', 'disaster-view'),
('transaction-delete', 'disaster-delete'),
('transaction-report', 'disaster-report'),

-- Aid Plan
('transaction-index', 'aidPlan-index'),
('transaction-create', 'aidPlan-create'),
('transaction-update', 'aidPlan-update'),
('transaction-view', 'aidPlan-view'),
('transaction-delete', 'aidPlan-delete'),
('transaction-report', 'aidPlan-report'),

-- Aid Distribution
('transaction-index', 'aidDistribution-index'),
('transaction-create', 'aidDistribution-create'),
('transaction-update', 'aidDistribution-update'),
('transaction-view', 'aidDistribution-view'),
('transaction-delete', 'aidDistribution-delete'),
('transaction-report', 'aidDistribution-report'),

-- Verification
('transaction-index', 'verification-index'),
('transaction-create', 'verification-create'),
('transaction-update', 'verification-update'),
('transaction-view', 'verification-view'),
('transaction-delete', 'verification-delete'),
('transaction-report', 'verification-report'),

-- Verification Vote
('transaction-index', 'verificationVote-index'),
('transaction-create', 'verificationVote-create'),
('transaction-update', 'verificationVote-update'),
('transaction-view', 'verificationVote-view'),
('transaction-delete', 'verificationVote-delete'),
('transaction-report', 'verificationVote-report'),

-- Media
('transaction-index', 'mediaFile-index'),
('transaction-create', 'mediaFile-create'),
('transaction-update', 'mediaFile-update'),
('transaction-view', 'mediaFile-view'),
('transaction-delete', 'mediaFile-delete'),
('transaction-report', 'mediaFile-report'),

-- Access Route (Detail)
('transaction-index', 'accessRouteShelter-index'),
('transaction-create', 'accessRouteShelter-create'),
('transaction-update', 'accessRouteShelter-update'),
('transaction-view', 'accessRouteShelter-view'),
('transaction-delete', 'accessRouteShelter-delete'),
('transaction-report', 'accessRouteShelter-report'),

('transaction-index', 'accessRouteStatus-index'),
('transaction-create', 'accessRouteStatus-create'),
('transaction-update', 'accessRouteStatus-update'),
('transaction-view', 'accessRouteStatus-view'),
('transaction-delete', 'accessRouteStatus-delete'),
('transaction-report', 'accessRouteStatus-report'),

('transaction-index', 'accessRouteVehicle-index'),
('transaction-create', 'accessRouteVehicle-create'),
('transaction-update', 'accessRouteVehicle-update'),
('transaction-view', 'accessRouteVehicle-view'),
('transaction-delete', 'accessRouteVehicle-delete'),
('transaction-report', 'accessRouteVehicle-report'),

-- Shelter
('transaction-index', 'shelter-index'),
('transaction-create', 'shelter-create'),
('transaction-update', 'shelter-update'),
('transaction-view', 'shelter-view'),
('transaction-delete', 'shelter-delete'),
('transaction-report', 'shelter-report'),

-- Item
('transaction-index', 'item-index'),
('transaction-create', 'item-create'),
('transaction-update', 'item-update'),
('transaction-view', 'item-view'),
('transaction-delete', 'item-delete'),
('transaction-report', 'item-report'),

-- Profile
('transaction-index', 'profile-index'),
('transaction-create', 'profile-create'),
('transaction-update', 'profile-update'),
('transaction-view', 'profile-view'),
('transaction-delete', 'profile-delete'),
('transaction-report', 'profile-report');


-- =========================================================================
-- G. ASSIGN PERMISSION KE ROLE
-- =========================================================================

-- -------------------------------------------------------------------------
-- 1. GUEST (TRANSPARANSI PUBLIK)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item_child (parent, child)
VALUES ('guest', 'disaster-index'),
       ('guest', 'disaster-view'),
       ('guest', 'disaster-report'),
       ('guest', 'aidDistribution-index'),
       ('guest', 'aidDistribution-view'),
       ('guest', 'aidDistribution-report'),
       ('guest', 'shelter-index'),
       ('guest', 'shelter-view'),
       ('guest', 'accessRoute-index'),
       ('guest', 'accessRoute-view');


-- -------------------------------------------------------------------------
-- 2. REGULAR (RELAWAN / CROWD)
-- -------------------------------------------------------------------------
-- Mendapat hak input & update via transaction group
-- TANPA delete & report

INSERT INTO t_auth_item_child (parent, child)
VALUES ('regular', 'transaction-index'),
       ('regular', 'transaction-create'),
       ('regular', 'transaction-update'),
       ('regular', 'transaction-view');


-- -------------------------------------------------------------------------
-- 3. COORDINATOR (MODERATOR)
-- -------------------------------------------------------------------------
-- Mewarisi Regular
-- Tambahan: report & moderasi

INSERT INTO t_auth_item_child (parent, child)
VALUES ('coordinator', 'transaction-report');


-- -------------------------------------------------------------------------
-- 4. ADMIN (GOVERNANCE)
-- -------------------------------------------------------------------------
-- Mewarisi Coordinator
-- Tambahan: master, delete, dan user management

INSERT INTO t_auth_item_child (parent, child)
VALUES
-- Master Data
('admin', 'master-index'),
('admin', 'master-create'),
('admin', 'master-update'),
('admin', 'master-view'),
('admin', 'master-delete'),
('admin', 'master-report'),

-- Critical Action
('admin', 'transaction-delete'),

-- User Management
('admin', 'user-index'),
('admin', 'user-create'),
('admin', 'user-update'),
('admin', 'user-view'),
('admin', 'user-delete'),
('admin', 'user-report');


-- =========================================================================
-- H. BOOTSTRAP ADMIN USER
-- =========================================================================
INSERT INTO t_auth_assignment (item_name, user_id, created_at)
VALUES ('admin', 1, UNIX_TIMESTAMP());

COMMIT;
