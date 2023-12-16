<?php

/**
 * Class Migration_Add_field_product
 */
class Migration_Add_field_product extends CI_Migration {

    public function up() {
        $sql = "ALTER TABLE `products` 
ADD COLUMN `parent_id` int NULL AFTER `id`,
ADD COLUMN `group_id` int NULL AFTER `avatar`,
ADD COLUMN `purchase_price` int NOT NULL DEFAULT 0 AFTER `group_id`,
ADD COLUMN `quantity` int NOT NULL DEFAULT 0 AFTER `price`,
ADD COLUMN `unit_value` int NOT NULL DEFAULT 1 AFTER `unit`,
ADD COLUMN `barcode` varchar(255) NULL AFTER `code`";
        $this->db->query($sql);
        $sql1 = "CREATE TABLE `category_products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp,
  `created_by` int NULL,
  `lastest_update_on` datetime NULL,
  `lastest_update_by` int NULL,
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);";
        $this->db->query($sql1);
        $sql2 = "CREATE TABLE `purchase_order`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `supplier_id` int NULL,
  `status` varchar(255) NULL,
  `total` int NOT NULL DEFAULT 0 COMMENT '=grand - discount',
  `discount_amount` int NULL DEFAULT 0,
  `grand_total` int NULL DEFAULT 0,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `payment_date` datetime NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp,
  `created_by` int NULL,
  `lastest_update_on` datetime NULL,
  `lastest_update_by` int NULL,
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);";
        $this->db->query($sql2);

        $sql3 = "CREATE TABLE `purchase_order_detail`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `purchase_order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT 0,
  `purchase_price` int NOT NULL DEFAULT 0,
  `discount_amount` int NOT NULL DEFAULT 0 COMMENT 'giam gia tren 1 san pham',
  `value_total` int NOT NULL DEFAULT 0 COMMENT '=(purchase_price - discount_amount) * quantity',
  `product_info` text NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp,
  `created_by` int NULL,
  `lastest_update_on` datetime NULL,
  `lastest_update_by` int NULL,
  `deleted` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);";
        $this->db->query($sql3);
    }

    public function down() {
        $sql1 = "DROP TABLE IF EXISTS `category_products`;";
        $this->db->query($sql1);
        $sql2 = "DROP TABLE IF EXISTS `purchase_order`;";
        $this->db->query($sql2);
        $sql3 = "DROP TABLE IF EXISTS `purchase_order_detail`;";
        $this->db->query($sql3);
    }
}