-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Nov 27, 2024 at 11:51 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
