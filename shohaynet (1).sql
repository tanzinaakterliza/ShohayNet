-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2026 at 06:16 PM
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
-- Database: `shohaynet`
--

-- --------------------------------------------------------

--
-- Table structure for table `affected_areas`
--

CREATE TABLE `affected_areas` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `people_affected` int(11) DEFAULT 0,
  `incidents_count` int(11) DEFAULT 0,
  `impact_level` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `affected_areas`
--

INSERT INTO `affected_areas` (`id`, `name`, `people_affected`, `incidents_count`, `impact_level`, `status`) VALUES
(1, 'Sylhet', 12400, 45, 'high', 'Critical'),
(2, 'Cox\'s Bazar', 9800, 32, 'high', 'Critical'),
(3, 'Dhaka', 8200, 28, 'high', 'Critical'),
(4, 'Rajshahi', 5600, 18, 'medium', 'Monitoring'),
(5, 'Khulna', 4300, 14, 'medium', 'Monitoring'),
(6, 'Rangpur', 2100, 8, 'low', 'Stable'),
(7, 'Barisal', 1800, 6, 'low', 'Stable'),
(8, 'Mymensingh', 1100, 4, 'low', 'Stable'),
(9, 'Comilla', 900, 3, 'low', 'Stable');

-- --------------------------------------------------------

--
-- Table structure for table `benefits`
--

CREATE TABLE `benefits` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `benefits`
--

INSERT INTO `benefits` (`id`, `name`, `color`) VALUES
(1, 'Elimination of Duplication', 'pill-dark'),
(2, 'Faster Response', 'pill-light'),
(3, 'One Source of Truth', 'pill-teal'),
(4, 'Legal Compliance', 'pill-dark'),
(5, 'Better Reach', 'pill-light'),
(6, 'High Transparency', 'pill-light'),
(7, 'Sustainability', 'pill-teal');

-- --------------------------------------------------------

--
-- Table structure for table `clusters`
--

CREATE TABLE `clusters` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `lead` varchar(255) NOT NULL,
  `co_lead` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clusters`
--

INSERT INTO `clusters` (`id`, `name`, `icon`, `lead`, `co_lead`, `description`) VALUES
(1, 'Food Security', 'fas fa-seedling', 'MoF/MoA', 'WFP/FAO', 'THIS IS A TEST - Data coming from database!'),
(2, 'Health', 'fas fa-hand-holding-medical', 'MoHFW', 'WHO', 'Ensuring health services is the responsibility of Lead: MoHFW Co-lead: WHO.'),
(3, 'Shelter', 'fas fa-house-user', 'MoHPW', 'IFRC/UNDP', 'Housing assistance is led by Lead: MoHPW with the support of Co-lead: IFRC/UNDP.');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `status` enum('Pending','Completed','Failed') DEFAULT 'Pending',
  `donation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `full_name`, `mobile`, `email`, `amount`, `payment_method`, `message`, `transaction_id`, `status`, `donation_date`) VALUES
(1, 'Tabassum Huda Nishat', '01987654899', 'thnishat337@gmail.com', 121.00, 'Nagad', 'I want to donate', NULL, 'Pending', '2026-04-12 14:21:41'),
(2, 'Tanzina Akter', '01978654325', 'tanzinaakter@gmail.com', 108.00, 'Nagad', 'Donate now', NULL, 'Pending', '2026-04-19 14:33:13');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_reports`
--

CREATE TABLE `emergency_reports` (
  `id` int(11) NOT NULL,
  `report_type` enum('Fire','Flood','Earthquake','Medical Emergency','Accident','Other') NOT NULL,
  `severity` enum('Critical','High','Medium','Low') NOT NULL,
  `location_name` varchar(200) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `description` text NOT NULL,
  `affected_people` int(11) DEFAULT 0,
  `contact_name` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','In Progress','Resolved','Closed') DEFAULT 'Pending',
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_reports`
--

INSERT INTO `emergency_reports` (`id`, `report_type`, `severity`, `location_name`, `address`, `city`, `latitude`, `longitude`, `description`, `affected_people`, `contact_name`, `contact_phone`, `contact_email`, `photo_path`, `status`, `reported_at`, `updated_at`) VALUES
(1, 'Fire', 'Medium', 'sylhet', 'sylhet', 'Hobigonj', NULL, NULL, 'drtfyujhk', 15, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:15:53', '2026-04-19 06:15:53'),
(2, 'Fire', 'Medium', 'sylhet', 'sylhet', 'Hobigonj', NULL, NULL, 'drtfyujhk', 15, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:16:55', '2026-04-19 06:16:55'),
(4, 'Fire', 'Medium', 'sylhet', 'sylhet', 'Hobigonj', NULL, NULL, 'drtfyujhk', 15, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:18:09', '2026-04-19 06:18:09'),
(5, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:37:38', '2026-04-19 06:37:38'),
(6, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:40:23', '2026-04-19 06:40:23'),
(7, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:42:38', '2026-04-19 06:42:38'),
(8, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:44:02', '2026-04-19 06:44:02'),
(9, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:45:01', '2026-04-19 06:45:01'),
(10, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:45:28', '2026-04-19 06:45:28'),
(11, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:45:56', '2026-04-19 06:45:56'),
(12, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:46:23', '2026-04-19 06:46:23'),
(13, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:49:17', '2026-04-19 06:49:17'),
(14, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:50:04', '2026-04-19 06:50:04'),
(15, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:50:34', '2026-04-19 06:50:34'),
(16, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:53:19', '2026-04-19 06:53:19'),
(17, 'Earthquake', 'Medium', 'Dhaka', 'Gazipur ,Dhaka ,Bangladesh', 'Gazipur', NULL, NULL, 'we need help', 4, 'Tabassum Huda Nishat', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 06:54:10', '2026-04-19 06:54:10'),
(18, 'Fire', 'Critical', 'cumilla', 'Cumilla', 'jkhjhkjh', NULL, NULL, 'dcfgbjhh', 3, 'Runa Islam', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 11:04:06', '2026-04-19 11:04:06'),
(19, 'Accident', 'Medium', 'Khulna', 'Khulna ,Bangladesh', 'Bagerhat', NULL, NULL, 'fyguhjnk', 13, 'Mina', '01978654325', 'thnishat337@gmail.com', NULL, 'Pending', '2026-04-19 14:40:57', '2026-04-19 14:40:57');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `severity` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `reporter` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`id`, `title`, `location`, `type`, `severity`, `date`, `reporter`) VALUES
(1, 'Earthquake Magnitude 5.8 — Dhaka', 'Dhaka', 'Earthquake', 'high', '2025-04-02', 'Ahmed Kabir'),
(2, 'Flash Flood — Sylhet Division', 'Sylhet', 'Flood', 'high', '2025-04-10', 'Field Team Alpha'),
(3, 'Industrial Fire — Rajshahi EPZ', 'Rajshahi', 'Fire', 'medium', '2024-04-11', 'Fire Service'),
(4, 'Cyclone Warning — Cox\'s Bazar', 'Cox\'s Bazar', 'Cyclone', 'medium', '2025-04-08', 'BMD'),
(5, 'Waterlogging — Chittagong City', 'Chittagong', 'Waterlogging', 'low', '2025-04-05', 'City Corporation'),
(6, 'Minor Landslide — Rangamati', 'Rangamati', 'Landslide', 'low', '2025-03-28', 'Hill District Authority');

-- --------------------------------------------------------

--
-- Table structure for table `kpi_data`
--

CREATE TABLE `kpi_data` (
  `id` int(11) NOT NULL,
  `label` varchar(100) NOT NULL,
  `value` int(11) NOT NULL,
  `change_text` varchar(20) NOT NULL,
  `change_color` varchar(20) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `icon_color` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kpi_data`
--

INSERT INTO `kpi_data` (`id`, `label`, `value`, `change_text`, `change_color`, `icon`, `icon_color`) VALUES
(1, 'Total Incidents', 9999, '+8.2%', '#D32F2F', 'fas fa-book', '#D32F2F'),
(2, 'People Affected', 45300, '+5.4%', '#4CAF50', 'fas fa-users', '#1976D2'),
(3, 'Resources Deployed', 320, '+3.3%', '#D32F2F', 'fas fa-ambulance', '#D32F2F'),
(4, 'Volunteers Active', 850, '+6.8%', '#1976D2', 'fas fa-hand-holding-heart', '#FBC02D');

-- --------------------------------------------------------

--
-- Table structure for table `live_locations`
--

CREATE TABLE `live_locations` (
  `id` int(11) NOT NULL,
  `location_name` varchar(150) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `type` enum('Flood','Cyclone','Earthquake','Fire','Medical Emergency','Shelter','Rescue Team','Relief Distribution','Other') NOT NULL,
  `severity` enum('High Risk','Medium','Low','Safe') NOT NULL DEFAULT 'Medium',
  `affected_people` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `reported_by` varchar(100) DEFAULT 'System',
  `status` enum('Active','Resolved','Monitoring') DEFAULT 'Active',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_locations`
--

INSERT INTO `live_locations` (`id`, `location_name`, `latitude`, `longitude`, `type`, `severity`, `affected_people`, `description`, `reported_by`, `status`, `last_updated`, `created_at`) VALUES
(1, 'Tabassum Huda\'s Emergency Help', 23.68500000, 90.35630000, 'Fire', 'High Risk', 0, 'need help from khulna', 'Tabassum Huda', 'Active', '2026-04-13 14:45:00', '2026-04-13 14:45:00'),
(3, 'Nishat\'s Emergency Help', 23.68500000, 90.35630000, '', 'High Risk', 0, 'hfgasfdf', 'Nishat', 'Active', '2026-04-19 14:34:39', '2026-04-19 14:34:39');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `type` enum('incoming','outgoing') NOT NULL DEFAULT 'outgoing',
  `urgent` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `body`, `type`, `urgent`, `created_at`) VALUES
(1, 'Commander Dina', 'All units, we have confirmation that flooding has receded in Zone B. Begin assessment procedures.', 'incoming', 1, '2026-04-19 13:19:07'),
(2, 'You', 'Copy that. Dispatching assessment team now.', 'outgoing', 0, '2026-04-19 13:19:07'),
(3, 'Lt. Maria', 'Medical team is en route to the shelter. ETA 10 minutes.', 'incoming', 0, '2026-04-19 13:19:07'),
(4, 'You', 'Great. I\'ll notify the shelter coordinator.', 'outgoing', 0, '2026-04-19 13:19:07');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `nid` varchar(50) DEFAULT NULL,
  `family_count` varchar(20) DEFAULT NULL,
  `division` varchar(50) DEFAULT NULL,
  `district` varchar(50) DEFAULT NULL,
  `area_gps` text DEFAULT NULL,
  `emergency_types` text DEFAULT NULL,
  `urgency` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `full_name`, `phone`, `nid`, `family_count`, `division`, `district`, `area_gps`, `emergency_types`, `urgency`, `description`, `created_at`) VALUES
(3, 'Tabassum Huda Nishat', '67890', '4567890', '', 'Dhaka', 'Gazipur', '23.9456, 90.3856', 'Food', 'Medium', 'i nedd some food', '2026-04-12 06:26:07'),
(4, 'SOS User', 'N/A', '', '', '', '', '23.94570, 90.38572', 'Severe Flooding', 'Medium', ' [Logged at 4/12/2026, 12:36:22 PM]', '2026-04-12 06:36:22'),
(5, 'SOS User', 'N/A', '', '', '', '', '23.94562, 90.38564', 'Medical Support', 'Medium', ' [Logged at 4/12/2026, 12:42:54 PM]', '2026-04-12 06:42:54'),
(6, 'SOS User', 'N/A', '', '', '', '', 'Not Captured', 'Medical Support', 'Medium', ' [Logged at 4/14/2026, 7:05:39 PM]', '2026-04-14 13:05:39'),
(7, 'Runa Islam', '01978654325', '4567890', '1-2', 'Rangpur', 'Rangpur', '23.9457, 90.3857', 'Food', 'Medium', 'tgyuhiuj', '2026-04-19 14:42:37'),
(8, 'SOS User', 'N/A', '', '', '', '', '23.94573, 90.38570', 'Rescue', 'Medium', 'thjhjo[pl [Logged at 4/19/2026, 8:43:48 PM]', '2026-04-19 14:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `category` enum('Medical','Food','Shelter','Other') NOT NULL,
  `location` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` enum('Available','Low Stock','Checked Out','In Maintenance') NOT NULL DEFAULT 'Available',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `item_name`, `category`, `location`, `quantity`, `status`, `last_updated`, `description`, `created_at`) VALUES
(1, 'Medical Kits', 'Medical', 'Dhaka Center', 18, 'Low Stock', '2026-04-12 05:32:42', 'Complete first aid kit with bandages and antiseptics', '2026-04-12 05:32:42'),
(2, 'Emergency Medicines', 'Medical', 'Rajshahi Office', 20, 'Available', '2026-04-12 05:32:42', 'Antibiotics and saline packets', '2026-04-12 05:32:42'),
(3, 'ORS Packets', 'Medical', 'Chittagong', 60, 'Available', '2026-04-12 05:32:42', 'Oral Rehydration Solution', '2026-04-12 05:32:42'),
(4, 'Mask & Goggles', 'Medical', 'Khulna Branch', 150, 'Available', '2026-04-12 05:32:42', 'Disposable face masks and safety goggles', '2026-04-12 05:32:42'),
(5, 'Bottled Drinking Water', 'Food', 'Chittagong', 85, 'Checked Out', '2026-04-12 05:32:42', '500ml mineral water bottles', '2026-04-12 05:32:42'),
(6, 'Dry Food Packets', 'Food', 'Sylhet Camp', 70, 'Available', '2026-04-12 05:32:42', 'Emergency dry food packets', '2026-04-12 05:32:42'),
(7, 'Tents', 'Shelter', 'Rajshahi Office', 14, 'In Maintenance', '2026-04-12 05:32:42', 'Portable shelter tents', '2026-04-12 05:32:42'),
(8, 'Blankets', 'Shelter', 'Khulna ', 50, 'Available', '2026-04-12 06:02:30', 'Warm blankets for emergency use', '2026-04-12 05:32:42'),
(11, 'oxyzen', 'Medical', 'Barishal', 27, 'Available', '2026-04-19 14:35:25', 'Added from inventory page', '2026-04-19 14:35:25');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `skills` varchar(255) NOT NULL,
  `availability` enum('Full Time','Part Time','Weekends Only') NOT NULL,
  `status` enum('Pending','Approved','Active') DEFAULT 'Pending',
  `assigned_missions` int(11) DEFAULT 0,
  `hours_served` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`id`, `full_name`, `email`, `phone`, `skills`, `availability`, `status`, `assigned_missions`, `hours_served`, `created_at`) VALUES
(1, 'Tabassum Huda Nishat', 'thnishat337@gmail.com', '4567880989', 'Food Distribution', 'Part Time', 'Pending', 0, 0, '2026-04-12 07:44:52'),
(2, 'Nijhum Akter', 'njjioji@gmail.com', '56789', 'Counseling', 'Part Time', 'Pending', 0, 0, '2026-04-12 07:46:08'),
(10, 'Tabassum Huda Nishat', 'rinaakter@gmail.com', '01978658766', 'Food Distribution', 'Weekends Only', 'Pending', 0, 0, '2026-04-19 11:01:40'),
(13, 'Runa Islam', 'runaislam@gmail.com', '01978657888', 'Logistics', 'Part Time', 'Pending', 0, 0, '2026-04-19 11:05:51'),
(15, 'suma Islam', 'sumaislam@gmail.com', '01978665437', 'Medical', 'Part Time', 'Pending', 0, 0, '2026-04-19 14:31:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affected_areas`
--
ALTER TABLE `affected_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `benefits`
--
ALTER TABLE `benefits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clusters`
--
ALTER TABLE `clusters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_reports`
--
ALTER TABLE `emergency_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi_data`
--
ALTER TABLE `kpi_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `live_locations`
--
ALTER TABLE `live_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `affected_areas`
--
ALTER TABLE `affected_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `benefits`
--
ALTER TABLE `benefits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clusters`
--
ALTER TABLE `clusters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `emergency_reports`
--
ALTER TABLE `emergency_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kpi_data`
--
ALTER TABLE `kpi_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `live_locations`
--
ALTER TABLE `live_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
