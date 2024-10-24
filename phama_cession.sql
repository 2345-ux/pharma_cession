-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : jeu. 24 oct. 2024 à 13:02
-- Version du serveur : 5.7.39
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `phama_cession`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_fournisseurs`
--

CREATE TABLE `t_fournisseurs` (
  `code` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `adre` varchar(250) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `t_fournisseurs`
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
('202410241210458550', 'Abdoulwahabou SOULEY v2', '25897', 'Niamey', 'abdoulwahabous@gmail.com');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `t_fournisseurs`
--
ALTER TABLE `t_fournisseurs`
  ADD PRIMARY KEY (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
