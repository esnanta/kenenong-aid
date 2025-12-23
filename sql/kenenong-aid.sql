-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: kenenong-aid
-- ------------------------------------------------------
-- Server version	8.0.44-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `t_access_route`
--

DROP TABLE IF EXISTS `t_access_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route` (
  `id` int NOT NULL AUTO_INCREMENT,
  `disaster_id` int DEFAULT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `route_geometry` linestring DEFAULT NULL,
  `route_length_km` decimal(6,2) DEFAULT NULL,
  `access_route_status_id` int DEFAULT NULL COMMENT 'safe | damaged | blocked',
  `geometry_updated_at` datetime DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_access_route_relation_status` (`access_route_status_id`),
  KEY `idx_route_disaster` (`disaster_id`),
  CONSTRAINT `t_access_route_relation_disaster` FOREIGN KEY (`disaster_id`) REFERENCES `t_disaster` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_access_route_relation_status` FOREIGN KEY (`access_route_status_id`) REFERENCES `t_access_route_status` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_access_route`
--

LOCK TABLES `t_access_route` WRITE;
/*!40000 ALTER TABLE `t_access_route` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_access_route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_access_route_shelter`
--

DROP TABLE IF EXISTS `t_access_route_shelter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route_shelter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_route_id` int DEFAULT NULL,
  `shelter_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_access_route_shelters_relation_route` (`access_route_id`),
  KEY `t_access_route_shelters_relation_shelter` (`shelter_id`),
  CONSTRAINT `t_access_route_shelter_relation_route` FOREIGN KEY (`access_route_id`) REFERENCES `t_access_route` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_access_route_shelter_relation_shelter` FOREIGN KEY (`shelter_id`) REFERENCES `t_shelter` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_access_route_shelter`
--

LOCK TABLES `t_access_route_shelter` WRITE;
/*!40000 ALTER TABLE `t_access_route_shelter` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_access_route_shelter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_access_route_status`
--

DROP TABLE IF EXISTS `t_access_route_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_access_route_status_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_access_route_status`
--

LOCK TABLES `t_access_route_status` WRITE;
/*!40000 ALTER TABLE `t_access_route_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_access_route_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_access_route_vehicle`
--

DROP TABLE IF EXISTS `t_access_route_vehicle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route_vehicle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_route_id` int DEFAULT NULL,
  `vehicle_type_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_access_route_vehicles_relation_route` (`access_route_id`),
  KEY `t_access_route_vehicles_relation_type` (`vehicle_type_id`),
  CONSTRAINT `t_access_route_vehicle_relation_route` FOREIGN KEY (`access_route_id`) REFERENCES `t_access_route` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_access_route_vehicle_relation_vehicle` FOREIGN KEY (`vehicle_type_id`) REFERENCES `t_vehicle_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_access_route_vehicle`
--

LOCK TABLES `t_access_route_vehicle` WRITE;
/*!40000 ALTER TABLE `t_access_route_vehicle` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_access_route_vehicle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_aid_distribution`
--

DROP TABLE IF EXISTS `t_aid_distribution`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_aid_distribution` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aid_plan_id` int DEFAULT NULL,
  `shelter_id` int DEFAULT NULL,
  `distribution_date` datetime DEFAULT NULL,
  `distributed_by` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_distribution_relation_plan` (`aid_plan_id`),
  KEY `t_aid_distribution_relation_shelter` (`shelter_id`),
  KEY `t_aid_distribution_relation_distribution` (`distributed_by`),
  CONSTRAINT `t_aid_distribution_relation_distribution` FOREIGN KEY (`distributed_by`) REFERENCES `t_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_aid_distribution_relation_plan` FOREIGN KEY (`aid_plan_id`) REFERENCES `t_aid_plan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_aid_distribution_relation_shelter` FOREIGN KEY (`shelter_id`) REFERENCES `t_shelter` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_aid_distribution`
--

LOCK TABLES `t_aid_distribution` WRITE;
/*!40000 ALTER TABLE `t_aid_distribution` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_aid_distribution` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_aid_distribution_details`
--

DROP TABLE IF EXISTS `t_aid_distribution_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_aid_distribution_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aid_distribution_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_distribution_details_relation_master` (`aid_distribution_id`),
  KEY `t_aid_distribution_details_relation_item` (`item_id`),
  KEY `t_aid_distribution_details_relation_unit` (`unit_id`),
  CONSTRAINT `t_aid_distribution_details_relation_item` FOREIGN KEY (`item_id`) REFERENCES `t_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_distribution_details_relation_master` FOREIGN KEY (`aid_distribution_id`) REFERENCES `t_aid_distribution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_distribution_details_relation_unit` FOREIGN KEY (`unit_id`) REFERENCES `t_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_aid_distribution_details`
--

LOCK TABLES `t_aid_distribution_details` WRITE;
/*!40000 ALTER TABLE `t_aid_distribution_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_aid_distribution_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_aid_plan`
--

DROP TABLE IF EXISTS `t_aid_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_aid_plan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `shelter_id` int DEFAULT NULL,
  `distribution_plan_date` datetime DEFAULT NULL,
  `plan_status` int DEFAULT NULL COMMENT 'draft | approved | executed',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` int DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_plan_relation_shelter` (`shelter_id`),
  CONSTRAINT `t_aid_plan_relation_shelter` FOREIGN KEY (`shelter_id`) REFERENCES `t_shelter` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_aid_plan`
--

LOCK TABLES `t_aid_plan` WRITE;
/*!40000 ALTER TABLE `t_aid_plan` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_aid_plan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_aid_plan_details`
--

DROP TABLE IF EXISTS `t_aid_plan_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_aid_plan_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aid_plan_id` int DEFAULT NULL,
  `item_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_plan_details_relation_plan` (`aid_plan_id`),
  KEY `t_aid_plan_details_relation_item` (`item_id`),
  KEY `t_aid_plan_details_relation_unit` (`unit_id`),
  CONSTRAINT `t_aid_plan_details_relation_item` FOREIGN KEY (`item_id`) REFERENCES `t_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_plan_details_relation_plan` FOREIGN KEY (`aid_plan_id`) REFERENCES `t_aid_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_plan_details_relation_unit` FOREIGN KEY (`unit_id`) REFERENCES `t_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_aid_plan_details`
--

LOCK TABLES `t_aid_plan_details` WRITE;
/*!40000 ALTER TABLE `t_aid_plan_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_aid_plan_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_auth_assignment`
--

DROP TABLE IF EXISTS `t_auth_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `t_idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `t_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_auth_assignment`
--

LOCK TABLES `t_auth_assignment` WRITE;
/*!40000 ALTER TABLE `t_auth_assignment` DISABLE KEYS */;
INSERT INTO `t_auth_assignment` VALUES ('admin','1',1766468700);
/*!40000 ALTER TABLE `t_auth_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_auth_item`
--

DROP TABLE IF EXISTS `t_auth_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_auth_item` (
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` smallint NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `rule_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `t_idx-auth_item-type` (`type`),
  CONSTRAINT `t_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `t_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_auth_item`
--

LOCK TABLES `t_auth_item` WRITE;
/*!40000 ALTER TABLE `t_auth_item` DISABLE KEYS */;
INSERT INTO `t_auth_item` VALUES ('accessRoute-create',2,'Create Access Route',NULL,NULL,1766468700,1766468700),('accessRoute-delete',2,'Delete Access Route',NULL,NULL,1766468700,1766468700),('accessRoute-index',2,'Index Access Route',NULL,NULL,1766468700,1766468700),('accessRoute-report',2,'Report Access Route',NULL,NULL,1766468700,1766468700),('accessRoute-update',2,'Update Access Route',NULL,NULL,1766468700,1766468700),('accessRoute-view',2,'View Access Route',NULL,NULL,1766468700,1766468700),('accessRouteShelter-create',2,'Create Access Route Shelter',NULL,NULL,1766468700,1766468700),('accessRouteShelter-delete',2,'Delete Access Route Shelter',NULL,NULL,1766468700,1766468700),('accessRouteShelter-index',2,'Index Access Route Shelter',NULL,NULL,1766468700,1766468700),('accessRouteShelter-report',2,'Report Access Route Shelter',NULL,NULL,1766468700,1766468700),('accessRouteShelter-update',2,'Update Access Route Shelter',NULL,NULL,1766468700,1766468700),('accessRouteShelter-view',2,'View Access Route Shelter',NULL,NULL,1766468700,1766468700),('accessRouteStatus-create',2,'Create Access Route Status',NULL,NULL,1766468700,1766468700),('accessRouteStatus-delete',2,'Delete Access Route Status',NULL,NULL,1766468700,1766468700),('accessRouteStatus-index',2,'Index Access Route Status',NULL,NULL,1766468700,1766468700),('accessRouteStatus-report',2,'Report Access Route Status',NULL,NULL,1766468700,1766468700),('accessRouteStatus-update',2,'Update Access Route Status',NULL,NULL,1766468700,1766468700),('accessRouteStatus-view',2,'View Access Route Status',NULL,NULL,1766468700,1766468700),('accessRouteVehicle-create',2,'Create Access Route Vehicle',NULL,NULL,1766468700,1766468700),('accessRouteVehicle-delete',2,'Delete Access Route Vehicle',NULL,NULL,1766468700,1766468700),('accessRouteVehicle-index',2,'Index Access Route Vehicle',NULL,NULL,1766468700,1766468700),('accessRouteVehicle-report',2,'Report Access Route Vehicle',NULL,NULL,1766468700,1766468700),('accessRouteVehicle-update',2,'Update Access Route Vehicle',NULL,NULL,1766468700,1766468700),('accessRouteVehicle-view',2,'View Access Route Vehicle',NULL,NULL,1766468700,1766468700),('admin',1,'Administrator Sistem / Governance',NULL,NULL,1766468700,1766468700),('aidDistribution-create',2,'Create Aid Distribution',NULL,NULL,1766468700,1766468700),('aidDistribution-delete',2,'Delete Aid Distribution',NULL,NULL,1766468700,1766468700),('aidDistribution-index',2,'Index Aid Distribution',NULL,NULL,1766468700,1766468700),('aidDistribution-report',2,'Report Aid Distribution',NULL,NULL,1766468700,1766468700),('aidDistribution-update',2,'Update Aid Distribution',NULL,NULL,1766468700,1766468700),('aidDistribution-view',2,'View Aid Distribution',NULL,NULL,1766468700,1766468700),('aidPlan-create',2,'Create Aid Plan',NULL,NULL,1766468700,1766468700),('aidPlan-delete',2,'Delete Aid Plan',NULL,NULL,1766468700,1766468700),('aidPlan-index',2,'Index Aid Plan',NULL,NULL,1766468700,1766468700),('aidPlan-report',2,'Report Aid Plan',NULL,NULL,1766468700,1766468700),('aidPlan-update',2,'Update Aid Plan',NULL,NULL,1766468700,1766468700),('aidPlan-view',2,'View Aid Plan',NULL,NULL,1766468700,1766468700),('coordinator',1,'Koordinator / Moderator Wilayah',NULL,NULL,1766468700,1766468700),('disaster-create',2,'Create Disaster',NULL,NULL,1766468700,1766468700),('disaster-delete',2,'Delete Disaster',NULL,NULL,1766468700,1766468700),('disaster-index',2,'Index Disaster',NULL,NULL,1766468700,1766468700),('disaster-report',2,'Report Disaster',NULL,NULL,1766468700,1766468700),('disaster-update',2,'Update Disaster',NULL,NULL,1766468700,1766468700),('disaster-view',2,'View Disaster',NULL,NULL,1766468700,1766468700),('disasterStatus-create',2,'Create Status Disaster',NULL,NULL,1766468700,1766468700),('disasterStatus-delete',2,'Delete Status Disaster',NULL,NULL,1766468700,1766468700),('disasterStatus-index',2,'Index Status Disaster',NULL,NULL,1766468700,1766468700),('disasterStatus-report',2,'Report Status Disaster',NULL,NULL,1766468700,1766468700),('disasterStatus-update',2,'Update Status Disaster',NULL,NULL,1766468700,1766468700),('disasterStatus-view',2,'View Status Disaster',NULL,NULL,1766468700,1766468700),('disasterType-create',2,'Create Type Disaster',NULL,NULL,1766468700,1766468700),('disasterType-delete',2,'Delete Type Disaster',NULL,NULL,1766468700,1766468700),('disasterType-index',2,'Index Type Disaster',NULL,NULL,1766468700,1766468700),('disasterType-report',2,'Report Type Disaster',NULL,NULL,1766468700,1766468700),('disasterType-update',2,'Update Type Disaster',NULL,NULL,1766468700,1766468700),('disasterType-view',2,'View Type Disaster',NULL,NULL,1766468700,1766468700),('entityType-create',2,'Create Entity Type',NULL,NULL,1766468700,1766468700),('entityType-delete',2,'Delete Entity Type',NULL,NULL,1766468700,1766468700),('entityType-index',2,'Index Entity Type',NULL,NULL,1766468700,1766468700),('entityType-report',2,'Report Entity Type',NULL,NULL,1766468700,1766468700),('entityType-update',2,'Update Entity Type',NULL,NULL,1766468700,1766468700),('entityType-view',2,'View Entity Type',NULL,NULL,1766468700,1766468700),('guest',1,'Public / Observer (Read-only)',NULL,NULL,1766468700,1766468700),('item-create',2,'Create Item',NULL,NULL,1766468700,1766468700),('item-delete',2,'Delete Item',NULL,NULL,1766468700,1766468700),('item-index',2,'Index Item',NULL,NULL,1766468700,1766468700),('item-report',2,'Report Item',NULL,NULL,1766468700,1766468700),('item-update',2,'Update Item',NULL,NULL,1766468700,1766468700),('item-view',2,'View Item',NULL,NULL,1766468700,1766468700),('itemCategory-create',2,'Create Item Category',NULL,NULL,1766468700,1766468700),('itemCategory-delete',2,'Delete Item Category',NULL,NULL,1766468700,1766468700),('itemCategory-index',2,'Index Item Category',NULL,NULL,1766468700,1766468700),('itemCategory-report',2,'Report Item Category',NULL,NULL,1766468700,1766468700),('itemCategory-update',2,'Update Item Category',NULL,NULL,1766468700,1766468700),('itemCategory-view',2,'View Item Category',NULL,NULL,1766468700,1766468700),('master-create',2,'Create Master Data',NULL,NULL,1766468700,1766468700),('master-delete',2,'Delete Master Data',NULL,NULL,1766468700,1766468700),('master-index',2,'Index Master Data',NULL,NULL,1766468700,1766468700),('master-report',2,'Report Master Data',NULL,NULL,1766468700,1766468700),('master-update',2,'Update Master Data',NULL,NULL,1766468700,1766468700),('master-view',2,'View Master Data',NULL,NULL,1766468700,1766468700),('mediaFile-create',2,'Create Media File',NULL,NULL,1766468700,1766468700),('mediaFile-delete',2,'Delete Media File',NULL,NULL,1766468700,1766468700),('mediaFile-index',2,'Index Media File',NULL,NULL,1766468700,1766468700),('mediaFile-report',2,'Report Media File',NULL,NULL,1766468700,1766468700),('mediaFile-update',2,'Update Media File',NULL,NULL,1766468700,1766468700),('mediaFile-view',2,'View Media File',NULL,NULL,1766468700,1766468700),('profile-create',2,'Create Profile',NULL,NULL,1766468700,1766468700),('profile-delete',2,'Delete Profile',NULL,NULL,1766468700,1766468700),('profile-index',2,'Index Profile',NULL,NULL,1766468700,1766468700),('profile-report',2,'Report Profile',NULL,NULL,1766468700,1766468700),('profile-update',2,'Update Profile',NULL,NULL,1766468700,1766468700),('profile-view',2,'View Profile',NULL,NULL,1766468700,1766468700),('regular',1,'Crowd / Relawan Lapangan',NULL,NULL,1766468700,1766468700),('shelter-create',2,'Create Shelter',NULL,NULL,1766468700,1766468700),('shelter-delete',2,'Delete Shelter',NULL,NULL,1766468700,1766468700),('shelter-index',2,'Index Shelter',NULL,NULL,1766468700,1766468700),('shelter-report',2,'Report Shelter',NULL,NULL,1766468700,1766468700),('shelter-update',2,'Update Shelter',NULL,NULL,1766468700,1766468700),('shelter-view',2,'View Shelter',NULL,NULL,1766468700,1766468700),('transaction-create',2,'Create Transaction',NULL,NULL,1766468700,1766468700),('transaction-delete',2,'Delete Transaction (Critical)',NULL,NULL,1766468700,1766468700),('transaction-index',2,'Index Transaction',NULL,NULL,1766468700,1766468700),('transaction-report',2,'Report Transaction',NULL,NULL,1766468700,1766468700),('transaction-update',2,'Update Transaction',NULL,NULL,1766468700,1766468700),('transaction-view',2,'View Transaction',NULL,NULL,1766468700,1766468700),('unit-create',2,'Create Unit',NULL,NULL,1766468700,1766468700),('unit-delete',2,'Delete Unit',NULL,NULL,1766468700,1766468700),('unit-index',2,'Index Unit',NULL,NULL,1766468700,1766468700),('unit-report',2,'Report Unit',NULL,NULL,1766468700,1766468700),('unit-update',2,'Update Unit',NULL,NULL,1766468700,1766468700),('unit-view',2,'View Unit',NULL,NULL,1766468700,1766468700),('user-create',2,'Create User',NULL,NULL,1766468700,1766468700),('user-delete',2,'Delete User',NULL,NULL,1766468700,1766468700),('user-index',2,'Index User',NULL,NULL,1766468700,1766468700),('user-report',2,'Report User',NULL,NULL,1766468700,1766468700),('user-update',2,'Update User',NULL,NULL,1766468700,1766468700),('user-view',2,'View User',NULL,NULL,1766468700,1766468700),('vehicleType-create',2,'Create Vehicle Type',NULL,NULL,1766468700,1766468700),('vehicleType-delete',2,'Delete Vehicle Type',NULL,NULL,1766468700,1766468700),('vehicleType-index',2,'Index Vehicle Type',NULL,NULL,1766468700,1766468700),('vehicleType-report',2,'Report Vehicle Type',NULL,NULL,1766468700,1766468700),('vehicleType-update',2,'Update Vehicle Type',NULL,NULL,1766468700,1766468700),('vehicleType-view',2,'View Vehicle Type',NULL,NULL,1766468700,1766468700),('verification-create',2,'Create Verification',NULL,NULL,1766468700,1766468700),('verification-delete',2,'Delete Verification',NULL,NULL,1766468700,1766468700),('verification-index',2,'Index Verification',NULL,NULL,1766468700,1766468700),('verification-report',2,'Report Verification',NULL,NULL,1766468700,1766468700),('verification-update',2,'Update Verification',NULL,NULL,1766468700,1766468700),('verification-view',2,'View Verification',NULL,NULL,1766468700,1766468700),('verificationAction-create',2,'Create Verification Action',NULL,NULL,1766468700,1766468700),('verificationAction-delete',2,'Delete Verification Action',NULL,NULL,1766468700,1766468700),('verificationAction-index',2,'Index Verification Action',NULL,NULL,1766468700,1766468700),('verificationAction-report',2,'Report Verification Action',NULL,NULL,1766468700,1766468700),('verificationAction-update',2,'Update Verification Action',NULL,NULL,1766468700,1766468700),('verificationAction-view',2,'View Verification Action',NULL,NULL,1766468700,1766468700),('verificationVote-create',2,'Create Verification Vote',NULL,NULL,1766468700,1766468700),('verificationVote-delete',2,'Delete Verification Vote',NULL,NULL,1766468700,1766468700),('verificationVote-index',2,'Index Verification Vote',NULL,NULL,1766468700,1766468700),('verificationVote-report',2,'Report Verification Vote',NULL,NULL,1766468700,1766468700),('verificationVote-update',2,'Update Verification Vote',NULL,NULL,1766468700,1766468700),('verificationVote-view',2,'View Verification Vote',NULL,NULL,1766468700,1766468700);
/*!40000 ALTER TABLE `t_auth_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_auth_item_child`
--

DROP TABLE IF EXISTS `t_auth_item_child`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `t_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_auth_item_child`
--

LOCK TABLES `t_auth_item_child` WRITE;
/*!40000 ALTER TABLE `t_auth_item_child` DISABLE KEYS */;
INSERT INTO `t_auth_item_child` VALUES ('master-create','accessRoute-create'),('master-delete','accessRoute-delete'),('guest','accessRoute-index'),('master-index','accessRoute-index'),('master-report','accessRoute-report'),('master-update','accessRoute-update'),('guest','accessRoute-view'),('master-view','accessRoute-view'),('transaction-create','accessRouteShelter-create'),('transaction-delete','accessRouteShelter-delete'),('transaction-index','accessRouteShelter-index'),('transaction-report','accessRouteShelter-report'),('transaction-update','accessRouteShelter-update'),('transaction-view','accessRouteShelter-view'),('transaction-create','accessRouteStatus-create'),('transaction-delete','accessRouteStatus-delete'),('transaction-index','accessRouteStatus-index'),('transaction-report','accessRouteStatus-report'),('transaction-update','accessRouteStatus-update'),('transaction-view','accessRouteStatus-view'),('transaction-create','accessRouteVehicle-create'),('transaction-delete','accessRouteVehicle-delete'),('transaction-index','accessRouteVehicle-index'),('transaction-report','accessRouteVehicle-report'),('transaction-update','accessRouteVehicle-update'),('transaction-view','accessRouteVehicle-view'),('transaction-create','aidDistribution-create'),('transaction-delete','aidDistribution-delete'),('guest','aidDistribution-index'),('transaction-index','aidDistribution-index'),('guest','aidDistribution-report'),('transaction-report','aidDistribution-report'),('transaction-update','aidDistribution-update'),('guest','aidDistribution-view'),('transaction-view','aidDistribution-view'),('transaction-create','aidPlan-create'),('transaction-delete','aidPlan-delete'),('transaction-index','aidPlan-index'),('transaction-report','aidPlan-report'),('transaction-update','aidPlan-update'),('transaction-view','aidPlan-view'),('admin','coordinator'),('transaction-create','disaster-create'),('transaction-delete','disaster-delete'),('guest','disaster-index'),('transaction-index','disaster-index'),('guest','disaster-report'),('transaction-report','disaster-report'),('transaction-update','disaster-update'),('guest','disaster-view'),('transaction-view','disaster-view'),('master-create','disasterStatus-create'),('master-delete','disasterStatus-delete'),('master-index','disasterStatus-index'),('master-report','disasterStatus-report'),('master-update','disasterStatus-update'),('master-view','disasterStatus-view'),('master-create','disasterType-create'),('master-delete','disasterType-delete'),('master-index','disasterType-index'),('master-report','disasterType-report'),('master-update','disasterType-update'),('master-view','disasterType-view'),('master-create','entityType-create'),('master-delete','entityType-delete'),('master-index','entityType-index'),('master-report','entityType-report'),('master-update','entityType-update'),('master-view','entityType-view'),('transaction-create','item-create'),('transaction-delete','item-delete'),('transaction-index','item-index'),('transaction-report','item-report'),('transaction-update','item-update'),('transaction-view','item-view'),('master-create','itemCategory-create'),('master-delete','itemCategory-delete'),('master-index','itemCategory-index'),('master-report','itemCategory-report'),('master-update','itemCategory-update'),('master-view','itemCategory-view'),('admin','master-create'),('admin','master-delete'),('admin','master-index'),('admin','master-report'),('admin','master-update'),('admin','master-view'),('transaction-create','mediaFile-create'),('transaction-delete','mediaFile-delete'),('transaction-index','mediaFile-index'),('transaction-report','mediaFile-report'),('transaction-update','mediaFile-update'),('transaction-view','mediaFile-view'),('transaction-create','profile-create'),('transaction-delete','profile-delete'),('transaction-index','profile-index'),('transaction-report','profile-report'),('transaction-update','profile-update'),('transaction-view','profile-view'),('coordinator','regular'),('transaction-create','shelter-create'),('transaction-delete','shelter-delete'),('guest','shelter-index'),('transaction-index','shelter-index'),('transaction-report','shelter-report'),('transaction-update','shelter-update'),('guest','shelter-view'),('transaction-view','shelter-view'),('regular','transaction-create'),('admin','transaction-delete'),('regular','transaction-index'),('coordinator','transaction-report'),('regular','transaction-update'),('regular','transaction-view'),('master-create','unit-create'),('master-delete','unit-delete'),('master-index','unit-index'),('master-report','unit-report'),('master-update','unit-update'),('master-view','unit-view'),('admin','user-create'),('admin','user-delete'),('admin','user-index'),('admin','user-report'),('admin','user-update'),('admin','user-view'),('master-create','vehicleType-create'),('master-delete','vehicleType-delete'),('master-index','vehicleType-index'),('master-report','vehicleType-report'),('master-update','vehicleType-update'),('master-view','vehicleType-view'),('transaction-create','verification-create'),('transaction-delete','verification-delete'),('transaction-index','verification-index'),('transaction-report','verification-report'),('transaction-update','verification-update'),('transaction-view','verification-view'),('master-create','verificationAction-create'),('master-delete','verificationAction-delete'),('master-index','verificationAction-index'),('master-report','verificationAction-report'),('master-update','verificationAction-update'),('master-view','verificationAction-view'),('transaction-create','verificationVote-create'),('transaction-delete','verificationVote-delete'),('transaction-index','verificationVote-index'),('transaction-report','verificationVote-report'),('transaction-update','verificationVote-update'),('transaction-view','verificationVote-view');
/*!40000 ALTER TABLE `t_auth_item_child` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_auth_rule`
--

DROP TABLE IF EXISTS `t_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_auth_rule` (
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `data` blob,
  `created_at` int DEFAULT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_auth_rule`
--

LOCK TABLES `t_auth_rule` WRITE;
/*!40000 ALTER TABLE `t_auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_auth_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_disaster`
--

DROP TABLE IF EXISTS `t_disaster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_disaster` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `disaster_type_id` int DEFAULT NULL COMMENT '(banjir, gempa, dll)',
  `disaster_status_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_disaster_relation_type` (`disaster_type_id`),
  KEY `t_disaster_relation_status` (`disaster_status_id`),
  CONSTRAINT `t_disaster_relation_status` FOREIGN KEY (`disaster_status_id`) REFERENCES `t_disaster_status` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_disaster_relation_type` FOREIGN KEY (`disaster_type_id`) REFERENCES `t_disaster_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_disaster`
--

LOCK TABLES `t_disaster` WRITE;
/*!40000 ALTER TABLE `t_disaster` DISABLE KEYS */;
INSERT INTO `t_disaster` VALUES (2,NULL,1,2,'2025-12-01','2025-12-24','hjkgkghkg hkjhlkhlkjhk hhhhhhhhhhhhhhh','2025-12-20 14:44:31','2025-12-20 14:48:40',1,1,1,'2025-12-20 07:48:40',1,2,'b89ca4c8dd7711f09ac4c858c0b7f92b'),(3,NULL,1,2,'2025-12-18','2026-01-01','asd asd fasd f asdf ','2025-12-20 14:53:23','2025-12-20 14:53:26',1,1,1,'2025-12-20 07:53:26',1,1,'f5683480dd7811f09ac4c858c0b7f92b');
/*!40000 ALTER TABLE `t_disaster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_disaster_status`
--

DROP TABLE IF EXISTS `t_disaster_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_disaster_status` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_disaster_status_index_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_disaster_status`
--

LOCK TABLES `t_disaster_status` WRITE;
/*!40000 ALTER TABLE `t_disaster_status` DISABLE KEYS */;
INSERT INTO `t_disaster_status` VALUES (1,'FIXED_FINALo','Fixed Final Titleo','Descr','2025-12-20 13:59:15','2025-12-20 18:56:19',1,1,1,'2025-12-20 11:56:19',1,4,'65e77ac2dd7111f09ac4c858c0b7f92b'),(2,'cancel','Cancel','test','2025-12-20 14:04:41','2025-12-20 18:56:08',1,1,1,'2025-12-20 11:56:08',1,1,'27eaa978dd7211f09ac4c858c0b7f92b'),(3,'test lagi','testlagi','test','2025-12-20 14:07:58','2025-12-20 18:39:45',1,1,1,'2025-12-20 11:39:45',1,1,'9d7d8b65dd7211f09ac4c858c0b7f92b'),(4,'lagi lagi','lagi','lagiiiii','2025-12-20 14:11:46','2025-12-20 18:23:08',1,1,1,'2025-12-20 11:23:08',1,1,'250c9539dd7311f09ac4c858c0b7f92b');
/*!40000 ALTER TABLE `t_disaster_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_disaster_type`
--

DROP TABLE IF EXISTS `t_disaster_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_disaster_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_disaster_type_index_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_disaster_type`
--

LOCK TABLES `t_disaster_type` WRITE;
/*!40000 ALTER TABLE `t_disaster_type` DISABLE KEYS */;
INSERT INTO `t_disaster_type` VALUES (1,'jjjj','jjj','jjjjjjjkhkjhhjhkj','2025-12-20 14:37:28','2025-12-20 18:56:26',1,1,1,'2025-12-20 11:56:26',1,3,'bc2316d1dd7611f09ac4c858c0b7f92b'),(2,'bnbnbn','nbnbnbnbnb','nbnmbmnmnbjbjbj klhlk ','2025-12-20 15:38:03','2025-12-20 15:54:46',1,1,1,'2025-12-20 08:54:46',1,3,'32d499c0dd7f11f09ac4c858c0b7f92b'),(3,'test','te','st','2025-12-22 11:09:13','2025-12-22 11:09:13',1,1,0,NULL,NULL,0,'f979390ddeeb11f09d3fc858c0b7f92b');
/*!40000 ALTER TABLE `t_disaster_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_entity_type`
--

DROP TABLE IF EXISTS `t_entity_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_entity_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_entity_type_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_entity_type`
--

LOCK TABLES `t_entity_type` WRITE;
/*!40000 ALTER TABLE `t_entity_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_entity_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_item`
--

DROP TABLE IF EXISTS `t_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `item_category_id` int DEFAULT NULL COMMENT 'food, water, medicine, shelter, etc for disaster',
  `title` varchar(255) DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` int DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_item_relation_category` (`item_category_id`),
  CONSTRAINT `t_item_relation_category` FOREIGN KEY (`item_category_id`) REFERENCES `t_item_category` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_item`
--

LOCK TABLES `t_item` WRITE;
/*!40000 ALTER TABLE `t_item` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_item_category`
--

DROP TABLE IF EXISTS `t_item_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_item_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_disaster_type_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_item_category`
--

LOCK TABLES `t_item_category` WRITE;
/*!40000 ALTER TABLE `t_item_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_item_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_media_file`
--

DROP TABLE IF EXISTS `t_media_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_media_file` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `entity_type_id` int DEFAULT NULL COMMENT 'shelter | distribution | route',
  `entity_id` int DEFAULT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `file_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `taken_at` datetime DEFAULT NULL,
  `uploaded_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_media_entity` (`entity_type_id`,`entity_id`),
  CONSTRAINT `t_media_files_relation_entity_type` FOREIGN KEY (`entity_type_id`) REFERENCES `t_entity_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_media_file`
--

LOCK TABLES `t_media_file` WRITE;
/*!40000 ALTER TABLE `t_media_file` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_media_file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_migration`
--

DROP TABLE IF EXISTS `t_migration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_migration`
--

LOCK TABLES `t_migration` WRITE;
/*!40000 ALTER TABLE `t_migration` DISABLE KEYS */;
INSERT INTO `t_migration` VALUES ('Da\\User\\Migration\\m000000_000001_create_user_table',1765883503),('Da\\User\\Migration\\m000000_000002_create_profile_table',1765883503),('Da\\User\\Migration\\m000000_000003_create_social_account_table',1765883503),('Da\\User\\Migration\\m000000_000004_create_token_table',1765883503),('Da\\User\\Migration\\m000000_000005_add_last_login_at',1765883503),('Da\\User\\Migration\\m000000_000006_add_two_factor_fields',1765883503),('Da\\User\\Migration\\m000000_000007_enable_password_expiration',1765883503),('Da\\User\\Migration\\m000000_000008_add_last_login_ip',1765883503),('Da\\User\\Migration\\m000000_000009_add_gdpr_consent_fields',1765883503),('Da\\User\\Migration\\m000000_000010_add_auth_tf_type_auth_tf_mobile_phone_columns_to_user_table',1765883504),('m000000_000000_base',1765781075),('m140506_102106_rbac_init',1765883443),('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1765883443),('m180523_151638_rbac_updates_indexes_without_prefix',1765883443),('m200409_110543_rbac_update_mssql_trigger',1765883443),('m240101_000001_create_users_table',1765781077),('m240101_000002_create_password_reset_tokens_table',1765781077),('m240717_000001_create_admin_user',1765965246),('m251215_094135_create_disaster_table',1765791861),('m251216_000001_insert_admin_user',1765883710),('m251217_095248_test_check',1765965246);
/*!40000 ALTER TABLE `t_migration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_password_reset_tokens`
--

DROP TABLE IF EXISTS `t_password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_password_reset_tokens`
--

LOCK TABLES `t_password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `t_password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_profile`
--

DROP TABLE IF EXISTS `t_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_profile` (
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `public_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gravatar_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gravatar_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `timezone` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `t_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_profile`
--

LOCK TABLES `t_profile` WRITE;
/*!40000 ALTER TABLE `t_profile` DISABLE KEYS */;
INSERT INTO `t_profile` VALUES (1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `t_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_shelter`
--

DROP TABLE IF EXISTS `t_shelter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_shelter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `disaster_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `latitude` decimal(11,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `evacuee_count` int DEFAULT NULL,
  `aid_status` tinyint DEFAULT NULL COMMENT 'derived',
  `last_aid_distribution_at` datetime DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` int DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_shelter_relation_disaster` (`disaster_id`),
  KEY `idx_shelter_latlng` (`latitude`,`longitude`),
  CONSTRAINT `t_shelter_relation_disaster` FOREIGN KEY (`disaster_id`) REFERENCES `t_disaster` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_shelter`
--

LOCK TABLES `t_shelter` WRITE;
/*!40000 ALTER TABLE `t_shelter` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_shelter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_social_account`
--

DROP TABLE IF EXISTS `t_social_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_social_account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_social_account_provider_client_id` (`provider`,`client_id`),
  UNIQUE KEY `idx_social_account_code` (`code`),
  KEY `fk_social_account_user` (`user_id`),
  CONSTRAINT `fk_social_account_user` FOREIGN KEY (`user_id`) REFERENCES `t_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_social_account`
--

LOCK TABLES `t_social_account` WRITE;
/*!40000 ALTER TABLE `t_social_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_social_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_token`
--

DROP TABLE IF EXISTS `t_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_token` (
  `user_id` int DEFAULT NULL,
  `code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` smallint NOT NULL,
  `created_at` int NOT NULL,
  UNIQUE KEY `idx_token_user_id_code_type` (`user_id`,`code`,`type`),
  CONSTRAINT `fk_token_user` FOREIGN KEY (`user_id`) REFERENCES `t_user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_token`
--

LOCK TABLES `t_token` WRITE;
/*!40000 ALTER TABLE `t_token` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_unit`
--

DROP TABLE IF EXISTS `t_unit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_unit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_disaster_status_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_unit`
--

LOCK TABLES `t_unit` WRITE;
/*!40000 ALTER TABLE `t_unit` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_unit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_user`
--

DROP TABLE IF EXISTS `t_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password_hash` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `unconfirmed_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `registration_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `flags` int NOT NULL DEFAULT '0',
  `confirmed_at` int DEFAULT NULL,
  `blocked_at` int DEFAULT NULL,
  `updated_at` int NOT NULL,
  `created_at` int NOT NULL,
  `last_login_at` int DEFAULT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `auth_tf_key` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `auth_tf_enabled` tinyint(1) DEFAULT '0',
  `auth_tf_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `auth_tf_mobile_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password_changed_at` int DEFAULT NULL,
  `gdpr_consent` tinyint(1) DEFAULT '0',
  `gdpr_consent_date` int DEFAULT NULL,
  `gdpr_deleted` tinyint(1) DEFAULT '0',
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_user_username` (`username`),
  UNIQUE KEY `idx_user_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_user`
--

LOCK TABLES `t_user` WRITE;
/*!40000 ALTER TABLE `t_user` DISABLE KEYS */;
INSERT INTO `t_user` VALUES (1,'admin','ombakrinai@gmail.com','$2y$10$yn1fG2byleO/oDVnnz.VteVDF.MGwL7QkMQ1LcjaLYtMu98mHjKQO','5fNVddTTNYd_ykEQVpLOGuEhgF07bk77',NULL,NULL,0,1765965246,NULL,1765965246,1765965246,1765982890,'127.0.0.1','',0,NULL,NULL,1765965246,0,NULL,0,NULL,NULL);
/*!40000 ALTER TABLE `t_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_vehicle_type`
--

DROP TABLE IF EXISTS `t_vehicle_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_vehicle_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_vehicle_types_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_vehicle_type`
--

LOCK TABLES `t_vehicle_type` WRITE;
/*!40000 ALTER TABLE `t_vehicle_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_vehicle_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_verification`
--

DROP TABLE IF EXISTS `t_verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_verification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entity_type_id` int DEFAULT NULL COMMENT 'shelter | distribution | route',
  `entity_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `last_activity_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_entity_verification` (`entity_type_id`,`entity_id`),
  KEY `idx_verification_entity` (`entity_type_id`,`entity_id`),
  KEY `idx_verification_activity` (`entity_type_id`,`last_activity_at`),
  CONSTRAINT `t_verification_relation_entity_type` FOREIGN KEY (`entity_type_id`) REFERENCES `t_entity_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_verification`
--

LOCK TABLES `t_verification` WRITE;
/*!40000 ALTER TABLE `t_verification` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_verification_type`
--

DROP TABLE IF EXISTS `t_verification_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_verification_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'confirm, deny, outdated, blocked',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_verification_action_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_verification_type`
--

LOCK TABLES `t_verification_type` WRITE;
/*!40000 ALTER TABLE `t_verification_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_verification_vote`
--

DROP TABLE IF EXISTS `t_verification_vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_verification_vote` (
  `id` int NOT NULL AUTO_INCREMENT,
  `verification_id` int DEFAULT NULL,
  `verification_type_id` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `voted_by` int DEFAULT NULL,
  `voted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_verification_user` (`verification_id`,`voted_by`),
  KEY `idx_votes_action` (`verification_type_id`),
  KEY `idx_votes_time` (`voted_at`),
  KEY `t_verification_vote_relation_vote_by` (`voted_by`),
  CONSTRAINT `t_verification_vote_relation_master` FOREIGN KEY (`verification_id`) REFERENCES `t_verification` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_verification_vote_relation_type` FOREIGN KEY (`verification_type_id`) REFERENCES `t_verification_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_verification_vote_relation_vote_by` FOREIGN KEY (`voted_by`) REFERENCES `t_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_verification_vote`
--

LOCK TABLES `t_verification_vote` WRITE;
/*!40000 ALTER TABLE `t_verification_vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification_vote` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-23 14:20:41
