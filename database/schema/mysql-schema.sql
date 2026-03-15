/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT (now()),
  `updated_at` timestamp NULL DEFAULT (now()),
  `bucketname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `displayorder` int NOT NULL,
  `current` tinyint(1) NOT NULL DEFAULT '1',
  `enrolment` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `classes_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes_orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `class_id` int unsigned NOT NULL,
  `profit` decimal(6,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `classes_orders_class_id_foreign` (`class_id`),
  KEY `classes_orders_order_id_foreign` (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `classes_pointsales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes_pointsales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pointsale_id` int unsigned NOT NULL,
  `class_id` int unsigned NOT NULL,
  `profit` decimal(6,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `classes_pointsales_class_id_foreign` (`class_id`),
  KEY `classes_pointsales_pointsale_id_foreign` (`pointsale_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cutoffdates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cutoffdates` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cutoff` datetime NOT NULL,
  `first` tinyint(1) NOT NULL,
  `charge` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `delivery` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `saveon_cheque_value` decimal(10,2) NOT NULL,
  `saveon_card_value` decimal(10,2) NOT NULL,
  `coop_cheque_value` decimal(10,2) NOT NULL,
  `coop_card_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `class_id` int unsigned NOT NULL,
  `expense_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `expenses_class_id_foreign` (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `permissions` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `cutoff_date_id` int unsigned NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `payment` tinyint NOT NULL,
  `saveon` int NOT NULL,
  `coop` int NOT NULL,
  `deliverymethod` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '2014-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '2014-01-01 00:00:00',
  `profit` decimal(6,2) NOT NULL,
  `coop_onetime` int DEFAULT NULL,
  `saveon_onetime` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_cutoff_date_id_foreign` (`cutoff_date_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reminders` (
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_reminders_email_index` (`email`),
  KEY `password_reminders_token_index` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pointsales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pointsales` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `payment` tinyint NOT NULL,
  `saveon_dollars` int NOT NULL,
  `coop_dollars` int NOT NULL,
  `profit` decimal(6,2) NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `saledate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `throttle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `throttle` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `two_factor_secret` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `permissions` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '2014-01-01 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '2014-01-01 00:00:00',
  `address1` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `address2` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `province` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `postal_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `stripe_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `last_four` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment` tinyint NOT NULL,
  `saveon` int NOT NULL,
  `coop` int NOT NULL,
  `deliverymethod` tinyint NOT NULL,
  `pickupalt` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `employee` tinyint(1) NOT NULL,
  `coop_onetime` int DEFAULT NULL,
  `saveon_onetime` int DEFAULT NULL,
  `no_beg` tinyint(1) NOT NULL DEFAULT '0',
  `reactivation_code` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '',
  `remember_token` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `isadmin` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_activation_code_index` (`activation_code`),
  KEY `users_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2012_12_06_225921_migration_cartalyst_sentry_install_users',1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2012_12_06_225929_migration_cartalyst_sentry_install_groups',1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2012_12_06_225945_migration_cartalyst_sentry_install_users_groups_pivot',1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2012_12_06_225988_migration_cartalyst_sentry_install_throttle',1);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_07_17_032830_users_alter_phone_and_name',2);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_07_19_033429_users_add_address_fields',2);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_07_19_055020_users_add_class_fields',2);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_08_04_202438_create_cutoffdates',2);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_08_07_061945_add_cashier_columns',2);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_08_14_214520_add_payment_info',2);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_08_16_172242_add_delivery_and_referral',2);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_08_25_002258_create_password_reminders_table',3);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_01_041106_create_orders',4);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_02_052111_make_sentry_admin_group',5);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_04_203347_add_tracking_to_orders',6);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_04_234202_add_tracking_to_orders_phase2',6);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_05_153911_add_order_referrers',7);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_05_174223_second_half_monthly_cutoffs',8);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_24_061303_move_cutoff_dates',9);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_09_28_194001_per_order_profit_numbers',10);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_10_25_041952_add_employee_checkbox',11);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_11_12_062409_add_onetime_order_columns',12);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_11_19_065448_add_suspended_schedule',12);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_11_23_182015_suspensionsemantics',12);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_01_07_074559_add_no_beg',13);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_01_17_181351_create_classes_table',14);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_01_17_183903_create_expenses_table',14);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_02_13_062859_classbucketname',15);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_02_28_025950_reify_dependent_cutoff_dates',16);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_03_02_063820_populate_dependent_cutoff_dates',16);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_04_26_213922_create_pointsales_table',17);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_05_23_194505_create_users_classes',18);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_05_30_125303_splits_on_classes',18);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_06_11_043813_add_order_column_to_classes',18);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_06_11_060437_drop_denormalized_class_columns',18);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2015_08_23_194747_add_users_activation_code',18);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_10_12_100000_create_password_reset_tokens_table',19);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2014_10_12_200000_add_two_factor_columns_and_remember_to_users_table',20);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2019_08_19_000000_create_failed_jobs_table',20);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2019_12_14_000001_create_personal_access_tokens_table',20);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_02_26_185148_create_sessions_table',20);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_03_26_105337_add_isadmin_to_users',21);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_03_26_065754_0001_01_01_000001_create_cache_table',22);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_03_26_070103_0001_01_01_000002_create_jobs_table',23);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_04_20_064630_add_user_nobeg_default',24);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_04_23_002712_emailverifiedat',25);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_10_27_061931_add_current_to_classes',26);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_10_27_070145_drop_classes_users_relation',27);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_11_02_132312_remove_obsolete_columns_from_user',28);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_11_02_133657_drop_schedule_columns_from_users',29);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_11_02_161217_drop_obsolete_class_columns_from_orders',30);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_11_02_163632_add_enrollment_to_classes',31);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_11_02_171924_remove_more_columns_from_users',32);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_11_06_121912_increase_size_of_expenses',32);
INSERT INTO `migrations` (`migration`, `batch`) VALUES ('2024_11_11_094926_remove_stripe_active_from_users',33);
