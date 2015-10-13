-- phpMyAdmin SQL Dump
-- version 3.2.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 26 gen, 2012 at 05:32 PM
-- Versione MySQL: 5.1.44
-- Versione PHP: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `cist_rss`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `cron_activities`
--

CREATE TABLE `cron_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `updated_feeds` text COLLATE utf8_unicode_ci NOT NULL,
  `updated_feeds_number` int(11) NOT NULL,
  `new_feed_items` int(11) NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `feeds`
--

CREATE TABLE `feeds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `analyze` tinyint(1) NOT NULL,
  `url` varchar(250) NOT NULL DEFAULT '',
  `update_interval` int(11) NOT NULL DEFAULT '86400' COMMENT 'in seconds (86400 is one day)',
  `first_update` datetime NOT NULL,
  `last_update` datetime DEFAULT NULL,
  `last_week_feeds` int(11) NOT NULL,
  `average_weekly_feeds` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1= general, 2= international, 3=news',
  `country` varchar(9) NOT NULL,
  `language` varchar(6) NOT NULL DEFAULT 'en' COMMENT 'en=english, fr=french',
  `irregular` tinyint(1) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `feed_items`
--

CREATE TABLE `feed_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL DEFAULT '0',
  `CreatedUniqueID` varchar(250) NOT NULL DEFAULT '',
  `ItemAddedTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ItemTitle` text NOT NULL,
  `ItemDescription` mediumtext NOT NULL,
  `ItemLink` varchar(250) NOT NULL DEFAULT '',
  `ItemPubDate` varchar(250) NOT NULL DEFAULT '',
  `ItemPubDate_t` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ItemGuid` varchar(250) NOT NULL DEFAULT '',
  `ItemAuthor` varchar(250) NOT NULL DEFAULT '',
  `ItemCategory` varchar(250) NOT NULL DEFAULT '',
  `ItemCategoryDomain` varchar(250) NOT NULL DEFAULT '',
  `ItemDCCreator` varchar(255) NOT NULL DEFAULT '',
  `other_fields` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18723 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `feed_items_feed_tags`
--

CREATE TABLE `feed_items_feed_tags` (
  `feed_item_id` int(11) NOT NULL,
  `feed_tag_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `feed_tags`
--

CREATE TABLE `feed_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `feed_updates`
--

CREATE TABLE `feed_updates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL,
  `result` tinyint(1) NOT NULL COMMENT '0: failed, 1:success',
  `new_items` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2307 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;
