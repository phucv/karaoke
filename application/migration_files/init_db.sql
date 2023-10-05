SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bill_details
-- ----------------------------
DROP TABLE IF EXISTS `bill_details`;
CREATE TABLE `bill_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `quantity` decimal(11,1) NOT NULL,
  `price` int(11) NOT NULL,
  `value_total` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` datetime DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='luu thong tin tai khoan nguoi dung dang nhap he thong';

-- ----------------------------
-- Table structure for bills
-- ----------------------------
DROP TABLE IF EXISTS `bills`;
CREATE TABLE `bills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total` int(11) NOT NULL COMMENT '=grand - discount',
  `discount_amount` int(11) NOT NULL DEFAULT 0,
  `grand_total` int(11) NOT NULL DEFAULT 0,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new' COMMENT 'new, done',
  `payment_method` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'cash' COMMENT 'tien mat, chuyen khoan',
  `payment_date` datetime DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` datetime DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='luu thong tin tai khoan nguoi dung dang nhap he thong';

-- ----------------------------
-- Table structure for ci_migrations
-- ----------------------------
DROP TABLE IF EXISTS `ci_migrations`;
CREATE TABLE `ci_migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ci_migrations
-- ----------------------------
BEGIN;
INSERT INTO `ci_migrations` VALUES (20231001142700);
COMMIT;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `ci_sessions_timestamp` (`timestamp`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for config_menu
-- ----------------------------
DROP TABLE IF EXISTS `config_menu`;
CREATE TABLE `config_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `controller` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `obj_active` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(5) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_on` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` datetime DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of config_menu
-- ----------------------------
BEGIN;
INSERT INTO `config_menu` VALUES (1, 1, 'Làm việc', 'work', 'work.*', '<i class=\"fa fa-book\"></i>', NULL, 1, 1, '2020-01-09 10:20:11', 1, NULL, NULL, 0, '');
INSERT INTO `config_menu` VALUES (2, 1, 'Quản lý phòng', 'room', 'room.*', 'domain', NULL, 2, 1, NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `config_menu` VALUES (3, 1, 'Quản lý sản phẩm', 'product', 'product.*', 'card_giftcard', NULL, 3, 1, NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `config_menu` VALUES (4, 1, 'Quản lý nhân viên', 'user', 'user.*', 'person', NULL, 4, 1, NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `config_menu` VALUES (5, 2, 'Làm việc', 'work', 'work.*', '<i class=\"fa fa-book\"></i>', NULL, 1, 1, NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `config_menu` VALUES (6, 1, 'Hoá đơn', 'bill', 'bill.*', 'pie_chart', NULL, 5, 1, NULL, NULL, NULL, NULL, 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` datetime DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='luu thong tin tai khoan nguoi dung dang nhap he thong';

-- ----------------------------
-- Table structure for rooms
-- ----------------------------
DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `area` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `time_enter` datetime DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` datetime DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `avatar` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='luu thong tin tai khoan nguoi dung dang nhap he thong';

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sex` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'male/female/other',
  `public` tinyint(2) NOT NULL DEFAULT 1,
  `session_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` datetime DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `username_deleted` (`username`,`deleted`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='luu thong tin tai khoan nguoi dung dang nhap he thong';

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES (1, 1, 'admin', '4297f44b13955235245b2497399d7a93', 'Admin', 'assets/images/default-avatar.png', NULL, NULL, 1, 'abn193tlamvnlkfk6ijbgvlcjeqbf1gq', '2023-09-29 10:17:06', NULL, '2023-10-05 14:28:59', 1, 0);
INSERT INTO `users` VALUES (2, 2, 'nhan_vien', '4297f44b13955235245b2497399d7a93', 'Nhân viên', 'assets/images/default-avatar.png', '', 'male', 1, 'b4h5r7v5uhdcegpupkl4qakqtum2ldsl', '2023-09-29 10:17:06', NULL, '2023-10-05 16:38:55', 2, 0);
COMMIT;

-- ----------------------------
-- Table structure for users_role
-- ----------------------------
DROP TABLE IF EXISTS `users_role`;
CREATE TABLE `users_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_data` text CHARACTER SET utf8 DEFAULT NULL COMMENT '*.*;home.*',
  `role_data_limit` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `public` int(11) DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) DEFAULT NULL,
  `lastest_update_on` datetime DEFAULT NULL,
  `lastest_update_by` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='dinh nghia cac quyen cua nguoi dung, bao gom: admin, PDT, giao vien, hoc vien';

-- ----------------------------
-- Records of users_role
-- ----------------------------
BEGIN;
INSERT INTO `users_role` VALUES (1, 'Quản trị hệ thống', 'admin', NULL, '*.*', NULL, NULL, 'manager', NULL, '2023-09-29 10:17:06', NULL, NULL, NULL, 0);
INSERT INTO `users_role` VALUES (2, 'Nhân viên', 'nv', NULL, 'work.*;profile.*', NULL, NULL, 'view', NULL, '2023-09-29 10:17:06', NULL, NULL, NULL, 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
