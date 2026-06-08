-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 11, 2026 at 12:59 AM
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
-- Database: `pharma_core`
--

-- --------------------------------------------------------

--
-- Table structure for table `batches`
--

CREATE TABLE `batches` (
  `id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `batch_number` varchar(50) NOT NULL,
  `expiry_date` date NOT NULL,
  `current_quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batches`
--

INSERT INTO `batches` (`id`, `medicine_id`, `batch_number`, `expiry_date`, `current_quantity`, `created_at`) VALUES
(1, 1, 'B-2024-001', '2026-12-31', 149, '2026-05-06 15:45:17'),
(2, 1, 'B-2024-002', '2027-05-15', 200, '2026-05-06 15:45:17'),
(3, 2, 'AUG-9921', '2025-11-30', 44, '2026-05-06 15:45:17'),
(4, 3, 'LEX-442', '2026-08-20', 5, '2026-05-06 15:45:17'),
(5, 4, 'SPA-001', '2028-01-10', 74, '2026-05-06 15:45:17'),
(6, 5, 'SME-092', '2027-03-25', 120, '2026-05-06 15:45:17'),
(7, 6, 'LEV-551', '2026-06-30', 60, '2026-05-06 15:45:17'),
(8, 7, 'VOL-883', '2025-09-15', 30, '2026-05-06 15:45:17'),
(9, 8, 'ASP-112', '2027-11-01', 90, '2026-05-06 15:45:17'),
(10, 9, 'RHI-334', '2026-02-28', 25, '2026-05-06 15:45:17'),
(11, 10, 'ZYR-775', '2028-05-15', 50, '2026-05-06 15:45:17'),
(12, 14, '2022', '2026-05-01', 20, '2026-05-09 09:04:07'),
(13, 15, '2022', '2026-05-01', 2, '2026-05-09 09:09:52'),
(14, 16, '2022', '2026-02-01', 333, '2026-05-09 09:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `credit_balance` decimal(12,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `phone`, `credit_balance`, `created_at`) VALUES
(1, 'أحمد العلمي', '0611111111', 150.00, '2026-05-06 15:45:17'),
(2, 'خديجة بنجلون', '0622222222', 0.00, '2026-05-06 15:45:17'),
(3, 'محمد التازي', '0633333333', 450.50, '2026-05-06 15:45:17'),
(4, 'سناء الإدريسي', '0644444444', 80.00, '2026-05-06 15:45:17'),
(5, 'كريم العمراني', '0655555555', 1100.00, '2026-05-06 15:45:17'),
(6, 'ali', '0613529669', 0.00, '2026-05-09 13:57:29'),
(7, 'طابعة °1', '0613529669', 0.00, '2026-05-09 14:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `client_payments`
--

CREATE TABLE `client_payments` (
  `id` bigint(20) NOT NULL,
  `type` enum('payment','debt') NOT NULL DEFAULT 'payment',
  `client_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','card','cheque','transfer','credit') NOT NULL DEFAULT 'cash',
  `note` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client_payments`
--

INSERT INTO `client_payments` (`id`, `client_id`, `amount`, `user_id`, `payment_date`) VALUES
(1, 1, 50.00, 1, '2026-05-06 15:45:17'),
(2, 3, 200.00, 2, '2026-05-06 15:45:17'),
(3, 5, 500.00, 1, '2026-05-06 15:45:17'),
(4, 4, 20.00, 3, '2026-05-06 15:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `movement_type` enum('in','sale','return_to_supplier','expired','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_movements`
--

INSERT INTO `inventory_movements` (`id`, `batch_id`, `movement_type`, `quantity`, `user_id`, `reference_id`, `created_at`) VALUES
(1, 1, 'in', 152, 1, NULL, '2026-05-06 15:45:17'),
(2, 1, 'sale', 2, 1, 1, '2026-05-06 15:45:17'),
(3, 3, 'in', 46, 1, NULL, '2026-05-06 15:45:17'),
(4, 3, 'sale', 1, 2, 2, '2026-05-06 15:45:17'),
(5, 5, 'in', 81, 1, NULL, '2026-05-06 15:45:17'),
(6, 5, 'sale', 1, 3, 4, '2026-05-06 15:45:17'),
(7, 7, 'expired', 2, 1, NULL, '2026-05-06 15:45:17'),
(8, 9, 'return_to_supplier', 5, 1, NULL, '2026-05-06 15:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dci` varchar(150) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'General',
  `price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `is_tableau_b` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `barcode`, `name`, `dci`, `category`, `price`, `cost_price`, `is_tableau_b`, `created_at`, `deleted_at`) VALUES
(1, '6111234567001', 'Doliprane 1000mg', 'Paracetamol', 'analgesic', 15.00, 9.80, 0, '2026-05-06 15:45:17', NULL),
(2, '6111234567002', 'Augmentin 1g/125mg', 'Amoxicilline + Acide Clavulanique', 'antibiotic', 79.00, 52.50, 0, '2026-05-06 15:45:17', NULL),
(3, '6111234567003', 'Lexomil 6mg', 'Bromazepam', 'General', 38.50, 25.00, 1, '2026-05-06 15:45:17', NULL),
(4, '6111234567004', 'Spasfon 80mg', 'Phloroglucinol', 'general', 24.00, 16.00, 0, '2026-05-06 15:45:17', NULL),
(5, '6111234567005', 'Smecta 3g', 'Diosmectite', 'general', 35.00, 23.50, 0, '2026-05-06 15:45:17', NULL),
(6, '6111234567006', 'Levothyrox 50µg', 'Levothyroxine', 'Viramins', 18.00, 11.50, 0, '2026-05-06 15:45:17', '2026-05-09 09:33:40'),
(7, '6111234567007', 'Voltaren 50mg', 'Diclofenac', 'analgesic', 42.00, 28.00, 0, '2026-05-06 15:45:17', NULL),
(8, '6111234567008', 'Aspegic 1000mg', 'Acide Acetylsalicylique', 'analgesic', 22.00, 14.50, 0, '2026-05-06 15:45:17', NULL),
(9, '6111234567009', 'Rhinocort 64µg', 'Budesonide', 'General', 65.00, 43.00, 0, '2026-05-06 15:45:17', NULL),
(10, '6111234567010', 'Zyrtec 10mg', 'Cetirizine', 'General', 45.00, 30.00, 0, '2026-05-06 15:45:17', NULL),
(11, '45678906', 'new', '500', 'vitamin', 100.00, 0.00, 0, '2026-05-09 08:45:13', NULL),
(12, '4567890644', 'جديد', '500', 'cardiac', 200.00, 0.00, 0, '2026-05-09 08:59:41', NULL),
(14, '45678906443', 'بميمم', '89', 'vitamin', 200.00, 0.00, 0, '2026-05-09 09:04:07', NULL),
(15, 'يسبسش', 'يسب', '500', 'analgesic', 77.00, 0.00, 0, '2026-05-09 09:09:52', '2026-05-09 09:18:17'),
(16, 'يسشبسش', 'سيبسيشب', '333', 'derma', 333.00, 0.00, 0, '2026-05-09 09:34:06', '2026-05-09 09:34:16');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','card','credit','cheque','mutuelle') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `client_id`, `total_amount`, `payment_method`, `created_at`, `deleted_at`) VALUES
(1, 1, NULL, 30.00, 'cash', '2026-05-06 15:45:17', NULL),
(2, 2, 1, 79.00, 'credit', '2026-05-06 15:45:17', NULL),
(3, 1, 2, 114.00, 'card', '2026-05-06 15:45:17', NULL),
(4, 3, NULL, 24.00, 'cash', '2026-05-06 15:45:17', NULL),
(5, 1, 3, 185.00, 'mutuelle', '2026-05-06 15:45:17', NULL),
(6, 2, 4, 45.00, 'credit', '2026-05-06 15:45:17', NULL),
(7, 3, 5, 250.00, 'cheque', '2026-05-06 15:45:17', NULL),
(8, 1, NULL, 39.00, 'cash', '2026-05-09 07:00:18', NULL),
(9, 1, NULL, 39.00, 'cash', '2026-05-09 07:07:25', NULL),
(10, 1, NULL, 39.00, 'cash', '2026-05-09 07:20:21', NULL),
(11, 1, NULL, 39.00, 'cash', '2026-05-09 07:21:07', NULL),
(12, 1, NULL, 24.00, 'cash', '2026-05-09 07:25:04', NULL),
(13, 1, NULL, 385.00, 'card', '2026-05-09 08:43:18', NULL),
(14, 1, NULL, 79.00, 'credit', '2026-05-09 14:00:29', NULL),
(15, 1, NULL, 48.00, 'cash', '2026-05-09 14:12:00', NULL),
(16, 1, NULL, 24.00, 'card', '2026-05-09 14:12:46', NULL),
(17, 1, NULL, 24.00, 'cash', '2026-05-09 14:12:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) NOT NULL,
  `sale_id` bigint(20) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `snapshot_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`snapshot_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `batch_id`, `quantity`, `unit_price`, `snapshot_data`) VALUES
(1, 1, 1, 2, 15.00, '{\"medicine_name\": \"Doliprane 1000mg\", \"barcode\": \"6111234567001\", \"dci\": \"Paracetamol\"}'),
(2, 2, 3, 1, 79.00, '{\"medicine_name\": \"Augmentin 1g/125mg\", \"barcode\": \"6111234567002\", \"dci\": \"Amoxicilline + Acide Clavulanique\"}'),
(3, 3, 2, 1, 15.00, '{\"medicine_name\": \"Doliprane 1000mg\", \"barcode\": \"6111234567001\", \"dci\": \"Paracetamol\"}'),
(4, 3, 3, 1, 79.00, '{\"medicine_name\": \"Augmentin 1g/125mg\", \"barcode\": \"6111234567002\", \"dci\": \"Amoxicilline + Acide Clavulanique\"}'),
(5, 3, 10, 1, 20.00, '{\"medicine_name\": \"Zyrtec 10mg\", \"barcode\": \"6111234567010\", \"dci\": \"Cetirizine\"}'),
(6, 4, 5, 1, 24.00, '{\"medicine_name\": \"Spasfon 80mg\", \"barcode\": \"6111234567004\", \"dci\": \"Phloroglucinol\"}'),
(7, 5, 6, 2, 35.00, '{\"medicine_name\": \"Smecta 3g\", \"barcode\": \"6111234567005\", \"dci\": \"Diosmectite\"}'),
(8, 5, 9, 1, 65.00, '{\"medicine_name\": \"Rhinocort 64µg\", \"barcode\": \"6111234567009\", \"dci\": \"Budesonide\"}'),
(9, 6, 11, 1, 45.00, '{\"medicine_name\": \"Zyrtec 10mg\", \"barcode\": \"6111234567010\", \"dci\": \"Cetirizine\"}'),
(10, 7, 7, 2, 42.00, '{\"medicine_name\": \"Voltaren 50mg\", \"barcode\": \"6111234567007\", \"dci\": \"Diclofenac\"}'),
(11, 11, 5, 1, 24.00, '{\"name\":\"Spasfon 80mg\",\"price\":\"24.00\"}'),
(12, 11, 1, 1, 15.00, '{\"name\":\"Doliprane 1000mg\",\"price\":\"15.00\"}'),
(13, 12, 5, 1, 24.00, '{\"name\":\"Spasfon 80mg\",\"price\":\"24.00\"}'),
(14, 13, 4, 10, 38.50, '{\"name\":\"Lexomil 6mg\",\"price\":\"38.50\"}'),
(15, 14, 3, 1, 79.00, '{\"name\":\"Augmentin 1g\\/125mg\",\"price\":\"79.00\"}'),
(16, 15, 5, 2, 24.00, '{\"name\":\"Spasfon 80mg\",\"price\":\"24.00\"}'),
(17, 16, 5, 1, 24.00, '{\"name\":\"Spasfon 80mg\",\"price\":\"24.00\"}'),
(18, 17, 5, 1, 24.00, '{\"name\":\"Spasfon 80mg\",\"price\":\"24.00\"}');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pharmacist') DEFAULT 'pharmacist',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `created_at`, `deleted_at`) VALUES
(1, 'Soulayman Id Baha', 'soulayman', '$2y$10$FmTb1pEZSslfG0ivILNMLe9/CRRkfGLXdjICA6L96be8FGykOoz2m', 'admin', '2026-05-05 16:38:55', NULL),
(2, 'Youssef Mansour', 'youssef', '$2y$10$FmTb1pEZSslfG0ivILNMLe9/CRRkfGLXdjICA6L96be8FGykOoz2m', 'pharmacist', '2026-05-06 15:45:17', NULL),
(3, 'ASSMA', 'assma', '$2y$10$FmTb1pEZSslfG0ivILNMLe9/CRRkfGLXdjICA6L96be8FGykOoz2m', 'pharmacist', '2026-05-06 15:45:17', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `batches`
--
ALTER TABLE `batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medicine_id` (`medicine_id`),
  ADD KEY `idx_batches_expiry` (`expiry_date`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_payments`
--
ALTER TABLE `client_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `idx_medicines_barcode` (`barcode`),
  ADD KEY `idx_medicines_name` (`name`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `batches`
--
ALTER TABLE `batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `client_payments`
--
ALTER TABLE `client_payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batches`
--
ALTER TABLE `batches`
  ADD CONSTRAINT `batches_ibfk_1` FOREIGN KEY (`medicine_id`) REFERENCES `medicines` (`id`);

--
-- Constraints for table `client_payments`
--
ALTER TABLE `client_payments`
  ADD CONSTRAINT `client_payments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  ADD CONSTRAINT `client_payments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`),
  ADD CONSTRAINT `inventory_movements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sales_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
