-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 07, 2014 at 08:47 AM
-- Server version: 5.5.34-MariaDB-log
-- PHP Version: 5.5.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `flora-kalbar`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `coll`
--

INSERT INTO `coll` (`id`, `collCode`, `dateColl`, `indivID`, `collReps`, `dnaColl`, `notes`, `deposit`) VALUES
(1, 'sdf', NULL, 2, NULL, NULL, NULL, NULL);

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
  UNIQUE KEY `coll_order` (`collID`,`order`) COMMENT 'Only one order number per coll',
  UNIQUE KEY `coll_person` (`collID`,`personID`) COMMENT 'Only one occurrence of person per coll',
  KEY `personID` (`personID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `det`
--

CREATE TABLE IF NOT EXISTS `det` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indivID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `date` date NOT NULL,
  `taxonID` int(11) NOT NULL,
  `confid` enum('high','medium','low') NOT NULL,
  `using` varchar(1000) DEFAULT NULL,
  `notes` varchar(1500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indivID` (`indivID`),
  KEY `personID` (`personID`),
  KEY `taxonID` (`taxonID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `det`
--

INSERT INTO `det` (`id`, `indivID`, `personID`, `date`, `taxonID`, `confid`, `using`, `notes`) VALUES
(1, 1, 1, '2012-09-16', 1, 'medium', NULL, 'Used Flora Malesiana'),
(2, 2, 1, '2012-09-16', 2, 'medium', NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `img`
--

INSERT INTO `img` (`id`, `indivID`, `personID`, `md5sum`, `filename`, `directory`, `plantpart`, `notes`, `mimetype`) VALUES
(17, 1, 1, '', 'IMG_0123.JPG', '', 'whole compound leaf', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `indiv`
--

CREATE TABLE IF NOT EXISTS `indiv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `locnID` int(11) NOT NULL,
  `plot` varchar(100) DEFAULT NULL COMMENT 'The unique code for the sample plot (if any)',
  `tag` int(11) DEFAULT NULL COMMENT 'The plant/tree number within the sample plot',
  PRIMARY KEY (`id`),
  KEY `locnID` (`locnID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `indiv`
--

INSERT INTO `indiv` (`id`, `locnID`, `plot`, `tag`) VALUES
(1, 1, NULL, NULL),
(2, 1, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `locn`
--

INSERT INTO `locn` (`id`, `longitude`, `latitude`, `elev`, `geomorph`, `locality`, `county`, `province`, `island`, `country`, `notes`) VALUES
(1, 109.95228, -1.25001, 20, 'Granite boulders and sloping sandy soil', 'Tanah Merah, Sukadana', 'Kayong Utara', 'Kalimantan Barat', 'Borneo', 'Indonesia', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `name`, `email`, `twitter`, `website`, `phone`) VALUES
(1, 'Cam Webb', 'cwebb@oeb.harvard.edu', '@cmwbb', 'http://camwebb.info', NULL),
(2, 'UNKNOWN', 'foo@foo.com', NULL, NULL, NULL),
(3, 'Joe Bloggs', 'jb@gmail.com', NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `taxon`
--

INSERT INTO `taxon` (`id`, `rank`, `morphotype`, `fam`, `gen`, `sp`, `subtype`, `ssp`, `auth`, `notes`) VALUES
(1, 'family', NULL, 'Myristicaceae', 'Myristica', 'iners', NULL, NULL, NULL, NULL),
(2, 'family', NULL, 'Sapotaceae', 'Madhuca', 'malaccensis', NULL, NULL, NULL, NULL),
(3, 'family', NULL, 'Dipterocarpaceae', 'Shorea', 'parvifolia', NULL, NULL, NULL, NULL),
(4, 'family', NULL, 'Dipterocarpaceae', 'Shorea', 'pauciflora', NULL, NULL, NULL, NULL);

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
  ADD CONSTRAINT `collector_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `collector_ibfk_1` FOREIGN KEY (`collID`) REFERENCES `coll` (`id`);

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
  ADD CONSTRAINT `img_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `img_ibfk_1` FOREIGN KEY (`indivID`) REFERENCES `indiv` (`id`);

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
