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
  `shelter_id` int DEFAULT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `route_geometry` linestring DEFAULT NULL,
  `route_status` int DEFAULT NULL COMMENT 'safe | damaged | blocked',
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
  KEY `t_access_route_relation_disaster` (`disaster_id`),
  CONSTRAINT `t_access_route_relation_disaster` FOREIGN KEY (`disaster_id`) REFERENCES `t_disaster` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
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
  `distribution_date` date DEFAULT NULL,
  `distributed_by` int DEFAULT NULL,
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
  KEY `t_aid_distribution_relation_plan` (`aid_plan_id`),
  KEY `t_aid_distribution_relation_shelter` (`shelter_id`),
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
  `unit` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_distribution_details_relation_master` (`aid_distribution_id`),
  KEY `t_aid_distribution_details_relation_item` (`aid_item_id`),
  CONSTRAINT `t_aid_distribution_details_relation_item` FOREIGN KEY (`aid_item_id`) REFERENCES `t_aid_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_distribution_details_relation_master` FOREIGN KEY (`aid_distribution_id`) REFERENCES `t_aid_distribution` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `distribution_plan_date` date DEFAULT NULL,
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
  `unit` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_aid_plan_details_relation_plan` (`aid_plan_id`),
  KEY `t_aid_plan_details_relation_item` (`aid_item_id`),
  CONSTRAINT `t_aid_plan_details_relation_item` FOREIGN KEY (`aid_item_id`) REFERENCES `t_aid_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_aid_plan_details_relation_plan` FOREIGN KEY (`aid_plan_id`) REFERENCES `t_aid_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  `disaster_type` int DEFAULT NULL COMMENT '(banjir, gempa, dll)',
  `disaster_status` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `updated_by` int DEFAULT NULL,
  `is_deleted` int DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
  `deleted_by` int DEFAULT NULL,
  `verlock` int DEFAULT NULL,
  `uuid` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_disaster`
--

LOCK TABLES `t_disaster` WRITE;
/*!40000 ALTER TABLE `t_disaster` DISABLE KEYS */;
INSERT INTO `t_disaster` VALUES (1,2,1,'2025-12-01','2025-12-31','test test test','2025-12-15 20:54:42','2025-12-15 20:54:42',1,1,0,NULL,NULL,0,'9b52f176d9bd11f0812bc858c0b7f92b');
/*!40000 ALTER TABLE `t_disaster` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_media_files`
--

DROP TABLE IF EXISTS `t_media_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_media_files` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `entity_type` int DEFAULT NULL COMMENT 'shelter | distribution | route',
  `entity_id` int DEFAULT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `uploaded_by` int DEFAULT NULL,
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
  `verification_status` int DEFAULT NULL,
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
  PRIMARY KEY (`id`),
  KEY `t_shelter_relation_disaster` (`disaster_id`),
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
  `title` varchar(255) DEFAULT NULL,
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
  `entity_type` int DEFAULT NULL COMMENT 'shelter | distribution | route',
  `entity_id` int DEFAULT NULL,
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
-- Dumping data for table `t_verification`
--

LOCK TABLES `t_verification` WRITE;
/*!40000 ALTER TABLE `t_verification` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_verification_details`
--

DROP TABLE IF EXISTS `t_verification_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_verification_details` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `verification_id` int DEFAULT NULL,
  `verification_status` int DEFAULT NULL COMMENT 'approved | rejected',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `verified_by` int DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `t_verification_details_relation_verification` (`verification_id`),
  CONSTRAINT `t_verification_details_relation_verification` FOREIGN KEY (`verification_id`) REFERENCES `t_verification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_verification_details`
--

LOCK TABLES `t_verification_details` WRITE;
/*!40000 ALTER TABLE `t_verification_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification_details` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-16  3:28:23
