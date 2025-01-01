-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2025 at 01:13 PM
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
-- Database: `donationwebsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted_items`
--

CREATE TABLE `accepted_items` (
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accepted_items`
--

INSERT INTO `accepted_items` (`item_id`, `item_name`, `description`, `created_at`) VALUES
(1, 'Blanket', 'Warm blankets for winter', '2024-12-31 06:10:50'),
(2, 'Canned Food', 'Non-perishable food items', '2024-12-31 06:10:50');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `userid`, `password`, `created_at`) VALUES
(1, 'sf', '1768', '2024-12-31 06:40:38');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `goal_amount` decimal(10,2) NOT NULL,
  `raised_amount` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `keyword` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `amount_raised` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`campaign_id`, `name`, `description`, `goal_amount`, `raised_amount`, `created_at`, `image`, `end_date`, `start_date`, `keyword`, `title`, `amount_raised`) VALUES
(8, 'Help Walter White Fight Cancer', 'Walter White, a dedicated high school chemistry teacher, has spent his life inspiring young minds and providing for his family. Now, he faces the fight of his life—a battle against lung cancer. Walter is determined to overcome this challenge, but he needs your help. Medical treatments, travel expenses, and the cost of care have placed a heavy financial burden on his family. Your donation can make a life-changing difference.', 1000000.00, 350200.00, '2024-12-31 18:14:04', 'uploads/6773bd9437d4d_walter.jpg', '2025-03-31', '2025-01-01', 'ill health', NULL, 0.00),
(9, 'Relief Fund for Chernobyl: Restoring Hope and Healing', 'n the aftermath of the 1986 Chernobyl nuclear disaster, the affected communities and ecosystems continue to bear the burden of this catastrophic event. Families displaced by the accident still struggle with health issues, economic challenges, and environmental degradation. Despite the passage of decades, the region\'s needs remain urgent and significant.\r\n\r\nThe Chernobyl Relief Fund seeks to provide targeted support for those impacted by this tragedy, ensuring a future of healing, resilience, and sustainable recovery', 10000000.00, 439999.00, '2024-12-31 18:53:32', 'uploads/6773c6d968238_chernobyl.jpg', '2026-01-01', '2025-01-01', 'natural disaster', NULL, 0.00),
(10, 'Rest for Trevor Reznik: A Journey Toward Healing from Insomnia', 'Trevor Reznik, a dedicated machinist and a kind-hearted individual, has been suffering from severe insomnia for over a year. His condition has taken a toll on his physical and mental health, leaving him isolated and struggling to maintain his livelihood. The lack of proper rest has led to worsening health complications, making it harder for Trevor to function daily.\r\n\r\nDespite his challenges, Trevor remains hopeful for recovery. With the right medical care, therapy, and community support, he can reclaim his life. This campaign aims to provide Trevor with the resources and care he desperately needs to overcome his insomnia and rebuild his future.', 100000.00, 0.00, '2024-12-31 18:58:15', 'uploads/images.jfif', '2025-02-28', '2025-01-01', 'ill health', NULL, 0.00),
(11, 'The Hawkins Medical Fund', 'Support medical treatment for the kids of Hawkins, Indiana, who are recovering from mysterious injuries caused by supernatural events.', 1000000.00, 0.00, '2024-12-31 19:08:47', 'uploads/hawkins.jpg', '2025-01-31', '2025-01-01', 'natural disaster', NULL, 0.00),
(12, 'Interstellar Crop Restoration Project', 'After Earth’s crops fail due to environmental collapse, help Cooper and the team provide alternative food sources for humanity. Contributions go toward space farming research.', 100000.00, 12020.00, '2024-12-31 19:10:57', 'uploads/intersteller.jfif', '2026-12-01', '2025-01-01', 'food crisis', NULL, 0.00),
(13, 'save hrishikes', 'There is a huge flood in hrishikes and people needs help.', 5000000.00, 1000000.00, '2025-01-01 08:00:47', 'uploads/flood_relief.jpg', '2025-01-08', '2025-01-01', 'natural disaster', NULL, 0.00),
(14, 'save Bangladesh from Boat', 'political issues', 10000000.00, 0.00, '2025-01-01 11:43:09', 'uploads/nouka.jpg', '2025-01-10', '2025-01-01', 'natural disaster', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `donation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `donation_type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `campaign_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`donation_id`, `user_id`, `donation_type`, `amount`, `created_at`, `campaign_id`) VALUES
(22, 8, '', 100.00, '2024-12-31 18:18:16', 8),
(23, 8, '', 100.00, '2024-12-31 18:18:26', 8),
(24, 8, '', 10000.00, '2024-12-31 18:18:33', 8),
(25, 10, '', 100.00, '2024-12-31 18:25:10', 8),
(26, 10, '', 100.00, '2024-12-31 18:25:18', 8);

-- --------------------------------------------------------

--
-- Table structure for table `keyword_reference`
--

CREATE TABLE `keyword_reference` (
  `id` int(11) NOT NULL,
  `keyword` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keyword_reference`
--

INSERT INTO `keyword_reference` (`id`, `keyword`) VALUES
(3, 'food crisis'),
(2, 'ill health'),
(1, 'natural disaster');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `created_at`, `is_admin`) VALUES
(5, 'a2', '2222', '2024-12-31 07:35:00', 0),
(6, 'a3', '33333', '2024-12-31 08:28:02', 0),
(7, '2', '1', '2024-12-31 08:30:25', 0),
(8, 'deb', '1', '2024-12-31 12:25:10', 0),
(9, 'masrur', '1111', '2024-12-31 12:52:53', 0),
(10, 'sf', '1768', '2024-12-31 18:21:35', 0),
(11, 'fathin', '1234', '2024-12-31 19:20:25', 0),
(12, 'john cena', '123', '2025-01-01 07:28:28', 0),
(13, 'masrur1', '2', '2025-01-01 11:38:57', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accepted_items`
--
ALTER TABLE `accepted_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_campaign` (`campaign_id`);

--
-- Indexes for table `keyword_reference`
--
ALTER TABLE `keyword_reference`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `keyword` (`keyword`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accepted_items`
--
ALTER TABLE `accepted_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `keyword_reference`
--
ALTER TABLE `keyword_reference`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_campaign` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
