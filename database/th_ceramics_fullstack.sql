-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mariadb
-- Generation Time: May 06, 2026 at 05:56 AM
-- Server version: 12.2.2-MariaDB-ubu2404
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `th_ceramics_fullstack`
--

-- --------------------------------------------------------

--
-- Table structure for table `bo_noc_chu_van_ct`
--

CREATE TABLE `bo_noc_chu_van_ct` (
  `bo_noc_chu_van_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `size_des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`size_des`)),
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bo_noc_chu_van_ct`
--

INSERT INTO `bo_noc_chu_van_ct` (`bo_noc_chu_van_ct_id`, `name`, `images`, `des`, `size`, `size_image`, `size_des`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Ngói bò nóc chữ vạn', '[\"bo_noc_chu_van_ct\\/images\\/KbjU83fwxoBWzsiO_1778046449.png\"]', '[\"Timeless beauty to be treasured\",\"Beginner friendly and improves intelligence\",\"High-quality and classic design, suitable for decoration\"]', 'L280 x W280 x H54mm', 'bo_noc_chu_van_ct/sizes/7svIRiJyzVku8cLK_1778046449.png', '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\"]', 0, '2026-05-06 05:47:29', '2026-05-06 05:47:29');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dau_an_gach_trang_tri`
--

CREATE TABLE `dau_an_gach_trang_tri` (
  `dau_an_gach_trang_tri_id` bigint(20) UNSIGNED NOT NULL,
  `background` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `gach_trang_tri_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dau_an_gach_trang_tri`
--

INSERT INTO `dau_an_gach_trang_tri` (`dau_an_gach_trang_tri_id`, `background`, `title`, `location`, `description`, `gach_trang_tri_id`, `created_at`, `updated_at`) VALUES
(1, 'gach_trang_tri/dau_an/HlnnI60e1ey6ovh5_1778041492.jpg', 'Chùa Bái Đính', 'Ninh Bình', 'Ngói âm dương nâu đen', 1, '2026-05-06 04:24:52', '2026-05-06 04:24:52'),
(2, 'gach_trang_tri/dau_an/R3xeGbI5aZmV7DW9_1778041545.jpg', 'Chùa Bái Đính', 'Ninh Bình', 'Ngói âm dương nâu đen', 1, '2026-05-06 04:25:45', '2026-05-06 04:25:45'),
(3, 'gach_trang_tri/dau_an/ok5QVsneaRuCRFYM_1778041602.jpg', 'Chùa Bái Đính', 'Ninh Bình', 'Ngói âm dương nâu đen', 1, '2026-05-06 04:26:42', '2026-05-06 04:26:42'),
(4, 'gach_trang_tri/dau_an/LGLupJaEr4P080lM_1778041655.jpg', 'Chùa Bái Đính', 'Ninh Bình', 'Ngói âm dương nâu đen', 1, '2026-05-06 04:27:35', '2026-05-06 04:27:35');

-- --------------------------------------------------------

--
-- Table structure for table `den_gom_su`
--

CREATE TABLE `den_gom_su` (
  `den_gom_su_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `image1` varchar(255) NOT NULL,
  `image2` varchar(255) NOT NULL,
  `title2` varchar(30) DEFAULT NULL,
  `image3` varchar(255) NOT NULL,
  `title3` varchar(30) DEFAULT NULL,
  `image4` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `den_gom_su`
--

INSERT INTO `den_gom_su` (`den_gom_su_id`, `thumbnail_main`, `video`, `image1`, `image2`, `title2`, `image3`, `title3`, `image4`, `created_at`, `updated_at`) VALUES
(1, 'den_gom_su/images/9pnCwrGS2KlH7Yx9_1778042578.png', NULL, 'den_gom_su/images/RSsPFU5QMsPpLrQ7_1778042578.png', 'den_gom_su/images/cW9w8Prnk7OlDdsV_1778042578.png', 'ĐÈN GỐM', 'den_gom_su/images/mb0FP7MDCoiXJWUw_1778042578.png', 'ĐÈN SỨ', 'den_gom_su/images/8DCy4ciNfoFhHOvu_1778042578.png', '2026-05-06 03:47:07', '2026-05-06 04:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `den_gom_su_anh`
--

CREATE TABLE `den_gom_su_anh` (
  `den_gom_su_anh_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `den_gom_su_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `den_gom_su_anh`
--

INSERT INTO `den_gom_su_anh` (`den_gom_su_anh_id`, `image`, `den_gom_su_id`, `created_at`, `updated_at`) VALUES
(1, 'den_gom_su/gallery/HF6q5155OjeR7pTe_1778042578.jpg', 1, '2026-05-06 04:42:58', '2026-05-06 04:42:58'),
(2, 'den_gom_su/gallery/yDhxwGs2gG2xzjLd_1778042578.jpg', 1, '2026-05-06 04:42:58', '2026-05-06 04:42:58'),
(3, 'den_gom_su/gallery/mNZPrz0RFPlZSToK_1778042578.jpg', 1, '2026-05-06 04:42:58', '2026-05-06 04:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `dinh_muc_gach_co_bat_trang`
--

CREATE TABLE `dinh_muc_gach_co_bat_trang` (
  `dinh_muc_gach_co_bat_trang_id` bigint(20) UNSIGNED NOT NULL,
  `brick_type` varchar(255) NOT NULL,
  `value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinh_muc_gach_co_bat_trang`
--

INSERT INTO `dinh_muc_gach_co_bat_trang` (`dinh_muc_gach_co_bat_trang_id`, `brick_type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'Gạch cổ Bát Tràng', 32, '2026-05-06 05:42:03', '2026-05-06 05:42:03');

-- --------------------------------------------------------

--
-- Table structure for table `dinh_muc_gach_hoa_thong_gio`
--

CREATE TABLE `dinh_muc_gach_hoa_thong_gio` (
  `dinh_muc_gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL,
  `brick_type` varchar(255) NOT NULL,
  `value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinh_muc_gach_hoa_thong_gio`
--

INSERT INTO `dinh_muc_gach_hoa_thong_gio` (`dinh_muc_gach_hoa_thong_gio_id`, `brick_type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'Gạch hoa thông gió', 32, '2026-05-06 05:39:05', '2026-05-06 05:39:05');

-- --------------------------------------------------------

--
-- Table structure for table `dinh_muc_gach_trang_tri`
--

CREATE TABLE `dinh_muc_gach_trang_tri` (
  `dinh_muc_gach_trang_tri_id` bigint(20) UNSIGNED NOT NULL,
  `brick_type` varchar(255) NOT NULL,
  `value` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinh_muc_gach_trang_tri`
--

INSERT INTO `dinh_muc_gach_trang_tri` (`dinh_muc_gach_trang_tri_id`, `brick_type`, `value`, `created_at`, `updated_at`) VALUES
(1, 'Gạch trang trí', 25, '2026-05-06 05:40:23', '2026-05-06 05:40:23');

-- --------------------------------------------------------

--
-- Table structure for table `dinh_muc_ngoi_am_duong`
--

CREATE TABLE `dinh_muc_ngoi_am_duong` (
  `dinh_muc_ngoi_am_duong_id` bigint(20) UNSIGNED NOT NULL,
  `roof_type` varchar(255) NOT NULL,
  `tile_type` varchar(255) NOT NULL,
  `ngoi_am` int(11) NOT NULL,
  `ngoi_duong` int(11) NOT NULL,
  `diem` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinh_muc_ngoi_am_duong`
--

INSERT INTO `dinh_muc_ngoi_am_duong` (`dinh_muc_ngoi_am_duong_id`, `roof_type`, `tile_type`, `ngoi_am`, `ngoi_duong`, `diem`, `created_at`, `updated_at`) VALUES
(1, 'Mái gỗ', '15 cặp/m2', 25, 15, 3, '2026-05-06 04:55:00', '2026-05-06 04:55:00'),
(2, 'Mái gỗ', '27 cặp/m2', 40, 27, 5, '2026-05-06 04:55:36', '2026-05-06 04:55:36'),
(3, 'Mái gỗ', '43 cặp/m2', 60, 43, 6, '2026-05-06 04:56:02', '2026-05-06 04:56:02'),
(4, 'Mái gỗ', '80 cặp/m2', 120, 80, 8, '2026-05-06 04:56:25', '2026-05-06 04:56:25'),
(5, 'Mái bê tông', '15 cặp/m2', 15, 15, 3, '2026-05-06 04:56:41', '2026-05-06 04:56:41'),
(6, 'Mái bê tông', '27 cặp/m2', 27, 27, 5, '2026-05-06 04:57:01', '2026-05-06 04:57:01'),
(7, 'Mái bê tông', '43 cặp/m2', 43, 43, 6, '2026-05-06 04:57:19', '2026-05-06 04:57:19'),
(8, 'Mái bê tông', '80 cặp/m2', 80, 80, 8, '2026-05-06 04:57:30', '2026-05-06 04:57:30');

-- --------------------------------------------------------

--
-- Table structure for table `dinh_muc_ngoi_hai_co`
--

CREATE TABLE `dinh_muc_ngoi_hai_co` (
  `dinh_muc_ngoi_hai_co_id` bigint(20) UNSIGNED NOT NULL,
  `roof_type` varchar(255) NOT NULL,
  `ngoi_tren_mai_go` int(11) NOT NULL,
  `ngoi_tren_mai_be_tong` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinh_muc_ngoi_hai_co`
--

INSERT INTO `dinh_muc_ngoi_hai_co` (`dinh_muc_ngoi_hai_co_id`, `roof_type`, `ngoi_tren_mai_go`, `ngoi_tren_mai_be_tong`, `created_at`, `updated_at`) VALUES
(1, 'Ngói hài cổ', 125, 75, '2026-05-06 05:01:50', '2026-05-06 05:01:50');

-- --------------------------------------------------------

--
-- Table structure for table `dinh_muc_ngoi_hai_van_mieu`
--

CREATE TABLE `dinh_muc_ngoi_hai_van_mieu` (
  `dinh_muc_ngoi_hai_van_mieu_id` bigint(20) UNSIGNED NOT NULL,
  `roof_type` varchar(255) NOT NULL,
  `ngoi_tren_mai_go` int(11) NOT NULL,
  `ngoi_tren_mai_be_tong` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dinh_muc_ngoi_hai_van_mieu`
--

INSERT INTO `dinh_muc_ngoi_hai_van_mieu` (`dinh_muc_ngoi_hai_van_mieu_id`, `roof_type`, `ngoi_tren_mai_go`, `ngoi_tren_mai_be_tong`, `created_at`, `updated_at`) VALUES
(1, 'Ngói văn miếu', 125, 88, '2026-05-06 05:49:03', '2026-05-06 05:49:03');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gach_co_bat_trang`
--

CREATE TABLE `gach_co_bat_trang` (
  `gach_co_bat_trang_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gach_co_bat_trang`
--

INSERT INTO `gach_co_bat_trang` (`gach_co_bat_trang_id`, `thumbnail_main`, `video`, `images`, `created_at`, `updated_at`) VALUES
(1, 'gach_co_bat_trang/images/wOBw7eRxIAD3hb4n_1778041890.png', NULL, '[\"gach_co_bat_trang\\/cong_doan_che_tac\\/Vwsm8QgKZaS7S6L2_1778041890.jpg\",\"gach_co_bat_trang\\/cong_doan_che_tac\\/qlKeqZNF7xtWzNAE_1778041890.jpg\",\"gach_co_bat_trang\\/cong_doan_che_tac\\/k2H7iScPW0Knzc7W_1778041890.jpg\"]', '2026-05-06 03:47:07', '2026-05-06 04:31:30');

-- --------------------------------------------------------

--
-- Table structure for table `gach_co_bat_trang_anh`
--

CREATE TABLE `gach_co_bat_trang_anh` (
  `gach_co_bat_trang_anh_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `gach_co_bat_trang_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gach_co_bat_trang_ct`
--

CREATE TABLE `gach_co_bat_trang_ct` (
  `gach_co_bat_trang_ct_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `price` int(11) NOT NULL,
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gach_co_bat_trang_ct`
--

INSERT INTO `gach_co_bat_trang_ct` (`gach_co_bat_trang_ct_id`, `code`, `name`, `images`, `price`, `des`, `size`, `size_image`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'THC 100-GDS-007', 'Gạch Cổ Bát Tràng', '[\"gach_co_bat_trang_ct\\/images\\/AhxPIlrMnYLLlxGY_1778046114.png\"]', 675000, '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\"]', 'L280 x W280 x H54mm', 'gach_co_bat_trang_ct/sizes/nXlwBTRkcbgVNfi6_1778046114.png', 0, '2026-05-06 05:41:54', '2026-05-06 05:41:54');

-- --------------------------------------------------------

--
-- Table structure for table `gach_hoa_thong_gio`
--

CREATE TABLE `gach_hoa_thong_gio` (
  `gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL,
  `video_thumbnail` varchar(255) NOT NULL,
  `video_url` longtext DEFAULT NULL,
  `process_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`process_images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gach_hoa_thong_gio`
--

INSERT INTO `gach_hoa_thong_gio` (`gach_hoa_thong_gio_id`, `video_thumbnail`, `video_url`, `process_images`, `created_at`, `updated_at`) VALUES
(1, 'defaults/placeholder.png', NULL, '[\"gach_hoa_thong_gio\\/cong_doan_che_tac\\/UL0h9zGM4v6OeLiM_1778040752.jpg\",\"gach_hoa_thong_gio\\/cong_doan_che_tac\\/38jz1DJSLzLoN4V8_1778040752.jpg\",\"gach_hoa_thong_gio\\/cong_doan_che_tac\\/s2IynuXG1vEi8tdG_1778040752.jpg\"]', '2026-05-06 03:47:07', '2026-05-06 04:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `gach_hoa_thong_gio_anh`
--

CREATE TABLE `gach_hoa_thong_gio_anh` (
  `gach_hoa_thong_gio_anh_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gach_hoa_thong_gio_anh`
--

INSERT INTO `gach_hoa_thong_gio_anh` (`gach_hoa_thong_gio_anh_id`, `image`, `gach_hoa_thong_gio_id`, `created_at`, `updated_at`) VALUES
(1, 'gach_hoa_thong_gio/gallery/1KziBq2qnR0UqkXB_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32'),
(2, 'gach_hoa_thong_gio/gallery/sc3Ujviv9h42ej4D_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32'),
(3, 'gach_hoa_thong_gio/gallery/pmePvImI3F4jvliK_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32'),
(4, 'gach_hoa_thong_gio/gallery/vwkDjeB52da6mrms_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32'),
(5, 'gach_hoa_thong_gio/gallery/hgJEf5gPv1CR5Zdf_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32'),
(6, 'gach_hoa_thong_gio/gallery/8Am7N3zfGRTA3OHJ_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32'),
(7, 'gach_hoa_thong_gio/gallery/21ZlvkQJyzDQciOe_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32'),
(8, 'gach_hoa_thong_gio/gallery/91mkkyaosGyGBMw3_1778040752.png', 1, '2026-05-06 04:12:32', '2026-05-06 04:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `gach_hoa_thong_gio_ct`
--

CREATE TABLE `gach_hoa_thong_gio_ct` (
  `gach_hoa_thong_gio_ct_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `price` int(11) NOT NULL,
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gach_hoa_thong_gio_ct`
--

INSERT INTO `gach_hoa_thong_gio_ct` (`gach_hoa_thong_gio_ct_id`, `code`, `name`, `images`, `price`, `des`, `size`, `size_image`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'THC 100-GDS-005', 'Gạch Hoa Thông Gió', '[\"gach_hoa_thong_gio_ct\\/images\\/5Y2lWiYgh3lkZVG0_1778045916.png\"]', 675000, '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\"]', 'L280 x W280 x H54mm', 'gach_hoa_thong_gio_ct/sizes/PiFHf71YZlGz4ty2_1778045916.png', 0, '2026-05-06 05:38:36', '2026-05-06 05:38:36');

-- --------------------------------------------------------

--
-- Table structure for table `gach_trang_tri`
--

CREATE TABLE `gach_trang_tri` (
  `gach_trang_tri_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gach_trang_tri`
--

INSERT INTO `gach_trang_tri` (`gach_trang_tri_id`, `thumbnail_main`, `video`, `images`, `created_at`, `updated_at`) VALUES
(1, 'gach_trang_tri/images/GepsBqG6JrHWe1Ee_1778041118.png', NULL, '[\"gach_trang_tri\\/cong_doan_che_tac\\/rKZA2EM23PLOUDC1_1778041375.jpg\",\"gach_trang_tri\\/cong_doan_che_tac\\/rs4XhmPnKW9Wo0Bj_1778041375.jpg\",\"gach_trang_tri\\/cong_doan_che_tac\\/RCAjNcZ0M0IqPTxS_1778041375.jpg\"]', '2026-05-06 03:47:07', '2026-05-06 04:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `gach_trang_tri_ct`
--

CREATE TABLE `gach_trang_tri_ct` (
  `gach_trang_tri_ct_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `price` int(11) NOT NULL,
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gach_trang_tri_ct`
--

INSERT INTO `gach_trang_tri_ct` (`gach_trang_tri_ct_id`, `code`, `name`, `images`, `price`, `des`, `size`, `size_image`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'THC 100-GDS-006', 'GẠCH TRANG TRÍ', '[\"gach_trang_tri_ct\\/images\\/PZpmrbU4rClU3iRm_1778046011.png\"]', 675000, '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\"]', 'L280 x W280 x H54mm', 'gach_trang_tri_ct/sizes/cQ9jQ06HSyZiMNrb_1778046011.png', 0, '2026-05-06 05:40:11', '2026-05-06 05:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `gia_tri_gach_hoa_thong_gio`
--

CREATE TABLE `gia_tri_gach_hoa_thong_gio` (
  `gia_tri_gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL,
  `background` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `desscription` longtext NOT NULL,
  `gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gia_tri_gach_hoa_thong_gio`
--

INSERT INTO `gia_tri_gach_hoa_thong_gio` (`gia_tri_gach_hoa_thong_gio_id`, `background`, `image`, `title`, `desscription`, `gach_hoa_thong_gio_id`, `created_at`, `updated_at`) VALUES
(1, '#1D78AD', 'assets/images/gach-hoa-value.png', 'Tuyệt tác men hỏa biến kháng', 'Lớp men dày dặn đóng vai trò như màng chắn thủy tinh siêu cứng, ngăn chặn bụi bẩn và rêu mốc bám dính. Nhờ độ trơn bóng cao, mái nhà có khả năng \"tự làm sạch\"', 1, '2026-05-06 04:11:44', '2026-05-06 04:11:44'),
(2, '#5A7E46', 'assets/images/work-03.jpg', 'Tuyệt tác men hỏa biến kháng', 'Lớp men dày dặn đóng vai trò như màng chắn thủy tinh siêu cứng, ngăn chặn bụi bẩn và rêu mốc bám dính. Nhờ độ trơn bóng cao, mái nhà có khả năng \"tự làm sạch\"', 1, '2026-05-06 04:13:17', '2026-05-06 04:13:17'),
(3, '#B28373', 'assets/images/value-01.png', 'Tuyệt tác men hỏa biến kháng', 'Lớp men dày dặn đóng vai trò như màng chắn thủy tinh siêu cứng, ngăn chặn bụi bẩn và rêu mốc bám dính. Nhờ độ trơn bóng cao, mái nhà có khả năng \"tự làm sạch\"', 1, '2026-05-06 04:13:54', '2026-05-06 04:13:54');

-- --------------------------------------------------------

--
-- Table structure for table `gia_tri_vuot_troi`
--

CREATE TABLE `gia_tri_vuot_troi` (
  `gia_tri_vuot_troi_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(50) NOT NULL,
  `desscription` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gia_tri_vuot_troi`
--

INSERT INTO `gia_tri_vuot_troi` (`gia_tri_vuot_troi_id`, `title`, `desscription`, `image`, `created_at`, `updated_at`) VALUES
(1, 'CỐT ĐẤT LUYỆN LỬA', 'Được tôi luyện ở nhiệt độ 1300°C tạo nên cốt gốm đanh chắc, thách thức mọi khắc nghiệt từ sương muối đến bão giông. Đây là sự đầu tư một lần cho giá trị truyền đời, chẳng ngại dấu vết thời gian', 'gia_tri_vuot_troi/images/3wegJcBEgwbszAjw_1778039461.png', '2026-05-06 03:49:47', '2026-05-06 03:51:01'),
(2, 'KIẾN TRÚC TRƯỜNG TỒN', 'Vẻ đẹp truyền thống kết hợp cùng công nghệ sản xuất hiện đại, tạo nên mái nhà vững chãi, che chở vạn vật qua bao thăng trầm, mang lại sự bình yên và hưng thịnh cho gia chủ.', 'gia_tri_vuot_troi/images/8dNJ2Jt9tUewiBDg_1778039455.png', '2026-05-06 03:50:10', '2026-05-06 03:50:55'),
(3, 'NGHỆ THUẬT GỐM SỨ', 'Từng viên ngói là một tác phẩm nghệ thuật, kết tinh từ bàn tay khéo léo của các nghệ nhân làng gốm Bát Tràng, mang trong mình tâm hồn và sức sống di sản Việt.', 'gia_tri_vuot_troi/images/iSInOLh34j9HrwN5_1778039483.png', '2026-05-06 03:51:23', '2026-05-06 03:51:23'),
(4, 'SẮC MEN ĐỘC BẢN', 'Lớp men hỏa biến biến ảo trong lò nung, tạo nên những sắc thái màu đặc trưng không viên nào giống viên nào, tạo nên dấu ấn riêng biệt cho ngôi nhà của bạn.', 'gia_tri_vuot_troi/images/0POqo3k9sajaG0yA_1778039500.png', '2026-05-06 03:51:40', '2026-05-06 03:51:40');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lan_can_gom_xu`
--

CREATE TABLE `lan_can_gom_xu` (
  `lan_can_gom_xu_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lan_can_gom_xu`
--

INSERT INTO `lan_can_gom_xu` (`lan_can_gom_xu_id`, `thumbnail_main`, `video`, `created_at`, `updated_at`) VALUES
(1, 'lan_can_gom_xu/images/seq88QezkkuoimcI_1778041696.jpg', NULL, '2026-05-06 03:47:07', '2026-05-06 04:28:16');

-- --------------------------------------------------------

--
-- Table structure for table `linh_vat`
--

CREATE TABLE `linh_vat` (
  `linh_vat_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `linh_vat_phong_thuy_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `linh_vat`
--

INSERT INTO `linh_vat` (`linh_vat_id`, `image`, `title`, `description`, `linh_vat_phong_thuy_id`, `created_at`, `updated_at`) VALUES
(1, 'linh_vat_phong_thuy/items/9Jv4rF5DFfchwQQz_1778042095.png', 'Nghê', 'Nghê là linh vật thuần Việt độc đáo, mang thân hình loài chó trung thành trong diện mạo uy nghi của lân sư. Với quyền năng xua đuổi tà khí và canh giữ bình an, Nghê từ lâu đã trở thành \"vị hộ thần\" không thể thiếu tại các không gian tâm linh và cửa đình, hay đơn giản trong không gian nhà Việt.', 1, '2026-05-06 04:34:55', '2026-05-06 04:34:55'),
(2, 'linh_vat_phong_thuy/items/PhcQ40xvipMvq9oQ_1778042115.png', 'Phượng', 'Phượng là linh vật biểu trưng cho sự thanh cao và trường tồn bất diệt. Với dáng hình kiêu sa hội tụ tinh hoa muôn loài, chim Phượng mang đến điềm lành và vượng khí khởi sắc cho mọi không gian kiến trúc cổ.', 1, '2026-05-06 04:35:15', '2026-05-06 04:35:15'),
(3, 'linh_vat_phong_thuy/items/QY0HynG8lOHDhsfs_1778042137.png', 'Đầu rồng', 'Đầu rồng thời Lý là kiệt tác gốm sứ hiền triết, biểu trưng cho quyền uy và thịnh vượng. Đầu rồng có các phiên bản lớn uy nghiêm trong đại sảnh đến nhỏ xinh tinh xảo trên bàn làm việc. Không chỉ là vật phẩm phong thủy linh thiêng, đây còn là món quà đối ngoại cao cấp từng được trao tặng Tổng thống Mỹ Obama, đóng vai trò \"sứ giả văn hóa\" truyền tải niềm tự hào di sản Việt đến quốc tế.', 1, '2026-05-06 04:35:37', '2026-05-06 04:35:37');

-- --------------------------------------------------------

--
-- Table structure for table `linh_vat_phong_thuy`
--

CREATE TABLE `linh_vat_phong_thuy` (
  `linh_vat_phong_thuy_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `linh_vat_phong_thuy`
--

INSERT INTO `linh_vat_phong_thuy` (`linh_vat_phong_thuy_id`, `thumbnail_main`, `video`, `created_at`, `updated_at`) VALUES
(1, 'linh_vat_phong_thuy/images/2HS57UpefBeByDwd_1778042016.png', NULL, '2026-05-06 03:47:07', '2026-05-06 04:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `linh_vat_phong_thuy_anh`
--

CREATE TABLE `linh_vat_phong_thuy_anh` (
  `linh_vat_phong_thuy_anh_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `linh_vat_phong_thuy_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `linh_vat_phong_thuy_anh`
--

INSERT INTO `linh_vat_phong_thuy_anh` (`linh_vat_phong_thuy_anh_id`, `image`, `linh_vat_phong_thuy_id`, `created_at`, `updated_at`) VALUES
(1, 'linh_vat_phong_thuy/gallery/6KKoyf3tkOLeWYY5_1778042016.jpg', 1, '2026-05-06 04:33:36', '2026-05-06 04:33:36'),
(2, 'linh_vat_phong_thuy/gallery/b8y833VCTaThxQzG_1778042016.jpg', 1, '2026-05-06 04:33:36', '2026-05-06 04:33:36'),
(3, 'linh_vat_phong_thuy/gallery/yEZnNNRKuQHiWRju_1778042016.jpg', 1, '2026-05-06 04:33:36', '2026-05-06 04:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `linh_vat_phong_thuy_ct`
--

CREATE TABLE `linh_vat_phong_thuy_ct` (
  `linh_vat_phong_thuy_ct_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `price` int(11) NOT NULL,
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `size_des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`size_des`)),
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `linh_vat_phong_thuy_ct`
--

INSERT INTO `linh_vat_phong_thuy_ct` (`linh_vat_phong_thuy_ct_id`, `code`, `name`, `images`, `price`, `des`, `size`, `size_image`, `size_des`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'THC 100-GDS-008', 'NGHÊ TRƯỜN CỔ', '[\"linh_vat_phong_thuy_ct\\/images\\/lBQ1tINTISN6AqEQ_1778046270.png\"]', 675000, '[\"Timeless beauty to be treasured\",\"Beginner friendly and improves intelligence\",\"High-quality and classic design, suitable for decoration\"]', 'L280 x W280 x H54mm', 'linh_vat_phong_thuy_ct/sizes/E5ZenbWGmUAKNYpM_1778046270.png', '[\"High-quality and classic design, suitable for decoration\",\"Timeless beauty to be treasured\",\"Beginner friendly and improves intelligence\"]', 0, '2026-05-06 05:44:30', '2026-05-06 05:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `mau_sac_ngoi_am_duong_ct`
--

CREATE TABLE `mau_sac_ngoi_am_duong_ct` (
  `mau_sac_ngoi_am_duong_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mau_sac_ngoi_am_duong_ct`
--

INSERT INTO `mau_sac_ngoi_am_duong_ct` (`mau_sac_ngoi_am_duong_ct_id`, `name`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Da lươn', 'mau_sac_ngoi_am_duong_ct/images/sx847QZOKXTGo3mG_1778043099.png', '2026-05-06 04:51:39', '2026-05-06 04:51:39'),
(2, 'Xanh rêu', 'mau_sac_ngoi_am_duong_ct/images/98njkBI3zD2nQqZt_1778043105.png', '2026-05-06 04:51:45', '2026-05-06 04:51:45'),
(3, 'Xanh ngọc', 'mau_sac_ngoi_am_duong_ct/images/UOj1LtHoVR7YxhBp_1778043111.png', '2026-05-06 04:51:51', '2026-05-06 04:51:51'),
(4, 'Xanh ngọc', 'mau_sac_ngoi_am_duong_ct/images/nVszZMkcmoppdvvt_1778043126.png', '2026-05-06 04:52:06', '2026-05-06 04:52:06'),
(5, 'Xanh dương', 'mau_sac_ngoi_am_duong_ct/images/YdWjQT3DVumsj2kH_1778043133.png', '2026-05-06 04:52:13', '2026-05-06 04:52:13'),
(6, 'Xanh đồng', 'mau_sac_ngoi_am_duong_ct/images/XEldqJLHiBBlBcC0_1778043140.png', '2026-05-06 04:52:20', '2026-05-06 04:52:20'),
(7, 'Socola', 'mau_sac_ngoi_am_duong_ct/images/dkuQhJPcbuiz1MGs_1778043145.png', '2026-05-06 04:52:25', '2026-05-06 04:52:25'),
(8, 'Nâu đỏ', 'mau_sac_ngoi_am_duong_ct/images/G1jUjStRIyFIrL6u_1778043153.jpg', '2026-05-06 04:52:33', '2026-05-06 04:52:33'),
(9, 'Nâu đen', 'mau_sac_ngoi_am_duong_ct/images/qTrc7ZI8pXcoffBj_1778043159.jpg', '2026-05-06 04:52:39', '2026-05-06 04:52:39'),
(10, 'Hổ phách', 'mau_sac_ngoi_am_duong_ct/images/2nGizIO8yqqNQVzg_1778043166.png', '2026-05-06 04:52:46', '2026-05-06 04:52:46'),
(11, 'Gốm đỏ', 'mau_sac_ngoi_am_duong_ct/images/rtBBNPD5p2RjED8s_1778043175.png', '2026-05-06 04:52:55', '2026-05-06 04:52:55'),
(12, 'Ghi xám', 'mau_sac_ngoi_am_duong_ct/images/FGZIaCUUzHUsQvvA_1778043193.png', '2026-05-06 04:53:13', '2026-05-06 04:53:13'),
(13, 'Đỏ cờ', 'mau_sac_ngoi_am_duong_ct/images/SjfgSc4vKn348WpH_1778043200.png', '2026-05-06 04:53:20', '2026-05-06 04:53:20'),
(14, 'Đen nhám', 'mau_sac_ngoi_am_duong_ct/images/YEqYW35DcBCDO2lN_1778043209.jpg', '2026-05-06 04:53:29', '2026-05-06 04:53:29');

-- --------------------------------------------------------

--
-- Table structure for table `mau_sac_ngoi_hai_co_ct`
--

CREATE TABLE `mau_sac_ngoi_hai_co_ct` (
  `mau_sac_ngoi_hai_co_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `ngoi_hai_co_ct_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mau_sac_ngoi_hai_co_ct`
--

INSERT INTO `mau_sac_ngoi_hai_co_ct` (`mau_sac_ngoi_hai_co_ct_id`, `name`, `image`, `code`, `price`, `ngoi_hai_co_ct_id`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Xanh đồng', 'mau_sac_ngoi_hai_co_ct/images/UCUEKtR2r7B9YdDM_1778043611.png', 'THC 100-GDS-001', 675000, 1, 0, '2026-05-06 05:00:11', '2026-05-06 05:00:11'),
(2, 'Nâu đen', 'mau_sac_ngoi_hai_co_ct/images/nKUznBYdq9z9OoeE_1778043643.jpg', 'THC 100-GDS-002', 675000, 1, 0, '2026-05-06 05:00:43', '2026-05-06 05:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `mau_sac_ngoi_hai_van_mieu_ct`
--

CREATE TABLE `mau_sac_ngoi_hai_van_mieu_ct` (
  `mau_sac_ngoi_hai_van_mieu_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `ngoi_hai_van_mieu_ct_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mau_sac_ngoi_hai_van_mieu_ct`
--

INSERT INTO `mau_sac_ngoi_hai_van_mieu_ct` (`mau_sac_ngoi_hai_van_mieu_ct_id`, `name`, `image`, `code`, `price`, `ngoi_hai_van_mieu_ct_id`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Xanh đồng', 'mau_sac_ngoi_hai_van_mieu_ct/images/Smp4Y9hAjPO5QEFM_1778043830.png', 'THC 100-GDS-003', 675000, 1, 0, '2026-05-06 05:03:50', '2026-05-06 05:03:50'),
(2, 'Xanh rêu', 'mau_sac_ngoi_hai_van_mieu_ct/images/PX21gL9sQnUToFzA_1778043844.png', 'THC 100-GDS-004', 675000, 1, 0, '2026-05-06 05:04:04', '2026-05-06 05:04:04');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_26_034128_create_tables_product_type', 1),
(5, '2026_05_01_082923_create_product_details', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ngoi_am_duong`
--

CREATE TABLE `ngoi_am_duong` (
  `ngoi_am_duong_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `thumbnail1` varchar(255) NOT NULL,
  `thumbnail2` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ngoi_am_duong`
--

INSERT INTO `ngoi_am_duong` (`ngoi_am_duong_id`, `thumbnail_main`, `thumbnail1`, `thumbnail2`, `video`, `created_at`, `updated_at`) VALUES
(1, 'ngoi_am_duong/images/Gj6FdR6bkPJFdVb2_1778039586.jpg', 'ngoi_am_duong/images/NcOh4ejnWvWl2lDO_1778039586.jpg', 'ngoi_am_duong/images/K0XFmxQe06nuFDOI_1778039586.png', NULL, '2026-05-06 03:47:07', '2026-05-06 03:53:06');

-- --------------------------------------------------------

--
-- Table structure for table `ngoi_am_duong_ct`
--

CREATE TABLE `ngoi_am_duong_ct` (
  `ngoi_am_duong_ct_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `price` int(11) NOT NULL,
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ngoi_am_duong_ct`
--

INSERT INTO `ngoi_am_duong_ct` (`ngoi_am_duong_ct_id`, `code`, `name`, `images`, `price`, `des`, `size`, `size_image`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'THC 100-GDS', 'Ngói Âm Dương 27 Xanh Đồng', '[\"ngoi_am_duong_ct\\/images\\/grxYPdzu5lE2SMHZ_1778042705.png\"]', 675000, '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\"]', 'L280 x W280 x H54mm', 'ngoi_am_duong_ct/sizes/4fzzvL1zsfjqpWVC_1778042705.png', 0, '2026-05-06 04:45:05', '2026-05-06 04:45:05');

-- --------------------------------------------------------

--
-- Table structure for table `ngoi_bo_noc_ct`
--

CREATE TABLE `ngoi_bo_noc_ct` (
  `ngoi_bo_noc_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `size_des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`size_des`)),
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ngoi_bo_noc_ct`
--

INSERT INTO `ngoi_bo_noc_ct` (`ngoi_bo_noc_ct_id`, `name`, `images`, `des`, `size`, `size_image`, `size_des`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Ngói bò nóc chữ vạn', '[\"ngoi_bo_noc_ct\\/images\\/rk0sb8eE0ZIFddl8_1778046344.png\"]', '[\"Timeless beauty to be treasured\",\"Beginner friendly and improves intelligence\",\"High-quality and classic design, suitable for decoration\"]', 'L280 x W280 x H54mm', 'ngoi_bo_noc_ct/sizes/4gdRE65ms9lGOZ26_1778046344.png', '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\",\"Timeless beauty to be treasured\"]', 0, '2026-05-06 05:45:44', '2026-05-06 05:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `ngoi_hai_co_ct`
--

CREATE TABLE `ngoi_hai_co_ct` (
  `ngoi_hai_co_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ngoi_hai_co_ct`
--

INSERT INTO `ngoi_hai_co_ct` (`ngoi_hai_co_ct_id`, `name`, `images`, `des`, `size`, `size_image`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Ngói hài cổ nâu đen', '[\"ngoi_hai_co_ct\\/images\\/NmqNDso1q2mUDqDu_1778043562.png\"]', '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\"]', 'L280 x W280 x H54mm', 'ngoi_hai_co_ct/sizes/CjdgbLyBuS1sGYO1_1778043562.png', 0, '2026-05-06 04:59:22', '2026-05-06 04:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `ngoi_hai_van_mieu`
--

CREATE TABLE `ngoi_hai_van_mieu` (
  `ngoi_hai_van_mieu_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `title1` varchar(50) NOT NULL,
  `thumbnail1` varchar(255) NOT NULL,
  `title2` varchar(50) NOT NULL,
  `thumbnail2` varchar(255) NOT NULL,
  `title3` varchar(50) NOT NULL,
  `thumbnail3` varchar(255) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ngoi_hai_van_mieu`
--

INSERT INTO `ngoi_hai_van_mieu` (`ngoi_hai_van_mieu_id`, `thumbnail_main`, `title1`, `thumbnail1`, `title2`, `thumbnail2`, `title3`, `thumbnail3`, `video`, `images`, `created_at`, `updated_at`) VALUES
(1, 'ngoi_hai_van_mieu/images/g5iCZXir9WL1fuO7_1778040059.jpg', 'Ngói văn miếu tròn', 'ngoi_hai_van_mieu/images/D7n1Jz62ugBGv41s_1778040059.png', 'Ngói văn miếu mũi', 'ngoi_hai_van_mieu/images/txVHXDgw5ylWNGOB_1778040059.png', 'Ngói hài cổ', 'ngoi_hai_van_mieu/images/XLh3jsaldsoQrRMA_1778040059.png', NULL, '[\"ngoi_hai_van_mieu\\/cong_doan_che_tac\\/fdVt1BW6hOpzV6M7_1778040059.jpg\",\"ngoi_hai_van_mieu\\/cong_doan_che_tac\\/8Z3N95VeWvaDdzrL_1778040059.jpg\",\"ngoi_hai_van_mieu\\/cong_doan_che_tac\\/kA3QWIcUe9AAcXYk_1778040059.jpg\"]', '2026-05-06 03:47:07', '2026-05-06 04:00:59');

-- --------------------------------------------------------

--
-- Table structure for table `ngoi_hai_van_mieu_ct`
--

CREATE TABLE `ngoi_hai_van_mieu_ct` (
  `ngoi_hai_van_mieu_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`images`)),
  `price` int(11) NOT NULL,
  `des` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`des`)),
  `mau_sac_id` bigint(20) UNSIGNED NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `size_image` varchar(255) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ngoi_hai_van_mieu_ct`
--

INSERT INTO `ngoi_hai_van_mieu_ct` (`ngoi_hai_van_mieu_ct_id`, `name`, `images`, `price`, `des`, `mau_sac_id`, `size`, `size_image`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'Ngói Hài Văn Miếu', '[\"ngoi_hai_van_mieu_ct\\/images\\/AYiCunWcdVyA1Z9W_1778043791.png\"]', 0, '[\"Timeless beauty to be treasured\",\"High-quality and classic design, suitable for decoration\",\"Beginner friendly and improves intelligence\"]', 0, 'L280 x W280 x H54mm', 'ngoi_hai_van_mieu_ct/sizes/xqePHEnmBgWDkM8w_1778043791.png', 0, '2026-05-06 05:03:11', '2026-05-06 05:03:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phan_loai_bo_noc_chu_van_ct`
--

CREATE TABLE `phan_loai_bo_noc_chu_van_ct` (
  `phan_loai_bo_noc_chu_van_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `bo_noc_chu_van_ct_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phan_loai_bo_noc_chu_van_ct`
--

INSERT INTO `phan_loai_bo_noc_chu_van_ct` (`phan_loai_bo_noc_chu_van_ct_id`, `name`, `code`, `price`, `bo_noc_chu_van_ct_id`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'COMBO NGÓI + PHỤ KIỆN', 'THC 100-GDS-011', 700000, 1, 0, '2026-05-06 05:48:01', '2026-05-06 05:48:01'),
(2, 'NGÓI BÒ CHỮ VẠN', 'THC 100-GDS-012', 690000, 1, 0, '2026-05-06 05:48:26', '2026-05-06 05:48:26');

-- --------------------------------------------------------

--
-- Table structure for table `phan_loai_ngoi_bo_noc_ct`
--

CREATE TABLE `phan_loai_ngoi_bo_noc_ct` (
  `phan_loai_ngoi_bo_noc_ct_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `ngoi_bo_noc_ct_id` bigint(20) UNSIGNED NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Active, 1: Deleted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phan_loai_ngoi_bo_noc_ct`
--

INSERT INTO `phan_loai_ngoi_bo_noc_ct` (`phan_loai_ngoi_bo_noc_ct_id`, `name`, `code`, `price`, `ngoi_bo_noc_ct_id`, `is_delete`, `created_at`, `updated_at`) VALUES
(1, 'COMBO NGÓI + PHỤ KIỆN', 'THC 100-GDS-009', 600000, 1, 0, '2026-05-06 05:46:15', '2026-05-06 05:46:15'),
(2, 'NGÓI BÒ CHỮ VẠN', 'THC 100-GDS-010', 650000, 1, 0, '2026-05-06 05:46:32', '2026-05-06 05:46:32');

-- --------------------------------------------------------

--
-- Table structure for table `phu_kien_ngoi`
--

CREATE TABLE `phu_kien_ngoi` (
  `phu_kien_ngoi_id` bigint(20) UNSIGNED NOT NULL,
  `thumbnail_main` varchar(255) NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `phu_kien_ngoi`
--

INSERT INTO `phu_kien_ngoi` (`phu_kien_ngoi_id`, `thumbnail_main`, `images`, `created_at`, `updated_at`) VALUES
(1, 'phu_kien_ngoi/images/di4zC1v1ACHJlCmq_1778041040.png', '[\"phu_kien_ngoi\\/gallery\\/oWCK6QalvVUCWkCm_1778041001.jpg\",\"phu_kien_ngoi\\/gallery\\/Ra5cNLKtY4M7n1li_1778041001.jpg\",\"phu_kien_ngoi\\/gallery\\/X64nzLdrX1sWureJ_1778041001.jpg\",\"phu_kien_ngoi\\/gallery\\/FCYsPsaXRnduXqP3_1778041001.jpg\",\"phu_kien_ngoi\\/gallery\\/Kz9pR5SBdfudFWVU_1778041001.png\",\"phu_kien_ngoi\\/gallery\\/drgnB4G1pwU0XgAh_1778041001.png\"]', '2026-05-06 03:47:07', '2026-05-06 04:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('Pd1r7cVhteH1MjqQM6KNBmRGYhT7EQrjzgtdXB4Y', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWldtdVFQZHQxcVBGUk1lTGdaR3FsYmt1UXdhR3Vpdjc5WGY2NnpVQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9saW5oLXZhdC1waG9uZy10aHV5IjtzOjU6InJvdXRlIjtzOjMxOiJhZG1pbi5saW5oLXZhdC1waG9uZy10aHV5LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1778046589);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` enum('superadmin','admin','customer') NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'superadmin', 'admin@gmail.com', NULL, '$2y$12$YsuDKoDwecKpoOpA7VwkXObbN14EPAXTt6AeTDAQ/uCMdyOo.t.o.', NULL, '2026-05-06 03:47:07', '2026-05-06 03:47:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bo_noc_chu_van_ct`
--
ALTER TABLE `bo_noc_chu_van_ct`
  ADD PRIMARY KEY (`bo_noc_chu_van_ct_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `dau_an_gach_trang_tri`
--
ALTER TABLE `dau_an_gach_trang_tri`
  ADD PRIMARY KEY (`dau_an_gach_trang_tri_id`),
  ADD KEY `dau_an_gach_trang_tri_gach_trang_tri_id_foreign` (`gach_trang_tri_id`);

--
-- Indexes for table `den_gom_su`
--
ALTER TABLE `den_gom_su`
  ADD PRIMARY KEY (`den_gom_su_id`);

--
-- Indexes for table `den_gom_su_anh`
--
ALTER TABLE `den_gom_su_anh`
  ADD PRIMARY KEY (`den_gom_su_anh_id`),
  ADD KEY `den_gom_su_anh_den_gom_su_id_foreign` (`den_gom_su_id`);

--
-- Indexes for table `dinh_muc_gach_co_bat_trang`
--
ALTER TABLE `dinh_muc_gach_co_bat_trang`
  ADD PRIMARY KEY (`dinh_muc_gach_co_bat_trang_id`),
  ADD UNIQUE KEY `dinh_muc_gach_co_bat_trang_brick_type_unique` (`brick_type`);

--
-- Indexes for table `dinh_muc_gach_hoa_thong_gio`
--
ALTER TABLE `dinh_muc_gach_hoa_thong_gio`
  ADD PRIMARY KEY (`dinh_muc_gach_hoa_thong_gio_id`),
  ADD UNIQUE KEY `dinh_muc_gach_hoa_thong_gio_brick_type_unique` (`brick_type`);

--
-- Indexes for table `dinh_muc_gach_trang_tri`
--
ALTER TABLE `dinh_muc_gach_trang_tri`
  ADD PRIMARY KEY (`dinh_muc_gach_trang_tri_id`),
  ADD UNIQUE KEY `dinh_muc_gach_trang_tri_brick_type_unique` (`brick_type`);

--
-- Indexes for table `dinh_muc_ngoi_am_duong`
--
ALTER TABLE `dinh_muc_ngoi_am_duong`
  ADD PRIMARY KEY (`dinh_muc_ngoi_am_duong_id`),
  ADD UNIQUE KEY `dinh_muc_ngoi_am_duong_roof_type_tile_type_unique` (`roof_type`,`tile_type`);

--
-- Indexes for table `dinh_muc_ngoi_hai_co`
--
ALTER TABLE `dinh_muc_ngoi_hai_co`
  ADD PRIMARY KEY (`dinh_muc_ngoi_hai_co_id`),
  ADD UNIQUE KEY `dinh_muc_ngoi_hai_co_roof_type_unique` (`roof_type`);

--
-- Indexes for table `dinh_muc_ngoi_hai_van_mieu`
--
ALTER TABLE `dinh_muc_ngoi_hai_van_mieu`
  ADD PRIMARY KEY (`dinh_muc_ngoi_hai_van_mieu_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gach_co_bat_trang`
--
ALTER TABLE `gach_co_bat_trang`
  ADD PRIMARY KEY (`gach_co_bat_trang_id`);

--
-- Indexes for table `gach_co_bat_trang_anh`
--
ALTER TABLE `gach_co_bat_trang_anh`
  ADD PRIMARY KEY (`gach_co_bat_trang_anh_id`),
  ADD KEY `gach_co_bat_trang_anh_gach_co_bat_trang_id_foreign` (`gach_co_bat_trang_id`);

--
-- Indexes for table `gach_co_bat_trang_ct`
--
ALTER TABLE `gach_co_bat_trang_ct`
  ADD PRIMARY KEY (`gach_co_bat_trang_ct_id`),
  ADD UNIQUE KEY `gach_co_bat_trang_ct_code_unique` (`code`);

--
-- Indexes for table `gach_hoa_thong_gio`
--
ALTER TABLE `gach_hoa_thong_gio`
  ADD PRIMARY KEY (`gach_hoa_thong_gio_id`);

--
-- Indexes for table `gach_hoa_thong_gio_anh`
--
ALTER TABLE `gach_hoa_thong_gio_anh`
  ADD PRIMARY KEY (`gach_hoa_thong_gio_anh_id`),
  ADD KEY `gach_hoa_thong_gio_anh_gach_hoa_thong_gio_id_foreign` (`gach_hoa_thong_gio_id`);

--
-- Indexes for table `gach_hoa_thong_gio_ct`
--
ALTER TABLE `gach_hoa_thong_gio_ct`
  ADD PRIMARY KEY (`gach_hoa_thong_gio_ct_id`),
  ADD UNIQUE KEY `gach_hoa_thong_gio_ct_code_unique` (`code`);

--
-- Indexes for table `gach_trang_tri`
--
ALTER TABLE `gach_trang_tri`
  ADD PRIMARY KEY (`gach_trang_tri_id`);

--
-- Indexes for table `gach_trang_tri_ct`
--
ALTER TABLE `gach_trang_tri_ct`
  ADD PRIMARY KEY (`gach_trang_tri_ct_id`),
  ADD UNIQUE KEY `gach_trang_tri_ct_code_unique` (`code`);

--
-- Indexes for table `gia_tri_gach_hoa_thong_gio`
--
ALTER TABLE `gia_tri_gach_hoa_thong_gio`
  ADD PRIMARY KEY (`gia_tri_gach_hoa_thong_gio_id`),
  ADD KEY `gia_tri_gach_hoa_thong_gio_gach_hoa_thong_gio_id_foreign` (`gach_hoa_thong_gio_id`);

--
-- Indexes for table `gia_tri_vuot_troi`
--
ALTER TABLE `gia_tri_vuot_troi`
  ADD PRIMARY KEY (`gia_tri_vuot_troi_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lan_can_gom_xu`
--
ALTER TABLE `lan_can_gom_xu`
  ADD PRIMARY KEY (`lan_can_gom_xu_id`);

--
-- Indexes for table `linh_vat`
--
ALTER TABLE `linh_vat`
  ADD PRIMARY KEY (`linh_vat_id`),
  ADD KEY `linh_vat_linh_vat_phong_thuy_id_foreign` (`linh_vat_phong_thuy_id`);

--
-- Indexes for table `linh_vat_phong_thuy`
--
ALTER TABLE `linh_vat_phong_thuy`
  ADD PRIMARY KEY (`linh_vat_phong_thuy_id`);

--
-- Indexes for table `linh_vat_phong_thuy_anh`
--
ALTER TABLE `linh_vat_phong_thuy_anh`
  ADD PRIMARY KEY (`linh_vat_phong_thuy_anh_id`),
  ADD KEY `linh_vat_phong_thuy_anh_linh_vat_phong_thuy_id_foreign` (`linh_vat_phong_thuy_id`);

--
-- Indexes for table `linh_vat_phong_thuy_ct`
--
ALTER TABLE `linh_vat_phong_thuy_ct`
  ADD PRIMARY KEY (`linh_vat_phong_thuy_ct_id`),
  ADD UNIQUE KEY `linh_vat_phong_thuy_ct_code_unique` (`code`);

--
-- Indexes for table `mau_sac_ngoi_am_duong_ct`
--
ALTER TABLE `mau_sac_ngoi_am_duong_ct`
  ADD PRIMARY KEY (`mau_sac_ngoi_am_duong_ct_id`);

--
-- Indexes for table `mau_sac_ngoi_hai_co_ct`
--
ALTER TABLE `mau_sac_ngoi_hai_co_ct`
  ADD PRIMARY KEY (`mau_sac_ngoi_hai_co_ct_id`),
  ADD UNIQUE KEY `mau_sac_ngoi_hai_co_ct_code_unique` (`code`),
  ADD KEY `mau_sac_ngoi_hai_co_ct_ngoi_hai_co_ct_id_foreign` (`ngoi_hai_co_ct_id`);

--
-- Indexes for table `mau_sac_ngoi_hai_van_mieu_ct`
--
ALTER TABLE `mau_sac_ngoi_hai_van_mieu_ct`
  ADD PRIMARY KEY (`mau_sac_ngoi_hai_van_mieu_ct_id`),
  ADD UNIQUE KEY `mau_sac_ngoi_hai_van_mieu_ct_code_unique` (`code`),
  ADD KEY `fk_mau_sac_van_mieu` (`ngoi_hai_van_mieu_ct_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ngoi_am_duong`
--
ALTER TABLE `ngoi_am_duong`
  ADD PRIMARY KEY (`ngoi_am_duong_id`);

--
-- Indexes for table `ngoi_am_duong_ct`
--
ALTER TABLE `ngoi_am_duong_ct`
  ADD PRIMARY KEY (`ngoi_am_duong_ct_id`),
  ADD UNIQUE KEY `ngoi_am_duong_ct_code_unique` (`code`);

--
-- Indexes for table `ngoi_bo_noc_ct`
--
ALTER TABLE `ngoi_bo_noc_ct`
  ADD PRIMARY KEY (`ngoi_bo_noc_ct_id`);

--
-- Indexes for table `ngoi_hai_co_ct`
--
ALTER TABLE `ngoi_hai_co_ct`
  ADD PRIMARY KEY (`ngoi_hai_co_ct_id`);

--
-- Indexes for table `ngoi_hai_van_mieu`
--
ALTER TABLE `ngoi_hai_van_mieu`
  ADD PRIMARY KEY (`ngoi_hai_van_mieu_id`);

--
-- Indexes for table `ngoi_hai_van_mieu_ct`
--
ALTER TABLE `ngoi_hai_van_mieu_ct`
  ADD PRIMARY KEY (`ngoi_hai_van_mieu_ct_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `phan_loai_bo_noc_chu_van_ct`
--
ALTER TABLE `phan_loai_bo_noc_chu_van_ct`
  ADD PRIMARY KEY (`phan_loai_bo_noc_chu_van_ct_id`),
  ADD UNIQUE KEY `phan_loai_bo_noc_chu_van_ct_name_unique` (`name`),
  ADD UNIQUE KEY `phan_loai_bo_noc_chu_van_ct_code_unique` (`code`),
  ADD KEY `fk_phan_loai_chu_van` (`bo_noc_chu_van_ct_id`);

--
-- Indexes for table `phan_loai_ngoi_bo_noc_ct`
--
ALTER TABLE `phan_loai_ngoi_bo_noc_ct`
  ADD PRIMARY KEY (`phan_loai_ngoi_bo_noc_ct_id`),
  ADD UNIQUE KEY `phan_loai_ngoi_bo_noc_ct_name_unique` (`name`),
  ADD UNIQUE KEY `phan_loai_ngoi_bo_noc_ct_code_unique` (`code`),
  ADD KEY `fk_phan_loai_bo_noc` (`ngoi_bo_noc_ct_id`);

--
-- Indexes for table `phu_kien_ngoi`
--
ALTER TABLE `phu_kien_ngoi`
  ADD PRIMARY KEY (`phu_kien_ngoi_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `bo_noc_chu_van_ct`
--
ALTER TABLE `bo_noc_chu_van_ct`
  MODIFY `bo_noc_chu_van_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dau_an_gach_trang_tri`
--
ALTER TABLE `dau_an_gach_trang_tri`
  MODIFY `dau_an_gach_trang_tri_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `den_gom_su`
--
ALTER TABLE `den_gom_su`
  MODIFY `den_gom_su_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `den_gom_su_anh`
--
ALTER TABLE `den_gom_su_anh`
  MODIFY `den_gom_su_anh_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dinh_muc_gach_co_bat_trang`
--
ALTER TABLE `dinh_muc_gach_co_bat_trang`
  MODIFY `dinh_muc_gach_co_bat_trang_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dinh_muc_gach_hoa_thong_gio`
--
ALTER TABLE `dinh_muc_gach_hoa_thong_gio`
  MODIFY `dinh_muc_gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dinh_muc_gach_trang_tri`
--
ALTER TABLE `dinh_muc_gach_trang_tri`
  MODIFY `dinh_muc_gach_trang_tri_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dinh_muc_ngoi_am_duong`
--
ALTER TABLE `dinh_muc_ngoi_am_duong`
  MODIFY `dinh_muc_ngoi_am_duong_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dinh_muc_ngoi_hai_co`
--
ALTER TABLE `dinh_muc_ngoi_hai_co`
  MODIFY `dinh_muc_ngoi_hai_co_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dinh_muc_ngoi_hai_van_mieu`
--
ALTER TABLE `dinh_muc_ngoi_hai_van_mieu`
  MODIFY `dinh_muc_ngoi_hai_van_mieu_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gach_co_bat_trang`
--
ALTER TABLE `gach_co_bat_trang`
  MODIFY `gach_co_bat_trang_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gach_co_bat_trang_anh`
--
ALTER TABLE `gach_co_bat_trang_anh`
  MODIFY `gach_co_bat_trang_anh_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gach_co_bat_trang_ct`
--
ALTER TABLE `gach_co_bat_trang_ct`
  MODIFY `gach_co_bat_trang_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gach_hoa_thong_gio`
--
ALTER TABLE `gach_hoa_thong_gio`
  MODIFY `gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gach_hoa_thong_gio_anh`
--
ALTER TABLE `gach_hoa_thong_gio_anh`
  MODIFY `gach_hoa_thong_gio_anh_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gach_hoa_thong_gio_ct`
--
ALTER TABLE `gach_hoa_thong_gio_ct`
  MODIFY `gach_hoa_thong_gio_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gach_trang_tri`
--
ALTER TABLE `gach_trang_tri`
  MODIFY `gach_trang_tri_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gach_trang_tri_ct`
--
ALTER TABLE `gach_trang_tri_ct`
  MODIFY `gach_trang_tri_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gia_tri_gach_hoa_thong_gio`
--
ALTER TABLE `gia_tri_gach_hoa_thong_gio`
  MODIFY `gia_tri_gach_hoa_thong_gio_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gia_tri_vuot_troi`
--
ALTER TABLE `gia_tri_vuot_troi`
  MODIFY `gia_tri_vuot_troi_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lan_can_gom_xu`
--
ALTER TABLE `lan_can_gom_xu`
  MODIFY `lan_can_gom_xu_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `linh_vat`
--
ALTER TABLE `linh_vat`
  MODIFY `linh_vat_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `linh_vat_phong_thuy`
--
ALTER TABLE `linh_vat_phong_thuy`
  MODIFY `linh_vat_phong_thuy_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `linh_vat_phong_thuy_anh`
--
ALTER TABLE `linh_vat_phong_thuy_anh`
  MODIFY `linh_vat_phong_thuy_anh_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `linh_vat_phong_thuy_ct`
--
ALTER TABLE `linh_vat_phong_thuy_ct`
  MODIFY `linh_vat_phong_thuy_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mau_sac_ngoi_am_duong_ct`
--
ALTER TABLE `mau_sac_ngoi_am_duong_ct`
  MODIFY `mau_sac_ngoi_am_duong_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `mau_sac_ngoi_hai_co_ct`
--
ALTER TABLE `mau_sac_ngoi_hai_co_ct`
  MODIFY `mau_sac_ngoi_hai_co_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mau_sac_ngoi_hai_van_mieu_ct`
--
ALTER TABLE `mau_sac_ngoi_hai_van_mieu_ct`
  MODIFY `mau_sac_ngoi_hai_van_mieu_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ngoi_am_duong`
--
ALTER TABLE `ngoi_am_duong`
  MODIFY `ngoi_am_duong_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ngoi_am_duong_ct`
--
ALTER TABLE `ngoi_am_duong_ct`
  MODIFY `ngoi_am_duong_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ngoi_bo_noc_ct`
--
ALTER TABLE `ngoi_bo_noc_ct`
  MODIFY `ngoi_bo_noc_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ngoi_hai_co_ct`
--
ALTER TABLE `ngoi_hai_co_ct`
  MODIFY `ngoi_hai_co_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ngoi_hai_van_mieu`
--
ALTER TABLE `ngoi_hai_van_mieu`
  MODIFY `ngoi_hai_van_mieu_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ngoi_hai_van_mieu_ct`
--
ALTER TABLE `ngoi_hai_van_mieu_ct`
  MODIFY `ngoi_hai_van_mieu_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `phan_loai_bo_noc_chu_van_ct`
--
ALTER TABLE `phan_loai_bo_noc_chu_van_ct`
  MODIFY `phan_loai_bo_noc_chu_van_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phan_loai_ngoi_bo_noc_ct`
--
ALTER TABLE `phan_loai_ngoi_bo_noc_ct`
  MODIFY `phan_loai_ngoi_bo_noc_ct_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `phu_kien_ngoi`
--
ALTER TABLE `phu_kien_ngoi`
  MODIFY `phu_kien_ngoi_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dau_an_gach_trang_tri`
--
ALTER TABLE `dau_an_gach_trang_tri`
  ADD CONSTRAINT `dau_an_gach_trang_tri_gach_trang_tri_id_foreign` FOREIGN KEY (`gach_trang_tri_id`) REFERENCES `gach_trang_tri` (`gach_trang_tri_id`) ON DELETE CASCADE;

--
-- Constraints for table `den_gom_su_anh`
--
ALTER TABLE `den_gom_su_anh`
  ADD CONSTRAINT `den_gom_su_anh_den_gom_su_id_foreign` FOREIGN KEY (`den_gom_su_id`) REFERENCES `den_gom_su` (`den_gom_su_id`) ON DELETE CASCADE;

--
-- Constraints for table `gach_co_bat_trang_anh`
--
ALTER TABLE `gach_co_bat_trang_anh`
  ADD CONSTRAINT `gach_co_bat_trang_anh_gach_co_bat_trang_id_foreign` FOREIGN KEY (`gach_co_bat_trang_id`) REFERENCES `gach_co_bat_trang` (`gach_co_bat_trang_id`) ON DELETE CASCADE;

--
-- Constraints for table `gach_hoa_thong_gio_anh`
--
ALTER TABLE `gach_hoa_thong_gio_anh`
  ADD CONSTRAINT `gach_hoa_thong_gio_anh_gach_hoa_thong_gio_id_foreign` FOREIGN KEY (`gach_hoa_thong_gio_id`) REFERENCES `gach_hoa_thong_gio` (`gach_hoa_thong_gio_id`) ON DELETE CASCADE;

--
-- Constraints for table `gia_tri_gach_hoa_thong_gio`
--
ALTER TABLE `gia_tri_gach_hoa_thong_gio`
  ADD CONSTRAINT `gia_tri_gach_hoa_thong_gio_gach_hoa_thong_gio_id_foreign` FOREIGN KEY (`gach_hoa_thong_gio_id`) REFERENCES `gach_hoa_thong_gio` (`gach_hoa_thong_gio_id`) ON DELETE CASCADE;

--
-- Constraints for table `linh_vat`
--
ALTER TABLE `linh_vat`
  ADD CONSTRAINT `linh_vat_linh_vat_phong_thuy_id_foreign` FOREIGN KEY (`linh_vat_phong_thuy_id`) REFERENCES `linh_vat_phong_thuy` (`linh_vat_phong_thuy_id`) ON DELETE CASCADE;

--
-- Constraints for table `linh_vat_phong_thuy_anh`
--
ALTER TABLE `linh_vat_phong_thuy_anh`
  ADD CONSTRAINT `linh_vat_phong_thuy_anh_linh_vat_phong_thuy_id_foreign` FOREIGN KEY (`linh_vat_phong_thuy_id`) REFERENCES `linh_vat_phong_thuy` (`linh_vat_phong_thuy_id`) ON DELETE CASCADE;

--
-- Constraints for table `mau_sac_ngoi_hai_co_ct`
--
ALTER TABLE `mau_sac_ngoi_hai_co_ct`
  ADD CONSTRAINT `mau_sac_ngoi_hai_co_ct_ngoi_hai_co_ct_id_foreign` FOREIGN KEY (`ngoi_hai_co_ct_id`) REFERENCES `ngoi_hai_co_ct` (`ngoi_hai_co_ct_id`) ON DELETE CASCADE;

--
-- Constraints for table `mau_sac_ngoi_hai_van_mieu_ct`
--
ALTER TABLE `mau_sac_ngoi_hai_van_mieu_ct`
  ADD CONSTRAINT `fk_mau_sac_van_mieu` FOREIGN KEY (`ngoi_hai_van_mieu_ct_id`) REFERENCES `ngoi_hai_van_mieu_ct` (`ngoi_hai_van_mieu_ct_id`) ON DELETE CASCADE;

--
-- Constraints for table `phan_loai_bo_noc_chu_van_ct`
--
ALTER TABLE `phan_loai_bo_noc_chu_van_ct`
  ADD CONSTRAINT `fk_phan_loai_chu_van` FOREIGN KEY (`bo_noc_chu_van_ct_id`) REFERENCES `bo_noc_chu_van_ct` (`bo_noc_chu_van_ct_id`) ON DELETE CASCADE;

--
-- Constraints for table `phan_loai_ngoi_bo_noc_ct`
--
ALTER TABLE `phan_loai_ngoi_bo_noc_ct`
  ADD CONSTRAINT `fk_phan_loai_bo_noc` FOREIGN KEY (`ngoi_bo_noc_ct_id`) REFERENCES `ngoi_bo_noc_ct` (`ngoi_bo_noc_ct_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
