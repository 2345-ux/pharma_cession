-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 11, 2024 at 01:53 PM
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
  `code` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_categories`
--

INSERT INTO `t_categories` (`id`, `code`, `nom`) VALUES
(8, '202411051424061852', 'Hygiène'),
(7, '202411051423560333', 'Matériel médical'),
(6, '202411051423424021', 'Médicaments'),
(9, '202411051424271457', 'Parapharmacie'),
(10, '202411051424405063', 'Premier soins'),
(11, 'CAT-202411051602168140', 'Anti-Biotic'),
(12, '11', '11'),
(13, '8', '8'),
(14, '202411060802095719', 'Anasthesique'),
(15, '14', '14'),
(16, '202411080953491435', 'Anti-vers'),
(17, '16', '16'),
(18, '202411080958493825', 'HSQOFISJ'),
(19, '18', '18');

-- --------------------------------------------------------

--
-- Table structure for table `t_entrees`
--

DROP TABLE IF EXISTS `t_entrees`;
CREATE TABLE IF NOT EXISTS `t_entrees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Code unique généré automatiquement',
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du produit',
  `quantite` int(11) NOT NULL DEFAULT '0' COMMENT 'Quantité du produit',
  `prix_fournisseur` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Prix unitaire fournisseur',
  `prix_total` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Prix total du produit',
  `date_ajout` date NOT NULL COMMENT 'Date d''ajout du produit',
  `date_expiration` date NOT NULL COMMENT 'Date d''expiration du produit',
  `fournisseur` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nom du fournisseur',
  `categorie` varchar(50) CHARACTER SET utf8 NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création de l''enregistrement',
  `date_modification` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date de dernière modification',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_entrees`
--

INSERT INTO `t_entrees` (`id`, `code`, `nom`, `quantite`, `prix_fournisseur`, `prix_total`, `date_ajout`, `date_expiration`, `fournisseur`, `categorie`, `date_creation`, `date_modification`) VALUES
(1, 'ENT20241031094212152', 'Amoxicilline', 10, '1000.00', '10000.00', '2025-02-28', '2025-01-01', 'moha', '', '2024-10-31 09:42:12', '2024-10-31 09:42:12'),
(2, 'ENT20241031110237635', 'para', 120, '1200.00', '144000.00', '2024-10-31', '2024-11-07', 'moha', 'comp', '2024-10-31 11:02:37', '2024-10-31 11:02:37'),
(3, 'ENT20241031110321052', 'artemether', 12, '1450.00', '17400.00', '2024-10-31', '2025-05-21', 'moha', 'gelulles', '2024-10-31 11:03:21', '2024-10-31 11:03:21'),
(4, 'ENT20241031152907621', 'Amoxicilline', 130, '1200.00', '156000.00', '2024-10-31', '2025-05-29', 'moha', 'gelulles', '2024-10-31 15:29:07', '2024-10-31 15:29:07'),
(5, 'ENT20241031153859145', 'Amoxicilline', 10, '1000.00', '10000.00', '2024-10-31', '2025-04-30', 'moha', 'gelulles', '2024-10-31 15:38:59', '2024-10-31 15:38:59'),
(6, 'ENT20241101092335017', 'paracetamol', 500, '100.00', '50000.00', '2024-11-01', '2025-09-25', 'moha', 'comprimés', '2024-11-01 09:23:35', '2024-11-01 09:23:35'),
(7, 'ENT20241101100832523', 'hhhh', 22, '52.00', '1144.00', '2024-11-01', '2025-02-27', 'moha', 'anti-tout', '2024-11-01 10:08:32', '2024-11-01 10:08:32'),
(8, 'ENT20241101100917885', 'Amoxicilline', 130, '1200.00', '156000.00', '2024-11-01', '2025-03-26', 'moha', 'anti-tout', '2024-11-01 10:09:17', '2024-11-01 10:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `t_fournisseurs`
--

DROP TABLE IF EXISTS `t_fournisseurs`;
CREATE TABLE IF NOT EXISTS `t_fournisseurs` (
  `code` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `adre` varchar(250) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_fournisseurs`
--

INSERT INTO `t_fournisseurs` (`code`, `nom`, `tel`, `adre`, `email`) VALUES
('001', 'DAHICO', '12346', 'Niamey', NULL),
('002', 'UBIPHARM', '12346789', 'Niamey2', NULL),
('003', 'Laborex', NULL, NULL, NULL),
('202410241138553394', 'Abdoulwahabou SOULEY', '', 'Niamey', 'abdoulwahabous@gmail.com'),
('202410241139242855', 'HumanWell', '', '', ''),
('202410241206096525', 'Un autre', '', '', ''),
('202410241206297892', 'Vendeur unique', '', '', ''),
('202410241210148620', 'fournisseur externe', '25897', 'Filingué', 'four@info.ne'),
('202410241210458550', 'Abdoulwahabou SOULEY v2', '25897', 'Niamey', 'abdoulwahabous@gmail.com'),
('202410251032360938', 'Hamadou Alfousseini', '', 'Reference/Tchangarey', 'haidarahamadou128@gmail.com'),
('202410251033054191', 'Hamadou Alfousseini', '', 'Reference/Tchangarey', 'haidarahamadou128@gmail.com'),
('202410251142019921', 'Hamadou Alfousseini sarmey', '', 'Reference/Tchangarey', 'haidarahamadou128@gmail.com'),
('202410291059435178', 'UNIPHARM', '+22792868032', 'Reference/Tchangarey', 'haidarahamadou128@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `t_produit`
--

DROP TABLE IF EXISTS `t_produit`;
CREATE TABLE IF NOT EXISTS `t_produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `categorie` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `categorie_id` (`categorie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_produit`
--

INSERT INTO `t_produit` (`id`, `code`, `nom`, `categorie_id`, `categorie`) VALUES
(1, 'MED001', 'Paracétamol', 1, ''),
(2, 'MED002', 'Ibuprofène', 1, ''),
(3, 'MAT001', 'Seringues', 2, ''),
(4, 'HYG001', 'Gel hydroalcoolique', 3, ''),
(5, 'PAR001', 'Vitamines C', 4, ''),
(6, 'PS001', 'Bandages', 5, ''),
(7, 'PRD20241106073839732', 'Amoxicilline', NULL, '11'),
(8, 'PRD20241106074027217', 'Amoxicilline', NULL, '11'),
(9, 'PRD20241106074042429', 'Amoxicilline', NULL, '8'),
(10, 'PRD20241106080213448', 'adrenaline', NULL, '14'),
(11, 'PRD20241108095442177', 'Albendazole', NULL, '16'),
(12, 'PRD20241108095859506', '¨DUJHLLIDSQ', NULL, '18');

-- --------------------------------------------------------

--
-- Table structure for table `t_sorties`
--

DROP TABLE IF EXISTS `t_sorties`;
CREATE TABLE IF NOT EXISTS `t_sorties` (
  `code` varchar(50) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `date_sortie` date NOT NULL,
  `date_expiration` date NOT NULL,
  `categorie` varchar(50) NOT NULL,
  `destinataire` varchar(50) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_sorties`
--

INSERT INTO `t_sorties` (`code`, `nom`, `quantite`, `prix_unitaire`, `prix_total`, `date_sortie`, `date_expiration`, `categorie`, `destinataire`) VALUES
('SRT20241030090507498', 'amoxicilline', 120, '1200.00', '144000.00', '2024-10-30', '2025-01-15', '', ''),
('SRT20241030121455451', 'ampiciline', 200, '1500.00', '300000.00', '2024-10-30', '2024-12-11', '', ''),
('SRT20241031105435947', 'para', 12, '1000.00', '12000.00', '2024-10-31', '2025-02-26', 'inj', ''),
('SRT20241101095907549', 'amoxicilline', 500, '1100.00', '550000.00', '2024-11-01', '2025-05-01', 'gelulles', '');

-- --------------------------------------------------------

--
-- Table structure for table `t_stock`
--

DROP TABLE IF EXISTS `t_stock`;
CREATE TABLE IF NOT EXISTS `t_stock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_fournisseur` decimal(10,2) NOT NULL,
  `prix_total` decimal(10,2) NOT NULL,
  `date_ajout` date NOT NULL,
  `date_expiration` date NOT NULL,
  `fournisseur` varchar(100) NOT NULL,
  `categorie` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_stock`
--

INSERT INTO `t_stock` (`id`, `code`, `nom`, `quantite`, `prix_fournisseur`, `prix_total`, `date_ajout`, `date_expiration`, `fournisseur`, `categorie`) VALUES
(1, 'ENT20241031154539772', 'Amoxicilline', 10, '1000.00', '10000.00', '2024-10-31', '2025-04-23', 'moha', 'gelulles'),
(2, 'ENT20241031154622186', 'ampiciline', 120, '1200.00', '144000.00', '2024-10-31', '2025-05-27', 'moha', 'sirop');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
