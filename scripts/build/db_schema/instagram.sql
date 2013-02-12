-- phpMyAdmin SQL Dump
-- version 3.5.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 06, 2012 at 06:41 AM
-- Server version: 5.1.63-0+squeeze1
-- PHP Version: 5.3.3-7+squeeze14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `instagram`
--

-- --------------------------------------------------------

--
-- Table structure for table `instagram_languages`
--

CREATE TABLE IF NOT EXISTS `instagram_languages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iso_3166‑1` varchar(5) COLLATE utf8_unicode_ci NOT NULL COMMENT 'http://en.wikipedia.org/wiki/ISO_3166-1',
  `iso_639` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT 'locale',
  `friendly_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `native_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `instagram_languages`
--

INSERT INTO `instagram_languages` (`id`, `iso_3166‑1`, `iso_639`, `friendly_name`, `native_name`) VALUES
(1, 'en-us', 'en', 'English (U.S.)', 'English'),
(2, 'de-de', 'de', 'German', 'Deutsch');

-- --------------------------------------------------------

--
-- Table structure for table `instagram_languagesphotos`
--

CREATE TABLE IF NOT EXISTS `instagram_languagesphotos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) unsigned NOT NULL,
  `thumb_url` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `sha-1_hash` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_photos`
--

CREATE TABLE IF NOT EXISTS `instagram_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` bigint(20) unsigned NOT NULL,
  `upload_date` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_phrases`
--

CREATE TABLE IF NOT EXISTS `instagram_phrases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `language_id_2` (`language_id`,`name`),
  KEY `language_id` (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `instagram_phrases`
--

INSERT INTO `instagram_phrases` (`id`, `language_id`, `name`, `text`) VALUES
(1, 1, 'your_account', 'Your Account'),
(2, 1, 'about_us', 'About Us'),
(3, 1, 'support', 'Support'),
(4, 1, 'privacy', 'Privacy'),
(5, 1, 'terms', 'Terms'),
(6, 2, 'your_account', 'Dein Konto'),
(7, 2, 'about_us', 'Über Uns'),
(8, 2, 'terms', 'Nutzungsbedingungen'),
(9, 2, 'privacy', 'Privatsphäre\r\n'),
(10, 1, 'homepage_header', 'Meet Instagram'),
(11, 2, 'homepage_header', 'Instagram stellt sich vor.'),
(12, 2, 'homepage_app_description', '<p>\r\nEs ist ein <b>schneller</b>, <b>schöner</b> und <b>lustiger</b> Weg, Deine Freunde durch Bilder an Deinem Leben teilhaben zu lassen.\r\n</p>\r\n<p>\r\nMache ein Bild mit Deinem iPhone, wähle einen Filter, um das Aussehen und die Stimmung des Bildes zu ändern, sende es zu Facebook, Twitter oder Tumblr –  so einfach ist das! Ein ganz neuer Weg, Deine Bilder zu zeigen.\r\n</p>\r\n<p>\r\nUnd haben wir es schon erwähnt? Es ist kostenlos!\r\n</p>'),
(13, 1, 'homepage_app_description', '<p>\r\nIt’s a <b>fast</b>, <b>beautiful</b> and <b>fun</b> way to share your photos with friends and family.\r\n</p>\r\n<p>\r\nSnap a picture, choose a filter to transform its look and feel, then post to Instagram. Share to Facebook, Twitter, and Tumblr too – it''s as easy as pie. It''s photo sharing, reinvented.\r\n</p>\r\n<p>\r\nOh yeah, did we mention it’s free?\r\n</p>'),
(14, 1, 'self_profile_privacy_notice', 'Your profile is private. Only users who follow you can view this page.'),
(15, 1, 'profile_privacy_notice', 'This profile is private. Only users who follow this user can view this page.');

-- --------------------------------------------------------

--
-- Table structure for table `instagram_site_config`
--

CREATE TABLE IF NOT EXISTS `instagram_site_config` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `instagram_site_config`
--

INSERT INTO `instagram_site_config` (`id`, `name`, `value`, `comment`) VALUES
(1, 'site_name', 'Instagram Clone', NULL),
(2, 'site_default_language', 'en-us', NULL),
(3, 'site_allow_signup', 'true', NULL),
(4, 'site_default_avatar_url', 'http://192.168.2.130/instagram/images/profiles/anonymousUser.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `instagram_users`
--

CREATE TABLE IF NOT EXISTS `instagram_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` text COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `email` text COLLATE utf8_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8_unicode_ci,
  `last_name` text COLLATE utf8_unicode_ci,
  `phone` text COLLATE utf8_unicode_ci,
  `birthday` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'MM-DD-YYYY',
  `sex` enum('male','female') COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8_unicode_ci,
  `website` text COLLATE utf8_unicode_ci,
  `site_language` bigint(20) unsigned DEFAULT '1',
  `avatar_url` text COLLATE utf8_unicode_ci,
  `private_profile` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0' COMMENT 'is the user profile private?',
  `join_date` int(10) unsigned NOT NULL,
  `last_login` int(10) unsigned NOT NULL,
  `last_active` int(10) unsigned NOT NULL,
  `signup_ip` int(10) unsigned NOT NULL,
  `last_ip` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_language` (`site_language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `instagram_users`
--

INSERT INTO `instagram_users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `phone`, `birthday`, `sex`, `bio`, `website`, `site_language`, `avatar_url`, `private_profile`, `join_date`, `last_login`, `last_active`, `signup_ip`, `last_ip`) VALUES
(1, 'demo', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'demo@cloneui.com', 'Demo', 'User', NULL, NULL, NULL, NULL, NULL, 1, NULL, '0', 1354182889, 0, 0, 0, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `instagram_phrases`
--
ALTER TABLE `instagram_phrases`
  ADD CONSTRAINT `instagram_phrases_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `instagram_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `instagram_users`
--
ALTER TABLE `instagram_users`
  ADD CONSTRAINT `instagram_users_ibfk_1` FOREIGN KEY (`site_language`) REFERENCES `instagram_languages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
