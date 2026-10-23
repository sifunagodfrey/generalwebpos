-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 07:07 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `generalpos`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_01_01_000001_create_su_settings_table', 1),
(2, '2025_01_01_000002_create_su_roles_table', 1),
(3, '2025_01_01_000003_create_su_permissions_table', 1),
(4, '2025_01_01_000004_create_su_users_table', 1),
(5, '2025_01_01_000005_create_su_role_permissions_table', 1),
(6, '2025_01_01_000006_create_su_user_profiles_table', 1),
(7, '2025_01_01_000007_create_su_password_reset_tokens_table', 1),
(8, '2025_01_01_000008_create_su_voice_provider_credentials_table', 1),
(9, '2025_01_01_000009_create_su_ivr_menus_table', 1),
(10, '2025_01_01_000010_create_su_queues_table', 1),
(11, '2025_01_01_000011_create_su_ivr_options_table', 1),
(12, '2025_01_01_000012_create_su_contacts_table', 1),
(13, '2025_01_01_000013_create_su_queue_agents_table', 1),
(14, '2025_01_01_000014_create_su_calls_table', 1),
(15, '2025_01_01_000015_create_su_call_events_table', 1),
(16, '2025_01_01_000016_create_su_call_recordings_table', 1),
(17, '2025_01_01_000017_create_su_call_notes_table', 1),
(18, '2025_01_01_000018_create_su_agent_status_logs_table', 1),
(19, '2025_01_01_000019_create_su_agent_daily_stats_table', 1),
(20, '2025_01_01_000020_create_su_csat_ratings_table', 1),
(21, '2025_01_01_000021_create_su_business_hours_table', 1),
(22, '2025_01_01_000022_create_su_hold_media_table', 1),
(23, '2025_01_01_000023_create_su_audit_logs_table', 1),
(24, '2025_01_01_000023_create_su_system_logs_table', 1),
(25, '2025_01_01_000026_create_su_user_roles_table', 1),
(26, '2025_01_01_000027_create_su_email_settings_table', 1),
(27, '2025_01_01_000028_create_su_media_table', 1),
(28, '2025_01_01_000029_create_su_notifications_table', 1),
(29, '2025_02_01_000001_add_provider_session_id_to_su_calls_table', 2),
(30, '2025_02_01_000002_add_current_ivr_menu_id_to_su_calls_table', 3),
(31, '2025_02_01_000003_add_greeting_text_to_su_ivr_menus_table', 4),
(32, '2025_02_01_000004_add_tried_agent_ids_to_su_calls_table', 4),
(33, '2025_02_01_000005_add_direction_to_su_calls_table', 5),
(34, '2025_02_01_000006_add_softphone_client_name_to_su_users_table', 6),
(35, '2025_02_01_000007_add_follow_up_agent_id_to_su_call_notes_table', 7),
(36, '2025_02_01_000008_add_normalized_phone_to_su_contacts_table', 8),
(37, '2025_02_01_000009_add_last_seen_at_to_su_users_table', 9),
(38, '2025_02_01_000011_add_ivr_rating_support', 10),
(39, '2025_02_01_000010_add_ivr_path_to_su_calls_table', 11),
(40, '2025_02_01_000012_add_purpose_to_su_calls_table', 12),
(41, '2026_10_17_120001_remove_callcenter_reporting_tables', 13),
(42, '2026_10_17_120002_remove_callcenter_call_detail_tables', 13),
(43, '2026_10_17_120003_remove_callcenter_routing_tables', 13),
(44, '2026_10_17_120004_remove_callcenter_telephony_settings_tables', 13),
(45, '2026_10_18_10001_create_catalog_pos_tables', 14),
(46, '2026_10_18_10002_create_people_pos_tables', 14),
(47, '2026_10_18_10003_create_shifts_pos_tables', 14),
(48, '2026_10_18_10004_create_sales_pos_tables', 14),
(49, '2026_10_18_10005_create_purchasing_pos_tables', 14),
(50, '2026_10_18_10006_create_documents_pos_tables', 14),
(51, '2026_10_18_10007_create_operations_pos_tables', 14),
(52, '2026_10_18_10008_create_cloud_and_ledgers_pos_tables', 14),
(53, '2026_10_18_10009_create_pos_cloud_backup_tables', 14),
(54, '2026_10_18_10100_add_pos_foreign_keys', 14);

-- --------------------------------------------------------

--
-- Table structure for table `su_audit_log`
--

CREATE TABLE `su_audit_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` text NOT NULL,
  `target_type` text DEFAULT NULL,
  `target_id` bigint(20) UNSIGNED DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_audit_logs`
--

CREATE TABLE `su_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `record_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_backorders`
--

CREATE TABLE `su_backorders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_pending` decimal(18,4) NOT NULL,
  `fulfilled_from_batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fulfilled_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_backups`
--

CREATE TABLE `su_backups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_path` text NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `size_bytes` int(11) NOT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_barcode_labels`
--

CREATE TABLE `su_barcode_labels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_assigned` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_barcode_labels`
--

INSERT INTO `su_barcode_labels` (`id`, `code`, `product_id`, `variant_id`, `is_assigned`, `created_at`, `business_id`, `branch_id`) VALUES
(1, 'DEMO-GEN-0001', 1, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(2, 'DEMO-GEN-0002', 2, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(3, 'DEMO-GEN-0003', 3, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(4, 'DEMO-GEN-0004', 4, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(5, 'DEMO-GEN-0005', 5, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(6, 'DEMO-GEN-0006', 6, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(7, 'DEMO-GEN-0007', 7, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(8, 'DEMO-GEN-0008', 8, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(9, 'DEMO-GEN-0009', 9, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(10, 'DEMO-GEN-0010', 10, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(11, 'DEMO-GEN-0011', 11, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(12, 'DEMO-GEN-0012', 12, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(13, 'DEMO-GEN-0013', 13, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(14, 'DEMO-GEN-0014', 14, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(15, 'DEMO-GEN-0015', 15, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(16, 'DEMO-GEN-0016', 16, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(17, 'DEMO-GEN-0017', 17, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(18, 'DEMO-GEN-0018', 18, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(19, 'DEMO-GEN-0019', 19, NULL, 1, '2026-10-17 11:57:12', NULL, NULL),
(20, 'DEMO-GEN-0020', 20, NULL, 1, '2026-10-17 11:57:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_branches`
--

CREATE TABLE `su_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `business_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_brands`
--

CREATE TABLE `su_brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_brands`
--

INSERT INTO `su_brands` (`id`, `name`, `description`, `image_path`, `created_at`, `business_id`, `branch_id`) VALUES
(1, 'Generic', NULL, NULL, '2026-10-17 11:57:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_businesses`
--

CREATE TABLE `su_businesses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'KES',
  `timezone` varchar(255) NOT NULL DEFAULT 'Africa/Nairobi',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_cash_box`
--

CREATE TABLE `su_cash_box` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `direction` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_cash_box_categories`
--

CREATE TABLE `su_cash_box_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `direction` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_categories`
--

CREATE TABLE `su_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` text DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_categories`
--

INSERT INTO `su_categories` (`id`, `name`, `description`, `image_path`, `parent_id`, `created_at`, `business_id`, `branch_id`) VALUES
(1, 'Food Staples', 'Sample category for demo products.', NULL, NULL, '2026-10-17 11:57:12', NULL, NULL),
(2, 'Cooking Essentials', 'Sample category for demo products.', NULL, NULL, '2026-10-17 11:57:12', NULL, NULL),
(3, 'Dairy & Bakery', 'Sample category for demo products.', NULL, NULL, '2026-10-17 11:57:12', NULL, NULL),
(4, 'Beverages', 'Sample category for demo products.', NULL, NULL, '2026-10-17 11:57:12', NULL, NULL),
(5, 'Household', 'Sample category for demo products.', NULL, NULL, '2026-10-17 11:57:12', NULL, NULL),
(6, 'Personal Care', 'Sample category for demo products.', NULL, NULL, '2026-10-17 11:57:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_credit_notes`
--

CREATE TABLE `su_credit_notes` (
  `return_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `restocked` tinyint(1) NOT NULL,
  `unpaid_reduction` decimal(18,2) NOT NULL DEFAULT 0.00,
  `credit_reversal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `cash_refund` decimal(18,2) NOT NULL DEFAULT 0.00,
  `mobile_refund` decimal(18,2) NOT NULL DEFAULT 0.00,
  `card_refund` decimal(18,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_credit_transactions`
--

CREATE TABLE `su_credit_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `note` text DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_customers`
--

CREATE TABLE `su_customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `credit_balance` decimal(18,2) NOT NULL DEFAULT 0.00,
  `credit_limit` decimal(18,2) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_delivery_notes`
--

CREATE TABLE `su_delivery_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL DEFAULT 'issued',
  `cancel_reason` text DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_discounts_promotions`
--

CREATE TABLE `su_discounts_promotions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_percent` decimal(18,2) DEFAULT NULL,
  `discount_amount` decimal(18,2) DEFAULT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_documents`
--

CREATE TABLE `su_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `tax_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `source_document_id` bigint(20) UNSIGNED DEFAULT NULL,
  `converted_to_sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `converted_at` datetime DEFAULT NULL,
  `void_reason` text DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_document_items`
--

CREATE TABLE `su_document_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` decimal(18,4) NOT NULL,
  `unit_price` decimal(18,2) NOT NULL,
  `tax_type` varchar(255) NOT NULL DEFAULT 'standard_rated',
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_email_settings`
--

CREATE TABLE `su_email_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `driver` varchar(255) NOT NULL DEFAULT 'smtp',
  `host` varchar(255) DEFAULT NULL,
  `port` int(10) UNSIGNED DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `encryption` varchar(255) DEFAULT NULL,
  `from_address` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_email_settings`
--

INSERT INTO `su_email_settings` (`id`, `driver`, `host`, `port`, `username`, `password`, `encryption`, `from_address`, `from_name`, `is_active`) VALUES
(1, 'smtp', NULL, 587, NULL, NULL, 'tls', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `su_license_state`
--

CREATE TABLE `su_license_state` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `license_key` varchar(255) NOT NULL,
  `pos_app_code` varchar(255) NOT NULL DEFAULT 'general_shop',
  `device_fingerprint` text NOT NULL DEFAULT '',
  `seats` int(11) NOT NULL DEFAULT 1,
  `registrant_name` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `lease_payload` text DEFAULT NULL,
  `lease_signature` text DEFAULT NULL,
  `lease_key_id` text DEFAULT NULL,
  `last_verified_at` datetime DEFAULT NULL,
  `restricted_reason` text DEFAULT NULL,
  `support_url` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_media`
--

CREATE TABLE `su_media` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `disk` varchar(255) NOT NULL DEFAULT 'public',
  `folder` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_media`
--

INSERT INTO `su_media` (`id`, `file_name`, `original_name`, `file_path`, `disk`, `folder`, `mime_type`, `extension`, `file_size`, `width`, `height`, `alt_text`, `title`, `uploaded_by`, `created_at`, `updated_at`) VALUES
(1, 'agent-performance-2026-08-27-to-2026-09-02-sacwkx.csv', 'agent-performance-2026-08-27-to-2026-09-02.csv', 'uploads/products/agent-performance-2026-08-27-to-2026-09-02-sacwkx.csv', 'public', 'products', 'text/csv', 'csv', 199, NULL, NULL, NULL, NULL, 1, '2026-09-02 09:29:18', '2026-09-02 09:29:18');

-- --------------------------------------------------------

--
-- Table structure for table `su_missing_stock_requests`
--

CREATE TABLE `su_missing_stock_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` text NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_by_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_mpesa_applications`
--

CREATE TABLE `su_mpesa_applications` (
  `remote_id` varchar(255) NOT NULL,
  `reservation_key` varchar(255) NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `synced` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_mpesa_attempts`
--

CREATE TABLE `su_mpesa_attempts` (
  `checkout_key` varchar(255) NOT NULL,
  `request_key` varchar(255) NOT NULL,
  `remote_id` varchar(255) DEFAULT NULL,
  `payload` longtext NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_mpesa_receipts`
--

CREATE TABLE `su_mpesa_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `remote_id` varchar(255) NOT NULL,
  `merchant_id` varchar(255) NOT NULL,
  `environment` varchar(30) NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `amount_cents` bigint(20) UNSIGNED NOT NULL,
  `payload` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_network_checkouts`
--

CREATE TABLE `su_network_checkouts` (
  `device_id` varchar(255) NOT NULL,
  `checkout_key` varchar(255) NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `basket` longtext NOT NULL,
  `quote` longtext NOT NULL,
  `response` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_network_devices`
--

CREATE TABLE `su_network_devices` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `paired_at` datetime DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_network_shifts`
--

CREATE TABLE `su_network_shifts` (
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_notifications`
--

CREATE TABLE `su_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `audience` varchar(255) NOT NULL DEFAULT 'admin',
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_password_reset_tokens`
--

CREATE TABLE `su_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_payment_methods`
--

CREATE TABLE `su_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_payment_methods`
--

INSERT INTO `su_payment_methods` (`id`, `sale_id`, `method`, `amount`, `reference`, `staff_id`, `shift_id`, `created_at`, `business_id`, `branch_id`) VALUES
(1, 1, 'cash', 1230.00, NULL, 1, 1, '2026-10-19 12:49:11', NULL, NULL),
(2, 2, 'cash', 1110.00, NULL, 1, 1, '2026-10-19 14:06:04', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_permissions`
--

CREATE TABLE `su_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_permissions`
--

INSERT INTO `su_permissions` (`id`, `name`, `slug`, `module`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Manage staff, roles and permissions', 'users.manage', 'system', 'Create, edit and deactivate staff accounts, and manage roles and permissions.', NULL, NULL),
(2, 'View audit log', 'view_system_logs', 'system', 'View the system wide audit trail.', NULL, NULL),
(3, 'Manage settings', 'manage_settings', 'settings', 'Change company details, branding, email and security settings.', NULL, NULL),
(4, 'Manage voice provider', 'voice_provider.manage', 'settings', 'Configure the voice provider connection.', NULL, NULL),
(5, 'Manage media library', 'media', 'media', 'Upload and manage files in the media library.', NULL, NULL),
(6, 'View live wallboard', 'wallboard.view', 'calls', 'See live agent status and queue activity.', NULL, NULL),
(7, 'View call recordings', 'recordings.view', 'calls', 'Listen to and download recorded calls.', NULL, NULL),
(8, 'View reports', 'reports.view', 'calls', 'View agent performance and call reports.', NULL, NULL),
(9, 'Manage queues', 'queues.manage', 'calls', 'Create and edit call queues.', NULL, NULL),
(10, 'Manage IVR menus', 'ivr.manage', 'calls', 'Build and edit IVR menu flows.', NULL, NULL),
(11, 'Manage contacts', 'contacts.manage', 'calls', 'Add and edit saved contacts.', NULL, NULL),
(80, 'POS: View Dashboard', 'pos.dashboard.view', 'dashboard', 'POS access: view dashboard.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(81, 'POS: View Products', 'pos.products.view', 'products', 'POS access: view products.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(82, 'POS: Create Products', 'pos.products.create', 'products', 'POS access: create products.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(83, 'POS: Update Products', 'pos.products.update', 'products', 'POS access: update products.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(84, 'POS: Delete Products', 'pos.products.delete', 'products', 'POS access: delete products.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(85, 'POS: Import Products', 'pos.products.import', 'products', 'POS access: import products.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(86, 'POS: Export Products', 'pos.products.export', 'products', 'POS access: export products.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(87, 'POS: Manage Prices Products', 'pos.products.manage_prices', 'products', 'POS access: manage_prices products.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(88, 'POS: View Categories', 'pos.categories.view', 'categories', 'POS access: view categories.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(89, 'POS: Manage Categories', 'pos.categories.manage', 'categories', 'POS access: manage categories.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(90, 'POS: View Brands', 'pos.brands.view', 'brands', 'POS access: view brands.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(91, 'POS: Manage Brands', 'pos.brands.manage', 'brands', 'POS access: manage brands.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(92, 'POS: View Inventory', 'pos.inventory.view', 'inventory', 'POS access: view inventory.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(93, 'POS: Receive Inventory', 'pos.inventory.receive', 'inventory', 'POS access: receive inventory.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(94, 'POS: Adjust Inventory', 'pos.inventory.adjust', 'inventory', 'POS access: adjust inventory.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(95, 'POS: Count Inventory', 'pos.inventory.count', 'inventory', 'POS access: count inventory.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(96, 'POS: Transfer Inventory', 'pos.inventory.transfer', 'inventory', 'POS access: transfer inventory.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(97, 'POS: View Costs Inventory', 'pos.inventory.view_costs', 'inventory', 'POS access: view_costs inventory.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(98, 'POS: View Sales', 'pos.sales.view', 'sales', 'POS access: view sales.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(99, 'POS: Create Sales', 'pos.sales.create', 'sales', 'POS access: create sales.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(100, 'POS: Hold Sales', 'pos.sales.hold', 'sales', 'POS access: hold sales.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(101, 'POS: Discount Sales', 'pos.sales.discount', 'sales', 'POS access: discount sales.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(102, 'POS: Void Sales', 'pos.sales.void', 'sales', 'POS access: void sales.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(103, 'POS: Refund Sales', 'pos.sales.refund', 'sales', 'POS access: refund sales.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(104, 'POS: Reprint Sales', 'pos.sales.reprint', 'sales', 'POS access: reprint sales.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(105, 'POS: View Payments', 'pos.payments.view', 'payments', 'POS access: view payments.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(106, 'POS: Receive Payments', 'pos.payments.receive', 'payments', 'POS access: receive payments.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(107, 'POS: Refund Payments', 'pos.payments.refund', 'payments', 'POS access: refund payments.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(108, 'POS: View Customers', 'pos.customers.view', 'customers', 'POS access: view customers.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(109, 'POS: Manage Customers', 'pos.customers.manage', 'customers', 'POS access: manage customers.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(110, 'POS: Credit Customers', 'pos.customers.credit', 'customers', 'POS access: credit customers.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(111, 'POS: View Suppliers', 'pos.suppliers.view', 'suppliers', 'POS access: view suppliers.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(112, 'POS: Manage Suppliers', 'pos.suppliers.manage', 'suppliers', 'POS access: manage suppliers.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(113, 'POS: Settle Suppliers', 'pos.suppliers.settle', 'suppliers', 'POS access: settle suppliers.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(114, 'POS: View Purchases', 'pos.purchases.view', 'purchases', 'POS access: view purchases.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(115, 'POS: Create Purchases', 'pos.purchases.create', 'purchases', 'POS access: create purchases.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(116, 'POS: Approve Purchases', 'pos.purchases.approve', 'purchases', 'POS access: approve purchases.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(117, 'POS: Receive Purchases', 'pos.purchases.receive', 'purchases', 'POS access: receive purchases.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(118, 'POS: Cancel Purchases', 'pos.purchases.cancel', 'purchases', 'POS access: cancel purchases.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(119, 'POS: View Documents', 'pos.documents.view', 'documents', 'POS access: view documents.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(120, 'POS: Create Documents', 'pos.documents.create', 'documents', 'POS access: create documents.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(121, 'POS: Update Documents', 'pos.documents.update', 'documents', 'POS access: update documents.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(122, 'POS: Convert Documents', 'pos.documents.convert', 'documents', 'POS access: convert documents.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(123, 'POS: Export Documents', 'pos.documents.export', 'documents', 'POS access: export documents.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(124, 'POS: View Shifts', 'pos.shifts.view', 'shifts', 'POS access: view shifts.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(125, 'POS: Open Shifts', 'pos.shifts.open', 'shifts', 'POS access: open shifts.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(126, 'POS: Close Shifts', 'pos.shifts.close', 'shifts', 'POS access: close shifts.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(127, 'POS: Review Shifts', 'pos.shifts.review', 'shifts', 'POS access: review shifts.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(128, 'POS: View Cashbox', 'pos.cashbox.view', 'cashbox', 'POS access: view cashbox.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(129, 'POS: Manage Cashbox', 'pos.cashbox.manage', 'cashbox', 'POS access: manage cashbox.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(130, 'POS: Approve Cashbox', 'pos.cashbox.approve', 'cashbox', 'POS access: approve cashbox.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(131, 'POS: View Mpesa', 'pos.mpesa.view', 'mpesa', 'POS access: view mpesa.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(132, 'POS: Initiate Mpesa', 'pos.mpesa.initiate', 'mpesa', 'POS access: initiate mpesa.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(133, 'POS: Reconcile Mpesa', 'pos.mpesa.reconcile', 'mpesa', 'POS access: reconcile mpesa.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(134, 'POS: Configure Mpesa', 'pos.mpesa.configure', 'mpesa', 'POS access: configure mpesa.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(135, 'POS: View Reports', 'pos.reports.view', 'reports', 'POS access: view reports.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(136, 'POS: Sales Reports', 'pos.reports.sales', 'reports', 'POS access: sales reports.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(137, 'POS: Inventory Reports', 'pos.reports.inventory', 'reports', 'POS access: inventory reports.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(138, 'POS: Financial Reports', 'pos.reports.financial', 'reports', 'POS access: financial reports.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(139, 'POS: Export Reports', 'pos.reports.export', 'reports', 'POS access: export reports.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(140, 'POS: View Staff', 'pos.staff.view', 'staff', 'POS access: view staff.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(141, 'POS: Manage Staff', 'pos.staff.manage', 'staff', 'POS access: manage staff.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(142, 'POS: Manage Roles Staff', 'pos.staff.manage_roles', 'staff', 'POS access: manage_roles staff.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(143, 'POS: View Businesses', 'pos.businesses.view', 'businesses', 'POS access: view businesses.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(144, 'POS: Manage Businesses', 'pos.businesses.manage', 'businesses', 'POS access: manage businesses.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(145, 'POS: View Branches', 'pos.branches.view', 'branches', 'POS access: view branches.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(146, 'POS: Manage Branches', 'pos.branches.manage', 'branches', 'POS access: manage branches.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(147, 'POS: View Settings', 'pos.settings.view', 'settings', 'POS access: view settings.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(148, 'POS: Manage Settings', 'pos.settings.manage', 'settings', 'POS access: manage settings.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(149, 'POS: View Audit', 'pos.audit.view', 'audit', 'POS access: view audit.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(150, 'POS: View Backups', 'pos.backups.view', 'backups', 'POS access: view backups.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(151, 'POS: Create Backups', 'pos.backups.create', 'backups', 'POS access: create backups.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(152, 'POS: Restore Backups', 'pos.backups.restore', 'backups', 'POS access: restore backups.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(153, 'POS: View Licensing', 'pos.licensing.view', 'licensing', 'POS access: view licensing.', '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(154, 'POS: Manage Licensing', 'pos.licensing.manage', 'licensing', 'POS access: manage licensing.', '2026-10-17 08:57:12', '2026-10-17 08:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `su_pos_cloud_backups`
--

CREATE TABLE `su_pos_cloud_backups` (
  `id` char(36) NOT NULL,
  `license_id` bigint(20) UNSIGNED NOT NULL,
  `edition` varchar(32) NOT NULL,
  `digest` varchar(64) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `email_status` varchar(255) NOT NULL DEFAULT 'not_requested',
  `created_at` timestamp(6) NULL DEFAULT NULL,
  `updated_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_pos_cloud_backup_settings`
--

CREATE TABLE `su_pos_cloud_backup_settings` (
  `license_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp(6) NULL DEFAULT NULL,
  `updated_at` timestamp(6) NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_products`
--

CREATE TABLE `su_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `unit_of_measure_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cost_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_price` decimal(18,2) DEFAULT NULL,
  `tax_type` varchar(255) NOT NULL DEFAULT 'standard_rated',
  `description` text DEFAULT NULL,
  `has_variants` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_products`
--

INSERT INTO `su_products` (`id`, `name`, `category_id`, `brand_id`, `unit_of_measure_id`, `cost_price`, `selling_price`, `discount_price`, `tax_type`, `description`, `has_variants`, `is_active`, `created_at`, `updated_at`, `business_id`, `branch_id`) VALUES
(1, 'Maize Flour 2 kg', 1, 1, 1, 140.00, 190.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(2, 'Wheat Flour 2 kg', 1, 1, 1, 145.00, 200.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(3, 'White Rice 1 kg', 1, 1, 1, 170.00, 230.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(4, 'Sugar 1 kg', 1, 1, 1, 125.00, 175.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(5, 'Dry Beans 1 kg', 1, 1, 1, 150.00, 210.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(6, 'Cooking Oil 1 L', 2, 1, 2, 240.00, 315.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(7, 'Table Salt 1 kg', 2, 1, 1, 35.00, 55.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(8, 'Tea Leaves 250 g', 2, 1, 3, 135.00, 185.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(9, 'UHT Milk 500 ml', 3, 1, 2, 48.00, 70.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(10, 'White Bread 400 g', 3, 1, 3, 55.00, 75.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(11, 'Eggs Tray 30 pcs', 3, 1, 3, 390.00, 480.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(12, 'Bottled Water 500 ml', 4, 1, 3, 20.00, 35.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(13, 'Orange Soda 500 ml', 4, 1, 3, 45.00, 65.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(14, 'Mango Juice 1 L', 4, 1, 3, 150.00, 210.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(15, 'Laundry Bar Soap 800 g', 5, 1, 3, 95.00, 135.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(16, 'Dishwashing Liquid 500 ml', 5, 1, 3, 100.00, 150.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(17, 'Toilet Tissue 4 Pack', 5, 1, 3, 125.00, 175.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(18, 'Toothpaste 100 ml', 6, 1, 3, 100.00, 150.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(19, 'Bath Soap 175 g', 6, 1, 3, 65.00, 95.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL),
(20, 'Shampoo 250 ml', 6, 1, 3, 170.00, 245.00, NULL, 'standard_rated', 'Demo item; edit before real trading.', 0, 1, '2026-10-17 11:57:12', '2026-10-17 11:57:12', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_product_images`
--

CREATE TABLE `su_product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_path` text NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_product_variants`
--

CREATE TABLE `su_product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit_of_measure_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cost_price` decimal(18,2) DEFAULT NULL,
  `selling_price` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_price` decimal(18,2) DEFAULT NULL,
  `stock_quantity` decimal(18,4) NOT NULL DEFAULT 0.0000,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_purchase_orders`
--

CREATE TABLE `su_purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `is_auto_generated` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `received_at` datetime DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_purchase_order_items`
--

CREATE TABLE `su_purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity_ordered` decimal(18,4) NOT NULL,
  `quantity_received` decimal(18,4) NOT NULL DEFAULT 0.0000,
  `unit_cost` decimal(18,2) NOT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_receipts`
--

CREATE TABLE `su_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `running_balance` decimal(18,2) NOT NULL,
  `clears_balance` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_returns_refunds`
--

CREATE TABLE `su_returns_refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(18,4) NOT NULL,
  `reason` text NOT NULL,
  `amount_refunded` decimal(18,2) NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_roles`
--

CREATE TABLE `su_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_roles`
--

INSERT INTO `su_roles` (`id`, `name`, `slug`, `description`, `is_system`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super_admin', 'Full access. Manages users, roles, permissions, IVR menus, queues, voice provider credentials, branding and system settings.', 1, '2026-08-25 02:54:09', '2026-08-25 02:54:09'),
(2, 'Supervisor', 'supervisor', 'Sees the live wallboard and can view call recordings, reports and team performance.', 1, '2026-08-25 02:54:09', '2026-08-25 02:54:09'),
(3, 'Agent', 'agent', 'Handles calls, manages their own contacts, and logs call notes and follow ups.', 1, '2026-08-25 02:54:09', '2026-08-25 02:54:09'),
(4, 'POS Owner', 'pos_owner', 'Owner of the business; all POS permissions.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 'POS Manager', 'pos_manager', 'Manages store operations, stock, purchasing, and staff oversight.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 'POS Supervisor', 'pos_supervisor', 'Supervises daily checkout, inventory and shifts.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 'POS Cashier', 'pos_cashier', 'Processes sales, receives payments and operates shifts.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 'POS Inventory Clerk', 'pos_inventory_clerk', 'Maintains product catalog and stock records.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 'POS Purchasing Officer', 'pos_purchasing_officer', 'Maintains suppliers and purchasing documents.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 'POS Accountant', 'pos_accountant', 'Oversees finances, reports, reconciliations and audit trails.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 'POS Auditor', 'pos_auditor', 'Read-only operational and financial visibility.', 1, '2026-10-17 08:57:12', '2026-10-17 08:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `su_role_permissions`
--

CREATE TABLE `su_role_permissions` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_role_permissions`
--

INSERT INTO `su_role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL),
(1, 2, NULL, NULL),
(1, 3, NULL, NULL),
(1, 4, NULL, NULL),
(1, 5, NULL, NULL),
(1, 6, NULL, NULL),
(1, 7, NULL, NULL),
(1, 8, NULL, NULL),
(1, 9, NULL, NULL),
(1, 10, NULL, NULL),
(1, 11, NULL, NULL),
(1, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 82, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 83, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 84, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 85, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 86, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 87, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 88, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 89, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 90, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 91, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 93, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 94, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 95, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 96, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 97, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 98, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 99, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 100, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 101, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 102, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 103, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 104, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 105, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 106, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 107, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 108, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 109, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 110, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 112, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 113, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 115, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 116, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 117, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 118, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 119, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 120, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 121, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 122, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 123, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 124, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 125, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 126, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 127, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 128, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 129, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 130, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 131, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 132, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 133, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 134, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 135, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 136, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 137, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 138, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 139, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 140, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 141, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 142, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 143, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 144, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 145, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 146, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 147, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 148, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 149, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 150, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 151, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 152, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 153, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(1, 154, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(2, 6, NULL, NULL),
(2, 7, NULL, NULL),
(2, 8, NULL, NULL),
(4, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 82, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 83, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 84, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 85, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 86, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 87, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 88, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 89, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 90, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 91, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 93, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 94, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 95, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 96, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 97, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 98, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 99, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 100, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 101, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 102, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 103, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 104, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 105, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 106, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 107, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 108, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 109, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 110, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 112, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 113, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 115, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 116, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 117, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 118, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 119, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 120, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 121, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 122, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 123, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 124, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 125, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 126, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 127, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 128, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 129, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 130, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 131, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 132, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 133, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 134, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 135, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 136, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 137, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 138, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 139, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 140, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 141, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 142, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 143, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 144, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 145, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 146, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 147, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 148, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 149, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 150, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 151, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 152, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 153, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 154, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 82, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 83, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 84, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 85, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 86, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 87, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 88, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 89, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 90, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 91, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 93, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 94, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 95, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 96, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 97, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 98, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 99, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 100, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 101, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 102, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 103, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 104, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 105, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 106, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 107, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 108, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 109, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 110, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 112, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 113, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 115, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 116, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 117, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 118, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 119, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 120, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 121, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 122, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 123, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 124, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 125, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 126, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 127, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 128, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 129, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 130, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 131, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 132, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 133, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 135, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 136, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 137, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 138, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 139, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 140, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 145, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 147, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 149, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 150, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 88, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 90, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 93, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 95, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 98, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 99, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 100, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 101, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 103, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 104, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 105, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 106, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 108, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 109, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 119, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 124, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 125, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 126, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 128, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 131, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 135, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 136, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 140, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 88, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 98, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 99, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 100, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 104, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 105, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 106, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 108, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 109, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 124, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 125, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 126, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 131, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(7, 132, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 82, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 83, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 88, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 90, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 93, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 95, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 117, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 93, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 112, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 115, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 117, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 119, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 97, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 98, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 105, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 108, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 110, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 113, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 119, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 123, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 124, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 127, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 128, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 130, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 131, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 133, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 135, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 136, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 137, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 138, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 139, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 149, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 80, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 81, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 92, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 98, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 105, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 108, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 111, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 114, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 119, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 124, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 128, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 131, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 135, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 136, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 137, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 138, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 139, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 149, '2026-10-17 08:57:12', '2026-10-17 08:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `su_sales`
--

CREATE TABLE `su_sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `hold_label` varchar(255) DEFAULT NULL,
  `subtotal` decimal(18,2) NOT NULL DEFAULT 0.00,
  `discount_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `tax_total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `total` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `held_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `void_reason` text DEFAULT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_sales`
--

INSERT INTO `su_sales` (`id`, `staff_id`, `shift_id`, `customer_id`, `status`, `hold_label`, `subtotal`, `discount_total`, `tax_total`, `total`, `created_at`, `held_at`, `completed_at`, `void_reason`, `business_id`, `branch_id`) VALUES
(1, 1, 1, NULL, 'completed', NULL, 1230.00, 0.00, 0.00, 1230.00, '2026-10-19 12:36:05', NULL, '2026-10-19 12:49:11', NULL, NULL, NULL),
(2, 1, 1, NULL, 'completed', NULL, 1110.00, 0.00, 0.00, 1110.00, '2026-10-19 14:06:04', NULL, '2026-10-19 14:06:04', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_sale_items`
--

CREATE TABLE `su_sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(18,4) NOT NULL,
  `unit_price` decimal(18,2) NOT NULL,
  `discount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(18,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(18,2) NOT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_sale_items`
--

INSERT INTO `su_sale_items` (`id`, `sale_id`, `product_id`, `variant_id`, `batch_id`, `quantity`, `unit_price`, `discount`, `tax_amount`, `line_total`, `business_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(4, 1, 16, NULL, NULL, 5.0000, 150.00, 0.00, 0.00, 750.00, NULL, NULL, '2026-10-19 09:49:11', '2026-10-19 09:49:11'),
(5, 1, 12, NULL, NULL, 2.0000, 35.00, 0.00, 0.00, 70.00, NULL, NULL, '2026-10-19 09:49:11', '2026-10-19 09:49:11'),
(6, 1, 19, NULL, NULL, 1.0000, 95.00, 0.00, 0.00, 95.00, NULL, NULL, '2026-10-19 09:49:11', '2026-10-19 09:49:11'),
(7, 1, 6, NULL, NULL, 1.0000, 315.00, 0.00, 0.00, 315.00, NULL, NULL, '2026-10-19 09:49:11', '2026-10-19 09:49:11'),
(8, 2, 19, NULL, NULL, 8.0000, 95.00, 0.00, 0.00, 760.00, NULL, NULL, '2026-10-19 11:06:04', '2026-10-19 11:06:04'),
(9, 2, 12, NULL, NULL, 1.0000, 35.00, 0.00, 0.00, 35.00, NULL, NULL, '2026-10-19 11:06:04', '2026-10-19 11:06:04'),
(10, 2, 6, NULL, NULL, 1.0000, 315.00, 0.00, 0.00, 315.00, NULL, NULL, '2026-10-19 11:06:04', '2026-10-19 11:06:04');

-- --------------------------------------------------------

--
-- Table structure for table `su_sale_payment_allocations`
--

CREATE TABLE `su_sale_payment_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_sale_payment_allocations`
--

INSERT INTO `su_sale_payment_allocations` (`id`, `sale_id`, `payment_method_id`, `amount`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 1110.00, '2026-10-19 11:06:04', '2026-10-19 11:06:04');

-- --------------------------------------------------------

--
-- Table structure for table `su_settings`
--

CREATE TABLE `su_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `setting_group` varchar(255) NOT NULL DEFAULT 'general',
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `field_type` varchar(255) NOT NULL DEFAULT 'text',
  `description` varchar(255) DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_settings`
--

INSERT INTO `su_settings` (`id`, `setting_group`, `setting_key`, `setting_value`, `field_type`, `description`, `is_public`, `sort_order`) VALUES
(1, 'general', 'csat_ivr_enabled', '1', 'text', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `su_shifts`
--

CREATE TABLE `su_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `opening_cash` decimal(18,2) NOT NULL,
  `opening_mobile_money` decimal(18,2) NOT NULL,
  `closing_cash_counted` decimal(18,2) DEFAULT NULL,
  `closing_mobile_money_counted` decimal(18,2) DEFAULT NULL,
  `expected_cash` decimal(18,2) DEFAULT NULL,
  `expected_mobile_money` decimal(18,2) DEFAULT NULL,
  `cash_variance` decimal(18,2) DEFAULT NULL,
  `mobile_money_variance` decimal(18,2) DEFAULT NULL,
  `opened_at` datetime NOT NULL DEFAULT current_timestamp(),
  `closed_at` datetime DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_shifts`
--

INSERT INTO `su_shifts` (`id`, `staff_id`, `opening_cash`, `opening_mobile_money`, `closing_cash_counted`, `closing_mobile_money_counted`, `expected_cash`, `expected_mobile_money`, `cash_variance`, `mobile_money_variance`, `opened_at`, `closed_at`, `is_closed`, `business_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-19 12:49:11', NULL, 0, NULL, NULL, '2026-10-19 09:49:11', '2026-10-19 09:49:11');

-- --------------------------------------------------------

--
-- Table structure for table `su_staff`
--

CREATE TABLE `su_staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'cashier',
  `pin_hash` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_staff`
--

INSERT INTO `su_staff` (`id`, `first_name`, `last_name`, `role`, `pin_hash`, `is_active`, `created_at`, `user_id`) VALUES
(1, 'Sifuna', 'Godfrey', 'cashier', '$2y$12$Nh0JqDtfmOb7SPrsgnPMEO0lHOeEShZ4szBJO3dXMIxkFUysb80lO', 1, '2026-10-19 11:02:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `su_staff_leave`
--

CREATE TABLE `su_staff_leave` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `reason` text DEFAULT NULL,
  `logged_by_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_stock_adjustments`
--

CREATE TABLE `su_stock_adjustments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recorded_quantity` decimal(18,4) NOT NULL,
  `counted_quantity` decimal(18,4) NOT NULL,
  `discrepancy` decimal(18,4) NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_stock_batches`
--

CREATE TABLE `su_stock_batches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` decimal(18,4) NOT NULL,
  `expiry_date` datetime DEFAULT NULL,
  `cost_price` decimal(18,2) DEFAULT NULL,
  `received_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_stock_batches`
--

INSERT INTO `su_stock_batches` (`id`, `product_id`, `variant_id`, `quantity`, `expiry_date`, `cost_price`, `received_at`, `business_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 45.0000, NULL, 140.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(2, 2, NULL, 35.0000, NULL, 145.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(3, 3, NULL, 60.0000, NULL, 170.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(4, 4, NULL, 55.0000, NULL, 125.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(5, 5, NULL, 32.0000, NULL, 150.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(6, 6, NULL, 26.0000, NULL, 240.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-19 11:06:04'),
(7, 7, NULL, 75.0000, NULL, 35.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(8, 8, NULL, 38.0000, NULL, 135.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(9, 9, NULL, 50.0000, NULL, 48.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(10, 10, NULL, 25.0000, NULL, 55.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(11, 11, NULL, 15.0000, NULL, 390.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(12, 12, NULL, 87.0000, NULL, 20.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-19 11:06:04'),
(13, 13, NULL, 55.0000, NULL, 45.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(14, 14, NULL, 23.0000, NULL, 150.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(15, 15, NULL, 42.0000, NULL, 95.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(16, 16, NULL, 33.0000, NULL, 100.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-19 09:49:11'),
(17, 17, NULL, 35.0000, NULL, 125.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(18, 18, NULL, 34.0000, NULL, 100.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(19, 19, NULL, 39.0000, NULL, 65.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-19 11:06:04'),
(20, 20, NULL, 20.0000, NULL, 170.00, '2026-10-17 11:57:12', NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `su_stock_counts`
--

CREATE TABLE `su_stock_counts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `started_by_staff_id` bigint(20) UNSIGNED NOT NULL,
  `completed_by_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'open',
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_stock_count_items`
--

CREATE TABLE `su_stock_count_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_count_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expected_quantity` decimal(18,4) NOT NULL,
  `counted_quantity` decimal(18,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_stock_movements`
--

CREATE TABLE `su_stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `batch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `movement_type` varchar(40) NOT NULL,
  `quantity_delta` decimal(18,4) NOT NULL,
  `source_type` varchar(255) DEFAULT NULL,
  `source_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_stock_transfers`
--

CREATE TABLE `su_stock_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `from_branch_id` bigint(20) UNSIGNED NOT NULL,
  `to_branch_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by_staff_id` bigint(20) UNSIGNED NOT NULL,
  `approved_by_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'draft',
  `dispatched_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_stock_transfer_items`
--

CREATE TABLE `su_stock_transfer_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_transfer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity_sent` decimal(18,4) NOT NULL,
  `quantity_received` decimal(18,4) NOT NULL DEFAULT 0.0000,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_suppliers`
--

CREATE TABLE `su_suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `other_details` text DEFAULT NULL,
  `balance_owed` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_supplier_transactions`
--

CREATE TABLE `su_supplier_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(18,2) NOT NULL,
  `note` text DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_system_logs`
--

CREATE TABLE `su_system_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_system_logs`
--

INSERT INTO `su_system_logs` (`id`, `user_id`, `action`, `module`, `reference_type`, `reference_id`, `description`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-26 11:29:18', NULL),
(2, 3, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.206', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-28 09:17:11', NULL),
(3, 3, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.206', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-28 09:20:45', NULL),
(4, 3, 'updated', 'staff', 'User', 5, NULL, '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-28 11:15:01', NULL),
(5, 1, 'updated', 'ivr_menus', 'IvrMenu', 1, NULL, '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-28 11:26:32', NULL),
(6, 1, 'updated', 'ivr_menus', 'IvrMenu', 2, NULL, '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-28 13:18:50', NULL),
(7, 1, 'updated', 'ivr_menus', 'IvrMenu', 2, NULL, '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-28 13:19:37', NULL),
(8, 1, 'updated', 'ivr_menus', 'IvrMenu', 1, NULL, '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-28 13:23:06', NULL),
(9, 1, 'updated', 'ivr_menus', 'IvrMenu', 2, NULL, '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-08-30 14:07:39', NULL),
(10, 1, 'merged', 'contacts', 'Contact', 6, 'Merged +254706006230 (#7) into 0706006230', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-02 08:49:36', NULL),
(11, 1, 'merged', 'contacts', 'Contact', 6, 'Merged 254706006230 (#8) into 0706006230', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-02 08:49:36', NULL),
(12, 1, 'merged', 'contacts', 'Contact', 6, 'Merged 706006230 (#9) into 0706006230', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-02 08:49:36', NULL),
(13, 1, 'created', 'hold_media', 'HoldMedia', 1, 'Uploaded Hold Media', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-02 11:10:08', NULL),
(14, 3, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-02 12:40:03', NULL),
(15, 3, 'updated', 'ivr_menus', 'IvrMenu', 2, NULL, '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-02 15:06:20', NULL),
(16, 3, 'created', 'hold_media', 'HoldMedia', 2, 'Uploaded Advert', '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-03 14:05:50', NULL),
(17, 1, 'created', 'hold_media', 'HoldMedia', 3, 'Uploaded 2026-09-03 13-42-13.mp3', '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-03 15:36:38', NULL),
(18, 1, 'created', 'hold_media', 'HoldMedia', 4, 'Uploaded Advert', '102.204.12.75', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-03 16:41:18', NULL),
(19, 1, 'created', 'hold_media', 'HoldMedia', 5, 'Uploaded Advertisement', '102.204.12.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-04 05:00:58', NULL),
(20, 1, 'created', 'hold_media', 'HoldMedia', 6, 'Uploaded Music', '102.204.12.84', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-04 07:13:14', NULL),
(21, 1, 'created', 'hold_media', 'HoldMedia', 7, 'Uploaded 2026-09-03 13-42-13.mp3', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-09 09:16:51', NULL),
(22, 1, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-09 11:27:46', NULL),
(23, 1, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-09 11:28:56', NULL),
(24, 1, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-09 11:37:16', NULL),
(25, 1, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-09 11:40:49', NULL),
(26, 1, 'merged', 'contacts', 'Contact', 14, 'Merged +254700577467 (#13) into 0700577467', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-09 11:41:41', NULL),
(27, 1, 'merged', 'contacts', 'Contact', 15, 'Merged +254792196624 (#10) into 0792196624', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-09 11:41:47', NULL),
(28, 1, 'updated', 'voice_provider', 'VoiceProviderCredential', 1, 'Updated voice provider connection', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 05:53:06', NULL),
(29, 1, 'created', 'hold_media', 'HoldMedia', 8, 'Uploaded 2026-09-03 13-42-13.mp3', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-10 06:15:39', NULL),
(30, 1, 'created', 'hold_media', 'HoldMedia', 15, 'Uploaded Hold music', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 11:37:14', NULL),
(31, 1, 'created', 'hold_media', 'HoldMedia', 16, 'Uploaded Music', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 11:54:04', NULL),
(32, 1, 'created', 'hold_media', 'HoldMedia', 17, 'Uploaded 2026-09-03 13-42-13.mp3', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 12:14:29', NULL),
(33, 1, 'updated', 'follow_ups', 'CallNote', 1, 'Updated follow up for call #4', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 13:09:32', NULL),
(34, 1, 'updated', 'follow_ups', 'CallNote', 1, 'Updated follow up for call #4', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 13:09:41', NULL),
(35, 1, 'created', 'hold_media', 'HoldMedia', 18, 'Uploaded Music', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 13:39:41', NULL),
(36, 1, 'created', 'hold_media', 'HoldMedia', 19, 'Uploaded test', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 14:06:13', NULL),
(37, 1, 'created', 'hold_media', 'HoldMedia', 20, 'Added external hold audio: Hold audio', '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 14:35:21', NULL),
(38, 1, 'updated', 'ivr_menus', 'IvrMenu', 1, NULL, '102.204.12.74', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-15 14:31:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_units_of_measure`
--

CREATE TABLE `su_units_of_measure` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `allows_decimal` tinyint(1) NOT NULL DEFAULT 1,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_units_of_measure`
--

INSERT INTO `su_units_of_measure` (`id`, `name`, `allows_decimal`, `business_id`, `branch_id`, `created_at`, `updated_at`) VALUES
(1, 'Kg', 1, NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(2, 'Litre', 1, NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12'),
(3, 'Piece', 0, NULL, NULL, '2026-10-17 08:57:12', '2026-10-17 08:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `su_users`
--

CREATE TABLE `su_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `softphone_client_name` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `twofa_code` varchar(6) DEFAULT NULL,
  `twofa_expires_at` timestamp NULL DEFAULT NULL,
  `password_reset_code` varchar(6) DEFAULT NULL,
  `password_reset_expires_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_users`
--

INSERT INTO `su_users` (`id`, `first_name`, `last_name`, `email`, `phone`, `softphone_client_name`, `is_available`, `last_seen_at`, `password`, `status`, `last_login_at`, `twofa_code`, `twofa_expires_at`, `password_reset_code`, `password_reset_expires_at`, `created_by`, `created_at`, `updated_at`, `deleted_at`, `remember_token`) VALUES
(1, 'Sifuna', 'Godfrey', 'sifuna.godfreyw@gmail.com', NULL, 'agent_1_1789528638_97988e', 1, '2026-09-16 03:29:49', '$2y$12$d9safZcXDqgoCKOg5dYulOv7RspcJvg/Bm49lmADaHfdoaTB1PfmW', 'active', '2026-10-19 07:07:12', NULL, NULL, '668028', '2026-09-10 09:57:43', NULL, '2026-08-25 02:54:09', '2026-10-19 07:07:12', NULL, 'OtnFjmwuVGHEQsv6hz37h5C6fLGUUE1dQpfkgm5r5xSXUMx21UmSRobEWsUw'),
(2, 'Call Center', NULL, 'info@duramaxmabati.com', NULL, NULL, 1, NULL, '$2y$12$5h1JsKmsvoOz3oOAVPTM9.lHY9YZcK/POMbDsXL/9J0e3GyxrZlt2', 'active', NULL, NULL, NULL, NULL, NULL, NULL, '2026-08-25 02:54:10', '2026-08-25 02:54:10', '2026-09-11 04:04:44', NULL),
(3, 'Collince', 'Ouma', 'duramaxmabati@gmail.com', NULL, 'agent_3_1789488860_7e38a2', 1, '2026-09-15 16:16:54', '$2y$10$xrfivIEzVbCI0lForVAgT.LeRjtIB.F45xdN.LNWPHqG6a.1NHWzi', 'active', '2026-09-15 14:57:33', NULL, NULL, NULL, NULL, NULL, '2026-08-25 02:54:10', '2026-09-15 16:16:54', NULL, 'NfLOlJvQDcyrpDtCJdTwXOjbaxy3EoDJKoSP6KJYw2osjvUsMHniZ8bzvrGe'),
(6, 'Elizabeth', 'Nasambu', 'lizbethnasambu@gmail.com', '0701118637', 'agent_6_1789192853_c0dbef', 1, '2026-09-12 06:12:50', '$2y$12$aAoTGtVQNu153deO9hGARuLNnKaqekUfwpfScrHKnGRKHceomTm5e', 'active', '2026-09-12 06:00:52', NULL, NULL, NULL, NULL, NULL, '2026-09-11 14:17:41', '2026-09-11 14:19:12', NULL, NULL),
(7, 'Phoebe', 'Obino', 'obinophoebe@gmail.com', '0742643123', NULL, 1, '2026-09-11 14:23:33', '$2y$12$aAoTGtVQNu153deO9hGARuLNnKaqekUfwpfScrHKnGRKHceomTm5e', 'active', '2026-09-11 14:23:33', NULL, NULL, NULL, NULL, NULL, '2026-09-11 14:17:41', '2026-09-11 14:19:35', NULL, NULL),
(8, 'Kevine', 'Galacha', 'kevinegalacha2019@gmail.com', '0792196624', NULL, 1, '2026-09-11 14:24:13', '$2y$12$aAoTGtVQNu153deO9hGARuLNnKaqekUfwpfScrHKnGRKHceomTm5e', 'active', '2026-09-11 14:24:13', NULL, NULL, NULL, NULL, NULL, '2026-09-11 14:17:41', '2026-09-11 14:19:26', NULL, NULL),
(9, 'Janet', 'Adhiambo', 'adhiambojanet@gmail.com', '0718667308', 'agent_9_1789195059_4fbfde', 1, '2026-09-12 08:40:12', '$2y$12$aAoTGtVQNu153deO9hGARuLNnKaqekUfwpfScrHKnGRKHceomTm5e', 'active', '2026-09-12 08:40:12', NULL, NULL, NULL, NULL, NULL, '2026-09-11 14:17:41', '2026-09-11 14:19:20', NULL, NULL),
(10, 'Dana', 'Katisya', 'katisyadana@gmail.com', NULL, 'agent_10_1789196945_c93c6b', 1, '2026-09-12 14:15:08', '$2y$10$SJl4ezddohQAFDhQpprUa.yiWX51qEjIZ9ZNeihELJBxLliWrWkyO', 'active', '2026-09-12 07:09:05', NULL, NULL, NULL, NULL, NULL, '2026-09-12 07:08:07', '2026-09-12 07:08:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `su_user_branches`
--

CREATE TABLE `su_user_branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_user_profiles`
--

CREATE TABLE `su_user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `profile_picture_path` varchar(255) DEFAULT NULL,
  `secondary_phone` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `su_user_roles`
--

CREATE TABLE `su_user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `su_user_roles`
--

INSERT INTO `su_user_roles` (`user_id`, `role_id`, `assigned_at`) VALUES
(1, 1, '2026-08-25 13:44:51'),
(2, 1, '2026-08-25 13:44:51'),
(3, 1, '2026-09-12 06:56:16'),
(6, 3, '2026-09-11 14:19:12'),
(7, 3, '2026-09-11 14:19:35'),
(8, 3, '2026-09-11 14:19:26'),
(9, 3, '2026-09-11 14:19:20'),
(10, 3, '2026-09-12 07:08:07');

-- --------------------------------------------------------

--
-- Table structure for table `su_wholesale_pricing`
--

CREATE TABLE `su_wholesale_pricing` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `min_quantity` decimal(18,4) DEFAULT NULL,
  `price` decimal(18,2) NOT NULL,
  `business_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `su_audit_log`
--
ALTER TABLE `su_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_7ed27d68d9d001893368` (`staff_id`);

--
-- Indexes for table `su_audit_logs`
--
ALTER TABLE `su_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `su_audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `su_audit_logs_module_record_id_index` (`module`,`record_id`);

--
-- Indexes for table `su_backorders`
--
ALTER TABLE `su_backorders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_69d814ed029bdc2a6427` (`sale_item_id`),
  ADD KEY `fk_dea5f8102294131be573` (`fulfilled_from_batch_id`),
  ADD KEY `fk_28e98f10229c14db9b9d` (`business_id`),
  ADD KEY `fk_a17a569d9d90850cbaad` (`branch_id`);

--
-- Indexes for table `su_backups`
--
ALTER TABLE `su_backups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_77fd9be3e7aacb9642bf` (`staff_id`),
  ADD KEY `fk_a1747e9352d028e61975` (`business_id`),
  ADD KEY `fk_efd825092fc7efad2094` (`branch_id`);

--
-- Indexes for table `su_barcode_labels`
--
ALTER TABLE `su_barcode_labels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_barcode_labels_code_unique` (`code`),
  ADD KEY `fk_208264470b50c33cf6ab` (`product_id`),
  ADD KEY `fk_9d96cb07a6a18aab6e61` (`variant_id`),
  ADD KEY `fk_362b63677776b97f5836` (`business_id`),
  ADD KEY `fk_eb3654adbcb7db474cfb` (`branch_id`);

--
-- Indexes for table `su_branches`
--
ALTER TABLE `su_branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_branches_business_id_name_unique` (`business_id`,`name`);

--
-- Indexes for table `su_brands`
--
ALTER TABLE `su_brands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ec74bba912565c45d34b` (`business_id`),
  ADD KEY `fk_56c9bc79e67ff0a4da94` (`branch_id`);

--
-- Indexes for table `su_businesses`
--
ALTER TABLE `su_businesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_businesses_slug_unique` (`slug`);

--
-- Indexes for table `su_cash_box`
--
ALTER TABLE `su_cash_box`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_3adb9ec7725dc246cd18` (`shift_id`),
  ADD KEY `fk_e6a9084f18cd80d1bb20` (`category_id`),
  ADD KEY `fk_d2e1cd7aa863368b16b7` (`business_id`),
  ADD KEY `fk_1539a873829abf9366a9` (`branch_id`);

--
-- Indexes for table `su_cash_box_categories`
--
ALTER TABLE `su_cash_box_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_42bbcf6a88ec680410a9` (`business_id`),
  ADD KEY `fk_fb9856816b75fe99188e` (`branch_id`);

--
-- Indexes for table `su_categories`
--
ALTER TABLE `su_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_71d9f5ea53fa6e6a688a` (`parent_id`),
  ADD KEY `fk_d39c6ab89a940936391e` (`business_id`),
  ADD KEY `fk_3f5df99125682c921c95` (`branch_id`);

--
-- Indexes for table `su_credit_notes`
--
ALTER TABLE `su_credit_notes`
  ADD PRIMARY KEY (`return_id`);

--
-- Indexes for table `su_credit_transactions`
--
ALTER TABLE `su_credit_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_d95f0d98089ff60094de` (`customer_id`),
  ADD KEY `fk_71019439b9813847c5e0` (`staff_id`),
  ADD KEY `fk_16283c3a45bcd691e2dd` (`sale_id`),
  ADD KEY `fk_965718f6614f36e341fa` (`business_id`),
  ADD KEY `fk_a980117cc8a5e69c6024` (`branch_id`);

--
-- Indexes for table `su_customers`
--
ALTER TABLE `su_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_7f32c48e0945ce5becab` (`business_id`),
  ADD KEY `fk_5bad188a7ec70b75fb86` (`branch_id`);

--
-- Indexes for table `su_delivery_notes`
--
ALTER TABLE `su_delivery_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ec9448e8a3901d708820` (`sale_id`),
  ADD KEY `fk_99542f07870d6ef62225` (`staff_id`),
  ADD KEY `fk_d52420a4b57632a908ac` (`business_id`),
  ADD KEY `fk_04947bf11858f7a758fe` (`branch_id`);

--
-- Indexes for table `su_discounts_promotions`
--
ALTER TABLE `su_discounts_promotions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_5376af98b2a49d51994e` (`product_id`),
  ADD KEY `fk_edf4721f720b47224986` (`variant_id`),
  ADD KEY `fk_b5d2cd05c0b4428ccfd6` (`business_id`),
  ADD KEY `fk_6280455fbcf4f6a5be2c` (`branch_id`);

--
-- Indexes for table `su_documents`
--
ALTER TABLE `su_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_6c807ed13fa9f906b7b2` (`customer_id`),
  ADD KEY `fk_19ca3c5a7b4bafe106f6` (`staff_id`),
  ADD KEY `fk_65d0e3997fd19770b35e` (`source_document_id`),
  ADD KEY `fk_0f02c30acebb750d2a9d` (`converted_to_sale_id`),
  ADD KEY `fk_dc9d25dafd98d438b5cd` (`business_id`),
  ADD KEY `fk_5d897133abea745322f9` (`branch_id`);

--
-- Indexes for table `su_document_items`
--
ALTER TABLE `su_document_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_57b7d66fbd6ccaac91b7` (`document_id`),
  ADD KEY `fk_1ec4e208a21d377bd3e9` (`product_id`),
  ADD KEY `fk_66b19c1bc3b62e1e88b9` (`variant_id`),
  ADD KEY `fk_8704fdf6109ff4fe2c44` (`business_id`),
  ADD KEY `fk_6f37984ba7c01086e9da` (`branch_id`);

--
-- Indexes for table `su_email_settings`
--
ALTER TABLE `su_email_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `su_license_state`
--
ALTER TABLE `su_license_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `su_media`
--
ALTER TABLE `su_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `su_media_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `su_missing_stock_requests`
--
ALTER TABLE `su_missing_stock_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ae093d5c282478be9c25` (`product_id`),
  ADD KEY `fk_a028909028dbb5c65773` (`customer_id`),
  ADD KEY `fk_82ad8fc9649e81ffbc20` (`requested_by_staff_id`),
  ADD KEY `fk_b0e1636932b71bf47a8f` (`business_id`),
  ADD KEY `fk_a9e8e70579154c34e6a2` (`branch_id`);

--
-- Indexes for table `su_mpesa_applications`
--
ALTER TABLE `su_mpesa_applications`
  ADD PRIMARY KEY (`remote_id`),
  ADD UNIQUE KEY `su_mpesa_applications_reservation_key_unique` (`reservation_key`),
  ADD KEY `fk_d958f8034e9d25c637dc` (`sale_id`);

--
-- Indexes for table `su_mpesa_attempts`
--
ALTER TABLE `su_mpesa_attempts`
  ADD PRIMARY KEY (`checkout_key`),
  ADD UNIQUE KEY `su_mpesa_attempts_request_key_unique` (`request_key`),
  ADD KEY `fk_562184e4160e866315fd` (`remote_id`);

--
-- Indexes for table `su_mpesa_receipts`
--
ALTER TABLE `su_mpesa_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_mpesa_receipt_merchant_env_unique` (`merchant_id`,`environment`,`receipt`),
  ADD UNIQUE KEY `su_mpesa_receipts_remote_id_unique` (`remote_id`);

--
-- Indexes for table `su_network_checkouts`
--
ALTER TABLE `su_network_checkouts`
  ADD PRIMARY KEY (`device_id`,`checkout_key`),
  ADD KEY `fk_061b1064fa6021e837e4` (`staff_id`);

--
-- Indexes for table `su_network_devices`
--
ALTER TABLE `su_network_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_network_devices_token_hash_unique` (`token_hash`);

--
-- Indexes for table `su_network_shifts`
--
ALTER TABLE `su_network_shifts`
  ADD PRIMARY KEY (`shift_id`),
  ADD KEY `fk_7a61e024a79d9b7ec508` (`device_id`);

--
-- Indexes for table `su_notifications`
--
ALTER TABLE `su_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `su_notifications_user_id_foreign` (`user_id`),
  ADD KEY `su_notifications_causer_id_foreign` (`causer_id`);

--
-- Indexes for table `su_password_reset_tokens`
--
ALTER TABLE `su_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `su_payment_methods`
--
ALTER TABLE `su_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dcb3c060753b2325a36b` (`sale_id`),
  ADD KEY `fk_5cf2a275230272b79ac4` (`staff_id`),
  ADD KEY `fk_51608e2dbfe3f0ef6371` (`shift_id`),
  ADD KEY `fk_01e60222cd11a521b543` (`business_id`),
  ADD KEY `fk_1cc776021e123e6360bf` (`branch_id`);

--
-- Indexes for table `su_permissions`
--
ALTER TABLE `su_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_permissions_name_unique` (`name`),
  ADD UNIQUE KEY `su_permissions_slug_unique` (`slug`);

--
-- Indexes for table `su_pos_cloud_backups`
--
ALTER TABLE `su_pos_cloud_backups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pos_backup_dedup` (`license_id`,`edition`,`digest`),
  ADD KEY `pos_backup_license` (`license_id`);

--
-- Indexes for table `su_pos_cloud_backup_settings`
--
ALTER TABLE `su_pos_cloud_backup_settings`
  ADD PRIMARY KEY (`license_id`);

--
-- Indexes for table `su_products`
--
ALTER TABLE `su_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_7c09e195a6e24f3d3d4a` (`category_id`),
  ADD KEY `fk_9e6cefeb7b798740e2cf` (`brand_id`),
  ADD KEY `fk_3b0d604f0cbb7924f0bd` (`unit_of_measure_id`),
  ADD KEY `fk_4245478b6bcd2a066f6f` (`business_id`),
  ADD KEY `fk_3c8eb998882f230b0a66` (`branch_id`);

--
-- Indexes for table `su_product_images`
--
ALTER TABLE `su_product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_80162cc64b0271434395` (`product_id`),
  ADD KEY `fk_45bb6971835560af6600` (`variant_id`),
  ADD KEY `fk_d11b6a4b057306592b41` (`business_id`),
  ADD KEY `fk_d1fd57843d27ce09a480` (`branch_id`);

--
-- Indexes for table `su_product_variants`
--
ALTER TABLE `su_product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_c3a4c5e04841b80a3ce3` (`product_id`),
  ADD KEY `fk_f242bd38e8f0a0df1c14` (`unit_of_measure_id`),
  ADD KEY `fk_4520153dace57082f089` (`business_id`),
  ADD KEY `fk_42483777d6da358448f6` (`branch_id`);

--
-- Indexes for table `su_purchase_orders`
--
ALTER TABLE `su_purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bc9f6eccfa7118ba8187` (`supplier_id`),
  ADD KEY `fk_ea8e60b02ff278f3a10e` (`business_id`),
  ADD KEY `fk_3b367d81fc33c55700d0` (`branch_id`);

--
-- Indexes for table `su_purchase_order_items`
--
ALTER TABLE `su_purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_0651d14a364a4079b4a1` (`purchase_order_id`),
  ADD KEY `fk_e4fa1623da1d0d897845` (`product_id`),
  ADD KEY `fk_d285a8975317462b74a2` (`variant_id`),
  ADD KEY `fk_54cd81248c8448f72a2c` (`business_id`),
  ADD KEY `fk_a936001395427a537e29` (`branch_id`);

--
-- Indexes for table `su_receipts`
--
ALTER TABLE `su_receipts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_9a00a1aa639933095a78` (`sale_id`),
  ADD KEY `fk_bb3931a9023e635ab4bf` (`payment_id`),
  ADD KEY `fk_d2330097f9e202f1af93` (`business_id`),
  ADD KEY `fk_d1ed554c183bae40ce88` (`branch_id`);

--
-- Indexes for table `su_returns_refunds`
--
ALTER TABLE `su_returns_refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_c05a5a3dbae139336795` (`sale_id`),
  ADD KEY `fk_151b9ebaa5d81e4cee87` (`sale_item_id`),
  ADD KEY `fk_195636c8d4fe2fc60091` (`staff_id`),
  ADD KEY `fk_95ae95945f8fa9caaff4` (`business_id`),
  ADD KEY `fk_6b29593d145e8f074534` (`branch_id`);

--
-- Indexes for table `su_roles`
--
ALTER TABLE `su_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_roles_name_unique` (`name`),
  ADD UNIQUE KEY `su_roles_slug_unique` (`slug`);

--
-- Indexes for table `su_role_permissions`
--
ALTER TABLE `su_role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `su_role_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `su_sales`
--
ALTER TABLE `su_sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_27d3f09aab24bc1f0742` (`staff_id`),
  ADD KEY `fk_a5d2f278e814e7f50aed` (`shift_id`),
  ADD KEY `fk_988e8a84dc8071a38037` (`customer_id`),
  ADD KEY `fk_38941e5548e33c68a481` (`business_id`),
  ADD KEY `fk_3127d50434f3a9c6731c` (`branch_id`);

--
-- Indexes for table `su_sale_items`
--
ALTER TABLE `su_sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_7c3c39b6d6c211d61a1d` (`sale_id`),
  ADD KEY `fk_02cd25831720c86174d3` (`product_id`),
  ADD KEY `fk_a4dcd8fe4d04103758a0` (`variant_id`),
  ADD KEY `fk_409705d77f7041e9192b` (`batch_id`),
  ADD KEY `fk_bffa77f101a21a73d6c0` (`business_id`),
  ADD KEY `fk_57de94ab0d59cf1fef1c` (`branch_id`);

--
-- Indexes for table `su_sale_payment_allocations`
--
ALTER TABLE `su_sale_payment_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_a8d41dedbd3986a39329` (`sale_id`),
  ADD KEY `fk_bca763477d56f67292df` (`payment_method_id`);

--
-- Indexes for table `su_settings`
--
ALTER TABLE `su_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_settings_setting_key_unique` (`setting_key`);

--
-- Indexes for table `su_shifts`
--
ALTER TABLE `su_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_9073fe7546b0940b4f80` (`staff_id`),
  ADD KEY `fk_b70c9faea7856dc24e9d` (`business_id`),
  ADD KEY `fk_f95c81ab0d1957867542` (`branch_id`);

--
-- Indexes for table `su_staff`
--
ALTER TABLE `su_staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_staff_user_id_unique` (`user_id`);

--
-- Indexes for table `su_staff_leave`
--
ALTER TABLE `su_staff_leave`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_4601764e9651da3c02e6` (`staff_id`),
  ADD KEY `fk_98cc692c379fa592eb8a` (`logged_by_staff_id`),
  ADD KEY `fk_32e568079969701aba28` (`business_id`),
  ADD KEY `fk_b0092f38398263b42f08` (`branch_id`);

--
-- Indexes for table `su_stock_adjustments`
--
ALTER TABLE `su_stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_309b32bc3927c35a31ea` (`product_id`),
  ADD KEY `fk_3f35ad9a2f4c32247bbc` (`variant_id`),
  ADD KEY `fk_6d262511e244d93d2244` (`staff_id`),
  ADD KEY `fk_caeebfb9ecb0aeb2a17e` (`business_id`),
  ADD KEY `fk_74e273ad77f959d4d77d` (`branch_id`);

--
-- Indexes for table `su_stock_batches`
--
ALTER TABLE `su_stock_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_a84a74570168466ffed8` (`product_id`),
  ADD KEY `fk_e2c7279d23f3244a7df4` (`variant_id`),
  ADD KEY `fk_1824f451483e95de7ec5` (`business_id`),
  ADD KEY `fk_0bf71fd837613aa59ef0` (`branch_id`);

--
-- Indexes for table `su_stock_counts`
--
ALTER TABLE `su_stock_counts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_65bc4484f2612811118d` (`branch_id`),
  ADD KEY `fk_344f6a5f1ec95d821239` (`started_by_staff_id`),
  ADD KEY `fk_1f79ba427f256690ddb1` (`completed_by_staff_id`);

--
-- Indexes for table `su_stock_count_items`
--
ALTER TABLE `su_stock_count_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_409850b30a02ebe02c1a` (`stock_count_id`),
  ADD KEY `fk_80f524933a476e86bf6f` (`product_id`),
  ADD KEY `fk_4085c2be656e836a743c` (`variant_id`),
  ADD KEY `fk_2bf754589cf5f1b04ff9` (`batch_id`);

--
-- Indexes for table `su_stock_movements`
--
ALTER TABLE `su_stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `su_stock_movements_product_id_created_at_index` (`product_id`,`created_at`),
  ADD KEY `fk_7d398b6653e2c9e5846a` (`branch_id`),
  ADD KEY `fk_67257c0dc0c5ee7efdc5` (`variant_id`),
  ADD KEY `fk_6702a939812628cc343d` (`batch_id`),
  ADD KEY `fk_8e2b472e59e3001a6967` (`staff_id`);

--
-- Indexes for table `su_stock_transfers`
--
ALTER TABLE `su_stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_3703a3a6e5019f582a66` (`from_branch_id`),
  ADD KEY `fk_4a6f565236592c45edd7` (`to_branch_id`),
  ADD KEY `fk_2e4eab6b14c69f01b883` (`requested_by_staff_id`),
  ADD KEY `fk_670646eef5eb24c03a6a` (`approved_by_staff_id`);

--
-- Indexes for table `su_stock_transfer_items`
--
ALTER TABLE `su_stock_transfer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_a8299560ad22fc15d9ae` (`stock_transfer_id`),
  ADD KEY `fk_74eaa2d4dd70336c98d6` (`product_id`),
  ADD KEY `fk_c204d45ccce13f59d122` (`variant_id`);

--
-- Indexes for table `su_suppliers`
--
ALTER TABLE `su_suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_9055724229da601d0ffb` (`business_id`),
  ADD KEY `fk_3ab10ed3d7c6bc9805e6` (`branch_id`);

--
-- Indexes for table `su_supplier_transactions`
--
ALTER TABLE `su_supplier_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_19746caf8d216ae600d0` (`supplier_id`),
  ADD KEY `fk_7ba2318b662fbe2bd41f` (`staff_id`),
  ADD KEY `fk_d86a4437d3724018096c` (`purchase_order_id`),
  ADD KEY `fk_4f33e164e51ed71c028b` (`business_id`),
  ADD KEY `fk_903a447488fad81d4127` (`branch_id`);

--
-- Indexes for table `su_system_logs`
--
ALTER TABLE `su_system_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `su_system_logs_user_id_foreign` (`user_id`),
  ADD KEY `su_system_logs_module_reference_id_index` (`module`,`reference_id`);

--
-- Indexes for table `su_units_of_measure`
--
ALTER TABLE `su_units_of_measure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_0b81605f272efd8aaf02` (`business_id`),
  ADD KEY `fk_05b8785c66ba4f6460d7` (`branch_id`);

--
-- Indexes for table `su_users`
--
ALTER TABLE `su_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_users_email_unique` (`email`),
  ADD UNIQUE KEY `su_users_phone_unique` (`phone`),
  ADD KEY `su_users_created_by_foreign` (`created_by`);

--
-- Indexes for table `su_user_branches`
--
ALTER TABLE `su_user_branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_user_branches_user_id_branch_id_unique` (`user_id`,`branch_id`),
  ADD KEY `fk_ccf3b04cb88082c1f33a` (`branch_id`);

--
-- Indexes for table `su_user_profiles`
--
ALTER TABLE `su_user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `su_user_profiles_user_id_unique` (`user_id`);

--
-- Indexes for table `su_user_roles`
--
ALTER TABLE `su_user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `su_user_roles_role_id_foreign` (`role_id`);

--
-- Indexes for table `su_wholesale_pricing`
--
ALTER TABLE `su_wholesale_pricing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_eb5a3219bf348638d8d7` (`product_id`),
  ADD KEY `fk_8e72acd4ff6f9cb1743e` (`variant_id`),
  ADD KEY `fk_a810c949fb86908960a9` (`customer_id`),
  ADD KEY `fk_69064a706a2cb50a1334` (`business_id`),
  ADD KEY `fk_78db3bf7c6dc376449e3` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `su_audit_log`
--
ALTER TABLE `su_audit_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_audit_logs`
--
ALTER TABLE `su_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_backorders`
--
ALTER TABLE `su_backorders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_backups`
--
ALTER TABLE `su_backups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_barcode_labels`
--
ALTER TABLE `su_barcode_labels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `su_branches`
--
ALTER TABLE `su_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_brands`
--
ALTER TABLE `su_brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_businesses`
--
ALTER TABLE `su_businesses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_cash_box`
--
ALTER TABLE `su_cash_box`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_cash_box_categories`
--
ALTER TABLE `su_cash_box_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_categories`
--
ALTER TABLE `su_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `su_credit_transactions`
--
ALTER TABLE `su_credit_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_customers`
--
ALTER TABLE `su_customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_delivery_notes`
--
ALTER TABLE `su_delivery_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_discounts_promotions`
--
ALTER TABLE `su_discounts_promotions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_documents`
--
ALTER TABLE `su_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_document_items`
--
ALTER TABLE `su_document_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_email_settings`
--
ALTER TABLE `su_email_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_license_state`
--
ALTER TABLE `su_license_state`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_media`
--
ALTER TABLE `su_media`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_missing_stock_requests`
--
ALTER TABLE `su_missing_stock_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_mpesa_receipts`
--
ALTER TABLE `su_mpesa_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_notifications`
--
ALTER TABLE `su_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_payment_methods`
--
ALTER TABLE `su_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `su_permissions`
--
ALTER TABLE `su_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=155;

--
-- AUTO_INCREMENT for table `su_products`
--
ALTER TABLE `su_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `su_product_images`
--
ALTER TABLE `su_product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_product_variants`
--
ALTER TABLE `su_product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_purchase_orders`
--
ALTER TABLE `su_purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_purchase_order_items`
--
ALTER TABLE `su_purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_receipts`
--
ALTER TABLE `su_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_returns_refunds`
--
ALTER TABLE `su_returns_refunds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_roles`
--
ALTER TABLE `su_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `su_sales`
--
ALTER TABLE `su_sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `su_sale_items`
--
ALTER TABLE `su_sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `su_sale_payment_allocations`
--
ALTER TABLE `su_sale_payment_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_settings`
--
ALTER TABLE `su_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_shifts`
--
ALTER TABLE `su_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_staff`
--
ALTER TABLE `su_staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `su_staff_leave`
--
ALTER TABLE `su_staff_leave`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_stock_adjustments`
--
ALTER TABLE `su_stock_adjustments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_stock_batches`
--
ALTER TABLE `su_stock_batches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `su_stock_counts`
--
ALTER TABLE `su_stock_counts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_stock_count_items`
--
ALTER TABLE `su_stock_count_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_stock_movements`
--
ALTER TABLE `su_stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_stock_transfers`
--
ALTER TABLE `su_stock_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_stock_transfer_items`
--
ALTER TABLE `su_stock_transfer_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_suppliers`
--
ALTER TABLE `su_suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_supplier_transactions`
--
ALTER TABLE `su_supplier_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_system_logs`
--
ALTER TABLE `su_system_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `su_units_of_measure`
--
ALTER TABLE `su_units_of_measure`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `su_users`
--
ALTER TABLE `su_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `su_user_branches`
--
ALTER TABLE `su_user_branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_user_profiles`
--
ALTER TABLE `su_user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `su_wholesale_pricing`
--
ALTER TABLE `su_wholesale_pricing`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `su_audit_log`
--
ALTER TABLE `su_audit_log`
  ADD CONSTRAINT `fk_7ed27d68d9d001893368` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_audit_logs`
--
ALTER TABLE `su_audit_logs`
  ADD CONSTRAINT `su_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `su_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_backorders`
--
ALTER TABLE `su_backorders`
  ADD CONSTRAINT `fk_28e98f10229c14db9b9d` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_69d814ed029bdc2a6427` FOREIGN KEY (`sale_item_id`) REFERENCES `su_sale_items` (`id`),
  ADD CONSTRAINT `fk_a17a569d9d90850cbaad` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dea5f8102294131be573` FOREIGN KEY (`fulfilled_from_batch_id`) REFERENCES `su_stock_batches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_backups`
--
ALTER TABLE `su_backups`
  ADD CONSTRAINT `fk_77fd9be3e7aacb9642bf` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a1747e9352d028e61975` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_efd825092fc7efad2094` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_barcode_labels`
--
ALTER TABLE `su_barcode_labels`
  ADD CONSTRAINT `fk_208264470b50c33cf6ab` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_362b63677776b97f5836` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_9d96cb07a6a18aab6e61` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_eb3654adbcb7db474cfb` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_branches`
--
ALTER TABLE `su_branches`
  ADD CONSTRAINT `fk_c766e985fb0c89c52341` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`);

--
-- Constraints for table `su_brands`
--
ALTER TABLE `su_brands`
  ADD CONSTRAINT `fk_56c9bc79e67ff0a4da94` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ec74bba912565c45d34b` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_cash_box`
--
ALTER TABLE `su_cash_box`
  ADD CONSTRAINT `fk_1539a873829abf9366a9` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_3adb9ec7725dc246cd18` FOREIGN KEY (`shift_id`) REFERENCES `su_shifts` (`id`),
  ADD CONSTRAINT `fk_d2e1cd7aa863368b16b7` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_e6a9084f18cd80d1bb20` FOREIGN KEY (`category_id`) REFERENCES `su_cash_box_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_cash_box_categories`
--
ALTER TABLE `su_cash_box_categories`
  ADD CONSTRAINT `fk_42bbcf6a88ec680410a9` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_fb9856816b75fe99188e` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_categories`
--
ALTER TABLE `su_categories`
  ADD CONSTRAINT `fk_3f5df99125682c921c95` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_71d9f5ea53fa6e6a688a` FOREIGN KEY (`parent_id`) REFERENCES `su_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_d39c6ab89a940936391e` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_credit_notes`
--
ALTER TABLE `su_credit_notes`
  ADD CONSTRAINT `fk_49b1b3155a85852d6f70` FOREIGN KEY (`return_id`) REFERENCES `su_returns_refunds` (`id`);

--
-- Constraints for table `su_credit_transactions`
--
ALTER TABLE `su_credit_transactions`
  ADD CONSTRAINT `fk_16283c3a45bcd691e2dd` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_71019439b9813847c5e0` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_965718f6614f36e341fa` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a980117cc8a5e69c6024` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_d95f0d98089ff60094de` FOREIGN KEY (`customer_id`) REFERENCES `su_customers` (`id`);

--
-- Constraints for table `su_customers`
--
ALTER TABLE `su_customers`
  ADD CONSTRAINT `fk_5bad188a7ec70b75fb86` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_7f32c48e0945ce5becab` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_delivery_notes`
--
ALTER TABLE `su_delivery_notes`
  ADD CONSTRAINT `fk_04947bf11858f7a758fe` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_99542f07870d6ef62225` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_d52420a4b57632a908ac` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ec9448e8a3901d708820` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`);

--
-- Constraints for table `su_discounts_promotions`
--
ALTER TABLE `su_discounts_promotions`
  ADD CONSTRAINT `fk_5376af98b2a49d51994e` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_6280455fbcf4f6a5be2c` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_b5d2cd05c0b4428ccfd6` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_edf4721f720b47224986` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_documents`
--
ALTER TABLE `su_documents`
  ADD CONSTRAINT `fk_0f02c30acebb750d2a9d` FOREIGN KEY (`converted_to_sale_id`) REFERENCES `su_sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_19ca3c5a7b4bafe106f6` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_5d897133abea745322f9` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_65d0e3997fd19770b35e` FOREIGN KEY (`source_document_id`) REFERENCES `su_documents` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_6c807ed13fa9f906b7b2` FOREIGN KEY (`customer_id`) REFERENCES `su_customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dc9d25dafd98d438b5cd` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_document_items`
--
ALTER TABLE `su_document_items`
  ADD CONSTRAINT `fk_1ec4e208a21d377bd3e9` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_57b7d66fbd6ccaac91b7` FOREIGN KEY (`document_id`) REFERENCES `su_documents` (`id`),
  ADD CONSTRAINT `fk_66b19c1bc3b62e1e88b9` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_6f37984ba7c01086e9da` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_8704fdf6109ff4fe2c44` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_media`
--
ALTER TABLE `su_media`
  ADD CONSTRAINT `su_media_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `su_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_missing_stock_requests`
--
ALTER TABLE `su_missing_stock_requests`
  ADD CONSTRAINT `fk_82ad8fc9649e81ffbc20` FOREIGN KEY (`requested_by_staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a028909028dbb5c65773` FOREIGN KEY (`customer_id`) REFERENCES `su_customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a9e8e70579154c34e6a2` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ae093d5c282478be9c25` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_b0e1636932b71bf47a8f` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_mpesa_applications`
--
ALTER TABLE `su_mpesa_applications`
  ADD CONSTRAINT `fk_288a6ac6a9db8d1604fa` FOREIGN KEY (`remote_id`) REFERENCES `su_mpesa_receipts` (`remote_id`),
  ADD CONSTRAINT `fk_d958f8034e9d25c637dc` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`);

--
-- Constraints for table `su_mpesa_attempts`
--
ALTER TABLE `su_mpesa_attempts`
  ADD CONSTRAINT `fk_562184e4160e866315fd` FOREIGN KEY (`remote_id`) REFERENCES `su_mpesa_receipts` (`remote_id`) ON DELETE SET NULL;

--
-- Constraints for table `su_network_checkouts`
--
ALTER TABLE `su_network_checkouts`
  ADD CONSTRAINT `fk_061b1064fa6021e837e4` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_8f624c409fad617b4f84` FOREIGN KEY (`device_id`) REFERENCES `su_network_devices` (`id`);

--
-- Constraints for table `su_network_shifts`
--
ALTER TABLE `su_network_shifts`
  ADD CONSTRAINT `fk_7a61e024a79d9b7ec508` FOREIGN KEY (`device_id`) REFERENCES `su_network_devices` (`id`),
  ADD CONSTRAINT `fk_d41cc9b60c7892b11cfb` FOREIGN KEY (`shift_id`) REFERENCES `su_shifts` (`id`);

--
-- Constraints for table `su_notifications`
--
ALTER TABLE `su_notifications`
  ADD CONSTRAINT `su_notifications_causer_id_foreign` FOREIGN KEY (`causer_id`) REFERENCES `su_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `su_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `su_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `su_payment_methods`
--
ALTER TABLE `su_payment_methods`
  ADD CONSTRAINT `fk_01e60222cd11a521b543` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_1cc776021e123e6360bf` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_51608e2dbfe3f0ef6371` FOREIGN KEY (`shift_id`) REFERENCES `su_shifts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_5cf2a275230272b79ac4` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dcb3c060753b2325a36b` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`);

--
-- Constraints for table `su_products`
--
ALTER TABLE `su_products`
  ADD CONSTRAINT `fk_3b0d604f0cbb7924f0bd` FOREIGN KEY (`unit_of_measure_id`) REFERENCES `su_units_of_measure` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_3c8eb998882f230b0a66` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_4245478b6bcd2a066f6f` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_7c09e195a6e24f3d3d4a` FOREIGN KEY (`category_id`) REFERENCES `su_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_9e6cefeb7b798740e2cf` FOREIGN KEY (`brand_id`) REFERENCES `su_brands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_product_images`
--
ALTER TABLE `su_product_images`
  ADD CONSTRAINT `fk_45bb6971835560af6600` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_80162cc64b0271434395` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_d11b6a4b057306592b41` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_d1fd57843d27ce09a480` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_product_variants`
--
ALTER TABLE `su_product_variants`
  ADD CONSTRAINT `fk_42483777d6da358448f6` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_4520153dace57082f089` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_c3a4c5e04841b80a3ce3` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`),
  ADD CONSTRAINT `fk_f242bd38e8f0a0df1c14` FOREIGN KEY (`unit_of_measure_id`) REFERENCES `su_units_of_measure` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_purchase_orders`
--
ALTER TABLE `su_purchase_orders`
  ADD CONSTRAINT `fk_3b367d81fc33c55700d0` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bc9f6eccfa7118ba8187` FOREIGN KEY (`supplier_id`) REFERENCES `su_suppliers` (`id`),
  ADD CONSTRAINT `fk_ea8e60b02ff278f3a10e` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_purchase_order_items`
--
ALTER TABLE `su_purchase_order_items`
  ADD CONSTRAINT `fk_0651d14a364a4079b4a1` FOREIGN KEY (`purchase_order_id`) REFERENCES `su_purchase_orders` (`id`),
  ADD CONSTRAINT `fk_54cd81248c8448f72a2c` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a936001395427a537e29` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_d285a8975317462b74a2` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_e4fa1623da1d0d897845` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_receipts`
--
ALTER TABLE `su_receipts`
  ADD CONSTRAINT `fk_9a00a1aa639933095a78` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`),
  ADD CONSTRAINT `fk_bb3931a9023e635ab4bf` FOREIGN KEY (`payment_id`) REFERENCES `su_payment_methods` (`id`),
  ADD CONSTRAINT `fk_d1ed554c183bae40ce88` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_d2330097f9e202f1af93` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_returns_refunds`
--
ALTER TABLE `su_returns_refunds`
  ADD CONSTRAINT `fk_151b9ebaa5d81e4cee87` FOREIGN KEY (`sale_item_id`) REFERENCES `su_sale_items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_195636c8d4fe2fc60091` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_6b29593d145e8f074534` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_95ae95945f8fa9caaff4` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_c05a5a3dbae139336795` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`);

--
-- Constraints for table `su_role_permissions`
--
ALTER TABLE `su_role_permissions`
  ADD CONSTRAINT `su_role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `su_permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `su_role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `su_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `su_sales`
--
ALTER TABLE `su_sales`
  ADD CONSTRAINT `fk_27d3f09aab24bc1f0742` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_3127d50434f3a9c6731c` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_38941e5548e33c68a481` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_988e8a84dc8071a38037` FOREIGN KEY (`customer_id`) REFERENCES `su_customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a5d2f278e814e7f50aed` FOREIGN KEY (`shift_id`) REFERENCES `su_shifts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_sale_items`
--
ALTER TABLE `su_sale_items`
  ADD CONSTRAINT `fk_02cd25831720c86174d3` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_409705d77f7041e9192b` FOREIGN KEY (`batch_id`) REFERENCES `su_stock_batches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_57de94ab0d59cf1fef1c` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_7c3c39b6d6c211d61a1d` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`),
  ADD CONSTRAINT `fk_a4dcd8fe4d04103758a0` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bffa77f101a21a73d6c0` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_sale_payment_allocations`
--
ALTER TABLE `su_sale_payment_allocations`
  ADD CONSTRAINT `fk_a8d41dedbd3986a39329` FOREIGN KEY (`sale_id`) REFERENCES `su_sales` (`id`),
  ADD CONSTRAINT `fk_bca763477d56f67292df` FOREIGN KEY (`payment_method_id`) REFERENCES `su_payment_methods` (`id`);

--
-- Constraints for table `su_shifts`
--
ALTER TABLE `su_shifts`
  ADD CONSTRAINT `fk_9073fe7546b0940b4f80` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_b70c9faea7856dc24e9d` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_f95c81ab0d1957867542` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_staff`
--
ALTER TABLE `su_staff`
  ADD CONSTRAINT `fk_8e4dc3d5e99d80bf0005` FOREIGN KEY (`user_id`) REFERENCES `su_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_staff_leave`
--
ALTER TABLE `su_staff_leave`
  ADD CONSTRAINT `fk_32e568079969701aba28` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_4601764e9651da3c02e6` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_98cc692c379fa592eb8a` FOREIGN KEY (`logged_by_staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_b0092f38398263b42f08` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_stock_adjustments`
--
ALTER TABLE `su_stock_adjustments`
  ADD CONSTRAINT `fk_309b32bc3927c35a31ea` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_3f35ad9a2f4c32247bbc` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_6d262511e244d93d2244` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_74e273ad77f959d4d77d` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_caeebfb9ecb0aeb2a17e` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_stock_batches`
--
ALTER TABLE `su_stock_batches`
  ADD CONSTRAINT `fk_0bf71fd837613aa59ef0` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_1824f451483e95de7ec5` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a84a74570168466ffed8` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_e2c7279d23f3244a7df4` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_stock_counts`
--
ALTER TABLE `su_stock_counts`
  ADD CONSTRAINT `fk_1f79ba427f256690ddb1` FOREIGN KEY (`completed_by_staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_344f6a5f1ec95d821239` FOREIGN KEY (`started_by_staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_65bc4484f2612811118d` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_stock_count_items`
--
ALTER TABLE `su_stock_count_items`
  ADD CONSTRAINT `fk_2bf754589cf5f1b04ff9` FOREIGN KEY (`batch_id`) REFERENCES `su_stock_batches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_4085c2be656e836a743c` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_409850b30a02ebe02c1a` FOREIGN KEY (`stock_count_id`) REFERENCES `su_stock_counts` (`id`),
  ADD CONSTRAINT `fk_80f524933a476e86bf6f` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`);

--
-- Constraints for table `su_stock_movements`
--
ALTER TABLE `su_stock_movements`
  ADD CONSTRAINT `fk_6702a939812628cc343d` FOREIGN KEY (`batch_id`) REFERENCES `su_stock_batches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_67257c0dc0c5ee7efdc5` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_7d398b6653e2c9e5846a` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_80422ac4d14fe034a834` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`),
  ADD CONSTRAINT `fk_8e2b472e59e3001a6967` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_stock_transfers`
--
ALTER TABLE `su_stock_transfers`
  ADD CONSTRAINT `fk_2e4eab6b14c69f01b883` FOREIGN KEY (`requested_by_staff_id`) REFERENCES `su_staff` (`id`),
  ADD CONSTRAINT `fk_3703a3a6e5019f582a66` FOREIGN KEY (`from_branch_id`) REFERENCES `su_branches` (`id`),
  ADD CONSTRAINT `fk_4a6f565236592c45edd7` FOREIGN KEY (`to_branch_id`) REFERENCES `su_branches` (`id`),
  ADD CONSTRAINT `fk_670646eef5eb24c03a6a` FOREIGN KEY (`approved_by_staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_stock_transfer_items`
--
ALTER TABLE `su_stock_transfer_items`
  ADD CONSTRAINT `fk_74eaa2d4dd70336c98d6` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`),
  ADD CONSTRAINT `fk_a8299560ad22fc15d9ae` FOREIGN KEY (`stock_transfer_id`) REFERENCES `su_stock_transfers` (`id`),
  ADD CONSTRAINT `fk_c204d45ccce13f59d122` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_suppliers`
--
ALTER TABLE `su_suppliers`
  ADD CONSTRAINT `fk_3ab10ed3d7c6bc9805e6` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_9055724229da601d0ffb` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_supplier_transactions`
--
ALTER TABLE `su_supplier_transactions`
  ADD CONSTRAINT `fk_19746caf8d216ae600d0` FOREIGN KEY (`supplier_id`) REFERENCES `su_suppliers` (`id`),
  ADD CONSTRAINT `fk_4f33e164e51ed71c028b` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_7ba2318b662fbe2bd41f` FOREIGN KEY (`staff_id`) REFERENCES `su_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_903a447488fad81d4127` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_d86a4437d3724018096c` FOREIGN KEY (`purchase_order_id`) REFERENCES `su_purchase_orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_system_logs`
--
ALTER TABLE `su_system_logs`
  ADD CONSTRAINT `su_system_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `su_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_units_of_measure`
--
ALTER TABLE `su_units_of_measure`
  ADD CONSTRAINT `fk_05b8785c66ba4f6460d7` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_0b81605f272efd8aaf02` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_users`
--
ALTER TABLE `su_users`
  ADD CONSTRAINT `su_users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `su_users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `su_user_branches`
--
ALTER TABLE `su_user_branches`
  ADD CONSTRAINT `fk_2ea172b0b7146a3b9f5c` FOREIGN KEY (`user_id`) REFERENCES `su_users` (`id`),
  ADD CONSTRAINT `fk_ccf3b04cb88082c1f33a` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`);

--
-- Constraints for table `su_user_profiles`
--
ALTER TABLE `su_user_profiles`
  ADD CONSTRAINT `su_user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `su_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `su_user_roles`
--
ALTER TABLE `su_user_roles`
  ADD CONSTRAINT `su_user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `su_roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `su_user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `su_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `su_wholesale_pricing`
--
ALTER TABLE `su_wholesale_pricing`
  ADD CONSTRAINT `fk_69064a706a2cb50a1334` FOREIGN KEY (`business_id`) REFERENCES `su_businesses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_78db3bf7c6dc376449e3` FOREIGN KEY (`branch_id`) REFERENCES `su_branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_8e72acd4ff6f9cb1743e` FOREIGN KEY (`variant_id`) REFERENCES `su_product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_a810c949fb86908960a9` FOREIGN KEY (`customer_id`) REFERENCES `su_customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_eb5a3219bf348638d8d7` FOREIGN KEY (`product_id`) REFERENCES `su_products` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
