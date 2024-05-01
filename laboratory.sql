-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2024 at 09:47 AM
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
-- Database: `laboratory`
--

-- --------------------------------------------------------

--
-- Table structure for table `emp`
--

CREATE TABLE `emp` (
  `emp_id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp`
--

INSERT INTO `emp` (`emp_id`, `name`, `email`) VALUES
('TO183', 'Y.A.Kamal', 'TO183@eng.jfn.ac.lk');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `equip_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `available_qty` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `left_qty` int(11) NOT NULL,
  `total_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`equip_id`, `name`, `available_qty`, `brand`, `left_qty`, `total_qty`) VALUES
(1, 'Arduino Uno', 20, 'Arduino', 0, 20),
(2, 'Raspberry Pi', 20, 'Raspberry Pi Foundation', 0, 20),
(3, 'BeagleBone Black', 20, 'Texas Instruments', 0, 20),
(4, 'Nucleo Board', 20, 'STMicroelectronics', 0, 20),
(5, 'ESP32 Development Board', 20, 'Espressif Systems', 0, 20);

-- --------------------------------------------------------

--
-- Table structure for table `equipmentmodel`
--

CREATE TABLE `equipmentmodel` (
  `equip_id` int(11) NOT NULL,
  `model_num` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','admin') NOT NULL,
  `acc_status` enum('active','inactive') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`user_name`, `password`, `role`, `acc_status`) VALUES
('2021e183@eng.jfn.ac.lk', '1234', 'student', 'active'),
('admin', 'admin', 'admin', 'active'),
('TO183@eng.jfn.ac.lk', 'admin', 'admin', 'active'),
('2021e018@eng.jfn.ac.lk', '1234', 'student', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_no` int(11) NOT NULL,
  `student_reg` varchar(10) DEFAULT NULL,
  `to_id` varchar(10) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  `status` enum('pending','approved','borrowed','returned') DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `request_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`request_no`, `student_reg`, `to_id`, `date_time`, `status`, `start_time`, `end_time`, `request_date`) VALUES
(8, '2021E183', NULL, '2024-04-30 19:17:50', 'pending', '22:41:00', '22:42:00', '2024-04-10'),
(9, '2021E183', NULL, '2024-04-30 19:19:02', 'pending', '10:50:00', '10:51:00', '2024-05-01'),
(10, '2021E018', NULL, '2024-04-30 19:30:02', 'pending', '22:01:00', '23:00:00', '2024-04-24'),
(11, '2021E018', NULL, '2024-04-30 19:42:17', 'pending', '22:01:00', '23:00:00', '2024-04-24'),
(12, '2021E018', NULL, '2024-04-30 20:13:07', 'pending', '23:45:00', '23:46:00', '2024-04-24');

-- --------------------------------------------------------

--
-- Table structure for table `requestequipment`
--

CREATE TABLE `requestequipment` (
  `request_no` int(11) NOT NULL,
  `euip_id` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requestequipment`
--

INSERT INTO `requestequipment` (`request_no`, `euip_id`, `count`) VALUES
(8, 1, 2),
(8, 2, 4),
(9, 1, 3),
(9, 3, 7),
(10, 1, 4),
(10, 3, 5),
(11, 1, 4),
(11, 3, 5),
(12, 1, 3),
(12, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `reg_id` varchar(10) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `mid_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `batch_id` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`reg_id`, `first_name`, `mid_name`, `last_name`, `batch_id`, `email`) VALUES
('2021E018', 'Ashan', 'Odithya', 'Sirisena', '2020/21', '2021e018@eng.jfn.ac.lk'),
('2021E183', 'Janith', 'Hansidu', 'Yapa', '2020/21', '2021e183@eng.jfn.ac.lk');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_no`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`reg_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
