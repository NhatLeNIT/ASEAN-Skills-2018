-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 30, 2018 at 09:30 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asc_module2_02`
--

-- --------------------------------------------------------

--
-- Table structure for table `histories`
--

CREATE TABLE `histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `place_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `count` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `histories`
--

INSERT INTO `histories` (`id`, `place_id`, `user_id`, `count`) VALUES
(4, 8, 1, 8),
(5, 5, 1, 20),
(7, 13, 1, 11),
(8, 17, 1, 6),
(10, 4, 1, 3),
(11, 20, 1, 11),
(12, 11, 1, 1),
(13, 14, 1, 1),
(14, 19, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2018_08_26_110348_create_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `type` enum('Attraction','Restaurant') DEFAULT NULL,
  `image_path` varchar(50) DEFAULT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`id`, `name`, `latitude`, `longitude`, `x`, `y`, `type`, `image_path`, `open_time`, `close_time`, `description`) VALUES
(3, 'Wat Pho', 13.7465, 100.493, 248, 574, 'Attraction', 'Wat Pho.jpg', '08:30:00', '18:30:00', 'Walk up to the top of the Golden Mount to see the view around the Grand Palace area and Bangkok old town.  The waiting area of Golden Mount area is quite chill (literally).'),
(4, 'MuseumSiam', 13.7441, 100.494, 271, 627, 'Attraction', 'MuseumSiam.jpg', '10:00:00', '18:00:00', ' The Grand Palace was built by King Rama I when the capital city was moved from Thonburi.  Although the royal family no longer lives in the Grand Palace, it is still used for several official ceremonies.  You need to dress appropriately. (see FAQ www.tuktukhop.com) '),
(5, 'FlowerMarket', 13.7418, 100.497, 343, 678, 'Attraction', 'FlowerMarket.jpg', '00:00:00', '23:00:00', 'Khao San means rice, and Khao San Road was once the largest rice market in Bangkok.  Today, it has transformed into a little tourist town in Bangkok (with a similar feeling to Pattaya and Phuket).  Everything in Khao San Road is geared toward tourists: international food, tattoo shops, clubs, bars hostels, etc.  It is worth a visit, even for Thais.'),
(6, 'Giant Swing', 13.7518, 100.501, 439, 456, 'Attraction', 'Giant Swing.jpg', '00:00:00', '23:00:00', 'You can see the King Rama V monument and Royal Plaza from the front of Ananta Samakom Throne Hall.  King Rama V was one of the greatest kings of Thailand - he founded the first university in Thailand, established sanitary districts, established monthon system (provincial boundaries), etc.'),
(7, 'National Museum', 13.7574, 100.492, 224, 333, 'Attraction', 'National Museum.jpg', '09:00:00', '16:00:00', 'The Marble Temple has a unique architecture.  It was constructed from the leftover Italian marbles from the building of Ananta Samakom Throne Hall .  The marble keeps the temple nice and cool, even in hot weather.  You might recognize this temple from The Amazing Race 9. Photo by Krissanapong Wongsawarng'),
(8, 'KhaoSanRoad', 13.7577, 100.498, 367, 326, 'Attraction', 'KhaoSanRoad.jpg', '00:00:00', '23:00:00', 'Museum Siam  is a relatively new museum with the concept of Play + Learn = Plearn (Plearn in Thai ???Թ = having fun, enjoying the moment).  In addition to edutainment, there are many funky/cool art displays throughout the museum.  Unlike the National Museum and Rattanakosin Exhibition Hall, Museum Siam is very interactive and engaging.'),
(9, 'Rattanakosin Exhibition Hall', 13.748, 100.503, 487, 540, 'Attraction', 'Rattanakosin Exhibition Hall.jpg', '10:00:00', '19:00:00', 'The National Museum  has many important artifacts including the first stone inscription of King Ram Khamhaeng and Rama I\'s chariot.  The museum offers audio tours and a free guided tour in English.'),
(10, 'GoldenMount', 13.7539, 100.507, 583, 410, 'Attraction', 'GoldenMount.jpg', '09:00:00', '19:00:00', 'The Rattanakosin Exhibition Hall  is packed with high-quality exhibitions.  This is a modern museum focusing on the Rattanakosin era (which started when King Rama I (1737-1809) reigned over Thailand).  \nYou need to sign up for a two-hour guided tour, which starts every 20 mins, starting from 10AM.'),
(11, 'MarbleTemple', 13.7666, 100.514, 751, 129, 'Attraction', 'MarbleTemple.jpg', '06:00:00', '17:00:00', 'Tha Maharaj  is a pier situated between three key landmarks (Grand Palace, Thammasat University, and Silapakorn Fine Arts University).  The pier (\"Tha\" in Thai) was recently renovated and is now packed with trendy restaurants.  Also, it?s next to a large amulet market, a unique place to explore.'),
(13, 'Savoey', 13.7466, 100.532, 1182, 571, 'Restaurant', 'Savoey.jpg', '11:00:00', '22:00:00', 'Food heaven where you can find anything from street food to high end Chinese Restaurant'),
(14, 'Krua Khun Kung kitchen', 13.755, 100.486, 80, 386, 'Restaurant', 'Krua Khun Kung kitchen.jpg', '11:00:00', '22:00:00', 'Khun Dang Guay Jub Yuan  serves Vietnamese noodles (different from pho, where the texture of the noodle is chewy).  Try the \"Pork sausage spicy Thai salad\" which is a Thai/Vietnamese fusion dish'),
(16, 'Nang Loeng Market', 13.7591, 100.512, 703, 295, 'Restaurant', 'Nang Loeng Market.jpg', '08:00:00', '16:00:00', 'Situated inside the RTN (Royal Thai Navy) club house (enter from main road, not from Tha Chang pier), Krua Khun Kung  is a Thai restaurant with a home style cooking.  You can find some dishes that cannot be easily found elsewhere, such as \"A.09 Cold dish fried red fish with Thai herbs\".  Enjoy your food right next to Chao Phraya river, seeing boats cruise past Wat Arun'),
(17, 'Swing Bar by ChingCha', 13.7525, 100.504, 511, 441, 'Restaurant', 'Swing Bar by ChingCha.jpg', '17:00:00', '23:00:00', 'Variety of street foods with nostalgic atmosphere.'),
(18, 'Khun Dang Guay Jub Yuan', 13.762, 100.494, 271, 231, 'Restaurant', 'Khun Dang Guay Jub Yuan.jpg', '11:00:00', '21:30:00', 'Raweekanlaya Dining, where you can experience Thai royal authentic cuisine dated back to King Rama 6. The hotel offer Thai dining atmosphere with healthy and natural ingredients from local farmers.  Call to make a reservation on 02 628 5999'),
(19, 'Krua Apsorn', 13.7553, 100.502, 463, 379, 'Restaurant', 'Krua Apsorn.jpg', '10:30:00', '20:00:00', 'More than 45 years of serving authentic Thai cuisine, with inspiration of \"Fresh and Delectable\".  Try Savoey?s signature spring rolls, while sitting outside to enjoy the view of Chao Phraya River.'),
(20, 'Raweekanlaya Dining', 13.7678, 100.505, 535, 103, 'Restaurant', 'Raweekanlaya Dining.jpg', '06:30:00', '21:00:00', 'Swing Bar is a rooftop bar of ChingCha Bangkok Hostel, where you can chill out and enjoy local craft beers, cocktails and\nThai dishes with great views of Giant Swing and Golden Mount.'),
(21, 'asdas1', 342341, 3242340, 1919041, 724293, 'Restaurant', '1535549269AseanSkills2018.png', '22:04:00', '22:05:00', 'afsdaf 1');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `line` int(11) DEFAULT NULL,
  `from_place_id` int(10) UNSIGNED NOT NULL,
  `to_place_id` int(10) UNSIGNED NOT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `status` enum('AVAILABLE','UNAVAILABLE') DEFAULT 'AVAILABLE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `line`, `from_place_id`, `to_place_id`, `departure_time`, `arrival_time`, `distance`, `speed`, `status`) VALUES
(2, 1, 3, 5, '09:18:00', '09:24:00', 6310, 75, 'AVAILABLE'),
(5, 1, 7, 9, '08:47:00', '08:53:00', 7050, 75, 'AVAILABLE'),
(6, 1, 9, 10, '08:55:00', '08:58:00', 3580, 75, 'AVAILABLE'),
(7, 1, 10, 17, '09:00:00', '09:04:00', 4860, 75, 'AVAILABLE'),
(10, 2, 11, 10, '08:52:00', '08:56:00', 4860, 75, 'AVAILABLE'),
(11, 2, 10, 9, '08:58:00', '09:01:00', 3580, 75, 'AVAILABLE'),
(12, 2, 9, 16, '09:03:00', '09:09:00', 7050, 75, 'AVAILABLE'),
(13, 2, 16, 6, '09:11:00', '09:21:00', 11300, 75, 'AVAILABLE'),
(14, 2, 6, 5, '09:23:00', '09:30:00', 8570, 75, 'AVAILABLE'),
(15, 2, 5, 3, '09:32:00', '09:38:00', 6310, 75, 'AVAILABLE'),
(18, 1, 3, 5, '09:48:00', '09:54:00', 6310, 75, 'AVAILABLE'),
(21, 1, 7, 9, '10:17:00', '10:23:00', 7050, 75, 'AVAILABLE'),
(22, 1, 9, 10, '10:25:00', '10:28:00', 3580, 75, 'AVAILABLE'),
(23, 1, 10, 17, '10:30:00', '10:34:00', 4860, 75, 'AVAILABLE'),
(26, 2, 11, 10, '10:22:00', '10:26:00', 4860, 75, 'AVAILABLE'),
(27, 2, 10, 9, '10:28:00', '10:31:00', 3580, 75, 'AVAILABLE'),
(28, 2, 9, 16, '10:33:00', '10:39:00', 7050, 75, 'AVAILABLE'),
(29, 2, 16, 6, '10:41:00', '10:51:00', 11300, 75, 'AVAILABLE'),
(30, 2, 6, 5, '10:53:00', '11:00:00', 8570, 75, 'AVAILABLE'),
(31, 2, 5, 3, '11:02:00', '11:08:00', 6310, 75, 'AVAILABLE'),
(34, 1, 3, 5, '11:18:00', '11:24:00', 6310, 75, 'AVAILABLE'),
(37, 1, 7, 9, '11:47:00', '11:53:00', 7050, 75, 'AVAILABLE'),
(38, 1, 9, 10, '11:55:00', '11:58:00', 3580, 75, 'AVAILABLE'),
(39, 1, 10, 17, '12:00:00', '12:04:00', 4860, 75, 'UNAVAILABLE'),
(42, 2, 11, 10, '11:52:00', '11:56:00', 4860, 75, 'AVAILABLE'),
(43, 2, 10, 9, '11:58:00', '12:01:00', 3580, 75, 'AVAILABLE'),
(44, 2, 9, 16, '12:03:00', '12:09:00', 7050, 75, 'AVAILABLE'),
(45, 2, 16, 6, '12:11:00', '12:21:00', 11300, 75, 'AVAILABLE'),
(46, 2, 6, 5, '12:23:00', '12:30:00', 8570, 75, 'AVAILABLE'),
(47, 2, 5, 3, '12:32:00', '12:38:00', 6310, 75, 'AVAILABLE'),
(50, 1, 3, 5, '12:48:00', '12:54:00', 6310, 75, 'AVAILABLE'),
(53, 1, 7, 9, '13:17:00', '13:23:00', 7050, 75, 'AVAILABLE'),
(54, 1, 9, 10, '13:25:00', '13:28:00', 3580, 75, 'AVAILABLE'),
(55, 1, 10, 17, '13:30:00', '13:34:00', 4860, 75, 'AVAILABLE'),
(58, 2, 11, 10, '13:22:00', '13:26:00', 4860, 75, 'AVAILABLE'),
(59, 2, 10, 9, '13:28:00', '13:31:00', 3580, 75, 'AVAILABLE'),
(60, 2, 9, 16, '13:33:00', '13:39:00', 7050, 75, 'AVAILABLE'),
(61, 2, 16, 6, '13:41:00', '13:51:00', 11300, 75, 'AVAILABLE'),
(62, 2, 6, 5, '13:53:00', '14:00:00', 8570, 75, 'AVAILABLE'),
(63, 2, 5, 3, '14:02:00', '14:08:00', 6310, 75, 'AVAILABLE'),
(66, 1, 3, 5, '14:18:00', '14:24:00', 6310, 75, 'AVAILABLE'),
(69, 1, 7, 9, '14:47:00', '14:53:00', 7050, 75, 'AVAILABLE'),
(70, 1, 9, 10, '14:55:00', '14:58:00', 3580, 75, 'AVAILABLE'),
(71, 1, 10, 17, '15:00:00', '15:04:00', 4860, 75, 'AVAILABLE'),
(74, 2, 11, 10, '14:52:00', '14:56:00', 4860, 75, 'AVAILABLE'),
(75, 2, 10, 9, '14:58:00', '15:01:00', 3580, 75, 'AVAILABLE'),
(76, 2, 9, 16, '15:03:00', '15:09:00', 7050, 75, 'AVAILABLE'),
(77, 2, 16, 6, '15:11:00', '15:21:00', 11300, 75, 'AVAILABLE'),
(78, 2, 6, 5, '15:23:00', '15:30:00', 8570, 75, 'AVAILABLE'),
(79, 2, 5, 3, '15:32:00', '15:38:00', 6310, 75, 'AVAILABLE'),
(82, 1, 3, 5, '15:48:00', '15:54:00', 6310, 75, 'AVAILABLE'),
(85, 1, 7, 9, '16:17:00', '16:23:00', 7050, 75, 'AVAILABLE'),
(86, 1, 9, 10, '16:25:00', '16:28:00', 3580, 75, 'AVAILABLE'),
(87, 1, 10, 17, '16:30:00', '16:34:00', 4860, 75, 'AVAILABLE'),
(90, 2, 11, 10, '16:22:00', '16:26:00', 4860, 75, 'AVAILABLE'),
(91, 2, 10, 9, '16:28:00', '16:31:00', 3580, 75, 'AVAILABLE'),
(92, 2, 9, 16, '16:33:00', '16:39:00', 7050, 75, 'AVAILABLE'),
(93, 2, 16, 6, '16:41:00', '16:51:00', 11300, 75, 'AVAILABLE'),
(94, 2, 6, 5, '16:53:00', '17:00:00', 8570, 75, 'AVAILABLE'),
(95, 2, 5, 3, '17:02:00', '17:08:00', 6310, 75, 'AVAILABLE'),
(98, 1, 3, 5, '17:18:00', '17:24:00', 6310, 75, 'AVAILABLE'),
(101, 1, 7, 9, '17:47:00', '17:53:00', 7050, 75, 'AVAILABLE'),
(102, 1, 9, 10, '17:55:00', '17:58:00', 3580, 75, 'AVAILABLE'),
(103, 1, 10, 17, '18:00:00', '18:04:00', 4860, 75, 'AVAILABLE'),
(106, 2, 11, 10, '17:52:00', '17:56:00', 4860, 75, 'AVAILABLE'),
(107, 2, 10, 9, '17:58:00', '18:01:00', 3580, 75, 'AVAILABLE'),
(108, 2, 9, 16, '18:03:00', '18:09:00', 7050, 75, 'AVAILABLE'),
(109, 2, 16, 6, '18:11:00', '18:21:00', 11300, 75, 'AVAILABLE'),
(110, 2, 6, 5, '18:23:00', '18:30:00', 8570, 75, 'AVAILABLE'),
(111, 2, 5, 3, '18:32:00', '18:38:00', 6310, 75, 'AVAILABLE'),
(114, 1, 3, 5, '18:48:00', '18:54:00', 6310, 75, 'AVAILABLE'),
(117, 1, 7, 9, '19:17:00', '19:23:00', 7050, 75, 'AVAILABLE'),
(118, 1, 9, 10, '19:25:00', '19:28:00', 3580, 75, 'AVAILABLE'),
(119, 1, 10, 17, '19:30:00', '19:34:00', 4860, 75, 'AVAILABLE'),
(122, 2, 11, 10, '19:22:00', '19:26:00', 4860, 75, 'AVAILABLE'),
(123, 2, 10, 9, '19:28:00', '19:31:00', 3580, 75, 'AVAILABLE'),
(124, 2, 9, 16, '19:33:00', '19:39:00', 7050, 75, 'AVAILABLE'),
(125, 2, 16, 6, '19:41:00', '19:51:00', 11300, 75, 'AVAILABLE'),
(126, 2, 6, 5, '19:53:00', '20:00:00', 8570, 75, 'AVAILABLE'),
(127, 2, 5, 3, '20:02:00', '20:08:00', 6310, 75, 'AVAILABLE'),
(130, 1, 3, 5, '20:18:00', '20:24:00', 6310, 75, 'AVAILABLE'),
(133, 1, 7, 9, '20:47:00', '20:53:00', 7050, 75, 'AVAILABLE'),
(134, 1, 9, 10, '20:55:00', '20:58:00', 3580, 75, 'AVAILABLE'),
(135, 1, 10, 17, '21:00:00', '21:04:00', 4860, 75, 'UNAVAILABLE'),
(141, 4, 10, 8, '08:38:00', '08:44:00', 3760, 45, 'AVAILABLE'),
(142, 4, 8, 7, '08:46:00', '08:57:00', 7570, 45, 'AVAILABLE'),
(147, 4, 10, 8, '10:38:00', '10:44:00', 3760, 45, 'AVAILABLE'),
(148, 4, 8, 7, '10:46:00', '10:57:00', 7570, 45, 'AVAILABLE'),
(153, 4, 10, 8, '12:38:00', '12:44:00', 3760, 45, 'AVAILABLE'),
(154, 4, 8, 7, '12:46:00', '12:57:00', 7570, 45, 'AVAILABLE'),
(159, 4, 10, 8, '14:38:00', '14:44:00', 3760, 45, 'AVAILABLE'),
(160, 4, 8, 7, '14:46:00', '14:57:00', 7570, 45, 'AVAILABLE'),
(165, 4, 10, 8, '16:38:00', '16:44:00', 3760, 45, 'AVAILABLE'),
(166, 4, 8, 7, '16:46:00', '16:57:00', 7570, 45, 'AVAILABLE'),
(171, 4, 10, 8, '18:38:00', '18:44:00', 3760, 45, 'AVAILABLE'),
(172, 4, 8, 7, '18:46:00', '18:57:00', 7570, 45, 'AVAILABLE'),
(177, 4, 10, 8, '20:38:00', '20:44:00', 3760, 45, 'AVAILABLE'),
(178, 4, 8, 7, '20:46:00', '20:57:00', 7570, 45, 'AVAILABLE'),
(181, 5, 13, 20, '09:14:00', '09:28:00', 9850, 45, 'AVAILABLE'),
(182, 6, 20, 13, '10:00:00', '10:14:00', 9850, 45, 'AVAILABLE'),
(187, 5, 13, 20, '11:14:00', '11:28:00', 9850, 45, 'AVAILABLE'),
(188, 6, 20, 13, '12:00:00', '12:14:00', 9850, 45, 'AVAILABLE'),
(193, 5, 13, 20, '13:14:00', '13:28:00', 9850, 45, 'AVAILABLE'),
(194, 6, 20, 13, '14:00:00', '14:14:00', 9850, 45, 'AVAILABLE'),
(199, 5, 13, 20, '15:14:00', '15:28:00', 9850, 45, 'AVAILABLE'),
(200, 6, 20, 13, '16:00:00', '16:14:00', 9850, 45, 'AVAILABLE'),
(205, 5, 13, 20, '17:14:00', '17:28:00', 9850, 45, 'AVAILABLE'),
(206, 6, 20, 13, '18:00:00', '18:14:00', 9850, 45, 'AVAILABLE'),
(211, 5, 13, 20, '19:14:00', '19:28:00', 9850, 45, 'AVAILABLE'),
(212, 6, 20, 13, '20:00:00', '20:14:00', 9850, 45, 'AVAILABLE'),
(241, 9, 3, 4, '08:00:00', '08:06:00', 4450, 45, 'AVAILABLE'),
(242, 9, 4, 5, '08:08:00', '08:14:00', 4220, 45, 'AVAILABLE'),
(243, 9, 5, 3, '08:16:00', '08:25:00', 6310, 45, 'AVAILABLE'),
(244, 10, 3, 5, '09:00:00', '09:09:00', 6310, 45, 'AVAILABLE'),
(245, 10, 5, 4, '09:11:00', '09:17:00', 4220, 45, 'AVAILABLE'),
(246, 10, 4, 3, '09:19:00', '09:25:00', 4450, 45, 'AVAILABLE'),
(247, 9, 3, 4, '10:00:00', '10:06:00', 4450, 45, 'AVAILABLE'),
(248, 9, 4, 5, '10:08:00', '10:14:00', 4220, 45, 'AVAILABLE'),
(249, 9, 5, 3, '10:16:00', '10:25:00', 6310, 45, 'AVAILABLE'),
(250, 10, 3, 5, '11:00:00', '11:09:00', 6310, 45, 'AVAILABLE'),
(251, 10, 5, 4, '11:11:00', '11:17:00', 4220, 45, 'AVAILABLE'),
(252, 10, 4, 3, '11:19:00', '11:25:00', 4450, 45, 'AVAILABLE'),
(253, 9, 3, 4, '12:00:00', '12:06:00', 4450, 45, 'AVAILABLE'),
(254, 9, 4, 5, '12:08:00', '12:14:00', 4220, 45, 'AVAILABLE'),
(255, 9, 5, 3, '12:16:00', '12:25:00', 6310, 45, 'AVAILABLE'),
(256, 10, 3, 5, '13:00:00', '13:09:00', 6310, 45, 'AVAILABLE'),
(257, 10, 5, 4, '13:11:00', '13:17:00', 4220, 45, 'AVAILABLE'),
(258, 10, 4, 3, '13:19:00', '13:25:00', 4450, 45, 'AVAILABLE'),
(259, 9, 3, 4, '14:00:00', '14:06:00', 4450, 45, 'AVAILABLE'),
(260, 9, 4, 5, '14:08:00', '14:14:00', 4220, 45, 'AVAILABLE'),
(261, 9, 5, 3, '14:16:00', '14:25:00', 6310, 45, 'AVAILABLE'),
(262, 10, 3, 5, '15:00:00', '15:09:00', 6310, 45, 'AVAILABLE'),
(263, 10, 5, 4, '15:11:00', '15:17:00', 4220, 45, 'AVAILABLE'),
(264, 10, 4, 3, '15:19:00', '15:25:00', 4450, 45, 'AVAILABLE'),
(265, 9, 3, 4, '16:00:00', '16:06:00', 4450, 45, 'AVAILABLE'),
(266, 9, 4, 5, '16:08:00', '16:14:00', 4220, 45, 'AVAILABLE'),
(267, 9, 5, 3, '16:16:00', '16:25:00', 6310, 45, 'AVAILABLE'),
(268, 10, 3, 5, '17:00:00', '17:09:00', 6310, 45, 'AVAILABLE'),
(269, 10, 5, 4, '17:11:00', '17:17:00', 4220, 45, 'AVAILABLE'),
(270, 10, 4, 3, '17:19:00', '17:25:00', 4450, 45, 'AVAILABLE'),
(271, 9, 3, 4, '18:00:00', '18:06:00', 4450, 45, 'AVAILABLE'),
(272, 9, 4, 5, '18:08:00', '18:14:00', 4220, 45, 'AVAILABLE'),
(273, 9, 5, 3, '18:16:00', '18:25:00', 6310, 45, 'AVAILABLE'),
(274, 10, 3, 5, '19:00:00', '19:09:00', 6310, 45, 'AVAILABLE'),
(275, 10, 5, 4, '19:11:00', '19:17:00', 4220, 45, 'AVAILABLE'),
(276, 10, 4, 3, '19:19:00', '19:25:00', 4450, 45, 'AVAILABLE'),
(277, 9, 3, 4, '20:00:00', '20:06:00', 4450, 45, 'AVAILABLE'),
(278, 9, 4, 5, '20:08:00', '20:14:00', 4220, 45, 'AVAILABLE'),
(279, 9, 5, 3, '20:16:00', '20:25:00', 6310, 45, 'AVAILABLE'),
(280, 11, 6, 4, '08:15:00', '08:24:00', 6040, 45, 'AVAILABLE'),
(281, 11, 4, 5, '08:26:00', '08:32:00', 4220, 45, 'AVAILABLE'),
(282, 11, 5, 6, '08:34:00', '08:46:00', 8570, 45, 'AVAILABLE'),
(283, 12, 6, 5, '09:45:00', '09:57:00', 8570, 45, 'AVAILABLE'),
(284, 12, 5, 4, '09:59:00', '10:05:00', 4220, 45, 'AVAILABLE'),
(285, 12, 4, 6, '10:07:00', '10:16:00', 6040, 45, 'AVAILABLE'),
(286, 11, 6, 4, '11:15:00', '11:24:00', 6040, 45, 'AVAILABLE'),
(287, 11, 4, 5, '11:26:00', '11:32:00', 4220, 45, 'AVAILABLE'),
(288, 11, 5, 6, '11:34:00', '11:46:00', 8570, 45, 'AVAILABLE'),
(289, 12, 6, 5, '12:45:00', '12:57:00', 8570, 45, 'AVAILABLE'),
(290, 12, 5, 4, '12:59:00', '13:05:00', 4220, 45, 'AVAILABLE'),
(291, 12, 4, 6, '13:07:00', '13:16:00', 6040, 45, 'AVAILABLE'),
(292, 11, 6, 4, '14:15:00', '14:24:00', 6040, 45, 'AVAILABLE'),
(293, 11, 4, 5, '14:26:00', '14:32:00', 4220, 45, 'AVAILABLE'),
(294, 11, 5, 6, '14:34:00', '14:46:00', 8570, 45, 'AVAILABLE'),
(295, 12, 6, 5, '15:45:00', '15:57:00', 8570, 45, 'AVAILABLE'),
(296, 12, 5, 4, '15:59:00', '16:05:00', 4220, 45, 'AVAILABLE'),
(297, 12, 4, 6, '16:07:00', '16:16:00', 6040, 45, 'AVAILABLE'),
(298, 11, 6, 4, '17:15:00', '17:24:00', 6040, 45, 'AVAILABLE'),
(299, 11, 4, 5, '17:26:00', '17:32:00', 4220, 45, 'AVAILABLE'),
(300, 11, 5, 6, '17:34:00', '17:46:00', 8570, 45, 'AVAILABLE'),
(301, 12, 6, 5, '18:45:00', '18:57:00', 8570, 45, 'AVAILABLE'),
(302, 12, 5, 4, '18:59:00', '19:05:00', 4220, 45, 'AVAILABLE'),
(303, 12, 4, 6, '19:07:00', '19:16:00', 6040, 45, 'AVAILABLE'),
(304, 11, 6, 4, '20:15:00', '20:24:00', 6040, 45, 'AVAILABLE'),
(305, 11, 4, 5, '20:26:00', '20:32:00', 4220, 45, 'AVAILABLE'),
(306, 11, 5, 6, '20:34:00', '20:46:00', 8570, 45, 'AVAILABLE'),
(307, 42, 13, 5, '13:02:00', '13:25:00', 234, 234, 'AVAILABLE');

-- --------------------------------------------------------

--
-- Table structure for table `selections`
--

CREATE TABLE `selections` (
  `id` int(10) UNSIGNED NOT NULL,
  `from_place_id` int(10) UNSIGNED NOT NULL,
  `to_place_id` int(10) UNSIGNED NOT NULL,
  `count` int(10) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `selections`
--

INSERT INTO `selections` (`id`, `from_place_id`, `to_place_id`, `count`) VALUES
(13, 5, 20, 2);

-- --------------------------------------------------------

--
-- Table structure for table `selection_schedules`
--

CREATE TABLE `selection_schedules` (
  `id` int(10) UNSIGNED NOT NULL,
  `selection_id` int(10) UNSIGNED NOT NULL,
  `schedule_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `selection_schedules`
--

INSERT INTO `selection_schedules` (`id`, `selection_id`, `schedule_id`) VALUES
(75, 13, 257),
(76, 13, 258),
(81, 13, 199);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('ADMIN','USER') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `token`, `role`) VALUES
(1, 'admin', '$2y$10$hEc41YU0MHNaVy4aCQhi7.gWjg2/iMElWVZUMW3yrUiC0tuC7.Wfe', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'ADMIN'),
(2, 'user1', '$2y$10$oQtF6Zs.x26AvyRGaUoC.eyPd7p9h63hjoy/JUApxwQkpMc.yE7Nm', NULL, 'USER'),
(3, 'user2', '$2y$10$8OSjPUr.mhYqVlRmP7MLyeri94QL5WUEpTmV5NbCZfSxtQuVIf3e.', NULL, 'USER');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `histories_place_id_foreign` (`place_id`),
  ADD KEY `histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_from_place_id_foreign` (`from_place_id`),
  ADD KEY `schedules_to_place_id_foreign` (`to_place_id`);

--
-- Indexes for table `selections`
--
ALTER TABLE `selections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `selections_from_place_id_foreign` (`from_place_id`),
  ADD KEY `selections_to_place_id_foreign` (`to_place_id`);

--
-- Indexes for table `selection_schedules`
--
ALTER TABLE `selection_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `selection_schedules_selection_id_foreign` (`selection_id`),
  ADD KEY `selection_schedules_schedule_id_foreign` (`schedule_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `histories`
--
ALTER TABLE `histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=308;

--
-- AUTO_INCREMENT for table `selections`
--
ALTER TABLE `selections`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `selection_schedules`
--
ALTER TABLE `selection_schedules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `histories`
--
ALTER TABLE `histories`
  ADD CONSTRAINT `histories_place_id_foreign` FOREIGN KEY (`place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_from_place_id_foreign` FOREIGN KEY (`from_place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedules_to_place_id_foreign` FOREIGN KEY (`to_place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selections`
--
ALTER TABLE `selections`
  ADD CONSTRAINT `selections_from_place_id_foreign` FOREIGN KEY (`from_place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selections_to_place_id_foreign` FOREIGN KEY (`to_place_id`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `selection_schedules`
--
ALTER TABLE `selection_schedules`
  ADD CONSTRAINT `selection_schedules_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `selection_schedules_selection_id_foreign` FOREIGN KEY (`selection_id`) REFERENCES `selections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
