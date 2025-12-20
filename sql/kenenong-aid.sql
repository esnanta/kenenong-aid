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
-- Table structure for table `t_access_route_shelters`
--

DROP TABLE IF EXISTS `t_access_route_shelters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route_shelters` (
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
-- Table structure for table `t_access_route_vehicles`
--

DROP TABLE IF EXISTS `t_access_route_vehicles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_access_route_vehicles` (
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
  CONSTRAINT `t_access_route_vehicles_relation_route` FOREIGN KEY (`access_route_id`) REFERENCES `t_access_route` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_access_route_vehicles_relation_type` FOREIGN KEY (`vehicle_type_id`) REFERENCES `t_vehicle_type` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
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
INSERT INTO `t_auth_assignment` VALUES ('admin','1',1766213419);
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
INSERT INTO `t_auth_item` VALUES ('accessRoute.create',2,'Create Access Route',NULL,NULL,1766213419,1766213419),('accessRoute.delete',2,'Delete Access Route',NULL,NULL,1766213419,1766213419),('accessRoute.index',2,'Index Access Route',NULL,NULL,1766213419,1766213419),('accessRoute.report',2,'Report Access Route',NULL,NULL,1766213419,1766213419),('accessRoute.update',2,'Update Access Route',NULL,NULL,1766213419,1766213419),('accessRoute.view',2,'View Access Route',NULL,NULL,1766213419,1766213419),('admin',1,'Administrator',NULL,NULL,1766213419,1766213419),('coordinator',1,'Coordinator',NULL,NULL,1766213419,1766213419),('disaster.create',2,'Create Disaster',NULL,NULL,1766213419,1766213419),('disaster.delete',2,'Delete Disaster',NULL,NULL,1766213419,1766213419),('disaster.index',2,'Index Disaster',NULL,NULL,1766213419,1766213419),('disaster.report',2,'Report Disaster',NULL,NULL,1766213419,1766213419),('disaster.update',2,'Update Disaster',NULL,NULL,1766213419,1766213419),('disaster.view',2,'View Disaster',NULL,NULL,1766213419,1766213419),('disasterStatus.create',2,'Create Status Disaster',NULL,NULL,1766213419,1766213419),('disasterStatus.delete',2,'Delete Status Disaster',NULL,NULL,1766213419,1766213419),('disasterStatus.index',2,'Index Status Disaster',NULL,NULL,1766213419,1766213419),('disasterStatus.report',2,'Report Status Disaster',NULL,NULL,1766213419,1766213419),('disasterStatus.update',2,'Update Status Disaster',NULL,NULL,1766213419,1766213419),('disasterStatus.view',2,'View Status Disaster',NULL,NULL,1766213419,1766213419),('disasterType.create',2,'Create Type Disaster',NULL,NULL,1766213419,1766213419),('disasterType.delete',2,'Delete Type Disaster',NULL,NULL,1766213419,1766213419),('disasterType.index',2,'Index Type Disaster',NULL,NULL,1766213419,1766213419),('disasterType.report',2,'Report Type Disaster',NULL,NULL,1766213419,1766213419),('disasterType.update',2,'Update Type Disaster',NULL,NULL,1766213419,1766213419),('disasterType.view',2,'View Type Disaster',NULL,NULL,1766213419,1766213419),('guest',1,'Guest',NULL,NULL,1766213419,1766213419),('master.create',2,'Create Master',NULL,NULL,1766213419,1766213419),('master.delete',2,'Delete Master',NULL,NULL,1766213419,1766213419),('master.index',2,'Index Master',NULL,NULL,1766213419,1766213419),('master.report',2,'Report Master',NULL,NULL,1766213419,1766213419),('master.update',2,'Update Master',NULL,NULL,1766213419,1766213419),('master.view',2,'View Master',NULL,NULL,1766213419,1766213419),('regular',1,'Regular User',NULL,NULL,1766213419,1766213419),('transaction.create',2,'Create Transaction',NULL,NULL,1766213419,1766213419),('transaction.delete',2,'Delete Transaction',NULL,NULL,1766213419,1766213419),('transaction.index',2,'Index Transaction',NULL,NULL,1766213419,1766213419),('transaction.report',2,'Report Transaction',NULL,NULL,1766213419,1766213419),('transaction.update',2,'Update Transaction',NULL,NULL,1766213419,1766213419),('transaction.view',2,'View Transaction',NULL,NULL,1766213419,1766213419);
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
INSERT INTO `t_auth_item_child` VALUES ('master.create','accessRoute.create'),('master.delete','accessRoute.delete'),('master.index','accessRoute.index'),('master.report','accessRoute.report'),('master.update','accessRoute.update'),('master.view','accessRoute.view'),('admin','coordinator'),('master.create','disaster.create'),('master.delete','disaster.delete'),('master.index','disaster.index'),('master.report','disaster.report'),('master.update','disaster.update'),('master.view','disaster.view'),('master.create','disasterStatus.create'),('master.delete','disasterStatus.delete'),('master.index','disasterStatus.index'),('master.report','disasterStatus.report'),('master.update','disasterStatus.update'),('master.view','disasterStatus.view'),('master.create','disasterType.create'),('master.delete','disasterType.delete'),('master.index','disasterType.index'),('master.report','disasterType.report'),('master.update','disasterType.update'),('master.view','disasterType.view'),('admin','master.create'),('admin','master.delete'),('admin','master.index'),('admin','master.report'),('admin','master.update'),('admin','master.view'),('coordinator','regular'),('admin','transaction.create'),('regular','transaction.create'),('admin','transaction.delete'),('admin','transaction.index'),('regular','transaction.index'),('admin','transaction.report'),('admin','transaction.update'),('regular','transaction.update'),('admin','transaction.view'),('regular','transaction.view');
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
-- Dumping data for table `t_verification_action`
--

LOCK TABLES `t_verification_action` WRITE;
/*!40000 ALTER TABLE `t_verification_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `t_verification_action` ENABLE KEYS */;
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
  `verification_action_id` int DEFAULT NULL,
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
  KEY `idx_votes_action` (`verification_action_id`),
  KEY `idx_votes_time` (`voted_at`),
  KEY `t_verification_vote_relation_vote_by` (`voted_by`),
  CONSTRAINT `t_verification_vote_relation_vote_by` FOREIGN KEY (`voted_by`) REFERENCES `t_user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_verification_votes_relation_action` FOREIGN KEY (`verification_action_id`) REFERENCES `t_verification_action` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_verification_votes_relation_verification` FOREIGN KEY (`verification_id`) REFERENCES `t_verification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
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

-- Dump completed on 2025-12-20 13:51:14
