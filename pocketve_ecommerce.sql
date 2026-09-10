-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 09, 2026 at 01:02 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pocketve_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintable`
--

DROP TABLE IF EXISTS `admintable`;
CREATE TABLE IF NOT EXISTS `admintable` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `uin` varchar(500) DEFAULT NULL,
  `otp` varchar(500) DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL,
  `role` varchar(500) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admintable`
--

INSERT INTO `admintable` (`id`, `timestamp`, `fullname`, `email`, `phone`, `password`, `uin`, `otp`, `status`, `role`, `photo`) VALUES
(1, '2026-03-10 06:38:11', 'Ademola', 'ademolaomomeji@gmail.com', '08160161379', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'NAME3350', '3558', 'Verified', 'Super Admin', '699d8b56d4142.png'),
(2, '2026-02-27 13:43:29', 'Ignatos', 'shoptianah@gmail.com', '9238682072079', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'REG7972', NULL, NULL, 'Manager', NULL),
(3, '2026-02-28 11:39:36', 'Ignatos Omomeji', 'omomejiademola684@gmail.com', '09124605476', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'REG9664', NULL, NULL, 'Admin', '69a2e208b7050.png'),
(4, '2026-02-28 11:46:57', 'Daniel Wilson', 'shopt@gmail.com', '08038171707', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'REG2243', NULL, NULL, 'Manager', '69a2e3c1e7689.png'),
(5, '2026-03-02 00:27:53', 'Vic', 'otaligodspower@gmail.com', '8463522888', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'REG1589', NULL, NULL, 'Admin', '69a4e799ca022.png'),
(6, '2026-03-02 00:32:50', 'Wisdom', 'gaufuiae@gmail.com', '80854553627', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'REG4147', NULL, 'Verified', 'Cashier', '69a4e8c230e41.png'),
(7, '2026-03-07 04:53:35', 'Ademola Omomeji', 'tobaypamilerin@gmail.com', '09124756054', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'NAME6882', '2941', 'Verified', 'Super Admin', '69abbd50d6239.png');

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

DROP TABLE IF EXISTS `admin_table`;
CREATE TABLE IF NOT EXISTS `admin_table` (
  `admin_id` int UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date` date DEFAULT NULL,
  `uin` varchar(500) DEFAULT NULL,
  `otp` varchar(500) DEFAULT NULL,
  `role` varchar(500) DEFAULT NULL,
  `admin_name` varchar(500) DEFAULT NULL,
  `admin_email` varchar(500) DEFAULT NULL,
  `admin_phone` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `otpstatus` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_table`
--

INSERT INTO `admin_table` (`admin_id`, `timestamp`, `date`, `uin`, `otp`, `role`, `admin_name`, `admin_email`, `admin_phone`, `password`, `otpstatus`) VALUES
(1, '2026-05-13 11:59:23', '2026-05-13', 'POCKEADEMP7H9', '2142', 'Admin', 'Ademola Omomeji', 'ademolaomomeji@gmail.com', '08160161379', '*6ED59E20472ED7442CA86508AE55363010AEFA38', 'Verified'),
(2, '2026-05-13 12:56:06', '2026-05-13', 'POCKECHRIAXVD', NULL, 'Admin', 'Christopher Akintoye', 'akintoyechristopher353@gmail.com', '8061997741', '*132F0FA03C57DDA00B02F8F4214C39BC6EF5C190', NULL),
(3, '2026-05-13 12:59:52', '2026-05-13', 'POCKEADMI856X', NULL, 'Admin', 'Admin', 'ayomidealadegba65@gmail.com', '09137982821', '*4EAA2F45B496B1F58F33BC8424F28E323BC6BA48', NULL),
(4, '2026-05-13 13:44:32', '2026-05-13', 'POCKEALABY05P', NULL, 'Admin', 'Alabi Prosper', 'alabi9986@gmail.com', '07030326113', '*CC3B719B678AD531CFD95C4F3BA7B5A5AD190B49', NULL),
(5, '2026-05-13 13:55:22', '2026-05-13', 'POCKEDESTONAC', NULL, 'Admin', 'Destiny Orji', 'Destinyorji309@gmail.com', '07065037569', '*1E396A741CC57E1D2C02A60EB20DE03E32E15986', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bankaccount`
--

DROP TABLE IF EXISTS `bankaccount`;
CREATE TABLE IF NOT EXISTS `bankaccount` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bankname` varchar(500) DEFAULT NULL,
  `accountname` varchar(500) DEFAULT NULL,
  `accountnumber` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
CREATE TABLE IF NOT EXISTS `blog` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date` varchar(500) DEFAULT NULL,
  `staff` varchar(500) DEFAULT NULL,
  `headline` varchar(500) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL,
  `blogimage` varchar(500) DEFAULT NULL,
  `uin` varchar(500) DEFAULT NULL,
  `category` varchar(500) DEFAULT NULL,
  `photocredit` varchar(500) DEFAULT NULL,
  `viewpost` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `timestamp`, `date`, `staff`, `headline`, `content`, `blogimage`, `uin`, `category`, `photocredit`, `viewpost`) VALUES
(1, '2026-08-06 12:19:03', '20/03/26', 'Super Admin', 'The Country in chaos.', '<div>WAGWAN!</div>', 'jay-lee-BkaYLFGnxc8-unsplash_1600x1060.jpg', 'BLOG2003260222281964', 'Politics', 'unsplash', NULL),
(2, '2026-04-08 16:13:26', '8th-April-2026', 'Super Admin', 'FOOD is LIFE', 'What else? Without FOOD, Life is nothing!!!', 'maria_1600_1200.jpg', 'BLOG0804260613262976', 'Food', 'Freepik', NULL),
(3, '2026-04-08 16:44:22', '8th-April-2026', 'Super Admin', 'Apple releases new product.', 'New iPhone 17 now on sale.', 'blake-wisz-Xn5FbEM9564-unsplash_1600x1067.jpg', 'BLOG0804260644224009', 'Technology', 'Freepik', NULL),
(4, '2026-04-24 12:05:57', '24th-April-2026', 'Super Admin', 'CR7 is the GOAT.', '<div>Cristiano Ronaldo is the greatest player alive.</div>', 'natalia-grela-6MM7l0QRBdk-unsplash_1600x1067.jpg', 'BLOG2404260205576170', 'Sports', 'Freepik', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_category`
--

DROP TABLE IF EXISTS `blog_category`;
CREATE TABLE IF NOT EXISTS `blog_category` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blogcategoryname` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `blog_category`
--

INSERT INTO `blog_category` (`id`, `timestamp`, `blogcategoryname`) VALUES
(1, '2026-04-23 15:01:53', 'Technology'),
(2, '2026-04-23 15:06:23', 'Politics'),
(3, '2026-04-23 15:13:03', 'Food'),
(4, '2026-04-23 15:14:47', 'Fashion'),
(5, '2026-04-23 15:15:00', 'Lifestyle'),
(6, '2026-04-24 12:02:02', 'Sports');

-- --------------------------------------------------------

--
-- Table structure for table `blog_images`
--

DROP TABLE IF EXISTS `blog_images`;
CREATE TABLE IF NOT EXISTS `blog_images` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uin` varchar(500) DEFAULT NULL,
  `blog_image` varchar(500) DEFAULT NULL,
  `sort_order` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `categoryname` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `timestamp`, `categoryname`) VALUES
(1, '2026-04-05 05:33:13', 'Food'),
(2, '2026-04-05 05:36:33', 'Shoes'),
(3, '2026-04-05 05:36:58', 'Deodorant'),
(5, '2026-04-05 05:37:27', 'Fashion'),
(6, '2026-04-05 05:37:50', 'Wristwatch'),
(7, '2026-04-05 05:38:05', 'Electronics'),
(8, '2026-04-07 01:09:16', 'Gadgets'),
(9, '2026-04-30 19:36:03', 'Drinks'),
(10, '2026-05-07 10:30:22', 'Wears');

-- --------------------------------------------------------

--
-- Table structure for table `conferenceform`
--

DROP TABLE IF EXISTS `conferenceform`;
CREATE TABLE IF NOT EXISTS `conferenceform` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `gender` varchar(500) DEFAULT NULL,
  `homeaddress` varchar(500) DEFAULT NULL,
  `state` varchar(500) DEFAULT NULL,
  `occupation` varchar(500) DEFAULT NULL,
  `company` varchar(500) DEFAULT NULL,
  `years` varchar(500) DEFAULT NULL,
  `area` varchar(500) DEFAULT NULL,
  `attendance` varchar(500) DEFAULT NULL,
  `ticket` varchar(500) DEFAULT NULL,
  `hear` varchar(500) DEFAULT NULL,
  `interest` varchar(500) DEFAULT NULL,
  `hope` varchar(500) DEFAULT NULL,
  `network` varchar(500) DEFAULT NULL,
  `postevent` varchar(500) DEFAULT NULL,
  `terms` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `conferenceform`
--

INSERT INTO `conferenceform` (`id`, `timestamp`, `fullname`, `phone`, `email`, `gender`, `homeaddress`, `state`, `occupation`, `company`, `years`, `area`, `attendance`, `ticket`, `hear`, `interest`, `hope`, `network`, `postevent`, `terms`) VALUES
(1, '2026-01-23 07:39:28', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'male', 'Akure', ' Ondo', 'Developer', 'Wetin Dey Code Academy', '2 years', 'Property Management', 'In person', 'VIP', ' Referral', 'Tech', '2', 'Yes', 'Yes', 'on'),
(2, '2026-01-23 07:40:48', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'male', 'Akure', ' Ondo', 'Developer', 'Wetin Dey Code Academy', '2 years', 'Property Management', 'In person', 'VIP', ' Referral', 'Tech', '2', 'Yes', 'Yes', 'on'),
(3, '2026-01-23 09:56:58', 'Ademola Omomeji', '2348160161379', 'ademolaomomeji@gmail.com', 'male', 'Akure', ' Ondo', 'Developer', 'Wetin Dey Code Academy', '2 years', 'Property Management', 'In person', 'VIP', ' Referral', 'Real Estate', 'I hope to get more insights and understanding about real estate properly.', 'Yes', 'Yes', 'I agree');

-- --------------------------------------------------------

--
-- Table structure for table `customertable`
--

DROP TABLE IF EXISTS `customertable`;
CREATE TABLE IF NOT EXISTS `customertable` (
  `customer_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `customer_email` varchar(500) DEFAULT NULL,
  `customer_uin` varchar(500) DEFAULT NULL,
  `otp` varchar(500) DEFAULT NULL,
  `fullname` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customertable`
--

INSERT INTO `customertable` (`customer_id`, `timestamp`, `customer_email`, `customer_uin`, `otp`, `fullname`, `phone`, `address`, `password`, `status`, `date`) VALUES
(1, '2026-08-29 01:13:13', 'ademolaomomeji@gmail.com', 'DEE3201240826', '9925', 'Ademola Omomeji', '08160161379', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', '$2y$10$PdXTl0JKkCHqCzlbs0YCceXWbTO/wBtutnqzKHH9McSwlv2yXyH3O', 'Verified', '2026-08-24');

-- --------------------------------------------------------

--
-- Table structure for table `invoiceorder`
--

DROP TABLE IF EXISTS `invoiceorder`;
CREATE TABLE IF NOT EXISTS `invoiceorder` (
  `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `invoicenumber` varchar(500) DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `amount` float DEFAULT NULL,
  `profit` float DEFAULT NULL,
  `productname` varchar(500) DEFAULT NULL,
  `category` varchar(500) DEFAULT NULL,
  `uin` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `customername` varchar(500) DEFAULT NULL,
  `customer_phone` varchar(500) DEFAULT NULL,
  `customer_email` varchar(500) DEFAULT NULL,
  `customer_uin` varchar(500) DEFAULT NULL,
  `paymentstatus` varchar(500) DEFAULT NULL,
  `productimage` varchar(500) DEFAULT NULL,
  `vendor_uin` varchar(500) DEFAULT NULL,
  `item_status` varchar(500) DEFAULT NULL,
  `courier_name` varchar(500) DEFAULT NULL,
  `tracking_number` varchar(500) DEFAULT NULL,
  `vendor_payout_status` varchar(500) DEFAULT NULL,
  `commission_amount` varchar(500) DEFAULT NULL,
  `vendor_netpay` varchar(500) DEFAULT NULL,
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoiceorder`
--

INSERT INTO `invoiceorder` (`product_id`, `timestamp`, `invoicenumber`, `quantity`, `amount`, `profit`, `productname`, `category`, `uin`, `date`, `customername`, `customer_phone`, `customer_email`, `customer_uin`, `paymentstatus`, `productimage`, `vendor_uin`, `item_status`, `courier_name`, `tracking_number`, `vendor_payout_status`, `commission_amount`, `vendor_netpay`) VALUES
(8, '2026-09-05 15:07:06', 'THEADEMOLADEV-0409260604563623', 6, 600, 300, 'Coca-Cola', 'Drinks', 'COCACOLA1UNQ', '2026-09-04', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'DEE3201240826', 'Pending', 'prod_1788226988_0_7420.jpg', 'VNDRTNVLF6', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoicesales`
--

DROP TABLE IF EXISTS `invoicesales`;
CREATE TABLE IF NOT EXISTS `invoicesales` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order_id` varchar(500) DEFAULT NULL,
  `invoicenumber` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `productname` varchar(500) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `profit` varchar(500) DEFAULT NULL,
  `customer_uin` varchar(500) DEFAULT NULL,
  `customername` varchar(500) DEFAULT NULL,
  `customer_phone` varchar(500) DEFAULT NULL,
  `customer_address` varchar(500) DEFAULT NULL,
  `customer_email` varchar(500) DEFAULT NULL,
  `paymentmethod` varchar(500) DEFAULT NULL,
  `receipt` varchar(500) DEFAULT NULL,
  `quantity` varchar(500) DEFAULT NULL,
  `paymentstatus` varchar(500) DEFAULT NULL,
  `ordernote` varchar(500) DEFAULT NULL,
  `bankpaidto` varchar(500) DEFAULT NULL,
  `deliverymethod` varchar(500) DEFAULT NULL,
  `order_status` varchar(500) DEFAULT NULL,
  `courier_name` varchar(500) DEFAULT NULL,
  `tracking_number` varchar(500) DEFAULT NULL,
  `notes` text,
  `vendor_uin` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_type` varchar(100) NOT NULL,
  `payload` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `attempts` int NOT NULL DEFAULT '0',
  `error_log` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status_idx` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_type`, `payload`, `status`, `attempts`, `error_log`, `created_at`, `updated_at`) VALUES
(1, 'send_order_status_email', '{\"customer_email\":\"test@example.com\"}', 'failed', 1, 'Email Error: Could not connect to SMTP host mail.pocketvest.com.ng:465 (Unable to find the socket transport \"ssl\" - did you forget to enable it when you configured PHP?)', '2026-08-08 05:01:13', '2026-08-08 03:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `newform`
--

DROP TABLE IF EXISTS `newform`;
CREATE TABLE IF NOT EXISTS `newform` (
  `id` int UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `newform`
--

INSERT INTO `newform` (`id`, `timestamp`, `fullname`, `email`, `message`) VALUES
(2, '2026-02-09 17:58:14', 'Ademola', 'ademolaomomeji@gmail.com', 'Cbqbi');

-- --------------------------------------------------------

--
-- Table structure for table `opex`
--

DROP TABLE IF EXISTS `opex`;
CREATE TABLE IF NOT EXISTS `opex` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `amount` varchar(500) DEFAULT NULL,
  `vendor` varchar(500) DEFAULT NULL,
  `staff` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `otpver`
--

DROP TABLE IF EXISTS `otpver`;
CREATE TABLE IF NOT EXISTS `otpver` (
  `id` int UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `otp` varchar(500) DEFAULT NULL,
  `uin` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `otpver`
--

INSERT INTO `otpver` (`id`, `timestamp`, `fullname`, `email`, `otp`, `uin`, `password`, `status`, `phone`, `photo`) VALUES
(3, '2026-02-18 09:51:49', 'Ademola', 'ademolaomomeji@gmail.com', '2193', 'NAME8761', '*D9FB334D1F747A1930239EBF1445F2DB75AB81D7', 'Pending', '52699638820', '69921a5397c82.png'),
(4, '2026-02-15 20:38:49', 'Ademola', 'tobaypamilerin@gmail.com', '4800', 'NAME8163', '*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9', 'Pending', '77259206868', '69923cd12eb03.png'),
(5, '2026-03-06 15:26:17', NULL, 'omomejiademola@gmail.com', NULL, 'REG2404', NULL, 'Pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
CREATE TABLE IF NOT EXISTS `personal` (
  `id` int UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `state` varchar(500) DEFAULT NULL,
  `hobbies` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `personal`
--

INSERT INTO `personal` (`id`, `timestamp`, `fullname`, `dob`, `gender`, `phone`, `email`, `address`, `state`, `hobbies`) VALUES
(1, '2026-01-26 08:11:33', 'Ademola Omomeji', '2002-01-03', 'male', '08160161379', 'ademolaomomeji@gmail.com', 'Akure Ondo', ' Ondo', 'traveling,coding,reading'),
(2, '2026-01-29 09:23:14', '', '0000-00-00', '', '', '', '', '', ''),
(3, '2026-01-29 09:23:19', '', '0000-00-00', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uin` varchar(500) DEFAULT NULL,
  `product_image` varchar(500) DEFAULT NULL,
  `sort_order` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `timestamp`, `uin`, `product_image`, `sort_order`) VALUES
(1, '2026-08-27 17:16:10', 'HPLAPTOPHSW6', 'HP LAPTOP_1787850970_1_6790.jpg', 1),
(3, '2026-08-27 17:43:31', 'HPLAPTOPHSW6', 'prod_1787852611_0_8556.jpg', 2),
(4, '2026-08-27 17:53:45', 'HPLAPTOPHSW6', 'prod_1787853225_0_8961.jpg', 3),
(5, '2026-08-28 08:42:43', 'HPLAPTOPCJPQ', 'HP LAPTOP_1787906563_1_8499.jpg', 1),
(6, '2026-08-28 08:42:43', 'HPLAPTOPCJPQ', 'HP LAPTOP_1787906563_2_7149.jpg', 2),
(7, '2026-08-28 11:07:52', 'DRMERTENSYRTW', 'Dr Mertens_1787915272_1_1298.jpg', 1),
(8, '2026-08-28 11:07:52', 'DRMERTENSYRTW', 'Dr Mertens_1787915272_2_3192.jpg', 2),
(10, '2026-09-01 01:43:08', 'COCACOLA1UNQ', 'prod_1788226988_0_7420.jpg', 1),
(11, '2026-09-01 01:43:29', 'COCACOLA1UNQ', 'prod_1788227009_0_9978.jpg', 2),
(12, '2026-09-04 15:03:27', 'BURGERJ584', 'Burger_1788534207_1_1327.jpg', 1),
(13, '2026-09-04 15:03:27', 'BURGERJ584', 'Burger_1788534207_2_1482.jpg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `product_uin` varchar(500) DEFAULT NULL,
  `customer_uin` varchar(500) DEFAULT NULL,
  `customer_name` varchar(500) DEFAULT NULL,
  `rating` varchar(500) DEFAULT NULL,
  `review_title` varchar(500) DEFAULT NULL,
  `review_text` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_table`
--

DROP TABLE IF EXISTS `product_table`;
CREATE TABLE IF NOT EXISTS `product_table` (
  `product_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uin` varchar(500) DEFAULT NULL,
  `productname` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `costprice` float DEFAULT NULL,
  `sellingprice` float DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `lowlevel` varchar(500) DEFAULT NULL,
  `productimage` varchar(500) DEFAULT NULL,
  `profit` float DEFAULT NULL,
  `category` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `vendor_storename` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `vendor_uin` varchar(500) DEFAULT NULL,
  `approval_status` varchar(500) DEFAULT NULL,
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_table`
--

INSERT INTO `product_table` (`product_id`, `timestamp`, `uin`, `productname`, `date`, `costprice`, `sellingprice`, `quantity`, `lowlevel`, `productimage`, `profit`, `category`, `description`, `vendor_storename`, `vendor_uin`, `approval_status`) VALUES
(2, '2026-09-01 00:40:28', 'HPLAPTOPCJPQ', 'HP LAPTOP', '2026-08-28', 265000, 320000, 45, '10', 'HP LAPTOP_1787906563_0_1668.jpg', 55000, 'Gadgets', 'sleek piece.', 'THEADEMOLADEV', 'VNDRTNVLF6', 'Approved'),
(3, '2026-09-01 00:40:28', 'DRMERTENSYRTW', 'Dr Mertens', '2026-08-28', 37000, 45000, 15, '5', 'Dr Mertens_1787915272_0_5186.jpg', 8000, 'Shoes', 'sleek piece!', 'TOBAYE', 'VNDR5WBVDO', 'Approved'),
(4, '2026-09-05 15:07:06', 'COCACOLA1UNQ', 'Coca-Cola', '2026-09-01', 50, 100, 144, '20', 'prod_1788226988_0_7420.jpg', 50, 'Drinks', 'cool stuff.', 'THEADEMOLADEV', 'VNDRTNVLF6', 'Approved'),
(5, '2026-09-04 15:33:10', 'BURGERJ584', 'Burger', '2026-09-04', 70, 100, 50, '12', 'Burger_1788534207_0_4304.jpg', 30, 'Food', 'sleek.', 'TOBAYE', 'VNDR5WBVDO', 'Approved'),
(11, '2026-09-05 17:42:41', 'DEEMALADEGITV', 'La De Perfume', '2026-09-05', 15000, 24000, 19, '6', 'blake-wisz-Xn5FbEM9564-unsplash_1600x1067.jpg', 9000, 'Deodorant', '<div>sleek</div>', NULL, 'REG7031240826013208', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `public`
--

DROP TABLE IF EXISTS `public`;
CREATE TABLE IF NOT EXISTS `public` (
  `id` int UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `state` varchar(500) DEFAULT NULL,
  `hobbies` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `public`
--

INSERT INTO `public` (`id`, `timestamp`, `fullname`, `dob`, `gender`, `phone`, `email`, `address`, `state`, `hobbies`) VALUES
(1, '2026-01-26 08:11:33', 'Ademola Omomeji', '2002-01-03', 'male', '08160161379', 'ademolaomomeji@gmail.com', 'Akure Ondo', ' Ondo', 'traveling,coding,reading'),
(2, '2026-01-29 09:23:14', '', '0000-00-00', '', '', '', '', '', ''),
(3, '2026-01-29 09:23:19', '', '0000-00-00', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `registerform`
--

DROP TABLE IF EXISTS `registerform`;
CREATE TABLE IF NOT EXISTS `registerform` (
  `id` int UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `username` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `state` varchar(500) DEFAULT NULL,
  `uin` varchar(500) DEFAULT NULL,
  `agree` varchar(500) DEFAULT NULL,
  `ipaddress` varchar(500) DEFAULT NULL,
  `device` varchar(500) DEFAULT NULL,
  `picture` varchar(500) DEFAULT NULL,
  `cvupload` varchar(500) DEFAULT NULL,
  `certupload` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registerform`
--

INSERT INTO `registerform` (`id`, `timestamp`, `fullname`, `username`, `email`, `password`, `state`, `uin`, `agree`, `ipaddress`, `device`, `picture`, `cvupload`, `certupload`) VALUES
(3, '2026-03-30 15:35:35', 'Ademola Omomeji', 'ademola_user', 'ademolaomomeji@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', ' Imo', 'THEADADEM913B', 'on', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'THEADADEM913BWhatsApp Image 2025-10-21 at 18.37.07_c012643f.jpg', 'THEADADEM913B100Lsecondsemester.pdf', 'THEADADEM913B300Lfirstsemester.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `resultchecker`
--

DROP TABLE IF EXISTS `resultchecker`;
CREATE TABLE IF NOT EXISTS `resultchecker` (
  `id` int UNSIGNED NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `studentname` varchar(500) DEFAULT NULL,
  `class` varchar(500) DEFAULT NULL,
  `subject2` varchar(500) DEFAULT NULL,
  `test1` varchar(500) DEFAULT NULL,
  `test2` varchar(500) DEFAULT NULL,
  `exam1` varchar(500) DEFAULT NULL,
  `total1` varchar(500) DEFAULT NULL,
  `subject3` varchar(500) DEFAULT NULL,
  `test3` varchar(500) DEFAULT NULL,
  `test4` varchar(500) DEFAULT NULL,
  `exam2` varchar(500) DEFAULT NULL,
  `total2` varchar(500) DEFAULT NULL,
  `subject4` varchar(500) DEFAULT NULL,
  `test5` varchar(500) DEFAULT NULL,
  `test6` varchar(500) DEFAULT NULL,
  `exam3` varchar(500) DEFAULT NULL,
  `total3` varchar(500) DEFAULT NULL,
  `subject5` varchar(500) DEFAULT NULL,
  `test7` varchar(500) DEFAULT NULL,
  `test8` varchar(500) DEFAULT NULL,
  `exam4` varchar(500) DEFAULT NULL,
  `total4` varchar(500) DEFAULT NULL,
  `subject6` varchar(500) DEFAULT NULL,
  `test9` varchar(500) DEFAULT NULL,
  `test10` varchar(500) DEFAULT NULL,
  `exam5` varchar(500) DEFAULT NULL,
  `total5` varchar(500) DEFAULT NULL,
  `subject7` varchar(500) DEFAULT NULL,
  `test11` varchar(500) DEFAULT NULL,
  `test12` varchar(500) DEFAULT NULL,
  `exam6` varchar(500) DEFAULT NULL,
  `total6` varchar(500) DEFAULT NULL,
  `subject8` varchar(500) DEFAULT NULL,
  `test13` varchar(500) DEFAULT NULL,
  `test14` varchar(500) DEFAULT NULL,
  `exam7` varchar(500) DEFAULT NULL,
  `total7` varchar(500) DEFAULT NULL,
  `subject9` varchar(500) DEFAULT NULL,
  `test15` varchar(500) DEFAULT NULL,
  `test16` varchar(500) DEFAULT NULL,
  `exam8` varchar(500) DEFAULT NULL,
  `total8` varchar(500) DEFAULT NULL,
  `subject10` varchar(500) DEFAULT NULL,
  `test17` varchar(500) DEFAULT NULL,
  `test18` varchar(500) DEFAULT NULL,
  `exam9` varchar(500) DEFAULT NULL,
  `total9` varchar(500) DEFAULT NULL,
  `teacher` varchar(500) DEFAULT NULL,
  `principal` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `resultchecker`
--

INSERT INTO `resultchecker` (`id`, `timestamp`, `studentname`, `class`, `subject2`, `test1`, `test2`, `exam1`, `total1`, `subject3`, `test3`, `test4`, `exam2`, `total2`, `subject4`, `test5`, `test6`, `exam3`, `total3`, `subject5`, `test7`, `test8`, `exam4`, `total4`, `subject6`, `test9`, `test10`, `exam5`, `total5`, `subject7`, `test11`, `test12`, `exam6`, `total6`, `subject8`, `test13`, `test14`, `exam7`, `total7`, `subject9`, `test15`, `test16`, `exam8`, `total8`, `subject10`, `test17`, `test18`, `exam9`, `total9`, `teacher`, `principal`) VALUES
(1, '2026-01-31 10:26:58', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '10', '30', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(2, '2026-01-31 10:27:28', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '20', '40', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(3, '2026-01-31 10:38:27', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '20', '40', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(4, '2026-01-31 10:38:51', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '20', '40', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(5, '2026-01-31 10:39:37', 'Ademola', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(6, '2026-01-31 10:41:28', 'Ademola', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(7, '2026-02-03 16:51:26', 'Ignatos', 'SS2', ' English', '16', '18', '55', '89', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(8, '2026-02-03 16:51:55', 'Ignatos', 'SS2', ' English', '16', '18', '55', '89', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(9, '2026-02-03 17:04:05', 'Ignatos', 'SS2', ' English', '16', '18', '55', '89', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(10, '2026-02-03 22:40:39', 'Ademola', 'JSS3', ' Computer Science', '15', '14', '57', '86', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(11, '2026-02-03 22:42:51', 'Ademola', 'JSS3', ' Computer Science', '15', '14', '57', '86', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(12, '2026-02-03 22:44:24', 'Ademola', 'SS2', ' English', '13', '18', '49', '80', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(13, '2026-02-04 06:49:43', 'Ademola', 'SS3', ' English', '10', '19', '49', '78', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(14, '2026-02-04 06:49:52', 'Ademola', 'SS3', ' English', '10', '19', '49', '78', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(15, '2026-02-04 06:50:05', 'Ademola', 'SS3', ' English', '10', '19', '49', '78', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(16, '2026-02-04 20:10:13', 'Ignatos', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(17, '2026-02-04 20:11:20', 'Ignatos', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(18, '2026-02-04 20:12:53', 'Ignatos', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(19, '2026-02-14 14:42:52', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(20, '2026-02-14 14:43:51', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(21, '2026-02-14 14:43:54', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(22, '2026-02-14 14:44:25', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(23, '2026-02-14 14:45:19', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(24, '2026-02-14 14:45:21', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(25, '2026-02-14 14:45:40', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '15', '43', '68', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(26, '2026-02-14 14:46:21', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '15', '43', '68', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(27, '2026-02-14 14:47:25', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '15', '43', '68', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(28, '2026-02-14 14:47:51', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(29, '2026-02-14 14:49:46', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(30, '2026-02-14 14:50:22', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(31, '2026-02-14 14:50:47', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(32, '2026-02-14 14:52:42', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(33, '2026-02-14 14:52:45', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(34, '2026-02-14 14:53:08', 'Ademola Omomeji', 'SS2', 'Mathematics', '10', '15', '44', '69', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(35, '2026-04-01 15:44:06', 'Ademola', 'JSS2', 'Mathematics', '1', '1', '2', '4', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(36, '2026-04-02 14:53:11', 'Ademola', 'JSS1', 'Mathematics', '12', '22', '22', '56', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `stafftable`
--

DROP TABLE IF EXISTS `stafftable`;
CREATE TABLE IF NOT EXISTS `stafftable` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` varchar(500) DEFAULT NULL,
  `uin` varchar(500) DEFAULT NULL,
  `otp` varchar(500) DEFAULT NULL,
  `fullname` varchar(500) DEFAULT NULL,
  `phone` varchar(500) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `role` varchar(500) DEFAULT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stafftable`
--

INSERT INTO `stafftable` (`id`, `timestamp`, `email`, `uin`, `otp`, `fullname`, `phone`, `password`, `role`, `photo`, `status`) VALUES
(1, '2026-08-24 12:32:52', 'ademolaomomeji@gmail.com', 'REG7031240826013208', '5905', 'Ademola Omomeji', '08160161379', '$2y$10$EEWXV/TrrmtpP7cncBOlE.sAhgibstU1niR8CtH8NGVOQRjGvgYqu', 'Super Admin', '6a8c39f3f0b3d.png', 'Verified');

-- --------------------------------------------------------

--
-- Table structure for table `system_setting`
--

DROP TABLE IF EXISTS `system_setting`;
CREATE TABLE IF NOT EXISTS `system_setting` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `business_name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_type` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_setting`
--

INSERT INTO `system_setting` (`id`, `timestamp`, `business_name`, `address`, `phone`, `email`, `country`, `state`, `city`, `business_type`, `status`, `website`) VALUES
(1, '2026-09-09 12:13:23', 'THEADEMOLADEV', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', '08160161379', 'ademolaomomeji@gmail.com', 'Nigeria', 'Jigawa', 'Dutse', 'E-Commerce', 'Active', 'https://ademolathedev.name.ng');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_table`
--

DROP TABLE IF EXISTS `vendor_table`;
CREATE TABLE IF NOT EXISTS `vendor_table` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `vendor_uin` varchar(500) DEFAULT NULL,
  `store_name` varchar(500) DEFAULT NULL,
  `store_slug` varchar(500) DEFAULT NULL,
  `vendor_name` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `vendor_email` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `vendor_phone` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `store_address` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL,
  `logo` varchar(500) DEFAULT NULL,
  `banner` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `bank_name` varchar(500) DEFAULT NULL,
  `account_name` varchar(500) DEFAULT NULL,
  `account_number` varchar(500) DEFAULT NULL,
  `commission_rate` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vendor_table`
--

INSERT INTO `vendor_table` (`id`, `timestamp`, `vendor_uin`, `store_name`, `store_slug`, `vendor_name`, `vendor_email`, `vendor_phone`, `password`, `store_address`, `date`, `status`, `logo`, `banner`, `description`, `bank_name`, `account_name`, `account_number`, `commission_rate`) VALUES
(1, '2026-08-28 09:59:53', 'VNDRTNVLF6', 'THEADEMOLADEV', 'theademoladev', 'Ademola Omomeji', 'ademolaomomeji@gmail.com', '08160161379', '$2y$10$oZbs6rxYs85vV3QdD3sfBOEx90ZFr0Unq/QC65mfn1D3M3naE4xXO', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', '2026-08-25', 'Active', 'logo_VNDRTNVLF6_1787691421.png', 'banner_VNDRTNVLF6_1787910340.jpg', 'We deal with tech savvy equipments that help improve your productivity as a person.', NULL, NULL, NULL, NULL),
(2, '2026-09-01 00:00:40', 'VNDR5WBVDO', 'TOBAYE', 'tobaye', 'Tobay Pamilerin', 'tobaypamilerin@gmail.com', '09124756054', '$2y$10$9A0fGmc0ODud8l6Us7BbX.muGuIkyhs24e80GwT..dkaPGA0LBile', 'Akure, Ondo State', '2026-08-28', 'Active', 'logo_VNDR5WBVDO_1787912331.png', 'banner_VNDR5WBVDO_1787912331.jpg', 'work work work!!!', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

DROP TABLE IF EXISTS `wishlist`;
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `customer_uin` varchar(500) DEFAULT NULL,
  `product_uin` varchar(500) DEFAULT NULL,
  `productname` varchar(500) DEFAULT NULL,
  `product_image` varchar(500) DEFAULT NULL,
  `price` varchar(500) DEFAULT NULL,
  `quantity` varchar(500) DEFAULT NULL,
  `category` varchar(500) DEFAULT NULL,
  `sellingprice` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `timestamp`, `customer_uin`, `product_uin`, `productname`, `product_image`, `price`, `quantity`, `category`, `sellingprice`) VALUES
(2, '2026-08-31 21:02:03', 'DEE3201240826', 'HPLAPTOPCJPQ', 'HP LAPTOP', 'HP LAPTOP_1787906563_0_1668.jpg', NULL, NULL, 'Gadgets', 320000),
(3, '2026-08-31 21:02:06', 'DEE3201240826', 'DRMERTENSYRTW', 'Dr Mertens', 'Dr Mertens_1787915272_0_5186.jpg', NULL, NULL, 'Shoes', 45000),
(7, '2026-09-05 15:29:05', 'DEE3201240826', 'COCACOLA1UNQ', 'Coca-Cola', 'prod_1788226988_0_7420.jpg', NULL, NULL, 'Drinks', 100);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
