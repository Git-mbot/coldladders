-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 06, 2019 at 02:38 PM
-- Server version: 5.7.26-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cs_banks_primary`
--

CREATE TABLE `cs_banks_primary` (
  `steam_id` varchar(32) NOT NULL,
  `cash` int(16) NOT NULL DEFAULT '0',
  `bank` int(16) NOT NULL DEFAULT '0',
  `income` int(16) NOT NULL DEFAULT '0',
  `experience` int(16) NOT NULL DEFAULT '0',
  `respect` int(16) NOT NULL DEFAULT '0',
  `name` text,
  `minutes` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_banks_secondary`
--

CREATE TABLE `cs_banks_secondary` (
  `steam_id` varchar(32) NOT NULL,
  `cash` int(16) NOT NULL DEFAULT '0',
  `bank` int(16) NOT NULL DEFAULT '0',
  `income` int(16) NOT NULL DEFAULT '0',
  `experience` int(16) NOT NULL DEFAULT '0',
  `respect` int(16) NOT NULL DEFAULT '0',
  `name` text,
  `minutes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_elo_primary`
--

CREATE TABLE `cs_elo_primary` (
  `steam_id` varchar(32) NOT NULL,
  `name` text,
  `elo` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_elo_seasons`
--

CREATE TABLE `cs_elo_seasons` (
  `steam_id` varchar(32) NOT NULL,
  `name` text,
  `elo` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL,
  `season` int(1) NOT NULL,
  `rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_elo_secondary`
--

CREATE TABLE `cs_elo_secondary` (
  `steam_id` varchar(32) NOT NULL,
  `name` text,
  `elo` int(11) NOT NULL,
  `kills` int(11) NOT NULL,
  `deaths` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_expresp_ladder`
--

CREATE TABLE `cs_expresp_ladder` (
  `steam_id` varchar(32) NOT NULL,
  `experience` int(11) NOT NULL,
  `respect` int(11) NOT NULL,
  `name` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_itemladder`
--

CREATE TABLE `cs_itemladder` (
  `itemid` int(32) NOT NULL,
  `value` int(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cs_items`
--

CREATE TABLE `cs_items` (
  `steam_id` varchar(32) NOT NULL,
  `item_id` int(11) NOT NULL,
  `itemname` varchar(32) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ranks_economy`
--

CREATE TABLE `ranks_economy` (
  `date` date NOT NULL,
  `steam_id` varchar(32) NOT NULL,
  `cash` int(16) NOT NULL,
  `bank` int(16) NOT NULL,
  `income` int(16) NOT NULL,
  `experience` int(11) NOT NULL,
  `respect` int(11) NOT NULL,
  `name` text,
  `rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
