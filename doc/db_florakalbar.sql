-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Waktu pembuatan: 05. Februari 2014 jam 07:34
-- Versi Server: 5.5.16
-- Versi PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `florakb_poltek`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `adm_member`
--

CREATE TABLE IF NOT EXISTS `adm_member` (
  `id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `n_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `adm_member`
--

INSERT INTO `adm_member` (`id`, `level`, `name`, `email`, `username`, `password`, `salt`, `n_status`) VALUES
(1, 1, 'ovan', 'ovan89@gmail.com', 'ovan89@gmail.com', '12345', '12345', 1);


--
-- Struktur dari tabel `florakb_news_content`
--

CREATE TABLE IF NOT EXISTS `florakb_news_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `brief` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `thumbnailimage` varchar(100) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `articletype` int(11) NOT NULL,
  `tags` text NOT NULL,
  `createdate` datetime NOT NULL,
  `postdate` datetime NOT NULL,
  `expiredate` datetime NOT NULL,
  `fromwho` int(11) NOT NULL,
  `authorid` int(11) NOT NULL,
  `n_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `florakb_news_content`
--

INSERT INTO `florakb_news_content` (`id`, `title`, `brief`, `content`, `image`, `thumbnailimage`, `categoryid`, `articletype`, `tags`, `createdate`, `postdate`, `expiredate`, `fromwho`, `authorid`, `n_status`) VALUES
(1, 'tessss', 'ada', 'sadasa', 'lorem-ipsum.jpg', 'lorem-ipsum.jpg', 0, 0, 'qweqwewq', '2013-12-01 00:00:00', '2013-12-01 00:00:00', '2013-12-07 00:00:00', 0, 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `florakb_news_content_category`
--

CREATE TABLE IF NOT EXISTS `florakb_news_content_category` (
  `id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `value` varchar(100) NOT NULL,
  `n_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `florakb_news_content_type`
--

CREATE TABLE IF NOT EXISTS `florakb_news_content_type` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `n_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_activity`
--

CREATE TABLE IF NOT EXISTS `code_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `activityId` int(11) NOT NULL,
  `activityValue` varchar(50) NOT NULL,
  `n_status` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `code_activity`
--

INSERT INTO `code_activity` (`id`, `activityId`, `activityValue`, `n_status`) VALUES
(1, 1, 'surf', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_activity_log`
--

CREATE TABLE IF NOT EXISTS `code_activity_log` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `activityId` int(11) NOT NULL,
  `activityDesc` varchar(250) NOT NULL,
  `source` varchar(20) NOT NULL,
  `datetimes` datetime NOT NULL,
  `n_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `code_url_redirect`
--

CREATE TABLE IF NOT EXISTS `code_url_redirect` (
  `id` int(11) NOT NULL,
  `articleId` int(11) DEFAULT NULL,
  `shortUrl` varchar(100) DEFAULT NULL,
  `friendlyUrl` varchar(300) DEFAULT NULL,
  `datetimes` datetime DEFAULT NULL,
  `n_status` int(1) NOT NULL,
  UNIQUE KEY `shortUrl` (`shortUrl`),
  UNIQUE KEY `friendlyUrl` (`friendlyUrl`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struktur dari tabel `user_member`
--

CREATE TABLE IF NOT EXISTS `user_member` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(46) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `verified_date` datetime NOT NULL,
  `img` varchar(200) DEFAULT NULL COMMENT 'GIID Image',
  `image_profile` varchar(200) NOT NULL,
  `username` varchar(46) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `sex` varchar(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `description` text,
  `middle_name` varchar(46) DEFAULT NULL,
  `last_name` varchar(46) DEFAULT NULL,
  `StreetName` varchar(150) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `n_status` int(3) NOT NULL DEFAULT '0' COMMENT ' pending , approved, verified, rejected , deleted ( 7 day ), deactivated ( kill my self )',
  `login_count` int(11) NOT NULL DEFAULT '0',
  `verified` tinyint(3) DEFAULT '0' COMMENT '0->no hp blm verified, 1->sudah verified.',
  `usertype` int(11) NOT NULL COMMENT '0:online;1:offline;2;existing',
  `salt` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `user_member`
--

INSERT INTO `user_member` (`id`, `name`, `nickname`, `email`, `register_date`, `verified_date`, `img`, `image_profile`, `username`, `last_login`, `city`, `sex`, `birthday`, `description`, `middle_name`, `last_name`, `StreetName`, `phone_number`, `n_status`, `login_count`, `verified`, `usertype`, `salt`, `password`) VALUES
(1, 'admin', 'admin', NULL, '2014-01-20 05:37:14', '0000-00-00 00:00:00', NULL, '', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 0, 1, '1234567890', 'ebf95c3f793174665fd929f01597df7738f574c0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
