-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 13, 2022 at 09:32 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ocwbs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking_list`
--

CREATE TABLE `booking_list` (
  `id` int(30) NOT NULL,
  `code` varchar(100) NOT NULL,
  `client_name` text NOT NULL,
  `contact` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `vehicle_id` int(30) NOT NULL,
  `schedule` date NOT NULL,
  `total_amount` float(15,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(3) NOT NULL DEFAULT 0 COMMENT '0 = Pending,\r\n1 = Confirmed,\r\n2 = Arrived,\r\n3 = On-process,\r\n4 = Done',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking_list`
--

INSERT INTO `booking_list` (`id`, `code`, `client_name`, `contact`, `email`, `address`, `vehicle_id`, `schedule`, `total_amount`, `status`, `date_created`, `date_updated`) VALUES
(3, '20220400001', 'Mark Cooper', '09123456789', 'mcooper@sample.com', 'Sample Address Only', 1, '2022-04-13', 340.00, 2, '2022-04-13 13:43:22', '2022-04-13 14:45:47'),
(4, '20220400002', 'John Smith', '09789456321', 'jsmith@sample.com', 'Sample Address 2', 1, '2022-04-15', 300.00, 3, '2022-04-13 13:44:27', '2022-04-13 14:38:09');

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `booking_id` int(30) NOT NULL,
  `service_id` int(30) NOT NULL,
  `price` float(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking_services`
--

INSERT INTO `booking_services` (`booking_id`, `service_id`, `price`) VALUES
(1, 2, 40.00),
(1, 3, 50.00),
(1, 1, 150.00),
(1, 4, 100.00),
(2, 2, 40.00),
(2, 3, 50.00),
(2, 1, 150.00),
(2, 4, 100.00),
(3, 2, 40.00),
(3, 3, 50.00),
(3, 1, 150.00),
(3, 4, 100.00),
(4, 3, 50.00),
(4, 1, 150.00),
(4, 4, 100.00),
(5, 2, 100.00),
(5, 3, 100.00),
(5, 1, 200.00),
(5, 4, 200.00);

-- --------------------------------------------------------

--
-- Table structure for table `price_list`
--

CREATE TABLE `price_list` (
  `service_id` int(30) NOT NULL,
  `vehicle_id` int(30) NOT NULL,
  `price` float(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `price_list`
--

INSERT INTO `price_list` (`service_id`, `vehicle_id`, `price`) VALUES
(1, 5, 300.00),
(1, 2, 50.00),
(1, 3, 75.00),
(1, 1, 150.00),
(1, 4, 200.00),
(2, 5, 150.00),
(2, 2, 20.00),
(2, 3, 30.00),
(2, 1, 40.00),
(2, 4, 100.00),
(4, 5, 300.00),
(4, 2, 50.00),
(4, 3, 60.00),
(4, 1, 100.00),
(4, 4, 200.00),
(3, 5, 150.00),
(3, 2, 20.00),
(3, 3, 20.00),
(3, 1, 50.00),
(3, 4, 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_list`
--

CREATE TABLE `service_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_list`
--

INSERT INTO `service_list` (`id`, `name`, `description`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 'Wash', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce iaculis dui non ante suscipit mollis. Suspendisse ut consequat sem. In tempus purus in interdum porttitor.', 1, 0, '2022-04-13 09:40:19', '2022-04-13 09:40:19'),
(2, 'Tire Black', 'Integer nec eleifend sapien. Nunc nec massa et magna vestibulum malesuada. Phasellus sed elit sed urna sagittis tempor non at libero. Nunc quam felis, commodo sit amet sapien in, finibus ullamcorper lorem.', 1, 0, '2022-04-13 09:40:44', '2022-04-13 09:40:44'),
(3, 'Vacuum', 'Donec vel leo et nunc varius finibus. Quisque pellentesque malesuada arcu, et sagittis velit iaculis eu. Aenean at pretium libero, at tempor quam.', 1, 0, '2022-04-13 09:40:58', '2022-04-13 09:40:58'),
(4, 'Wax', 'Ut sodales, sapien ut laoreet interdum, ipsum sapien viverra velit, ac aliquet ex nunc eu nisl. Vivamus hendrerit lacus nec enim rhoncus, quis dapibus lectus consequat. Nam tincidunt tellus eu mi porta, quis malesuada magna tincidunt.', 1, 0, '2022-04-13 09:41:15', '2022-04-13 09:41:15');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Online Car Wash Booking System'),
(6, 'short_name', 'OCWBS - PHP'),
(11, 'logo', 'uploads/logo.png?v=1649834631'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.png?v=1649834631');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatars/1.png?v=1649834664', NULL, 1, '2021-01-20 14:02:37', '2022-04-13 15:24:24'),
(2, 'John', 'Smith', 'jsmith', '1254737c076cf867dc53d60a0364f38e', 'uploads/avatars/2.png?v=1649834681', NULL, 2, '2022-04-13 15:01:30', '2022-04-13 15:28:29');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_list`
--

CREATE TABLE `vehicle_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vehicle_list`
--

INSERT INTO `vehicle_list` (`id`, `name`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, '4 Wheeler', 1, 0, '2022-04-13 09:22:20', '2022-04-13 09:22:20'),
(2, '2 wheeler', 1, 0, '2022-04-13 09:22:53', '2022-04-13 09:22:53'),
(3, '3 wheeler', 1, 0, '2022-04-13 09:23:17', '2022-04-13 09:23:17'),
(4, '6 wheeler', 1, 0, '2022-04-13 09:23:25', '2022-04-13 09:23:25'),
(5, '10 wheeler', 1, 0, '2022-04-13 09:23:36', '2022-04-13 09:23:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking_list`
--
ALTER TABLE `booking_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `price_list`
--
ALTER TABLE `price_list`
  ADD KEY `service_id` (`service_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `service_list`
--
ALTER TABLE `service_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_list`
--
ALTER TABLE `vehicle_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking_list`
--
ALTER TABLE `booking_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_list`
--
ALTER TABLE `service_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicle_list`
--
ALTER TABLE `vehicle_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking_list`
--
ALTER TABLE `booking_list`
  ADD CONSTRAINT `booking_vehicle_id_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `price_list`
--
ALTER TABLE `price_list`
  ADD CONSTRAINT `price_service_id_fk` FOREIGN KEY (`service_id`) REFERENCES `service_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `price_vehicle_id_fk` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle_list` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
