-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2024 at 03:50 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `googledrive`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `title` varchar(260) NOT NULL,
  `file_type` varchar(1000) NOT NULL DEFAULT 'null',
  `file_extension` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `size` int(11) NOT NULL DEFAULT 0,
  `is_private` tinyint(4) NOT NULL DEFAULT 0,
  `is_starred` tinyint(4) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `date_added` int(11) NOT NULL,
  `last_updated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `uuid`, `title`, `file_type`, `file_extension`, `user_id`, `folder_id`, `size`, `is_private`, `is_starred`, `is_deleted`, `date_added`, `last_updated`) VALUES
(14, 'd4ed4729-d1e4-40c7-9bdb-a4bab6a90fb2', 'payments_error_reasons.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'xlsx', 5, 11, 80041, 0, 0, 0, 1721391025, 1721391025),
(15, '8aa2cc54-472c-44f8-a97f-3a6f507c0df5', 'payments_error_reasons.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'xlsx', 5, 13, 80041, 0, 0, 0, 1721391321, 1721391321);

-- --------------------------------------------------------

--
-- Table structure for table `file_sharing_access`
--

CREATE TABLE `file_sharing_access` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `share_with` int(11) NOT NULL DEFAULT 0 COMMENT '0 means, the file can view by anyone',
  `file_id` int(11) NOT NULL,
  `is_seen` tinyint(4) NOT NULL DEFAULT 0,
  `date_added` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT 0,
  `title` varchar(260) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `files` int(11) NOT NULL DEFAULT 0,
  `size` bigint(20) NOT NULL DEFAULT 4096 COMMENT 'default 4kb of size, the size is stored in byte',
  `is_private` tinyint(4) NOT NULL DEFAULT 0,
  `is_deleted` tinyint(4) NOT NULL DEFAULT 0,
  `is_starred` tinyint(4) NOT NULL DEFAULT 0,
  `date_added` int(11) NOT NULL,
  `last_updated` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `uuid`, `parent_id`, `title`, `path`, `user_id`, `files`, `size`, `is_private`, `is_deleted`, `is_starred`, `date_added`, `last_updated`) VALUES
(11, 'b3d9ae39-80ec-4bc5-96af-32b3336c76fb', 0, 'Depth 1', '/11', 5, 2, 4096, 0, 0, 0, 1717588684, 1717588684),
(13, '2cb5fa4e-72ff-40bd-8bf2-6976c5a8c07b', 11, 'Depth 2', '/11/13', 5, 1, 4096, 0, 0, 0, 1717588737, 1717588737),
(14, '509a7039-c21b-49af-88c7-009b56f7d480', 13, 'Depth 3', '/11/13/14', 5, 0, 4096, 0, 0, 0, 1717588749, 1717588749),
(15, '2f1919e8-8fce-4ac1-9b9f-5bb78a4b91b2', 11, 'Direct child', '/11/15', 5, 0, 4096, 0, 0, 0, 1717588779, 1717588779),
(17, '26dbf28a-d27f-4de9-9e39-982815a468b1', 13, 'testing', '/11/13/17', 5, 0, 4096, 0, 0, 0, 1717592436, 1717592436),
(18, '90dca91e-cbbe-4ccd-b986-d8f8d88edfd4', 13, 'My Photos', '/11/13/18', 5, 0, 4096, 0, 0, 0, 1717593535, 1717593535),
(19, 'd0d3d31f-1f35-4367-b967-f9f59139ffbf', 0, 'new test', '/19', 5, 0, 4096, 0, 0, 0, 1717595240, 1717595240);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_email_verified` tinyint(4) NOT NULL DEFAULT 0,
  `storage_allocate` bigint(20) DEFAULT 1073741824 COMMENT '1GB, size in bytes',
  `status` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `is_email_verified`, `storage_allocate`, `status`) VALUES
(5, 'kam', 'uttam.81810@gmail.com', '202cb962ac59075b964b07152d234b70', 0, 1073741824, 1),
(6, 'Uttam Kumar', 'nawjeshbd@gmail.com', '202cb962ac59075b964b07152d234b70', 0, 1073741824, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `file_sharing_access`
--
ALTER TABLE `file_sharing_access`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `file_sharing_access`
--
ALTER TABLE `file_sharing_access`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
