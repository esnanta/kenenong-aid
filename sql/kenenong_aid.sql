-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: kenenong_aid
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
  `is_deleted` tinyint(1) DEFAULT NULL,
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
-- Table structure for table `t_access_route_shelters`
--

DROP TABLE IF EXISTS `t_access_route_shelters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route_shelters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_route_id` int DEFAULT NULL,
  `shelter_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_access_route_shelters_relation_route` (`access_route_id`),
  KEY `t_access_route_shelters_relation_shelter` (`shelter_id`),
  CONSTRAINT `t_access_route_shelters_relation_route` FOREIGN KEY (`access_route_id`) REFERENCES `t_access_route` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_access_route_shelters_relation_shelter` FOREIGN KEY (`shelter_id`) REFERENCES `t_shelter` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_access_route_shelters`
--

LOCK TABLES `t_access_route_shelters` WRITE;
/*!40000 ALTER TABLE `t_access_route_shelters` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_access_route_shelters` ENABLE KEYS */;
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
  `is_deleted` tinyint(1) DEFAULT NULL,
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
-- Table structure for table `t_access_route_vehicles`
--

DROP TABLE IF EXISTS `t_access_route_vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route_vehicles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_route_id` int DEFAULT NULL,
  `vehicle_type_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_access_route_vehicles_relation_route` (`access_route_id`),
  KEY `t_access_route_vehicles_relation_vehicle` (`vehicle_type_id`),
  CONSTRAINT `t_access_route_vehicles_relation_route` FOREIGN KEY (`access_route_id`) REFERENCES `t_access_route` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_access_route_vehicles_relation_vehicle` FOREIGN KEY (`vehicle_type_id`) REFERENCES `t_vehicle_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_access_route_vehicles`
--

LOCK TABLES `t_access_route_vehicles` WRITE;
/*!40000 ALTER TABLE `t_access_route_vehicles` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_access_route_vehicles` ENABLE KEYS */;
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
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_distribution_relation_plan` (`aid_plan_id`),
  KEY `t_aid_distribution_relation_shelter` (`shelter_id`),
  KEY `t_aid_distribution_relation_distributed_by` (`distributed_by`),
  CONSTRAINT `t_aid_distribution_relation_distributed_by` FOREIGN KEY (`distributed_by`) REFERENCES `t_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
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
  `aid_item_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_distribution_details_relation_master` (`aid_distribution_id`),
  KEY `t_aid_distribution_details_relation_item` (`aid_item_id`),
  KEY `t_aid_distribution_details_relation_unit` (`unit_id`),
  CONSTRAINT `t_aid_distribution_details_relation_item` FOREIGN KEY (`aid_item_id`) REFERENCES `t_aid_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_distribution_details_relation_master` FOREIGN KEY (`aid_distribution_id`) REFERENCES `t_aid_distribution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_distribution_details_relation_unit` FOREIGN KEY (`unit_id`) REFERENCES `t_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
-- Table structure for table `t_aid_items`
--

DROP TABLE IF EXISTS `t_aid_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_aid_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `aid_category` int DEFAULT NULL COMMENT 'food, water, medicine, shelter, etc for disaster',
  `title` varchar(255) DEFAULT NULL,
  `unit` int DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` int DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_aid_items`
--

LOCK TABLES `t_aid_items` WRITE;
/*!40000 ALTER TABLE `t_aid_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_aid_items` ENABLE KEYS */;
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
  `is_deleted` int DEFAULT NULL,
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
  `aid_item_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_plan_details_relation_plan` (`aid_plan_id`),
  KEY `t_aid_plan_details_relation_item` (`aid_item_id`),
  KEY `t_aid_plan_details_relation_unit` (`unit_id`),
  CONSTRAINT `t_aid_plan_details_relation_item` FOREIGN KEY (`aid_item_id`) REFERENCES `t_aid_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_plan_details_relation_plan` FOREIGN KEY (`aid_plan_id`) REFERENCES `t_aid_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_plan_details_relation_unit` FOREIGN KEY (`unit_id`) REFERENCES `t_units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_disaster_relation_type` (`disaster_type_id`),
  KEY `t_disaster_relation_status` (`disaster_status_id`),
  CONSTRAINT `t_disaster_relation_status` FOREIGN KEY (`disaster_status_id`) REFERENCES `t_disaster_status` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_disaster_relation_type` FOREIGN KEY (`disaster_type_id`) REFERENCES `t_disaster_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_disaster`
--

LOCK TABLES `t_disaster` WRITE;
/*!40000 ALTER TABLE `t_disaster` DISABLE KEYS */;
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
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_disaster_status_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_disaster_status`
--

LOCK TABLES `t_disaster_status` WRITE;
/*!40000 ALTER TABLE `t_disaster_status` DISABLE KEYS */;
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
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_disaster_type_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_disaster_type`
--

LOCK TABLES `t_disaster_type` WRITE;
/*!40000 ALTER TABLE `t_disaster_type` DISABLE KEYS */;
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
  `is_deleted` tinyint(1) DEFAULT NULL,
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
-- Table structure for table `t_media_files`
--

DROP TABLE IF EXISTS `t_media_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_media_files` (
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
  `is_deleted` int DEFAULT NULL,
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
-- Dumping data for table `t_media_files`
--

LOCK TABLES `t_media_files` WRITE;
/*!40000 ALTER TABLE `t_media_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_media_files` ENABLE KEYS */;
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
INSERT INTO `t_migration` VALUES ('m000000_000000_base',1765781075),('m240101_000001_create_users_table',1765781077),('m240101_000002_create_password_reset_tokens_table',1765781077),('m251215_094135_create_disaster_table',1765791861);
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
  `is_deleted` int DEFAULT NULL,
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
-- Table structure for table `t_units`
--

DROP TABLE IF EXISTS `t_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_units` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_disaster_status_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_units`
--

LOCK TABLES `t_units` WRITE;
/*!40000 ALTER TABLE `t_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_users`
--

DROP TABLE IF EXISTS `t_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` int DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx-users-email` (`email`),
  KEY `idx-users-current_team_id` (`current_team_id`),
  KEY `idx-users-deleted_at` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_users`
--

LOCK TABLES `t_users` WRITE;
/*!40000 ALTER TABLE `t_users` DISABLE KEYS */;
INSERT INTO `t_users` VALUES (1,'Admin User','admin@example.com',NULL,'$2y$13$kljt9YSHk3Ng.Mg5wNTLPuHAX0lMUjd/VKQxe5NAIq2wyV0iRvlOq','9h8ZfSIKSaUQMtpTeAb93LsZ6h6mfN35xrQ4N9yFEhYCD46pmAVBpnR4HaAc',NULL,NULL,'2025-12-15 06:45:05','2025-12-15 06:45:05',NULL);
/*!40000 ALTER TABLE `t_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_vehicle_types`
--

DROP TABLE IF EXISTS `t_vehicle_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_vehicle_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_vehicle_types_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_vehicle_types`
--

LOCK TABLES `t_vehicle_types` WRITE;
/*!40000 ALTER TABLE `t_vehicle_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_vehicle_types` ENABLE KEYS */;
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
  `is_deleted` tinyint(1) DEFAULT NULL,
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
-- Table structure for table `t_verification_action`
--

DROP TABLE IF EXISTS `t_verification_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_verification_action` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'confirm, deny, outdated, blocked',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `weight` int DEFAULT NULL,
  `description` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `t_verification_action_index_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_verification_action`
--

LOCK TABLES `t_verification_action` WRITE;
/*!40000 ALTER TABLE `t_verification_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_verification_votes`
--

DROP TABLE IF EXISTS `t_verification_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_verification_votes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `verification_id` int DEFAULT NULL,
  `verification_action_id` int DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `voted_by` int DEFAULT NULL,
  `voted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_verification_user` (`verification_id`,`voted_by`),
  KEY `t_verification_votes_relation_voted_by` (`voted_by`),
  KEY `idx_votes_action` (`verification_action_id`),
  KEY `idx_votes_time` (`voted_at`),
  CONSTRAINT `t_verification_votes_relation_action` FOREIGN KEY (`verification_action_id`) REFERENCES `t_verification_action` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_verification_votes_relation_verification` FOREIGN KEY (`verification_id`) REFERENCES `t_verification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_verification_votes_relation_voted_by` FOREIGN KEY (`voted_by`) REFERENCES `t_users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_verification_votes`
--

LOCK TABLES `t_verification_votes` WRITE;
/*!40000 ALTER TABLE `t_verification_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification_votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-16 17:04:29
