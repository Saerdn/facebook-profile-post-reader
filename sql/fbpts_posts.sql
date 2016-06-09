-- phpMyAdmin SQL Dump
-- version 4.1.13
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 07, 2016 at 06:51 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `fbpts_posts`
--

CREATE TABLE IF NOT EXISTS `fbpts_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profileid` bigint(50) NOT NULL,
  `postid` bigint(50) NOT NULL,
  `profilename` varchar(80) COLLATE utf8_bin NOT NULL,
  `message` varchar(5000) COLLATE utf8_bin NOT NULL,
  `type` varchar(10) COLLATE utf8_bin NOT NULL,
  `profiletype` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT 'player',
  `pictureurl` varchar(1000) COLLATE utf8_bin NOT NULL,
  `postcreationdatetime` datetime NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`profileid`,`postid`),
  KEY `postcreationdatetime` (`postcreationdatetime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=202 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
