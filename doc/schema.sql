-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 05, 2014 at 01:50 PM
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
  `detDate` date NOT NULL,
  `taxonID` int(11) NOT NULL,
  `taxonConf` enum('high','medium','low') NOT NULL,
  `detNotes` varchar(1500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `indivID` (`indivID`),
  KEY `personID` (`personID`),
  KEY `taxonID` (`taxonID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `det`
--

INSERT INTO `det` (`id`, `indivID`, `personID`, `detDate`, `taxonID`, `taxonConf`, `detNotes`) VALUES
(1, 1, 1, '2012-09-16', 1, 'medium', 'Used Flora Malesiana'),
(2, 2, 1, '2012-09-16', 2, 'medium', NULL);

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
  UNIQUE KEY `url` (`filename`),
  KEY `indivID` (`indivID`),
  KEY `personID` (`personID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `img`
--

INSERT INTO `img` (`id`, `indivID`, `personID`, `md5sum`, `filename`, `directory`, `plantpart`, `notes`, `mimetype`) VALUES
(1, 1, 2, '', 'P1070419.400px.jpg', '', NULL, 'Bark', 'image/jpeg'),
(2, 1, 2, '', 'P1070427.400px.jpg', '', NULL, 'Leaf underside.', 'image/jpeg'),
(3, 1, 2, '', 'P1070428.400px.jpg', '', NULL, 'Leaf overside', 'image/jpeg'),
(4, 1, 2, '', 'P1070430.400px.jpg', '', NULL, 'Twig tip', 'image/jpeg'),
(5, 1, 2, '', 'P1070429.400px.jpg', '', NULL, 'Leaf base.', 'image/jpeg'),
(6, 1, 2, '', 'P1070433.400px.jpg', '', NULL, 'Fruits', 'image/jpeg'),
(7, 1, 2, '', 'P1070435.400px.jpg', '', NULL, 'Seed', 'image/jpeg'),
(8, 2, 2, '', 'P1070420.400px.jpg', '', NULL, 'Kulit', 'image/jpeg'),
(9, 2, 2, '', 'P1070454.400px.jpg', '', NULL, 'Bunga, dibela', 'image/jpeg'),
(10, 2, 2, '', 'P1070444.400px.jpg', '', NULL, 'Daun, bawah', 'image/jpeg'),
(11, 2, 2, '', 'P1070445.400px.jpg', '', NULL, 'Daun, atas', 'image/jpeg'),
(12, 2, 2, '', 'P1070448.400px.jpg', '', NULL, NULL, 'image/jpeg'),
(13, 2, 2, '', 'P1070450.400px.jpg', '', NULL, 'Bunga2', 'image/jpeg'),
(14, 2, 2, '', 'P1070443.400px.jpg', '', NULL, 'twig bark', 'image/jpeg'),
(15, 2, 2, '', 'P1070449.400px.jpg', '', NULL, 'underside of base of leaf', 'image/jpeg'),
(16, 2, 2, '', 'P1070455.400px.jpg', '', NULL, 'Flowers from end', 'image/jpeg');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

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
  `locality` varchar(300) NOT NULL COMMENT 'Descriptive name of place',
  `county` varchar(300) DEFAULT NULL COMMENT 'Kabupaten',
  `province` varchar(300) NOT NULL DEFAULT 'Kalimantan Barat',
  `island` varchar(300) NOT NULL DEFAULT 'Borneo',
  `geomorph` varchar(200) DEFAULT NULL COMMENT 'General geomorphology (e.g., rolling hills, swamp, coastal) ',
  `country` varchar(100) NOT NULL DEFAULT 'Indonesia',
  `notes` varchar(500) DEFAULT NULL COMMENT 'Other notes about place',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `locn`
--

INSERT INTO `locn` (`id`, `longitude`, `latitude`, `elev`, `locality`, `county`, `province`, `island`, `geomorph`, `country`, `notes`) VALUES
(1, 109.95228, -1.25001, 20, 'Tanah Merah, Sukadana', 'Kayong Utara', 'Kalimantan Barat', 'Borneo', 'Granite boulders and sloping sandy soil', 'Indonesia', NULL);

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
  `shortcode` varchar(10) NOT NULL COMMENT 'The short code in the spreadsheet',
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `web` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `twitter` (`twitter`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `person`
--

INSERT INTO `person` (`id`, `shortcode`, `name`, `email`, `twitter`, `web`) VALUES
(1, '', 'Cam Webb', 'cwebb@oeb.harvard.edu', '@cmwbb', 'http://camwebb.info'),
(2, '', 'UNKNOWN', 'foo@foo.com', NULL, NULL),
(3, 'joe', 'Joe Bloggs', 'jb@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `taxon`
--

CREATE TABLE IF NOT EXISTS `taxon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` enum('family','genus','species','subspecies') NOT NULL,
  `tmpFam` varchar(100) DEFAULT NULL,
  `plantlistID` varchar(50) DEFAULT NULL,
  `gen` varchar(100) NOT NULL,
  `sp` varchar(100) DEFAULT NULL,
  `spauth` varchar(200) DEFAULT NULL,
  `subtype` enum('var','ssp','forma') DEFAULT NULL,
  `subsp` varchar(100) DEFAULT NULL,
  `subauth` varchar(200) DEFAULT NULL,
  `notes` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genSppSub` (`gen`,`sp`,`spauth`,`subtype`,`subsp`,`subauth`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `taxon`
--

INSERT INTO `taxon` (`id`, `rank`, `tmpFam`, `plantlistID`, `gen`, `sp`, `spauth`, `subtype`, `subsp`, `subauth`, `notes`) VALUES
(1, 'family', 'Myristicaceae', NULL, 'Myristica', 'iners', 'Blume', NULL, NULL, NULL, NULL),
(2, 'family', 'Sapotaceae', NULL, 'Madhuca', 'malaccensis', '(C.B.Clarke) H.J.Lam', NULL, NULL, NULL, NULL),
(3, 'family', 'Dipterocarpaceae', NULL, 'Shorea', 'parvifolia', NULL, NULL, NULL, NULL, NULL),
(4, 'family', 'Dipterocarpaceae', NULL, 'Shorea', 'pauciflora', NULL, NULL, NULL, NULL, NULL);

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
