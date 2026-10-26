-- =====================================================================
-- AlphaMindz - Coupon Management System Database Migration
-- Run this script in your production database (e.g. via phpMyAdmin)
-- =====================================================================

-- 1. Create `coupons` table
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) NOT NULL,
  `discount_type` ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
  `discount_value` DECIMAL(10,2) NOT NULL,
  `min_spend` DECIMAL(10,2) DEFAULT NULL,
  `valid_till` DATE NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `used_count` INT(11) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Add coupon discount columns to `orders` table
-- If your MySQL/MariaDB version supports IF NOT EXISTS on ALTER:
ALTER TABLE `orders` 
ADD COLUMN IF NOT EXISTS `subtotal_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `customer_phone`,
ADD COLUMN IF NOT EXISTS `coupon_code` VARCHAR(50) DEFAULT NULL AFTER `subtotal_amount`,
ADD COLUMN IF NOT EXISTS `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `coupon_code`;

-- Note: If running on a strict MySQL server that rejects 'IF NOT EXISTS' in ALTER TABLE,
-- run these three lines instead:
-- ALTER TABLE `orders` ADD COLUMN `subtotal_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `customer_phone`;
-- ALTER TABLE `orders` ADD COLUMN `coupon_code` VARCHAR(50) DEFAULT NULL AFTER `subtotal_amount`;
-- ALTER TABLE `orders` ADD COLUMN `discount_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00 AFTER `coupon_code`;
