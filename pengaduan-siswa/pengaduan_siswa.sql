-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 09:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengaduan_siswa`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'fas fa-folder',
  `color` varchar(255) NOT NULL DEFAULT '#6366f1',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `color`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Fasilitas Sekolah', 'Kerusakan sarana dan prasarana', 'fas fa-building', '#3b82f6', 1, '2026-03-29 21:10:09', '2026-03-29 23:40:29'),
(2, 'Bullying', 'Kasus perundungan di sekolah', 'fas fa-users-slash', '#ef4444', 1, '2026-03-29 21:10:09', '2026-03-29 21:10:09'),
(3, 'Akademik', 'Terkait KBM dan nilai', 'fas fa-book-reader', '#10b981', 1, '2026-03-29 21:10:09', '2026-03-29 21:10:09'),
(4, 'Pelanggaran', 'Siswa melanggar aturan', 'fas fa-exclamation-triangle', '#f59e0b', 1, '2026-03-29 21:10:09', '2026-03-29 21:10:09'),
(5, 'Lainnya', 'Topik lain-lain', 'fas fa-ellipsis-h', '#6b7280', 1, '2026-03-29 21:10:09', '2026-03-29 21:10:09'),
(6, 'Informasi', NULL, 'fas fa-folder', '#3b82f6', 1, '2026-03-29 23:48:35', '2026-03-29 23:48:35');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diproses','selesai','ditolak') NOT NULL DEFAULT 'menunggu',
  `priority` enum('rendah','sedang','tinggi') NOT NULL DEFAULT 'sedang',
  `handled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `category_id`, `title`, `description`, `attachment`, `status`, `priority`, `handled_by`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'AIR', 'Air di lantai 3 mati', NULL, 'selesai', 'tinggi', NULL, NULL, '2026-03-29 21:21:23', '2026-03-29 21:43:42'),
(2, 3, 1, 'AC', 'Ac gak dingin', NULL, 'selesai', 'sedang', 2, NULL, '2026-03-29 21:46:23', '2026-03-29 21:53:06'),
(3, 3, 4, 'melanggar peraturan', 'teman pulang lebih awal', NULL, 'diproses', 'sedang', 2, NULL, '2026-03-29 23:42:41', '2026-03-29 23:44:37'),
(4, 4, 1, 'Air', 'Air lantai 2 mati', 'complaints/lVJ0BKxEbD0UqpkD0RDTrGmoHQazMdWccytCRvqg.png', 'menunggu', 'sedang', NULL, NULL, '2026-03-29 23:53:40', '2026-03-29 23:53:40'),
(5, 4, 4, 't', 'w', NULL, 'selesai', 'rendah', NULL, NULL, '2026-03-29 23:53:59', '2026-03-29 23:57:10'),
(6, 4, 1, 'q', 'q', 'complaints/6aZo1WfoZGqJ1kGBnmEoXG46UhYFtrqYeJd8441i.jpg', 'menunggu', 'sedang', NULL, NULL, '2026-03-30 00:10:13', '2026-03-30 00:10:13');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_responses`
--

CREATE TABLE `complaint_responses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `complaint_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint_responses`
--

INSERT INTO `complaint_responses` (`id`, `complaint_id`, `user_id`, `message`, `attachment`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'cepetan mas', NULL, '2026-03-29 21:34:17', '2026-03-29 21:34:17');

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
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_01_01_000001_create_categories_table', 1),
(7, '2024_01_01_000002_create_complaints_table', 1),
(8, '2024_01_01_000003_create_complaint_responses_table', 1),
(9, '2024_01_01_000004_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `complaint_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `complaint_id`, `title`, `message`, `type`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Balasan Baru (Siswa)', 'Siswa ivan tama mengirim balasan pada pengaduan: AIR', 'info', 1, '2026-03-29 21:34:17', '2026-03-29 21:43:02'),
(2, 3, 1, 'Update Status Pengaduan', 'Status pengaduan Anda \'AIR\' telah diubah menjadi Selesai', 'success', 1, '2026-03-29 21:43:42', '2026-03-29 23:24:19'),
(3, 1, 2, 'Pengaduan Baru', 'Ada pengaduan baru dari ivan tama: AC', 'warning', 0, '2026-03-29 21:46:23', '2026-03-29 21:46:23'),
(4, 3, 2, 'Update Status Pengaduan', 'Guru telah mengubah status pengaduan Anda \'AC\' menjadi Selesai', 'success', 1, '2026-03-29 21:53:06', '2026-03-29 23:24:19'),
(5, 1, 3, 'Pengaduan Baru', 'Ada pengaduan baru dari ivan tama: melanggar peraturan', 'warning', 0, '2026-03-29 23:42:41', '2026-03-29 23:42:41'),
(6, 3, 3, 'Update Status Pengaduan', 'Status pengaduan Anda \'melanggar peraturan\' telah diubah menjadi Selesai', 'success', 0, '2026-03-29 23:44:04', '2026-03-29 23:44:04'),
(7, 2, 3, 'Tugas Pengaduan Baru', 'Anda telah ditugaskan untuk menangani pengaduan: melanggar peraturan', 'warning', 0, '2026-03-29 23:44:04', '2026-03-29 23:44:04'),
(8, 3, 3, 'Update Status Pengaduan', 'Status pengaduan Anda \'melanggar peraturan\' telah diubah menjadi Diproses', 'info', 0, '2026-03-29 23:44:37', '2026-03-29 23:44:37'),
(9, 1, 4, 'Pengaduan Baru', 'Ada pengaduan baru dari Ivan Tama Ramadhan: Air', 'warning', 0, '2026-03-29 23:53:40', '2026-03-29 23:53:40'),
(10, 1, 5, 'Pengaduan Baru', 'Ada pengaduan baru dari Ivan Tama Ramadhan: t', 'warning', 0, '2026-03-29 23:53:59', '2026-03-29 23:53:59'),
(11, 4, 5, 'Update Status Pengaduan', 'Status pengaduan Anda \'t\' telah diubah menjadi Selesai', 'success', 0, '2026-03-29 23:57:10', '2026-03-29 23:57:10'),
(12, 1, 6, 'Pengaduan Baru', 'Ada pengaduan baru dari Ivan Tama Ramadhan: q', 'warning', 0, '2026-03-30 00:10:13', '2026-03-30 00:10:13');

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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL DEFAULT 'siswa',
  `nis_nip` varchar(255) DEFAULT NULL,
  `kelas` varchar(255) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `nis_nip`, `kelas`, `jurusan`, `phone`, `foto`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@sekolah.com', NULL, '$2y$12$wmjNY1EXTINNb534qF701.hifodkt/fQy4Im0usRKnDpttNUAsOZO', 'admin', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-29 21:10:09', '2026-03-29 21:10:09'),
(2, 'Budi Guru', 'guru@sekolah.com', NULL, '$2y$12$pPCDB20VM3vT6cbmrzMBgO2HapluK8GoDpKthO644S04M4e4zJQXW', 'guru', '198001012010011001', NULL, NULL, NULL, NULL, 1, NULL, '2026-03-29 21:10:09', '2026-03-29 21:10:09'),
(3, 'ivan tama', 'IVAN@SISWA.EDU', NULL, '$2y$12$zIsKtYpydSjj2o/k4YYtZe39fusb7C9GF8OJJjaLnnf2hHGRFfbx.', 'siswa', '553231095', '12 RPL 1', 'REKAYASA PERANGKAT LUNAK', '083867626171', NULL, 1, NULL, '2026-03-29 21:16:14', '2026-03-29 21:16:14'),
(4, 'Ivan Tama Ramadhan', 'siswa@siswa.com', NULL, '$2y$12$8Tr4Flwd6qUPLtEtk4PjyOs/2xE9Kv797yTSb27x6lK5p0Ng3nAAm', 'siswa', '553231096', '12', 'rpl 1', '083867626171', NULL, 1, NULL, '2026-03-29 23:53:03', '2026-03-29 23:53:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_user_id_foreign` (`user_id`),
  ADD KEY `complaints_category_id_foreign` (`category_id`),
  ADD KEY `complaints_handled_by_foreign` (`handled_by`);

--
-- Indexes for table `complaint_responses`
--
ALTER TABLE `complaint_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_responses_complaint_id_foreign` (`complaint_id`),
  ADD KEY `complaint_responses_user_id_foreign` (`user_id`);

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`),
  ADD KEY `notifications_complaint_id_foreign` (`complaint_id`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `complaint_responses`
--
ALTER TABLE `complaint_responses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaints_handled_by_foreign` FOREIGN KEY (`handled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaint_responses`
--
ALTER TABLE `complaint_responses`
  ADD CONSTRAINT `complaint_responses_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_responses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
