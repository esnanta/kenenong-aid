-- RBAC Tables for Yii2
-- These tables are required for yii2-usuario and the RBAC system

DROP TABLE IF EXISTS `t_auth_assignment`;
DROP TABLE IF EXISTS `t_auth_item_child`;
DROP TABLE IF EXISTS `t_auth_item`;
DROP TABLE IF EXISTS `t_auth_rule`;

-- Auth Rule Table
CREATE TABLE `t_auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Auth Item Table (Roles and Permissions)
CREATE TABLE `t_auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `t_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `t_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Auth Item Child Table (Role/Permission Hierarchy)
CREATE TABLE `t_auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `t_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Auth Assignment Table (User Role Assignments)
CREATE TABLE `t_auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `t_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `t_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin role
INSERT INTO `t_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)
VALUES ('admin', 1, 'Administrator role with full access', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Note: After creating these tables, assign the admin role to your user:
-- INSERT INTO `t_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES ('admin', 1, UNIX_TIMESTAMP());
-- Replace '1' with your actual user ID

