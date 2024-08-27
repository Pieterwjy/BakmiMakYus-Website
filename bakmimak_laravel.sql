-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 01, 2024 at 10:54 AM
-- Server version: 8.0.37-cll-lve
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bakmimak_laravel`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`bakmimak`@`localhost` PROCEDURE `revert_stock` (IN `orderId` INT)   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE productId INT;
    DECLARE orderQty INT;
    DECLARE cur CURSOR FOR
        SELECT product_id, order_qty
        FROM order_details
        WHERE order_id = orderId;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO productId, orderQty;
        IF done THEN
            LEAVE read_loop;
        END IF;
        UPDATE products
        SET product_stock = product_stock + orderQty
        WHERE id = productId;
    END LOOP;

    CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_04_23_023231_create_tables_table', 1),
(6, '2024_04_23_040221_create_products_table', 1),
(7, '2024_04_26_021316_create_orders_table', 2),
(8, '2024_04_26_051351_create_order_details_table', 3),
(9, '2024_05_04_020715_trigger_update_cash_payment_status', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `table_number` int NOT NULL,
  `order_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_amount` double(12,0) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cashier` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snap_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `table_number`, `order_type`, `notes`, `order_status`, `gross_amount`, `status`, `cashier`, `snap_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Dine-In', NULL, 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-06-19 20:51:39', '2024-06-19 22:07:48'),
(2, 1, 'Dine-In', NULL, NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-19 20:53:02', '2024-06-19 20:53:05'),
(3, 1, 'Dine-In', NULL, NULL, 760000, 'Cancelled', NULL, NULL, '2024-06-19 21:03:53', '2024-06-19 21:04:06'),
(4, 0, 'Dine-In', NULL, NULL, 3800000, 'Expired', NULL, NULL, '2024-06-19 21:15:54', '2024-06-19 21:32:02'),
(5, 0, 'Dine-In', NULL, NULL, 3800000, 'Cancelled', NULL, NULL, '2024-06-19 21:25:30', '2024-06-19 21:25:30'),
(6, 0, 'Dine-In', NULL, NULL, 3838000, 'Expired', NULL, NULL, '2024-06-19 21:32:28', '2024-06-19 21:32:28'),
(7, 0, 'Dine-In', NULL, NULL, 7600000, 'Cancelled', NULL, NULL, '2024-06-19 21:35:52', '2024-06-19 21:46:58'),
(8, 0, 'Dine-In', NULL, NULL, 7600000, 'Cancelled', NULL, NULL, '2024-06-19 21:48:19', '2024-06-19 21:59:28'),
(9, 0, 'Dine-In', NULL, NULL, 7600000, 'Cancelled', NULL, NULL, '2024-06-19 22:06:01', '2024-06-19 22:07:22'),
(10, 0, 'Dine-In', NULL, NULL, 7600000, 'Cancelled', NULL, NULL, '2024-06-19 22:11:09', '2024-06-19 22:12:23'),
(11, 0, 'Dine-In', NULL, 'Selesai', 7600000, 'Paid By Cash', 'Admin', NULL, '2024-06-19 22:12:50', '2024-06-19 22:28:59'),
(12, 0, 'Dine-In', NULL, NULL, 38000, 'Expired', NULL, NULL, '2024-06-19 22:33:12', '2024-06-19 22:56:03'),
(13, 1, 'Dine-In', NULL, NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-19 22:34:09', '2024-06-19 22:44:31'),
(14, 1, 'Dine-In', NULL, NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-19 22:46:09', '2024-06-19 22:50:13'),
(15, 1, 'Dine-In', 'Nama: Pieter', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-19 22:50:22', '2024-06-19 22:51:11'),
(16, 1, 'Dine-In', 'Pieter. \r\nalkdhsalkdjaslkd jaslkdjsakldj', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-19 22:51:21', '2024-06-19 22:57:17'),
(17, 1, 'Takeaway', 'a.n. Its free for me,', NULL, 38000, NULL, NULL, NULL, '2024-06-20 19:42:58', '2024-06-20 19:42:58'),
(18, 1, 'Takeaway', 'a.n. Its free for me, \r\na.n. Its free for me,', NULL, 38000, NULL, NULL, NULL, '2024-06-20 19:43:13', '2024-06-20 19:43:13'),
(19, 2, 'Dine-In', 'a.n. Hayo,', NULL, 39000, 'Expired', NULL, NULL, '2024-06-20 19:45:01', '2024-06-20 20:00:07'),
(20, 2, 'Dine-In', 'a.n. Gratis,', NULL, 39000, NULL, NULL, NULL, '2024-06-20 19:45:55', '2024-06-20 19:45:55'),
(21, 1, 'Dine-In', 'a.n. Pieter, \r\nPedas', NULL, 114000, 'Cancelled', NULL, NULL, '2024-06-21 00:55:48', '2024-06-21 00:56:18'),
(22, 1, 'Dine-In', 'a.n. Pieter, \r\nPedas', 'Selesai', 114000, 'Paid By Cash', 'Admin', NULL, '2024-06-21 00:57:03', '2024-06-28 11:04:23'),
(23, 1, 'Dine-In', 'a.n. Pieter,', 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-06-21 01:17:00', '2024-06-28 11:04:24'),
(24, 1, 'Dine-In', 'a.n. Pieter, \r\na.n. Pieter,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-21 01:17:50', '2024-06-21 01:18:21'),
(25, 1, 'Dine-In', 'a.n. pieter,', 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-06-21 01:18:56', '2024-06-28 11:04:25'),
(26, 1, 'Dine-In', 'a.n. pieter, \r\na.n. pieter,', NULL, 38000, 'Expired', NULL, NULL, '2024-06-21 01:19:20', '2024-06-21 01:40:02'),
(27, 1, 'Dine-In', 'a.n. test,', NULL, 38000, 'Expired', NULL, NULL, '2024-06-21 01:25:59', '2024-06-21 01:48:02'),
(28, 6, 'Dine-In', 'a.n. Elon Musk, \r\ni just want to clear your stock', 'Selesai', 1634000, 'Settlement', 'Admin', '34a767af-a8f8-4fc6-82f7-65d572ee247d', '2024-06-23 21:38:59', '2024-07-02 09:48:24'),
(29, 1, 'Dine-In', 'a.n. test,', NULL, 76000, 'Expired', NULL, NULL, '2024-06-25 22:06:40', '2024-06-25 22:24:03'),
(30, 1, 'Dine-In', 'a.n. test,', 'Selesai', 114000, 'Paid By Cash', 'Admin', NULL, '2024-06-25 22:11:31', '2024-07-03 19:13:59'),
(31, 1, 'Dine-In', 'a.n. test, \r\na.n. test,', NULL, 114000, 'Cancelled', NULL, NULL, '2024-06-25 22:12:07', '2024-06-25 22:28:45'),
(32, 1, 'Dine-In', 'a.n. test,', 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-06-25 22:29:22', '2024-07-03 19:14:00'),
(33, 1, 'Dine-In', 'a.n. test,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-25 22:31:16', '2024-06-25 22:49:21'),
(34, 1, 'Dine-In', 'a.n. sda,', NULL, 38000, 'Expired', NULL, NULL, '2024-06-25 22:56:16', '2024-06-25 23:16:02'),
(35, 1, 'Takeaway', 'a.n. sad,', NULL, 76000, NULL, NULL, NULL, '2024-06-25 23:45:38', '2024-06-25 23:45:38'),
(36, 1, 'Dine-In', 'a.n. asdsa,', NULL, 76000, 'Expired', NULL, NULL, '2024-06-25 23:46:03', '2024-06-26 00:08:02'),
(37, 1, 'Dine-In', 'a.n. Pit,', NULL, 38000, NULL, NULL, NULL, '2024-06-25 23:51:20', '2024-06-25 23:51:20'),
(38, 1, 'Dine-In', 'a.n. Res,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-25 23:51:43', '2024-06-25 23:51:48'),
(39, 1, 'Dine-In', 'a.n. Fu,', NULL, 38000, NULL, NULL, NULL, '2024-06-25 23:51:55', '2024-06-25 23:51:55'),
(300, 1, 'Dine-In', 'a.n. Fu,', NULL, 38000, NULL, NULL, NULL, '2024-06-25 23:51:55', '2024-06-25 23:51:55'),
(301, 1, 'Dine-In', 'a.n. sad,', NULL, 76000, 'Cancelled', NULL, '9932d1d1-3758-46bd-a29f-39bd3048ded3', '2024-06-26 00:04:22', '2024-06-26 00:04:38'),
(302, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, 'e836404b-05b2-4a56-9ca5-b3732f2b4952', '2024-06-26 00:05:44', '2024-06-26 00:06:26'),
(303, 1, 'Dine-In', 'a.n. asd,', NULL, 114000, 'Cancelled', NULL, NULL, '2024-06-26 00:09:18', '2024-06-26 00:09:29'),
(304, 1, 'Dine-In', 'a.n. Tcc,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-26 00:20:31', '2024-06-26 00:21:18'),
(305, 1, 'Dine-In', 'a.n. Gg,', NULL, 38000, 'Expired', NULL, '400eda2f-a2b8-4422-b929-bbe412763bee', '2024-06-26 00:20:42', '2024-06-26 00:40:03'),
(306, 1, 'Dine-In', 'a.n. Tes,', NULL, 114000, 'Cancelled', NULL, NULL, '2024-06-27 00:34:20', '2024-06-27 00:35:42'),
(307, 1, 'Dine-In', 'a.n. 777,', NULL, 114000, 'Expired', NULL, 'b390caff-dcce-4c8f-bd6f-2455368c0ca5', '2024-06-27 00:36:16', '2024-06-27 00:56:06'),
(308, 1, 'Dine-In', 'a.n. 777, \r\na.n. 777,', NULL, 114000, 'Expired', NULL, '4844e9b7-7612-44d5-b69f-5f84bf814470', '2024-06-27 00:36:17', '2024-06-27 00:56:06'),
(309, 1, 'Dine-In', 'a.n. 777, \r\na.n. 777, \r\na.n. 777,', NULL, 114000, 'Expired', NULL, '006fa2e1-306b-4d8f-96e9-943daa8e38b4', '2024-06-27 00:36:18', '2024-06-27 00:56:06'),
(310, 1, 'Dine-In', 'a.n. asd,', NULL, 114000, 'Cancelled', NULL, NULL, '2024-06-27 16:51:24', '2024-06-27 16:51:31'),
(311, 1, 'Dine-In', 'a.n. asd,', NULL, 153000, 'Cancelled', NULL, NULL, '2024-06-27 17:12:03', '2024-06-27 17:12:19'),
(312, 1, 'Dine-In', 'a.n. Pieter, \r\nasd', NULL, 427000, 'Expired', NULL, NULL, '2024-06-28 09:56:57', '2024-06-28 10:16:02'),
(313, 1, 'Dine-In', 'a.n. test, \r\nasdasd', 'Selesai', 770000, 'Settlement', 'Admin', '22759f97-3e85-4518-8568-08f9a10ced15', '2024-06-28 10:08:37', '2024-07-03 19:14:00'),
(314, 1, 'Dine-In', 'a.n. asd, \r\nasd', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-28 10:10:41', '2024-06-28 10:10:44'),
(315, 1, 'Dine-In', 'a.n. sad,', NULL, 38000, NULL, NULL, NULL, '2024-06-28 10:20:25', '2024-06-28 10:20:25'),
(316, 1, 'Dine-In', 'a.n. asd, \r\nsad', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-28 10:20:53', '2024-06-28 10:26:58'),
(317, 1, 'Dine-In', 'a.n. sad,', NULL, 38000, 'Cancelled', NULL, 'a306ca0d-b340-4465-867e-fbfbbc7a9d24', '2024-06-28 10:21:08', '2024-06-28 10:21:48'),
(318, 1, 'Dine-In', 'a.n. asd,', NULL, 38000, 'Cancelled', NULL, 'fff7beaa-e2ac-4d78-9bb6-71c3f2606343', '2024-06-28 10:27:09', '2024-06-28 10:27:15'),
(319, 1, 'Dine-In', 'a.n. sad,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-28 10:29:15', '2024-06-28 10:34:43'),
(320, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:38:24', '2024-06-28 10:38:35'),
(321, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:38:50', '2024-06-28 10:39:00'),
(322, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:39:29', '2024-06-28 10:42:29'),
(323, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:42:54', '2024-06-28 10:43:03'),
(324, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:46:06', '2024-06-28 10:46:19'),
(325, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:48:14', '2024-06-28 10:48:30'),
(326, 1, 'Dine-In', 'a.n. sad,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:48:42', '2024-06-28 10:48:51'),
(327, 1, 'Dine-In', 'a.n. sad,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:49:01', '2024-06-28 10:49:18'),
(328, 1, 'Dine-In', 'a.n. sad,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:53:26', '2024-06-28 10:53:53'),
(329, 1, 'Dine-In', 'a.n. sad,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:54:41', '2024-06-28 10:54:50'),
(330, 1, 'Dine-In', 'a.n. asd,', NULL, 76000, 'Cancelled', NULL, NULL, '2024-06-28 10:55:06', '2024-06-28 10:55:13'),
(331, 2, 'Dine-In', 'a.n. Ags,', NULL, 4096000, 'Cancelled', NULL, NULL, '2024-06-28 10:56:14', '2024-06-28 10:56:28'),
(332, 1, 'Dine-In', 'a.n. asd,', 'Selesai', 202000, 'Paid By Cash', 'Admin', NULL, '2024-06-28 11:03:29', '2024-07-03 19:14:06'),
(333, 1, 'Dine-In', 'a.n. asd, \r\nsad', 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-06-28 11:11:27', '2024-07-03 19:14:07'),
(334, 2, 'Dine-In', 'a.n. Gsh,', NULL, 3982000, 'Cancelled', NULL, NULL, '2024-06-28 11:18:39', '2024-06-28 11:18:46'),
(335, 2, 'Dine-In', 'a.n. Su,', NULL, 3982000, 'Cancelled', NULL, NULL, '2024-06-28 11:18:51', '2024-06-28 11:18:55'),
(336, 1, 'Dine-In', NULL, NULL, 38000, 'Cancelled', NULL, NULL, '2024-06-29 01:04:03', '2024-06-29 01:07:15'),
(337, 0, 'Dine-In', NULL, NULL, 76000, 'Expired', NULL, NULL, '2024-06-29 01:07:43', '2024-06-29 01:27:02'),
(338, 0, 'Dine-In', NULL, NULL, 114000, 'Cancelled', NULL, NULL, '2024-06-29 01:08:36', '2024-06-29 01:09:52'),
(339, 0, 'Dine-In', NULL, NULL, 152000, 'Expired', NULL, NULL, '2024-06-29 01:10:03', '2024-06-29 01:27:02'),
(340, 0, 'Dine-In', NULL, 'Selesai', 114000, 'Paid By Cash', 'Admin', NULL, '2024-06-29 01:16:33', '2024-07-03 19:14:08'),
(341, 3, 'Takeaway', 'a.n. Pieter, \r\nEnak Pol', NULL, 3238000, 'Cancelled', NULL, NULL, '2024-06-30 04:48:35', '2024-06-30 04:48:54'),
(342, 0, 'Takeaway', 'a.n. ASD, \r\nSip', NULL, 3238000, 'Expired', NULL, '1125a205-857c-40b4-aeca-36743755037a', '2024-06-30 04:49:43', '2024-06-30 05:09:02'),
(343, 0, 'Dine-In', 'a.n. asd,', NULL, 3238000, 'Cancelled', NULL, NULL, '2024-06-30 05:13:27', '2024-06-30 05:13:53'),
(344, 0, 'Takeaway', 'a.n. Test, \r\nasdsadsa', NULL, 1170000, 'Cancelled', NULL, 'cb76295b-50ba-424b-be39-c182944e6b15', '2024-06-30 05:14:52', '2024-06-30 05:24:56'),
(345, 0, 'Dine-In', 'a.n. asd,', NULL, 1170000, 'Expired', NULL, '53c412af-2ee2-4ae1-8c74-470c3e0c2d02', '2024-06-30 05:25:40', '2024-06-30 05:45:03'),
(346, 0, 'Dine-In', 'a.n. Test,', NULL, 1170000, 'Cancelled', NULL, '5f6a2bfa-4da2-45a0-907e-5c0a151307a4', '2024-06-30 05:54:45', '2024-06-30 05:56:02'),
(347, 0, 'Dine-In', 'a.n. asd,', 'Selesai', 2698000, 'Paid By Cash', 'Admin', NULL, '2024-06-30 19:36:12', '2024-07-03 19:14:05'),
(348, 1, 'Dine-In', 'a.n. Abc,', NULL, 129000, 'Cancelled', NULL, NULL, '2024-06-30 22:48:55', '2024-06-30 22:48:59'),
(349, 1, 'Dine-In', 'a.n. test, \r\nasd', 'Selesai', 39000, 'Paid By Cash', 'Admin', NULL, '2024-07-01 22:42:43', '2024-07-03 19:14:08'),
(350, 1, 'Dine-In', 'a.n. nbv,', NULL, 38000, 'Expired', NULL, '21a77110-6a44-42cb-aee0-22f85e7668c8', '2024-07-01 22:46:36', '2024-07-01 23:09:02'),
(351, 1, 'Dine-In', 'a.n. asd,', 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-07-01 23:02:27', '2024-07-03 19:14:04'),
(352, 1, 'Dine-In', 'a.n. sad,', NULL, 38000, 'Expired', NULL, NULL, '2024-07-01 23:10:43', '2024-07-01 23:27:02'),
(353, 1, 'Dine-In', 'a.n. zxc,', NULL, 38000, 'Expired', NULL, 'f46402b6-df8e-45c7-ab1f-06d4aa8ee54b', '2024-07-01 23:16:53', '2024-07-01 23:36:03'),
(354, 0, 'Dine-In', 'a.n. asd,', NULL, 42000, 'Cancelled', NULL, NULL, '2024-07-01 23:53:48', '2024-07-01 23:54:23'),
(355, 0, 'Dine-In', 'a.n. sad,', NULL, 80000, 'Expired', NULL, '368f575a-261b-47e3-8d48-0db359f61c36', '2024-07-01 23:56:53', '2024-07-02 00:18:03'),
(356, 1, 'Dine-In', 'a.n. Test,', NULL, 342000, 'Expired', NULL, NULL, '2024-07-02 00:48:35', '2024-07-02 01:04:19'),
(357, 1, 'Dine-In', 'a.n. asd,', NULL, 381000, 'Expired', NULL, NULL, '2024-07-02 03:48:26', '2024-07-02 04:04:21'),
(358, 1, 'Dine-In', 'a.n. asd,', NULL, 381000, 'Cancelled', NULL, NULL, '2024-07-02 09:50:23', '2024-07-02 09:52:28'),
(359, 1, 'Dine-In', 'a.n. test,', NULL, 381000, 'Cancelled', NULL, '2f645e8e-c854-44b1-b59f-13b6de135ef8', '2024-07-02 09:54:09', '2024-07-02 09:56:51'),
(360, 0, 'Dine-In', 'a.n. ABC,', 'Selesai', 90000, 'Paid By Cash', 'Admin', NULL, '2024-07-02 10:16:38', '2024-07-03 19:14:09'),
(361, 0, 'Takeaway', 'a.n. asd,', 'Selesai', 90000, 'Paid by Cash', 'Admin', NULL, '2024-07-02 10:19:53', '2024-07-02 10:19:55'),
(362, 1, 'Dine-In', 'a.n. Pieter,', NULL, 38000, 'Expired', NULL, NULL, '2024-07-02 19:41:46', '2024-07-02 19:56:47'),
(363, 1, 'Dine-In', 'a.n. Hardy,', NULL, 77000, 'Cancelled', NULL, NULL, '2024-07-03 17:33:48', '2024-07-03 17:34:39'),
(364, 1, 'Dine-In', 'a.n. Fanny, \r\nga pake tempe', NULL, 81000, 'Expired', NULL, '35e4dcec-ed9c-4e70-b60a-58e909bd1b96', '2024-07-03 18:43:50', '2024-07-03 18:59:02'),
(365, 1, 'Dine-In', 'a.n. Hardy,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-07-03 19:09:15', '2024-07-03 19:09:31'),
(366, 1, 'Dine-In', 'a.n. Hardy,', 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-07-03 19:10:02', '2024-07-03 19:14:03'),
(367, 1, 'Takeaway', 'a.n. Melisa,', '', 38000, 'Cancelled', 'Admin', NULL, '2024-07-03 19:20:19', '2024-07-03 19:20:27'),
(368, 1, 'Dine-In', 'a.n. test, \r\ntest', NULL, 76000, 'Cancelled', NULL, 'dc3ec643-1eb2-4ef4-986e-9744b257f024', '2024-07-03 19:25:27', '2024-07-03 19:45:23'),
(369, 1, 'Dine-In', 'a.n. test,', NULL, 76000, 'Expired', NULL, '0b8be619-f2c9-45c9-960d-008112205ff4', '2024-07-03 19:38:40', '2024-07-03 19:54:04'),
(370, 1, 'Takeaway', 'a.n. test,', NULL, 38000, 'Expired', NULL, '51cc987a-a81b-44fc-803f-1f38b7499f56', '2024-07-03 19:45:45', '2024-07-03 20:00:52'),
(371, 1, 'Dine-In', 'a.n. Alfredo,', NULL, 38000, 'Expired', NULL, '7f04b72d-ff33-4213-8649-4a64d5a904b6', '2024-07-03 20:30:25', '2024-07-03 20:46:46'),
(372, 1, 'Dine-In', 'a.n. Jdbd,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-07-03 20:31:13', '2024-07-03 20:31:21'),
(373, 1, 'Takeaway', 'a.n. Antonius B W, \r\nTester', NULL, 119000, 'Cancelled', NULL, 'ba88d2d4-2c44-41f9-9689-9df7b2a4a80e', '2024-07-03 21:12:12', '2024-07-03 21:13:02'),
(374, 1, 'Dine-In', 'a.n. Anton Dev, \r\nTest', NULL, 38000, 'Expired', NULL, NULL, '2024-07-03 21:13:51', '2024-07-03 21:36:03'),
(375, 1, 'Dine-In', 'a.n. Kristiawan,', NULL, 83000, 'Expired', NULL, NULL, '2024-07-03 22:25:03', '2024-07-03 22:40:22'),
(376, 1, 'Dine-In', 'a.n. Kristiawan,', NULL, 121000, 'Cancelled', NULL, NULL, '2024-07-03 22:25:54', '2024-07-03 22:40:15'),
(377, 1, 'Dine-In', 'a.n. Joshua, \r\nTes', NULL, 77000, 'Expired', NULL, NULL, '2024-07-04 00:09:51', '2024-07-04 00:27:03'),
(378, 1, 'Dine-In', 'a.n. Test,', NULL, 38000, 'Cancelled', NULL, NULL, '2024-07-04 12:09:04', '2024-07-04 12:09:08'),
(379, 1, 'Dine-In', 'a.n. Test,', NULL, 38000, 'Expired', NULL, '74154040-406b-486a-9cd5-89e6c25992ea', '2024-07-04 12:09:13', '2024-07-04 12:27:03'),
(380, 1, 'Dine-In', 'a.n. Wusj,', NULL, 38000, 'Expired', NULL, NULL, '2024-07-05 04:14:21', '2024-07-05 04:36:02'),
(381, 5, 'Takeaway', 'a.n. Toto closet, \r\nTolong sambal dipisah, terimakasih', 'Selesai', 38000, 'Paid By Cash', 'Admin', NULL, '2024-07-05 06:13:12', '2024-07-10 01:08:18'),
(382, 1, 'Takeaway', 'a.n. Kirim ke rumah,', 'Selesai', 380, 'Settlement', 'Admin', 'b8af54a4-0465-4a02-8a16-c47ad2be33f2', '2024-07-07 22:36:51', '2024-07-10 01:08:29'),
(383, 1, 'Dine-In', 'a.n. asd,', NULL, 21000000, 'Cancelled', NULL, NULL, '2024-07-10 00:56:47', '2024-07-10 00:57:23'),
(384, 1, 'Dine-In', 'a.n. asdsad,', NULL, 20916000, 'Cancelled', NULL, NULL, '2024-07-10 01:03:04', '2024-07-10 01:03:12'),
(385, 0, 'Takeaway', 'a.n. test,', 'Selesai', 39000, 'Paid By Cash', 'Admin', NULL, '2024-07-10 09:01:15', '2024-07-10 09:02:07'),
(386, 1, 'Dine-In', 'a.n. test,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-10 09:52:02', '2024-07-10 09:52:10'),
(387, 1, 'Dine-In', 'a.n. Pieter,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-10 19:17:34', '2024-07-10 19:19:20'),
(388, 0, 'Takeaway', 'a.n. ikado, \r\npedas', 'Selesai', 40000, 'Paid By Cash', 'Admin', NULL, '2024-07-10 19:19:44', '2024-07-10 19:22:30'),
(389, 0, 'Takeaway', 'a.n. Test,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-10 19:21:22', '2024-07-10 19:22:16'),
(390, 3, 'Dine-In', 'a.n. Pak Alex, \r\nPedas Banget', NULL, 220000, 'Cancelled', NULL, NULL, '2024-07-10 19:26:14', '2024-07-10 19:26:31'),
(391, 3, 'Dine-In', 'a.n. Pak Alex, \r\nSangat Pedas', 'Selesai', 200000, 'Paid By Cash', 'Admin', NULL, '2024-07-10 19:27:15', '2024-07-10 19:32:39'),
(392, 3, 'Dine-In', 'a.n. Pak Eddy,', NULL, 30000, 'Expired', NULL, '4cea7178-2eb2-4d2b-a589-25f49e3111a7', '2024-07-10 19:44:48', '2024-07-10 20:00:08'),
(393, 3, 'Dine-In', 'a.n. Pak Eddy 2,', NULL, 10000, 'Expired', NULL, 'a6fbfe70-bb86-4246-88fb-e66a87c6b343', '2024-07-10 19:46:13', '2024-07-10 20:02:28'),
(394, 0, 'Takeaway', 'a.n. Ambil Kasir,', 'Selesai', 10000, 'Paid By Cash', 'Admin', NULL, '2024-07-10 19:57:43', '2024-07-16 20:35:06'),
(395, 1, 'Takeaway', 'a.n. Test,', 'Diteruskan Ke Koki', 25000, 'Paid By Cash', 'Admin', NULL, '2024-07-14 19:24:04', '2024-07-14 19:24:51'),
(396, 0, 'Takeaway', 'a.n. Test,', 'Diteruskan Ke Koki', 25000, 'Paid By Cash', 'Admin', NULL, '2024-07-14 19:31:42', '2024-07-14 19:31:46'),
(397, 0, 'Takeaway', 'a.n. test,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 19:42:29', '2024-07-14 19:46:31'),
(398, 0, 'Takeaway', 'a.n. test,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 19:47:40', '2024-07-14 19:47:44'),
(399, 0, 'Takeaway', 'a.n. test,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 19:48:00', '2024-07-14 19:48:07'),
(400, 0, 'Takeaway', 'a.n. asd,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 19:49:32', '2024-07-14 19:49:39'),
(401, 0, 'Takeaway', 'a.n. asd,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 19:51:00', '2024-07-14 19:51:21'),
(402, 0, 'Takeaway', 'a.n. test,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 19:52:55', '2024-07-14 19:53:11'),
(403, 0, 'Takeaway', 'a.n. asd,', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-14 19:55:13', '2024-07-14 19:55:22'),
(404, 0, 'Takeaway', 'a.n. sad,', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-14 19:55:50', '2024-07-14 19:55:55'),
(405, 0, 'Takeaway', 'a.n. sad,', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-14 19:58:12', '2024-07-14 19:58:15'),
(406, 0, 'Takeaway', 'a.n. test,', 'Diteruskan Ke Koki', 25000, 'Paid By Cash', 'Admin', NULL, '2024-07-14 19:59:26', '2024-07-14 19:59:32'),
(407, 0, 'Dine-In', 'a.n. asd,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 19:59:43', '2024-07-14 20:00:48'),
(408, 0, 'Takeaway', 'a.n. zxc,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 20:00:18', '2024-07-14 20:06:19'),
(409, 0, 'Dine-In', 'a.n. sad,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-14 20:01:18', '2024-07-14 20:06:17'),
(410, 0, 'Dine-In', 'a.n. sad,', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-14 20:05:58', '2024-07-14 20:06:03'),
(411, 0, 'Dine-In', 'a.n. sad, \r\nsad', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-14 20:06:28', '2024-07-14 20:06:36'),
(412, 0, 'Takeaway', 'a.n. pieter, \r\ntest', NULL, 50000, 'Expired', NULL, NULL, '2024-07-14 20:39:18', '2024-07-14 21:00:15'),
(413, 0, 'Takeaway', 'a.n. pieter, \r\ntest lah', NULL, 50000, 'Expired', NULL, NULL, '2024-07-14 20:47:59', '2024-07-14 21:09:02'),
(414, 0, 'Dine-In', 'a.n. doni, \r\ndont pakai sambal', NULL, 50000, 'Expired', NULL, NULL, '2024-07-14 20:52:47', '2024-07-14 21:09:02'),
(415, 0, 'Takeaway', 'a.n. pieter, \r\ntest lah', NULL, 50000, 'Expired', NULL, NULL, '2024-07-14 21:35:33', '2024-07-14 21:51:55'),
(416, 0, 'Takeaway', 'a.n. pieter, \r\ntest lah', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-14 21:52:06', '2024-07-14 21:52:12'),
(417, 0, 'Takeaway', 'a.n. pieter, \r\ntest lah', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-14 21:52:18', '2024-07-14 21:52:35'),
(418, 0, 'Takeaway', 'a.n. pieter, \r\ntest lah', NULL, 75000, 'Expired', NULL, NULL, '2024-07-14 21:55:43', '2024-07-14 22:18:04'),
(419, 1, 'Dine-In', 'a.n. Jasmine, \r\nAgak asin', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-16 06:24:01', '2024-07-16 06:24:27'),
(420, 1, 'Dine-In', 'a.n. Jasmine, \r\nAgak asin', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-16 06:24:40', '2024-07-16 06:24:44'),
(421, 0, 'Takeaway', 'a.n. Test,', 'Diteruskan Ke Koki', 25000, 'Paid By Cash', 'Admin', NULL, '2024-07-16 19:01:41', '2024-07-16 19:01:51'),
(422, 2, 'Dine-In', 'a.n. Test,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-16 19:06:45', '2024-07-16 19:07:14'),
(423, 2, 'Dine-In', 'a.n. Test,', NULL, 50000, 'Cancelled', NULL, NULL, '2024-07-16 19:12:03', '2024-07-16 20:56:44'),
(424, 1, 'Dine-In', 'a.n. Tes,', NULL, 4850000, 'Cancelled', NULL, NULL, '2024-07-16 19:41:13', '2024-07-16 19:41:36'),
(425, 2, 'Dine-In', 'a.n. Test, \r\nTesting', NULL, 25000, 'Expired', NULL, NULL, '2024-07-16 20:58:19', '2024-07-16 21:18:05'),
(426, 1, 'Dine-In', 'a.n. Tes,', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-21 19:42:25', '2024-07-21 19:42:38'),
(427, 0, 'Takeaway', 'a.n. Test, \r\nTesting', NULL, 25000, 'Cancelled', NULL, NULL, '2024-07-21 21:20:13', '2024-07-21 21:20:37'),
(428, 1, 'Dine-In', 'a.n. Tes, \r\nNone', NULL, 25000, 'Cancelled', NULL, 'a76ec93f-407e-4b6b-8a94-cd8b1052c67b', '2024-07-21 22:18:47', '2024-07-21 22:21:28'),
(429, 1, 'Dine-In', 'a.n. Tes, \r\nNone', NULL, 30000, 'Expired', NULL, NULL, '2024-07-21 22:22:44', '2024-07-21 22:45:04'),
(430, 1, 'Dine-In', 'a.n. Test, \r\nTesting', NULL, 50000, 'Expired', NULL, NULL, '2024-07-22 20:37:31', '2024-07-22 20:54:07');

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `after_order_update` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status IN ('Cancelled', 'Expired') AND OLD.status NOT IN ('Cancelled', 'Expired') THEN
        CALL revert_stock(NEW.id);
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double(8,2) NOT NULL,
  `order_qty` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `order_qty`, `created_at`, `updated_at`) VALUES
(1, 1, 15, 'Mie Makyus', 38000.00, 1, '2024-06-19 20:51:39', '2024-06-19 20:51:39'),
(2, 2, 15, 'Mie Makyus', 38000.00, 1, '2024-06-19 20:53:03', '2024-06-19 20:53:03'),
(3, 3, 15, 'Mie Makyus', 38000.00, 20, '2024-06-19 21:03:53', '2024-06-19 21:03:53'),
(4, 4, 15, 'Mie Makyus', 38000.00, 100, '2024-06-19 21:15:54', '2024-06-19 21:15:54'),
(5, 5, 15, 'Mie Makyus', 38000.00, 100, '2024-06-19 21:25:30', '2024-06-19 21:25:30'),
(6, 6, 15, 'Mie Makyus', 38000.00, 101, '2024-06-19 21:32:28', '2024-06-19 21:32:28'),
(7, 7, 15, 'Mie Makyus', 38000.00, 200, '2024-06-19 21:35:52', '2024-06-19 21:35:52'),
(8, 8, 15, 'Mie Makyus', 38000.00, 200, '2024-06-19 21:48:19', '2024-06-19 21:48:19'),
(9, 9, 15, 'Mie Makyus', 38000.00, 200, '2024-06-19 22:06:01', '2024-06-19 22:06:01'),
(10, 10, 15, 'Mie Makyus', 38000.00, 200, '2024-06-19 22:11:09', '2024-06-19 22:11:09'),
(11, 11, 15, 'Mie Makyus', 38000.00, 200, '2024-06-19 22:12:50', '2024-06-19 22:12:50'),
(12, 12, 15, 'Mie Makyus', 38000.00, 1, '2024-06-19 22:33:12', '2024-06-19 22:33:12'),
(13, 13, 15, 'Mie Makyus', 38000.00, 1, '2024-06-19 22:34:09', '2024-06-19 22:34:09'),
(14, 14, 15, 'Mie Makyus', 38000.00, 1, '2024-06-19 22:46:09', '2024-06-19 22:46:09'),
(15, 15, 15, 'Mie Makyus', 38000.00, 1, '2024-06-19 22:50:22', '2024-06-19 22:50:22'),
(16, 16, 15, 'Mie Makyus', 38000.00, 1, '2024-06-19 22:51:21', '2024-06-19 22:51:21'),
(17, 17, 15, 'Mie Makyus', 38000.00, 1, '2024-06-20 19:42:58', '2024-06-20 19:42:58'),
(18, 18, 15, 'Mie Makyus', 38000.00, 1, '2024-06-20 19:43:13', '2024-06-20 19:43:13'),
(19, 19, 16, 'Kwetiau  Siram', 39000.00, 0, '2024-06-20 19:45:01', '2024-06-20 19:45:01'),
(20, 21, 15, 'Mie Makyus', 38000.00, 3, '2024-06-21 00:55:48', '2024-06-21 00:55:48'),
(21, 22, 15, 'Mie Makyus', 38000.00, 3, '2024-06-21 00:57:03', '2024-06-21 00:57:03'),
(22, 23, 15, 'Mie Makyus', 38000.00, 1, '2024-06-21 01:17:00', '2024-06-21 01:17:00'),
(23, 25, 15, 'Mie Makyus', 38000.00, 1, '2024-06-21 01:18:56', '2024-06-21 01:18:56'),
(24, 27, 15, 'Mie Makyus', 38000.00, 1, '2024-06-21 01:25:59', '2024-06-21 01:25:59'),
(25, 28, 15, 'Mie Makyus', 38000.00, 43, '2024-06-23 21:38:59', '2024-06-23 21:38:59'),
(26, 29, 15, 'Mie Makyus', 38000.00, 2, '2024-06-25 22:06:40', '2024-06-25 22:06:40'),
(27, 30, 15, 'Mie Makyus', 38000.00, 3, '2024-06-25 22:11:31', '2024-06-25 22:11:31'),
(28, 32, 15, 'Mie Makyus', 38000.00, 1, '2024-06-25 22:29:22', '2024-06-25 22:29:22'),
(29, 33, 15, 'Mie Makyus', 38000.00, 1, '2024-06-25 22:31:16', '2024-06-25 22:31:16'),
(30, 34, 15, 'Mie Makyus', 38000.00, 1, '2024-06-25 22:56:16', '2024-06-25 22:56:16'),
(31, 35, 15, 'Mie Makyus', 38000.00, 2, '2024-06-25 23:45:38', '2024-06-25 23:45:38'),
(32, 36, 15, 'Mie Makyus', 38000.00, 2, '2024-06-25 23:46:03', '2024-06-25 23:46:03'),
(33, 37, 15, 'Mie Makyus', 38000.00, 1, '2024-06-25 23:51:20', '2024-06-25 23:51:20'),
(34, 38, 15, 'Mie Makyus', 38000.00, 1, '2024-06-25 23:51:43', '2024-06-25 23:51:43'),
(35, 39, 15, 'Mie Makyus', 38000.00, 1, '2024-06-25 23:51:55', '2024-06-25 23:51:55'),
(36, 301, 15, 'Mie Makyus', 38000.00, 2, '2024-06-26 00:04:22', '2024-06-26 00:04:22'),
(37, 302, 15, 'Mie Makyus', 38000.00, 2, '2024-06-26 00:05:44', '2024-06-26 00:05:44'),
(38, 303, 15, 'Mie Makyus', 38000.00, 3, '2024-06-26 00:09:18', '2024-06-26 00:09:18'),
(39, 304, 15, 'Mie Makyus', 38000.00, 1, '2024-06-26 00:20:31', '2024-06-26 00:20:31'),
(40, 305, 15, 'Mie Makyus', 38000.00, 1, '2024-06-26 00:20:42', '2024-06-26 00:20:42'),
(41, 306, 15, 'Mie Makyus', 38000.00, 3, '2024-06-27 00:34:20', '2024-06-27 00:34:20'),
(42, 307, 15, 'Mie Makyus', 38000.00, 3, '2024-06-27 00:36:16', '2024-06-27 00:36:16'),
(43, 308, 15, 'Mie Makyus', 38000.00, 3, '2024-06-27 00:36:17', '2024-06-27 00:36:17'),
(44, 309, 15, 'Mie Makyus', 38000.00, 3, '2024-06-27 00:36:18', '2024-06-27 00:36:18'),
(45, 310, 15, 'Mie Makyus', 38000.00, 3, '2024-06-27 16:51:24', '2024-06-27 16:51:24'),
(46, 311, 15, 'Mie Makyus', 38000.00, 3, '2024-06-27 17:12:03', '2024-06-27 17:12:03'),
(47, 311, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-06-27 17:12:03', '2024-06-27 17:12:03'),
(48, 312, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 09:56:57', '2024-06-28 09:56:57'),
(49, 312, 16, 'Kwetiau  Siram', 39000.00, 9, '2024-06-28 09:56:57', '2024-06-28 09:56:57'),
(50, 313, 15, 'Mie Makyus', 38000.00, 10, '2024-06-28 10:08:37', '2024-06-28 10:08:37'),
(51, 313, 16, 'Kwetiau  Siram', 39000.00, 10, '2024-06-28 10:08:37', '2024-06-28 10:08:37'),
(52, 314, 15, 'Mie Makyus', 38000.00, 1, '2024-06-28 10:10:41', '2024-06-28 10:10:41'),
(53, 315, 15, 'Mie Makyus', 38000.00, 1, '2024-06-28 10:20:25', '2024-06-28 10:20:25'),
(54, 316, 15, 'Mie Makyus', 38000.00, 1, '2024-06-28 10:20:53', '2024-06-28 10:20:53'),
(55, 317, 15, 'Mie Makyus', 38000.00, 1, '2024-06-28 10:21:08', '2024-06-28 10:21:08'),
(56, 318, 15, 'Mie Makyus', 38000.00, 1, '2024-06-28 10:27:09', '2024-06-28 10:27:09'),
(57, 319, 15, 'Mie Makyus', 38000.00, 1, '2024-06-28 10:29:15', '2024-06-28 10:29:15'),
(58, 320, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:38:24', '2024-06-28 10:38:24'),
(59, 321, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:38:50', '2024-06-28 10:38:50'),
(60, 322, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:39:29', '2024-06-28 10:39:29'),
(61, 323, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:42:54', '2024-06-28 10:42:54'),
(62, 324, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:46:06', '2024-06-28 10:46:06'),
(63, 325, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:48:14', '2024-06-28 10:48:14'),
(64, 326, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:48:42', '2024-06-28 10:48:42'),
(65, 327, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:49:01', '2024-06-28 10:49:01'),
(66, 328, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:53:26', '2024-06-28 10:53:26'),
(67, 329, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:54:41', '2024-06-28 10:54:41'),
(68, 330, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 10:55:06', '2024-06-28 10:55:06'),
(69, 331, 15, 'Mie Makyus', 38000.00, 77, '2024-06-28 10:56:14', '2024-06-28 10:56:14'),
(70, 331, 16, 'Kwetiau  Siram', 39000.00, 30, '2024-06-28 10:56:14', '2024-06-28 10:56:14'),
(71, 332, 15, 'Mie Makyus', 38000.00, 2, '2024-06-28 11:03:29', '2024-06-28 11:03:29'),
(72, 332, 18, 'Iga Penyet', 42000.00, 3, '2024-06-28 11:03:29', '2024-06-28 11:03:29'),
(73, 333, 15, 'Mie Makyus', 38000.00, 1, '2024-06-28 11:11:27', '2024-06-28 11:11:27'),
(74, 334, 15, 'Mie Makyus', 38000.00, 74, '2024-06-28 11:18:39', '2024-06-28 11:18:39'),
(75, 334, 16, 'Kwetiau  Siram', 39000.00, 30, '2024-06-28 11:18:39', '2024-06-28 11:18:39'),
(76, 335, 15, 'Mie Makyus', 38000.00, 74, '2024-06-28 11:18:51', '2024-06-28 11:18:51'),
(77, 335, 16, 'Kwetiau  Siram', 39000.00, 30, '2024-06-28 11:18:51', '2024-06-28 11:18:51'),
(78, 336, 15, 'Mie Makyus', 38000.00, 1, '2024-06-29 01:04:04', '2024-06-29 01:04:04'),
(79, 337, 15, 'Mie Makyus', 38000.00, 2, '2024-06-29 01:07:43', '2024-06-29 01:07:43'),
(80, 338, 15, 'Mie Makyus', 38000.00, 3, '2024-06-29 01:08:36', '2024-06-29 01:08:36'),
(81, 339, 15, 'Mie Makyus', 38000.00, 4, '2024-06-29 01:10:03', '2024-06-29 01:10:03'),
(82, 340, 15, 'Mie Makyus', 38000.00, 3, '2024-06-29 01:16:33', '2024-06-29 01:16:33'),
(83, 341, 15, 'Mie Makyus', 38000.00, 71, '2024-06-30 04:48:35', '2024-06-30 04:48:35'),
(84, 341, 17, 'Sapo Tahu', 45000.00, 12, '2024-06-30 04:48:35', '2024-06-30 04:48:35'),
(85, 342, 15, 'Mie Makyus', 38000.00, 71, '2024-06-30 04:49:43', '2024-06-30 04:49:43'),
(86, 342, 17, 'Sapo Tahu', 45000.00, 12, '2024-06-30 04:49:43', '2024-06-30 04:49:43'),
(87, 343, 15, 'Mie Makyus', 38000.00, 71, '2024-06-30 05:13:27', '2024-06-30 05:13:27'),
(88, 343, 17, 'Sapo Tahu', 45000.00, 12, '2024-06-30 05:13:27', '2024-06-30 05:13:27'),
(89, 344, 16, 'Kwetiau  Siram', 39000.00, 30, '2024-06-30 05:14:52', '2024-06-30 05:14:52'),
(90, 345, 16, 'Kwetiau  Siram', 39000.00, 30, '2024-06-30 05:25:40', '2024-06-30 05:25:40'),
(91, 346, 16, 'Kwetiau  Siram', 39000.00, 30, '2024-06-30 05:54:45', '2024-06-30 05:54:45'),
(92, 347, 15, 'Mie Makyus', 38000.00, 71, '2024-06-30 19:36:12', '2024-06-30 19:36:12'),
(93, 348, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-06-30 22:48:55', '2024-06-30 22:48:55'),
(94, 348, 17, 'Sapo Tahu', 45000.00, 2, '2024-06-30 22:48:55', '2024-06-30 22:48:55'),
(95, 349, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-01 22:42:43', '2024-07-01 22:42:43'),
(96, 350, 15, 'Mie Makyus', 38000.00, 1, '2024-07-01 22:46:36', '2024-07-01 22:46:36'),
(97, 351, 15, 'Mie Makyus', 38000.00, 1, '2024-07-01 23:02:27', '2024-07-01 23:02:27'),
(98, 352, 15, 'Mie Makyus', 38000.00, 1, '2024-07-01 23:10:43', '2024-07-01 23:10:43'),
(99, 353, 15, 'Mie Makyus', 38000.00, 1, '2024-07-01 23:16:53', '2024-07-01 23:16:53'),
(100, 354, 18, 'Iga Penyet', 42000.00, 1, '2024-07-01 23:53:48', '2024-07-01 23:53:48'),
(101, 355, 18, 'Iga Penyet', 42000.00, 1, '2024-07-01 23:56:53', '2024-07-01 23:56:53'),
(102, 355, 15, 'Mie Makyus', 38000.00, 1, '2024-07-01 23:56:53', '2024-07-01 23:56:53'),
(103, 356, 15, 'Mie Makyus', 38000.00, 9, '2024-07-02 00:48:35', '2024-07-02 00:48:35'),
(104, 357, 15, 'Mie Makyus', 38000.00, 9, '2024-07-02 03:48:26', '2024-07-02 03:48:26'),
(105, 357, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-02 03:48:26', '2024-07-02 03:48:26'),
(106, 358, 15, 'Mie Makyus', 38000.00, 9, '2024-07-02 09:50:23', '2024-07-02 09:50:23'),
(107, 358, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-02 09:50:23', '2024-07-02 09:50:23'),
(108, 359, 15, 'Mie Makyus', 38000.00, 9, '2024-07-02 09:54:09', '2024-07-02 09:54:09'),
(109, 359, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-02 09:54:09', '2024-07-02 09:54:09'),
(110, 360, 17, 'Sapo Tahu', 45000.00, 2, '2024-07-02 10:16:38', '2024-07-02 10:16:38'),
(111, 361, 17, 'Sapo Tahu', 45000.00, 2, '2024-07-02 10:19:53', '2024-07-02 10:19:53'),
(112, 362, 15, 'Mie Makyus', 38000.00, 1, '2024-07-02 19:41:46', '2024-07-02 19:41:46'),
(113, 363, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 17:33:48', '2024-07-03 17:33:48'),
(114, 363, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-03 17:33:48', '2024-07-03 17:33:48'),
(115, 364, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-03 18:43:50', '2024-07-03 18:43:50'),
(116, 364, 18, 'Iga Penyet', 42000.00, 1, '2024-07-03 18:43:50', '2024-07-03 18:43:50'),
(117, 365, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 19:09:15', '2024-07-03 19:09:15'),
(118, 366, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 19:10:02', '2024-07-03 19:10:02'),
(119, 367, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 19:20:19', '2024-07-03 19:20:19'),
(120, 368, 15, 'Mie Makyus', 38000.00, 2, '2024-07-03 19:25:28', '2024-07-03 19:25:28'),
(121, 369, 15, 'Mie Makyus', 38000.00, 2, '2024-07-03 19:38:40', '2024-07-03 19:38:40'),
(122, 370, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 19:45:45', '2024-07-03 19:45:45'),
(123, 371, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 20:30:25', '2024-07-03 20:30:25'),
(124, 372, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 20:31:13', '2024-07-03 20:31:13'),
(125, 373, 18, 'Iga Penyet', 42000.00, 1, '2024-07-03 21:12:12', '2024-07-03 21:12:12'),
(126, 373, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 21:12:12', '2024-07-03 21:12:12'),
(127, 373, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-03 21:12:12', '2024-07-03 21:12:12'),
(128, 374, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 21:13:51', '2024-07-03 21:13:51'),
(129, 375, 15, 'Mie Makyus', 38000.00, 1, '2024-07-03 22:25:03', '2024-07-03 22:25:03'),
(130, 375, 17, 'Sapo Tahu', 45000.00, 1, '2024-07-03 22:25:03', '2024-07-03 22:25:03'),
(131, 376, 15, 'Mie Makyus', 38000.00, 2, '2024-07-03 22:25:54', '2024-07-03 22:25:54'),
(132, 376, 17, 'Sapo Tahu', 45000.00, 1, '2024-07-03 22:25:55', '2024-07-03 22:25:55'),
(133, 377, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-04 00:09:51', '2024-07-04 00:09:51'),
(134, 377, 15, 'Mie Makyus', 38000.00, 1, '2024-07-04 00:09:51', '2024-07-04 00:09:51'),
(135, 378, 15, 'Mie Makyus', 38000.00, 1, '2024-07-04 12:09:04', '2024-07-04 12:09:04'),
(136, 379, 15, 'Mie Makyus', 38000.00, 1, '2024-07-04 12:09:13', '2024-07-04 12:09:13'),
(137, 380, 15, 'Mie Makyus', 38000.00, 1, '2024-07-05 04:14:21', '2024-07-05 04:14:21'),
(138, 381, 15, 'Mie Makyus', 38000.00, 1, '2024-07-05 06:13:12', '2024-07-05 06:13:12'),
(139, 382, 15, 'Mie Makyus', 380.00, 1, '2024-07-07 22:36:51', '2024-07-07 22:36:51'),
(140, 383, 18, 'Iga Penyet', 42000.00, 500, '2024-07-10 00:56:47', '2024-07-10 00:56:47'),
(141, 384, 18, 'Iga Penyet', 42000.00, 498, '2024-07-10 01:03:04', '2024-07-10 01:03:04'),
(142, 385, 16, 'Kwetiau  Siram', 39000.00, 1, '2024-07-10 09:01:15', '2024-07-10 09:01:15'),
(143, 386, 32, 'Mie Makyus OR', 25000.00, 1, '2024-07-10 09:52:02', '2024-07-10 09:52:02'),
(144, 387, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-10 19:17:34', '2024-07-10 19:17:34'),
(145, 388, 36, 'Es Milo', 15000.00, 1, '2024-07-10 19:19:44', '2024-07-10 19:19:44'),
(146, 388, 33, 'Mie Makyus Pedas Level 1', 25000.00, 1, '2024-07-10 19:19:44', '2024-07-10 19:19:44'),
(147, 389, 33, 'Mie Makyus Pedas Level 1', 25000.00, 1, '2024-07-10 19:21:22', '2024-07-10 19:21:22'),
(148, 390, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-10 19:26:14', '2024-07-10 19:26:14'),
(149, 390, 40, 'Mie Makyus Jumbo', 40000.00, 2, '2024-07-10 19:26:14', '2024-07-10 19:26:14'),
(150, 390, 35, 'Es  Jeruk', 10000.00, 9, '2024-07-10 19:26:14', '2024-07-10 19:26:14'),
(151, 391, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-10 19:27:15', '2024-07-10 19:27:15'),
(152, 391, 40, 'Mie Makyus Jumbo', 40000.00, 2, '2024-07-10 19:27:15', '2024-07-10 19:27:15'),
(153, 391, 35, 'Es  Jeruk', 10000.00, 7, '2024-07-10 19:27:15', '2024-07-10 19:27:15'),
(154, 392, 33, 'Mie Makyus Pedas Level 1', 25000.00, 1, '2024-07-10 19:44:48', '2024-07-10 19:44:48'),
(155, 392, 34, 'Es Teh', 5000.00, 1, '2024-07-10 19:44:48', '2024-07-10 19:44:48'),
(156, 393, 35, 'Es  Jeruk', 10000.00, 1, '2024-07-10 19:46:13', '2024-07-10 19:46:13'),
(157, 394, 35, 'Es  Jeruk', 10000.00, 1, '2024-07-10 19:57:43', '2024-07-10 19:57:43'),
(158, 395, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:24:04', '2024-07-14 19:24:04'),
(159, 396, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:31:42', '2024-07-14 19:31:42'),
(160, 397, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:42:29', '2024-07-14 19:42:29'),
(161, 398, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:47:40', '2024-07-14 19:47:40'),
(162, 399, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:48:00', '2024-07-14 19:48:00'),
(163, 400, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:49:32', '2024-07-14 19:49:32'),
(164, 401, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:51:00', '2024-07-14 19:51:00'),
(165, 402, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:52:55', '2024-07-14 19:52:55'),
(166, 403, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 19:55:13', '2024-07-14 19:55:13'),
(167, 404, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 19:55:50', '2024-07-14 19:55:50'),
(168, 405, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 19:58:12', '2024-07-14 19:58:12'),
(169, 406, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:59:26', '2024-07-14 19:59:26'),
(170, 407, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 19:59:43', '2024-07-14 19:59:43'),
(171, 408, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 20:00:18', '2024-07-14 20:00:18'),
(172, 409, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-14 20:01:18', '2024-07-14 20:01:18'),
(173, 410, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 20:05:58', '2024-07-14 20:05:58'),
(174, 411, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 20:06:28', '2024-07-14 20:06:28'),
(175, 412, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 20:39:18', '2024-07-14 20:39:18'),
(176, 413, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 20:47:59', '2024-07-14 20:47:59'),
(177, 414, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 20:52:47', '2024-07-14 20:52:47'),
(178, 415, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 21:35:33', '2024-07-14 21:35:33'),
(179, 416, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 21:52:06', '2024-07-14 21:52:06'),
(180, 417, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-14 21:52:18', '2024-07-14 21:52:18'),
(181, 418, 32, 'Mie Makyus Original', 25000.00, 3, '2024-07-14 21:55:43', '2024-07-14 21:55:43'),
(182, 419, 33, 'Mie Makyus Pedas Level 1', 25000.00, 2, '2024-07-16 06:24:01', '2024-07-16 06:24:01'),
(183, 420, 33, 'Mie Makyus Pedas Level 1', 25000.00, 2, '2024-07-16 06:24:40', '2024-07-16 06:24:40'),
(184, 421, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-16 19:01:41', '2024-07-16 19:01:41'),
(185, 422, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-16 19:06:45', '2024-07-16 19:06:45'),
(186, 423, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-16 19:12:03', '2024-07-16 19:12:03'),
(187, 424, 32, 'Mie Makyus Original', 25000.00, 194, '2024-07-16 19:41:13', '2024-07-16 19:41:13'),
(188, 425, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-16 20:58:19', '2024-07-16 20:58:19'),
(189, 426, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-21 19:42:25', '2024-07-21 19:42:25'),
(190, 427, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-21 21:20:13', '2024-07-21 21:20:13'),
(191, 428, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-21 22:18:47', '2024-07-21 22:18:47'),
(192, 429, 32, 'Mie Makyus Original', 25000.00, 1, '2024-07-21 22:22:44', '2024-07-21 22:22:44'),
(193, 429, 34, 'Es Teh', 5000.00, 1, '2024-07-21 22:22:44', '2024-07-21 22:22:44'),
(194, 430, 32, 'Mie Makyus Original', 25000.00, 2, '2024-07-22 20:37:32', '2024-07-22 20:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` double(8,2) NOT NULL,
  `images` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_stock` int NOT NULL DEFAULT '0',
  `product_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_price`, `images`, `product_description`, `product_category`, `product_stock`, `product_status`, `created_at`, `updated_at`) VALUES
(15, 'Mie Makyus', 38000.00, 'menu-images/TKqwOxqFUYyeOXWTLf1XeJEYeHs2ebUAs4Ml4B8O.png', 'Mie dengan daging ayam', 'Bakmi', 0, 'inactive', '2024-04-25 04:04:11', '2024-07-10 09:50:11'),
(32, 'Mie Makyus Original', 25000.00, 'menu-images/TuvYMSzGq8Td2Oie7L7PQEaiXEd8A8wtYKuRnirN.jpg', 'Mie + Gorengan + Kerupuk Pangsit+ Bawang Goreng.', 'Bakmi', 194, 'active', '2024-07-10 09:21:41', '2024-07-22 20:37:32'),
(33, 'Mie Makyus Pedas Level 1', 25000.00, 'menu-images/yvrE4KJ65P12XRSCDXRkq51UTDGL1MEmNsyAbplI.jpg', 'Mie+ Gorengan+ Kerupuk Pangsit+ Bawang Goreng', 'Bakmi', 199, 'active', '2024-07-10 09:25:45', '2024-07-16 06:24:40'),
(34, 'Es Teh', 5000.00, 'menu-images/XxhNTotMOKRc5GvMHlt7JojJABk2Jyiclvx7ouDk.jpg', 'Es teh manis', 'Minuman', 200, 'active', '2024-07-10 09:30:49', '2024-07-21 22:22:44'),
(35, 'Es  Jeruk', 10000.00, 'menu-images/B54mqRfKdLSXGQMU4GCEIDBLEnLAsGPRT7XqpsmL.jpg', 'Es jeruk manis', 'Minuman', 192, 'active', '2024-07-10 09:34:22', '2024-07-10 19:57:43'),
(36, 'Es Milo', 15000.00, 'menu-images/IVUzGoec8fRKlvjs8mGQjaX3JvnaqITLSdniwVGB.jpg', 'Es susu Milo', 'Minuman', 199, 'active', '2024-07-10 09:36:51', '2024-07-10 19:19:44'),
(37, 'Mie Makyus Spesial', 40000.00, 'menu-images/9b3c2W7RTU4dRV3n7a3HSP5LPklDcpxu3SdO2hK6.jpg', 'Mie + Gorengan + Baso + Sosis + Kerupuk Pangsit+ Bawang Goreng.', 'Bakmi', 200, 'active', '2024-07-10 09:39:26', '2024-07-10 09:47:13'),
(38, 'Mie Makyus Pedas Level 2', 30000.00, 'menu-images/Ewijm9UEGq8Ev7tFLLrgnHioBgXjou7Nt0qc6uph.jpg', 'Mie+ Gorengan+ Kerupuk Pangsit+ Bawang Goreng', 'Bakmi', 200, 'active', '2024-07-10 09:41:51', '2024-07-10 09:48:16'),
(39, 'Mie Makyus Pedas Level 3', 35000.00, 'menu-images/LcgSiyzrdywjkDvojWkdspRYzt08ZLbCdtJFhDg5.jpg', 'Mie+ Gorengan+ Kerupuk Pangsit+ Bawang Goreng', 'Bakmi', 200, 'active', '2024-07-10 09:43:13', '2024-07-10 09:48:59'),
(40, 'Mie Makyus Jumbo', 40000.00, 'menu-images/O4k7C9pAIiCkpX43NOTSxAuayDrBZfJFe7BfaZCA.jpg', 'Mie Extra + Gorengan+ Kerupuk Pangsit+ Bawang Goreng', 'Bakmi', 198, 'active', '2024-07-10 09:44:46', '2024-07-10 19:27:15');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `update_product_status` BEFORE UPDATE ON `products` FOR EACH ROW BEGIN
    IF NEW.product_stock > 0 THEN
        SET NEW.product_status = 'active';
    ELSEIF NEW.product_stock <= 0 THEN
        SET NEW.product_status = 'inactive';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint UNSIGNED NOT NULL,
  `table_number` int UNSIGNED NOT NULL,
  `table_capacity` int UNSIGNED NOT NULL,
  `table_qr` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `table_capacity`, `table_qr`, `created_at`, `updated_at`) VALUES
(1, 0, 0, 'qr_codes/table_0.png', '2024-05-18 19:03:21', '2024-05-18 19:03:21'),
(18, 2, 4, 'qr_codes/table_2.png', '2024-06-06 13:13:25', '2024-06-06 13:24:56'),
(19, 6, 4, 'qr_codes/table_6.png', '2024-06-06 13:14:55', '2024-06-06 13:26:08'),
(20, 1, 1, 'qr_codes/table_1.png', '2024-06-06 13:18:17', '2024-07-02 09:17:01'),
(21, 3, 4, 'qr_codes/table_3.png', '2024-06-06 13:25:44', '2024-06-06 13:25:44'),
(22, 4, 4, 'qr_codes/table_4.png', '2024-06-06 13:25:53', '2024-06-06 13:25:53'),
(23, 5, 4, 'qr_codes/table_5.png', '2024-06-06 13:25:59', '2024-06-06 13:25:59'),
(24, 7, 4, 'qr_codes/table_7.png', '2024-07-01 20:59:28', '2024-07-02 09:17:23'),
(26, 8, 10, 'qr_codes/table_8.png', '2024-07-03 17:51:04', '2024-07-03 17:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Owner', 'owner@owner.com', 'owner', '0812345678', NULL, '$2y$12$nuDTI2K8IM3b633D01U1juiK0vCGLwk.2ckYjmTA33FzucaEaMhl6', 'aUKxnpG33FILqQ4tYSCL8rzHZP4oYV3NmklHnetkdCO2BKsmcn9SdiuI3NkH', '2024-04-24 21:09:14', '2024-07-02 08:55:27'),
(2, 'Admin', 'admin@admin.com', 'admin', '081238726138', NULL, '$2y$12$jG5F8jz4oG48YbMcUB.IoOQDQdJHuj/8KBpP1tY2Wz4V0uE5WUe5e', NULL, '2024-04-25 01:27:30', '2024-04-25 01:28:00'),
(4, 'Cook', 'cook@cook.com', 'cook', '0812365123123', NULL, '$2y$12$jG5F8jz4oG48YbMcUB.IoOQDQdJHuj/8KBpP1tY2Wz4V0uE5WUe5e', NULL, '2024-05-01 09:43:55', '2024-05-01 09:43:55'),
(5, 'Pieter Wijaya', 'pieterwjy@gmail.com', 'owner', NULL, NULL, '$2y$12$U32jUFVMuzODy.l1qz4eBO0KZ8YVdNzQwL85ptMuxGqPIis5zWFtW', '19h5Z211luMiau6815pjLVHaIoTJuOzkn1wpJ3BIx96pwKayOdqdFsCZEe1W', '2024-05-05 04:24:34', '2024-07-02 08:26:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
