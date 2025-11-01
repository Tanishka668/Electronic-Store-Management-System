-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 01, 2025 at 05:35 PM
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
-- Database: `Electronic_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `Company`
--

CREATE TABLE `Company` (
  `company_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Company`
--

INSERT INTO `Company` (`company_id`, `company_name`, `contact_number`, `email`, `address`) VALUES
(1, 'Samsung Electronics', '9876543210', 'support@samsung.com', 'Samsung Tower, Bengaluru, Karnataka'),
(3, 'Apple India Pvt Ltd', '9123456780', 'info@appleindia.com', 'DLF Cyber City, Gurugram, Haryana'),
(4, 'Sony India', '9832145671', 'contact@sony.co.in', 'New Delhi, Delhi'),
(5, 'LG Electronics', '9765432180', 'care@lgindia.com', 'Electronic City, Bengaluru, Karnataka');

-- --------------------------------------------------------

--
-- Table structure for table `Distribution`
--

CREATE TABLE `Distribution` (
  `distribution_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Distribution`
--

INSERT INTO `Distribution` (`distribution_id`, `product_id`, `product_name`, `company_name`, `receiver_name`, `quantity`, `date`, `created_at`) VALUES
(1, 9, 'iPhone SE (3rd Gen, 64GB)', 'Apple', 'Shivank', 1, '2025-10-30', '2025-10-30 05:08:12'),
(2, 9, 'iPhone SE (3rd Gen, 64GB)', 'Apple', 'Shivank', 1, '2025-10-30', '2025-10-30 05:08:34'),
(3, 9, 'iPhone SE (3rd Gen, 64GB)', 'Apple', 'Shivank', 1, '2025-10-30', '2025-10-30 05:09:02'),
(4, 19, 'LG 260L 3-Star Frost-Free Double Door Refrigerator', 'LG', 'Khushi', 1, '2025-11-07', '2025-10-30 05:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `Product`
--

CREATE TABLE `Product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Product`
--

INSERT INTO `Product` (`product_id`, `product_name`, `category`, `price`, `quantity`, `company_name`, `created_at`) VALUES
(1, 'Sony Bravia 55-Inch 4K LED TV', 'Television', 65000.00, 11, 'Sony', '2025-10-30 15:33:16'),
(2, 'Sony Xperia 10 V', 'Phone', 40000.00, 11, 'Sony', '2025-10-30 15:33:20'),
(3, 'Sony Xperia 10 V', 'Phone', 40000.00, 11, 'Sony', '2025-10-30 15:33:23'),
(4, 'Sony Bravia 43-Inch Smart TV', 'Television', 45000.00, 11, 'Sony', '2025-10-30 15:33:14'),
(5, 'Sony Xperia 1 V Smartphone', 'Phone', 84990.00, 11, 'Sony', '2025-10-30 15:33:18'),
(6, 'Sony 1.5 Ton Split AC (Inverter)', 'AC', 49500.00, 21, 'Sony', '2025-10-30 15:32:58'),
(7, 'iPhone 15 Pro Max (256GB)', 'Phone', 159900.00, 10, 'Apple', '2025-10-29 19:17:25'),
(8, 'iPhone 14 (128GB)', 'Phone', 69900.00, 12, 'Apple', '2025-10-29 19:17:25'),
(9, 'iPhone SE (3rd Gen, 64GB)', 'Phone', 49900.00, 12, 'Apple', '2025-10-30 05:09:02'),
(10, 'Apple TV 4K (3rd Gen)', 'Television', 14900.00, 10, 'Apple', '2025-10-30 15:32:28'),
(11, 'iPhone 13 Mini (128GB)', 'Phone', 59900.00, 10, 'Apple', '2025-10-29 19:17:25'),
(12, 'Samsung Galaxy S24 Ultra (256GB)', 'Phone', 129999.00, 8, 'Samsung', '2025-10-29 19:19:43'),
(13, 'Samsung Galaxy A35 5G (128GB)', 'Phone', 28499.00, 15, 'Samsung', '2025-10-29 19:19:43'),
(14, 'Samsung 55-Inch Crystal UHD 4K TV', 'Television', 52990.00, 10, 'Samsung', '2025-10-29 19:19:43'),
(15, 'Samsung 324L 3-Star Frost-Free Refrigerator', 'Refrigerator', 35990.00, 8, 'Samsung', '2025-10-29 19:19:43'),
(16, 'Samsung 1.5 Ton 5-Star Inverter Split AC', 'AC', 47500.00, 10, 'Samsung', '2025-10-29 19:19:43'),
(17, 'LG G8X ThinQ Dual Screen (128GB)', 'Phone', 49990.00, 10, 'LG', '2025-10-29 19:20:38'),
(18, 'LG 55-Inch 4K Ultra HD Smart OLED TV', 'Television', 109990.00, 6, 'LG', '2025-10-29 19:20:38'),
(19, 'LG 260L 3-Star Frost-Free Double Door Refrigerator', 'Refrigerator', 28490.00, 11, 'LG', '2025-10-30 05:23:13'),
(20, 'LG 1.5 Ton 5-Star AI Dual Inverter Split AC', 'AC', 46490.00, 8, 'LG', '2025-10-29 19:20:38'),
(21, 'LG 32-Inch HD Ready Smart LED TV', 'Television', 17490.00, 14, 'LG', '2025-10-30 15:30:20');

-- --------------------------------------------------------

--
-- Table structure for table `Purchase`
--

CREATE TABLE `Purchase` (
  `purchase_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `purchase_date` datetime NOT NULL,
  `company_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Purchase`
--

INSERT INTO `Purchase` (`purchase_id`, `product_id`, `quantity`, `total_price`, `purchase_date`, `company_name`) VALUES
(1, 18, 1, 109990.00, '2025-10-30 01:23:02', 'LG'),
(2, 8, 1, 69900.00, '2025-10-31 14:08:37', 'Apple');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`user_id`, `username`, `password`) VALUES
(1, 'Admin', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Company`
--
ALTER TABLE `Company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `Distribution`
--
ALTER TABLE `Distribution`
  ADD PRIMARY KEY (`distribution_id`),
  ADD KEY `fk_product_distribution` (`product_id`);

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `Purchase`
--
ALTER TABLE `Purchase`
  ADD PRIMARY KEY (`purchase_id`),
  ADD KEY `fk_product_purchase` (`product_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Company`
--
ALTER TABLE `Company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Distribution`
--
ALTER TABLE `Distribution`
  MODIFY `distribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `Purchase`
--
ALTER TABLE `Purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Distribution`
--
ALTER TABLE `Distribution`
  ADD CONSTRAINT `fk_product_distribution` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`);

--
-- Constraints for table `Purchase`
--
ALTER TABLE `Purchase`
  ADD CONSTRAINT `fk_product_purchase` FOREIGN KEY (`product_id`) REFERENCES `Product` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
