-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2026 at 07:04 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintable`
--

CREATE TABLE IF NOT EXISTS `admintable` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `admintable`
--

INSERT INTO `admintable` (`id`, `timestamp`, `fullname`, `email`, `phone`, `password`, `uin`, `otp`, `status`, `role`, `photo`) VALUES
(1, '2026-03-10 07:38:11', 'Ademola', 'ademolaomomeji@gmail.com', '08160161379', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'NAME3350', '3558', 'Verified', 'Super Admin', '699d8b56d4142.png'),
(2, '2026-02-27 14:43:29', 'Ignatos', 'shoptianah@gmail.com', '9238682072079', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'REG7972', NULL, NULL, 'Manager', NULL),
(3, '2026-02-28 12:39:36', 'Ignatos Omomeji', 'omomejiademola684@gmail.com', '09124605476', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'REG9664', NULL, NULL, 'Admin', '69a2e208b7050.png'),
(4, '2026-02-28 12:46:57', 'Daniel Wilson', 'shopt@gmail.com', '08038171707', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'REG2243', NULL, NULL, 'Manager', '69a2e3c1e7689.png'),
(5, '2026-03-02 01:27:53', 'Vic', 'otaligodspower@gmail.com', '8463522888', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'REG1589', NULL, NULL, 'Admin', '69a4e799ca022.png'),
(6, '2026-03-02 01:32:50', 'Wisdom', 'gaufuiae@gmail.com', '80854553627', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'REG4147', NULL, 'Verified', 'Cashier', '69a4e8c230e41.png'),
(7, '2026-03-07 05:53:35', 'Ademola Omomeji', 'tobaypamilerin@gmail.com', '09124756054', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'NAME6882', '2941', 'Verified', 'Super Admin', '69abbd50d6239.png');

-- --------------------------------------------------------

--
-- Table structure for table `admin_table`
--

CREATE TABLE IF NOT EXISTS `admin_table` (
  `admin_id` int(225) unsigned NOT NULL,
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
(1, '2026-05-13 12:59:23', '2026-05-13', 'POCKEADEMP7H9', '2142', 'Admin', 'Ademola Omomeji', 'ademolaomomeji@gmail.com', '08160161379', '*6ED59E20472ED7442CA86508AE55363010AEFA38', 'Verified'),
(2, '2026-05-13 13:56:06', '2026-05-13', 'POCKECHRIAXVD', NULL, 'Admin', 'Christopher Akintoye', 'akintoyechristopher353@gmail.com', '8061997741', '*132F0FA03C57DDA00B02F8F4214C39BC6EF5C190', NULL),
(3, '2026-05-13 13:59:52', '2026-05-13', 'POCKEADMI856X', NULL, 'Admin', 'Admin', 'ayomidealadegba65@gmail.com', '09137982821', '*4EAA2F45B496B1F58F33BC8424F28E323BC6BA48', NULL),
(4, '2026-05-13 14:44:32', '2026-05-13', 'POCKEALABY05P', NULL, 'Admin', 'Alabi Prosper', 'alabi9986@gmail.com', '07030326113', '*CC3B719B678AD531CFD95C4F3BA7B5A5AD190B49', NULL),
(5, '2026-05-13 14:55:22', '2026-05-13', 'POCKEDESTONAC', NULL, 'Admin', 'Destiny Orji', 'Destinyorji309@gmail.com', '07065037569', '*1E396A741CC57E1D2C02A60EB20DE03E32E15986', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bankaccount`
--

CREATE TABLE IF NOT EXISTS `bankaccount` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bankname` varchar(500) DEFAULT NULL,
  `accountname` varchar(500) DEFAULT NULL,
  `accountnumber` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `blog`
--

INSERT INTO `blog` (`id`, `timestamp`, `date`, `staff`, `headline`, `content`, `blogimage`, `uin`, `category`, `photocredit`, `viewpost`) VALUES
(1, '2026-03-20 13:22:28', '20/03/26', 'Super Admin', 'The Country in chaos.', '<div>wagwan. wagmi.</div>', 'jay-lee-BkaYLFGnxc8-unsplash_1600x1060.jpg', 'BLOG2003260222281964', 'Politics', 'unsplash', NULL),
(2, '2026-04-08 17:13:26', '8th-April-2026', 'Super Admin', 'FOOD is LIFE', 'What else? Without FOOD, Life is nothing!!!', 'maria_1600_1200.jpg', 'BLOG0804260613262976', 'Food', 'Freepik', NULL),
(3, '2026-04-08 17:44:22', '8th-April-2026', 'Super Admin', 'Apple releases new product.', 'New iPhone 17 now on sale.', 'blake-wisz-Xn5FbEM9564-unsplash_1600x1067.jpg', 'BLOG0804260644224009', 'Technology', 'Freepik', NULL),
(4, '2026-04-24 13:05:57', '24th-April-2026', 'Super Admin', 'CR7 is the GOAT.', '<div>Cristiano Ronaldo is the greatest player alive.</div>', 'natalia-grela-6MM7l0QRBdk-unsplash_1600x1067.jpg', 'BLOG2404260205576170', 'Sports', 'Freepik', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_category`
--

CREATE TABLE IF NOT EXISTS `blog_category` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blogcategoryname` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `blog_category`
--

INSERT INTO `blog_category` (`id`, `timestamp`, `blogcategoryname`) VALUES
(1, '2026-04-23 16:01:53', 'Technology'),
(2, '2026-04-23 16:06:23', 'Politics'),
(3, '2026-04-23 16:13:03', 'Food'),
(4, '2026-04-23 16:14:47', 'Fashion'),
(5, '2026-04-23 16:15:00', 'Lifestyle'),
(6, '2026-04-24 13:02:02', 'Sports');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `categoryname` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `timestamp`, `categoryname`) VALUES
(1, '2026-04-05 06:33:13', 'Food'),
(2, '2026-04-05 06:36:33', 'Shoes'),
(3, '2026-04-05 06:36:58', 'Deodorant'),
(5, '2026-04-05 06:37:27', 'Fashion'),
(6, '2026-04-05 06:37:50', 'Wristwatch'),
(7, '2026-04-05 06:38:05', 'Electronics'),
(8, '2026-04-07 02:09:16', 'Gadgets'),
(9, '2026-04-30 20:36:03', 'Drinks'),
(10, '2026-05-07 11:30:22', 'Wears');

-- --------------------------------------------------------

--
-- Table structure for table `conferenceform`
--

CREATE TABLE IF NOT EXISTS `conferenceform` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `conferenceform`
--

INSERT INTO `conferenceform` (`id`, `timestamp`, `fullname`, `phone`, `email`, `gender`, `homeaddress`, `state`, `occupation`, `company`, `years`, `area`, `attendance`, `ticket`, `hear`, `interest`, `hope`, `network`, `postevent`, `terms`) VALUES
(1, '2026-01-23 08:39:28', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'male', 'Akure', ' Ondo', 'Developer', 'Wetin Dey Code Academy', '2 years', 'Property Management', 'In person', 'VIP', ' Referral', 'Tech', '2', 'Yes', 'Yes', 'on'),
(2, '2026-01-23 08:40:48', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'male', 'Akure', ' Ondo', 'Developer', 'Wetin Dey Code Academy', '2 years', 'Property Management', 'In person', 'VIP', ' Referral', 'Tech', '2', 'Yes', 'Yes', 'on'),
(3, '2026-01-23 10:56:58', 'Ademola Omomeji', '2348160161379', 'ademolaomomeji@gmail.com', 'male', 'Akure', ' Ondo', 'Developer', 'Wetin Dey Code Academy', '2 years', 'Property Management', 'In person', 'VIP', ' Referral', 'Real Estate', 'I hope to get more insights and understanding about real estate properly.', 'Yes', 'Yes', 'I agree');

-- --------------------------------------------------------

--
-- Table structure for table `customertable`
--

CREATE TABLE IF NOT EXISTS `customertable` (
  `customer_id` int(225) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `customertable`
--

INSERT INTO `customertable` (`customer_id`, `timestamp`, `customer_email`, `customer_uin`, `otp`, `fullname`, `phone`, `address`, `password`, `status`, `date`) VALUES
(1, '2026-07-22 16:40:13', 'ademolaomomeji@gmail.com', 'DEE5141220726', '2818', 'Ademola Omomeji', '08160161379', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'Verified', '2026-07-22');

-- --------------------------------------------------------

--
-- Table structure for table `invoiceorder`
--

CREATE TABLE IF NOT EXISTS `invoiceorder` (
  `product_id` int(225) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `invoicenumber` varchar(500) DEFAULT NULL,
  `quantity` int(225) unsigned NOT NULL,
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
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=51 ;

--
-- Dumping data for table `invoiceorder`
--

INSERT INTO `invoiceorder` (`product_id`, `timestamp`, `invoicenumber`, `quantity`, `amount`, `profit`, `productname`, `category`, `uin`, `date`, `customername`, `customer_phone`, `customer_email`, `customer_uin`, `paymentstatus`, `productimage`) VALUES
(46, '2026-05-20 09:29:14', 'DEE-2005261007069664', 1, 100, 50, 'Coca-Cola', 'Drinks', 'DEEMACOCAX5GK', '2026-05-20', 'Olawoore Isaac', '09131806671', 'Olawoorejesujuwon09@gmail.com', 'DEE8006200526', 'Paid', 'mae_960_1200.jpg'),
(47, '2026-05-20 09:50:02', 'DEE-2005261049307282', 1, 14999, 3000, 'La De Perfume', 'Deodorant', 'DEEMALADESC82', '2026-05-20', 'Rejoice Eko', '08139610146', 'olakunlerejoice@gmail.com', 'DEE6235200526', 'Pending', 'charlesssss_784_1200.jpg'),
(48, '2026-07-22 16:48:44', 'DEE-2207260540454338', 2, 45998, 10000, 'Nike Airforce 5', 'Shoes', 'DEEMANIKESQCK', '2026-07-22', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'DEE5141220726', 'Pending', 'ryan_800_1200.jpg'),
(49, '2026-07-24 14:42:19', 'DEE-2407260341542145', 1, 23999, 5000, 'LV Portable bag', 'Fashion', 'DEEMALVPOQERO', '2026-07-24', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'DEE5141220726', 'Pending', 'wiser_801_1200.jpg'),
(50, '2026-07-27 12:22:47', 'DEE-2207260540454338', 2, 29998, 6000, 'La De Perfume', 'Deodorant', 'DEEMALADESC82', '2026-07-27', 'Ademola Omomeji', '08160161379', 'ademolaomomeji@gmail.com', 'DEE5141220726', 'Pending', 'charlesssss_784_1200.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `invoicesales`
--

CREATE TABLE IF NOT EXISTS `invoicesales` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order_id` varchar(500) DEFAULT NULL,
  `invoicenumber` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount` varchar(500) DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `invoicesales`
--

INSERT INTO `invoicesales` (`id`, `timestamp`, `order_id`, `invoicenumber`, `date`, `amount`, `profit`, `customer_uin`, `customername`, `customer_phone`, `customer_address`, `customer_email`, `paymentmethod`, `receipt`, `quantity`, `paymentstatus`, `ordernote`, `bankpaidto`, `deliverymethod`, `order_status`, `courier_name`, `tracking_number`, `notes`) VALUES
(1, '2026-05-04 15:46:34', 'ORD-69F8BF2F4A393', 'DEE-2904261133315459', '2026-05-04', '', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', NULL, 'need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(2, '2026-05-04 17:07:47', 'ORD-69F8D213C2AD1', 'DEE-2904261133315459', '2026-05-04', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(3, '2026-05-05 19:05:55', 'ORD-69FA3F37D6B84', 'DEE-0505260803533455', '2026-05-05', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'Akure', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(4, '2026-05-06 08:31:05', 'ORD-69FAFB2E5AB46', 'DEE-0605260917226726', '2026-05-06', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(5, '2026-05-06 09:01:09', 'ORD-69FB02AF72D56', 'DEE-0605260957522593', '2026-05-06', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(6, '2026-05-06 09:08:40', 'ORD-69FB04BD1B8F8', 'DEE-0605261006111847', '2026-05-06', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'Akure', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'Need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(7, '2026-05-06 12:52:49', 'ORD-69FB3947D5282', 'DEE-0605261250154031', '2026-05-06', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'Ibadan', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'Need my chilled drink asap!', NULL, 'Self Delivery', NULL, NULL, NULL, NULL),
(8, '2026-05-06 15:26:57', 'ORD-69FB5D60640C0', 'DEE-0605260417393628', '2026-05-06', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '08160161379', 'Ibadan', 'tobaypamilerin@gmail.com', 'Paystack', NULL, '1', 'Paid', 'Chilled one please.', NULL, 'Self Delivery', NULL, NULL, NULL, NULL),
(9, '2026-05-08 11:49:33', 'ORD-69FDCD4C30AB7', 'DEE-0805261247102483', '2026-05-08', '100', NULL, 'DEE7981290426', 'Tobay Pamilerin', '09124756054', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(10, '2026-05-12 08:37:46', 'ORD-6A02E67C1DFF9', 'DEE-1205260935299080', '2026-05-12', '100', NULL, 'DEE7829080526', 'Ademola Omomeji', '08160161379', 'No 1, Atantuyi layout, Kajola quarters, Akure, Ondo State.', 'ademolaomomeji@gmail.com', 'Paystack', NULL, '1', 'Paid', 'need a chilled one.', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(11, '2026-05-20 09:11:41', 'ORD-6A0D7A0208FC5', 'DEE-2005261002018966', '2026-05-20', '100', NULL, 'DEE4509200526', 'Felix Oladimeji Promise', '08128832695', 'Eyinapata off danuma road, oke aro, Akure, Ondo State.', 'teniola484@gmail.com', 'Paystack', NULL, '1', 'Paid', 'hello', NULL, 'Self Delivery', NULL, NULL, NULL, NULL),
(12, '2026-05-20 09:12:19', 'ORD-6A0D796711BFF', 'DEE-2005261001449671', '2026-05-20', '100', NULL, 'DEE4124200526', 'Ezeh Justine', '09060146860', 'National Open Unversity, Afunbiowo Estate. ', 'ezehjustine428@gmail.com', 'Paystack', NULL, '1', 'Paid', 'Thank you Justine. We love you!', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(13, '2026-05-20 09:19:26', 'ORD-6A0D7B9ECC2D7', 'DEE-2005261011136501', '2026-05-20', '100', NULL, 'DEE3808200526', 'God''s Power Otali', '08074400351', '54a oba adesida road, akure.', 'otaligodspower@gmail.com', 'Paystack', NULL, '1', 'Paid', 'Deliver to my office at Wetin DEY.', NULL, 'Self Delivery', NULL, NULL, NULL, NULL),
(14, '2026-05-20 09:22:36', 'ORD-6A0D7B1B5C4DC', 'DEE-2005261008463253', '2026-05-20', '100', NULL, 'DEE7901200526', 'oluwatimilehin', '09072535561', 'niger', 'falalatimilehin@gmail.com', 'Paystack', NULL, '1', 'Paid', '', NULL, 'Local Pickup', NULL, NULL, NULL, NULL),
(15, '2026-07-22 23:14:54', 'ORD-6A0D7E7EC64A9', 'DEE-2005261007069664', '2026-05-20', '100', NULL, 'DEE8006200526', 'Olawoore Isaac', '09131806671', 'No 1 Road A4 Samakinwa Opposite Lova Boy Street Ondo', 'Olawoorejesujuwon09@gmail.com', 'Paystack', NULL, '1', 'Paid', '', NULL, 'Self Delivery', 'Processing', '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `newform`
--

CREATE TABLE IF NOT EXISTS `newform` (
  `id` int(225) unsigned NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fullname` varchar(500) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `newform`
--

INSERT INTO `newform` (`id`, `timestamp`, `fullname`, `email`, `message`) VALUES
(2, '2026-02-09 18:58:14', 'Ademola', 'ademolaomomeji@gmail.com', 'Cbqbi');

-- --------------------------------------------------------

--
-- Table structure for table `opex`
--

CREATE TABLE IF NOT EXISTS `opex` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `amount` varchar(500) DEFAULT NULL,
  `vendor` varchar(500) DEFAULT NULL,
  `staff` varchar(500) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `otpver`
--

CREATE TABLE IF NOT EXISTS `otpver` (
  `id` int(225) unsigned NOT NULL,
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
(3, '2026-02-18 10:51:49', 'Ademola', 'ademolaomomeji@gmail.com', '2193', 'NAME8761', '*D9FB334D1F747A1930239EBF1445F2DB75AB81D7', 'Pending', '52699638820', '69921a5397c82.png'),
(4, '2026-02-15 21:38:49', 'Ademola', 'tobaypamilerin@gmail.com', '4800', 'NAME8163', '*6BB4837EB74329105EE4568DDA7DC67ED2CA2AD9', 'Pending', '77259206868', '69923cd12eb03.png'),
(5, '2026-03-06 16:26:17', NULL, 'omomejiademola@gmail.com', NULL, 'REG2404', NULL, 'Pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal`
--

CREATE TABLE IF NOT EXISTS `personal` (
  `id` int(225) unsigned NOT NULL,
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
(1, '2026-01-26 09:11:33', 'Ademola Omomeji', '2002-01-03', 'male', '08160161379', 'ademolaomomeji@gmail.com', 'Akure Ondo', ' Ondo', 'traveling,coding,reading'),
(2, '2026-01-29 10:23:14', '', '0000-00-00', '', '', '', '', '', ''),
(3, '2026-01-29 10:23:19', '', '0000-00-00', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `product_table`
--

CREATE TABLE IF NOT EXISTS `product_table` (
  `product_id` int(225) unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uin` varchar(500) DEFAULT NULL,
  `productname` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `costprice` float DEFAULT NULL,
  `sellingprice` float DEFAULT NULL,
  `quantity` int(225) unsigned NOT NULL,
  `lowlevel` varchar(500) DEFAULT NULL,
  `productimage` varchar(500) DEFAULT NULL,
  `profit` float DEFAULT NULL,
  `category` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `staff` varchar(500) DEFAULT NULL,
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `product_table`
--

INSERT INTO `product_table` (`product_id`, `timestamp`, `uin`, `productname`, `date`, `costprice`, `sellingprice`, `quantity`, `lowlevel`, `productimage`, `profit`, `category`, `description`, `staff`) VALUES
(16, '2026-04-11 23:03:47', 'DEEMADRMEIUQG', 'Dr Mertens', '2026-04-01', 27999, 33999, 6, NULL, 'arturo_800_1200.jpg', 6000, 'Shoes', '<div>great kick.</div>', 'Super Admin'),
(17, '2026-04-10 19:56:00', 'DEEMALADESC82', 'La De Perfume', '2026-04-02', 11999, 14999, 4, NULL, 'charlesssss_784_1200.jpg', 3000, 'Deodorant', '<div>great stuff.</div>', 'Super Admin'),
(20, '2026-04-11 23:08:04', 'DEEMALVPOQERO', 'LV Portable bag', '2026-04-02', 18999, 23999, 13, NULL, 'wiser_801_1200.jpg', 5000, 'Fashion', 'great stuff.', 'Super Admin'),
(21, '2026-04-02 22:47:23', 'DEEMAAPPLS1BU', 'Apple Watch', '2026-04-02', 54999, 64999, 5, NULL, 'daniel_960_1200.jpg', 10000, 'Wristwatch', '<div>good watch.</div>', 'Super Admin'),
(22, '2026-04-11 20:55:36', 'DEEMAORAINK14', 'Oraimo Headset', '2026-04-04', 16999, 22999, 7, NULL, 'kiran_794_1200.jpg', 6000, 'Gadgets', '<div>original oraimo headset. limited piece.</div>', 'Super Admin'),
(23, '2026-04-04 19:33:36', 'DEEMACAMEDT4I', 'Camera 4D', '2026-04-04', 449999, 535000, 4, NULL, 'varun_798_1200.jpg', 85001, 'Electronics', 'great piece. sharp one. very new.', 'Super Admin'),
(24, '2026-04-10 20:16:58', 'DEEMANIKESQCK', 'Nike Airforce 5', '2026-04-04', 17999, 22999, 9, NULL, 'ryan_800_1200.jpg', 5000, 'Shoes', '<div>great sneaks. good quality.</div>', 'Super Admin'),
(25, '2026-04-10 19:58:19', 'DEEMAHANDGD7C', 'Hand Bag', '2026-04-06', 144999, 175999, 16, NULL, '5.jpg', 31000, 'Fashion', '<div>good stuff.</div>', 'Super Admin'),
(26, '2026-04-11 23:05:58', 'DEEMAPORTK75U', 'Portable Cross-Bag', '2026-04-06', 17999, 23999, 3, NULL, 'category-10.jpg', 6000, 'Fashion', '<div>great stuff.</div>', 'Super Admin'),
(27, '2026-04-10 20:20:12', 'DEEMABALOKY03', 'Bal Office Bag', '2026-04-06', 25699, 31999, 6, NULL, '10.jpg', 6300, 'Fashion', '<div>great stuff.</div>', 'Super Admin'),
(28, '2026-04-11 23:05:00', 'DEEMASCHO2S5V', 'School Bag', '2026-04-06', 16999, 21999, 3, NULL, '12.jpg', 5000, 'Fashion', '<div>oroginal kids'' school bag.</div>', 'Super Admin'),
(29, '2026-04-11 23:10:34', 'DEEMANEWA4UXF', 'New Age Headset', '2026-04-07', 16499, 24999, 5, NULL, 'headset.jpg', 8500, 'Gadgets', '<div>original headset. limited piece.</div>', 'Super Admin'),
(30, '2026-04-10 20:25:22', 'DEEMAMACB8RYT', 'MacBook Air', '2026-04-07', 449999, 559999, 1, NULL, 'macbook.jpg', 110000, 'Gadgets', '<div>macbook air 2016. sleek asf!</div>', 'Super Admin'),
(31, '2026-04-07 17:27:42', 'DEEMASNEA9IGS', 'Sneaks', '2026-04-07', 13999, 16499, 4, NULL, '2.jpg', 2500, 'Shoes', '<div>great sneaks.</div>', 'Super Admin'),
(32, '2026-04-11 20:21:45', 'DEEMASNEA6BQ1', 'Sneaks 2', '2026-04-07', 12999, 17400, 3, NULL, '1.jpg', 4401, 'Shoes', 'chill sneaks.', 'Super Admin'),
(33, '2026-04-13 21:08:08', 'DEEMABURGWSYA', 'Burger', '2026-04-13', 2799, 3199, 10, NULL, 'chad.jpg', 400, 'Food', 'tasteful burger. impressive shit.', 'Super Admin'),
(36, '2026-05-04 16:32:10', 'DEEMACOCAX5GK', 'Coca-Cola', '2026-05-04', 50, 100, 35, NULL, 'mae_960_1200.jpg', 50, 'Drinks', '<div>chilled mf.</div>', 'Super Admin'),
(37, '2026-05-07 09:28:02', 'DEEMAMACBTVZ7', 'MacBook Pro', '2026-05-07', 649999, 759999, 7, NULL, 'macbook.jfif', 110000, 'Gadgets', '<div>great stuff.</div>', 'Super Admin'),
(38, '2026-05-07 09:34:55', 'DEEMAIJEBJMY9', 'Ijebu Garri', '2026-05-07', 749, 999, 10, NULL, 'garri.jfif', 250, 'Food', '<div>great stuff.</div>', 'Super Admin'),
(39, '2026-05-07 11:31:45', 'DEEMAMANUZLY6', 'Man United Jersey', '2026-05-07', 21999, 27999, 16, NULL, 'unitedjersey.jfif', 6000, 'Wears', '<div>Original Man United Jersey.</div>', 'Super Admin'),
(40, '2026-05-07 11:38:21', 'DEEMAKNIT6BT2', 'Knitted Shirt', '2026-05-07', 14999, 21499, 12, NULL, 'knittedshirt.jfif', 6500, 'Wears', '<div>Hand knitted shirt. Cool stuff.</div>', 'Super Admin'),
(41, '2026-05-07 11:41:07', 'DEEMAACMI8I3R', 'Ac Milan Jersey', '2026-05-07', 21999, 27999, 16, NULL, 'milanjersey.jfif', 6000, 'Wears', '<div>Original AC Milan Jersey. Cool stuff.</div>', 'Super Admin'),
(42, '2026-05-07 11:45:06', 'DEEMABOTTN5PM', 'Bottled Groundnut', '2026-05-07', 1199, 1499, 35, NULL, 'groundnuts.jfif', 300, 'Food', 'Sweet bottled groundnuts.', 'Super Admin'),
(43, '2026-05-07 12:29:18', 'DEEMASAMSFVX7', 'Samsung Galaxy s22', '2026-05-07', 319999, 364999, 7, NULL, 'samsung1.jfif', 45000, 'Gadgets', '<div>Sleek samsung galaxy s22. cool stuff</div>', 'Super Admin'),
(44, '2026-05-07 13:05:32', 'DEEMASAMSV8DL', 'Samsung Galaxy', '2026-05-07', 679999, 719999, 10, NULL, 'samsung2.jfif', 40000, 'Gadgets', '<div>sleek ass phone.</div>', 'Super Admin'),
(45, '2026-05-20 09:19:26', 'DEEMAKULIDAHV', 'KuliKuli Alata', '2026-05-07', 1999, 2799, 14, NULL, 'kulikuli.jfif', 800, 'Food', '<div>peppered kulikuli</div>', 'Super Admin'),
(46, '2026-05-20 09:29:14', 'DEEMADELLA2LE', 'DELL', '2026-05-07', 279999, 319999, 16, NULL, 'dell.jfif', 40000, 'Gadgets', '<div>SLEEK DELL.</div>', 'Super Admin');

-- --------------------------------------------------------

--
-- Table structure for table `public`
--

CREATE TABLE IF NOT EXISTS `public` (
  `id` int(225) unsigned NOT NULL,
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
(1, '2026-01-26 09:11:33', 'Ademola Omomeji', '2002-01-03', 'male', '08160161379', 'ademolaomomeji@gmail.com', 'Akure Ondo', ' Ondo', 'traveling,coding,reading'),
(2, '2026-01-29 10:23:14', '', '0000-00-00', '', '', '', '', '', ''),
(3, '2026-01-29 10:23:19', '', '0000-00-00', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `registerform`
--

CREATE TABLE IF NOT EXISTS `registerform` (
  `id` int(225) unsigned NOT NULL,
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
(3, '2026-03-30 16:35:35', 'Ademola Omomeji', 'ademola_user', 'ademolaomomeji@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', ' Imo', 'THEADADEM913B', 'on', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'THEADADEM913BWhatsApp Image 2025-10-21 at 18.37.07_c012643f.jpg', 'THEADADEM913B100Lsecondsemester.pdf', 'THEADADEM913B300Lfirstsemester.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `resultchecker`
--

CREATE TABLE IF NOT EXISTS `resultchecker` (
  `id` int(225) unsigned NOT NULL,
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
(1, '2026-01-31 11:26:58', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '10', '30', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(2, '2026-01-31 11:27:28', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '20', '40', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(3, '2026-01-31 11:38:27', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '20', '40', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(4, '2026-01-31 11:38:51', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '10', '20', '40', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(5, '2026-01-31 11:39:37', 'Ademola', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(6, '2026-01-31 11:41:28', 'Ademola', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(7, '2026-02-03 17:51:26', 'Ignatos', 'SS2', ' English', '16', '18', '55', '89', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(8, '2026-02-03 17:51:55', 'Ignatos', 'SS2', ' English', '16', '18', '55', '89', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(9, '2026-02-03 18:04:05', 'Ignatos', 'SS2', ' English', '16', '18', '55', '89', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(10, '2026-02-03 23:40:39', 'Ademola', 'JSS3', ' Computer Science', '15', '14', '57', '86', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(11, '2026-02-03 23:42:51', 'Ademola', 'JSS3', ' Computer Science', '15', '14', '57', '86', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(12, '2026-02-03 23:44:24', 'Ademola', 'SS2', ' English', '13', '18', '49', '80', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(13, '2026-02-04 07:49:43', 'Ademola', 'SS3', ' English', '10', '19', '49', '78', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(14, '2026-02-04 07:49:52', 'Ademola', 'SS3', ' English', '10', '19', '49', '78', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(15, '2026-02-04 07:50:05', 'Ademola', 'SS3', ' English', '10', '19', '49', '78', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(16, '2026-02-04 21:10:13', 'Ignatos', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(17, '2026-02-04 21:11:20', 'Ignatos', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(18, '2026-02-04 21:12:53', 'Ignatos', 'SS3', 'Mathematics', '12', '15', '43', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', 'A good student, Keep it up.', 'Fantastic job!'),
(19, '2026-02-14 15:42:52', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(20, '2026-02-14 15:43:51', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(21, '2026-02-14 15:43:54', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(22, '2026-02-14 15:44:25', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(23, '2026-02-14 15:45:19', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(24, '2026-02-14 15:45:21', 'Ademola Omomeji', 'JSS1', 'Mathematics', '10', '15', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(25, '2026-02-14 15:45:40', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '15', '43', '68', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(26, '2026-02-14 15:46:21', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '15', '43', '68', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(27, '2026-02-14 15:47:25', 'Ademola Omomeji', 'SS3', 'Mathematics', '10', '15', '43', '68', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(28, '2026-02-14 15:47:51', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(29, '2026-02-14 15:49:46', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(30, '2026-02-14 15:50:22', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(31, '2026-02-14 15:50:47', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(32, '2026-02-14 15:52:42', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(33, '2026-02-14 15:52:45', 'Ademola Omomeji', 'SS2', 'Mathematics', '16', '9', '45', '70', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(34, '2026-02-14 15:53:08', 'Ademola Omomeji', 'SS2', 'Mathematics', '10', '15', '44', '69', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(35, '2026-04-01 16:44:06', 'Ademola', 'JSS2', 'Mathematics', '1', '1', '2', '4', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', ''),
(36, '2026-04-02 15:53:11', 'Ademola', 'JSS1', 'Mathematics', '12', '22', '22', '56', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '', '', '', '0', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `stafftable`
--

CREATE TABLE IF NOT EXISTS `stafftable` (
  `id` int(225) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `stafftable`
--

INSERT INTO `stafftable` (`id`, `timestamp`, `email`, `uin`, `otp`, `fullname`, `phone`, `password`, `role`, `photo`, `status`) VALUES
(10, '2026-03-16 15:50:12', 'ademolaomomeji@gmail.com', 'REG4018160326044938', '9705', 'Ademola Omomeji', '08160161379', '*C4E4B59963A6AF043FBE4C36C3209771914E8483', 'Super Admin', '69b826b480ee2.png', 'Verified'),
(14, '2026-03-16 20:56:47', 'tobaypamilerin@gmail.com', 'REG5301160326095647', NULL, 'Tobay Pamilerin', '09124756054', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'Staff', NULL, 'Verified');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `job_type` varchar(100) NOT NULL,
  `payload` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `attempts` int(11) NOT NULL DEFAULT 0,
  `error_log` text NULL,
  `created_at` datetime NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
