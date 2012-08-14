-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 14 Août 2012 à 22:29
-- Version du serveur: 5.5.8-log
-- Version de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `pirox`
--

-- --------------------------------------------------------

--
-- Structure de la table `pirox_keys`
--

CREATE TABLE IF NOT EXISTS `pirox_keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL COMMENT 'Clé d''activation de 32 caractères',
  `skey` varchar(100) NOT NULL COMMENT 'Clé d''activation serveur',
  `validity` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 ou 1, en fonction de l''abonnement activé ou pas',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `pirox_keys`
--

INSERT INTO `pirox_keys` (`id`, `key`, `skey`, `validity`) VALUES
(1, '00000000000000000000000000000000', '0000000000', 0);

-- --------------------------------------------------------

--
-- Structure de la table `pirox_subscriptions`
--

CREATE TABLE IF NOT EXISTS `pirox_subscriptions` (
  `id` int(10) unsigned NOT NULL COMMENT 'Équivaut id de keys',
  `basic` varchar(50) NOT NULL DEFAULT '0!0',
  `elite` varchar(50) NOT NULL DEFAULT '0!0',
  `premium` varchar(50) NOT NULL DEFAULT '0!0',
  `archa` varchar(50) NOT NULL DEFAULT '0!0',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `pirox_subscriptions`
--

INSERT INTO `pirox_subscriptions` (`id`, `basic`, `elite`, `premium`, `archa`) VALUES
(1, '0!0', '0!0', '0!0', '0!0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
