ALTER TABLE `points` CHANGE `type` `type` ENUM('checkin_point','product_share_point','invite_friend_share_point','register_points','per_order_point','redeem') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('0', 'register_points', '5', 'point_setting', NULL, NULL, NULL, NULL);
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('0', 'minimum_order_total', '150', 'business_general', NULL, NULL, NULL, NULL);
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('0', 'free_shipping_order_price', '299', 'business_general', NULL, NULL, NULL, NULL);
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES ('0', 'business_name', 'The Kundol Shop', 'business_general', NULL, NULL, NULL, NULL);

ALTER TABLE `sale_quotations` ADD `customer_address_book_id` BIGINT(20) NULL AFTER `customer_id`;
ALTER TABLE `sale_quotations` ADD `coupon_setting_id` BIGINT(20) NULL AFTER `payable`;

ALTER TABLE `sale_quotation_details` CHANGE `qty` `qty` INT(20) NULL DEFAULT NULL;

ALTER TABLE `orders` ADD `warehouse_id` BIGINT(20) NULL DEFAULT NULL AFTER `delivery_boy_id`;

ALTER TABLE `orders` ADD `order_from` ENUM('androidapp','iosapp','web','pos','addsale') NOT NULL DEFAULT 'androidapp' AFTER `warehouse_id`;
