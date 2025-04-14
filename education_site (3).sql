-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 14, 2025 at 08:29 PM
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
(8, 'Linux', 'understand the concepts of Linux with Ubuntu', 'uploads/1744654035_linux.png', '2025-04-14 18:07:15', 1, ''),
(9, 'Git & GitHub', 'understand the concepts of Git & Github', 'uploads/1744654084_github.png', '2025-04-14 18:08:04', 2, ''),
(10, 'Shell Scripting', 'understand the concepts of Shell Scripting', 'uploads/1744654173_script.png', '2025-04-14 18:09:33', 3, ''),
(11, 'Jenkins', 'understand the concepts of Cicd with Jenkins', 'uploads/1744654310_icons8-jenkins-144.png', '2025-04-14 18:11:50', 4, ''),
(12, 'Docker', 'understand the concepts of Docker Container', 'uploads/1744654338_docker.png', '2025-04-14 18:12:18', 5, ''),
(13, 'Kubernetes', 'understand the concepts of Kubernetes Clusture', 'uploads/1744654391_icons8-kubernetes-144.png', '2025-04-14 18:13:11', 6, ''),
(14, 'AWS', 'understand the concepts of AWS Cloud', 'uploads/1744654460_icons8-aws-144.png', '2025-04-14 18:14:20', 7, ''),
(15, 'Terraform', 'understand the concepts of Terraform', 'uploads/1744654583_icons8-terraform-144.png', '2025-04-14 18:16:23', 8, '');

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
