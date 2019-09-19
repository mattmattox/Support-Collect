-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 19, 2019 at 03:14 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supportcollect`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created`, `modified`) VALUES
(1, 'Fashion', 'Category for anything related to fashion.', '2014-06-01 00:35:07', '2014-05-30 17:34:33'),
(2, 'Electronics', 'Gadgets, drones and more.', '2014-06-01 00:35:07', '2014-05-30 17:34:33'),
(3, 'Motors', 'Motor sports and more', '2014-06-01 00:35:07', '2014-05-30 17:34:54'),
(5, 'Movies', 'Movie products.', '0000-00-00 00:00:00', '2016-01-08 13:27:26'),
(6, 'Books', 'Kindle books, audio books and more.', '0000-00-00 00:00:00', '2016-01-08 13:27:47'),
(13, 'Sports', 'Drop into new winter gear.', '2016-01-09 02:24:24', '2016-01-09 01:24:24'),
(20, 'Home', 'Category for home and garden products!', '2017-02-27 21:50:09', '2017-02-27 20:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category_id`, `created`, `modified`) VALUES
(1, 'LG P880 4X HD', 'My first awesome phone!', '336', 3, '2014-06-01 01:12:26', '2014-05-31 17:12:26'),
(2, 'Google Nexus 4', 'The most awesome phone of 2013!', '299', 2, '2014-06-01 01:12:26', '2014-05-31 17:12:26'),
(3, 'Samsung Galaxy S4', 'How about no?', '600', 3, '2014-06-01 01:12:26', '2014-05-31 17:12:26'),
(6, 'Bench Shirt', 'The best shirt!', '29', 1, '2014-06-01 01:12:26', '2014-05-31 02:12:21'),
(7, 'Lenovo Laptop', 'My business partner.', '399', 2, '2014-06-01 01:13:45', '2014-05-31 02:13:39'),
(8, 'Samsung Galaxy Tab 10.1', 'Good tablet.', '259', 2, '2014-06-01 01:14:13', '2014-05-31 02:14:08'),
(9, 'Spalding Watch', 'My sports watch.', '199', 1, '2014-06-01 01:18:36', '2014-05-31 02:18:31'),
(10, 'Sony Smart Watch', 'The coolest smart watch!', '300', 2, '2014-06-06 17:10:01', '2014-06-05 18:09:51'),
(11, 'Huawei Y300', 'For testing purposes.', '100', 2, '2014-06-06 17:11:04', '2014-06-05 18:10:54'),
(12, 'Abercrombie Lake Arnold Shirt', 'Perfect as gift!', '60', 1, '2014-06-06 17:12:21', '2014-06-05 18:12:11'),
(13, 'Abercrombie Allen Brook Shirt', 'Cool red shirt!', '70', 1, '2014-06-06 17:12:59', '2014-06-05 18:12:49'),
(26, 'Another product Men', 'Awesome product!', '555', 2, '2014-11-22 19:07:34', '2014-11-21 20:07:34'),
(28, 'Wallet Men', 'You can absolutely use this one!', '799', 6, '2014-12-04 21:12:03', '2014-12-03 22:12:03'),
(31, 'Amanda Waller Shirt Men', 'New awesome shirt!', '333', 1, '2014-12-13 00:52:54', '2014-12-12 01:52:54'),
(42, 'Nike Shoes for Men', 'Nike Shoes', '12999', 3, '2015-12-12 06:47:08', '2015-12-12 05:47:08'),
(48, 'Bristol Shoes Men', 'Awesome shoes. Seen in movies.', '999', 5, '2016-01-08 06:36:37', '2016-01-08 05:36:37'),
(60, 'Rolex Watch Men', 'Luxury watch.', '25000', 1, '2016-01-11 15:46:02', '2016-01-11 14:46:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `access_level` varchar(54) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `access_level`, `email`, `password`, `created`, `modified`) VALUES
(9, 'Mikee', 'Dalisay', 'Admin', 'mike@test.com', '$2y$10$DBlhVvsHE6sLXTXQWCeHVe8SO664tGDs3nPtypC77NLmCAEPK3gwa', '2018-09-17 08:16:41', '2019-01-19 02:12:07'),
(12, 'James', 'Harden', 'Customer', 'jame@test.com', '$2y$10$kYP6OZLSsjseQ6IGw.FRCuTkq..Zfc/zXAUwGV4SXhtg8aE6GFiQ.', '2018-09-18 09:52:14', '2019-01-18 14:14:05'),
(14, 'Darwin', 'Dalisay', 'Customer', 'darwin@test.com', '$2y$10$6EIXIZcKxG1Dq.TdeiYyjOeHxiy3K11QC8hWRdWWuQUFXkfUa0i0e', '2019-01-18 01:27:25', '2019-01-18 14:13:53'),
(15, 'LeBron', 'James', 'Customer', 'lebron@test.com', '$2y$10$rdXbT2ySUhdsdpr.oQsgtu7JPL1TAWxAWqtP9vnN1XpM5ZEtwYmAC', '2019-01-18 20:49:49', '2019-01-18 14:13:33'),
(16, 'Pau', 'Gasol', 'Customer', 'pau@test.com', '$2y$10$3KO9jDG8pFyoMSOrtnfGlehTWaCKlW.FXbX6akmgwKxmbz197BCTi', '2019-01-18 20:51:43', '2019-01-18 14:13:19'),
(17, 'Tony', 'Parker', 'Customer', 'tony@test.com', '$2y$10$GcNr.jbRyt5LUyzR2KvdFuM7cXna8WYXHSNJMAU2KgL3lmatezIRW', '2019-01-18 21:36:03', '2019-01-18 14:12:01'),
(18, 'Manu', 'Ginobilli', 'Customer', 'manu@test.com', '$2y$10$6iREh8bpfvlviWNFEJpg/eVT16o/pdI3y92RWyKH6h6ppo89s.CyG', '2019-01-18 21:36:14', '2019-01-18 14:11:39'),
(19, 'Tim', 'Duncan', 'Customer', 'tim@test.com', '$2y$10$QTWoWFqPfu6pxKrpVwxLfOLmMAEzKXzXdQKrmQxhn1CXOUJLJvRY.', '2019-01-18 21:36:47', '2019-01-18 13:36:47'),
(20, 'DeMar', 'DeRozan', 'Customer', 'demar@test.com', '$2y$10$b7Ae5JcqIIUvQLKdQgg0lehYU4v4rFlS7gw6R6YrqahLlGJ86BWiy', '2019-01-18 21:37:01', '2019-01-18 13:37:01'),
(21, 'Derrick', 'White', 'Customer', 'derrick@test.com', '$2y$10$xJHFCV2XsQjiHJuz2Zhcc.EP7pBolv3palVMEHBfaUsiZ0m4uxrf2', '2019-01-18 21:48:59', '2019-01-18 14:11:21'),
(22, 'Rudy', 'Gay', 'Customer', 'rudy@test.com', '$2y$10$SqpjVim/NCGKnNu35djvSesiBqpl6VnJ8FYK9Bn0z1IMxIGWW0p2W', '2019-01-18 21:49:03', '2019-01-18 14:10:59'),
(23, 'Patty', 'Mills', 'Customer', 'patty@test.com', '$2y$10$1yiPpYLz896eSg8eg/9GDeKjkbD44TOkXLseevhdn3AegtMkN3Rwy', '2019-01-18 21:49:03', '2019-01-18 14:11:17'),
(24, 'Drew', 'Eubanks', 'Customer', 'drew@test.com', '$2y$10$RZRxXBbyt44f/yvxo0LUd.ahfM1Mw4TymfbjRtiOeuw4J2WrCieYa', '2019-01-18 21:49:32', '2019-01-18 14:10:16'),
(25, 'Bryn', 'Forbes', 'Customer', 'bryn@test.com', '$2y$10$a7SOct5zCrLUc270spspt.IMrcT6Z.rD1iG5kBVruqRxAXjt24omK', '2019-01-18 21:50:46', '2019-01-18 13:50:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
