-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 02 fév. 2023 à 05:43
-- Version du serveur : 5.7.36
-- Version de PHP : 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lebonwe`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories_chambres`
--

DROP TABLE IF EXISTS `categories_chambres`;
CREATE TABLE IF NOT EXISTS `categories_chambres` (
  `id_categorie` int(11) NOT NULL,
  `libelle_categorie` varchar(255) NOT NULL,
  `description_categorie` text NOT NULL,
  `nbr_chambre` int(11) NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE IF NOT EXISTS `country` (
  `id_country` int(8) NOT NULL AUTO_INCREMENT,
  `country_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id_country`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `hotels`
--

DROP TABLE IF EXISTS `hotels`;
CREATE TABLE IF NOT EXISTS `hotels` (
  `id_hotel` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `id_town` int(11) NOT NULL,
  PRIMARY KEY (`id_hotel`),
  KEY `id_town` (`id_town`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id_location` int(11) NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_logement` int(11) NOT NULL,
  PRIMARY KEY (`id_location`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `logements`
--

DROP TABLE IF EXISTS `logements`;
CREATE TABLE IF NOT EXISTS `logements` (
  `id_logement` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `id_town` int(11) NOT NULL,
  PRIMARY KEY (`id_logement`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id_reservation` int(11) NOT NULL,
  `date_reservation` date NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_categorie_chambre` int(11) NOT NULL,
  PRIMARY KEY (`id_reservation`),
  KEY `id_user` (`id_user`),
  KEY `id_categorie_chambre` (`id_categorie_chambre`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `sells`
--

DROP TABLE IF EXISTS `sells`;
CREATE TABLE IF NOT EXISTS `sells` (
  `id_sells` int(11) NOT NULL AUTO_INCREMENT,
  `date_sells` date NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_logement` int(11) NOT NULL,
  PRIMARY KEY (`id_sells`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `towns`
--

DROP TABLE IF EXISTS `towns`;
CREATE TABLE IF NOT EXISTS `towns` (
  `id_town` int(11) NOT NULL AUTO_INCREMENT,
  `town_name` varchar(255) NOT NULL,
  `id_country` int(8) NOT NULL,
  PRIMARY KEY (`id_town`),
  KEY `id_country` (`id_country`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `first_names` varchar(500) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `account_creation_date` date NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `id_town` int(8) NOT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
