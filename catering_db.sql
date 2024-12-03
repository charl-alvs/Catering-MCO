-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2024 at 03:03 AM
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
-- Database: `catering_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `id` int(11) NOT NULL,
  `admin_username` varchar(500) NOT NULL,
  `admin_password` varchar(500) NOT NULL,
  `admin_firstname` varchar(200) NOT NULL,
  `admin_lastname` varchar(200) NOT NULL,
  `added_by` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cater_packages`
--

CREATE TABLE `cater_packages` (
  `id` int(11) NOT NULL,
  `package_name` varchar(200) NOT NULL,
  `package_type` varchar(200) NOT NULL,
  `package_items` varchar(500) NOT NULL,
  `package_prices` varchar(500) NOT NULL,
  `added_by` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cater_packages`
--

INSERT INTO `cater_packages` (`id`, `package_name`, `package_type`, `package_items`, `package_prices`, `added_by`) VALUES
(3, 'Birthday Package', 'A', 'Adobong Manok, Lumpiang Shanghai, Spaghetti, Chicken Nuggets, Assorted Soft drinks', '10 PAX - 2550, 15 PAX - 3400, 20 PAX - 4600', ''),
(4, 'Birthday Package', 'B', 'Palabok, Spaghetti, Tinolang Manok, Fried Chicken, Assorted Soft Drinks', '10 PAX - 2400, 15 PAX - 3600, 20 PAX - 4500', ''),
(9, 'Birthday Package', 'C', 'Chicken Nuggets, Fried Chicken, Spaghetti, Assorted Soft Drinks, Lumpiang Shanghai', '10 PAX - 2000, 15 PAX - 3000, 20 PAX - 4000', ''),
(10, 'Birthday Package', 'D', 'Fried Chicken,  Mechado, Kaldereta, Pancit Bihon, Graham, Ice Cream, Assorted Soft Drinks', '10 PAX - 2800, 15 PAX - 3000, 20 PAX - 3200', ''),
(11, 'Birthday Package', 'E', 'Fried Chicken, Spaghetti, Carbonara, Palabok, Assorted Soft Drinks, Lemonade Juice, Cupcakes', '10 PAX - 2950, 15 PAX - 3790, 20 PAX - 4690', ''),
(13, 'Birthday Package', 'F', 'Cupcakes, Brownies, Spaghetti, Macaroni, Salad, Assorted Soft Drinks, Juice', '10 PAX - 2500, 15 PAX - 3200, 20 PAX - 4600', '');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `landmark` text DEFAULT NULL,
  `contact_number` varchar(20) NOT NULL,
  `package_type` varchar(50) NOT NULL,
  `set_type` char(1) NOT NULL,
  `order_type` varchar(50) NOT NULL,
  `delivery_date` date NOT NULL,
  `delivery_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `package_price` varchar(255) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `address`, `landmark`, `contact_number`, `package_type`, `set_type`, `order_type`, `delivery_date`, `delivery_time`, `created_at`, `status`, `verified`, `email`, `package_price`, `tracking_number`) VALUES
(2, 'Markie Taneo', 'Calbayog City', 'Jollibee', '09090909000', 'Birthday Package', 'A', 'reservation', '2024-11-29', '10:47:00', '2024-11-28 20:10:40', 'Pending', 0, 'taneo@gmail.com', '10 PAX - 2550', NULL),
(16, 'Dorothy Sweet', 'Et pariatur Excepte', 'Officia dicta distin', '09776656555', 'Birthday Package', 'C', 'reservation', '2024-12-25', '20:10:00', '2024-12-02 12:46:53', 'Pending', 1, 'markietaneo14@gmail.com', '15 PAX - 3000', '136370');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cater_packages`
--
ALTER TABLE `cater_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cater_packages`
--
ALTER TABLE `cater_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
