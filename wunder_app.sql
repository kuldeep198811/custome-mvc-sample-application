-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2020 at 05:42 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wunder_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `users_address`
--

CREATE TABLE `users_address` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` varchar(256) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `address_type` enum('home','office') NOT NULL DEFAULT 'home',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_address`
--

INSERT INTO `users_address` (`address_id`, `user_id`, `address`, `zip_code`, `city`, `address_type`, `created_at`, `updated_at`) VALUES
(7, 9, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-04 07:36:19', '0000-00-00 00:00:00'),
(8, 10, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-04 07:36:48', '0000-00-00 00:00:00'),
(19, 22, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-04 09:05:21', '0000-00-00 00:00:00'),
(20, 23, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-04 09:07:12', '0000-00-00 00:00:00'),
(21, 24, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-04 10:24:51', '0000-00-00 00:00:00'),
(22, 25, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-04 10:26:22', '0000-00-00 00:00:00'),
(23, 26, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-05 13:49:21', '0000-00-00 00:00:00'),
(24, 27, '159, Govind Garh Road', '248001', 'DEHRADUN', 'home', '2020-01-05 13:54:44', '0000-00-00 00:00:00'),
(25, 28, '159, Govind Garh Road ddddddd', '248001', 'DEHRADUN', 'home', '2020-01-05 16:19:09', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_payment_info`
--

CREATE TABLE `users_payment_info` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_owner` varchar(64) NOT NULL,
  `payment_data_id` varchar(128) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_payment_info`
--

INSERT INTO `users_payment_info` (`payment_id`, `user_id`, `account_owner`, `payment_data_id`, `created_at`, `updated_at`) VALUES
(1, 22, 'Kuldeep Singh', 'bfbbd309a5c806613b565792a48d9adf9cc68f2afe01d998f7ffef2ebfeb37e525b328df15d86d15286f62139999c47dssdsfsdf', '2020-01-04 09:05:23', '2020-01-04 09:05:53'),
(2, 23, 'Kuldeep Singh', '060931ae5a351b5446b0cc8e47df53f6face684321aa42c9fed53fa86ca345dabd37b7ab0366143b20c849657e829a77', '2020-01-04 09:07:14', '0000-00-00 00:00:00'),
(3, 24, 'Kuldeep Singh', '4b35354c83652dc6b752cc1f8f862d1af3e9395c9227138443d64ab99b003b671fa7ecc9b6e1478f89461825426ba8dc', '2020-01-04 10:24:52', '0000-00-00 00:00:00'),
(4, 25, 'Kuldeep Singh', 'ebb74a16a3f209c47850948763ae210206aab51f4c65017ce55919d6c91202b844605c18d92adc372327e763e9ee3eb7', '2020-01-04 10:26:23', '0000-00-00 00:00:00'),
(5, 26, 'Kuldeep Singh', '07ab7df1328fa5da79cd06469e50e86a216625da4719f2dbf76444906404fb9a3002ba3f2ebff10b4de487610e021e58', '2020-01-05 13:49:30', '0000-00-00 00:00:00'),
(6, 27, 'KUldeep', 'b8b92e3ca9211fc30e026319ba7ab8ccc02f558b24e01555524a3a2cb7b25c9e62bbb67de5f9e895d99843871180f3b7', '2020-01-05 13:54:45', '0000-00-00 00:00:00'),
(7, 28, 'kuldeep singh', 'f6f70cde651748a4ff259c6ceb2cae09966579d2a74e459a8fd407055a5b588587e84f83b1e79ec37f1ba8ab7aa0fad2', '2020-01-05 16:19:13', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_personal_info`
--

CREATE TABLE `users_personal_info` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `telephone` varchar(32) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_personal_info`
--

INSERT INTO `users_personal_info` (`user_id`, `first_name`, `last_name`, `telephone`, `created_at`, `updated_at`) VALUES
(1, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 07:26:08', '0000-00-00 00:00:00'),
(2, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 07:28:19', '0000-00-00 00:00:00'),
(9, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 07:36:19', '0000-00-00 00:00:00'),
(10, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 07:36:48', '0000-00-00 00:00:00'),
(22, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 09:05:21', '0000-00-00 00:00:00'),
(23, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 09:07:12', '0000-00-00 00:00:00'),
(24, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 10:24:51', '0000-00-00 00:00:00'),
(25, 'Kuldeep', 'Singh', '9992818439', '2020-01-04 10:26:22', '0000-00-00 00:00:00'),
(26, 'Kuldeep', 'Singh', '9992818439', '2020-01-05 13:49:21', '0000-00-00 00:00:00'),
(27, 'Kuldeep', '+919992818439', '9992818439', '2020-01-05 13:54:44', '0000-00-00 00:00:00'),
(28, 'Kuldeep', 'Singh', '9992818439', '2020-01-05 16:19:09', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_temp_data`
--

CREATE TABLE `users_temp_data` (
  `temp_id` int(11) NOT NULL,
  `form_data` mediumtext NOT NULL,
  `step_number` tinyint(4) NOT NULL,
  `cookie_id` varchar(64) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users_temp_data`
--

INSERT INTO `users_temp_data` (`temp_id`, `form_data`, `step_number`, `cookie_id`, `created_at`, `updated_at`) VALUES
(19, 'a:8:{s:10:\"csrf_token\";s:64:\"1853ab75a467a4c4f3838bae625c2f7068fa1aec48842829af5c77536e922e98\";s:10:\"first_name\";s:7:\"Kuldeep\";s:9:\"last_name\";s:5:\"Singh\";s:9:\"telephone\";s:10:\"9992818439\";s:7:\"address\";s:21:\"159, Govind Garh Road\";s:8:\"zip_code\";s:6:\"248001\";s:4:\"city\";s:8:\"DEHRADUN\";s:13:\"account_owner\";s:13:\"Kuldeep Singh\";}', 2, '248f0d3c-1781-4b21-9eeb-1dc815dea65a', '2020-01-05 07:58:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users_address`
--
ALTER TABLE `users_address`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_payment_info`
--
ALTER TABLE `users_payment_info`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `payment_data_id` (`payment_data_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_personal_info`
--
ALTER TABLE `users_personal_info`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_temp_data`
--
ALTER TABLE `users_temp_data`
  ADD PRIMARY KEY (`temp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users_address`
--
ALTER TABLE `users_address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users_payment_info`
--
ALTER TABLE `users_payment_info`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users_personal_info`
--
ALTER TABLE `users_personal_info`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users_temp_data`
--
ALTER TABLE `users_temp_data`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_address`
--
ALTER TABLE `users_address`
  ADD CONSTRAINT `users_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_personal_info` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_payment_info`
--
ALTER TABLE `users_payment_info`
  ADD CONSTRAINT `users_payment_info_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_personal_info` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
