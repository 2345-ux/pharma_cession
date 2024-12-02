-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 02, 2024 at 10:15 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharma_cession`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_categories`
--

DROP TABLE IF EXISTS `t_categories`;
CREATE TABLE IF NOT EXISTS `t_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_categories`
--

INSERT INTO `t_categories` (`id`, `code`, `nom`, `created_at`, `updated_at`) VALUES
(1, '202411221413448498', 'Médicaments', '2024-11-22 14:13:44', '2024-11-22 14:13:44'),
(2, '202411301808386426', 'Premier soins', '2024-11-30 18:08:38', '2024-11-30 18:08:38');

-- --------------------------------------------------------

--
-- Table structure for table `t_commandes`
--

DROP TABLE IF EXISTS `t_commandes`;
CREATE TABLE IF NOT EXISTS `t_commandes` (
  `id_commande` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `id_produit` varchar(50) DEFAULT NULL,
  `id_fournisseur` varchar(50) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `prix_total` decimal(10,2) GENERATED ALWAYS AS ((`quantite` * `prix_unitaire`)) STORED,
  `date_ajout` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  PRIMARY KEY (`id_commande`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_commandes`
--

INSERT INTO `t_commandes` (`id_commande`, `code`, `id_produit`, `id_fournisseur`, `quantite`, `prix_unitaire`, `date_ajout`, `date_expiration`) VALUES
(1, 'CMD202411231127305855', 'Amoxicilline', 'Alfousseini', 10, '100.00', '2024-11-30', '2024-11-23'),
(2, 'CMD202411231140528709', 'paracetamol cp 250 mg', 'Hamadou Alfousseini sarmey', 100, '2000.00', '2024-11-23', '2024-11-30'),
(3, 'CMD202411251354556264', 'cloxa gel 500 mg', 'Alfousseini', 10, '1000.00', '2024-11-25', '2025-06-26'),
(4, 'CMD202411260725264941', 'cloxa gel 500 mg', 'Alfousseini', 12, '1000.00', '2024-11-26', '2025-01-23'),
(5, 'CMD202411260725315362', 'cloxa gel 500 mg', 'Alfousseini', 12, '1000.00', '2024-11-26', '2025-01-23'),
(6, 'CMD202411260825096438', 'Amoxicilline', 'Alfousseini', 1000, '1001.00', '2024-11-26', '2024-11-27'),
(7, 'CMD202411260830087513', 'Amoxicilline', 'Alfousseini', 1100, '1220.00', '2024-11-26', '2025-03-04'),
(8, 'CMD202411260908400315', '1', '2', 10, '4000.00', '2024-11-26', '2025-02-05'),
(9, 'CMD202411260921273851', '3', '1', 10, '10000.00', '2024-11-26', '2025-01-27'),
(10, 'CMD202411260922156048', '1', '1', 11000, '1010.00', '2024-11-06', '2025-02-24'),
(11, 'CMD202411260923386734', '1', '1', 1000, '100.00', '2024-11-26', '2025-05-02'),
(12, 'CMD202411260924301328', '2', '2', 100, '1000.00', '2024-11-26', '2025-01-28'),
(13, 'CMD202411261007557459', '2', '2', 10, '1000.00', '2024-11-26', '2024-12-26'),
(14, 'CMD202411280901552950', '3', '3', 10, '1000.00', '2024-11-28', '2025-02-26'),
(15, 'CMD202411281019189683', '2', '2', 10, '1000.00', '2024-11-28', '2024-12-31'),
(16, 'CMD202411281031027643', '2', '2', 5, '500.00', '2024-11-28', '2024-12-30'),
(17, 'CMD202411281037308012', '2', '2', 15, '200.00', '2024-11-28', '2024-12-29'),
(18, 'CMD202411281039197669', '2', '2', 4, '5.00', '2024-11-28', '2024-12-28'),
(19, 'CMD202411281042275068', '2', '2', 10, '1500.00', '2024-11-28', '2024-12-28'),
(20, 'CMD202411281046453977', '2', '2', 5, '1500.00', '2024-11-28', '2024-12-28'),
(21, 'CMD202411281124295135', '1', '3', 12, '6000.00', '2024-11-28', '2025-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `t_fournisseurs`
--

DROP TABLE IF EXISTS `t_fournisseurs`;
CREATE TABLE IF NOT EXISTS `t_fournisseurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tel` varchar(15) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_fournisseurs`
--

INSERT INTO `t_fournisseurs` (`id`, `code`, `nom`, `email`, `tel`, `adresse`, `created_at`, `updated_at`) VALUES
(1, '202411221428535540', 'Hamadou Alfousseini', 'haidarahamadou128@gmail.com', '+22792868032', 'Reference/Tchangarey', '2024-11-22 14:28:53', '2024-11-22 14:28:53'),
(2, '202411230859020835', 'Alfousseini', 'haidarahamadou128@gmail.com', '', 'Reference/Tchangarey', '2024-11-23 08:59:02', '2024-11-23 08:59:02'),
(3, '202411230959013795', 'Hamadou Alfousseini sarmey', 'haidarahamadou128@gmail.com', '+22792868032', 'Reference/Tchangarey', '2024-11-23 09:59:01', '2024-11-23 09:59:01');

-- --------------------------------------------------------

--
-- Table structure for table `t_produits`
--

DROP TABLE IF EXISTS `t_produits`;
CREATE TABLE IF NOT EXISTS `t_produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `categorie` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_produits`
--

INSERT INTO `t_produits` (`id`, `code`, `nom`, `categorie`, `created_at`, `updated_at`) VALUES
(1, 'PRD20241122142341744', 'Amoxicilline', '202411221413448498', '2024-11-22 14:23:41', '2024-11-22 14:23:41'),
(2, 'PRD20241123084045607', 'paracetamol cp 250 mg', '202411221413448498', '2024-11-23 08:40:45', '2024-11-23 08:40:45'),
(3, 'PRD20241125135428037', 'cloxa gel 500 mg', '202411221413448498', '2024-11-25 13:54:28', '2024-11-25 13:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `t_sorties`
--

DROP TABLE IF EXISTS `t_sorties`;
CREATE TABLE IF NOT EXISTS `t_sorties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `id_produit` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_sorties`
--

INSERT INTO `t_sorties` (`id`, `code`, `id_produit`, `quantite`, `prix_unitaire`, `prix_total`, `date_sortie`, `date_expiration`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 10, '10.00', '100.00', '2024-11-22', '2024-11-22', '2024-11-22 14:33:41', '2024-11-22 14:33:41'),
(2, NULL, 2, 12, '1000.00', '12000.00', '2024-11-27', '2024-12-26', '2024-11-26 07:28:02', '2024-11-26 07:28:02'),
(3, 'SORT202411301653533815', 2, 12, '6000.00', '72000.00', '2024-11-30', '2024-12-28', '2024-11-30 16:53:53', '2024-11-30 16:53:53');

-- --------------------------------------------------------

--
-- Table structure for table `t_stock`
--

DROP TABLE IF EXISTS `t_stock`;
CREATE TABLE IF NOT EXISTS `t_stock` (
  `id_stock` int(11) NOT NULL AUTO_INCREMENT,
  `id_produit` int(11) NOT NULL,
  `id_commandes` int(11) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT '0',
  `date_ajout` datetime DEFAULT NULL,
  `date_expiration` datetime DEFAULT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL DEFAULT '0.00',
  `valeur_totale` decimal(10,2) GENERATED ALWAYS AS ((`quantite` * `prix_unitaire`)) STORED,
  PRIMARY KEY (`id_stock`),
  UNIQUE KEY `id_produit` (`id_produit`,`date_expiration`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_stock`
--

INSERT INTO `t_stock` (`id_stock`, `id_produit`, `id_commandes`, `quantite`, `date_ajout`, `date_expiration`, `prix_unitaire`) VALUES
(1, 1, 8, 24020, '2024-11-26 00:00:00', '2025-05-02 00:00:00', '100.00'),
(2, 3, 9, 40, '2024-11-28 00:00:00', '2025-02-26 00:00:00', '1000.00'),
(3, 2, 12, 238, '2024-11-28 00:00:00', '2024-12-30 00:00:00', '500.00'),
(4, 2, 20, 3, '2024-11-28 00:00:00', '2024-12-28 00:00:00', '1500.00'),
(5, 1, 21, 12, '2024-11-28 00:00:00', '2025-01-01 00:00:00', '6000.00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
