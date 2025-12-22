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

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE t_auth_assignment;
TRUNCATE TABLE t_auth_item_child;
TRUNCATE TABLE t_auth_item;

SET FOREIGN_KEY_CHECKS = 1;


-- =========================================================================
-- B. DEFINISI ROLE (type = 1)
-- =========================================================================
-- Role merepresentasikan aktor dalam sistem

INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES
    ('guest',       1, 'Public / Observer (Read-only)', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('regular',     1, 'Crowd / Relawan Lapangan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('coordinator', 1, 'Koordinator / Moderator Wilayah', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('admin',       1, 'Administrator Sistem / Governance', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- =========================================================================
-- C. INHERITANCE ROLE (HIRARKI)
-- =========================================================================
-- Admin memiliki seluruh hak Coordinator
-- Coordinator memiliki seluruh hak Regular

INSERT INTO t_auth_item_child (parent, child)
VALUES
    ('admin', 'coordinator'),
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
VALUES
    ('master-index',  2, 'Index Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('master-create', 2, 'Create Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('master-update', 2, 'Update Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('master-view',   2, 'View Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('master-delete', 2, 'Delete Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('master-report', 2, 'Report Master Data', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- -------------------------------------------------------------------------
-- 2. TRANSACTION GROUP
-- -------------------------------------------------------------------------
-- Transaction = data operasional lapangan
-- Ini adalah JANTUNG sistem crowdsource

INSERT INTO t_auth_item (name, type, description, created_at, updated_at)
VALUES
    ('transaction-index',  2, 'Index Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('transaction-create', 2, 'Create Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('transaction-update', 2, 'Update Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('transaction-view',   2, 'View Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('transaction-delete', 2, 'Delete Transaction (Critical)', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
    ('transaction-report', 2, 'Report Transaction', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- =========================================================================
-- E. DETAIL PER MODULE (SEMUA KONSISTEN controller-action)
-- =========================================================================

-- -------------------------------------------------------------------------
-- 1. DISASTER (Kejadian Bencana – Data Lapangan)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item VALUES
                            ('disaster-index',  2, 'Index Disaster',  UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('disaster-create', 2, 'Create Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('disaster-update', 2, 'Update Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('disaster-view',   2, 'View Disaster',   UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('disaster-delete', 2, 'Delete Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('disaster-report', 2, 'Report Disaster', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- -------------------------------------------------------------------------
-- 2. AID PLAN & DISTRIBUTION (Bantuan)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item VALUES
                            ('aidPlan-index',  2, 'Index Aid Plan',  UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidPlan-create', 2, 'Create Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidPlan-update', 2, 'Update Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidPlan-view',   2, 'View Aid Plan',   UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidPlan-delete', 2, 'Delete Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidPlan-report', 2, 'Report Aid Plan', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

                            ('aidDistribution-index',  2, 'Index Aid Distribution',  UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidDistribution-create', 2, 'Create Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidDistribution-update', 2, 'Update Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidDistribution-view',   2, 'View Aid Distribution',   UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidDistribution-delete', 2, 'Delete Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('aidDistribution-report', 2, 'Report Aid Distribution', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- -------------------------------------------------------------------------
-- 3. VERIFICATION (Validasi Crowd / Anti Hoax)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item VALUES
                            ('verification-index',  2, 'Index Verification',  UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verification-create', 2, 'Create Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verification-update', 2, 'Update Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verification-view',   2, 'View Verification',   UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verification-delete', 2, 'Delete Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verification-report', 2, 'Report Verification', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),

                            ('verificationVote-index',  2, 'Index Verification Vote',  UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verificationVote-create', 2, 'Create Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verificationVote-update', 2, 'Update Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verificationVote-view',   2, 'View Verification Vote',   UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verificationVote-delete', 2, 'Delete Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('verificationVote-report', 2, 'Report Verification Vote', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- -------------------------------------------------------------------------
-- 4. MEDIA FILE (Bukti Lapangan)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item VALUES
                            ('mediaFile-index',  2, 'Index Media File',  UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('mediaFile-create', 2, 'Create Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('mediaFile-update', 2, 'Update Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('mediaFile-view',   2, 'View Media File',   UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('mediaFile-delete', 2, 'Delete Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('mediaFile-report', 2, 'Report Media File', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- -------------------------------------------------------------------------
-- 5. USER MANAGEMENT (KHUSUS ADMIN)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item VALUES
                            ('user-index',  2, 'Index User',  UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('user-create', 2, 'Create User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('user-update', 2, 'Update User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
                            ('user-delete', 2, 'Delete User', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());


-- =========================================================================
-- F. RELASI GROUP → DETAIL (INTI RBAC)
-- =========================================================================
-- Transaction group mengendalikan seluruh modul operasional

INSERT INTO t_auth_item_child (parent, child)
VALUES
-- Disaster
('transaction-index','disaster-index'),
('transaction-create','disaster-create'),
('transaction-update','disaster-update'),
('transaction-view','disaster-view'),
('transaction-delete','disaster-delete'),
('transaction-report','disaster-report'),

-- Aid Plan
('transaction-index','aidPlan-index'),
('transaction-create','aidPlan-create'),
('transaction-update','aidPlan-update'),
('transaction-view','aidPlan-view'),
('transaction-report','aidPlan-report'),

-- Aid Distribution
('transaction-index','aidDistribution-index'),
('transaction-create','aidDistribution-create'),
('transaction-update','aidDistribution-update'),
('transaction-view','aidDistribution-view'),
('transaction-report','aidDistribution-report'),

-- Verification
('transaction-index','verification-index'),
('transaction-create','verification-create'),
('transaction-update','verification-update'),
('transaction-view','verification-view'),
('transaction-report','verification-report'),

-- Verification Vote
('transaction-index','verificationVote-index'),
('transaction-create','verificationVote-create'),
('transaction-update','verificationVote-update'),
('transaction-view','verificationVote-view'),
('transaction-report','verificationVote-report'),

-- Media
('transaction-index','mediaFile-index'),
('transaction-create','mediaFile-create'),
('transaction-update','mediaFile-update'),
('transaction-view','mediaFile-view'),
('transaction-report','mediaFile-report');


-- =========================================================================
-- G. ASSIGN PERMISSION KE ROLE
-- =========================================================================

-- -------------------------------------------------------------------------
-- 1. GUEST (TRANSPARANSI PUBLIK)
-- -------------------------------------------------------------------------
INSERT INTO t_auth_item_child (parent, child)
VALUES
    ('guest','disaster-index'),
    ('guest','disaster-view'),
    ('guest','disaster-report'),
    ('guest','aidDistribution-index'),
    ('guest','aidDistribution-view'),
    ('guest','aidDistribution-report');


-- -------------------------------------------------------------------------
-- 2. REGULAR (RELAWAN / CROWD)
-- -------------------------------------------------------------------------
-- Mendapat hak input & update via transaction group
-- TANPA delete & report

INSERT INTO t_auth_item_child (parent, child)
VALUES
    ('regular','transaction-index'),
    ('regular','transaction-create'),
    ('regular','transaction-update'),
    ('regular','transaction-view');


-- -------------------------------------------------------------------------
-- 3. COORDINATOR (MODERATOR)
-- -------------------------------------------------------------------------
-- Mewarisi Regular
-- Tambahan: report & moderasi

INSERT INTO t_auth_item_child (parent, child)
VALUES
    ('coordinator','transaction-report');


-- -------------------------------------------------------------------------
-- 4. ADMIN (GOVERNANCE)
-- -------------------------------------------------------------------------
-- Mewarisi Coordinator
-- Tambahan: master, delete, dan user management

INSERT INTO t_auth_item_child (parent, child)
VALUES
-- Master Data
('admin','master-index'),
('admin','master-create'),
('admin','master-update'),
('admin','master-view'),
('admin','master-delete'),
('admin','master-report'),

-- Critical Action
('admin','transaction-delete'),

-- User Management
('admin','user-index'),
('admin','user-create'),
('admin','user-update'),
('admin','user-delete');


-- =========================================================================
-- H. BOOTSTRAP ADMIN USER
-- =========================================================================
INSERT INTO t_auth_assignment (item_name, user_id, created_at)
VALUES ('admin', 1, UNIX_TIMESTAMP());

COMMIT;
