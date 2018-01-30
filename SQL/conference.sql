-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Úte 30. led 2018, 11:25
-- Verze serveru: 5.7.19
-- Verze PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `conference`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET latin2 COLLATE latin2_czech_cs DEFAULT NULL,
  `description` text CHARACTER SET latin2 COLLATE latin2_czech_cs,
  `file` mediumblob,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_author` int(16) DEFAULT NULL,
  `published` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `content`
--

INSERT INTO `content` (`id`, `title`, `description`, `file`, `added`, `id_author`, `published`) VALUES
(1, 'testovaci title', 'popis asdfasidjaisdjaosdijas', NULL, '2017-12-28 19:36:37', 1, 0),
(2, 'dalsititle', 'asdf', NULL, '2018-01-08 19:14:12', 1, 0),
(8, 'asi nefacha', 'sad', 0x446f6b756d656e74616365326e657732332e706466, '2018-01-22 23:51:07', 1, 0),
(10, 'sdfsad', 'dsfa', 0x446f6b756d656e746163653273732e706466, '2018-01-23 00:13:36', 1, 0),
(11, 'Semestrálka TI', 'taptapdigidagi', 0x446f6b756d656e74616365326e6577322e706466, '2018-01-23 18:39:23', 15, 1),
(12, 'necodalsiho', 'popis', 0x446f6b756d656e7461636532737373612e706466, '2018-01-25 09:46:10', 15, 1),
(13, 'Zkouska', 'snad', 0x446f6b756d656e7461632e706466, '2018-01-30 07:54:01', 15, 0);

-- --------------------------------------------------------

--
-- Struktura tabulky `content_rating`
--

DROP TABLE IF EXISTS `content_rating`;
CREATE TABLE IF NOT EXISTS `content_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_content` int(16) DEFAULT NULL,
  `id_reviewer` int(16) DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score_lang` int(3) DEFAULT NULL,
  `score_appearance` int(3) DEFAULT NULL,
  `score_benefit` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `content_rating`
--

INSERT INTO `content_rating` (`id`, `id_content`, `id_reviewer`, `added`, `score_lang`, `score_appearance`, `score_benefit`) VALUES
(2, 1, 14, '2017-12-28 19:41:11', 2, 3, 1),
(3, 11, 14, '2018-01-29 22:02:19', 1, 1, 1),
(4, 11, 1, '2018-01-29 22:02:19', 1, 1, 1),
(5, 11, 13, '2018-01-29 22:02:19', 5, 2, 1),
(6, 10, 13, '2018-01-30 07:08:30', 2, 3, 1),
(7, 1, 9, '2018-01-30 07:36:28', 4, 3, 2),
(8, 2, 13, '2018-01-30 07:58:51', 3, 4, 2),
(9, 12, 1, '2018-01-30 09:43:05', 3, 2, 4),
(10, 12, 9, '2018-01-30 09:43:07', 5, 2, 1),
(11, 12, 13, '2018-01-30 09:43:09', 1, 1, 1),
(12, 8, 1, '2018-01-30 10:05:36', NULL, NULL, NULL),
(13, 8, 9, '2018-01-30 10:05:39', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `registered_user`
--

DROP TABLE IF EXISTS `registered_user`;
CREATE TABLE IF NOT EXISTS `registered_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET latin2 COLLATE latin2_czech_cs DEFAULT NULL,
  `password` varchar(100) CHARACTER SET latin2 COLLATE latin2_czech_cs DEFAULT NULL,
  `email` varchar(40) CHARACTER SET latin2 COLLATE latin2_czech_cs NOT NULL,
  `role` tinyint(1) DEFAULT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blocked` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Vypisuji data pro tabulku `registered_user`
--

INSERT INTO `registered_user` (`id`, `username`, `password`, `email`, `role`, `registered`, `last_login`, `blocked`) VALUES
(1, 'testovac1', '$2y$10$NwxG/1aPIFlr0px5pMrTLeIRGullaai6onx8y7Owv4fsXYAVZibLK', '', 1, '2017-12-28 19:37:38', '2018-01-30 07:49:12', 0),
(4, 'sdifjosdij', '$2y$11$tKNSTDjyOXr6o8tK90a5U.MyCd0lSvthCxEXGdsKS3FxOugltON1K', '', 0, '2018-01-08 20:40:30', '2018-01-27 22:44:17', 0),
(6, 'test', NULL, '', 0, '2018-01-08 21:42:03', '2018-01-27 22:44:17', 1),
(8, 'jamesblack', '$2y$10$hDKvN2.mEfFvcN7BBmqvg.iIJo6rydt4w4Mda8yGMIcN0enU7BaQS', '', 0, '2018-01-09 14:34:04', '2018-01-27 22:44:17', 0),
(9, 'Ognam222', '$2y$10$FNwDryHJdK1ELuNXjx8GIOPTtDBz6vWPYfG6FzpMd4sGtVQOUI5CW', 'Ognam@seznam.cz', 1, '2018-01-21 14:27:57', '2018-01-30 09:57:49', 0),
(10, 'ognamisko', '$2y$10$j4e/rmCy5NAq.nGnA6C1He2aDqOw5VYMUx.CpZJAT4ygT2AaZ8e2O', 'rododendron', 2, '2018-01-22 22:35:52', '2018-01-27 22:44:17', 0),
(13, 'recenzent', '$2y$10$5UeZ6e8vZ3T9Wty1H.1wpOtLgUx4OwxiyEs1Ik6ADP8wWUZYJhn9S', 'neco@email.cz', 1, '2018-01-29 12:44:11', '2018-01-29 20:17:04', NULL),
(14, 'ognam222', '$2y$10$JYFSa59Fok5s6u76JRS3quPIFxwe3GW.dmJZ/CoLwq7Mb76nY20BK', 'ognam222@hefkej.cz', 1, '2018-01-29 20:19:43', '2018-01-29 20:25:57', NULL),
(15, 'auThor', '$2y$10$FNwDryHJdK1ELuNXjx8GIOPTtDBz6vWPYfG6FzpMd4sGtVQOUI5CW', 'thor@asgard.no', 0, '2018-01-29 20:44:15', '2018-01-29 20:44:15', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
