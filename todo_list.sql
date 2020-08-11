-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 11 août 2020 à 16:08
-- Version du serveur :  8.0.13
-- Version de PHP :  7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `todo_list`
--

-- --------------------------------------------------------

--
-- Structure de la table `task`
--

DROP TABLE IF EXISTS `task`;
CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `is_done` tinyint(1) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_527EDB25A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `task`
--

INSERT INTO `task` (`id`, `created_at`, `title`, `content`, `is_done`, `user_id`) VALUES
(28, '2020-08-11 08:07:00', 'faire du sport', 'faire un minimum de 50 abdos, 30 pompes et 400m de course à pieds.', 0, 87),
(29, '2020-08-10 06:16:00', 'les courses', 'Contrôler que nous avons assez d\'aliments dans le garde mangés.', 1, 40),
(30, '2020-08-11 16:05:25', 'vérifier l\'application', 'Vérifier les fonctionnalités de l\'application.', 0, 98),
(31, '2020-08-11 16:06:59', 'Lire l\'audit de performance et de qualités.', 'Lire l\'audit pour savoir ce qu\'il y a a modifier.', 0, 98);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `roles`) VALUES
(40, 'gironcel', '$2y$13$z.nn2qAn6H/Gn7x54zcrdemNcm6WKIfnUjK4st5qsqRBPbT1aFWNS', 'gironcel.stella@gmail.com', '[\"ROLE_USER\"]'),
(87, 'garret', '$2y$13$cTYaGPgin8t58ZZsOYwk9e3Iuj0hrCTv./lNJeYXTH/RXOvNoF5sO', 'mickdu62200@gmail.com', '[\"ROLE_USER\", \"ROLE_ADMIN\"]'),
(98, 'openclassrooms', '$2y$13$1p1XAdZF9uvupTQTCkGa7uaUK1oPYsiOhT5kv0tUN6d/T/SawJCpK', 'openclassrooms@gmail.com', '[\"ROLE_USER\", \"ROLE_ADMIN\"]');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
