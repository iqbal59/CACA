-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 20, 2019 at 11:25 AM
-- Server version: 5.7.24
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `load_data`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` int(5) NOT NULL,
  `bankname` varchar(100) DEFAULT NULL,
  `branchbank` varchar(100) DEFAULT NULL,
  `account` varchar(5) DEFAULT NULL,
  `iban` varchar(100) DEFAULT NULL,
  `status_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `type`, `bankname`, `branchbank`, `account`, `iban`, `status_id`) VALUES
(1, 'Cash', 0, '', '', '21474', '', 0),
(3, 'Zurich Cantonal Bank', 1, 'Zurich Cantonal Bank', 'New York', '21474', 'GB23 1123 1213 4343 3444 43', 0),
(4, 'Alternative Bank', 1, 'Alternative Bank', 'Denver', '21474', 'GB23 1123 1213 4343 3444 43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `appconfig`
--

CREATE TABLE `appconfig` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appconfig`
--

INSERT INTO `appconfig` (`id`, `name`, `value`) VALUES
(1, 'inv_prefix', 'INV-'),
(2, 'inv_suffix', ''),
(3, 'project_prefix', 'PRJ-'),
(4, 'project_suffix', ''),
(5, 'expense_prefix', 'EXP-'),
(6, 'expense_suffix', ''),
(7, 'proposal_prefix', 'PRO-'),
(8, 'proposal_suffix', ''),
(9, 'order_prefix', 'ORD-'),
(10, 'order_suffix', ''),
(11, 'tax_label', 'TAX'),
(12, 'product_prefix', 'PRD-'),
(13, 'vendor_prefix', 'VEN-'),
(14, 'customer_prefix', 'CUST-'),
(15, 'lead_prefix', 'LEAD-'),
(16, 'ticket_prefix', 'TKT-'),
(17, 'staff_prefix', 'STAFF-'),
(18, 'purchase_prefix', 'PO-'),
(19, 'task_prefix', 'TASK-'),
(20, 'invoice_series', ''),
(21, 'project_series', ''),
(22, 'product_series', ''),
(23, 'order_series', ''),
(24, 'proposal_series', ''),
(25, 'vendor_series', ''),
(26, 'customer_series', ''),
(27, 'expense_series', ''),
(28, 'lead_series', ''),
(29, 'ticket_series', ''),
(30, 'staff_series', ''),
(31, 'purchase_series', '1'),
(32, 'task_series', ''),
(33, 'deposit_prefix', 'DEP-'),
(34, 'deposit_series', '1'),
(35, 'decimal_separator', '.'),
(36, 'thousand_separator', ','),
(37, 'appointment_color', '#23ce54'),
(38, 'project_color', '#81c5f4'),
(39, 'task_color', '#f4de81');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(5) NOT NULL,
  `contact_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `contact_id`, `staff_id`, `booking_date`, `start_time`, `end_time`, `status`) VALUES
(1, 23, 1, '2018-04-02', '09:00:00', '09:30:00', 1),
(2, 23, 1, '2018-04-02', '09:30:00', '10:00:00', 3);

-- --------------------------------------------------------

--
-- Table structure for table `branding`
--

CREATE TABLE `branding` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branding`
--

INSERT INTO `branding` (`id`, `name`, `value`) VALUES
(1, 'meta_keywords', 'Ciuis™ CRM software for customer relationship management is available for sale, so you can get more information to take advantage of your exclusive concierge.'),
(2, 'meta_description', 'Ciuis, CRM, Project Management tool, client management, crm, customer, expenses, invoice system, invoices, lead, project management, recurring invoices, sales, self hosted, support tickets, task manager, ticket system'),
(3, 'admin_login_image', 'login.jpg'),
(4, 'client_login_image', 'login.jpg'),
(7, 'admin_login_text', 'Welcome! With Ciuis CRM you can easily manage your customer relationships and save time on your business.\r\n</br>\r\n</br>\r\n<strong>Admin Login: </strong></br>\r\nUsername: lance@example.com </br>\r\nPassword: demo </br>\r\n</br>\r\n<strong>User/Staff </strong></br>\r\nUsername: emma@example.com </br>\r\nPassword: demo </br>\r\n</br>\r\n<strong>Tax Consultant Login: </strong> </br>\r\nUsername: guy@example.com </br>\r\nPassword: demo </br>\r\n</br>\r\n\r\n'),
(8, 'client_login_text', 'Ciuis™ CRM software for customer relationship management is available for sale, so you can get more information to take advantage of your exclusive concierge.\r\n\r\n</br>\r\n<strong>Customer/Client Login:</strong> </br>\r\nUsername: sue@example.com </br>\r\nPassword: demo </br>\r\n</br>'),
(9, 'enable_support_button_on_client', '1'),
(10, 'favicon_icon', 'logo-fav.png'),
(11, 'support_button_title', 'Ciuis Support'),
(12, 'support_button_link', 'https://support.suisesoft.tech/'),
(13, 'title', 'Ciuis CRM'),
(14, 'nav_logo', ''),
(15, 'title', 'Ciuis CRM'),
(16, 'app_logo', ''),
(17, 'preloader', 'preloader.gif'),
(18, 'disable_preloader', '0');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` mediumtext,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content`, `relation_type`, `relation`, `staff_id`, `created`) VALUES
(1, 'Lorem ipsum dolor sit amet!', 'proposal', 1, 1, '2017-09-05 06:22:36'),
(2, 'Lorem ipsum dolor sit amet!', 'proposal', 2, 1, '2017-09-05 06:22:36'),
(4, 'Lorem ipsum comment.', 'proposal', 3, 1, '2017-09-10 01:23:55'),
(5, 'test', 'proposal', 3, NULL, '2017-09-10 01:29:20'),
(6, 'Test Comment', 'proposal', 3, NULL, '2017-09-10 01:30:38'),
(7, 'Sample comment', 'proposal', 5, 1, '2017-11-25 04:55:21'),
(8, 'Sample comment', 'proposal', 2, 1, '2018-01-10 00:19:23'),
(9, 'Sample', 'proposal', 2, 1, '2018-01-10 00:22:48'),
(10, 'Make a little discount.', 'proposal', 5, 1, '2018-01-22 20:47:51');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `extension` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `language` varchar(100) NOT NULL,
  `address` text,
  `skype` varchar(100) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `customer_id` int(5) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `primary` int(5) DEFAULT '0',
  `admin` int(5) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `surname`, `phone`, `extension`, `mobile`, `email`, `username`, `password`, `language`, `address`, `skype`, `linkedin`, `customer_id`, `position`, `primary`, `admin`, `inactive`) VALUES
(8, 'Sandra', 'Bailey', '(981) 450 5274', '102', '(948) 153 4877', 'sandra-86@example.com', '', '', '', '2514 W Tanner Ranch Rd, Queen Creek, AZ, 85142', 'Bailey', 'Bailey', 1, 'Sales Agent', 0, 0, 0),
(11, 'Kyle', 'Romero', '(946) 610 7796', '21', '(946) 610 7796', 'kyle.romero@example.com', '', '$2y$10$PIoAOeFgAkAHb1JZkD.kVeyXcANIz3y43uCSdYpjtmXq9vDXbbeM6', '', 'USA', 'kyle', 'kyle', 5, 'Sales Agent', 1, 0, 0),
(12, 'Charles', 'Simmons', '(271) 967 5863', '', '(271) 967 5863', 'charles-91@example.com', '', '$2y$10$FrXB.1neoDBrR25q0EGa9OCuBb6rh45VsD/v/YUO5oZhwO9tyaLam', '', 'RUSSIA', 'charles2323', 'charles2323', 5, 'Sales Agent', 1, 0, 0),
(13, 'Phillip', 'Estrada', '(666) 295 5922', '', '(666) 295 5922', 'phillip85@example.com', '', '$2y$10$pqF77bPYxazC7VcyXrfJL.D/C8l4.04xVeCEKW2lY0SCYpsXaHwYO', '', 'USA', 'phillip85', 'phillip85', 1, 'Product Manager', 1, 0, 0),
(14, 'Michel', 'Kworks', '1 993 23223', '', '', 'michel@example.com', '', '$2y$10$hZCawFLfCSIjDWST/k9RjubsJCc0j3WGrVZkgZ4uiK5vTrlNkuDQK', '', 'Test', 'michel', '', 16, 'General Manager', 1, 0, NULL),
(15, 'Gerald', 'DeGroot', '626-931-8754', '23', '626-931-8754', 'chaim@example.com', '', '$2y$10$uVDLwo5P.MX89gbyAHuNSuToHYLV8B5gtTOiTdPjShxGXAKIo6iu2', '', '4521 Providence Lane', 'chaim', 'chaim', 17, 'General Manager', 1, 0, NULL),
(16, 'Terry', 'McCoy', '(467) 826 4441', '11', '(467) 826 4441', 'terry_mccoy@example.com', '', '$2y$10$54kEiZGHESg.RMspMR9ro.fvKw56ux09ArXbmYlYvfQ.ybCVocixW', '', 'United States', 'terry', 'teryy', 9, 'Sales Agent', 1, 0, NULL),
(17, 'Robert Lopez', 'Lopez', '(344) 888 6449', '', '(344) 888 6449', 'robert.lopez@example.com', '', '$2y$10$cQqH.HpkZ5YAvO3AITYbLesxS/w5Xd2o.kGCP9LOo6DoR/PN9Jzce', '', '', '', '', 9, 'General Manager', NULL, 0, NULL),
(18, 'Mary', 'Murphy', '(694) 752 3564', '', '(694) 752 3564', 'mary.murphy@example.com', '', '$2y$10$QtEg17joyKhYOMo0GDYb/OhMtnJyD900CBIqiLsplglK1xzfky4/W', '', '', '', '', 9, '', NULL, 0, NULL),
(19, 'Theresa', 'Sullivan', '(781) 560 5175', '', '(781) 560 5175', 'theresa_82@example.com', '', '$2y$10$VvekKYEU.RqtGaNRqXALLu/a6wimQF.iRlCQz07EFic9QUjK49LYy', '', '', '', '', 9, '', NULL, 0, NULL),
(20, 'Anna', 'Holland', '(221) 862 7179', '11', '(221) 862 7179', 'anna-holland@example.com', '', '$2y$10$uE/XaLobpbnPIv9tpiKex.6q7UHcbauuGTgpWElggKNZVHYT0iGd.', '', 'Address lorem ipsum.', 'anna', 'anna', 9, 'Henel', 1, 0, NULL),
(21, 'Ann', 'Arbor', '(221) 862 7179', '', '(467) 826 4441', 'quice@example.com', '', '$2y$10$lMewMEh8/3wUcT48CRUBF.bFbypReQhIvap5Je59mRz/nkulLvPbG', '', '', '', '', 17, 'Sales Agent', 1, 0, NULL),
(22, 'Indigo', 'Violet', '(221) 862 7179', '222', '(467) 826 4441', 'indigo@example.com', '', '$2y$10$fw20vep0VddOgQVPhNrW0u7OrP6zgXWmXnSdhYQzUX2q.9X9OTDwO', '', 'United states of america', 'indigo', 'indigo', 17, 'Sales Agent', 1, 0, NULL),
(23, 'Sue', 'Shei', '(221) 862 7179', '222', '(781) 560 5175', 'sue@example.com', '', '$2y$10$fw20vep0VddOgQVPhNrW0u7OrP6zgXWmXnSdhYQzUX2q.9X9OTDwO', '', '22222', '222', '22', 17, 'Sales Agent', 1, 1, NULL),
(26, 'Andiano', 'Molly', '(221) 862 7179', '', '(467) 826 4441', 'molly@example.com', '', '$2y$10$7yT2BcUNFbc1G0cSx/xhseVPZJySTNosOtMv5azjyosNnMTnA0gGa', '', '', '', '', 17, '', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `customergroups`
--

CREATE TABLE `customergroups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_number` varchar(100) DEFAULT NULL,
  `type` int(5) DEFAULT NULL,
  `created` date NOT NULL,
  `staff_id` int(5) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `namesurname` varchar(200) DEFAULT NULL,
  `groupid` int(5) NOT NULL,
  `taxoffice` varchar(100) DEFAULT NULL,
  `taxnumber` varchar(50) DEFAULT NULL,
  `ssn` varchar(100) DEFAULT NULL,
  `executive` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(50) DEFAULT NULL,
  `country_id` int(5) DEFAULT NULL,
  `state` varchar(200) DEFAULT NULL,
  `state_id` int(5) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `town` varchar(200) DEFAULT NULL,
  `billing_street` varchar(200) NOT NULL,
  `billing_city` varchar(100) NOT NULL,
  `billing_state` varchar(200) NOT NULL,
  `billing_state_id` int(5) DEFAULT NULL,
  `billing_zip` varchar(50) NOT NULL,
  `billing_country` int(5) NOT NULL,
  `shipping_street` varchar(200) NOT NULL,
  `shipping_city` varchar(200) NOT NULL,
  `shipping_state` varchar(255) NOT NULL,
  `shipping_state_id` int(5) DEFAULT NULL,
  `shipping_zip` varchar(255) NOT NULL,
  `shipping_country` int(11) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `risk` int(5) DEFAULT '0',
  `customer_status_id` int(5) DEFAULT '1' COMMENT '0 = Inactive | 1 = Active',
  `subsidiary_parent_id` int(5) NOT NULL,
  `default_payment_method` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_number`, `type`, `created`, `staff_id`, `company`, `namesurname`, `groupid`, `taxoffice`, `taxnumber`, `ssn`, `executive`, `address`, `zipcode`, `country_id`, `state`, `state_id`, `city`, `town`, `billing_street`, `billing_city`, `billing_state`, `billing_state_id`, `billing_zip`, `billing_country`, `shipping_street`, `shipping_city`, `shipping_state`, `shipping_state_id`, `shipping_zip`, `shipping_country`, `latitude`, `longitude`, `phone`, `fax`, `email`, `web`, `risk`, `customer_status_id`, `subsidiary_parent_id`, `default_payment_method`) VALUES
(1, NULL, 0, '2017-04-16', 1, 'H&M Law Office', NULL, 0, 'Washington DC Tax & Revenue', '456364265', NULL, 'Roger Wade', '799 E DRAGRAM SUITE 5A TUCSON AZ 85705 USA', '97311-8487', 23, 'Uk', NULL, 'Deve', 'Ata', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, '', '', '+1 (808) 136 4131', '+1 (808) 136 4131', 'info@orgon.com', 'www.example.com', 17, 1, 0, NULL),
(6, NULL, 0, '2017-08-22', 1, 'Northern Star', '', 0, '', '0', '', '', '7110 Gum Branch Rd, Richlands, NC, 28574', '54617', 1, 'NC', NULL, 'New York', '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, '', '', '(140) 211 2494', '', 'judyyoung@example.com', 'www.example.com', 0, 1, 0, 'bank'),
(8, NULL, 0, '2017-08-22', 1, 'Transhex LLC.', '', 0, '', '0', '', '', '95 Meadow St, Winsted, CT, 06098', '54617', 6, 'CT', NULL, 'New York', '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, '', '', '(954) 630 6210', '', 'nicholas-90@example.com', 'www.example.com', 0, 1, 0, 'bank'),
(9, NULL, 1, '2017-08-23', 1, '', 'Neuer Manuel', 0, 'New York', '0', '234354241', 'Himself', 'Celeste Slater\n606-3727 Ullamcorper. Street\nRoseville NH 11523', '012345', 83, 'Berlin', NULL, 'Stadt', 'Dorf', 'Celeste Slater\n606-3727 Ullamcorper. Street\nRoseville NH 11523', 'Stadt', 'Berlin', NULL, '012345', 83, 'Celeste Slater\n606-3727 Ullamcorper. Street\nRoseville NH 11523', 'Stadt', 'Berlin', NULL, '012345', 83, '', '', '00493661123456', '00493661123456', 'manuel@neuer.de', 'www.neuer.de', 42, 1, 0, 'stripe'),
(12, NULL, 0, '2017-08-24', 1, 'Unadoncare INC.', '', 0, '', '0', '', '', '95 Meadow St, Winsted, CT, 06098', '54617', 3, 'CT', NULL, 'New York', '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, '', '', '(432) 156 5172', '', 'jane-85@example.com', 'www.example.com', 0, 1, 0, 'bank'),
(15, NULL, 0, '2017-08-24', 1, 'Perscriptorem Pictures', '', 0, '', '0', '', '', '70 Bowman St. South Windsor, CT 06074', '54617', 2, 'GA', NULL, 'New York', '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, '', '', '(296) 452 9522', '', 'joycemccoy@example.com', 'www.example.com', 0, 1, 0, 'stripe'),
(16, NULL, 0, '2017-11-12', 1, 'Donway INC.', '', 0, 'London', '22112211', '', '', '5094 Vidrine Rd, Ville Platte, LA, 22356', '54617', 5, 'MI', NULL, 'New York', '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, '', '', '+1-202-555-0160', '', 'lance@example.com', 'www.example.com', 0, 1, 17, 'stripe'),
(17, NULL, 0, '2017-11-13', 1, 'DHARMA Initiative', '', 0, 'London', '221122113', '', 'Gerald DeGroot', '4521 Providence Lane La Puente CA', '33344', 236, 'California', NULL, 'Los Angeles', 'Neiler', '4521 Providence Lane La Puente CA', 'Los Angeles', 'California', NULL, '33344', 236, '4521 Providence Lane La Puente CA', 'Los Angeles', 'California', NULL, '33344', 236, '', '', '+44 232 2322', '+44 232 2322', 'dharna@example.com', 'www.dharna.com', 48, 1, 0, 'bank'),
(18, NULL, 0, '2018-03-21', 1, 'Parallax Corporation', '', 0, 'New York', '2147483647', '', 'Melinda Gibson', 'Example Address', '2233', 236, 'California', NULL, 'Los Angeles', 'Bla', 'Example Address', 'Los Angeles', 'California', NULL, '2233', 236, 'Example Address', 'Los Angeles', 'California', NULL, '2233', 236, '', '', '1 944 444 4444', '1 944 444 4444', 'parallax@example.com', 'www.parallax.com', 0, 1, 17, 'bank');

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `order` int(11) NOT NULL,
  `data` longtext NOT NULL,
  `relation` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `active` varchar(100) NOT NULL DEFAULT 'true',
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields_data`
--

CREATE TABLE `custom_fields_data` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `db_backup`
--

CREATE TABLE `db_backup` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'Administrator'),
(2, 'Sales Agent'),
(3, 'IT Services'),
(4, 'Tax Dept');

-- --------------------------------------------------------

--
-- Table structure for table `depositcat`
--

CREATE TABLE `depositcat` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `deposit_number` varchar(100) DEFAULT NULL,
  `token` varchar(200) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `category_id` int(5) NOT NULL,
  `account_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `invoice_id` int(5) DEFAULT NULL,
  `created` datetime NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(20,2) NOT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `status` int(5) DEFAULT NULL COMMENT 'Unpaid-0, Paid-1, Internal-2',
  `recurring` int(5) DEFAULT NULL,
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) NOT NULL DEFAULT '0',
  `deposits_created_by` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `show_to_customer` tinyint(1) NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL,
  `staff_id` int(5) NOT NULL DEFAULT '0',
  `contact_id` int(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `discussions`
--

INSERT INTO `discussions` (`id`, `relation_type`, `relation`, `subject`, `description`, `show_to_customer`, `datecreated`, `staff_id`, `contact_id`) VALUES
(1, 'invoice', 7, 'Hi There?', 'Lorem ipsum dolor sit amet', 1, '2018-03-05 00:00:00', 1, 23),
(2, 'invoice', 7, 'Sample', 'Test', 1, '2018-03-07 21:37:02', 1, 23),
(3, 'invoice', 7, 'Test Two', 'Test detail', 1, '2018-03-07 21:40:37', 1, 26),
(4, 'invoice', 7, 'Test 3', 'Test 3', 1, '2018-03-07 21:43:00', 1, 22),
(5, 'invoice', 7, 'Example Discuss', 'test', 0, '2018-03-07 21:43:41', 1, 21),
(6, 'invoice', 7, 'Example Discuss 2', 'test', 1, '2018-03-07 21:43:59', 1, 23),
(7, 'invoice', 12, 'Test', 'Test discussion', 1, '2018-08-30 22:40:50', 1, 23);

-- --------------------------------------------------------

--
-- Table structure for table `discussion_comments`
--

CREATE TABLE `discussion_comments` (
  `id` int(11) NOT NULL,
  `discussion_id` int(5) NOT NULL,
  `created` datetime NOT NULL,
  `content` text NOT NULL,
  `staff_id` int(5) NOT NULL,
  `contact_id` int(5) DEFAULT '0',
  `full_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `discussion_comments`
--

INSERT INTO `discussion_comments` (`id`, `discussion_id`, `created`, `content`, `staff_id`, `contact_id`, `full_name`) VALUES
(1, 1, '2018-03-06 00:46:08', 'Sample', 1, 23, 'Lance Bogrol'),
(2, 1, '2018-03-06 00:46:55', 'Sample for tony', 1, 23, 'Lance Bogrol'),
(3, 1, '2018-03-06 18:19:11', 'Example for tony', 1, 23, 'Lance Bogrol'),
(4, 1, '2018-03-07 19:44:11', 'Contact message', 1, 23, 'Sue Shei'),
(5, 1, '2018-03-07 19:44:24', 'Contact message', 1, 23, 'Sue Shei'),
(6, 1, '2018-03-07 19:45:31', 'Toney contact', 1, 23, 'Sue Shei'),
(7, 1, '2018-03-07 19:57:38', 'Bla bla 8', 1, 23, 'Sue Shei'),
(8, 1, '2018-03-07 20:06:17', 'Thanks sue!', 1, 23, 'Lance Bogrol'),
(9, 1, '2018-03-07 20:07:19', 'thanks again', 1, 23, 'Lance Bogrol'),
(10, 1, '2018-03-07 20:08:26', 'ada', 1, 23, 'Lance Bogrol'),
(11, 1, '2018-03-09 16:08:39', 'adsfsa', 1, 23, 'Lance Bogrol'),
(12, 7, '2018-08-30 22:41:11', 'hi, its so expensive', 1, 23, 'Sue Shei');

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `subject` mediumtext,
  `message` mediumtext,
  `attachments` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `display` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `send_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_queue`
--

INSERT INTO `email_queue` (`id`, `from_name`, `email`, `cc`, `bcc`, `subject`, `message`, `attachments`, `status`, `display`, `created`, `send_date`) VALUES
(1, 'Ciuis CRM', 'emma@example.com', NULL, NULL, 'New Task Assigned to You - Design Frontend', '<p><span>Dear Emma Durst,</span><br><br><span>You have been assigned to a new task:</span><br><br><span><strong>Name:</strong> Design Frontend</span></p><p><strong>Start Date:</strong> 2019-04-15</p><p><span><strong>Due date:</strong> 2019-06-15</span></p><p><span><strong>Priority:</strong> MEDIUM</span></p><p><strong>Status:</strong> IN PROGRESS<span><br><br></span><span>You can view the task on the following link: <a href=\"http://localhost/ciuiscrm/tasks/task/2\" data-mce-href=\"http://localhost/ciuiscrm/tasks/task/2\" style=\"\">view</a></span><br><br><span>Kind Regards,</span></p><p><strong>Lance Bogrol</strong>,<br><span>lance@example.com</span><br></p>', NULL, 1, 1, '2019-04-15 19:20:21', NULL),
(2, 'Ciuis CRM', 'emma@example.com', NULL, NULL, 'New Comment on Task - Design Frontend', '<p>Dear Emma Durst,<br><br>A comment has been made on the following task:<br><br><strong>Task:</strong> Design Frontend<br><strong>Comment:</strong> Pending Server Conffig<br><br>You can view the task on the following link: <a href=\"http://localhost/ciuiscrm/tasks/task/2\" data-mce-href=\"http://localhost/ciuiscrm/tasks/task/2\">view</a><br><br>Kind Regards,</p><p><strong>Lance Bogrol</strong>,<br>lance@example.com<br></p>', NULL, 1, 1, '2019-04-15 19:21:00', NULL),
(3, 'Ciuis CRM', 'ruby@example.com', NULL, NULL, 'New Task Assigned to You - Coding PHP backend', '<p><span>Dear Ruby Von Rails,</span><br><br><span>You have been assigned to a new task:</span><br><br><span><strong>Name:</strong> Coding PHP backend</span></p><p><strong>Start Date:</strong> 2019-04-15</p><p><span><strong>Due date:</strong> 2019-04-30</span></p><p><span><strong>Priority:</strong> MEDIUM</span></p><p><strong>Status:</strong> Waiting<span><br><br></span><span>You can view the task on the following link: <a href=\"http://localhost/ciuiscrm/tasks/task/3\" data-mce-href=\"http://localhost/ciuiscrm/tasks/task/3\" style=\"\">view</a></span><br><br><span>Kind Regards,</span></p><p><strong>Lance Bogrol</strong>,<br><span>lance@example.com</span><br></p>', NULL, 1, 1, '2019-04-15 20:13:26', NULL),
(4, 'Ciuis CRM', 'ruby@example.com', NULL, NULL, 'New Comment on Task - Coding PHP backend', '<p>Dear Ruby Von Rails,<br><br>A comment has been made on the following task:<br><br><strong>Task:</strong> Coding PHP backend<br><strong>Comment:</strong> We can solve this problem by solving the HTML issue<br><br>You can view the task on the following link: <a href=\"http://localhost/ciuiscrm/tasks/task/3\" data-mce-href=\"http://localhost/ciuiscrm/tasks/task/3\">view</a><br><br>Kind Regards,</p><p><strong>Lance Bogrol</strong>,<br>lance@example.com<br></p>', NULL, 1, 1, '2019-04-15 20:14:10', NULL),
(5, 'Ciuis CRM', 'lance@example.com', NULL, NULL, 'New Ticket Created', '<p><span>A new ticket has been created.</span><br><br><span><strong>Subject</strong>: Zclus Site Similar On siteGround</span><br><span><strong>Department</strong>: </span><br><span><strong>Priority</strong>: MEDIUM</span><br><br><span><strong>Ticket message:</strong></span><br><span>Need help with the website ? </span><br><br><span>Kind Regards,</span></p><p><strong>Sue</strong>,<br><span>sue@example.com</span><br></p>', NULL, 1, 1, '2019-04-15 16:49:41', NULL),
(6, 'Ciuis CRM', 'sue@example.com', NULL, NULL, 'New Ticket Opened', '<p><span>Hi Sue,</span><br><br><span>Thank you for contacting our team. A ticket has now been created for your request. </span></p><p><span>You will be notified when a response is made by email.</span><br><br><span><strong>Subject:</strong> Zclus Site Similar On siteGround</span><br><span><strong>Department</strong>: </span><br><span><strong>Priority:</strong> MEDIUM</span><br><br><span><strong>Ticket message:</strong></span><br><span>Need help with the website ? </span><br><br><br><span>Kind Regards,</span></p><p><strong>Sue</strong>,<br><span>sue@example.com</span><br></p>', NULL, 1, 1, '2019-04-15 16:49:41', NULL),
(7, 'Ciuis CRM', 'root@ciuis.com', NULL, NULL, 'New Staff Added (Welcome Email)', '<p>Hi Root Admin,<br><br>You are added as member on our CRM.<br><br>Please use the following logic credentials:<br><br><strong>Email:</strong> root@ciuis.com<br><strong>Password:</strong> root<br><br>Click <span><span><a href=\"{http://localhost/ciuiscrm/login}\" data-mce-href=\"{http://localhost/ciuiscrm/login}\">here </a> </span></span>to login in the dashboard.<br><br>Best Regards,<br><strong>Lance Bogrol</strong>,<br>lance@example.com<br></p>', NULL, 1, 1, '2019-04-16 05:15:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` mediumtext,
  `from_name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `display` tinyint(1) DEFAULT '1',
  `attachment` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `relation`, `name`, `subject`, `message`, `from_name`, `status`, `display`, `attachment`) VALUES
(1, 'invoice', 'invoice_message', 'Invoice with number {invoice_number} created', '<p><span><strong>INVOICE {invoice_number}</strong></span><br><br></p><div>Hello {customer},</div><div><br></div><div>We have prepared the following invoice for you: # <strong>{invoice_number}</strong></div><div><br></div><div>Invoice status: <strong>{invoice_status}</strong></div><div><br></div><div>You can view the invoice on the following link: <strong>{invoice_number}</strong></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 1),
(2, 'invoice', 'invoice_reminder', 'Send Invoice', '<h1><span xss=removed>DOCTYPE</span></h1>\n<p xss=removed>DOCTYPE</p>', 'Ciuis CRM', 1, 0, 0),
(3, 'invoice', 'invoice_payment', 'Invoice Payment Recorded', '<p><span>Hello {customer}<br><br></span>Thank you for the payment. Find the payment details below:<br><br>-------------------------------------------------<br><br>Amount: <strong>{payment_total}<br></strong>Date: <strong>{payment_date}</strong><br>Invoice number: <span><strong># {invoice_number}<br><br></strong></span>-------------------------------------------------<br><br>You can always view the invoice for this payment at the following link: <a href=\"{invoice_link}\" data-mce-href=\"{invoice_link}\">{invoice_number}</a></p><p><a>View Invoice</a><br><br>We are looking forward working with you.<br><br><span>Kind Regards,</span></p><p><strong>{name}</strong><br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 1, 0),
(4, 'invoice', 'invoice_overdue', 'Invoice Overdue Notice - {invoice_number}', '<p><span>Hi {customer},</span><br><br><span>This is an overdue notice for invoice <strong># {invoice_number}</strong></span><br><br><span>This invoice was due: {invoice_duedate}</span><br><br><span>You can view the invoice on the following link: <a>{invoice_number}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong><br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 0, 0),
(6, 'customer', 'new_contact_added', 'Welcome aboard', '<p>Dear {customer},<br><br>Thank you for registering.<br><br>We just wanted to say welcome.<br><br>Please contact us if you need any help.<br><br>Click here to view your profile: <a href=\"{app_url}\" data-mce-href=\"{app_url}\" style=\"\">{app_url}</a></p><p>Your login details:</p><p>Email: <span><strong>{login_email}</strong></span></p><p>Password: <span><strong>{login_password}</strong></span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br><br>(This is an automated email, so please don\'t reply to this email address)</p>', 'Ciuis CRM', 1, 1, 0),
(7, 'customer', 'new_customer', 'New Customer Registration', '<p>Hello Admin.<br><br>New customer registration on your customer portal:<br></p><p><strong>Type</strong>: {customer_type}<br><strong>Name: </strong>{name}<br><strong>Email:</strong> {customer_email}<br><br>Best Regards<br></p>', 'Ciuis CRM', 1, 1, 0),
(8, 'staff', 'new_staff', 'New Staff Added (Welcome Email)', '<p>Hi {staff},<br><br>You are added as member on our CRM.<br><br>Please use the following logic credentials:<br><br><strong>Email:</strong> {staff_email}<br><strong>Password:</strong> {password}<br><br>Click <span><span><a href=\"{login_url}\" data-mce-href=\"{login_url}\">here </a> </span></span>to login in the dashboard.<br><br>Best Regards,<br><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(9, 'staff', 'forgot_password', 'Reset password', '<h2><span>Reset password</span></h2><p>Hi {staffname},</p><p><br>Forgot your password?<br>To create a new password, just follow this link:<br><br><a href=\"{password_url}\" data-mce-href=\"{password_url}\">Reset Password</a><br><br>You received this email, because it was requested by a user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br></p><p>Regards,</p><p>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(10, 'staff', 'password_reset', 'Your password has been changed', '<p><span><strong>You have changed your password.<br></strong></span><br>Please, keep it in your records so you don\'t forget it.<br><br>Your email address for login is: <span style=\"color: rgb(0, 0, 255)\">{staff_email}</span><br><br>If this was not you, please contact us.<br></p><p>Regards,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(11, 'staff', 'reminder_email', 'Send Invoice', 'Im sending Invoice', 'Ciuis CRM', 1, 0, 0),
(12, 'task', 'new_task_assigned', 'New Task Assigned to You - {task_name}', '<p><span>Dear {staffname},</span><br><br><span>You have been assigned to a new task:</span><br><br><span><strong>Name:</strong> {task_name}</span></p><p><strong>Start Date:</strong> {task_startdate}</p><p><span><strong>Due date:</strong> {task_duedate}</span></p><p><span><strong>Priority:</strong> {task_priority}</span></p><p><strong>Status:</strong> {task_status}<span><br><br></span><span>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\" style=\"\">view</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(13, 'task', 'task_comments', 'New Comment on Task - {task_name}', '<p>Dear {staffname},<br><br>A comment has been made on the following task:<br><br><strong>Task:</strong> {task_name}<br><strong>Comment:</strong> {task_comment}<br><br>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">view</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(14, 'task', 'task_attachment', 'New Attachment on Task - {task_name}', '<p>Hi {staffname},<br><br><strong>{logged_in_user}</strong> added an attachment on the following task:<br><br><strong>Name:</strong> {task_name}<br><br>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">view</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(15, 'task', 'task_updated', 'Task Status Changed', '<p><span>Hi {staffname},</span><br><br><span><strong>{</strong></span><span><b>logged_in_user}</b> marked task as <strong>{task_status}</strong></span><br><br><span><strong>Name:</strong> {task_name}</span><br><span><strong>Due date:</strong> {task_duedate}</span><br><br><span>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">{task_name}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(19, 'ticket', 'new_ticket', 'New Ticket Opened', '<p><span>Hi {customer},</span><br><br><span>New ticket has been opened.</span><br><br><span><strong>Subject:</strong> {ticket_subject}</span><br><span><strong>Department:</strong> {ticket_department}</span><br><span><strong>Priority:</strong> {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><span><a><br></a>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(20, 'ticket', 'new_customer_ticket', 'New Ticket Created', '<p><span>A new ticket has been created.</span><br><br><span><strong>Subject</strong>: {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority</strong>: {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(21, 'ticket', 'ticket_assigned', 'New ticket has been assigned to you', '<p><span>Hi {assigned},</span></p><p><span>A new support ticket&ampampnbsphas been assigned to you.</span></p><p><br><strong>Subject:</strong> {ticket_subject}<br><strong>Department:</strong> {ticket_department}<br><strong>Priority:</strong> {ticket_priority}<br></p><p><strong>Customer:</strong> {customer}</p><p><br><strong>Ticket message:</strong><br>{ticket_message}<br><br><a><br></a>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(22, 'ticket', 'ticket_reply_to_staff', 'Ticket Reply', '<p><span>A new support ticket reply from {customer}</span><br><br><span><strong>Subject</strong>: {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority</strong>: {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(23, 'ticket', 'ticket_autoresponse', 'New Ticket Opened', '<p><span>Hi {customer},</span><br><br><span>Thank you for contacting our team. A ticket has now been created for your request. </span></p><p><span>You will be notified when a response is made by email.</span><br><br><span><strong>Subject:</strong> {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority:</strong> {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(24, 'ticket', 'ticket_reply_to_customer', 'New Ticket Reply', '<p><span>Hi {customer},</span><br><br><span>You have a new ticket reply to ticket.</span><br><br></p><p><strong>Subject: </strong>{ticket_subject}<br><strong></strong><br><strong>Ticket message:</strong><br>{ticket_message}<br><br><a><br></a>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(25, 'proposal', 'send_proposal', 'Proposal With Number {proposal_number} Created', '<p>Dear {proposal_to},<br><br>Please find our attached proposal.<br><br>This proposal is valid until: {open_till}</p><p><br><strong>Proposal Subject:</strong> {subject}</p><p><strong>Proposal Details:</strong></p><p>{details}</p><p><br>Please don\'t hesitate to comment online if you have any questions.<br><br>We look forward to your communication.<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 1),
(26, 'proposal', 'thankyou_email', 'Thank for you accepting the proposal', '<p>Dear {proposal_to},<br><br>Thank for for accepting the proposal.<br><br>We look forward to doing business with you.<br><br>We will contact you as soon as possible<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(27, 'proposal', 'customer_accepted_proposal', 'Customer Accepted Proposal', '<div>Hi,<br><br>Client <strong>{proposal_to}</strong> accepted the following proposal:<br><br><strong>Number:</strong> {proposal_number}<br><strong>Subject</strong>: {subject}<br><strong>Total</strong>: {proposal_total}<br><br>Kind Regards,</div><div><strong>{name}</strong>,<br>{email_signature}</div>', 'Ciuis CRM', 1, 1, 0),
(28, 'proposal', 'customer_rejected_proposal', 'Client Declined Proposal', '<div>Hi,<br><br>Client <strong>{proposal_to}</strong> declined the proposal <strong>{subject}</strong><br><br><strong>Proposal Number:</strong> {proposal_number}<br><strong>Total</strong>: {proposal_total}<br><br>Kind Regards,</div><div><strong>{name}</strong>,<br>{email_signature}</div><div> </div><div> </div><p> <br></p><div> </div>', 'Ciuis CRM', 1, 1, 0),
(29, 'lead', 'lead_assigned', 'New lead assigned to you', '<p>Hello {lead_assigned_staff},<br><br>New lead is assigned to you.<br><br>Lead Name: {lead_name}<br>Lead Email: {lead_email}<br><br>You can view the lead on the following link: <a href=\"{lead_url}\" data-mce-href=\"{lead_url}\">View</a><br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(34, 'project', 'project_notification', 'New project created', '<p>Hello {customer},</p><p>New project is assigned to your company.<br><br><strong>Project Name:</strong> {project_name}<br><strong>Project Start Date:</strong> {project_start_date}</p><p>You can view the project on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\" style=\"\">{project_name}</a></p><p>\n\n\n</p><p>We are looking forward hearing from you.<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(35, 'project', 'staff_added', 'New project assigned to you', '<p>Hi {staff},<br><br>New project has been assigned to you.<br><br>You can view the project on the following link <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a><br></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(36, 'project', 'new_file_uploaded_to_members', 'New Project File Uploaded - {project_name}', '<p>Hello {staff},</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can view the project on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(37, 'project', 'new_file_uploaded_to_customer', 'New Project File Uploaded - {project_name}', '<p>Hello {customer},</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can view the project on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(38, 'project', 'new_note_to_members', 'New Note on Project - {project_name}', '<p>Hello {staff},<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>You can view the note on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(39, 'project', 'new_note_to_customers', 'New Note on Project - {project_name}', '<p><span>Hello {customer},</span><br><br><span>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong></span><br><br><span><strong>Note</strong>: </span></p><p><span>{note}</span><br><br><span>You can view the note on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 1, 0),
(40, 'project', 'project_status_changed', 'Project Status Changed', '<p><span>Hi {customer},</span><br><br><span><strong>{loggedin_staff}</strong> marked project as <strong>{project_status}</strong></span><br><br></p><p><strong>Project Name: </strong>{project_name}<br><strong>Project End Date:</strong> {project_end_date}</p><p>You can view the project on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(42, 'expense', 'expense_created', 'Expense Created - {expense_number}', '<p><strong></strong>Hello {customer},<br><br>We have prepared the following expense for you: # <strong>{expense_number}</strong><br></p><p><strong>Expense Title:</strong></p><p><span>{expense_title}</span></p><p><strong>Expense Category:</strong></p><p><span>{expense_category}</span></p><p><strong>Expense Date: </strong>{expense_date}</p><p><strong>Expense Description:</strong></p><p><span>{expense_description}</span></p><p><br></p><p><strong>Expense Amount: </strong>{expense_amount}<br><br>Please contact us for more information.<br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 1),
(43, 'staff', 'customer_forgot_password', 'Reset password', '<h2><span>Reset password</span></h2><p>Hi {customer},</p><p><br>Forgot your password?<br>To create a new password, just follow this link:<br><br><a href=\"{password_url}\" data-mce-href=\"{password_url}\">Reset Password</a><br><br>You received this email, because it was requested by a user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br></p><p>Regards,</p><p>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(44, 'staff', 'customer_password_reset', 'Your password has been changed', '<p><span><strong>You have changed your password.<br></strong></span><br>Please, keep it in your records so you don\'t forget it.<br><br>Your email address for login is: <span style=\"color: rgb(0, 0, 255)\">{email}</span><br><br>If this was not you, please contact us.<br></p><p>Regards,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(45, 'invoice', 'invoice_recurring', 'Recurring Invoice with number {invoice_number} created', '<p><span><strong>INVOICE {invoice_number}</strong></span><br><br></p><div>Hello {customer},</div><div><br></div><div>We have prepared the following invoice for you: # <strong>{invoice_number}</strong></div><div><br></div><div>Invoice status: <strong>{invoice_status}</strong></div><div><br></div><div>You can view the invoice on the following link: <a href=\"{invoice_link}\" data-mce-href=\"{invoice_link}\">{invoice_number}</a></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 1),
(46, 'lead', 'web_lead_created', 'New Online Web lead created', '<p>Hello {lead_assigned_staff},<br><br>New lead is assigned to you.<br><br>Lead Name: {lead_name}<br>Lead Email: {lead_email}<br><br>You can view the lead on the following link: <a href=\"{lead_url}\" data-mce-href=\"{lead_url}\">View</a><br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}<br></p>', 'Ciuis CRM', 0, 1, 0),
(47, 'lead', 'lead_submitted', 'New lead has been created for you', '<p>Hello {lead_name},<br><br>New lead has been created for you.<br><br>Lead Email: {lead_email}<br><br><br>Kind Regards,<br><strong>{email_signature}</strong><br></p>', 'Ciuis CRM', 0, 1, 0),
(48, 'expense', 'expense_recurring', 'Recurring Expense with number {expense_number} created', '<p>Hello {customer},</p><p><br>We have prepared the following expense for you: # <strong>{expense_number}</strong><br></p><p><strong>Expense Title:</strong></p><p>{expense_title}</p><p><strong>Expense Category:</strong></p><p>{expense_category}</p><p><strong>Expense Date: </strong>{expense_date}</p><p><strong>Expense Amount: </strong>{expense_amount}<br><br>Please contact us for more information.<br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}</p>', 'Ciuis CRM', 0, 1, 1),
(50, 'project', 'new_note_to_members_by_customer', 'New Note on Project by customer - {project_name}', '<p>Hello,<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(51, 'project', 'new_file_uploaded_by_customer', 'New Project File Uploaded By Customer - {project_name}', '<p>Hello,</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can find the attachment below attached.</p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(52, 'order', 'order_message', 'Order {order_number} confirmed', '<p>Hello {customer},<br></p><div><br></div><div><br></div><div>Thank you very much for your recent order with {company_name}. Your order is currently being processed and you will receive a shipping confirmation once the order has been shipped.</div><div><br></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 1),
(53, 'project', 'new_note_to_members_by_customer', 'New Note on Project by customer - {project_name}', '<p>Hello,<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(54, 'project', 'new_file_uploaded_by_customer', 'New Project File Uploaded By Customer - {project_name}', '<p>Hello,</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can find the attachment below attached.</p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(55, 'quote', 'request_quote', 'New Quote requested by {customer_name}', '<p>Dear {staff},<br><br>{customer_name} has requested a new quote.<br><br><br><strong>Quote Subject:</strong> {subject}</p><p><strong>Quote </strong><strong>Details:</strong></p><p>{details}</p><p><strong>Quote </strong><strong>Link: </strong><a href=\"{quote_link}\" data-mce-href=\"{quote_link}\">{quote_link}</a></p><p><br><br>Kind Regards,</p><p><strong>{company_name}</strong>,<br>{company_email}<br></p>', 'Ciuis CRM', 1, 1, 0),
(56, 'quote', 'quote_status_changed', 'Quote Status has Changed by {staff}', '<div><p>Hi {customer_name},<br><br><strong>{staff}</strong> marked quote as <strong>{quote_status}</strong><br><br></p><p><strong>Quote Subject: </strong>{subject}<br><strong>Quote Details:</strong></p><p>{details}</p><p><br>Kind Regards,</p><p><strong>{company_name}</strong>,<br>{company_email}</p></div><div><br></div>', 'Ciuis CRM', 1, 1, 0),
(57, 'events', 'event_reminder', 'Event Reminder', '<p>Hello {staff},<br></p><p>This is the {event_title} reminder.</p><p><br data-mce-bogus=\"1\"></p><p>Regards,</p><p><strong>{company_name}</strong></p><p>{company_email}</p>', 'Ciuis CRM', 1, 1, 0),
(58, 'purchase', 'purchase_message', 'purchase with number {purchase_number} created', '<p><span><strong>PURCHASE {purchase_number}</strong></span><br><br></p><div>Hello {vendor_name},</div><div><br></div><div>We have prepared the following purchase order for you: # <strong>{purchase_number}</strong></div><div><br></div><div>Purchase status: <strong>{purchase_status}</strong></div><div><br></div><div>You can view the purchase order on the following link: <a href=\"{purchase_link}\" data-mce-href=\"{purchase_link}\">{purchase_number}</a></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1),
(59, 'purchase', 'purchase_payment', 'Purchase Payment Recorded', '<p><span>Hello {vendor_name}<br><br></span>Thank you for the payment. Find the payment details below:<br><br>-------------------------------------------------<br><br>Amount: <strong>{payment_amount}<br></strong>Date: <strong>{payment_date}</strong><br>Purchase number: <span><strong># {purchase_number}<br><br></strong></span>-------------------------------------------------<br><br>You can always view the purchase for this payment at the following link:{purchase_link}</p><p><a>View Purchase</a><br><br>We are looking forward working with you.<br><br><span>Kind Regards,</span></p><p><strong>{company_name}</strong><br><span>{company_email}</span></p>', 'Ciuis CRM', 1, 1, 0),
(60, 'purchase', 'purchase_recurring', 'Recurring Purchase with number {purchase_number} created', '<p><span><strong>PURCHASE {purchase_number}</strong></span><br><br></p><div>Hello {vendor_name},</div><div><br></div><div>We have prepared the following purchase for you: # <strong>{purchase_number}</strong></div><div><br></div><div>Purchase status: <strong>{purchase_status}</strong></div><div><br></div><div>You can view the purchase on the following link: {purchase_link}</div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1),
(61, 'deposit', 'deposit_message', 'Deposit with number {deposit_number} created', '<p><span><strong>DEPOSIT {deposit_number}</strong></span><br><br></p><div>Hello {customer_name},</div><div><br></div><div>We have prepared the following deposit for you: # <strong>{deposit_number}</strong></div><div><br></div><div>Deposit status: <strong>{deposit_status}</strong></div><div><br></div><div>You can view the deposit on the following link: <strong>{deposit_link}</strong></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1),
(62, 'deposit', 'recurring_deposit', 'Recurring Deposit with number {deposit_number} created', '<p><strong>DEPOSIT {deposit_number}</strong><br><br></p><div>Hello {customer_name},</div><div><br></div><div>We have prepared the following deposit for you: # <strong>{deposit_number}</strong></div><div><br></div><div>Deposit status: <strong>{deposit_status}</strong></div><div><br></div><div>You can view the deposit on the following link: {deposit_link}</div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_template_fields`
--

CREATE TABLE `email_template_fields` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  `template_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_template_fields`
--

INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES
(1, 1, 'company_email', 'company_email', 'invoice_message'),
(2, 1, 'invoice_number', 'invoice_number', 'invoice_message'),
(3, 1, 'invoice_status', 'invoice_status', 'invoice_message'),
(4, 1, 'company_name', 'company_name', 'invoice_message'),
(5, 1, 'invoice_link', 'invoice_link', 'invoice_message'),
(6, 3, 'payment_total', 'payment_total', 'invoice_payment'),
(7, 3, 'payment_date', 'payment_date', 'invoice_payment'),
(8, 3, 'invoice_number', 'invoice_number', 'invoice_payment'),
(9, 3, 'email_signature', 'email_signature', 'invoice_payment'),
(10, 3, 'customer', 'customer', 'invoice_payment'),
(11, 3, 'invoice_link', 'invoice_link', 'invoice_payment'),
(12, 4, 'invoice_number', 'invoice_number', 'invoice_overdue'),
(13, 4, 'invoice_duedate', 'invoice_duedate', 'invoice_overdue'),
(14, 4, 'name', 'name', 'invoice_overdue'),
(15, 4, 'email_signature', 'email_signature', 'invoice_overdue'),
(16, 4, 'customer', 'customer', 'invoice_overdue'),
(17, 6, 'companyname', 'companyname', 'new_contact_added'),
(18, 6, 'app_url', 'app_url', 'new_contact_added'),
(19, 6, 'customer', 'customer', 'new_contact_added'),
(20, 6, 'name', 'name', 'new_contact_added'),
(21, 6, 'email_signature', 'email_signature', 'new_contact_added'),
(22, 7, 'customer_type', 'customer_type', 'new_customer'),
(23, 7, 'name', 'name', 'new_customer'),
(24, 7, 'customer_email', 'customer_email', 'new_customer'),
(25, 19, 'customer', 'customer', 'new_ticket'),
(26, 19, 'ticket_subject', 'ticket_subject', 'new_ticket'),
(27, 19, 'ticket_department', 'ticket_department', 'new_ticket'),
(28, 19, 'ticket_priority', 'ticket_priority', 'new_ticket'),
(29, 19, 'ticket_message', 'ticket_message', 'new_ticket'),
(30, 19, 'name', 'name', 'new_ticket'),
(31, 19, 'email_signature', 'email_signature', 'new_ticket'),
(32, 21, 'assigned', 'assigned', ''),
(33, 21, 'ticket_subject', 'ticket_subject', 'ticket_assigned'),
(34, 21, 'ticket_department', 'ticket_department', 'ticket_assigned'),
(35, 21, 'ticket_priority', 'ticket_priority', 'ticket_assigned'),
(36, 21, 'ticket_message', 'ticket_message', 'ticket_assigned'),
(37, 21, 'name', 'name', 'ticket_assigned'),
(38, 21, 'email_signature', 'email_signature', 'ticket_assigned'),
(39, 24, 'customer', 'customer', 'ticket_reply_to_customer'),
(40, 24, 'ticket_subject', 'ticket_subject', 'ticket_reply_to_customer'),
(41, 24, 'ticket_message', 'ticket_message', 'ticket_reply_to_customer'),
(42, 24, 'name', 'name', 'ticket_reply_to_customer'),
(43, 24, 'email_signature', 'email_signature', 'ticket_reply_to_customer'),
(44, 25, 'proposal_to', 'proposal_to', 'send_proposal'),
(45, 25, 'open_till', 'open_till', 'send_proposal'),
(46, 25, 'open_till', 'open_till', 'send_proposal'),
(47, 25, 'company_name', 'company_name', 'send_proposal'),
(48, 25, 'company_email', 'company_email', 'send_proposal'),
(49, 25, 'subject', 'subject', 'send_proposal'),
(50, 25, 'details', 'details', 'send_proposal'),
(51, 26, 'proposal_to', 'proposal_to', 'thankyou_email'),
(52, 26, 'name', 'name', 'thankyou_email'),
(53, 26, 'email_signature', 'email_signature', 'thankyou_email'),
(54, 26, 'subject', 'subject', 'thankyou_email'),
(55, 26, 'details', 'details', 'thankyou_email'),
(56, 26, 'proposal_number', 'proposal_number', 'thankyou_email'),
(57, 27, 'proposal_to', 'proposal_to', 'customer_accepted_proposal'),
(58, 27, 'subject', 'subject', 'customer_accepted_proposal'),
(59, 27, 'proposal_number', 'proposal_number', 'customer_accepted_proposal'),
(60, 27, 'proposal_total', 'proposal_total', 'customer_accepted_proposal'),
(61, 27, 'name', 'name', 'customer_accepted_proposal'),
(62, 27, 'email_signature', 'email_signature', 'customer_accepted_proposal'),
(63, 27, 'details', 'details', 'customer_accepted_proposal'),
(64, 28, 'proposal_to', 'proposal_to', 'customer_rejected_proposal'),
(65, 28, 'proposal_number', 'proposal_number', 'customer_rejected_proposal'),
(66, 28, 'subject', 'subject', 'customer_rejected_proposal'),
(67, 28, 'proposal_total', 'proposal_total', 'customer_rejected_proposal'),
(68, 28, 'name', 'name', 'customer_rejected_proposal'),
(69, 28, 'email_signature', 'email_signature', 'customer_rejected_proposal'),
(70, 28, 'details', 'details', 'customer_rejected_proposal'),
(71, 8, 'staff', 'staff', 'new_staff'),
(72, 8, 'staff_email', 'staff_email', 'new_staff'),
(73, 8, 'password', 'password', 'new_staff'),
(74, 8, 'name', 'name', 'new_staff'),
(75, 8, 'email_signature', 'email_signature', 'new_staff'),
(76, 8, 'login_url', 'login_url', 'new_staff'),
(77, 9, 'staffname', 'staffname', 'forgot_password'),
(78, 9, 'password_url', 'password_url', 'forgot_password'),
(80, 9, 'email_signature', 'email_signature', 'forgot_password'),
(81, 10, 'staff_email', 'staff_email', 'password_reset'),
(82, 10, 'email_signature', 'email_signature', 'password_reset'),
(83, 10, 'staffname', 'staffname', 'password_reset'),
(84, 29, 'lead_assigned_staff', 'lead_assigned_staff', 'lead_assigned'),
(85, 29, 'lead_name', 'lead_name', 'lead_assigned'),
(86, 29, 'lead_email', 'lead_email', 'lead_assigned'),
(87, 29, 'lead_url', 'lead_url', 'lead_assigned'),
(88, 29, 'name', 'name', 'lead_assigned'),
(89, 29, 'email_signature', 'email_signature', 'lead_assigned'),
(90, 12, 'staffname', 'staffname', 'new_task_assigned'),
(91, 12, 'task_name', 'task_name', 'new_task_assigned'),
(92, 12, 'task_startdate', 'task_startdate', 'new_task_assigned'),
(93, 12, 'task_duedate', 'task_duedate', 'new_task_assigned'),
(94, 12, 'task_priority', 'task_priority', 'new_task_assigned'),
(95, 12, 'task_url', 'task_url', 'new_task_assigned'),
(96, 12, 'name', 'name', 'new_task_assigned'),
(97, 12, 'email_signature', 'email_signature', 'new_task_assigned'),
(98, 13, 'staffname', 'staffname', 'task_comments'),
(99, 13, 'task_name', 'task_name', 'task_comments'),
(100, 13, 'task_startdate', 'task_startdate', 'task_comments'),
(101, 13, 'task_duedate', 'task_duedate', 'task_comments'),
(102, 13, 'task_priority', 'task_priority', 'task_comments'),
(103, 13, 'task_status', 'task_status', 'task_comments'),
(104, 13, 'task_url', 'task_url', 'task_comments'),
(105, 13, 'task_comment', 'task_comment', 'task_comments'),
(106, 13, 'name', 'name', 'task_comments'),
(107, 13, 'email_signature', 'email_signature', 'task_comments'),
(108, 14, 'staffname', 'staffname', 'task_attachment'),
(109, 14, 'task_name', 'task_name', 'task_attachment'),
(110, 14, 'task_startdate', 'task_startdate', 'task_attachment'),
(111, 14, 'task_duedate', 'task_duedate', 'task_attachment'),
(112, 14, 'task_priority', 'task_priority', 'task_attachment'),
(113, 14, 'task_url', 'task_url', 'task_attachment'),
(114, 14, 'name', 'name', 'task_attachment'),
(115, 14, 'email_signature', 'email_signature', 'task_attachment'),
(116, 14, 'task_status', 'task_status', 'task_attachment'),
(117, 13, 'task_status', 'task_status', 'task_comments'),
(118, 12, 'task_status', 'task_status', 'new_task_assigned'),
(119, 14, 'logged_in_user', 'logged_in_user', 'task_attachment'),
(120, 15, 'staffname', 'staffname', 'task_updated'),
(121, 15, 'task_name', 'task_name', 'task_updated'),
(122, 15, 'task_startdate', 'task_startdate', 'task_updated'),
(123, 15, 'task_duedate', 'task_duedate', 'task_updated'),
(124, 15, 'task_priority', 'task_priority', 'task_updated'),
(125, 15, 'task_url', 'task_url', 'task_updated'),
(126, 15, 'name', 'name', 'task_updated'),
(127, 15, 'task_status', 'task_status', 'task_updated'),
(128, 15, 'logged_in_user', 'logged_in_user', 'task_updated'),
(129, 15, 'email_signature', 'email_signature', 'task_updated'),
(130, 34, 'customer', 'customer', 'project_notification'),
(131, 34, 'project_name', 'project_name', 'project_notification'),
(132, 34, 'project_start_date', 'project_start_date', 'project_notification'),
(133, 34, 'project_value', 'project_value', 'project_notification'),
(134, 34, 'project_url', 'project_url', 'project_notification'),
(135, 34, 'name', 'name', 'project_notification'),
(136, 34, 'email_signature', 'email_signature', 'project_notification'),
(137, 34, 'project_status', 'project_status', 'project_notification'),
(138, 34, 'project_tax', 'project_tax', 'project_notification'),
(139, 34, 'project_end_date', 'project_end_date', 'project_notification'),
(140, 40, 'customer', 'customer', 'project_status_changed'),
(141, 40, 'project_name', 'project_name', 'project_status_changed'),
(142, 40, 'project_start_date', 'project_start_date', 'project_status_changed'),
(143, 40, 'project_end_date', 'project_end_date', 'project_status_changed'),
(144, 40, 'project_value', 'project_value', 'project_status_changed'),
(145, 40, 'project_tax', 'project_tax', 'project_status_changed'),
(146, 40, 'project_url', 'project_url', 'project_status_changed'),
(147, 40, 'name', 'name', 'project_status_changed'),
(148, 40, 'email_signature', 'email_signature', 'project_status_changed'),
(149, 40, 'loggedin_staff', 'loggedin_staff', 'project_status_changed'),
(150, 35, 'customer', 'customer', 'staff_added'),
(151, 35, 'staff', 'staff', 'staff_added'),
(152, 35, 'project_name', 'project_name', 'staff_added'),
(153, 35, 'project_start_date', 'project_start_date', 'staff_added'),
(154, 35, 'project_end_date', 'project_end_date', 'staff_added'),
(155, 35, 'project_value', 'project_value', 'staff_added'),
(156, 35, 'project_tax', 'project_tax', 'staff_added'),
(157, 35, 'project_url', 'project_url', 'staff_added'),
(158, 35, 'name', 'name', 'staff_added'),
(159, 35, 'email_signature', 'email_signature', 'staff_added'),
(160, 35, 'loggedin_staff', 'loggedin_staff', 'staff_added'),
(161, 36, 'customer', 'customer', 'new_file_uploaded_to_members'),
(162, 36, 'staff', 'staff', 'new_file_uploaded_to_members'),
(163, 36, 'project_name', 'project_name', 'new_file_uploaded_to_members'),
(164, 36, 'project_start_date', 'project_start_date', 'new_file_uploaded_to_members'),
(165, 36, 'project_end_date', 'project_end_date', 'new_file_uploaded_to_members'),
(166, 36, 'project_value', 'project_value', 'new_file_uploaded_to_members'),
(167, 36, 'project_tax', 'project_tax', 'new_file_uploaded_to_members'),
(168, 36, 'project_url', 'project_url', 'new_file_uploaded_to_members'),
(169, 36, 'name', 'name', 'new_file_uploaded_to_members'),
(170, 36, 'email_signature', 'email_signature', 'new_file_uploaded_to_members'),
(171, 36, 'loggedin_staff', 'loggedin_staff', 'new_file_uploaded_to_members'),
(172, 37, 'customer', 'customer', 'new_file_uploaded_to_customer'),
(173, 37, 'project_name', 'project_name', 'new_file_uploaded_to_customer'),
(174, 37, 'project_start_date', 'project_start_date', 'new_file_uploaded_to_customer'),
(175, 37, 'project_end_date', 'project_end_date', 'new_file_uploaded_to_customer'),
(176, 37, 'project_value', 'project_value', 'new_file_uploaded_to_customer'),
(177, 37, 'project_tax', 'project_tax', 'new_file_uploaded_to_customer'),
(178, 37, 'project_url', 'project_url', 'new_file_uploaded_to_customer'),
(179, 37, 'name', 'name', 'new_file_uploaded_to_customer'),
(180, 37, 'email_signature', 'email_signature', 'new_file_uploaded_to_customer'),
(181, 37, 'loggedin_staff', 'loggedin_staff', 'new_file_uploaded_to_customer'),
(182, 38, 'customer', 'customer', 'new_note_to_members'),
(183, 38, 'note', 'note', 'new_note_to_members'),
(184, 38, 'project_name', 'project_name', 'new_note_to_members'),
(185, 38, 'project_start_date', 'project_start_date', 'new_note_to_members'),
(186, 38, 'project_end_date', 'project_end_date', 'new_note_to_members'),
(187, 38, 'project_value', 'project_value', 'new_note_to_members'),
(188, 38, 'project_tax', 'project_tax', 'new_note_to_members'),
(189, 38, 'project_url', 'project_url', 'new_note_to_members'),
(190, 38, 'name', 'name', 'new_note_to_members'),
(191, 38, 'email_signature', 'email_signature', 'new_note_to_members'),
(192, 38, 'loggedin_staff', 'loggedin_staff', 'new_note_to_members'),
(193, 39, 'customer', 'customer', 'new_note_to_customers'),
(194, 39, 'note', 'note', 'new_note_to_customers'),
(195, 39, 'project_name', 'project_name', 'new_note_to_customers'),
(196, 39, 'project_start_date', 'project_start_date', 'new_note_to_customers'),
(197, 39, 'project_end_date', 'project_end_date', 'new_note_to_customers'),
(198, 39, 'project_value', 'project_value', 'new_note_to_customers'),
(199, 39, 'project_tax', 'project_tax', 'new_note_to_customers'),
(200, 39, 'project_url', 'project_url', 'new_note_to_customers'),
(201, 39, 'name', 'name', 'new_note_to_customers'),
(202, 39, 'email_signature', 'email_signature', 'new_note_to_customers'),
(203, 39, 'loggedin_staff', 'loggedin_staff', 'new_note_to_customers'),
(204, 42, 'customer', 'customer', 'expense_created'),
(205, 42, 'expense_number', 'expense_number', 'expense_created'),
(206, 42, 'expense_title', 'expense_title', 'expense_created'),
(207, 42, 'expense_category', 'expense_category', 'expense_created'),
(208, 42, 'expense_date', 'expense_date', 'expense_created'),
(210, 42, 'expense_amount', 'expense_amount', 'expense_created'),
(211, 42, 'company_name', 'company_name', 'expense_created'),
(212, 42, 'company_email', 'company_email', 'expense_created'),
(213, 20, 'ticket_subject', 'ticket_subject', 'new_customer_ticket'),
(214, 20, 'ticket_department', 'ticket_department', 'new_customer_ticket'),
(215, 20, 'ticket_priority', 'ticket_priority', 'new_customer_ticket'),
(216, 20, 'ticket_message', 'ticket_message', 'new_customer_ticket'),
(217, 20, 'name', 'name', 'new_customer_ticket'),
(218, 20, 'email_signature', 'email_signature', 'new_customer_ticket'),
(219, 22, 'ticket_subject', 'ticket_subject', 'ticket_reply_to_staff'),
(220, 22, 'ticket_department', 'ticket_department', 'ticket_reply_to_staff'),
(221, 22, 'ticket_priority', 'ticket_priority', 'ticket_reply_to_staff'),
(222, 22, 'ticket_message', 'ticket_message', 'ticket_reply_to_staff'),
(223, 22, 'name', 'name', 'ticket_reply_to_staff'),
(224, 22, 'email_signature', 'email_signature', 'ticket_reply_to_staff'),
(225, 23, 'customer', 'customer', 'ticket_autoresponse'),
(226, 23, 'ticket_subject', 'ticket_subject', 'ticket_autoresponse'),
(227, 23, 'ticket_department', 'ticket_department', 'ticket_autoresponse'),
(228, 23, 'ticket_priority', 'ticket_priority', 'ticket_autoresponse'),
(229, 23, 'ticket_message', 'ticket_message', 'ticket_autoresponse'),
(230, 23, 'name', 'name', 'ticket_autoresponse'),
(231, 23, 'email_signature', 'email_signature', 'ticket_autoresponse'),
(232, 43, 'customer', 'customer', 'customer_forgot_password'),
(234, 43, 'email_signature', 'email_signature', 'customer_forgot_password'),
(235, 44, 'email', 'email', 'customer_password_reset'),
(237, 44, 'email_signature', 'email_signature', 'customer_password_reset'),
(241, 45, 'invoice_number', 'invoice_number', 'invoice_recurring'),
(242, 45, 'company_name', 'company_name', 'invoice_recurring'),
(243, 45, 'customer', 'customer', 'invoice_recurring'),
(244, 45, 'invoice_link', 'invoice_link', 'invoice_recurring'),
(245, 45, 'invoice_status', 'invoice_status', 'invoice_recurring'),
(246, 45, 'company_email', 'company_email', 'invoice_recurring'),
(247, 1, 'customer', 'customer', 'invoice_message'),
(248, 46, 'lead_assigned_staff', 'lead_assigned_staff', 'web_lead_created'),
(249, 46, 'lead_name', 'lead_name', 'web_lead_created'),
(250, 46, 'lead_email', 'lead_email', 'web_lead_created'),
(251, 46, 'lead_url', 'lead_url', 'web_lead_created'),
(252, 46, 'email_signature', 'email_signature', 'web_lead_created'),
(253, 46, 'name', 'name', 'web_lead_created'),
(254, 25, 'proposal_link', 'proposal_link', 'send_proposal'),
(255, 48, 'expense_title', 'expense_title', 'expense_recurring'),
(256, 48, 'company_email', 'company_email', 'expense_recurring'),
(257, 48, 'expense_number', 'expense_number', 'expense_recurring'),
(258, 48, 'customer', 'customer', 'expense_recurring'),
(259, 48, 'company_name', 'company_name', 'expense_recurring'),
(260, 48, 'expense_amount', 'expense_amount', 'expense_recurring'),
(261, 48, 'expense_category', 'expense_category', 'expense_recurring'),
(262, 48, 'expense_date', 'expense_date', 'expense_recurring'),
(263, 49, 'expense_title', 'expense_title', 'expense_consultant'),
(264, 49, 'email_signature', 'email_signature', 'expense_consultant'),
(265, 49, 'expense_number', 'expense_number', 'expense_consultant'),
(266, 49, 'customer', 'customer', 'expense_consultant'),
(267, 49, 'name', 'name', 'expense_consultant'),
(268, 49, 'expense_amount', 'expense_amount', 'expense_consultant'),
(269, 49, 'expense_category', 'expense_category', 'expense_consultant'),
(270, 49, 'expense_date', 'expense_date', 'expense_consultant'),
(271, 50, 'note', 'note', 'new_note_to_members_by_customer'),
(272, 50, 'project_name', 'project_name', 'new_note_to_members_by_customer'),
(273, 50, 'project_start_date', 'project_start_date', 'new_note_to_members_by_customer'),
(274, 50, 'project_end_date', 'project_end_date', 'new_note_to_members_by_customer'),
(275, 50, 'project_value', 'project_value', 'new_note_to_members_by_customer'),
(276, 50, 'project_tax', 'project_tax', 'new_note_to_members_by_customer'),
(277, 50, 'project_url', 'project_url', 'new_note_to_members_by_customer'),
(278, 50, 'name', 'name', 'new_note_to_members_by_customer'),
(279, 50, 'email_signature', 'email_signature', 'new_note_to_members_by_customer'),
(280, 50, 'loggedin_staff', 'loggedin_staff', 'new_note_to_members_by_customer'),
(281, 51, 'project_name', 'project_name', 'new_file_uploaded_by_customer'),
(282, 51, 'project_start_date', 'project_start_date', 'new_file_uploaded_by_customer'),
(283, 51, 'project_end_date', 'project_end_date', 'new_file_uploaded_by_customer'),
(284, 51, 'project_value', 'project_value', 'new_file_uploaded_by_customer'),
(285, 51, 'project_tax', 'project_tax', 'new_file_uploaded_by_customer'),
(286, 51, 'project_url', 'project_url', 'new_file_uploaded_by_customer'),
(287, 51, 'name', 'name', 'new_file_uploaded_by_customer'),
(288, 51, 'email_signature', 'email_signature', 'new_file_uploaded_by_customer'),
(289, 51, 'loggedin_staff', 'loggedin_staff', 'new_file_uploaded_by_customer'),
(290, 34, 'project_description', 'project_description', 'project_notification'),
(291, 52, 'customer', 'customer', 'order_message'),
(292, 52, 'order_to', 'order_to', 'order_message'),
(293, 52, 'company_email', 'company_email', 'order_message'),
(294, 52, 'company_name', 'company_name', 'order_message'),
(295, 52, 'order_number', 'order_number', 'order_message'),
(296, 52, 'app_name', 'app_name', 'order_message'),
(297, 52, 'company_name', 'company_name', 'order_message'),
(298, 53, 'staff', 'staff', 'request_quote'),
(299, 53, 'customer_name', 'customer_name', 'request_quote'),
(300, 53, 'subject', 'subject', 'request_quote'),
(301, 53, 'details', 'details', 'request_quote'),
(302, 53, 'quote_link', 'quote_link', 'request_quote'),
(303, 53, 'company_name', 'company_name', 'request_quote'),
(304, 53, 'company_email', 'company_email', 'request_quote'),
(305, 54, 'quote_status', 'quote_status', 'quote_status_changed'),
(306, 54, 'staff', 'staff', 'quote_status_changed'),
(307, 54, 'customer_name', 'customer_name', 'quote_status_changed'),
(308, 54, 'subject', 'subject', 'quote_status_changed'),
(309, 54, 'details', 'details', 'quote_status_changed'),
(310, 54, 'quote_link', 'quote_link', 'quote_status_changed'),
(311, 54, 'company_name', 'company_name', 'quote_status_changed'),
(312, 54, 'company_email', 'company_email', 'quote_status_changed'),
(313, 12, 'company_name', 'company_name', 'new_task_assigned'),
(314, 12, 'company_email', 'company_email', 'new_task_assigned'),
(315, 12, 'task_description', 'task_description', 'new_task_assigned'),
(316, 14, 'company_name', 'company_name', 'task_attachment'),
(317, 14, 'company_email', 'company_email', 'task_attachment'),
(318, 55, 'company_name', 'company_name', 'event_reminder'),
(319, 55, 'company_email', 'company_email', 'event_reminder'),
(320, 55, 'staff', 'staff', 'event_reminder'),
(321, 55, 'event_title', 'event_title', 'event_reminder'),
(322, 55, 'event_type', 'event_type', 'event_reminder'),
(323, 55, 'event_details', 'event_details', 'event_reminder'),
(324, 55, 'staff_email', 'staff_email', 'event_reminder'),
(325, 58, 'purchase_number', 'purchase_number', 'purchase_message'),
(326, 58, 'vendor_name', 'vendor_name', 'purchase_message'),
(327, 58, 'issuance_date', 'issuance_date', 'purchase_message'),
(328, 58, 'due_date', 'due_date', 'purchase_message'),
(329, 58, 'due_note', 'due_note', 'purchase_message'),
(330, 58, 'purchase_status', 'purchase_status', 'purchase_message'),
(331, 58, 'total_amount', 'total_amount', 'purchase_message'),
(332, 58, 'company_name', 'company_name', 'purchase_message'),
(333, 58, 'company_email', 'company_email', 'purchase_message'),
(334, 58, 'purchsae_link', 'purchsae_link', 'purchase_message'),
(335, 59, 'purchase_number', 'purchase_number', 'purchase_payment'),
(336, 59, 'vendor_name', 'vendor_name', 'purchase_payment'),
(337, 59, 'issuance_date', 'issuance_date', 'purchase_payment'),
(338, 59, 'due_date', 'due_date', 'purchase_payment'),
(339, 59, 'payment_date', 'payment_date', 'purchase_payment'),
(340, 59, 'payment_account', 'payment_account', 'purchase_payment'),
(341, 59, 'payment_amount', 'payment_amount', 'purchase_payment'),
(342, 59, 'payment_description', 'payment_description', 'purchase_payment'),
(343, 59, 'payment_made_by', 'payment_made_by', 'purchase_payment'),
(344, 59, 'purchase_status', 'purchase_status', 'purchase_payment'),
(345, 59, 'purchase_link', 'purchase_link', 'purchase_payment'),
(346, 59, 'company_name', 'company_name', 'purchase_payment'),
(347, 59, 'company_email', 'company_email', 'purchase_payment'),
(348, 59, 'total_amount', 'total_amount', 'purchase_payment'),
(349, 60, 'purchase_number', 'purchase_number', 'purchase_recurring'),
(350, 60, 'vendor_name', 'vendor_name', 'purchase_recurring'),
(351, 60, 'issuance_date', 'issuance_date', 'purchase_recurring'),
(352, 60, 'due_date', 'due_date', 'purchase_recurring'),
(353, 60, 'purchase_status', 'purchase_status', 'purchase_recurring'),
(354, 60, 'purchase_link', 'purchase_link', 'purchase_recurring'),
(355, 60, 'total_amount', 'total_amount', 'purchase_recurring'),
(356, 60, 'due_note', 'due_note', 'purchase_recurring'),
(357, 60, 'company_name', 'company_name', 'purchase_recurring'),
(358, 60, 'company_email', 'company_email', 'purchase_recurring'),
(359, 61, 'deposit_number', 'deposit_number', 'deposit_message'),
(360, 61, 'customer_name', 'customer_name', 'deposit_message'),
(361, 61, 'deposit_date', 'deposit_date', 'deposit_message'),
(362, 61, 'deposit_amount', 'deposit_amount', 'deposit_message'),
(363, 61, 'deposit_status', 'deposit_status', 'deposit_message'),
(364, 61, 'deposit_link', 'deposit_link', 'deposit_message'),
(365, 61, 'company_name', 'company_name', 'deposit_message'),
(366, 61, 'company_email', 'company_email', 'deposit_message'),
(367, 62, 'deposit_number', 'deposit_number', 'recurring_deposit'),
(368, 62, 'customer_name', 'customer_name', 'recurring_deposit'),
(369, 62, 'deposit_date', 'deposit_date', 'recurring_deposit'),
(370, 62, 'deposit_amount', 'deposit_amount', 'recurring_deposit'),
(371, 62, 'deposit_status', 'deposit_status', 'recurring_deposit'),
(372, 62, 'deposit_link', 'deposit_link', 'recurring_deposit'),
(373, 62, 'company_name', 'company_name', 'recurring_deposit'),
(374, 62, 'company_email', 'company_email', 'recurring_deposit');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `added_by` int(5) DEFAULT NULL,
  `staffname` varchar(255) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `public` varchar(10) DEFAULT '0',
  `reminder` tinyint(1) DEFAULT NULL,
  `event_type` int(5) DEFAULT NULL,
  `is_all` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `detail`, `staff_id`, `added_by`, `staffname`, `start`, `end`, `color`, `public`, `reminder`, `event_type`, `is_all`, `created`) VALUES
(1, 'Lorem Ipsum', 'Today is good.', 1, NULL, 'Lance Bogrol', '2018-02-01 00:05:22', '2018-01-30 20:05:28', NULL, 'true', NULL, NULL, 0, NULL),
(2, 'Sample Event', 'Today is good.', 1, NULL, 'Lance Bogrol', '2018-02-02 00:05:22', '2018-02-02 20:05:28', NULL, 'true', NULL, NULL, 0, NULL),
(3, 'Go Client Meet', 'Today is good.', 1, NULL, 'Lance Bogrol', '2018-02-03 00:05:22', '2018-02-03 20:05:28', NULL, 'true', NULL, NULL, 0, NULL),
(4, 'Web Design', 'Today is good.', 1, NULL, 'Lance Bogrol', '2018-02-04 00:05:22', '2018-02-04 20:05:28', NULL, 'true', NULL, NULL, 0, NULL),
(5, 'Hola!', 'Today is good.', 1, NULL, 'Lance Bogrol', '2018-02-06 00:05:22', '2018-02-06 20:05:28', NULL, 'true', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `event_triggers`
--

CREATE TABLE `event_triggers` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `duration_type` int(11) DEFAULT NULL COMMENT '0 = minutes | 1 = hours | 2 = days | 3 = weeks',
  `duration_period` varchar(100) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `public` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expensecat`
--

CREATE TABLE `expensecat` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `expensecat`
--

INSERT INTO `expensecat` (`id`, `name`, `description`) VALUES
(1, 'Office Expenses', 'Office Expenses'),
(2, 'Other Expenses', 'Other Expenses'),
(3, 'Trivia', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_number` varchar(100) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `hash` varchar(6) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT '0',
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `category_id` int(5) DEFAULT NULL,
  `account_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `invoice_id` int(5) DEFAULT NULL,
  `purchase_id` int(5) DEFAULT NULL,
  `created` datetime NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(20,2) NOT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `internal` tinyint(1) NOT NULL DEFAULT '0',
  `recurring` int(5) DEFAULT NULL,
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0',
  `expense_created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_number`, `number`, `hash`, `relation_type`, `relation`, `title`, `description`, `category_id`, `account_id`, `staff_id`, `customer_id`, `invoice_id`, `purchase_id`, `created`, `date`, `amount`, `sub_total`, `total_discount`, `total_tax`, `internal`, `recurring`, `last_recurring`, `pdf_status`, `expense_created_by`) VALUES
(1, NULL, NULL, '', 'project', 1, 'Sample Expense', 'Lorem ipsum sit dolor amet.', 3, 1, 1, 17, NULL, NULL, '2018-01-05 21:22:56', '2018-01-05', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(3, NULL, NULL, '', NULL, 0, 'Food Expenses', 'Other Expenses', 1, 3, 1, 0, NULL, NULL, '2018-01-06 04:26:16', '2018-01-06', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(4, NULL, NULL, '', 'project', 1, 'Sample', 'Sample', 3, 1, 1, 17, NULL, NULL, '2018-01-25 19:30:44', '2018-01-25', '10.00', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(5, NULL, NULL, '33fa04', NULL, 0, 'Sample', 'Lorem ipsum dolor sit amet', 2, 1, 1, 0, NULL, NULL, '2018-04-11 22:07:27', '2018-04-11', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(7, NULL, NULL, '9bdb1f', NULL, 0, 'Test', 'Test', 1, 3, 1, 16, NULL, NULL, '2018-04-11 22:32:14', '2018-04-11', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(8, NULL, NULL, '', NULL, 0, 'Test', 'test', 3, 1, 1, 18, 15, NULL, '2018-05-20 17:29:39', '2018-05-20', '20.00', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(9, NULL, NULL, '', NULL, 0, 'Test', 'Test', 2, 4, 1, 0, NULL, NULL, '2018-06-01 19:29:37', '2018-06-01', '10.00', NULL, NULL, NULL, 0, NULL, NULL, 0, NULL),
(10, NULL, '2', '', NULL, 0, 'Telephone charges', NULL, 1, 1, 1, 0, NULL, NULL, '2018-11-13 19:18:34', '2018-11-13', '210.00', '200.00', NULL, '10.00', 1, NULL, '2018-11-13', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `is_old` tinyint(1) DEFAULT '1',
  `filetype` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `relation_type`, `relation`, `file_name`, `is_old`, `filetype`, `created`) VALUES
(2, 'project', 11, 'Document.pdf', 1, '.doc', '2017-11-04 00:00:00'),
(6, 'project', 11, 'Chaim.doc', 1, '.doc', '2017-11-04 00:00:00'),
(8, 'project', 9, 'Sample_File2.png', 1, NULL, '2017-11-16 01:48:03'),
(9, 'task', 25, 'Sample_File3.png', 1, NULL, '2017-11-23 21:28:35'),
(10, 'task', 25, 'ticket.png', 1, NULL, '2017-11-23 21:29:10'),
(11, 'task', 27, 'Sample_File4.png', 1, NULL, '2017-11-23 23:16:21'),
(12, 'task', 30, 'Sample_File5.png', 1, NULL, '2017-11-23 23:43:52'),
(16, 'task', 2, '1_9PnPjPI65fGwLiMfluVLrw.jpg', 0, NULL, '2019-04-15 19:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `invoiceId` varchar(100) DEFAULT NULL,
  `token` mediumtext NOT NULL,
  `no` varchar(50) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `customer_id` int(5) NOT NULL,
  `expense_id` int(5) DEFAULT NULL,
  `proposal_id` int(5) DEFAULT NULL,
  `project_id` int(5) DEFAULT NULL,
  `staff_id` int(5) DEFAULT NULL,
  `datesend` datetime DEFAULT NULL,
  `datepayment` date DEFAULT NULL,
  `duenote` varchar(255) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `amount_paying` varchar(50) DEFAULT NULL,
  `default_payment_method` varchar(50) DEFAULT NULL,
  `CustomField` varchar(255) NOT NULL,
  `billing_street` varchar(100) NOT NULL,
  `billing_city` varchar(100) NOT NULL,
  `billing_state` varchar(100) NOT NULL,
  `billing_state_id` int(5) DEFAULT NULL,
  `billing_zip` varchar(50) NOT NULL,
  `billing_country` int(5) NOT NULL,
  `shipping_street` varchar(100) NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_state` varchar(100) NOT NULL,
  `shipping_state_id` int(5) DEFAULT NULL,
  `shipping_zip` varchar(100) NOT NULL,
  `shipping_country` int(5) NOT NULL,
  `recurring` int(2) NOT NULL DEFAULT '0',
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `invoiceId`, `token`, `no`, `serie`, `created`, `duedate`, `customer_id`, `expense_id`, `proposal_id`, `project_id`, `staff_id`, `datesend`, `datepayment`, `duenote`, `status_id`, `sub_total`, `total_discount`, `total_tax`, `total`, `amount_paying`, `default_payment_method`, `CustomField`, `billing_street`, `billing_city`, `billing_state`, `billing_state_id`, `billing_zip`, `billing_country`, `shipping_street`, `shipping_city`, `shipping_state`, `shipping_state_id`, `shipping_zip`, `shipping_country`, `recurring`, `last_recurring`, `pdf_status`) VALUES
(1, NULL, NULL, '9289e7e9faf3b97032dc96e367e69b23', '6', 'A', '2018-01-01', '0000-00-00', 17, NULL, NULL, NULL, 1, NULL, NULL, 'Please pay on time.', 2, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(5, NULL, NULL, '2d78f01034c59352131086ab2995cd59', '2', 'A', '2018-01-05', NULL, 16, NULL, NULL, NULL, 1, NULL, NULL, NULL, 2, '400.00', '0.00', '10.00', '410.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(6, NULL, NULL, '431f5592a096e81c18de0be1b34af5bc', '23', 'A', '2018-01-06', NULL, 12, NULL, NULL, NULL, 1, '2018-01-21 16:21:09', '2018-01-06', NULL, 2, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(7, NULL, NULL, '7f2088df0ff31cb17341e29826e83eb6', '12', 'A', '2018-01-22', '2018-12-12', 17, NULL, NULL, NULL, 1, '2018-01-25 05:51:06', NULL, 'Sample', 3, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(8, NULL, NULL, 'c8031bdc83af8bf95f6e455ef12c042c', '102', 'a', '2018-02-12', '2018-04-11', 6, NULL, NULL, NULL, 1, NULL, NULL, 'Please pay on time.', 4, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(9, NULL, NULL, 'd9d400ebda1d350da0bff978ac606959', '2332', 'A', '2018-02-11', NULL, 1, NULL, NULL, NULL, 1, NULL, '2018-02-13', NULL, 2, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(10, NULL, NULL, '0dbffee1accb51b774c6274a1ba74c90', '102', 'a', '2018-02-13', '2019-02-06', 6, NULL, NULL, NULL, 2, '2018-03-18 04:42:34', NULL, 'Please pay on time.', 3, '200.00', '0.00', '0.00', '200.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 13, NULL, 0),
(11, NULL, NULL, 'e4304c0de5cdc010f2b63d90def71d9b', '12', '12', '2018-03-09', '0000-00-00', 17, NULL, NULL, NULL, 1, NULL, NULL, 'Please pay on time.', 2, '100.00', '0.00', '0.00', '100.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(12, NULL, NULL, '5436b282ebbd7e40ba6655bbd7d0e62d', '12', 'a', '2018-03-25', '0000-00-00', 17, NULL, NULL, NULL, 1, '2018-04-08 16:59:58', NULL, 'adf', 2, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(13, NULL, NULL, '1e0aee98391a3d89525d12f09b28e19c', '23', 'A', '2018-04-22', '2018-10-30', 18, NULL, NULL, NULL, 1, NULL, NULL, 'Pay on time.', 3, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', 'Example Address', 'Los Angeles', 'California', NULL, '2233', 236, 'Example Address', 'Los Angeles', 'California', NULL, '2233', 1, 0, NULL, 0),
(14, NULL, NULL, '', NULL, NULL, '2018-05-20', NULL, 18, 8, NULL, NULL, 1, NULL, NULL, NULL, 3, '20.00', NULL, NULL, '20.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(15, NULL, NULL, '', NULL, NULL, '2018-05-20', NULL, 18, 8, NULL, NULL, 1, NULL, NULL, NULL, 3, '20.00', NULL, NULL, '20.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(16, NULL, NULL, '5af6a98d566d216a16d9a26e8559cbb6', '0', 'A', '2018-06-20', '2018-06-27', 12, NULL, 5, NULL, 1, NULL, '0000-00-00', 'TEST', 3, '200.00', '0.00', '10.00', '210.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(17, NULL, NULL, 'b32c25e711a8c18f8cb7da09842a3cb8', '1', '1', '2018-08-11', '2019-08-20', 16, NULL, NULL, NULL, 1, NULL, NULL, 'Pay on time', 3, '100.00', '0.00', '0.00', '100.00', NULL, NULL, '', '', '', '', NULL, '', 0, '', '', '', NULL, '', 0, 0, NULL, 0),
(18, NULL, NULL, 'b12880099b999862fcc47d67b90e19bf', '7', '01', '2018-09-19', NULL, 18, NULL, NULL, NULL, 1, NULL, '2018-09-19', NULL, 2, '1000.00', '0.00', '0.00', '1000.00', NULL, NULL, '', 'Example Address', 'Los Angeles', 'California', NULL, '2233', 236, 'Example Address', 'Los Angeles', 'California', NULL, '2233', 236, 0, NULL, 0),
(19, NULL, NULL, '85dd8412b3f8d532f3c6da6793d1f5a5', '12', 'Test', '2018-11-12', '2019-12-24', 18, NULL, NULL, NULL, 1, NULL, NULL, 'pay on time', 3, '100.00', '0.00', '0.00', '100.00', NULL, NULL, '', 'Example Address', 'Los Angeles', 'California', NULL, '2233', 236, 'Example Address', 'Los Angeles', 'California', NULL, '2233', 236, 0, NULL, 0),
(22, NULL, NULL, '68605ed0278edc00434f461ad4ce04f3', '0', '', '2018-11-12', '2018-11-12', 0, NULL, NULL, NULL, 1, NULL, NULL, '', 3, '0.00', '0.00', '0.00', '0.00', NULL, NULL, '', '------', ',---- ', ',----', NULL, '----', 0, '------', ',---- ', ',----', NULL, '----', 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `invoicestatus`
--

CREATE TABLE `invoicestatus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoicestatus`
--

INSERT INTO `invoicestatus` (`id`, `name`, `color`) VALUES
(1, 'Draft', '#7d7d7d'),
(2, 'Paid', '#26c281'),
(3, 'Unpaid', '#ff3b30'),
(4, 'Cancelled', '#dd2c00');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT NULL,
  `product_id` int(5) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `quantity` decimal(20,2) DEFAULT NULL,
  `unit` varchar(200) DEFAULT NULL,
  `price` decimal(20,2) DEFAULT NULL,
  `tax` decimal(20,2) DEFAULT '0.00',
  `discount` decimal(20,2) DEFAULT '0.00',
  `total` decimal(20,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `relation_type`, `relation`, `product_id`, `code`, `name`, `description`, `quantity`, `unit`, `price`, `tax`, `discount`, `total`) VALUES
(1, 'invoice', 1, 4, 'WEB', 'Consultance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(5, 'invoice', 5, 4, 'WEB', 'Consultance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(8, 'proposal', 2, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(10, 'proposal', 4, 5, 'GRA-24', 'Graphic Design', 'Graphic Design Services', '1.00', 'Unit', '100.00', '0.00', '0.00', '100.00'),
(11, 'proposal', 5, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(12, 'invoice', 6, 3, 'WEB-204', 'Logo Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(13, 'invoice', 7, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(14, 'invoice', 8, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(15, 'invoice', 9, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(16, 'invoice', 10, 2, 'WEB', 'Seo Consultant', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '0.00', '0.00', '200.00'),
(17, 'invoice', 11, 5, 'GRA-24', 'Graphic Design', 'Graphic Design Services', '1.00', 'Unit', '100.00', '0.00', '0.00', '100.00'),
(18, 'invoice', 12, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(19, 'invoice', 13, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(20, 'invoice', 5, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(21, 'invoice', 15, NULL, NULL, 'Test', 'test', '1.00', 'Unit', '20.00', '0.00', '0.00', '20.00'),
(22, 'invoice', 16, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00'),
(23, 'order', 1, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '10.00', '190.00'),
(24, 'invoice', 19, 5, 'GRA-24', 'Graphic Design', 'Graphic Design Services', '1.00', 'Unit', '100.00', '0.00', '0.00', '100.00'),
(27, 'invoice', 22, 0, '', 'New', '', '1.00', 'Unit', '0.00', '0.00', '0.00', '0.00'),
(29, 'expense', 10, 1, 'WEB', 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', '1.00', 'Unit', '200.00', '5.00', '0.00', '210.00');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `langcode` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `foldername` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `langcode`, `name`, `foldername`) VALUES
(1, 'de_De', 'german', 'german'),
(2, 'en_US', 'english', 'english'),
(3, 'es_ES', 'spanish', 'spanish'),
(4, 'fr_FR', 'french', 'french'),
(5, 'pt-pt', 'portuguese_pt', 'portuguese_pt'),
(6, 'pt-BR', 'portuguese_br', 'portuguese_br'),
(7, 'tr_TR', 'turkish', 'turkish'),
(8, 'ru_RU', 'russian', 'russian'),
(9, 'sv_SV', 'swedish', 'swedish'),
(10, 'it-ch', 'italian', 'italian');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `lead_number` varchar(100) DEFAULT NULL,
  `date_contacted` datetime NOT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `description` text,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `zip` varchar(15) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `state_id` int(5) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `assigned_id` int(5) NOT NULL,
  `created` date NOT NULL,
  `status` int(5) NOT NULL,
  `source` int(5) NOT NULL,
  `lastcontact` datetime DEFAULT NULL,
  `dateassigned` date DEFAULT NULL,
  `staff_id` int(5) NOT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `lost` tinyint(1) DEFAULT '0',
  `junk` int(5) DEFAULT '0',
  `public` tinyint(1) DEFAULT '0',
  `weblead` int(5) DEFAULT NULL,
  `lead_status_id` int(5) DEFAULT '1' COMMENT '0 = Inactive | 1 = Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `leads`
--

INSERT INTO `leads` (`id`, `lead_number`, `date_contacted`, `type`, `name`, `title`, `company`, `description`, `country_id`, `zip`, `city`, `state`, `state_id`, `email`, `address`, `website`, `phone`, `assigned_id`, `created`, `status`, `source`, `lastcontact`, `dateassigned`, `staff_id`, `dateconverted`, `lost`, `junk`, `public`, `weblead`, `lead_status_id`) VALUES
(1, NULL, '0000-00-00 00:00:00', 0, 'Judy Youngs', 'Lorem Ipsum', 'Northern Star', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 90, '54617', 'New York', 'NC', NULL, 'judyyoung@example.com', '1333 Deerfield Dr, State College, PA, 16803', 'www.example.com', '(140) 211 2494', 2, '2017-11-11', 3, 1, NULL, '2017-11-11', 1, '2017-08-22 04:35:57', 0, 0, 0, NULL, 1),
(2, NULL, '0000-00-00 00:00:00', 0, 'Joyce McCoy', 'Lorem Ipsum', 'Codelam LLC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 23, '54617', 'New York', 'GA', NULL, 'joycemccoy@example.com', '1398 N 80th W, Orem, UT, 84057', 'www.example.com', '(296) 452 9522', 2, '2017-11-11', 1, 2, NULL, '2017-11-11', 1, '2017-08-24 22:13:34', 0, 0, 1, NULL, 1),
(3, NULL, '0000-00-00 00:00:00', 0, 'Jane Carpenter', 'Lorem Ipsum', 'Unadoncare INC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 55, '54617', 'New York', 'CT', NULL, 'jane-85@example.com', '245 Bourbon Acres Rd, Paris, KY, 40361', 'www.example.com', '(432) 156 5172', 3, '2018-01-26', 1, 1, NULL, '2018-01-26', 2, '2017-08-24 17:45:07', NULL, NULL, 0, NULL, 1),
(4, NULL, '0000-00-00 00:00:00', 0, 'William Patel', 'Lorem Ipsum', 'Canelectrics INC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 11, '54617', 'New York', 'VA', NULL, 'abaris@null.net', '2806 Mimi Ave, Chester, VA, 23831', 'www.example.com', '(269) 364 3098', 4, '2018-09-28', 4, 2, NULL, '2018-09-28', 1, '2017-08-31 03:23:26', NULL, NULL, 0, NULL, 1),
(5, NULL, '0000-00-00 00:00:00', 1, 'Danielle Burns', 'Lorem Ipsum', 'Donway INC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 23, '54617', 'New York', 'MI', NULL, 'danielle-92@example.com', '5094 Vidrine Rd, Ville Platte, LA, 22356', 'www.example.com', '(610) 465 2198', 1, '2018-01-26', 2, 1, NULL, '2018-01-26', 2, '2017-11-12 00:17:05', NULL, NULL, 0, NULL, 1),
(6, NULL, '0000-00-00 00:00:00', 0, 'Nicholas Walters', 'Lorem Ipsum', 'Transhex LLC.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 12, '54617', 'New York', 'CT', NULL, 'nicholas-90@example.com', '1911 Crestview Dr, Johnstown, CO, 80534', 'www.example.com', '(954) 630 6210', 3, '2018-01-26', 4, 2, NULL, '2018-01-26', 2, '2017-08-22 04:40:32', NULL, NULL, 0, NULL, 1),
(7, NULL, '0000-00-00 00:00:00', 0, 'Evelyn Bradley', 'Lorem Ipsum', 'Latdoace AG.', 'Proin tempor tortor ac sem sollicitudin, eu ornare lacus rutrum.', 32, '54617', 'New York', 'GA', NULL, 'evelyn-96@example.com', 'Po Box 961, Thibodaux, LA, 703024', 'www.example.com', '(489) 588 6002', 2, '2017-11-01', 1, 1, NULL, '2017-11-01', 1, NULL, 0, 0, 0, NULL, 1),
(11, NULL, '0000-00-00 00:00:00', 1, 'Roger H Barnett', 'Roger H Barnett', 'Roger H Barnett', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 235, '91744', 'Denge', 'Conda', NULL, 'roger@example.com', 'Sample adress will placed here!', 'example.com', '626-931-8754', 3, '2018-11-13', 1, 6, NULL, '2018-11-13', 1, NULL, NULL, NULL, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `leadssources`
--

CREATE TABLE `leadssources` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leadssources`
--

INSERT INTO `leadssources` (`id`, `name`) VALUES
(1, 'WEB'),
(2, 'EMAIL'),
(6, 'TELEPHONE'),
(7, 'SOCIAL MEDIA');

-- --------------------------------------------------------

--
-- Table structure for table `leadsstatus`
--

CREATE TABLE `leadsstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(10) DEFAULT '#28B8DA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leadsstatus`
--

INSERT INTO `leadsstatus` (`id`, `name`, `color`) VALUES
(1, 'NEW', NULL),
(2, 'CONTACTED', '#fff3d1'),
(3, 'INPROGRESS', '#ffdc77'),
(4, 'CONVERTED', '#daf196'),
(5, 'LOST', 'pink');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `detail` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `staff_id` varchar(5) DEFAULT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `project_id` int(5) NOT NULL,
  `vendor_id` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `detail`, `date`, `staff_id`, `customer_id`, `project_id`, `vendor_id`) VALUES
(753, 'Lance Bogrol added new member to project', '2018-06-20 16:57:23', '1', NULL, 1, NULL),
(754, 'Lance Bogrol added new member to project', '2018-06-20 16:57:27', '1', NULL, 1, NULL),
(755, 'Sue Shei added <a href=\"tickets/ticket/3\"> TICKET-3</a>', '2018-06-20 19:23:49', NULL, 23, 0, NULL),
(756, 'Lance Bogrol updated project.', '2018-06-20 19:42:36', '1', NULL, 1, NULL),
(757, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-06-20 19:42:52', '1', NULL, 0, NULL),
(758, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-20 20:24:35', '1', NULL, 0, NULL),
(759, 'Lance Bogrol updated project.', '2018-06-20 20:26:59', '1', NULL, 1, NULL),
(760, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-25 15:58:19', '1', NULL, 0, NULL),
(761, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-25 16:34:07', '1', NULL, 0, NULL),
(762, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-28 14:50:37', '1', NULL, 0, NULL),
(763, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-29 20:36:47', '1', NULL, 0, NULL),
(764, 'Lance Bogrol changed Ruby Von Rails\'s login password.', '2018-06-29 20:37:23', '1', NULL, 0, NULL),
(765, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-29 20:38:32', '1', NULL, 0, NULL),
(766, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-29 20:38:46', '1', NULL, 0, NULL),
(767, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-30 17:03:04', '1', NULL, 0, NULL),
(768, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-06-30 17:45:49', '1', NULL, 0, NULL),
(769, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-02 16:46:02', '1', NULL, 0, NULL),
(770, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-03 12:56:03', '1', NULL, 0, NULL),
(771, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-04 16:42:35', '1', NULL, 0, NULL),
(772, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-06 19:01:38', '1', NULL, 0, NULL),
(773, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-06 19:46:36', '1', NULL, 0, NULL),
(774, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/7\"> Product7</a>', '2018-07-06 19:46:57', '1', NULL, 0, NULL),
(775, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-7', '2018-07-06 19:47:11', '1', NULL, 0, NULL),
(776, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-08 00:40:50', '1', NULL, 0, NULL),
(777, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-11 21:59:15', '1', NULL, 0, NULL),
(778, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-12 17:22:16', '1', NULL, 0, NULL),
(779, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> replied <a href=\"tickets/ticket/2\"> TICKET-2</a>', '2018-07-12 17:22:36', '1', NULL, 0, NULL),
(780, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-13 16:02:14', '1', NULL, 0, NULL),
(781, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:13:32', '1', NULL, 0, NULL),
(782, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-15 13:13:43', '1', NULL, 0, NULL),
(783, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:13:48', '1', NULL, 0, NULL),
(784, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:18:55', '1', NULL, 0, NULL),
(785, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:21:05', '1', NULL, 0, NULL),
(786, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-15 13:21:13', '1', NULL, 0, NULL),
(787, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-15 13:21:18', '1', NULL, 0, NULL),
(788, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-16 04:12:00', '1', NULL, 0, NULL),
(789, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-21 22:24:12', '1', NULL, 0, NULL),
(790, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-25 02:22:13', '1', NULL, 0, NULL),
(791, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-25 04:12:13', '1', NULL, 0, NULL),
(792, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-25 21:25:22', '1', NULL, 0, NULL),
(793, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-07-29 22:41:08', '1', NULL, 0, NULL),
(794, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:08', '1', NULL, 0, NULL),
(795, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:20', '1', NULL, 0, NULL),
(796, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:31', '1', NULL, 0, NULL),
(797, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:39', '1', NULL, 0, NULL),
(798, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:42:51', '1', NULL, 0, NULL),
(799, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:04', '1', NULL, 0, NULL),
(800, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:13', '1', NULL, 0, NULL),
(801, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:25', '1', NULL, 0, NULL),
(802, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:43:46', '1', NULL, 0, NULL),
(803, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:44:49', '1', NULL, 0, NULL),
(804, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:14', '1', NULL, 0, NULL),
(805, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:34', '1', NULL, 0, NULL),
(806, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:46', '1', NULL, 0, NULL),
(807, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:45:57', '1', NULL, 0, NULL),
(808, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:46:07', '1', NULL, 0, NULL),
(809, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:46:15', '1', NULL, 0, NULL),
(810, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:56:23', '1', NULL, 0, NULL),
(811, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:58:54', '1', NULL, 0, NULL),
(812, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:05', '1', NULL, 0, NULL),
(813, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:13', '1', NULL, 0, NULL),
(814, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:21', '1', NULL, 0, NULL),
(815, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:32', '1', NULL, 0, NULL),
(816, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:45', '1', NULL, 0, NULL),
(817, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 22:59:54', '1', NULL, 0, NULL),
(818, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:04', '1', NULL, 0, NULL),
(819, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:12', '1', NULL, 0, NULL),
(820, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:46', '1', NULL, 0, NULL),
(821, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:00:55', '1', NULL, 0, NULL),
(822, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:03', '1', NULL, 0, NULL),
(823, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:17', '1', NULL, 0, NULL),
(824, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:34', '1', NULL, 0, NULL),
(825, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:45', '1', NULL, 0, NULL),
(826, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:01:54', '1', NULL, 0, NULL),
(827, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:12', '1', NULL, 0, NULL),
(828, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:23', '1', NULL, 0, NULL),
(829, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:32', '1', NULL, 0, NULL),
(830, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:40', '1', NULL, 0, NULL),
(831, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:49', '1', NULL, 0, NULL),
(832, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:02:59', '1', NULL, 0, NULL),
(833, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:32', '1', NULL, 0, NULL),
(834, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:46', '1', NULL, 0, NULL),
(835, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:51', '1', NULL, 0, NULL),
(836, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:04:59', '1', NULL, 0, NULL),
(837, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:07', '1', NULL, 0, NULL),
(838, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:15', '1', NULL, 0, NULL),
(839, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:36', '1', NULL, 0, NULL),
(840, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:45', '1', NULL, 0, NULL),
(841, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:05:53', '1', NULL, 0, NULL),
(842, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-07-29 23:06:02', '1', NULL, 0, NULL),
(843, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-02 21:12:45', '1', NULL, 0, NULL),
(844, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-02 21:13:04', '1', NULL, 0, NULL),
(845, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-02 21:13:31', '1', NULL, 0, NULL),
(846, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-11 04:13:29', '1', NULL, 0, NULL),
(847, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/17\">INV-17</a>.', '2018-08-11 06:16:05', '1', 16, 0, NULL),
(848, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/10\">INV-10</a>.', '2018-08-11 06:23:26', '1', 6, 0, NULL),
(849, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-18 22:08:29', '1', NULL, 0, NULL),
(850, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-19 00:44:38', '1', NULL, 0, NULL),
(851, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-19 12:39:40', '1', NULL, 0, NULL),
(852, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-19 15:40:29', '1', NULL, 0, NULL),
(853, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-20 16:06:25', '1', NULL, 0, NULL),
(854, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-22 02:23:08', '1', NULL, 0, NULL),
(855, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-23 22:52:31', '1', NULL, 0, NULL),
(856, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-24 01:42:54', '1', NULL, 0, NULL),
(857, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-24 21:32:46', '1', NULL, 0, NULL),
(858, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-25 22:37:03', '1', NULL, 0, NULL),
(859, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-27 04:33:51', '1', NULL, 0, NULL),
(860, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-27 17:01:47', '1', NULL, 0, NULL),
(861, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 03:05:56', '1', NULL, 0, NULL),
(862, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 22:38:59', '1', NULL, 0, NULL),
(863, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 22:39:31', '1', NULL, 0, NULL),
(864, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-28 22:47:27', '1', NULL, 0, NULL),
(865, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-08-30 22:31:21', '1', NULL, 0, NULL),
(866, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-02 19:02:17', '1', NULL, 0, NULL),
(867, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-03 01:39:48', '1', NULL, 0, NULL),
(868, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-08 18:59:46', '1', NULL, 0, NULL),
(869, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-08 19:00:31', '1', NULL, 0, NULL),
(870, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-08 19:02:12', '1', NULL, 0, NULL),
(871, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-19 03:37:48', '1', NULL, 0, NULL),
(872, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/18\">INV-18</a>.', '2018-09-19 03:47:05', '1', 18, 0, NULL),
(873, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:52:16', '1', NULL, 0, NULL),
(874, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:52:32', '1', NULL, 0, NULL),
(875, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:54:36', '1', NULL, 0, NULL),
(876, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:54:58', '1', NULL, 0, NULL),
(877, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:55:06', '1', NULL, 0, NULL),
(878, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:55:14', '1', NULL, 0, NULL),
(879, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:55:23', '1', NULL, 0, NULL),
(880, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:28', '1', NULL, 0, NULL),
(881, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:38', '1', NULL, 0, NULL),
(882, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:46', '1', NULL, 0, NULL),
(883, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-09-19 03:57:59', '1', NULL, 0, NULL),
(884, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-19 18:01:05', '1', NULL, 0, NULL),
(885, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-19 21:31:03', '1', NULL, 0, NULL),
(886, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-20 01:19:38', '1', NULL, 0, NULL),
(887, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-25 17:38:45', '1', NULL, 0, NULL),
(888, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-28 06:41:16', '1', NULL, 0, NULL),
(889, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-28 11:25:21', '1', NULL, 0, NULL),
(890, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-28 20:35:53', '1', NULL, 0, NULL),
(891, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/7\"> Product7</a>', '2018-09-29 03:47:43', '1', NULL, 0, NULL),
(892, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/8\"> Product8</a>', '2018-09-29 04:00:39', '1', NULL, 0, NULL),
(893, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-29 09:29:02', '1', NULL, 0, NULL),
(894, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-30 16:23:56', '1', NULL, 0, NULL),
(895, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-30 22:18:54', '1', NULL, 0, NULL),
(896, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-09-30 22:49:45', '1', NULL, 0, NULL),
(897, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/9\"> Product9</a>', '2018-09-30 22:59:59', '1', NULL, 0, NULL),
(898, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/10\"> Product10</a>', '2018-09-30 23:13:12', '1', NULL, 0, NULL),
(899, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/11\"> Product11</a>', '2018-09-30 23:13:24', '1', NULL, 0, NULL),
(900, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/12\"> Product12</a>', '2018-09-30 23:13:58', '1', NULL, 0, NULL),
(901, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/13\"> Product13</a>', '2018-09-30 23:14:34', '1', NULL, 0, NULL),
(902, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/14\"> Product14</a>', '2018-09-30 23:15:47', '1', NULL, 0, NULL),
(903, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/15\"> Product15</a>', '2018-09-30 23:17:37', '1', NULL, 0, NULL),
(904, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/16\"> Product16</a>', '2018-09-30 23:19:42', '1', NULL, 0, NULL),
(905, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/17\"> Product17</a>', '2018-09-30 23:20:36', '1', NULL, 0, NULL),
(906, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/18\"> Product18</a>', '2018-09-30 23:21:03', '1', NULL, 0, NULL),
(907, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/19\"> Product19</a>', '2018-09-30 23:23:21', '1', NULL, 0, NULL),
(908, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/20\"> Product20</a>', '2018-09-30 23:25:02', '1', NULL, 0, NULL),
(909, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/21\"> Product21</a>', '2018-09-30 23:27:10', '1', NULL, 0, NULL),
(910, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/22\"> Product22</a>', '2018-10-01 00:10:06', '1', NULL, 0, NULL),
(911, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/23\"> Product23</a>', '2018-10-01 00:12:29', '1', NULL, 0, NULL),
(912, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/24\"> Product24</a>', '2018-10-01 00:26:09', '1', NULL, 0, NULL),
(913, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/25\"> Product25</a>', '2018-10-01 00:27:50', '1', NULL, 0, NULL),
(914, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/26\"> Product26</a>', '2018-10-01 00:28:57', '1', NULL, 0, NULL),
(915, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-26', '2018-10-01 00:33:38', '1', NULL, 0, NULL),
(916, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-25', '2018-10-01 00:33:41', '1', NULL, 0, NULL),
(917, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-24', '2018-10-01 00:33:44', '1', NULL, 0, NULL),
(918, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-23', '2018-10-01 00:33:46', '1', NULL, 0, NULL),
(919, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-22', '2018-10-01 00:33:48', '1', NULL, 0, NULL),
(920, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 04:12:04', '1', NULL, 0, NULL),
(921, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/27\"> Product27</a>', '2018-10-01 04:38:18', '1', NULL, 0, NULL),
(922, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:10:24', '1', NULL, 0, NULL),
(923, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:10:39', '1', NULL, 0, NULL),
(924, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:11:14', '1', NULL, 0, NULL),
(925, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:11:30', '1', NULL, 0, NULL),
(926, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:12:47', '1', NULL, 0, NULL),
(927, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:14:32', '1', NULL, 0, NULL),
(928, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:16:47', '1', NULL, 0, NULL),
(929, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:19:28', '1', NULL, 0, NULL),
(930, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:20:21', '1', NULL, 0, NULL),
(931, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:20:54', '1', NULL, 0, NULL),
(932, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:22:10', '1', NULL, 0, NULL),
(933, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:23:39', '1', NULL, 0, NULL),
(934, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:24:01', '1', NULL, 0, NULL),
(935, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:24:22', '1', NULL, 0, NULL),
(936, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:15', '1', NULL, 0, NULL),
(937, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:29', '1', NULL, 0, NULL),
(938, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:33', '1', NULL, 0, NULL),
(939, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:26:45', '1', NULL, 0, NULL),
(940, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:27:32', '1', NULL, 0, NULL),
(941, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:28:53', '1', NULL, 0, NULL),
(942, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:29:14', '1', NULL, 0, NULL),
(943, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:29:31', '1', NULL, 0, NULL),
(944, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:35:26', '1', NULL, 0, NULL),
(945, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:16', '1', NULL, 0, NULL),
(946, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:28', '1', NULL, 0, NULL),
(947, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:40', '1', NULL, 0, NULL),
(948, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:39:56', '1', NULL, 0, NULL),
(949, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:41:48', '1', NULL, 0, NULL),
(950, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:42:50', '1', NULL, 0, NULL),
(951, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:43:54', '1', NULL, 0, NULL),
(952, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:52:56', '1', NULL, 0, NULL),
(953, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:53:45', '1', NULL, 0, NULL),
(954, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/27\"> Product27</a>', '2018-10-01 05:56:45', '1', NULL, 0, NULL),
(955, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-27', '2018-10-01 05:57:37', '1', NULL, 0, NULL),
(956, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/28\"> Product28</a>', '2018-10-01 05:58:52', '1', NULL, 0, NULL),
(957, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:29:17', '1', NULL, 0, NULL),
(958, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:30:30', '1', NULL, 0, NULL),
(959, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:30:58', '1', NULL, 0, NULL),
(960, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:31:08', '1', NULL, 0, NULL),
(961, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:32:42', '1', NULL, 0, NULL),
(962, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:40:10', '1', NULL, 0, NULL),
(963, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:40:34', '1', NULL, 0, NULL),
(964, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:41:10', '1', NULL, 0, NULL),
(965, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/28\"> Product28</a>', '2018-10-01 06:43:01', '1', NULL, 0, NULL),
(966, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/29\"> Product29</a>', '2018-10-01 06:45:12', '1', NULL, 0, NULL),
(967, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 06:47:10', '1', NULL, 0, NULL),
(968, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:04:19', '1', NULL, 0, NULL),
(969, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:05:27', '1', NULL, 0, NULL),
(970, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:05:52', '1', NULL, 0, NULL),
(971, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:06:45', '1', NULL, 0, NULL),
(972, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:08:04', '1', NULL, 0, NULL),
(973, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:12:52', '1', NULL, 0, NULL),
(974, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:15:59', '1', NULL, 0, NULL),
(975, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/29\"> Product29</a>', '2018-10-01 07:16:12', '1', NULL, 0, NULL),
(976, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:16:37', '1', NULL, 0, NULL),
(977, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:17:13', '1', NULL, 0, NULL),
(978, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:17:33', '1', NULL, 0, NULL),
(979, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:19:54', '1', NULL, 0, NULL),
(980, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/30\"> Product30</a>', '2018-10-01 07:20:35', '1', NULL, 0, NULL),
(981, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-30', '2018-10-01 07:21:57', '1', NULL, 0, NULL),
(982, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-29', '2018-10-01 07:22:00', '1', NULL, 0, NULL),
(983, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-28', '2018-10-01 07:22:03', '1', NULL, 0, NULL),
(984, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:22:26', '1', NULL, 0, NULL),
(985, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:26:26', '1', NULL, 0, NULL),
(986, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:35:38', '1', NULL, 0, NULL),
(987, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:36:08', '1', NULL, 0, NULL),
(988, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:46:22', '1', NULL, 0, NULL),
(989, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:47:51', '1', NULL, 0, NULL),
(990, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-01 07:50:33', '1', NULL, 0, NULL),
(991, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-01 08:46:32', '1', NULL, 0, NULL),
(992, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 08:46:36', '1', NULL, 0, NULL),
(993, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 12:54:55', '1', NULL, 0, NULL),
(994, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-01 20:29:40', '1', NULL, 0, NULL),
(995, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-02 00:11:54', '1', NULL, 0, NULL),
(996, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-02 21:39:36', '1', NULL, 0, NULL),
(997, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 00:16:12', '1', NULL, 0, NULL),
(998, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 00:16:30', '1', NULL, 0, NULL),
(999, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:14:33', '1', NULL, 0, NULL),
(1000, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:14:50', '1', NULL, 0, NULL),
(1001, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:20:22', '1', NULL, 0, NULL),
(1002, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 04:23:40', '1', NULL, 0, NULL),
(1003, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 12:15:25', '1', NULL, 0, NULL),
(1004, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 13:05:51', '1', NULL, 0, NULL),
(1005, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 16:53:10', '1', NULL, 0, NULL),
(1006, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-04 16:53:23', '1', NULL, 0, NULL),
(1007, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/31\"> Product31</a>', '2018-10-04 20:10:32', '1', NULL, 0, NULL),
(1008, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/32\"> Product32</a>', '2018-10-04 20:10:44', '1', NULL, 0, NULL),
(1009, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/33\"> Product33</a>', '2018-10-04 20:12:01', '1', NULL, 0, NULL),
(1010, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-32', '2018-10-04 20:13:30', '1', NULL, 0, NULL),
(1011, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-33', '2018-10-04 20:13:33', '1', NULL, 0, NULL),
(1012, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-31', '2018-10-04 20:13:35', '1', NULL, 0, NULL),
(1013, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/34\"> Product34</a>', '2018-10-04 20:34:13', '1', NULL, 0, NULL),
(1014, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/35\"> Product35</a>', '2018-10-04 20:35:03', '1', NULL, 0, NULL),
(1015, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/36\"> Product36</a>', '2018-10-04 21:51:24', '1', NULL, 0, NULL),
(1016, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-36', '2018-10-04 21:53:36', '1', NULL, 0, NULL),
(1017, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-35', '2018-10-04 21:53:38', '1', NULL, 0, NULL),
(1018, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-34', '2018-10-04 21:53:41', '1', NULL, 0, NULL),
(1019, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/37\"> Product37</a>', '2018-10-04 21:53:54', '1', NULL, 0, NULL),
(1020, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-37', '2018-10-04 21:54:12', '1', NULL, 0, NULL),
(1021, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/38\"> Product38</a>', '2018-10-04 21:54:27', '1', NULL, 0, NULL),
(1022, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/39\"> Product39</a>', '2018-10-04 21:55:29', '1', NULL, 0, NULL),
(1023, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-39', '2018-10-04 21:56:01', '1', NULL, 0, NULL),
(1024, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-38', '2018-10-04 21:56:08', '1', NULL, 0, NULL),
(1025, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/40\"> Product40</a>', '2018-10-04 21:56:19', '1', NULL, 0, NULL),
(1026, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-40', '2018-10-04 21:57:17', '1', NULL, 0, NULL),
(1027, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/41\"> Product41</a>', '2018-10-04 21:57:32', '1', NULL, 0, NULL),
(1028, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-41', '2018-10-04 21:57:40', '1', NULL, 0, NULL),
(1029, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/42\"> Product42</a>', '2018-10-04 21:58:17', '1', NULL, 0, NULL),
(1030, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-42', '2018-10-04 21:58:52', '1', NULL, 0, NULL),
(1031, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/43\"> Product43</a>', '2018-10-04 21:59:09', '1', NULL, 0, NULL),
(1032, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/44\"> Product44</a>', '2018-10-04 22:01:08', '1', NULL, 0, NULL),
(1033, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/45\"> Product45</a>', '2018-10-04 22:02:26', '1', NULL, 0, NULL),
(1034, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/46\"> Product46</a>', '2018-10-04 22:03:31', '1', NULL, 0, NULL),
(1035, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/47\"> Product47</a>', '2018-10-04 22:04:08', '1', NULL, 0, NULL),
(1036, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/48\"> Product48</a>', '2018-10-04 22:04:52', '1', NULL, 0, NULL),
(1037, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-48', '2018-10-04 22:10:07', '1', NULL, 0, NULL),
(1038, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-47', '2018-10-04 22:10:10', '1', NULL, 0, NULL),
(1039, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-46', '2018-10-04 22:10:12', '1', NULL, 0, NULL),
(1040, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-45', '2018-10-04 22:10:15', '1', NULL, 0, NULL),
(1041, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-44', '2018-10-04 22:10:18', '1', NULL, 0, NULL),
(1042, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-43', '2018-10-04 22:10:21', '1', NULL, 0, NULL),
(1043, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/49\"> Product49</a>', '2018-10-04 22:10:49', '1', NULL, 0, NULL),
(1044, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/50\"> Product50</a>', '2018-10-04 22:12:13', '1', NULL, 0, NULL),
(1045, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/51\"> Product51</a>', '2018-10-04 22:13:55', '1', NULL, 0, NULL),
(1046, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/52\"> Product52</a>', '2018-10-04 22:14:13', '1', NULL, 0, NULL),
(1047, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/53\"> Product53</a>', '2018-10-04 22:16:03', '1', NULL, 0, NULL),
(1048, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/54\"> Product54</a>', '2018-10-04 22:16:55', '1', NULL, 0, NULL),
(1049, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-54', '2018-10-04 22:27:13', '1', NULL, 0, NULL),
(1050, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-53', '2018-10-04 22:27:15', '1', NULL, 0, NULL),
(1051, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-52', '2018-10-04 22:27:18', '1', NULL, 0, NULL),
(1052, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-51', '2018-10-04 22:27:21', '1', NULL, 0, NULL),
(1053, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-50', '2018-10-04 22:27:24', '1', NULL, 0, NULL),
(1054, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-49', '2018-10-04 22:27:27', '1', NULL, 0, NULL),
(1055, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-6', '2018-10-04 22:27:29', '1', NULL, 0, NULL),
(1056, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-5', '2018-10-04 22:27:32', '1', NULL, 0, NULL),
(1057, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-3', '2018-10-04 22:27:35', '1', NULL, 0, NULL),
(1058, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-2', '2018-10-04 22:27:38', '1', NULL, 0, NULL),
(1059, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-1', '2018-10-04 22:28:05', '1', NULL, 0, NULL),
(1060, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/7\"> Product7</a>', '2018-10-04 22:30:21', '1', NULL, 0, NULL),
(1061, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-7', '2018-10-04 22:31:00', '1', NULL, 0, NULL),
(1062, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/8\"> Product8</a>', '2018-10-04 22:31:11', '1', NULL, 0, NULL),
(1063, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-8', '2018-10-04 22:33:40', '1', NULL, 0, NULL),
(1064, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a new product <a href=\"products/product/9\"> Product9</a>', '2018-10-04 22:44:41', '1', NULL, 0, NULL),
(1065, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted Product-9', '2018-10-04 22:45:03', '1', NULL, 0, NULL),
(1066, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-04 22:47:09', '1', NULL, 0, NULL),
(1067, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-05 07:08:13', '1', NULL, 0, NULL),
(1068, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-05 07:37:17', '1', NULL, 0, NULL),
(1069, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-05 16:07:30', '1', NULL, 0, NULL),
(1070, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-07 15:52:26', '1', NULL, 0, NULL),
(1071, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-10-07 15:53:38', '1', NULL, 0, NULL),
(1072, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-08 17:48:03', '1', NULL, 0, NULL),
(1073, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-10 18:12:41', '1', NULL, 0, NULL),
(1074, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:45:05', '1', NULL, 0, NULL),
(1075, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:54:33', '1', NULL, 0, NULL),
(1076, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:55:18', '1', NULL, 0, NULL),
(1077, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 10:56:24', '1', NULL, 0, NULL),
(1078, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 11:03:53', '1', NULL, 0, NULL),
(1079, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 11:08:34', '1', NULL, 0, NULL),
(1080, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"orders/order/1\">-1</a>.', '2018-10-14 14:52:18', '1', NULL, 0, NULL),
(1081, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"orders/order/2\">-2</a>.', '2018-10-14 14:52:28', '1', NULL, 0, NULL),
(1082, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"orders/order/3\">-3</a>.', '2018-10-14 14:55:26', '1', NULL, 0, NULL),
(1083, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted PRO-3', '2018-10-14 15:25:33', '1', NULL, 0, NULL),
(1084, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted PRO-1', '2018-10-14 15:25:45', '1', NULL, 0, NULL),
(1085, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted -3', '2018-10-14 15:26:58', '1', NULL, 0, NULL),
(1086, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted -2', '2018-10-14 15:27:06', '1', NULL, 0, NULL),
(1087, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 21:21:01', '1', NULL, 0, NULL),
(1088, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-14 21:23:12', '1', NULL, 0, NULL),
(1089, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-15 00:27:07', '1', NULL, 0, NULL),
(1090, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-15 01:40:21', '1', NULL, 0, NULL),
(1091, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-16 12:55:29', '1', NULL, 0, NULL),
(1092, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-25 23:32:41', '1', NULL, 0, NULL),
(1093, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-25 23:33:02', '1', NULL, 0, NULL),
(1094, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-25 23:35:07', '1', NULL, 0, NULL),
(1095, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:27:53', '1', NULL, 0, NULL),
(1096, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-26 11:57:37', '1', NULL, 0, NULL),
(1097, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2018-10-26 11:57:53', '1', NULL, 0, NULL),
(1098, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:58:24', '1', NULL, 0, NULL),
(1099, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:59:15', '1', NULL, 0, NULL),
(1100, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 11:59:51', '1', NULL, 0, NULL),
(1101, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-26 12:00:25', '1', NULL, 0, NULL),
(1102, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-10-27 16:10:59', '1', NULL, 0, NULL),
(1103, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-07 12:50:39', '1', NULL, 0, NULL),
(1104, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-12 02:24:38', '1', NULL, 0, NULL),
(1105, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"products/product/6\"> Product6</a>', '2018-11-12 02:25:50', '1', NULL, 0, NULL),
(1106, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/19\">INV-19</a>.', '2018-11-12 02:27:01', '1', 18, 0, NULL),
(1107, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/20\">INV-20</a>.', '2018-11-12 02:27:58', '1', 0, 0, NULL),
(1108, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-20', '2018-11-12 02:28:07', '1', NULL, 0, NULL),
(1109, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/21\">INV-21</a>.', '2018-11-12 02:28:21', '1', 0, 0, NULL),
(1110, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-21', '2018-11-12 02:28:48', '1', NULL, 0, NULL),
(1111, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/22\">INV-22</a>.', '2018-11-12 02:29:01', '1', 0, 0, NULL),
(1112, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"invoices/invoice/23\">INV-23</a>.', '2018-11-12 02:31:24', '1', 18, 0, NULL),
(1113, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted INV-23', '2018-11-12 02:32:19', '1', NULL, 0, NULL),
(1114, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"invoices/invoice/19\">INV-19</a>.', '2018-11-12 02:37:27', '1', 18, 0, NULL),
(1115, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-12 15:53:24', '1', NULL, 0, NULL),
(1116, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2018-11-13 19:05:56', '1', NULL, 0, NULL),
(1117, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added <a href=\"proposals/proposal/6\">PRO-6</a>.', '2018-11-13 19:06:36', '1', NULL, 0, NULL),
(1118, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"proposals/proposal/6\">PRO-6</a>.', '2018-11-13 19:17:43', '1', 0, 0, NULL),
(1119, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"proposals/proposal/6\">PRO-6</a>.', '2018-11-13 19:17:58', '1', 0, 0, NULL),
(1120, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a expense. <a href=\"expenses/receipt/10\">EXP-10</a>.', '2018-11-13 19:18:34', '1', 0, 0, NULL),
(1121, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"expenses/receipt/10\">EXP-10</a>.', '2018-11-13 19:24:52', '1', 0, 0, NULL),
(1122, 'Lance Bogrol updated project.', '2018-11-13 19:46:19', '1', NULL, 1, NULL);
INSERT INTO `logs` (`id`, `detail`, `date`, `staff_id`, `customer_id`, `project_id`, `vendor_id`) VALUES
(1123, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2018-11-13 19:48:43', '1', NULL, 0, NULL),
(1124, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-08 18:30:45', '1', NULL, 0, NULL),
(1125, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-15 11:52:28', '1', NULL, 0, NULL),
(1126, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-15 16:22:40', '1', NULL, 0, NULL),
(1127, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-15 19:10:51', '1', NULL, 0, NULL),
(1128, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> deleted PRO-6', '2019-04-15 19:17:03', '1', NULL, 0, NULL),
(1129, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/1\">Task-1</a>.', '2019-04-15 19:18:20', '1', NULL, 0, NULL),
(1130, 'Lance Bogrol added new task', '2019-04-15 19:20:21', '1', NULL, 1, NULL),
(1131, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"tasks/task/2\">Task-2</a>.', '2019-04-15 19:20:41', '1', NULL, 0, NULL),
(1132, 'Lance Bogrol added new task', '2019-04-15 20:13:26', '1', NULL, 1, NULL),
(1133, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated <a href=\"expenses/receipt/10\">EXP-10</a>.', '2019-04-15 20:22:46', '1', 0, 0, NULL),
(1134, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> added a online lead form <a href=\"leads/form/1\">Form</a>.', '2019-04-15 20:29:48', '1', NULL, 0, NULL),
(1135, 'Lance Bogrol created new project', '2019-04-15 20:43:48', '1', 0, 2, NULL),
(1136, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2019-04-15 20:45:43', '1', NULL, 0, NULL),
(1137, '<a href=\"staff/staffmember/\"> Lance Bogrol</a> deleted Customer-4', '2019-04-15 14:03:10', '1', NULL, 0, NULL),
(1138, '<a href=\"staff/staffmember/\"> Lance Bogrol</a> deleted Customer-3', '2019-04-15 14:04:48', '1', NULL, 0, NULL),
(1139, 'Lance Bogrol added New Milestone', '2019-04-15 16:20:52', '1', NULL, 2, NULL),
(1140, 'Lance Bogrol added New Milestone', '2019-04-15 16:21:20', '1', NULL, 2, NULL),
(1141, 'Lance Bogrol added New Milestone', '2019-04-15 16:21:59', '1', NULL, 2, NULL),
(1142, 'Sue Shei added <a href=\"tickets/ticket/4\"> TICKET-4</a>', '2019-04-15 16:49:41', NULL, 23, 0, NULL),
(1143, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-15 16:51:19', '1', NULL, 0, NULL),
(1144, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-16 02:03:57', '1', NULL, 0, NULL),
(1145, '<a href=\"staff/staffmember/3\"> Guy Mann</a> logged in the system', '2019-04-16 02:09:59', '3', NULL, 0, NULL),
(1146, '<a href=\"staff/staffmember/3\"> Guy Mann</a> logged in the system', '2019-04-16 02:35:14', '3', NULL, 0, NULL),
(1147, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-16 02:35:47', '1', NULL, 0, NULL),
(1148, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-16 05:08:33', '1', NULL, 0, NULL),
(1149, '', '2019-04-16 05:15:38', '1', NULL, 0, NULL),
(1150, '<a href=\"staff/staffmember/5\"> Root Admin</a> logged in the system', '2019-04-16 05:17:20', '5', NULL, 0, NULL),
(1151, '<a href=\"staff/staffmember/3\"> Guy Mann</a> logged in the system', '2019-04-18 03:29:35', '3', NULL, 0, NULL),
(1152, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> logged in the system', '2019-04-18 03:29:56', '1', NULL, 0, NULL),
(1153, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2019-04-18 03:32:17', '1', NULL, 0, NULL),
(1154, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2019-04-18 03:32:22', '1', NULL, 0, NULL),
(1155, '<a href=\"staff/staffmember/1\"> Lance Bogrol</a> updated settings', '2019-04-18 03:32:27', '1', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `customer_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `title`, `description`, `customer_id`, `staff_id`, `date`, `start`, `end`) VALUES
(1, 'Toyota Dallas Meet', 'Im going to DHARMA Initiative this day.', 17, 1, '2018-04-16', '13:00:00', '14:00:00'),
(2, 'Wallmart Dallas Meet', 'Im going to Parallax Corporation this day.', 18, 1, '2018-04-17', '16:00:00', '17:00:00'),
(3, 'Test', 'Test', 16, 2, '2018-04-20', '13:00:00', '15:00:00'),
(4, 'asdfsa', '', 0, 0, '2018-04-16', '04:43:07', '04:43:07'),
(5, 'Customer Visit', 'Visit', 17, 1, '2018-05-02', '15:00:00', '17:00:00'),
(6, 'Customer Visit', 'Visit', 18, 1, '2018-05-03', '15:00:00', '17:00:00'),
(7, 'Bla Bla', 'Test', 17, 1, '2018-05-30', '15:00:00', '18:00:00'),
(8, 'Test', 'Tes', 16, 1, '2018-05-15', '16:35:33', '18:35:58');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `order_id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `main_menu` int(5) DEFAULT '0',
  `icon` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `show_staff` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `order_id`, `name`, `description`, `main_menu`, `icon`, `url`, `show_staff`) VALUES
(1, 1, 'x_menu_panel', NULL, 0, NULL, 'panel', 0),
(2, 2, 'x_menu_finance', NULL, 0, NULL, NULL, 0),
(3, 3, 'x_menu_customers_and_lead', NULL, 0, NULL, NULL, 0),
(4, 4, 'x_menu_track', NULL, 0, NULL, NULL, 0),
(5, 5, 'x_menu_others', NULL, 0, NULL, NULL, 1),
(6, 6, 'x_menu_purchases', NULL, 0, NULL, NULL, 0),
(7, 7, 'x_menu_modules', NULL, 0, NULL, NULL, 0),
(8, 1, 'x_menu_invoices', 'manage_invoices', 2, 'ico-ciuis-invoices', 'invoices', 0),
(9, 2, 'x_menu_deposits', 'manage_deposits', 2, 'ion-ios-paper-outline', 'deposits', 0),
(10, 3, 'x_menu_expenses', 'manage_expenses', 2, 'ico-ciuis-expenses', 'expenses', 0),
(11, 4, 'x_menu_accounts', 'manage_accounts', 2, 'ico-ciuis-accounts', 'accounts', 0),
(12, 1, 'x_menu_orders', 'manage_orders', 3, 'ion-ios-filing-outline', 'orders', 0),
(13, 2, 'x_menu_proposals', 'manage_proposals', 3, 'ico-ciuis-proposals', 'proposals', 0),
(14, 3, 'x_menu_customers', 'manage_customers', 3, 'ico-ciuis-customers', 'customers', 0),
(15, 4, 'x_menu_leads', 'manage_leads', 3, 'ico-ciuis-leads', 'leads', 0),
(16, 5, 'x_menu_products', 'manage_products', 3, 'ico-ciuis-products', 'products', 0),
(17, 1, 'x_menu_projects', 'manage_projects', 4, 'ico-ciuis-projects', 'projects', 0),
(18, 2, 'x_menu_tasks', 'manage_tasks', 4, 'ico-ciuis-tasks', 'tasks', 0),
(19, 3, 'x_menu_timesheets', 'manage_timesheets', 4, 'ion-ios-clock-outline', 'timesheets', 0),
(20, 4, 'x_menu_tickets', 'manage_tickets', 4, 'ico-ciuis-supports', 'tickets', 0),
(21, 4, 'x_menu_reports', 'manage_reports', 5, 'ico-ciuis-reports', 'report', 0),
(22, 1, 'x_menu_staff', 'manage_staff', 5, 'ico-ciuis-staff', 'staff', 0),
(23, 2, 'x_menu_calendar', 'manage_calendar', 5, 'ion-android-calendar', 'calendar', 0),
(24, 3, 'x_menu_emails', 'manage_emails', 5, 'ion-ios-email-outline', 'emails', 0),
(25, 1, 'x_menu_vendors', 'manage_vendor', 6, 'ion-social-buffer-outline', 'vendors', 0),
(26, 2, 'x_menu_purchases', 'manage_purchases', 6, 'ion-ios-cart-outline', 'purchases', 0);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` int(11) NOT NULL,
  `order` int(5) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `description` text,
  `duedate` date NOT NULL,
  `project_id` int(5) NOT NULL,
  `color` varchar(10) DEFAULT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`id`, `order`, `name`, `description`, `duedate`, `project_id`, `color`, `created`) VALUES
(1, 1, 'Make a Design', 'Make a amazing design.', '2018-01-31', 1, 'green', '2018-01-02'),
(2, 2, 'Develop', 'Develop', '2018-01-31', 1, 'green', '2018-01-02'),
(3, 3, 'Start Coding', 'Start Coding', '2018-01-31', 1, 'green', '2018-01-02'),
(5, 1, 'Begin', 'Test', '2018-01-07', 3, 'green', '2018-01-07'),
(7, 2, 'Finish', 'Finish this job.', '2018-02-08', 3, 'green', '2018-02-08'),
(8, 1, 'Phase1', 'Phase1', '2019-04-27', 2, 'green', '2019-04-15'),
(9, 2, 'Phase2', 'Phase2', '2019-05-15', 2, 'green', '2019-04-15'),
(10, 3, 'Go-live', 'Go-Live', '2019-05-31', 2, 'green', '2019-04-15');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `license` varchar(255) DEFAULT NULL,
  `updatedat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) NOT NULL,
  `description` text,
  `addedfrom` int(5) DEFAULT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`id`, `relation_type`, `relation`, `description`, `addedfrom`, `customer_id`, `created`) VALUES
(2, 'customer', 2, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:26:46'),
(4, 'customer', 5, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:27:00'),
(5, 'customer', 6, 'Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-25 02:27:12'),
(7, 'customer', 5, 'Quisque commodo ornare nisi sed sagittis. Donec vitae feugiat odio.', 1, NULL, '2017-08-25 21:18:13'),
(9, 'customer', 6, 'Donec volutpat massa id justo lacinia, quis cursus lorem consectetur.', 1, NULL, '2017-08-26 15:51:45'),
(10, 'customer', 7, 'Lorem ipsum sit dolor amet.', 1, NULL, '2017-08-27 08:09:38'),
(11, 'customer', 8, 'Proin tellus mi, dignissim eget purus sit amet, egestas sodales lectus. Proin ac risus a velit tempor tincidunt. Mauris sapien odio, tincidunt eget magna nec, luctus scelerisque velit.', 1, NULL, '2017-08-28 20:55:19'),
(13, 'proposal', 2, 'Test', 1, NULL, '2017-08-31 00:46:45'),
(15, 'proposal', 2, 'Test', 1, NULL, '2017-08-31 00:48:55'),
(16, 'lead', 1, 'Test', 1, NULL, '2017-08-31 03:49:26'),
(19, 'customer', 16, 'Test', 1, NULL, '2017-08-31 03:56:39'),
(22, 'lead', 6, 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur.', 1, NULL, '2017-09-03 02:27:14'),
(24, 'customer', 1, 'Lorem ipsum dolor sit amet.', 1, NULL, '2017-09-06 17:29:26'),
(25, 'lead', 2, 'Test', 1, NULL, '2017-09-11 04:59:12'),
(35, 'project', 11, 'Sample Details', 1, NULL, '2017-11-14 02:10:46'),
(38, 'project', 0, 'Test', 1, NULL, '2017-11-23 01:48:24'),
(39, 'proposal', 0, 'Test', 1, NULL, '2017-11-23 02:05:42'),
(40, 'proposal', 0, 'test', 1, NULL, '2017-11-23 02:07:03'),
(45, 'customer', 9, 'Lorem ipsum sit dolor amet.', 1, NULL, '2017-11-23 02:24:09'),
(46, 'proposal', 7, 'Sample proposal note.', 1, NULL, '2017-11-23 02:28:59'),
(47, 'proposal', 7, 'Lorem ipsum dolor sit amet.', 1, NULL, '2017-11-23 02:29:09'),
(48, 'proposal', 7, 'Another note.', 2, NULL, '2017-11-23 02:29:59'),
(50, 'lead', 7, 'Sample lead note', 1, NULL, '2017-11-23 02:39:19'),
(51, 'lead', 7, 'Lorem ipsum sit dolor amet.', 1, NULL, '2017-11-23 02:39:28'),
(52, 'customer', 17, 'This page of this web site has a good collection of Zen Tales. Why are we suggesting that you tell these stories to your neighbors?', 1, NULL, '2017-11-23 03:54:59'),
(53, 'customer', 17, 'Or maybe it\'s just because they are fun to tell.', 1, NULL, '2017-11-23 03:55:13'),
(54, 'customer', 17, 'Think of these tales as conversation pieces, as handy tools that you can lift out of your pocket to help you and others talk, think and laugh about the wondrous and mysterious details of this thing we call Life. ', 1, NULL, '2017-11-23 03:55:34'),
(55, 'customer', 17, 'Lorem ipsum dolor sit amet', 1, NULL, '2017-11-23 03:58:58'),
(56, 'proposal', 5, 'Sample proposal note', 1, NULL, '2017-11-25 05:54:50'),
(63, 'project', 11, 'Sample note', 1, NULL, '2017-12-11 14:50:32'),
(64, 'proposal', 5, 'Sample note', 1, NULL, '2017-12-11 14:51:56'),
(66, 'lead', 11, 'Sample note', 1, NULL, '2017-12-11 14:52:10'),
(67, 'lead', 11, 'Lorem ipsum dolor sit amet.', 1, NULL, '2017-12-12 03:59:35'),
(69, 'customer', 1, 'Sample', 1, NULL, '2018-01-01 16:36:05'),
(70, 'proposal', 1, 'Lorem ipsum dolor sit amet.', 1, NULL, '2018-01-01 23:15:32'),
(71, 'invoice', 10, 'Lorem ipsum sit dolor amet.', 1, NULL, '2018-03-05 00:00:00'),
(72, 'project', 1, 'Sample test note.', 1, NULL, '2018-06-25 15:59:56');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `target` varchar(255) NOT NULL,
  `markread` tinyint(2) NOT NULL DEFAULT '0',
  `customerread` int(5) NOT NULL,
  `date` datetime NOT NULL,
  `detail` text NOT NULL,
  `staff_id` int(5) NOT NULL,
  `contact_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `vendor_id` int(5) NOT NULL,
  `public` int(5) DEFAULT NULL,
  `perres` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `target`, `markread`, `customerread`, `date`, `detail`, `staff_id`, `contact_id`, `customer_id`, `vendor_id`, `public`, `perres`) VALUES
(72, 'http://localhost/ciuiscrm/tasks/task/2', 0, 0, '2019-04-15 19:20:21', 'You have been added a new task', 2, 0, 0, 0, NULL, 'pm.jpg'),
(73, 'http://localhost/ciuiscrm/tasks/task/3', 0, 0, '2019-04-15 20:13:26', 'You have been added a new task', 4, 0, 0, 0, NULL, 'pm.jpg'),
(74, 'http://localhost/ciuiscrm/tickets/ticket/4', 1, 0, '2019-04-15 16:49:41', 'Sue Shei added new ticket', 0, 0, 0, 0, 1, 'n-img.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(100) DEFAULT NULL,
  `token` mediumtext NOT NULL,
  `subject` varchar(300) DEFAULT NULL,
  `content` longtext,
  `date` date NOT NULL,
  `created` date NOT NULL,
  `opentill` date DEFAULT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT NULL,
  `assigned` int(5) DEFAULT NULL,
  `addedfrom` int(5) NOT NULL,
  `datesend` datetime DEFAULT NULL,
  `comment` int(5) DEFAULT NULL,
  `status_id` int(5) NOT NULL DEFAULT '0',
  `invoice_id` int(5) DEFAULT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `token`, `subject`, `content`, `date`, `created`, `opentill`, `relation_type`, `relation`, `assigned`, `addedfrom`, `datesend`, `comment`, `status_id`, `invoice_id`, `dateconverted`, `sub_total`, `total_discount`, `total_tax`, `total`, `pdf_status`) VALUES
(1, NULL, 'ad794d7fa6507aa3fd93218c26d9b6f0', 'Sample Order', 'Order is added.', '2018-10-14', '2018-10-14', '2018-10-14', 'customer', 18, 2, 1, '0000-00-00 00:00:00', 0, 1, NULL, NULL, '200.00', '20.00', '10.00', '190.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transactiontype` int(5) NOT NULL,
  `is_transfer` int(5) DEFAULT NULL,
  `deposit_id` int(5) DEFAULT NULL,
  `invoice_id` int(5) DEFAULT NULL,
  `expense_id` int(5) DEFAULT NULL,
  `purchase_id` int(5) DEFAULT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `vendor_id` int(5) DEFAULT NULL,
  `amount` decimal(11,2) NOT NULL,
  `account_id` int(5) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `not` varchar(255) NOT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `staff_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `transactiontype`, `is_transfer`, `deposit_id`, `invoice_id`, `expense_id`, `purchase_id`, `customer_id`, `vendor_id`, `amount`, `account_id`, `mode`, `date`, `not`, `attachment`, `staff_id`) VALUES
(1, 0, 0, NULL, 5, 0, NULL, 16, NULL, '100.00', 1, NULL, '2018-01-05 00:00:00', 'Cash', NULL, 1),
(2, 1, 0, NULL, 0, 1, NULL, 17, NULL, '20.00', 1, NULL, '2018-01-05 00:00:00', 'Payment for <a href=\"http://localhost:8888/ciuis/expenses/edit/1\">EXP-1</a>', NULL, 1),
(3, 0, 0, NULL, 6, 0, NULL, 12, NULL, '200.00', 3, NULL, '2018-01-06 00:00:00', 'Payment for INV-6.', NULL, 1),
(5, 1, 0, NULL, 0, 3, NULL, 0, NULL, '20.00', 3, NULL, '2018-01-06 00:00:00', 'Payment for <a href=\"http://localhost:8888/ciuis/expenses/edit/3\">EXP-3</a>', NULL, 1),
(6, 0, 0, NULL, 7, 0, NULL, 17, NULL, '100.00', 1, NULL, '2018-01-22 19:22:38', 'Sample', NULL, 1),
(7, 1, 0, NULL, 0, 4, NULL, 17, NULL, '10.00', 1, NULL, '2018-01-25 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/4\">EXP-4</a>', NULL, 1),
(8, 0, 0, NULL, 9, 0, NULL, 1, NULL, '210.00', 1, NULL, '2018-02-13 00:00:00', 'Payment for INV-9.', NULL, 1),
(9, 0, 0, NULL, 1, 0, NULL, 17, NULL, '200.00', 1, NULL, '2018-03-09 01:11:22', 'Payment for INV-Array.', NULL, 1),
(10, 0, 0, NULL, 11, 0, NULL, 17, NULL, '100.00', 1, NULL, '2018-03-09 01:14:12', 'Payment for INV-Array.', NULL, 1),
(11, 1, 0, NULL, 0, 5, NULL, 0, NULL, '20.00', 1, NULL, '2018-04-11 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/5\">EXP-5</a>', NULL, 1),
(13, 1, 0, NULL, 0, 7, NULL, 16, NULL, '20.00', 3, NULL, '2018-04-11 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/7\">EXP-7</a>', NULL, 1),
(17, 0, 0, NULL, 12, 0, NULL, 17, NULL, '210.00', 3, NULL, '2018-04-22 22:41:05', 'Payment for INV-12.', NULL, 1),
(18, 0, 0, NULL, 13, 0, NULL, 18, NULL, '20.00', 1, NULL, '2018-04-25 19:02:44', '232', NULL, 1),
(19, 0, 0, NULL, 10, 0, NULL, 6, NULL, '23.00', 1, NULL, '2018-04-25 19:03:44', '232', NULL, 1),
(20, 0, 0, NULL, 13, 0, NULL, 18, NULL, '10.00', 1, NULL, '2018-04-25 19:05:36', 'Sale', NULL, 1),
(21, 1, 0, NULL, 0, 8, NULL, 18, NULL, '20.00', 1, NULL, '2018-05-20 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/8\">EXP-8</a>', NULL, 1),
(22, 0, 1, NULL, 0, 0, NULL, 0, NULL, '20.00', 1, NULL, '2018-06-01 06:59:56', 'Money transfer transaction between accounts.', NULL, 1),
(23, 1, 1, NULL, 0, 0, NULL, 0, NULL, '20.00', 3, NULL, '2018-06-01 06:59:56', 'Money transfer transaction between accounts.', NULL, 1),
(24, 1, 0, NULL, 0, 9, NULL, 0, NULL, '10.00', 4, NULL, '2018-06-01 00:00:00', 'Outgoings for <a href=\"http://localhost:8888/ciuis/expenses/receipt/9\">EXP-9</a>', NULL, 1),
(25, 0, 1, NULL, 0, 0, NULL, 0, NULL, '100.00', 4, NULL, '2018-06-01 07:31:05', 'Money transfer transaction between accounts.', NULL, 1),
(26, 1, 1, NULL, 0, 0, NULL, 0, NULL, '100.00', 1, NULL, '2018-06-01 07:31:05', 'Money transfer transaction between accounts.', NULL, 1),
(27, 0, 1, NULL, 0, 0, NULL, 0, NULL, '13.00', 4, NULL, '2018-06-01 07:40:06', 'Money transfer transaction between accounts.', NULL, 1),
(28, 1, 1, NULL, 0, 0, NULL, 0, NULL, '13.00', 1, NULL, '2018-06-01 07:40:06', 'Money transfer transaction between accounts.', NULL, 1),
(29, 0, 0, NULL, 18, 0, NULL, 18, NULL, '1000.00', 1, NULL, '2018-09-19 00:00:00', 'Payment for INV-18.', NULL, 1),
(30, 1, 0, NULL, 0, 10, NULL, 0, NULL, '20.00', 1, NULL, '2018-11-13 00:00:00', 'Payment for <a href=\"http://localhost/ciuiscrm/expenses/edit/10\">EXP-10</a>', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `input_label1` varchar(255) DEFAULT NULL,
  `input_label2` varchar(255) DEFAULT NULL,
  `input_label3` varchar(255) DEFAULT NULL,
  `active` varchar(1) DEFAULT '0',
  `input_value1` varchar(255) DEFAULT NULL,
  `input_value2` varchar(255) DEFAULT NULL,
  `input_value3` varchar(255) DEFAULT NULL,
  `sandbox_account` varchar(1) DEFAULT '0',
  `payment_record_account` varchar(11) DEFAULT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `gateway_note` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `image`, `input_label1`, `input_label2`, `input_label3`, `active`, `input_value1`, `input_value2`, `input_value3`, `sandbox_account`, `payment_record_account`, `relation`, `gateway_note`, `updated_at`) VALUES
(1, 'PayPal', 'paypal.png', 'paypalemail', 'paypalcurrency', NULL, '0', '', '', '', '1', '', 'paypal', NULL, '2019-08-07 04:03:22'),
(2, 'CCAvenue', 'ccavenue.png', 'ccavenue_marchent_key', 'ccavenue_working_key', 'ccavenue_access_code', '0', '', '', '', '1', '', 'ccavenue', 'CCAvenue Gateway uses mcrypt encryption. \r\n Please make sure you\'re using PHP 7.0 or Older and mcrypt extension is enabled.', '2019-08-07 04:22:31'),
(3, 'PayUmoney', 'payumoney.png', 'payu_money_key', 'payu_money_salt', NULL, '0', '', '', '', '1', '', 'payumoney', NULL, '2019-08-07 06:04:23'),
(4, 'Stripe', 'stripe.png', 'stripe_api_secret_key', 'stripe_public_key', NULL, '1', 'pk_test_NxPcM4BcnEUXoe4vX73CeZmk', 'sk_test_ec1SIytF7fm578SXqcZ0uFXn', '', '1', '4', 'stripe', NULL, '2019-08-07 04:09:00'),
(5, 'Authorize.net AIM', 'authorize.png', 'authorize_aim_api_login_id', 'authorize_aim_api_transaction_key', NULL, '0', '', '', NULL, '1', '', 'authorize', NULL, '2019-08-08 00:00:00'),
(6, 'Razorpay', 'razorpay.png', 'key_id', 'razorpay_key_secret', NULL, '0', '', '', '', '1', '0', 'razorpay', NULL, '2019-08-07 05:35:40'),
(7, 'Bank', 'bank.png', NULL, NULL, NULL, '0', '', '', '', '0', '3', 'bank', NULL, '2019-08-07 02:15:40');

-- --------------------------------------------------------

--
-- Table structure for table `payment_modes`
--

CREATE TABLE `payment_modes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_modes`
--

INSERT INTO `payment_modes` (`id`, `name`, `value`) VALUES
(1, 'authorize_aim_active', '0'),
(2, 'authorize_aim_api_login_id', ''),
(3, 'authorize_aim_api_transaction_key', ''),
(4, 'authorize_aim_payment_record_account', ''),
(5, 'paypal_active', '0'),
(6, 'paypal_username', ''),
(7, 'paypal_currency', ''),
(8, 'paypal_test_mode_enabled', '1'),
(9, 'paypal_payment_record_account', ''),
(10, 'stripe_active', '1'),
(11, 'stripe_api_secret_key', 'pk_test_NxPcM4BcnEUXoe4vX73CeZmk'),
(12, 'stripe_api_publishable_key', 'sk_test_ec1SIytF7fm578SXqcZ0uFXn'),
(13, 'payu_money_active', '0'),
(14, 'payu_money_key', ''),
(15, 'payu_money_salt', ''),
(16, 'payu_money_test_mode_enabled', '1'),
(17, 'stripe_record_account', '4'),
(18, 'ccavenue_record_account', ''),
(19, 'ccavenue_active', '0'),
(20, 'ccavenue_marchent_key', ''),
(21, 'ccavenue_working_key', ''),
(22, 'ccavenue_access_code', ''),
(23, 'ccavenue_test_mode', '1'),
(24, 'payu_money_record_account', ''),
(25, 'stripe_test_mode_enabled', '1'),
(26, 'razorpay_active', '0'),
(27, 'razorpay_key', ''),
(28, 'razorpay_test_mode_enabled', '1'),
(29, 'razorpay_payment_record_account', '0'),
(30, 'primary_bank_account', '3'),
(31, 'authorize_test_mode_enabled', '1'),
(32, 'razorpay_key_secret', '');

-- --------------------------------------------------------

--
-- Table structure for table `pending_process`
--

CREATE TABLE `pending_process` (
  `process_id` int(11) NOT NULL,
  `process_type` varchar(100) DEFAULT 'pdf',
  `process_relation` int(5) DEFAULT NULL,
  `process_relation_type` varchar(100) DEFAULT NULL,
  `process_template_name` varchar(200) DEFAULT NULL,
  `process_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`, `type`, `key`) VALUES
(1, 'x_menu_invoices', 'common', 'invoices'),
(2, 'x_menu_proposals', 'common', 'proposals'),
(3, 'x_menu_expenses', 'non-common', 'expenses'),
(4, 'x_menu_accounts', 'non-common', 'accounts'),
(5, 'x_menu_customers', 'non-common', 'customers'),
(6, 'x_menu_leads', 'non-common', 'leads'),
(7, 'x_menu_projects', 'common', 'projects'),
(8, 'x_menu_tasks', 'non-common', 'tasks'),
(9, 'x_menu_tickets', 'common', 'tickets'),
(10, 'x_menu_products', 'non-common', 'products'),
(11, 'x_menu_staff', 'non-common', 'staff'),
(12, 'x_menu_reports', 'non-common', 'report'),
(13, 'x_menu_orders', 'non-common', 'orders'),
(14, 'x_menu_emails', 'non-common', 'emails'),
(15, 'x_menu_timesheets', 'non-common', 'timesheets'),
(16, 'x_menu_quotes', 'common', 'quotes'),
(17, 'x_menu_vendor', 'non-common', 'vendors'),
(18, 'x_menu_purchases', 'non-common', 'purchases'),
(19, 'x_menu_deposits', 'non-common', 'deposits'),
(20, 'x_menu_settings', 'non-common', 'settings');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `relation` int(5) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `permission_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`relation`, `relation_type`, `permission_id`) VALUES
(1, 'staff', 3),
(1, 'staff', 4),
(1, 'staff', 5),
(1, 'staff', 6),
(1, 'staff', 7),
(1, 'staff', 8),
(1, 'staff', 9),
(3, 'staff', 3),
(15, 'contact', 1),
(15, 'contact', 2),
(15, 'contact', 7),
(15, 'contact', 9),
(2, 'staff', 1),
(2, 'staff', 2),
(2, 'staff', 3),
(2, 'staff', 4),
(2, 'staff', 5),
(2, 'staff', 6),
(2, 'staff', 7),
(2, 'staff', 8),
(2, 'staff', 9),
(2, 'staff', 10),
(2, 'staff', 11),
(2, 'staff', 12),
(23, 'contact', 1),
(23, 'contact', 2),
(23, 'contact', 7),
(23, 'contact', 9),
(4, 'staff', 1),
(1, 'staff', 2),
(1, 'staff', 1),
(1, 'staff', 11),
(1, 'staff', 12),
(1, 'staff', 10),
(1, 'staff', 14),
(1, 'staff', 15),
(1, 'staff', 13),
(3, 'staff', 1),
(1, 'staff', 16),
(23, 'contact', 16),
(5, 'staff', 1),
(5, 'staff', 2),
(5, 'staff', 3),
(5, 'staff', 4),
(5, 'staff', 5),
(5, 'staff', 6),
(5, 'staff', 7),
(5, 'staff', 8),
(5, 'staff', 9),
(5, 'staff', 10),
(5, 'staff', 11),
(5, 'staff', 12),
(5, 'staff', 13),
(5, 'staff', 14),
(5, 'staff', 15),
(5, 'staff', 16);

-- --------------------------------------------------------

--
-- Table structure for table `productcategories`
--

CREATE TABLE `productcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_number` varchar(100) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `productname` varchar(255) DEFAULT NULL,
  `categoryid` int(11) NOT NULL,
  `description` text NOT NULL,
  `productimage` varchar(255) DEFAULT NULL,
  `purchase_price` decimal(20,2) DEFAULT NULL,
  `sale_price` decimal(20,2) DEFAULT NULL,
  `stock` bigint(20) DEFAULT NULL,
  `vat` decimal(20,2) DEFAULT NULL,
  `status_id` enum('0','1') DEFAULT NULL,
  `product_created_by` int(5) DEFAULT '0',
  `createdat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_number`, `code`, `productname`, `categoryid`, `description`, `productimage`, `purchase_price`, `sale_price`, `stock`, `vat`, `status_id`, `product_created_by`, `createdat`) VALUES
(1, NULL, 'WEB', 'Web Design', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', NULL, '20.00', '200.00', 200, '5.00', NULL, 0, NULL),
(2, NULL, 'WEB', 'Seo Consultant', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', NULL, '10.00', '200.00', 200, '0.00', NULL, 0, NULL),
(3, NULL, 'WEB-204', 'Logo Design', 0, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet iaculis risus.', NULL, '10.00', '200.00', 200, '0.00', NULL, 0, NULL),
(5, NULL, 'GRA-24', 'Graphic Design', 0, 'Graphic Design Services', NULL, '0.00', '100.00', 10000, '0.00', NULL, 0, NULL),
(6, NULL, 'SRV-2324', 'Server Customization', 0, 'Server Customization.', NULL, '0.00', '1000.00', 0, '0.00', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `projectmembers`
--

CREATE TABLE `projectmembers` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projectmembers`
--

INSERT INTO `projectmembers` (`id`, `project_id`, `staff_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_number` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `projectvalue` decimal(20,2) DEFAULT '0.00',
  `tax` decimal(20,2) DEFAULT '0.00',
  `status_id` int(5) NOT NULL DEFAULT '0',
  `customer_id` int(5) NOT NULL,
  `invoice_id` int(5) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `created` date NOT NULL,
  `finished` datetime DEFAULT NULL,
  `pinned` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `template` tinyint(1) DEFAULT '0',
  `pdf_report` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_number`, `name`, `description`, `projectvalue`, `tax`, `status_id`, `customer_id`, `invoice_id`, `start_date`, `deadline`, `created`, `finished`, `pinned`, `staff_id`, `template`, `pdf_report`) VALUES
(1, NULL, 'Web Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', '0.00', '0.00', 5, 17, 0, '2018-06-01', '2018-08-02', '2018-06-01', NULL, 1, 1, 0, 0),
(2, NULL, 'Website Template', 'Website for a Big Marketing Agency Project Template', '3000.00', '19.00', 1, 0, 0, '2019-04-15', '2019-08-10', '2019-04-15', NULL, 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `projectservices`
--

CREATE TABLE `projectservices` (
  `id` int(11) NOT NULL,
  `projectid` int(5) DEFAULT NULL,
  `categoryid` int(5) DEFAULT NULL,
  `categoryname` varchar(255) DEFAULT NULL,
  `productid` int(5) DEFAULT NULL,
  `servicename` varchar(255) DEFAULT NULL,
  `serviceprice` decimal(20,2) DEFAULT NULL,
  `quantity` varchar(20) DEFAULT '1',
  `unit` varchar(100) DEFAULT 'Unit',
  `servicetax` decimal(20,2) DEFAULT NULL,
  `servicedescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` int(11) NOT NULL,
  `proposal_number` varchar(100) DEFAULT NULL,
  `token` varchar(200) NOT NULL,
  `subject` varchar(300) DEFAULT NULL,
  `content` longtext,
  `customer_quote` longtext,
  `date` date NOT NULL,
  `created` date NOT NULL,
  `opentill` date DEFAULT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT NULL,
  `assigned` int(5) DEFAULT NULL,
  `addedfrom` int(5) NOT NULL,
  `datesend` datetime DEFAULT NULL,
  `comment` int(5) DEFAULT NULL,
  `status_id` int(5) NOT NULL DEFAULT '0',
  `invoice_id` int(5) DEFAULT NULL,
  `project_id` int(5) DEFAULT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `is_requested` tinyint(1) DEFAULT '0',
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `proposal_number`, `token`, `subject`, `content`, `customer_quote`, `date`, `created`, `opentill`, `relation_type`, `relation`, `assigned`, `addedfrom`, `datesend`, `comment`, `status_id`, `invoice_id`, `project_id`, `dateconverted`, `sub_total`, `total_discount`, `total_tax`, `total`, `is_requested`, `pdf_status`) VALUES
(2, NULL, '2d5525f66e8e3bdc29d6b2c1bb834e48', 'Seo Consultant', 'Seo Consultant', NULL, '2018-01-10', '2018-01-10', '2018-03-13', 'customer', 17, 2, 1, '0000-00-00 00:00:00', 1, 2, NULL, NULL, NULL, '200.00', '0.00', '0.00', '200.00', 0, 0),
(4, NULL, '23fc708ebbc362838b681fac51046ab8', 'Poster Design', 'Poster design.', NULL, '2018-01-11', '2018-01-11', '2018-03-22', 'customer', 16, 4, 1, '0000-00-00 00:00:00', 0, 4, NULL, NULL, NULL, '100.00', '0.00', '0.00', '100.00', 0, 0),
(5, NULL, '31e3fe3b03d23ad26a774feb4553694a', 'Mobile Application', 'Lorem ipsum dolor sit amet, id quaeque disputationi est. Sit quando recteque ad, inermis vivendo mei te, minim feugait adversarium eu qui. Id utamur placerat adolescens est. Eu eam postea commodo corpora, no ius vidisse tibique sensibus. Duo noster euismod ex, in tale aeterno epicurei mei. Tota cotidieque cum ex, mea no reque dolorum deserunt, soluta phaedrum vix ea. Errem tollit concludaturque vix no.\n\nAd pro accumsan adipiscing. No duo equidem delicata, no dolorum electram sed, quo quis tota facilisis cu. Ut reque velit tincidunt has, per an case option utamur, ad est dolorem ponderum inciderint. His ea prompta expetenda, no aliquid facilisis dissentias vim.\n\nUt deleniti electram mel, sit te suas assum laboramus, sit id erat verterem interpretaris. Ei vix debet dissentias, vero accusata evertitur usu ut. Qui audiam accusamus id. An vix maiestatis interpretaris, est dictas inermis mediocrem no. Inermis referrentur pri id, iudico integre copiosae mea ex.', NULL, '2017-08-24', '2017-08-24', '2018-06-19', 'customer', 12, 1, 1, '2018-10-01 08:15:07', 1, 6, 16, NULL, '2018-06-20 16:44:43', '200.00', '0.00', '10.00', '210.00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `purchase_number` varchar(100) DEFAULT NULL,
  `token` varchar(200) NOT NULL,
  `no` int(11) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `vendor_id` int(5) NOT NULL,
  `expense_id` int(5) DEFAULT NULL,
  `proposal_id` int(5) DEFAULT NULL,
  `project_id` int(5) DEFAULT NULL,
  `staff_id` int(5) DEFAULT NULL,
  `datesend` datetime DEFAULT NULL,
  `datepayment` date DEFAULT NULL,
  `duenote` text,
  `status_id` int(5) DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `total_discount` decimal(11,2) DEFAULT NULL,
  `total_tax` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `recurring` int(5) NOT NULL DEFAULT '0',
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `recurring`
--

CREATE TABLE `recurring` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) NOT NULL DEFAULT '0',
  `period` int(5) NOT NULL DEFAULT '0',
  `type` int(5) NOT NULL DEFAULT '0' COMMENT '0 = day | 1 = week | 2 = month | 3 = years',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` varchar(100) DEFAULT 'Invalid date',
  `status` int(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recurring`
--

INSERT INTO `recurring` (`id`, `relation_type`, `relation`, `period`, `type`, `created_at`, `end_date`, `status`) VALUES
(13, 'invoice', 8, 1, 0, '2018-02-11 21:00:00', '2018-04-28 00:00:00', 0),
(14, 'invoice', 9, 1, 0, '2018-02-13 08:21:22', '2018-02-13 00:00:00', 1),
(15, 'invoice', 10, 0, 0, '2018-04-11 19:38:59', '1970-01-01 00:00:00', 1),
(16, 'invoice', 7, 0, 0, '2018-04-11 19:39:32', 'Invalid date', 0),
(17, 'invoice', 5, 0, 0, '2018-05-19 20:32:00', 'Invalid date', 0),
(18, 'invoice', 16, 0, 0, '2018-06-20 13:45:32', 'Invalid date', 0),
(19, 'invoice', 19, 0, 0, '2018-11-11 23:37:27', 'Invalid date', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `description` text,
  `date` datetime NOT NULL,
  `isnotified` int(11) NOT NULL DEFAULT '0',
  `addedfrom` int(11) NOT NULL,
  `public` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `relation_type`, `relation`, `staff_id`, `description`, `date`, `isnotified`, `addedfrom`, `public`) VALUES
(1, 'lead', 1, 1, 'Donec nisl risus, dignissim at.', '2017-09-05 17:16:25', 1, 1, 0),
(9, 'lead', 4, 4, 'Test', '2017-03-30 11:55:00', 0, 1, 0),
(10, 'lead', 2, 3, 'Lorem ipsum sit dolor amet.', '2017-09-30 12:50:00', 0, 1, 0),
(11, 'lead', 6, 3, 'Please check proposals.', '2017-09-03 03:25:00', 0, 1, 0),
(12, 'lead', 7, 2, 'Meet Evelyn Bradley.', '2017-09-03 03:35:00', 0, 1, 0),
(13, 'proposal', 2, 1, 'Lorem ipsum dolor sit amet.', '2017-09-07 01:20:00', 1, 1, 0),
(16, 'customer', 16, 1, 'Test', '2017-09-11 05:01:00', 1, 1, 0),
(19, 'proposal', 1, 2, 'Test reminder for this proposal.', '2017-09-11 10:35:00', 1, 1, 0),
(20, 'lead', 5, 1, 'Test', '2017-11-12 01:30:00', 1, 1, 0),
(21, 'lead', 11, 1, 'Sample reminder for lead', '2018-08-02 02:10:00', 1, 1, 0),
(24, 'customer', 17, 1, 'Test reminder', '2017-12-02 03:35:00', 1, 1, 0),
(62, 'proposal', 5, 3, 'Lorem ipsum dolor sit amet ectum veritas.', '2017-12-12 00:50:52', 0, 1, 0),
(63, 'customer', 17, 1, 'Sample reminder for me.', '2018-01-01 23:10:28', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_type` varchar(10) NOT NULL,
  `role_createdat` datetime DEFAULT NULL,
  `role_updatedat` datetime DEFAULT NULL,
  `created_by` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_type`, `role_createdat`, `role_updatedat`, `created_by`) VALUES
(1, 'Admin Role', 'admin', '2019-08-09 08:04:18', '2019-08-16 07:13:48', 1),
(2, 'Staff Role', 'staff', '2019-08-16 09:32:37', '2019-08-16 09:32:37', 1),
(3, 'VAT Consultant', 'other', '2019-08-16 09:32:57', '2019-08-16 09:32:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_permission_id` int(5) NOT NULL,
  `permission_id` int(5) NOT NULL,
  `permission_view_own` tinyint(1) NOT NULL,
  `permission_view_all` tinyint(1) NOT NULL,
  `permission_edit` tinyint(1) NOT NULL,
  `permission_delete` tinyint(1) NOT NULL,
  `permission_create` tinyint(1) NOT NULL,
  `role_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_permission_id`, `permission_id`, `permission_view_own`, `permission_view_all`, `permission_edit`, `permission_delete`, `permission_create`, `role_id`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 1, 1, 1),
(3, 3, 1, 1, 1, 1, 1, 1),
(4, 4, 1, 1, 1, 1, 1, 1),
(5, 5, 1, 1, 1, 1, 1, 1),
(6, 6, 1, 1, 1, 1, 1, 1),
(7, 7, 1, 0, 1, 1, 1, 1),
(8, 8, 1, 1, 1, 1, 1, 1),
(9, 9, 1, 1, 1, 1, 1, 1),
(10, 10, 1, 1, 1, 1, 1, 1),
(11, 11, 1, 1, 1, 1, 1, 1),
(12, 12, 0, 1, 0, 0, 0, 1),
(13, 13, 1, 1, 1, 1, 1, 1),
(14, 14, 0, 1, 1, 0, 0, 1),
(15, 15, 1, 1, 1, 1, 1, 1),
(16, 16, 0, 0, 0, 0, 0, 1),
(17, 17, 1, 1, 1, 1, 1, 1),
(18, 18, 1, 1, 1, 1, 1, 1),
(19, 19, 1, 1, 1, 1, 1, 1),
(20, 20, 1, 1, 1, 1, 1, 1),
(21, 1, 1, 1, 1, 1, 1, 2),
(22, 2, 1, 1, 1, 1, 1, 2),
(23, 3, 1, 1, 1, 1, 1, 2),
(24, 4, 1, 1, 1, 1, 1, 2),
(25, 5, 1, 1, 1, 1, 1, 2),
(26, 6, 1, 1, 1, 1, 1, 2),
(27, 7, 1, 1, 1, 1, 1, 2),
(28, 8, 1, 1, 1, 1, 1, 2),
(29, 9, 1, 1, 1, 1, 1, 2),
(30, 10, 1, 1, 1, 1, 1, 2),
(31, 11, 1, 1, 1, 1, 1, 2),
(32, 12, 1, 1, 1, 1, 1, 2),
(33, 13, 1, 1, 1, 1, 1, 2),
(34, 14, 1, 1, 1, 1, 1, 2),
(35, 15, 1, 1, 1, 1, 1, 2),
(36, 16, 1, 1, 1, 1, 1, 2),
(37, 17, 1, 1, 1, 1, 1, 2),
(38, 18, 1, 1, 1, 1, 1, 2),
(39, 19, 1, 1, 1, 1, 1, 2),
(40, 1, 1, 1, 1, 1, 1, 3),
(41, 3, 1, 1, 1, 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_id`, `status_id`, `customer_id`, `staff_id`, `total`, `date`) VALUES
(1, 1, 2, 17, 1, '200.00', '2018-01-01'),
(7, 7, 3, 17, 1, '200.00', '2018-01-22'),
(9, 9, 2, 1, 1, '210.00', '2018-02-13'),
(10, 10, 3, 6, 1, '200.00', '2018-02-13'),
(11, 11, 2, 17, 1, '100.00', '2018-03-09'),
(12, 12, 2, 17, 1, '210.00', '2017-03-25'),
(13, 13, 3, 18, 1, '210.00', '2018-04-22'),
(14, 15, 3, 18, 1, '20.00', '2018-05-20'),
(15, 16, 3, 12, 1, '210.00', '2018-06-20'),
(16, 17, 3, 16, 1, '100.00', '2018-08-11'),
(17, 18, 2, 18, 1, '1000.00', '2018-09-19'),
(18, 19, 3, 18, 1, '100.00', '2018-11-12'),
(21, 22, 3, 0, 1, '0.00', '2018-11-12');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES
('97v2nf6f047nqm1g3glvkmn68chqcqgh', '::1', 1555572893, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535353537323839333b7573725f69647c733a313a2231223b73746166666e616d657c733a31323a224c616e636520426f67726f6c223b656d61696c7c733a31373a226c616e6365406578616d706c652e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b6f746865727c4e3b4c6f67696e4f4b7c623a313b),
('egkif0sg486j7kvah7g43l529uahqb4f', '::1', 1555405635, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535353430353633353b7573725f69647c733a313a2231223b73746166666e616d657c733a31323a224c616e636520426f67726f6c223b656d61696c7c733a31373a226c616e6365406578616d706c652e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b6f746865727c4e3b4c6f67696e4f4b7c623a313b),
('fnt3cg20ud6f570i3cnilgd3rs7e4jcf', '::1', 1555572970, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535353537323839333b7573725f69647c733a313a2231223b73746166666e616d657c733a31323a224c616e636520426f67726f6c223b656d61696c7c733a31373a226c616e6365406578616d706c652e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b6f746865727c4e3b4c6f67696e4f4b7c623a313b),
('h85ga2vmvq37brum4o2i95f3vn35o6ua', '::1', 1555406293, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535353430363233353b7573725f69647c733a313a2235223b73746166666e616d657c733a31303a22526f6f742041646d696e223b656d61696c7c733a31343a22726f6f744063697569732e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a393a226a2d696d672e6a7067223b6f746865727c4e3b4c6f67696e4f4b7c623a313b),
('k62169ssu1bc2sq16m6mbu2dp0qk0uo0', '::1', 1555402324, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535353430323332343b7573725f69647c733a313a2231223b73746166666e616d657c733a31323a224c616e636520426f67726f6c223b656d61696c7c733a31373a226c616e6365406578616d706c652e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b6f746865727c4e3b4c6f67696e4f4b7c623a313b),
('lri8gclsv7h6joidp0p6qk7ng7l53s33', '::1', 1555406074, 0x5f5f63695f6c6173745f726567656e65726174657c693a313535353430363037343b7573725f69647c733a313a2231223b73746166666e616d657c733a31323a224c616e636520426f67726f6c223b656d61696c7c733a31373a226c616e6365406578616d706c652e636f6d223b726f6f747c4e3b6c616e67756167657c733a373a22656e676c697368223b61646d696e7c733a313a2231223b73746166666d656d6265727c4e3b73746166666176617461727c733a363a22706d2e6a7067223b6f746865727c4e3b4c6f67696e4f4b7c623a313b);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settingname` varchar(255) NOT NULL,
  `crm_name` varchar(255) NOT NULL DEFAULT 'Ciuisâ„¢ CRM',
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `country_id` int(5) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `state_id` int(5) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `zipcode` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) NOT NULL,
  `taxoffice` varchar(100) DEFAULT NULL,
  `vatnumber` varchar(100) DEFAULT NULL,
  `unitseparator` varchar(50) NOT NULL DEFAULT '.',
  `currencyid` int(5) NOT NULL,
  `currencyposition` varchar(50) NOT NULL,
  `termtitle` varchar(255) NOT NULL,
  `termdescription` varchar(255) NOT NULL,
  `dateformat` varchar(100) DEFAULT NULL,
  `default_timezone` varchar(100) NOT NULL,
  `languageid` varchar(100) NOT NULL,
  `smtphost` varchar(255) DEFAULT NULL,
  `smtpport` varchar(255) DEFAULT NULL,
  `emailcharset` varchar(255) DEFAULT NULL,
  `email_encryption` int(5) NOT NULL,
  `smtpusername` varchar(255) DEFAULT NULL,
  `smtppassoword` varchar(255) DEFAULT NULL,
  `sendermail` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `app_logo` varchar(255) DEFAULT NULL,
  `accepted_files_formats` varchar(255) NOT NULL DEFAULT 'jpg,jpeg,doc,png,txt,docx',
  `allowed_ip_adresses` varchar(255) DEFAULT NULL,
  `pushState` int(5) NOT NULL,
  `voicenotification` int(5) NOT NULL,
  `converted_lead_status_id` int(5) DEFAULT NULL,
  `two_factor_authentication` int(5) DEFAULT NULL,
  `is_demo` tinyint(1) DEFAULT '0',
  `is_mysql` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settingname`, `crm_name`, `company`, `email`, `address`, `country_id`, `state`, `state_id`, `city`, `town`, `zipcode`, `phone`, `fax`, `taxoffice`, `vatnumber`, `unitseparator`, `currencyid`, `currencyposition`, `termtitle`, `termdescription`, `dateformat`, `default_timezone`, `languageid`, `smtphost`, `smtpport`, `emailcharset`, `email_encryption`, `smtpusername`, `smtppassoword`, `sendermail`, `sender_name`, `logo`, `app_logo`, `accepted_files_formats`, `allowed_ip_adresses`, `pushState`, `voicenotification`, `converted_lead_status_id`, `two_factor_authentication`, `is_demo`, `is_mysql`) VALUES
('ciuis', 'CiuisCRM', 'Acme Business INC', 'info@businessaddress.com', 'P.O. Box 929 4189 Nunc RoadLebanon KY 69409', 236, 'DC', NULL, 'New York', 'New Jersey', '34400', '+1 (389) 737-2852', '+1 (389) 737-2852', 'New York Tax Office', '4530685631', '.', 159, 'before', 'Terms & Conditions', 'Maecenas facilisis ultrices purus non vehicula. Nulla dignissim enim a libero tincidunt, consequat molestie nisi mattis. Phasellus scelerisque fringilla lectus vel tempor.', 'dd.mm.yy', 'America/New_York', 'english', 'mail.ciuis.com', '587', 'utf-8', 0, 'noreply@ciuis.com', 'NKsm25Z0', 'noreply@ciuis.com', '', 'ciuis-icon.png', 'ciuis-icon.png', 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt', '', 0, 1, 4, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staff_number` varchar(100) DEFAULT NULL,
  `role_id` int(5) NOT NULL,
  `language` varchar(100) NOT NULL,
  `staffname` varchar(255) DEFAULT NULL,
  `staffavatar` varchar(300) DEFAULT 'n-img.jpg',
  `department_id` int(5) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `root` tinyint(1) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `staffmember` tinyint(1) DEFAULT NULL,
  `other` tinyint(1) DEFAULT NULL,
  `last_login` datetime NOT NULL,
  `timezone` varchar(200) DEFAULT NULL,
  `appointment_availability` int(5) NOT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `google_calendar_enable` int(5) NOT NULL,
  `google_calendar_id` varchar(255) NOT NULL,
  `google_calendar_api_key` varchar(255) NOT NULL,
  `createdat` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_number`, `role_id`, `language`, `staffname`, `staffavatar`, `department_id`, `phone`, `address`, `email`, `birthday`, `password`, `root`, `admin`, `staffmember`, `other`, `last_login`, `timezone`, `appointment_availability`, `inactive`, `google_calendar_enable`, `google_calendar_id`, `google_calendar_api_key`, `createdat`, `updatedAt`) VALUES
(1, NULL, 1, 'english', 'Lance Bogrol', 'pm.jpg', 1, '+1-202-555-0160', '71 Pilgrim Avenue Chevy Chase, MD 20815', 'lance@example.com', '1992-12-05', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, 1, NULL, NULL, '2017-08-05 03:02:42', NULL, 1, NULL, 0, 'abaris@sdf.net', 'AIzaSyA1sWdokA3dVTzk7gNN58NztVw3kbPhJX8', NULL, NULL),
(2, NULL, 0, 'english', 'Emma Durst', 'emma.jpg', 2, '+1-202-555-0158', '70 Bowman St. South Windsor, CT 06074', 'emma@example.com', '2017-07-03', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, NULL, 1, NULL, '0000-00-00 00:00:00', NULL, 1, NULL, 0, '', '', NULL, NULL),
(3, NULL, 0, 'english', 'Guy Mann', 'guy.jpg', 4, '+1-202-555-0129', '123 6th St. Melbourne, FL 32904', 'guy@example.com', '1992-12-05', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, NULL, 1, 1, '0000-00-00 00:00:00', NULL, 0, NULL, 0, '', '', NULL, NULL),
(4, NULL, 0, 'english', 'Ruby Von Rails', 'ruby.jpg', 3, '+1-202-555-0143', '4 Goldfield Rd. Honolulu, HI 96815', 'ruby@example.com', '1992-12-05', 'fe01ce2a7fbac8fafaed7c982a04e229', NULL, NULL, 1, NULL, '0000-00-00 00:00:00', NULL, 0, NULL, 0, '', '', NULL, NULL),
(5, NULL, 1, 'english', 'Root Admin', 'j-img.jpg', 1, '+457-8899-856', 'Root street', 'root@ciuis.com', '2019-04-16', '63a9f0ea7bb98050796b649e85481845', NULL, 1, NULL, NULL, '0000-00-00 00:00:00', NULL, 0, NULL, 0, '', '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff_work_plan`
--

CREATE TABLE `staff_work_plan` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `work_plan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff_work_plan`
--

INSERT INTO `staff_work_plan` (`id`, `staff_id`, `work_plan`) VALUES
(1, 1, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(2, 2, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(3, 3, '[{\"day\":\"monday\",\"status\":false,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":false,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":false,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":false,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":false,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(4, 4, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(5, 5, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]'),
(6, 5, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `finished` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `staff_id` int(11) NOT NULL,
  `complete` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subtasks`
--

INSERT INTO `subtasks` (`id`, `taskid`, `description`, `finished`, `created`, `staff_id`, `complete`) VALUES
(37, 27, 'Sample sub task.', 0, '2017-11-23 23:15:30', 1, 1),
(38, 27, 'Lorem ipsum dolor sit amet.', 0, '2017-11-23 23:15:36', 1, 1),
(39, 27, 'Hello darkness my old friend.', 0, '2017-11-23 23:15:44', 1, 0),
(40, 29, 'Sample Subtask', 0, '2017-11-23 23:37:44', 1, 0),
(41, 29, 'There is no pain you are receding.', 0, '2017-11-23 23:37:57', 1, 0),
(42, 29, 'Hello darkness my old friend.', 0, '2017-11-23 23:38:07', 1, 1),
(43, 30, 'Hello darkness my old friend', 0, '2017-11-23 23:39:25', 1, 1),
(44, 30, 'Loose yourself to dance', 0, '2017-11-23 23:39:58', 1, 1),
(45, 30, 'One day in the year of the fox', 0, '2017-11-23 23:40:15', 1, 1),
(48, 32, 'Make smiler logo', 0, '2017-11-24 04:53:46', 1, 1),
(49, 32, 'Change color', 0, '2017-11-24 04:53:48', 1, 1),
(50, 32, 'Send customer', 0, '2017-11-24 04:53:55', 1, 1),
(51, 35, 'Check availability of the most common tlds including com, net, org. Also, checks the hyphenated version of your preferred domain name.', 0, '2017-11-25 06:12:16', 2, 1),
(53, 35, 'Preserve the main keywords entered in the final domain name and use the following to preserve SEO', 0, '2017-11-25 06:12:40', 2, 1),
(54, 35, 'Simple Portmanteau - smartly merge popular suffixes like ly, ify, er, ish, ism and many more', 0, '2017-11-25 06:12:51', 2, 1),
(55, 32, '\"What\'s happened to me?\" he thought. It wasn\'t a dream. His room, a proper human', 0, '2017-11-25 06:18:25', 2, 0),
(56, 31, 'Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc, litot Europa usa li sam vocabular.', 0, '2017-11-25 06:18:44', 2, 1),
(57, 31, 'Li nov lingua franca va esser plu simplic e regulari quam li existent Europan', 0, '2017-11-25 06:19:04', 2, 1),
(58, 36, 'Altera perfecto philosophia et eum. Facete aliquip epicurei sed te, ex sed clita legendos atomorum.', 0, '2017-11-25 17:03:08', 1, 1),
(59, 36, 'Mei no alia cibo virtute. Has minim labores te, no quo viderer menandri, ut autem regione delicatissimi mea. Choro pertinacia in vis.', 0, '2017-11-25 17:03:16', 1, 1),
(60, 36, 'Mazim admodum epicurei pro cu. Per at veritus torquatos. Legimus laoreet persecuti ius ex, cu summo falli ius.', 0, '2017-11-25 17:03:25', 1, 1),
(62, 1, 'Sample sub task', 0, '2018-01-06 02:43:49', 1, 0),
(63, 1, 'There is no pain', 0, '2018-01-06 02:43:57', 1, 1),
(64, 11, 'Test', 0, '2018-01-07 22:16:35', 1, 0),
(65, 2, 'Lorem ipsum dolor sit amet.', 0, '2018-01-07 22:53:08', 1, 1),
(66, 2, 'Populo honestatis vel eu, duo in inani possim scriptorem', 0, '2018-01-07 22:53:15', 1, 1),
(67, 2, 'Mundi dolores antiopam eam id.', 0, '2018-01-07 22:53:21', 1, 1),
(68, 1, 'Test', 0, '2018-08-27 05:16:19', 1, 1),
(69, 2, 'Pending Server Conffig', 0, '2019-04-15 19:21:00', 1, 0),
(70, 3, 'We can solve this problem by solving the HTML issue', 0, '2019-04-15 20:14:10', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `table_columns`
--

CREATE TABLE `table_columns` (
  `id` int(11) NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `table_column` varchar(255) DEFAULT NULL,
  `display` tinyint(1) DEFAULT '1',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `table_columns`
--

INSERT INTO `table_columns` (`id`, `relation`, `table_column`, `display`, `updated_at`) VALUES
(1, 'projects', 'name', 1, '2019-07-30 08:02:20'),
(2, 'projects', 'startdate', 1, '2019-08-06 03:13:11'),
(3, 'projects', 'customer', 1, '2019-08-06 03:13:11'),
(4, 'projects', 'deadline', 1, '2019-08-06 03:13:12'),
(5, 'projects', 'value', 1, '2019-08-06 03:13:14'),
(6, 'projects', 'status', 0, '2019-08-06 03:13:29'),
(7, 'projects', 'members', 0, '2019-08-06 03:13:19'),
(8, 'projects', 'actions', 1, '2019-08-06 03:13:13'),
(9, 'projects', 'list_view', 1, '2019-08-06 03:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `relation_type`, `relation`, `data`) VALUES
(1, 'lead', 11, '[\"test\"]'),
(2, 'lead', 12, '[]'),
(3, 'lead', 13, '[]');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task_number` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `priority` int(5) DEFAULT NULL,
  `assigned` int(5) DEFAULT NULL,
  `created` datetime NOT NULL,
  `startdate` date NOT NULL,
  `duedate` date DEFAULT NULL,
  `datefinished` datetime NOT NULL,
  `addedfrom` int(5) NOT NULL,
  `status_id` int(5) NOT NULL DEFAULT '0',
  `relation` int(5) DEFAULT NULL,
  `relation_type` varchar(30) DEFAULT NULL,
  `public` tinyint(1) DEFAULT '0',
  `billable` tinyint(1) DEFAULT '0',
  `billed` tinyint(1) DEFAULT '0',
  `hourly_rate` decimal(20,2) DEFAULT '0.00',
  `milestone` int(5) DEFAULT '0',
  `visible` tinyint(1) DEFAULT '0',
  `timer` int(5) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `task_number`, `name`, `description`, `priority`, `assigned`, `created`, `startdate`, `duedate`, `datefinished`, `addedfrom`, `status_id`, `relation`, `relation_type`, `public`, `billable`, `billed`, `hourly_rate`, `milestone`, `visible`, `timer`) VALUES
(1, NULL, 'Install Wordpress', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 1, 1, '2018-06-01 20:51:46', '2018-06-01', '2019-06-14', '0000-00-00 00:00:00', 1, 4, 1, 'project', 1, 1, 0, '10.00', 1, 1, 0),
(2, NULL, 'Design Frontend', 'InnoDB Issues with the database, Need to solve the issue.', 2, 2, '2019-04-15 19:20:21', '2019-04-15', '2019-06-15', '0000-00-00 00:00:00', 1, 2, 1, 'project', 1, 0, 0, '0.00', 2, 1, 0),
(3, NULL, 'Coding PHP backend', 'Customer needs to provide clarifications', 2, 4, '2019-04-15 20:13:26', '2019-04-15', '2019-04-30', '0000-00-00 00:00:00', 1, 3, 1, 'project', 1, 1, 0, '15.00', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tasktimer`
--

CREATE TABLE `tasktimer` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `start` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `end` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `timed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tasktimer`
--

INSERT INTO `tasktimer` (`id`, `status`, `task_id`, `staff_id`, `start`, `end`, `timed`, `note`, `project_id`) VALUES
(14, 1, 1, 1, '2018-06-01 20:59', '2018-06-01 21:01', '2018-06-01 17:59:25', 'test', 1),
(15, 0, 2, 1, '2019-04-15 20:13:47', '2019-04-15 13:58:30', '2019-04-15 17:13:47', 'Frontend Completed', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ticketreplies`
--

CREATE TABLE `ticketreplies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `message` text,
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ticketreplies`
--

INSERT INTO `ticketreplies` (`id`, `ticket_id`, `staff_id`, `name`, `contact_id`, `date`, `message`, `attachment`) VALUES
(1, 1, 1, 'Lance Bogrol', 23, '2018-01-23 02:07:45', 'Hi, how can i help you?', ''),
(2, 1, 1, 'Sue', 23, '2018-02-01 08:04:27', 'Hi i have a question.', ''),
(3, 2, 1, 'Lance Bogrol', 14, '2018-07-12 05:22:36', 'Test', ''),
(4, 3, 1, 'Sue', 23, '2018-08-02 20:52:45', 'Sample', ''),
(5, 3, 1, 'Sue', 23, '2018-08-02 20:53:19', 'Tets', '');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(100) DEFAULT NULL,
  `contact_id` int(5) NOT NULL DEFAULT '0',
  `customer_id` int(5) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `department_id` int(5) NOT NULL,
  `priority` enum('1','2','3') NOT NULL,
  `status_id` enum('1','2','3','4') NOT NULL,
  `relation` varchar(100) DEFAULT NULL,
  `relation_id` int(11) DEFAULT NULL,
  `subject` varchar(300) NOT NULL,
  `message` text,
  `date` datetime NOT NULL,
  `lastreply` datetime DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `staff_id` int(5) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_number`, `contact_id`, `customer_id`, `email`, `department_id`, `priority`, `status_id`, `relation`, `relation_id`, `subject`, `message`, `date`, `lastreply`, `attachment`, `staff_id`) VALUES
(1, NULL, 23, 17, 'sue@example.com', 3, '3', '4', NULL, NULL, 'Hi Please Help Me', 'Hi could you help me?', '2018-01-23 14:06:46', '2018-02-01 08:04:27', '', 1),
(2, NULL, 14, 0, NULL, 3, '1', '3', NULL, NULL, 'Sample Ticket', 'Sample ticket detail.', '2018-01-23 15:47:56', '2018-07-12 17:22:36', '', 1),
(3, NULL, 23, 17, 'sue@example.com', 3, '3', '1', NULL, NULL, 'Hi, Can you help me?', 'Hi, i need some help.', '2018-06-20 19:23:49', '2018-08-02 20:53:19', '', 3),
(4, NULL, 23, 17, 'sue@example.com', 3, '2', '1', NULL, NULL, 'Zclus Site Similar On siteGround', 'Need help with the website ? ', '2019-04-15 16:49:41', NULL, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE `todo` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `done` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`id`, `description`, `staff_id`, `date`, `done`) VALUES
(2, 'Donec volutpat massa id justo lacinia, quis cursus lorem consectetur.', 1, '2017-08-25 21:17:08', 0),
(5, 'Mauris fringilla tincidunt mi at faucibus.', 1, '2017-08-25 21:17:53', 1),
(6, 'Donec volutpat massa id justo lacinia, quis cursus lorem consectetur.', 2, '2017-08-25 21:19:09', 1),
(7, 'Cras felis elit, vehicula id consectetur eu, cursus vel elit.', 2, '2017-08-25 21:19:17', 1),
(8, 'Vestibulum dolor felis, porta id auctor sollicitudin', 2, '2017-08-25 21:19:27', 0),
(9, 'Maecenas vel ultrices justo, nec consequat ipsum.', 2, '2017-08-25 21:19:35', 0),
(12, 'Test', 1, '2017-12-22 20:12:48', 1),
(13, 'Lorem ipsum dolor sit amet.', 1, '2018-01-21 19:02:18', 0),
(14, 'Sample to-do item.', 1, '2018-10-15 00:44:07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL,
  `contact_id` int(10) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `user_id`, `contact_id`, `created`) VALUES
(1, '46e684b53f71f476b8f152f0cf41ae', 0, 23, '2018-09-26');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `vendor_number` varchar(100) DEFAULT NULL,
  `type` int(5) DEFAULT NULL,
  `created` date NOT NULL,
  `staff_id` int(5) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `namesurname` varchar(255) DEFAULT NULL,
  `groupid` int(5) DEFAULT NULL,
  `taxoffice` varchar(255) DEFAULT NULL,
  `taxnumber` varchar(50) DEFAULT NULL,
  `ssn` varchar(255) DEFAULT NULL,
  `executive` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(50) DEFAULT NULL,
  `country_id` int(5) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web` varchar(255) DEFAULT NULL,
  `risk` int(5) DEFAULT '0',
  `vendor_status_id` int(5) DEFAULT '1' COMMENT '0 = Inactive | 1 = Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vendors_groups`
--

CREATE TABLE `vendors_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_sales`
--

CREATE TABLE `vendor_sales` (
  `id` int(11) NOT NULL,
  `purchase_id` int(5) NOT NULL,
  `status_id` int(5) NOT NULL,
  `vendor_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE `versions` (
  `id` int(11) NOT NULL,
  `versions_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_version` varchar(255) DEFAULT NULL,
  `last_updated` date DEFAULT NULL,
  `is_update_available` tinyint(1) DEFAULT '0',
  `last_checked` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `versions`
--

INSERT INTO `versions` (`id`, `versions_name`, `created_at`, `last_version`, `last_updated`, `is_update_available`, `last_checked`) VALUES
(1, '1.94', '2019-02-26 15:09:08', '1.7', '2019-06-04', 0, '2019-04-18');

-- --------------------------------------------------------

--
-- Table structure for table `webleads`
--

CREATE TABLE `webleads` (
  `id` int(11) NOT NULL,
  `token` text NOT NULL,
  `lead_source` int(5) NOT NULL,
  `lead_status` int(5) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `submit_text` varchar(255) CHARACTER SET latin1 NOT NULL,
  `success_message` text CHARACTER SET latin1 NOT NULL,
  `notification` tinyint(1) NOT NULL DEFAULT '1',
  `duplicate` tinyint(1) NOT NULL DEFAULT '1',
  `assigned_id` int(5) NOT NULL,
  `form_data` longtext CHARACTER SET latin1 NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `webleads`
--

INSERT INTO `webleads` (`id`, `token`, `lead_source`, `lead_status`, `name`, `submit_text`, `success_message`, `notification`, `duplicate`, `assigned_id`, `form_data`, `created`, `status`) VALUES
(1, '041f395da6d63183a9adf42a8bee1023', 1, 1, 'Customer Entry Form', '', 'Succesfully Added!', 1, 1, 1, '[{\"label\":\"Name\",\"type\":\"textfield\",\"key\":\"lName\",\"input\":true,\"tableView\":true},{\"label\":\"E-Mail\",\"type\":\"email\",\"key\":\"lEmail\",\"input\":true,\"tableView\":true},{\"label\":\"Phone\",\"type\":\"number\",\"title\":\"Phone\",\"key\":\"lPhone\",\"input\":true,\"tableView\":true},{\"label\":\"Description\",\"isUploadEnabled\":false,\"type\":\"textarea\",\"key\":\"lDescription\",\"input\":true,\"tableView\":true},{\"label\":\"Submit\",\"type\":\"button\",\"key\":\"lSubmit\",\"input\":true,\"tableView\":true}]', '2019-04-15 20:29:48', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appconfig`
--
ALTER TABLE `appconfig`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branding`
--
ALTER TABLE `branding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customergroups`
--
ALTER TABLE `customergroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `db_backup`
--
ALTER TABLE `db_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `depositcat`
--
ALTER TABLE `depositcat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_template_fields`
--
ALTER TABLE `email_template_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_triggers`
--
ALTER TABLE `event_triggers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expensecat`
--
ALTER TABLE `expensecat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoicestatus`
--
ALTER TABLE `invoicestatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leadssources`
--
ALTER TABLE `leadssources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leadsstatus`
--
ALTER TABLE `leadsstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_modes`
--
ALTER TABLE `payment_modes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_process`
--
ALTER TABLE `pending_process`
  ADD PRIMARY KEY (`process_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productcategories`
--
ALTER TABLE `productcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectmembers`
--
ALTER TABLE `projectmembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectservices`
--
ALTER TABLE `projectservices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recurring`
--
ALTER TABLE `recurring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_permission_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settingname`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_work_plan`
--
ALTER TABLE `staff_work_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_columns`
--
ALTER TABLE `table_columns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasktimer`
--
ALTER TABLE `tasktimer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticketreplies`
--
ALTER TABLE `ticketreplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors_groups`
--
ALTER TABLE `vendors_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_sales`
--
ALTER TABLE `vendor_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webleads`
--
ALTER TABLE `webleads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appconfig`
--
ALTER TABLE `appconfig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branding`
--
ALTER TABLE `branding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `customergroups`
--
ALTER TABLE `customergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `db_backup`
--
ALTER TABLE `db_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `depositcat`
--
ALTER TABLE `depositcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `email_template_fields`
--
ALTER TABLE `email_template_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `event_triggers`
--
ALTER TABLE `event_triggers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expensecat`
--
ALTER TABLE `expensecat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `invoicestatus`
--
ALTER TABLE `invoicestatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `leadssources`
--
ALTER TABLE `leadssources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leadsstatus`
--
ALTER TABLE `leadsstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1156;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_modes`
--
ALTER TABLE `payment_modes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pending_process`
--
ALTER TABLE `pending_process`
  MODIFY `process_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `productcategories`
--
ALTER TABLE `productcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `projectmembers`
--
ALTER TABLE `projectmembers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `projectservices`
--
ALTER TABLE `projectservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recurring`
--
ALTER TABLE `recurring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `role_permission_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `staff_work_plan`
--
ALTER TABLE `staff_work_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `table_columns`
--
ALTER TABLE `table_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tasktimer`
--
ALTER TABLE `tasktimer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ticketreplies`
--
ALTER TABLE `ticketreplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `todo`
--
ALTER TABLE `todo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors_groups`
--
ALTER TABLE `vendors_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_sales`
--
ALTER TABLE `vendor_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `webleads`
--
ALTER TABLE `webleads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '6', 'login_email', 'login_email', 'new_contact_added');
INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '6', 'login_password', 'login_password', 'new_contact_added');
INSERT INTO `email_templates` (`id`, `relation`, `name`, `subject`, `message`, `from_name`, `status`, `display`, `attachment`) VALUES (NULL, 'appointment', 'new_appointment', 'New Appointment', '<p>Hello {staff_name},<br></p><div>{contact_name} has requested for appointment.<br></div><div><br></div><div>Appointment Date: <strong>{appointment_date}</strong><br></div><div>Appointment Time: <strong>{appointment_time}</strong><strong><br data-mce-bogus="1"></strong></div><div><strong><br data-mce-bogus="1"></strong></div><div>Please contact us for more information.<br></div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', '1', '1', '1');
INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '63', 'staff_name', 'staff_name', 'new_appointment'), (NULL, '63', 'customer_name', 'customer_name', 'new_appointment'), (NULL, '63', 'contact_name', 'contact_name', 'new_appointment'), (NULL, '63', 'appointment_date', 'appointment_date', 'new_appointment'), (NULL, '63', 'appointment_time', 'appointment_time', 'new_appointment'), (NULL, '63', 'company_name', 'company_name', 'new_appointment'), (NULL, '63', 'company_email', 'company_email', 'new_appointment');

ALTER TABLE `settings` CHANGE `unitseparator` `thousand_separator` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'auto', CHANGE `currencyposition` `currency_position` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'auto';
ALTER TABLE `settings` ADD `decimal_separator` VARCHAR(50) NULL DEFAULT 'auto' AFTER `currency_position`, ADD `currency_display` VARCHAR(50) NULL DEFAULT 'code' AFTER `decimal_separator`;

ALTER TABLE `settings` ADD `email_type` TINYINT(1) NULL DEFAULT '1' AFTER `languageid`;

UPDATE `email_templates` SET `relation` = 'staff' WHERE `email_templates`.`name` = 'event_reminder';
UPDATE `email_templates` SET `relation` = 'staff' WHERE `email_templates`.`name` = 'new_appointment';

INSERT INTO `languages` (`id`, `langcode`, `name`, `foldername`) VALUES (NULL, 'bg_BG', 'bulgarian', 'bulgarian');