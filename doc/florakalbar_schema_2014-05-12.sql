-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2014 at 06:39 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `florakalbar`
--

-- --------------------------------------------------------

--
-- Table structure for table `coll`
--

CREATE TABLE IF NOT EXISTS `coll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collCode` varchar(50) NOT NULL,
  `dateColl` date DEFAULT NULL,
  `indivID` int(11) NOT NULL,
  `collReps` int(11) DEFAULT NULL,
  `dnaColl` enum('yes','no') DEFAULT NULL,
  `notes` varchar(1000) DEFAULT NULL,
  `deposit` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `collCode` (`collCode`),
  KEY `indivID` (`indivID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `collector`
--

CREATE TABLE IF NOT EXISTS `collector` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `collID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coll_order` (`collID`,`order`),
  UNIQUE KEY `coll_person` (`collID`,`personID`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `det`
--

CREATE TABLE IF NOT EXISTS `det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indivID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `det_date` date NOT NULL,
  `taxonID` int(11) NOT NULL,
  `confid` enum('high','medium','low') NOT NULL,
  `using` varchar(1000) DEFAULT NULL,
  `notes` varchar(1500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indivID` (`indivID`),
  KEY `personID` (`personID`),
  KEY `taxonID` (`taxonID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `img`
--

CREATE TABLE IF NOT EXISTS `img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indivID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `md5sum` varchar(50) NOT NULL,
  `filename` varchar(200) NOT NULL COMMENT 'Original file name',
  `directory` varchar(500) NOT NULL COMMENT 'Directory structure in zip file',
  `plantpart` enum('whole twig with leaves (and inflorescence)','whole compound leaf','leaf upper surface','leaf lower surface','lower leafbase','leaf axil (w stipules, petiole)','terminal bud','inflorescence','flower/fruit basal view','flower/fruit side view','flower/fruit apical view','flower/fruit longitudinal section','flower/fruit cross section','twig surface','twig cross section','trunk bark') DEFAULT NULL,
  `notes` varchar(300) DEFAULT NULL,
  `mimetype` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_file_person` (`personID`,`filename`),
  KEY `indivID` (`indivID`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Table structure for table `indiv`
--

CREATE TABLE IF NOT EXISTS `indiv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locnID` int(11) NOT NULL,
  `plot` varchar(100) DEFAULT NULL COMMENT 'The unique code for the sample plot (if any)',
  `tag` int(11) DEFAULT NULL COMMENT 'The plant/tree number within the sample plot',
  `personID` int(11) NOT NULL COMMENT 'original record creator',
  PRIMARY KEY (`id`),
  KEY `locnID` (`locnID`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `locn`
--

CREATE TABLE IF NOT EXISTS `locn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longitude` float(9,5) DEFAULT NULL COMMENT 'Longitude in decimal degrees, Datum WGS84',
  `latitude` float(8,5) DEFAULT NULL COMMENT 'Latitude in decimal degrees, Datum WGS84',
  `elev` int(11) DEFAULT NULL COMMENT 'Elevation ASL (m)',
  `geomorph` varchar(200) DEFAULT NULL,
  `locality` varchar(300) NOT NULL COMMENT 'Descriptive name of place',
  `county` varchar(300) DEFAULT NULL COMMENT 'Kabupaten',
  `province` varchar(300) NOT NULL DEFAULT 'Kalimantan Barat',
  `island` varchar(300) NOT NULL DEFAULT 'Borneo',
  `country` varchar(100) NOT NULL DEFAULT 'Indonesia',
  `notes` varchar(500) DEFAULT NULL COMMENT 'Other notes about place',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `obs`
--

CREATE TABLE IF NOT EXISTS `obs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indivID` int(11) NOT NULL,
  `date` date NOT NULL COMMENT 'Date of the observation',
  `personID` int(11) NOT NULL COMMENT 'Person making the observation',
  `microhab` varchar(500) DEFAULT NULL,
  `habit` enum('tree','shrub','liana','herb') NOT NULL,
  `dbh` decimal(10,1) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `bud` enum('no','yes') NOT NULL DEFAULT 'no',
  `flower` enum('no','yes') NOT NULL DEFAULT 'no',
  `fruit` enum('no','yes') NOT NULL DEFAULT 'no',
  `localname` varchar(100) DEFAULT NULL,
  `notes` varchar(300) DEFAULT NULL COMMENT 'General notes about this plant at time of observation',
  `char_lf_insert_alt` tinyint(1) DEFAULT NULL,
  `char_lf_insert_opp` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personID` (`personID`),
  KEY `indivID` (`indivID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE IF NOT EXISTS `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `phone` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `twitter` (`twitter`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `taxon`
--

CREATE TABLE IF NOT EXISTS `taxon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` enum('family','genus','species','subspecies') NOT NULL,
  `morphotype` varchar(100) DEFAULT NULL,
  `fam` varchar(100) DEFAULT NULL,
  `gen` varchar(100) NOT NULL,
  `sp` varchar(100) DEFAULT NULL,
  `subtype` enum('var','ssp','forma') DEFAULT NULL,
  `ssp` varchar(100) DEFAULT NULL,
  `auth` varchar(200) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genSppSub` (`gen`,`sp`,`subtype`,`ssp`,`auth`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coll`
--
ALTER TABLE `coll`
  ADD CONSTRAINT `coll_ibfk_1` FOREIGN KEY (`indivID`) REFERENCES `indiv` (`id`);

--
-- Constraints for table `collector`
--
ALTER TABLE `collector`
  ADD CONSTRAINT `collector_ibfk_1` FOREIGN KEY (`collID`) REFERENCES `coll` (`id`),
  ADD CONSTRAINT `collector_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `person` (`id`);

--
-- Constraints for table `det`
--
ALTER TABLE `det`
  ADD CONSTRAINT `det_ibfk_1` FOREIGN KEY (`indivID`) REFERENCES `indiv` (`id`),
  ADD CONSTRAINT `det_ibfk_3` FOREIGN KEY (`personID`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `det_ibfk_6` FOREIGN KEY (`taxonID`) REFERENCES `taxon` (`id`);

--
-- Constraints for table `img`
--
ALTER TABLE `img`
  ADD CONSTRAINT `img_ibfk_1` FOREIGN KEY (`indivID`) REFERENCES `indiv` (`id`),
  ADD CONSTRAINT `img_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `person` (`id`);

--
-- Constraints for table `indiv`
--
ALTER TABLE `indiv`
  ADD CONSTRAINT `indiv_ibfk_4` FOREIGN KEY (`locnID`) REFERENCES `locn` (`id`);

--
-- Constraints for table `obs`
--
ALTER TABLE `obs`
  ADD CONSTRAINT `obs_ibfk_1` FOREIGN KEY (`indivID`) REFERENCES `indiv` (`id`),
  ADD CONSTRAINT `obs_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `person` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
