-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 31, 2025 at 12:52 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `transcript`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `credits` int(11) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `title`, `description`, `credits`, `department_id`, `program_id`, `level`, `semester`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'HIM101', 'Introduction to Health Information Systems', 'Fundamentals of health information management and systems.', 3, 1, 3, 100, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(2, 'HIM201', 'Health Data Analytics', 'Analysis and interpretation of health data.', 3, 1, 3, 200, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(3, 'HPR101', 'Health Promotion Principles', 'Basic principles of health promotion and education.', 3, 2, 4, 100, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(4, 'HPR201', 'Community Health Programs', 'Design and implementation of community health programs.', 3, 2, 4, 200, 2, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(5, 'ENV101', 'Environmental Health Principles', 'Introduction to environmental health concepts.', 3, 3, 1, 100, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(6, 'ENV201', 'Environmental Assessment', 'Methods for environmental health assessment.', 3, 3, 1, 200, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(7, 'WAS101', 'Water Quality Management', 'Principles of water quality assessment and management.', 3, 4, 5, 100, 2, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(8, 'WAS201', 'Sanitation Systems', 'Design and management of sanitation systems.', 3, 4, 5, 200, 2, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(9, 'PUB101', 'Introduction to Public Health', 'Fundamental concepts in public health.', 3, 5, 6, 100, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(10, 'PUB201', 'Public Health Policy', 'Development and analysis of public health policies.', 3, 5, 6, 200, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(11, 'EPI101', 'Introduction to Epidemiology', 'Basic epidemiological concepts and methods.', 3, 6, 7, 100, 2, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(12, 'EPI201', 'Disease Surveillance', 'Methods and systems for disease surveillance.', 3, 6, 7, 200, 2, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(13, 'NUT101', 'Introduction to Nutrition', 'Basic principles of human nutrition.', 3, 7, 8, 100, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(14, 'NUT201', 'Clinical Nutrition', 'Nutritional therapy in clinical settings.', 3, 7, 8, 200, 1, 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_user`
--

CREATE TABLE `course_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_user`
--

INSERT INTO `course_user` (`id`, `course_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 9, 13, NULL, NULL),
(2, 10, 13, NULL, NULL),
(3, 3, 13, NULL, NULL),
(4, 3, 14, NULL, NULL),
(5, 7, 14, NULL, NULL),
(6, 5, 14, NULL, NULL),
(7, 1, 23, NULL, NULL),
(8, 2, 23, NULL, NULL),
(9, 9, 24, NULL, NULL),
(10, 10, 24, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `faculty_id` bigint(20) UNSIGNED NOT NULL,
  `head_name` varchar(255) DEFAULT NULL,
  `head_email` varchar(255) DEFAULT NULL,
  `head_phone` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `faculty_id`, `head_name`, `head_email`, `head_phone`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Health Information Management', 'HIM', 'Managing health information systems and data analytics.', 1, 'Dr. Grace Adjei', 'grace.adjei@schoolofhygiene.edu.gh', '+233 24 567 8901', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(2, 'Health Promotion', 'HPR', 'Promoting health awareness and community health programs.', 1, 'Dr. Comfort Nyarko', 'comfort.nyarko@schoolofhygiene.edu.gh', '+233 24 678 9012', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(3, 'Environmental Health', 'ENV', 'Environmental health assessment and management.', 2, 'Dr. Samuel Owusu', 'samuel.owusu@schoolofhygiene.edu.gh', '+233 24 789 0123', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(4, 'Water and Sanitation', 'WAS', 'Water quality management and sanitation practices.', 2, 'Dr. Mary Agyemang', 'mary.agyemang@schoolofhygiene.edu.gh', '+233 24 890 1234', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(5, 'Public Health', 'PUB', 'Public health policy and community health management.', 3, 'Dr. Joseph Appiah', 'joseph.appiah@schoolofhygiene.edu.gh', '+233 24 901 2345', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(6, 'Epidemiology', 'EPI', 'Disease surveillance and epidemiological research.', 3, 'Dr. Akosua Bonsu', 'akosua.bonsu@schoolofhygiene.edu.gh', '+233 24 012 3456', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(7, 'Nutrition and Dietetics', 'NUT', 'Nutritional science and dietary therapy.', 4, 'Dr. Patience Asiedu', 'patience.asiedu@schoolofhygiene.edu.gh', '+233 24 123 4567', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `dean_name` varchar(255) DEFAULT NULL,
  `dean_email` varchar(255) DEFAULT NULL,
  `dean_phone` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `name`, `code`, `description`, `dean_name`, `dean_email`, `dean_phone`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Faculty of Health Sciences', 'FHS', 'The Faculty of Health Sciences focuses on comprehensive health education and research.', 'Dr. Sarah Mensah', 'sarah.mensah@schoolofhygiene.edu.gh', '+233 24 123 4567', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(2, 'Faculty of Environmental Health', 'FEH', 'Dedicated to environmental health education and sustainable practices.', 'Prof. Kwame Asante', 'kwame.asante@schoolofhygiene.edu.gh', '+233 24 234 5678', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(3, 'Faculty of Public Health', 'FPH', 'Leading public health education and community health initiatives.', 'Dr. Ama Osei', 'ama.osei@schoolofhygiene.edu.gh', '+233 24 345 6789', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL),
(4, 'Faculty of Nutrition and Dietetics', 'FND', 'Promoting nutrition education and healthy dietary practices.', 'Dr. Kofi Boateng', 'kofi.boateng@schoolofhygiene.edu.gh', '+233 24 456 7890', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_09_09_142338_add_soft_deletes_to_users_table', 1),
(6, '2025_09_09_152047_create_faculties_table', 1),
(7, '2025_09_09_152048_create_departments_table', 1),
(8, '2025_09_09_152049_create_students_table', 1),
(9, '2025_09_09_152050_create_courses_table', 1),
(10, '2025_09_09_152054_update_users_table_for_system_access', 1),
(11, '2025_09_09_152529_create_permission_tables', 1),
(12, '2025_09_10_175941_create_results_table', 1),
(13, '2025_09_20_110738_create_transcripts_table', 1),
(14, '2025_09_20_110739_create_transcript_requests_table', 1),
(15, '2025_09_20_110741_create_transcript_courses_table', 1),
(16, '2025_09_20_161241_add_email_tracking_to_transcripts_table', 1),
(17, '2025_09_21_021857_add_verification_fields_to_transcripts_table', 1),
(18, '2025_10_28_000001_create_course_user_table', 1),
(19, '2025_10_30_000100_add_program_id_to_users_table', 2),
(20, '2025_10_30_000200_create_student_courses_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(2, 'App\\Models\\User', 7),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16),
(2, 'App\\Models\\User', 17),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(3, 'App\\Models\\User', 11),
(3, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 18),
(3, 'App\\Models\\User', 19),
(3, 'App\\Models\\User', 20),
(3, 'App\\Models\\User', 21),
(3, 'App\\Models\\User', 22),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 14),
(4, 'App\\Models\\User', 23),
(4, 'App\\Models\\User', 24);

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
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(2, 'view_any_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(3, 'create_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(4, 'update_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(5, 'restore_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(6, 'restore_any_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(7, 'replicate_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(8, 'reorder_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(9, 'delete_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(10, 'delete_any_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(11, 'force_delete_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(12, 'force_delete_any_course', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(13, 'view_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(14, 'view_any_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(15, 'create_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(16, 'update_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(17, 'restore_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(18, 'restore_any_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(19, 'replicate_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(20, 'reorder_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(21, 'delete_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(22, 'delete_any_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(23, 'force_delete_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(24, 'force_delete_any_department', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(25, 'view_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(26, 'view_any_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(27, 'create_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(28, 'update_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(29, 'restore_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(30, 'restore_any_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(31, 'replicate_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(32, 'reorder_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(33, 'delete_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(34, 'delete_any_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(35, 'force_delete_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(36, 'force_delete_any_faculty', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(37, 'view_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(38, 'view_any_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(39, 'create_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(40, 'update_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(41, 'restore_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(42, 'restore_any_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(43, 'replicate_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(44, 'reorder_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(45, 'delete_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(46, 'delete_any_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(47, 'force_delete_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(48, 'force_delete_any_program', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(49, 'view_role', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(50, 'view_any_role', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(51, 'create_role', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(52, 'update_role', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(53, 'delete_role', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(54, 'delete_any_role', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(55, 'view_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(56, 'view_any_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(57, 'create_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(58, 'update_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(59, 'restore_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(60, 'restore_any_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(61, 'replicate_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(62, 'reorder_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(63, 'delete_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(64, 'delete_any_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(65, 'force_delete_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(66, 'force_delete_any_student', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(67, 'view_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(68, 'view_any_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(69, 'create_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(70, 'update_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(71, 'restore_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(72, 'restore_any_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(73, 'replicate_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(74, 'reorder_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(75, 'delete_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(76, 'delete_any_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(77, 'force_delete_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(78, 'force_delete_any_transcript', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(79, 'view_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(80, 'view_any_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(81, 'create_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(82, 'update_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(83, 'restore_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(84, 'restore_any_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(85, 'replicate_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(86, 'reorder_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(87, 'delete_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(88, 'delete_any_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(89, 'force_delete_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(90, 'force_delete_any_transcript::request', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(91, 'view_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(92, 'view_any_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(93, 'create_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(94, 'update_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(95, 'restore_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(96, 'restore_any_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(97, 'replicate_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(98, 'reorder_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(99, 'delete_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(100, 'delete_any_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(101, 'force_delete_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(102, 'force_delete_any_user', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(103, 'widget_SystemStatsWidget', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(104, 'widget_QuickActionsWidget', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(105, 'widget_TranscriptRequestsChartWidget', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(106, 'widget_ComprehensiveAnalyticsWidget', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(107, 'widget_RequestStatusChartWidget', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(108, 'widget_RecentActivityWidget', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(109, 'widget_TranscriptStatusChartWidget', 'web', '2025-10-29 14:34:57', '2025-10-29 14:34:57'),
(110, 'widget_FacultyStatsWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(111, 'widget_DeliveryAnalyticsWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(112, 'widget_DepartmentStatsWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(113, 'widget_MonthlyAnalyticsWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(114, 'widget_RecentActivityTableWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(115, 'widget_PerformanceMetricsWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(116, 'widget_TopDepartmentsWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(117, 'widget_RecentTranscriptRequestsTableWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58'),
(118, 'widget_RecentTranscriptsTableWidget', 'web', '2025-10-29 14:34:58', '2025-10-29 14:34:58');

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
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `duration_years` tinyint(3) UNSIGNED NOT NULL DEFAULT 2,
  `total_credits` smallint(5) UNSIGNED DEFAULT NULL,
  `level` enum('certificate','diploma','degree') NOT NULL DEFAULT 'diploma',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `code`, `name`, `description`, `department_id`, `duration_years`, `total_credits`, `level`, `status`, `created_at`, `updated_at`) VALUES
(1, 'DEH', 'Diploma in Environmental Health', NULL, 3, 2, NULL, 'diploma', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34'),
(2, 'CPH', 'Certificate in Public Health', NULL, 5, 1, NULL, 'certificate', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34'),
(3, 'DHM', 'Diploma in Health Information Management', NULL, 1, 2, NULL, 'diploma', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34'),
(4, 'CHP', 'Certificate in Health Promotion', NULL, 2, 1, NULL, 'certificate', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34'),
(5, 'BWS', 'BSc in Water and Sanitation', NULL, 4, 4, NULL, 'degree', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34'),
(6, 'BPH', 'BSc in Public Health', NULL, 5, 4, NULL, 'degree', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34'),
(7, 'DEN', 'Diploma in Epidemiology', NULL, 6, 2, NULL, 'diploma', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34'),
(8, 'BND', 'BSc in Nutrition and Dietetics', NULL, 7, 4, NULL, 'degree', 'active', '2025-10-29 14:00:34', '2025-10-29 14:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `score` int(11) DEFAULT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `is_resit` tinyint(1) NOT NULL DEFAULT 0,
  `academic_year` varchar(255) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`id`, `student_id`, `course_id`, `score`, `grade`, `gpa`, `is_resit`, `academic_year`, `semester`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 95, 'A', 4.00, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(2, 1, 8, 57, 'D', 1.00, 0, '2023/2024', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(3, 1, 10, 76, 'B', 3.00, 0, '2023/2024', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(4, 1, 11, 55, 'D', 1.00, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(5, 1, 12, 76, 'B', 3.00, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(6, 1, 13, 34, 'F', 0.00, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(7, 1, 14, 68, 'C', 2.00, 0, '2023/2024', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(8, 2, 1, 77, 'B', 3.00, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(9, 2, 2, 58, 'D', 1.00, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(10, 2, 5, 13, 'F', 0.00, 1, '2023/2024', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(11, 2, 7, 67, 'C', 2.00, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(12, 2, 8, 66, 'C', 2.00, 0, '2020/2021', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(13, 2, 12, 73, 'C+', 2.50, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(14, 2, 13, 71, 'C+', 2.50, 0, '2020/2021', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(15, 2, 14, 73, 'C+', 2.50, 0, '2023/2024', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(16, 3, 2, 68, 'C', 2.00, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(17, 3, 3, 64, 'D+', 1.50, 0, '2020/2021', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(18, 3, 6, 75, 'B', 3.00, 0, '2020/2021', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(19, 3, 7, 62, 'D+', 1.50, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(20, 3, 8, 57, 'D', 1.00, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(21, 3, 11, 68, 'C', 2.00, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(22, 4, 1, 74, 'C+', 2.50, 0, '2020/2021', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(23, 4, 2, 85, 'A', 4.00, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(24, 4, 3, 73, 'C+', 2.50, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(25, 4, 5, 76, 'B', 3.00, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(26, 4, 6, 74, 'C+', 2.50, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(27, 4, 8, 48, 'F', 0.00, 0, '2023/2024', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(28, 4, 10, 78, 'B', 3.00, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(29, 4, 11, 98, 'A', 4.00, 0, '2023/2024', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(30, 4, 12, 70, 'C+', 2.50, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(31, 5, 1, 62, 'D+', 1.50, 0, '2023/2024', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(32, 5, 3, 70, 'C+', 2.50, 0, '2023/2024', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(33, 5, 4, 83, 'B+', 3.50, 0, '2023/2024', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(34, 5, 9, 56, 'D', 1.00, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(35, 5, 10, 64, 'D+', 1.50, 0, '2020/2021', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(36, 5, 11, 82, 'B+', 3.50, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(37, 5, 14, 59, 'D', 1.00, 0, '2022/2023', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(38, 6, 1, 1, 'F', 0.00, 1, '2020/2021', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(39, 6, 3, 64, 'D+', 1.50, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(40, 6, 4, 63, 'D+', 1.50, 0, '2021/2022', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(41, 6, 7, 80, 'B+', 3.50, 0, '2020/2021', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(42, 6, 9, 90, 'A', 4.00, 0, '2020/2021', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(43, 6, 11, 84, 'B+', 3.50, 0, '2023/2024', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(44, 6, 12, 80, 'B+', 3.50, 0, '2023/2024', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(45, 6, 13, 61, 'D+', 1.50, 0, '2022/2023', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(46, 6, 14, 21, 'F', 0.00, 1, '2023/2024', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(47, 7, 1, 90, 'A', 4.00, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(48, 7, 2, 67, 'C', 2.00, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(49, 7, 5, 82, 'B+', 3.50, 0, '2020/2021', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(50, 7, 6, 15, 'F', 0.00, 1, '2020/2021', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(51, 7, 8, 73, 'C+', 2.50, 0, '2022/2023', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(52, 7, 9, 59, 'D', 1.00, 0, '2021/2022', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(53, 7, 10, 78, 'B', 3.00, 0, '2023/2024', 1, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(54, 7, 11, 59, 'D', 1.00, 0, '2022/2023', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35'),
(55, 7, 12, 62, 'D+', 1.50, 0, '2020/2021', 2, '2025-10-29 14:00:35', '2025-10-29 14:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-10-29 14:00:33', '2025-10-29 14:00:33'),
(2, 'faculty_admin', 'web', '2025-10-29 14:00:33', '2025-10-29 14:00:33'),
(3, 'department_admin', 'web', '2025-10-29 14:00:33', '2025-10-29 14:00:33'),
(4, 'lecturer', 'web', '2025-10-29 14:00:33', '2025-10-29 14:00:33');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 4),
(2, 1),
(2, 4),
(3, 1),
(3, 4),
(4, 1),
(4, 4),
(5, 1),
(5, 4),
(6, 1),
(6, 4),
(7, 1),
(7, 4),
(8, 1),
(8, 4),
(9, 1),
(9, 4),
(10, 1),
(10, 4),
(11, 1),
(11, 4),
(12, 1),
(12, 4),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(37, 4),
(38, 1),
(38, 4),
(39, 1),
(39, 4),
(40, 1),
(40, 4),
(41, 1),
(41, 4),
(42, 1),
(42, 4),
(43, 1),
(43, 4),
(44, 1),
(44, 4),
(45, 1),
(45, 4),
(46, 1),
(46, 4),
(47, 1),
(47, 4),
(48, 1),
(48, 4),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(55, 4),
(56, 1),
(56, 4),
(57, 1),
(57, 4),
(58, 1),
(58, 4),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `nationality` varchar(255) NOT NULL DEFAULT 'Ghanaian',
  `address` text DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `year_of_admission` int(11) NOT NULL,
  `year_of_completion` int(11) DEFAULT NULL,
  `status` enum('active','graduated','dropped') NOT NULL DEFAULT 'active',
  `photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `first_name`, `last_name`, `middle_name`, `email`, `phone`, `date_of_birth`, `gender`, `nationality`, `address`, `department_id`, `program_id`, `year_of_admission`, `year_of_completion`, `status`, `photo_path`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SOH2021001', 'Kwame', 'Asante', 'Kofi', 'kwame.asante@student.schoolofhygiene.edu.gh', '+233 24 111 1111', '2000-05-15', 'male', 'Ghanaian', '123 Tamale Street, Tamale, Northern Region', 1, 3, 2021, 2023, 'graduated', NULL, '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL),
(2, 'SOH2021002', 'Ama', 'Osei', 'Serwaa', 'ama.osei@student.schoolofhygiene.edu.gh', '+233 24 222 2222', '1999-08-22', 'female', 'Ghanaian', '456 Bolgatanga Road, Bolgatanga, Upper East Region', 2, 4, 2021, 2022, 'graduated', NULL, '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL),
(3, 'SOH2022001', 'Kofi', 'Mensah', 'Nana', 'kofi.mensah@student.schoolofhygiene.edu.gh', '+233 24 333 3333', '2001-12-10', 'male', 'Ghanaian', '789 Wa Avenue, Wa, Upper West Region', 3, 1, 2022, NULL, 'active', NULL, '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL),
(4, 'SOH2022002', 'Akosua', 'Boateng', 'Adwoa', 'akosua.boateng@student.schoolofhygiene.edu.gh', '+233 24 444 4444', '2000-03-18', 'female', 'Ghanaian', '321 Kumasi Street, Kumasi, Ashanti Region', 4, 5, 2022, NULL, 'active', NULL, '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL),
(5, 'SOH2023001', 'Samuel', 'Appiah', 'Kwame', 'samuel.appiah@student.schoolofhygiene.edu.gh', '+233 24 555 5555', '2002-07-05', 'male', 'Ghanaian', '654 Accra Road, Accra, Greater Accra Region', 5, 6, 2023, NULL, 'active', NULL, '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL),
(6, 'SOH2023002', 'Mary', 'Agyemang', 'Serwaa', 'mary.agyemang@student.schoolofhygiene.edu.gh', '+233 24 666 6666', '2001-11-30', 'female', 'Ghanaian', '987 Cape Coast Street, Cape Coast, Central Region', 6, 7, 2023, NULL, 'active', NULL, '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL),
(7, 'SOH2024001', 'Joseph', 'Bonsu', 'Kofi', 'joseph.bonsu@student.schoolofhygiene.edu.gh', '+233 24 777 7777', '2003-01-12', 'male', 'Ghanaian', '147 Takoradi Avenue, Takoradi, Western Region', 7, 8, 2024, NULL, 'active', NULL, '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `academic_year` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL,
  `grade` varchar(255) DEFAULT NULL,
  `gpa` decimal(3,2) DEFAULT NULL,
  `credit_hours` int(11) NOT NULL,
  `status` enum('enrolled','completed','failed','resit') NOT NULL DEFAULT 'enrolled',
  `is_resit` tinyint(1) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transcripts`
--

CREATE TABLE `transcripts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `transcript_number` varchar(255) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `program` varchar(255) NOT NULL,
  `year_of_completion` int(11) NOT NULL,
  `cgpa` decimal(3,2) NOT NULL,
  `class_of_degree` varchar(255) NOT NULL,
  `qr_code` text DEFAULT NULL,
  `status` enum('draft','issued','verified') NOT NULL DEFAULT 'draft',
  `issued_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` varchar(255) DEFAULT NULL,
  `issued_by` bigint(20) UNSIGNED DEFAULT NULL,
  `recipient_email` varchar(255) DEFAULT NULL,
  `email_sent_at` timestamp NULL DEFAULT NULL,
  `delivery_method` enum('pickup','email','mail') NOT NULL DEFAULT 'pickup',
  `delivery_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transcripts`
--

INSERT INTO `transcripts` (`id`, `uuid`, `transcript_number`, `student_id`, `program`, `year_of_completion`, `cgpa`, `class_of_degree`, `qr_code`, `status`, `issued_at`, `verified_at`, `verified_by`, `issued_by`, `recipient_email`, `email_sent_at`, `delivery_method`, `delivery_notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1c2f3b66-d221-47ce-9b77-429d806a8ceb', 'SHT-2025-0001', 3, 'Diploma in Environmental Health', 2024, 1.83, 'Pass', NULL, 'draft', NULL, NULL, NULL, NULL, NULL, NULL, 'pickup', NULL, '2025-10-29 18:55:16', '2025-10-29 18:55:16', NULL),
(2, '742b26a6-2f34-4e6e-83a6-745640be7a6c', 'SHT-2025-0002', 5, 'BSc in Public Health', 2027, 2.07, 'Third Class', NULL, 'issued', '2025-10-30 18:48:16', NULL, NULL, 22, NULL, NULL, 'pickup', NULL, '2025-10-30 18:48:22', '2025-10-30 18:48:22', NULL),
(3, '7df9ff5b-6175-4c69-a107-29ab4d1c330d', 'SHT-2025-0003', 1, 'Diploma in Health Information Management', 2023, 2.00, 'Third Class', NULL, 'issued', '2025-10-30 00:00:00', NULL, NULL, 1, NULL, NULL, 'pickup', NULL, '2025-10-30 22:56:37', '2025-10-30 22:56:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transcript_courses`
--

CREATE TABLE `transcript_courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transcript_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `credit_hours` int(11) NOT NULL,
  `grade` varchar(255) NOT NULL,
  `grade_point` decimal(3,2) NOT NULL,
  `academic_year` int(11) NOT NULL,
  `semester` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transcript_requests`
--

CREATE TABLE `transcript_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_number` varchar(255) NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `request_type` enum('official','unofficial') NOT NULL DEFAULT 'official',
  `delivery_method` enum('email','pickup','mail') NOT NULL DEFAULT 'email',
  `recipient_email` varchar(255) DEFAULT NULL,
  `recipient_address` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `handled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `transcript_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `faculty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `program_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `faculty_id`, `department_id`, `program_id`, `phone`, `status`) VALUES
(1, 'Super Administrator', 'admin@admin.com', '2025-10-29 14:00:34', '$2y$12$EHVpVnca7YOpir08qeQg6.448jcrmqZLOw1lWQmhNC0BQHEYws4fm', NULL, '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL, NULL, NULL, NULL, NULL, 'active'),
(2, 'Faculty Administrator', 'facultyadmin@schoolofhygiene.edu.gh', '2025-10-29 14:00:34', '$2y$12$Eo56NZ1iPPpfda9h25vAPOX0TCAt/D6zIaYb8crNJCwNoHb9914BK', NULL, '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL, NULL, NULL, NULL, NULL, 'active'),
(3, 'Department Administrator', 'deptadmin@schoolofhygiene.edu.gh', '2025-10-29 14:00:34', '$2y$12$9XUmGeyuveMMjV75eTWx..xdW2XEKshedsUHMQKxP97Isgm/EP0q2', NULL, '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL, NULL, NULL, NULL, NULL, 'active'),
(4, 'Sample Lecturer', 'lecturer@schoolofhygiene.edu.gh', '2025-10-29 14:00:34', '$2y$12$DU.tUt0.aEjVjuZlffs0g.zpl03hH9rpXKjsMmOWSdaBxl4D.xRPi', NULL, '2025-10-29 14:00:34', '2025-10-29 14:00:34', NULL, NULL, NULL, NULL, NULL, 'active'),
(5, 'Ms. Rosemary Kemmer V', 'rylee.oberbrunner@example.org', '2025-10-29 14:00:34', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'Q9XPjPxhDc', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(6, 'Jeff Koepp', 'corwin.gardner@example.org', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'TY9qsApQKV', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(7, 'Miss Fatima McClure', 'carolyn94@example.org', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'Nv1uZWzv8t', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(8, 'Mr. Bobby Reichert DDS', 'gyundt@example.org', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', '58vkzk6zyN', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(9, 'Shyann Daugherty DVM', 'kirlin.merlin@example.org', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'FLGBJuefyB', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(10, 'Madelynn Watsica', 'nbreitenberg@example.com', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'SAgImwpt6Q', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(11, 'Adeline Dare', 'jnienow@example.com', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'duyqAejF8c', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(12, 'Prof. Thomas Klein V', 'odie.schmidt@example.net', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'qYTl9MfYsJ', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(13, 'Ms. Cara Ruecker II', 'lesch.brenna@example.net', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'lqRNyPys37', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(14, 'Diego McClure', 'alivia.ebert@example.com', '2025-10-29 14:00:35', '$2y$12$/RubsvO.xOHcZs/.x9kFKeS8D99JHhShpIM8GnrtCaAkjIN52iB6e', 'PtmnPBW3gD', '2025-10-29 14:00:35', '2025-10-29 14:00:35', NULL, NULL, NULL, NULL, NULL, 'active'),
(15, 'Nedra Schuster', 'queenie.okon@example.com', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', '5LB7SvfdF9', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(16, 'Dr. Kirstin Bednar V', 'rachael.schiller@example.com', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'RUTg7D2kCz', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(17, 'Sven Wintheiser I', 'berry73@example.org', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'jFQv8NFA8D', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(18, 'Mr. Marvin Vandervort II', 'hubert04@example.net', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'wNOfNEy3vj', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(19, 'Ms. Whitney Mraz', 'wehner.adela@example.net', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'Xib3J5xflr', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(20, 'Miss Maxie Bednar II', 'yundt.korbin@example.com', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'ptv6o6YDaR', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(21, 'Mr. Arno Braun', 'maximo23@example.org', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'fp24NH58EZ', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(22, 'Desiree Stamm', 'ken.marks@example.org', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'H3vOx3K3Nc', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, NULL, NULL, NULL, NULL, 'active'),
(23, 'Miss Alicia Hudson IV', 'alexandre.leannon@example.com', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'i9vfULgiq6', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, 1, 1, 3, NULL, 'active'),
(24, 'Kali Konopelski', 'tania66@example.com', '2025-10-30 18:41:32', '$2y$12$ckyEMlybB9l0Juehx1cHCOoOr0jtGyeuU6GSuxUw1ZzKbNbLuDNCy', 'jUt4PFGEls', '2025-10-30 18:41:32', '2025-10-30 18:41:32', NULL, 3, 5, 6, NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_code_unique` (`code`),
  ADD KEY `courses_department_id_foreign` (`department_id`),
  ADD KEY `courses_program_id_foreign` (`program_id`);

--
-- Indexes for table `course_user`
--
ALTER TABLE `course_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_user_course_id_user_id_unique` (`course_id`,`user_id`),
  ADD KEY `course_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_code_unique` (`code`),
  ADD KEY `departments_faculty_id_foreign` (`faculty_id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculties_code_unique` (`code`);

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
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `programs_code_unique` (`code`),
  ADD KEY `programs_department_id_foreign` (`department_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_course_period_result` (`student_id`,`course_id`,`academic_year`,`semester`),
  ADD KEY `results_course_id_foreign` (`course_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_student_id_unique` (`student_id`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD KEY `students_department_id_foreign` (`department_id`),
  ADD KEY `students_program_id_foreign` (`program_id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_courses_student_id_academic_year_semester_index` (`student_id`,`academic_year`,`semester`),
  ADD KEY `student_courses_course_id_index` (`course_id`);

--
-- Indexes for table `transcripts`
--
ALTER TABLE `transcripts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transcripts_uuid_unique` (`uuid`),
  ADD UNIQUE KEY `transcripts_transcript_number_unique` (`transcript_number`),
  ADD KEY `transcripts_issued_by_foreign` (`issued_by`),
  ADD KEY `transcripts_student_id_status_index` (`student_id`,`status`),
  ADD KEY `transcripts_transcript_number_index` (`transcript_number`),
  ADD KEY `transcripts_uuid_index` (`uuid`);

--
-- Indexes for table `transcript_courses`
--
ALTER TABLE `transcript_courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transcript_courses_transcript_id_course_id_unique` (`transcript_id`,`course_id`),
  ADD KEY `transcript_courses_course_id_foreign` (`course_id`),
  ADD KEY `transcript_courses_transcript_id_academic_year_semester_index` (`transcript_id`,`academic_year`,`semester`);

--
-- Indexes for table `transcript_requests`
--
ALTER TABLE `transcript_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transcript_requests_request_number_unique` (`request_number`),
  ADD KEY `transcript_requests_handled_by_foreign` (`handled_by`),
  ADD KEY `transcript_requests_transcript_id_foreign` (`transcript_id`),
  ADD KEY `transcript_requests_student_id_status_index` (`student_id`,`status`),
  ADD KEY `transcript_requests_request_number_index` (`request_number`),
  ADD KEY `transcript_requests_status_index` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_faculty_id_foreign` (`faculty_id`),
  ADD KEY `users_department_id_foreign` (`department_id`),
  ADD KEY `users_program_id_foreign` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `course_user`
--
ALTER TABLE `course_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `student_courses`
--
ALTER TABLE `student_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transcripts`
--
ALTER TABLE `transcripts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transcript_courses`
--
ALTER TABLE `transcript_courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transcript_requests`
--
ALTER TABLE `transcript_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_user`
--
ALTER TABLE `course_user`
  ADD CONSTRAINT `course_user_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `student_courses_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_courses_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transcripts`
--
ALTER TABLE `transcripts`
  ADD CONSTRAINT `transcripts_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transcripts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transcript_courses`
--
ALTER TABLE `transcript_courses`
  ADD CONSTRAINT `transcript_courses_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transcript_courses_transcript_id_foreign` FOREIGN KEY (`transcript_id`) REFERENCES `transcripts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transcript_requests`
--
ALTER TABLE `transcript_requests`
  ADD CONSTRAINT `transcript_requests_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transcript_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transcript_requests_transcript_id_foreign` FOREIGN KEY (`transcript_id`) REFERENCES `transcripts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
