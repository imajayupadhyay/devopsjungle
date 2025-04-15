-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 15, 2025 at 11:24 AM
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
-- Database: `education_site`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Ajay Upadhyay', 'ajayupadhyay030@gmail.com', '$2y$10$XuIt4iIfE8mIUjvMlBDEBev1/PqSe16xCRfsZPZLpctumNZQyoxyi', '2025-04-14 15:53:18');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `link` varchar(255) DEFAULT NULL,
  `position` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `image`, `created_at`, `link`, `position`) VALUES
(2, 'CSS Design', 'Understand styling concepts and how to make your site visually appealing.', NULL, '2025-04-14 15:25:43', NULL, 4),
(3, 'JavaScript Essentials', 'Learn how to bring interactivity and logic into web applications.', NULL, '2025-04-14 15:25:43', NULL, 5),
(4, 'PHP Fundamentals', 'Start with server-side scripting to power dynamic websites.', NULL, '2025-04-14 15:25:43', NULL, 2),
(5, 'Html5', 'Learn Htm', 'uploads/1744652104_docker.png', '2025-04-14 17:35:04', 'https://www.w3schools.com/Html', 1),
(6, 'Docker', 'Understand the core Git commands and flow.', 'uploads/1744652780_docker.png', '2025-04-14 17:46:20', 'https://www.docker.com/', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tutorials`
--

CREATE TABLE `tutorials` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `position` int(11) DEFAULT 0,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorials`
--

INSERT INTO `tutorials` (`id`, `title`, `description`, `file`, `created_at`, `position`, `link`) VALUES
(8, 'Linux', 'understand the concepts of Linux with Ubuntu', 'uploads/1744654035_linux.png', '2025-04-14 18:07:15', 1, 'http://localhost/devopsjungle/tutorial/linux'),
(9, 'Git & GitHub', 'understand the concepts of Git & Github', 'uploads/1744654084_github.png', '2025-04-14 18:08:04', 2, ''),
(10, 'Shell Scripting', 'understand the concepts of Shell Scripting', 'uploads/1744654173_script.png', '2025-04-14 18:09:33', 3, ''),
(11, 'Jenkins', 'understand the concepts of Cicd with Jenkins', 'uploads/1744654310_icons8-jenkins-144.png', '2025-04-14 18:11:50', 4, ''),
(12, 'Docker', 'understand the concepts of Docker Container', 'uploads/1744654338_docker.png', '2025-04-14 18:12:18', 5, ''),
(13, 'Kubernetes', 'understand the concepts of Kubernetes Clusture', 'uploads/1744654391_icons8-kubernetes-144.png', '2025-04-14 18:13:11', 6, ''),
(14, 'AWS', 'understand the concepts of AWS Cloud', 'uploads/1744654460_icons8-aws-144.png', '2025-04-14 18:14:20', 7, ''),
(15, 'Terraform', 'understand the concepts of Terraform', 'uploads/1744654583_icons8-terraform-144.png', '2025-04-14 18:16:23', 8, '');

-- --------------------------------------------------------

--
-- Table structure for table `tutorial_groups`
--

CREATE TABLE `tutorial_groups` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorial_groups`
--

INSERT INTO `tutorial_groups` (`id`, `title`, `slug`, `description`, `icon`, `created_at`) VALUES
(1, 'Linux', 'linux', '', NULL, '2025-04-15 06:29:57');

-- --------------------------------------------------------

--
-- Table structure for table `tutorial_pages`
--

CREATE TABLE `tutorial_pages` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `position` int(11) DEFAULT 0,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorial_pages`
--

INSERT INTO `tutorial_pages` (`id`, `group_id`, `title`, `slug`, `content`, `position`, `is_published`, `created_at`, `updated_at`, `meta_title`, `meta_description`) VALUES
(1, 1, 'linux', 'linux', '<p>&nbsp;</p><p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><h2>Why do we use it?</h2><p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p><p><br>&nbsp;</p>', 1, 1, '2025-04-15 06:31:21', '2025-04-15 08:57:48', 'my name is ajay', 'i am a web devoper'),
(2, 1, 'What is linux', 'what-is-linux', '<blockquote><p>Show <strong>all pages from all groups</strong> in tutorial-pages/index.php by default — with clear indication of group name — and still be able to edit, delete, and add new ones easily.</p></blockquote><h2>🛠️ Here\'s What We\'ll Do:</h2><h3>✅ 1. Show a <strong>combined list</strong> of all pages</h3><p>With columns: <strong>Title</strong>, <strong>Group</strong>, <strong>Slug</strong>, <strong>Status</strong>, <strong>Actions</strong></p><p>Sorted by group &amp; position</p><p>Each row shows which group it belongs to</p><h3>✅ 2. Add/Edit Modal will let you:</h3>', 2, 1, '2025-04-15 06:44:57', '2025-04-15 06:44:57', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutorials`
--
ALTER TABLE `tutorials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutorial_groups`
--
ALTER TABLE `tutorial_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `tutorial_pages`
--
ALTER TABLE `tutorial_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_id` (`group_id`,`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tutorials`
--
ALTER TABLE `tutorials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tutorial_groups`
--
ALTER TABLE `tutorial_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tutorial_pages`
--
ALTER TABLE `tutorial_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tutorial_pages`
--
ALTER TABLE `tutorial_pages`
  ADD CONSTRAINT `tutorial_pages_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `tutorial_groups` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
