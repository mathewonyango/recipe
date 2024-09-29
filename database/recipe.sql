-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2024 at 10:35 PM
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
-- Database: `recipe`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('illuminate:queue:restart', 'i:1727642037;', 2043002037);

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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `rating` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `views` int(255) NOT NULL DEFAULT 0,
  `interaction_type` enum('view','rate','comment') NOT NULL DEFAULT 'comment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `recipe_id`, `comment`, `rating`, `created_at`, `updated_at`, `views`, `interaction_type`) VALUES
(1, 1, 1, 'This recipe was fantastic! I loved it.', 5, '2024-09-27 09:46:31', '2024-09-29 16:43:58', 5, 'comment'),
(2, 1, 1, 'This recipe was fantastic! I loved it so much and we are happy to be part of this.', 5, '2024-09-27 09:50:50', '2024-09-29 20:07:26', 11, 'comment'),
(3, 11, 1, 'This recipe was fantastic! I loved it so much and we are happy to be part of this.', 5, '2024-09-27 14:14:25', '2024-09-27 14:14:25', 0, 'comment'),
(4, 11, 1, 'This recipe was fantastic! I loved it so much and we are happy to be part of this.', 5, '2024-09-28 16:12:21', '2024-09-28 16:12:21', 0, 'comment'),
(5, 2, 1, 'This is a great recipe!', 4, '2024-09-28 19:40:43', '2024-09-28 19:40:43', 0, 'comment'),
(6, 3, 1, 'This is a great recipe,i agree with him!', 4, '2024-09-28 19:41:05', '2024-09-28 19:41:05', 0, 'comment'),
(7, 5, 1, 'This is a great recipe,i agree with him with a view!', 4, '2024-09-28 19:41:35', '2024-09-28 19:41:35', 0, 'comment'),
(8, 2, 1, 'This is a great recipe,i agree with him with a view!', 2, '2024-09-28 20:01:34', '2024-09-28 20:01:34', 0, 'comment'),
(9, 2, 1, 'This is a great recipe,i agree with him with a view!', 5, '2024-09-28 20:06:28', '2024-09-28 20:06:28', 0, 'comment'),
(10, 2, 1, '', 3, '2024-09-28 20:14:28', '2024-09-28 20:14:28', 0, 'comment'),
(11, 5, 1, '', 1, '2024-09-28 20:14:57', '2024-09-28 20:14:57', 0, 'comment'),
(12, 5, 1, '', 1, '2024-09-28 20:38:47', '2024-09-28 20:38:47', 0, 'comment'),
(13, 5, 1, '', 1, '2024-09-28 20:39:55', '2024-09-28 20:39:55', 0, 'comment'),
(14, 5, 1, '', 1, '2024-09-29 16:43:58', '2024-09-29 16:43:58', 0, 'comment');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `charges` decimal(10,2) DEFAULT NULL,
  `day_of_event` date DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `created_at`, `updated_at`, `location`, `time`, `charges`, `day_of_event`, `contact_number`, `topic_id`, `name`) VALUES
(7, '2024-09-27 20:19:32', '2024-09-27 20:19:32', 'Subukia ,Nakuru', '23:21:00', 2.00, '2024-09-28', '0702622569', 1, 'African cook Event'),
(8, '2024-09-27 20:20:28', '2024-09-27 20:20:28', 'Subukia ,Nakuru', '14:24:00', 3000.00, '2024-09-28', '0702622569', 1, 'afric'),
(9, '2024-09-27 22:24:18', '2024-09-27 22:24:18', 'Nairobi', '07:29:00', 30000.00, '2024-09-25', '0702622569', 1, 'Paste event'),
(10, '2024-09-27 22:30:23', '2024-09-27 22:30:23', 'Nairobi, Kenya', '18:34:00', 4000.00, '2024-09-19', '0702622569', 2, 'Future event'),
(11, '2024-09-27 22:37:02', '2024-09-27 22:37:02', 'Nairobi, Kenya', '18:41:00', 80000.00, '2024-11-16', '0702622569', 1, 'Now future event');

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
(47, '0001_01_01_000000_create_users_table', 1),
(48, '0001_01_01_000001_create_cache_table', 1),
(49, '0001_01_01_000002_create_jobs_table', 1),
(50, '2024_09_24_175932_create_personal_access_tokens_table', 1),
(51, '2024_09_25_080202_create_topics_table', 1),
(52, '2024_09_25_080315_create_recipes_table', 1),
(53, '2024_09_25_092110_update_topics_table', 1),
(54, '2024_09_25_130125_add_chef_id_to_recipes_table', 1),
(55, '2024_09_25_134735_add_role_and_status_to_users_table', 1),
(56, '2024_09_25_135213_add_approval_status_to_users_table', 1),
(57, '2024_09_25_230114_add_recipes_count_to_users_table', 1),
(58, '2024_09_25_230417_add_user_id_to_recipes_table', 1),
(59, '2024_09_26_083843_add_status_to_recipes_table', 1),
(60, '2024_09_26_093929_create_votes_table', 1),
(61, '2024_09_26_111716_add_chef_fields_to_users_table', 1),
(62, '2024_09_26_113724_add_chef_fields_to_users_table', 1),
(63, '2024_09_26_114015_add_missing_fields_to_recipes_table', 1),
(64, '2024_09_26_114315_create_events_table', 1),
(65, '2024_09_26_115106_add_event_id_to_topics_table', 1),
(66, '2024_09_26_115805_add_fields_to_events_table', 1),
(67, '2024_09_26_134626_add_topic_id_to_events_table', 1),
(68, '2024_09_26_135042_add_user_id_to_topics_table', 1),
(69, '2024_09_26_215711_add_vote_and_voter_to_recipes_table', 2),
(70, '2024_09_26_235904_update_fields_in_recipes_table', 3),
(71, '2024_09_27_085610_create_password_resets_table', 4),
(72, '2024_09_26_235551_add_fields_to_recipes_table', 5),
(73, '2024_09_27_093223_create_comments_table', 6),
(74, '2024_09_27_093916_create_comments_table', 7),
(75, '2024_09_27_163409_add_push_notification_to_users_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `servings` int(11) NOT NULL,
  `prep_time` int(11) NOT NULL,
  `cook_time` int(11) NOT NULL,
  `total_time` int(11) NOT NULL,
  `ingredients` text NOT NULL,
  `instructions` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `chef_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `image` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `difficulty_level` enum('easy','medium','hard') DEFAULT NULL,
  `nutritional_information` text DEFAULT NULL,
  `vote` int(11) NOT NULL DEFAULT 0,
  `voter` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `user_id`, `title`, `topic_id`, `servings`, `prep_time`, `cook_time`, `total_time`, `ingredients`, `instructions`, `created_at`, `updated_at`, `chef_id`, `status`, `image`, `tags`, `difficulty_level`, `nutritional_information`, `vote`, `voter`) VALUES
(1, 1, 'Spaghetti Bolognese', 2, 4, 15, 30, 45, '200g spaghetti, 100g minced beef, 1 onion, 1 can of tomato sauce', 'Boil spaghetti, fry beef and onions, add sauce, mix everything.', '2024-09-26 22:29:43', '2024-09-29 16:32:24', NULL, 'approved', NULL, NULL, NULL, NULL, 4, NULL),
(2, 3, 'Spaghetti African', 1, 4, 15, 30, 45, '200g spaghetti, 100g minced beef, 1 onion, 1 can of tomato sauce', 'Boil spaghetti, fry beef and onions, add sauce, mix everything.', '2024-09-26 22:34:11', '2024-09-26 22:35:29', NULL, 'approved', NULL, NULL, NULL, NULL, 1, NULL),
(3, 1, 'Spicy Garlic Pasta', 1, 4, 10, 20, 30, 'Pasta, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-27 00:07:09', '2024-09-27 00:07:09', NULL, 'draft', NULL, NULL, NULL, NULL, 0, NULL),
(4, 1, 'Spicy Garlic Pasta Trial', 1, 4, 10, 20, 30, 'Pasta, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-27 00:21:26', '2024-09-28 09:39:42', NULL, 'approved', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL),
(5, 1, 'Spicy Garlic Pasta Trial with topic id', 1, 4, 10, 20, 30, 'Pasta, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-27 00:28:37', '2024-09-27 00:28:37', NULL, 'draft', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL),
(6, 1, 'Spicy guide sea food', 1, 4, 10, 20, 30, 'Pasta, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-27 00:30:48', '2024-09-27 00:30:48', NULL, 'draft', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL),
(7, 1, 'Spicy guide sea food three fresh', 1, 4, 10, 20, 30, 'Pasta, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-27 00:34:27', '2024-09-27 00:34:27', NULL, 'approved', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL),
(8, 1, 'Spicy guide sea food three fresh', 1, 4, 10, 20, 30, 'Pasta, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-28 18:15:17', '2024-09-28 18:15:17', NULL, 'draft', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL),
(9, 1, 'Testing string recipe and some data here', 1, 4, 10, 20, 30, 'Pasta, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-28 19:03:46', '2024-09-28 19:03:46', NULL, 'draft', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL),
(10, 1, 'Cooking in the modern world and Easy ways to go about it', 1, 4, 10, 20, 30, 'Pasta,Easy, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-29 16:33:40', '2024-09-29 16:33:40', NULL, 'draft', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL),
(11, 1, 'Cooking in the modern world and Easy ways to go about it', 1, 4, 10, 20, 30, 'Pasta,Easy, Garlic, Olive Oil, Chili Flakes, Salt, Pepper, Parmesan Cheese', '1. Boil pasta. 2. Sauté garlic in olive oil. 3. Add chili flakes and cooked pasta. 4. Serve with Parmesan.', '2024-09-29 16:39:30', '2024-09-29 16:39:30', NULL, 'draft', NULL, 'spicy, vegetarian, pasta', 'easy', 'Calories: 400, Fats: 15g, Proteins: 10g', 0, NULL);

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
('6mzAdVNuyO571OHw8iaX0ra2gnNN4qdV9mcK98Qk', NULL, '102.68.76.239', 'PostmanRuntime/7.42.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTc3UmFhemlkcVlOcjYyR0llT1dnQk82ejFPVXJuT1RRS21CQU82MCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly81MS4yMC4xNDQuMTc4OjkwMDAvYXBpL3JlY2lwZXMiO319', 1727628238),
('9v4xHSrtT3CRABqjcHjerc2fZ2lq4h8IlcEcrbdO', NULL, '102.68.76.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ2g0eExodDZkVDJGeExXQkpnNFlPYXN1SHhLakIzanlqUjJkbmpQaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly81MS4yMC4xNDQuMTc4OjkwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1727639473),
('cH8JMRXSvuR0owNkc3PC7wqxqQFwoHLGtuc9rHvc', 1, '102.68.76.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidXdObGd4UjUwcEx0OU5CSlYwakg1ZEl2Y0dZM1QwTFNDWXVpcmhqNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly81MS4yMC4xNDQuMTc4OjkwMDAvYmFja3VwL3RyaWdnZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1727642051),
('DpeGVtZ5oRs1UMV9lf0cqw7pzal452NuhF9Y6j3A', NULL, '102.68.76.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiU0FNejBEUXltM3VLczJFUTBWNVVsUmhjVU12VXVsNWdKZEc5V1ExTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cDovLzUxLjIwLjE0NC4xNzg6OTAwMC9jaGVmL2FsbCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vNTEuMjAuMTQ0LjE3ODo5MDAwL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1727639433),
('pCxyxpv13I713MqbVlWSSATpTfdgMVTTbZybZrxD', NULL, '102.68.76.239', 'PostmanRuntime/7.42.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieWlvNTI3M3l2ZlpwemVoR05LMGlNeE5Fb2tmdHhma1BLbGxVWm80eiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly81MS4yMC4xNDQuMTc4OjkwMDAvYXBpL2NoZWZzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1727640503),
('vxUoe2GG9MJtPpMdBSu1ELunLNZ9ijrWsI6NXcXm', NULL, '102.68.76.239', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRVl5cnJKYUp0MVFhd0ZOTzRubmtLMXNGWkZrdktEUVhXQkVYM1kzdCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly81MS4yMC4xNDQuMTc4OjkwMDAvZGVwbG95Ijt9fQ==', 1727627921);

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('open','closed') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `user_id`, `name`, `description`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `event_id`) VALUES
(1, NULL, 'How to cook Ugali Cabbage', 'This is a test topic', '2024-09-18', '2024-09-28', 'open', '2024-09-26 21:15:29', '2024-09-26 21:15:29', 7),
(2, NULL, 'How to cook Kuku the KFC way', 'This is how to cook kuku the kfc way', '2024-09-10', '2024-09-26', 'open', '2024-09-26 21:26:01', '2024-09-26 21:26:01', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` text DEFAULT NULL,
  `experience_level` enum('Beginner','Intermediate','Professional') NOT NULL DEFAULT 'Beginner',
  `cuisine_type` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL DEFAULT 'Unknown',
  `certification` text DEFAULT NULL,
  `bio` varchar(500) DEFAULT NULL,
  `payment_status` enum('Paid','Unpaid') NOT NULL DEFAULT 'Unpaid',
  `social_media_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_media_links`)),
  `events_participated` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`events_participated`)),
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `recipes_count` int(11) NOT NULL DEFAULT 0,
  `username` varchar(255) DEFAULT NULL,
  `push_notification` enum('allow','deny') NOT NULL DEFAULT 'allow',
  `notification_preferences` varchar(255) DEFAULT 'email'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `profile_picture`, `experience_level`, `cuisine_type`, `location`, `certification`, `bio`, `payment_status`, `social_media_links`, `events_participated`, `remember_token`, `created_at`, `updated_at`, `role`, `status`, `approval_status`, `recipes_count`, `username`, `push_notification`, `notification_preferences`) VALUES
(1, 'Mathews onyango', 'mathewsagumbah@gmail.com', NULL, '$2y$12$uWA0aT8ykV9z2QU.pCbGbeeJW1oZzkV1KBsvfKmHhWfZxvHru9qNq', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-26 18:24:34', '2024-09-29 16:42:32', 'chef', 'active', 'pending', 0, NULL, 'allow', 'email'),
(2, 'John Doe', 'johndoe@example.com', NULL, '$2y$12$EN7s6d/DJ1UAWgXKEFa5auDUaqvRbiaJbh84QWFV.bglKLPy.gw8S', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-26 18:39:43', '2024-09-26 18:39:43', 'user', 'active', 'pending', 0, 'johnny', 'allow', 'email'),
(3, 'John Doe', 'john@example.com', NULL, '$2y$12$Dr8I16N6r.kgdXx8ZRjM1.eHARR2AAVfC8GEcT5KUiiMazA6nZWiq', NULL, 'Intermediate', 'Italian', 'New York', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-26 18:42:34', '2024-09-26 18:42:34', 'chef', 'active', 'pending', 0, 'johnchef', 'allow', 'email'),
(4, 'Abdi Abdi', 'abdi.abdi@example.co.ke', NULL, '$2y$12$j8i.1YjAH5axqcwbLtV9p.lAAmw9BvrJ8gv35fzaZ0JaOBkTGFOLS', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Paid', '{\"facebook\":\"https:\\/\\/facebook.com\\/abdichef\",\"twitter\":\"https:\\/\\/twitter.com\\/abdichef\",\"instagram\":\"https:\\/\\/instagram.com\\/abdichef\"}', NULL, NULL, '2024-09-26 19:01:32', '2024-09-26 19:01:32', 'chef', 'active', 'pending', 0, 'abdichef', 'allow', 'email'),
(5, 'Mathews Onyango', 'mathews.abdi@example.co.ke', NULL, '$2y$12$/Apn/vMBoRPajg0SaZtXj.3niVZwFXXC0v9ye0i5RkJLU3fRIuU8C', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/abdichef\",\"twitter\":\"https:\\/\\/twitter.com\\/abdichef\",\"instagram\":\"https:\\/\\/instagram.com\\/abdichef\"}', NULL, NULL, '2024-09-26 19:06:32', '2024-09-26 19:06:32', 'chef', 'active', 'pending', 0, 'Mathews', 'allow', 'email'),
(6, 'Karen Metah', 'karen.abdi@example.co.ke', NULL, '$2y$12$zdUhTRCSbAz4qKItluCgJeBx0GrfU3eodrlU.4Fxj2V8nGa1qPHHC', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-26 19:14:12', '2024-09-26 19:14:12', 'user', 'active', 'pending', 0, 'Metah', 'allow', 'email'),
(7, 'Ken Dejah', 'Dejah.abdi@example.co.ke', NULL, '$2y$12$2ytgQNmd.0fkMOHSMf9g/e4h60jocxPuw8pkBOCrrLWgXs93hV3u2', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-26 20:37:17', '2024-09-26 20:37:17', 'chef', 'active', 'pending', 0, 'Dejah', 'allow', 'email'),
(8, 'Maria Meli', 'Meli@example.com', NULL, '$2y$12$dk2tM3nOyG0Pgx5QEKbzC.ZP2.s.SeM9vIyaSeIk1ifZCUntAiQU.', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-26 20:54:02', '2024-09-26 20:54:02', 'user', 'active', 'pending', 0, 'Meli', 'allow', 'email'),
(9, 'Maria Kennedy', 'Maria.abdi@example.co.ke', NULL, '$2y$12$3u2iGzaRweKSlI8cFo/WGOrh5eCxhMqE8Gt8NbuKrEM40i1RtCupG', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-26 20:55:06', '2024-09-26 20:55:06', 'chef', 'active', 'pending', 0, 'Maria Ken', 'allow', 'email'),
(10, 'Tonny Kennedy', 'Tonny.abdi@example.co.ke', NULL, '$2y$12$RlTkR8jya3S87aiKkpgChO.K9sSodud8ExE.fPIB4HcuuHhlnaPK6', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-26 21:02:37', '2024-09-26 21:02:37', 'chef', 'active', 'pending', 0, 'Tonny Ken', 'allow', 'email'),
(11, 'ABDI', '27870abdirahmanabdidict19s@gmail.com', NULL, '$2y$12$BGOeltNLZJda4/KgZ7XCs.VxvK.npoc2DS3fFW6w/aeuDVkaIMUES', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-26 23:10:32', '2024-09-26 23:10:32', 'user', 'active', 'pending', 0, NULL, 'allow', 'email'),
(12, 'Tonny Kennedy Mwai', 'Mwai.abdi@example.co.ke', NULL, '$2y$12$pGjtMStIQn2SFaqsqI8yEeB8Stuo6v5B2BY4YW2NviGhSmuMtdSIy', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:05:52', '2024-09-28 12:05:52', 'chef', 'active', 'pending', 0, 'Tonny MWai', 'allow', '\"null\"'),
(13, 'Evance Juma Meli Meli', 'Evanceevancejuma@gmail.com', NULL, '$2y$12$B9pvmbRB8Gfiei76.ekfGeZ41e.nAkEossuS7ZwRXDKfXZR4r5Znu', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-28 12:06:20', '2024-09-28 12:06:20', 'user', 'active', 'pending', 0, 'Evance', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(14, 'Michael Kennedy Mwai', 'Michael.abdi@example.co.ke', NULL, '$2y$12$ePTo2Co/mczROebHIxUkhuxwC1sZvtTpG4rsx325SzTnsrYWa/yKe', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:09:10', '2024-09-28 12:09:10', 'chef', 'active', 'pending', 0, 'Michael MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(15, 'Michael George Mwai', 'George.abdi@example.co.ke', NULL, '$2y$12$zP9ZzQdLbDe5JDDqZZv1UeoOzQ/ruNcaYv2YEk6lk0ZeFeGbKQipG', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:13:41', '2024-09-28 12:13:41', 'chef', 'active', 'pending', 0, 'George MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(16, 'Eunice George Mwai', 'Eunice.abdi@gmail.com', NULL, '$2y$12$ejjzFdD1eB5bshBoURFGNeA7SREa60fjt16/SAke6NX5/lbBABsNq', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:17:03', '2024-09-28 12:17:03', 'chef', 'active', 'pending', 0, 'Eunice MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(17, 'Cal George Mwai', 'Cal.abdi@gmail.com', NULL, '$2y$12$hTEvyvsSsaGyxULHiiPbxeykjpRVWWegZCEvFYsYpnZiJ8XPvT.4K', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:33:21', '2024-09-28 12:33:21', 'chef', 'active', 'pending', 0, 'Cal MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(18, 'Cal George Mwai', 'Cali.abdi@gmail.com', NULL, '$2y$12$gKtxwuhapr9owC3.7gSH5uzUNDJXBwXTbuzHu/rtZZ3Szv7n/HKnG', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:37:50', '2024-09-28 12:37:50', 'chef', 'active', 'pending', 0, 'Cali MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(19, 'Cali b George Mwai', 'Cali.b.abdi@gmail.com', NULL, '$2y$12$ZHHGWHAyfymefAEbYA.kxOFhVVuD4a/lCHz7w9ZLvQrLPHpSA4uga', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:40:25', '2024-09-28 12:40:25', 'chef', 'active', 'pending', 0, 'Cali.b MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(20, 'Cali b garry George Mwai', 'Cali.b.garry.abdi@gmail.com', NULL, '$2y$12$2.h1CuxWIlZ13FdSJboPLutz5dMROESpUeb4fQ4NmcNSoPziGDOsC', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:45:12', '2024-09-28 12:45:12', 'chef', 'active', 'pending', 0, 'Cali.b.garry. MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(21, 'Meli b garry George Kitchen', 'Kitchen.b.Kitchen.abdi@gmail.com', NULL, '$2y$12$R1C.G5nZiv4S6KNiLF0NH.2PewPOifNREiUt0OwHEXJWTeZKqcMnq', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 12:52:02', '2024-09-28 12:52:02', 'chef', 'active', 'pending', 0, 'Kitchen.b.garry. MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(22, 'ABDI MWenyewe', 'abdikoricha@gmail.com', NULL, '$2y$12$UsxDzPSSFZI19wFpFngW9O7385.9N7TYqjauC7SvLLkZNSSnX5nSu', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-28 15:17:04', '2024-09-28 15:17:04', 'user', 'active', 'pending', 0, 'Koricha', 'allow', '\"[\\\"email\\\"]\"'),
(23, 'Meli b garry Power Kitchen', 'Power.b.Kitchen.abdi@gmail.com', NULL, '$2y$12$SKxjui3lA755R.nmU5EcRuXwl3PviMPna8CIAnhPfH4bBR.7j79z.', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-28 16:24:09', '2024-09-28 16:24:09', 'chef', 'active', 'pending', 0, 'Power.b.garry. MWai', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(24, 'ABDILe fala', 'abdikoricha31@gmail.com', NULL, '$2y$12$36I4NRnAHcy8Xpd6iGWdourUeCygidwtx8H0QFtioMLUua11uvnCC', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-29 07:50:04', '2024-09-29 07:50:04', 'user', 'active', 'pending', 0, 'Koricha31', 'allow', '\"[\\\"email\\\"]\"'),
(25, 'ABDi', 'ABDi@gmail.com', NULL, '$2y$12$Y6UBBrcxFw0GgA7/VLxI3.SzpQuohanbb61S1Yq1yM5cpUdPqOeoq', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-29 08:29:40', '2024-09-29 08:29:40', 'user', 'active', 'pending', 0, 'abdi', 'allow', '\"[\\\"email\\\"]\"'),
(26, 'ABDILe Kru', 'abdikoricha37@gmail.com', NULL, '$2y$12$7h6aVU5eXbAyNMm8LkD0YOM7U7bzo9kL0Uz.4VhUSejacWAdHjHgS', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-29 08:54:43', '2024-09-29 08:54:43', 'user', 'active', 'pending', 0, 'Koricha37', 'allow', '\"[\\\"email\\\"]\"'),
(27, 'ABDi API accpeted', 'apiAcceot@gmail.com', NULL, '$2y$12$.IWUY7FbQlJh1r9Ih/T2u.fLw0ogrU2ZJLxWpMv24lHIdMKxI.sIW', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-29 10:49:49', '2024-09-29 10:49:49', 'user', 'active', 'pending', 0, 'abdiAPI', 'allow', '\"[\\\"email\\\"]\"'),
(28, 'Koricha ABDI AO', 'abdiKorichaAO@gmail.com', NULL, '$2y$12$bzOJMNFWERp6bBqllqB1ce1pV2pZnn9xGCbVQREFG1XB0y3tsj5S6', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-29 11:22:11', '2024-09-29 11:22:11', 'chef', 'active', 'pending', 0, 'ABDIKOricha', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"'),
(29, 'ABDi API accpeted', 'apiAcceotAccept@gmail.com', NULL, '$2y$12$5xRDtheiiV/2s6nsHBOFQusKpvg55NtzTQGNRIaDWXjSN08sXi8he', NULL, 'Beginner', NULL, 'Unknown', NULL, NULL, 'Unpaid', NULL, NULL, NULL, '2024-09-29 16:27:35', '2024-09-29 16:27:35', 'user', 'active', 'pending', 0, 'abdiAPIAccept', 'allow', '\"[\\\"email\\\"]\"'),
(30, 'Koricha ABDI AO Group', 'abdiKorichaAOGROUP@gmail.com', NULL, '$2y$12$0Gce.tuTRL.CL8naV2LtL.BVfGNp31mA9qywyEUZddrhfmZQ0oA2G', 'https://example.com/profile-pic.jpg', 'Professional', 'Swahili', 'Nairobi', 'Certified Swahili Culinary Expert', 'A professional chef specializing in authentic Swahili dishes with over 10 years of experience.', 'Unpaid', '{\"facebook\":\"https:\\/\\/facebook.com\\/metah\",\"twitter\":\"https:\\/\\/twitter.com\\/metah\",\"instagram\":\"https:\\/\\/instagram.com\\/metah\"}', NULL, NULL, '2024-09-29 20:08:06', '2024-09-29 20:08:06', 'chef', 'active', 'pending', 0, 'ABDIKOrichaGROUP', 'allow', '\"[\\\"email\\\",\\\"sms\\\"]\"');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recipe_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `recipe_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 6, '2024-09-26 22:31:11', '2024-09-26 22:31:11'),
(2, 2, 6, '2024-09-26 22:35:29', '2024-09-26 22:35:29'),
(3, 1, 6, '2024-09-26 22:36:35', '2024-09-26 22:36:35'),
(4, 1, 1, '2024-09-28 16:05:05', '2024-09-28 16:05:05'),
(5, 1, 2, '2024-09-29 16:32:24', '2024-09-29 16:32:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_recipe_id_foreign` (`recipe_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `events_topic_id_foreign` (`topic_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

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
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipes_topic_id_foreign` (`topic_id`),
  ADD KEY `recipes_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topics_event_id_foreign` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `votes_recipe_id_foreign` (`recipe_id`),
  ADD KEY `votes_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_recipe_id_foreign` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
