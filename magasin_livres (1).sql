-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 22 avr. 2025 à 16:26
-- Version du serveur :  8.0.21
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `magasin_livres`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_foreign` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `description`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Cuisine', 'DSC cuisine', NULL, '2025-04-12 23:11:34', '2025-04-20 23:46:28'),
(2, 'Roman', 'dsc Roman', NULL, '2025-04-12 23:11:34', '2025-04-20 23:45:14'),
(3, 'Programmation', 'dsc dev', NULL, '2025-04-12 23:12:31', '2025-04-12 23:12:31'),
(4, 'Réseaux', 'dsc Réseaux', NULL, '2025-04-12 23:12:31', '2025-04-12 23:12:31');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `contenu` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `utilisateur_id` bigint UNSIGNED NOT NULL,
  `ouvrage_id` bigint UNSIGNED NOT NULL,
  `note` int NOT NULL DEFAULT '0',
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commentaires_utilisateur_id_foreign` (`utilisateur_id`),
  KEY `commentaires_ouvrage_id_foreign` (`ouvrage_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `contenu`, `utilisateur_id`, `ouvrage_id`, `note`, `statut`, `created_at`, `updated_at`) VALUES
(1, 'test', 1, 2, 5, 'approuve', '2025-04-20 20:36:12', '2025-04-22 20:18:26'),
(2, 'fgft', 2, 6, 2, 'rejete', '2025-04-20 20:36:12', '2025-04-20 20:36:12'),
(3, 'bon jivre', 1, 7, 4, 'approuve', '2025-04-20 21:55:28', '2025-04-20 21:55:28'),
(4, 'bon', 2, 2, 5, 'approuve', '2025-04-20 21:55:28', '2025-04-21 02:19:32');

-- --------------------------------------------------------

--
-- Structure de la table `ligne_ventes`
--

DROP TABLE IF EXISTS `ligne_ventes`;
CREATE TABLE IF NOT EXISTS `ligne_ventes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vente_id` bigint UNSIGNED NOT NULL,
  `ouvrage_id` bigint UNSIGNED NOT NULL,
  `utilisateur_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_vente` (`vente_id`),
  KEY `fk_ouvrage` (`ouvrage_id`),
  KEY `fk_utilisateur` (`utilisateur_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ligne_ventes`
--

INSERT INTO `ligne_ventes` (`id`, `vente_id`, `ouvrage_id`, `utilisateur_id`, `quantite`, `prix_unitaire`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 12, '10.00', '2025-04-21 20:31:23', '2025-04-21 20:31:23'),
(2, 3, 7, 2, 3, '30.00', '2025-04-21 20:31:23', '2025-04-21 20:31:23');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_04_12_224248_create_ouvrages_table', 1),
(2, '2025_04_12_224603_create_utilisateurs_table', 1),
(3, '2025_04_12_224701_create_categories_table', 1),
(4, '2025_04_12_224742_create_ventes_table', 1),
(5, '2025_04_12_224903_create_ligne_ventes_table', 1),
(6, '2025_04_12_224953_create_stocks_table', 1),
(7, '2025_04_12_225015_create_commentaires_table', 1),
(8, '2025_04_19_213722_add_parent_id_to_categories_table', 2);

-- --------------------------------------------------------

--
-- Structure de la table `ouvrages`
--

DROP TABLE IF EXISTS `ouvrages`;
CREATE TABLE IF NOT EXISTS `ouvrages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `annee_publication` int NOT NULL,
  `niveau` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `categorie_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ouvrages_categorie_id_foreign` (`categorie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ouvrages`
--

INSERT INTO `ouvrages` (`id`, `titre`, `annee_publication`, `niveau`, `photo`, `description`, `categorie_id`, `created_at`, `updated_at`) VALUES
(2, 'Premiers pas en cuisine', 2009, 'Débutant', 'ghgh', '', 1, '2025-04-12 23:14:56', '2025-04-12 23:14:56'),
(7, 'Poo', 2009, 'Debutant', '1745102760montre2.jpg', 'poo pour', 3, '2025-04-20 02:46:00', '2025-04-20 02:46:00'),
(6, 'Cuisine marocaine', 2004, 'Intermediaire', '1745089249montre5.jpg', 'ffhfhfh', 3, '2025-04-19 23:00:49', '2025-04-20 00:44:39');

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ouvrage_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `prix_achat` decimal(8,2) NOT NULL,
  `prix_vente` decimal(8,2) NOT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_ouvrage_id_foreign` (`ouvrage_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `stocks`
--

INSERT INTO `stocks` (`id`, `ouvrage_id`, `quantite`, `prix_achat`, `prix_vente`, `statut`, `created_at`, `updated_at`) VALUES
(1, 2, 100, '120.00', '127.00', 'En stock', '2025-04-21 18:06:24', '2025-04-21 18:06:24'),
(2, 6, 36, '90.00', '99.00', 'Rupture', '2025-04-21 18:06:24', '2025-04-21 18:06:24'),
(3, 7, 5, '3.78', '4.13', 'Rupture', '2025-04-21 23:34:12', '2025-04-21 23:34:12');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `utilisateurs_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `adresse`, `telephone`, `photo`, `role`, `statut`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'GNING', 'Youssou', '220, rue isaac-christin', '5146019995', 'test', 'user', '', 'yussuf@gmail.com', 'passer1234', '2025-04-20 21:53:49', '2025-04-20 21:53:49'),
(2, 'FAYE', 'Moussa', '390, RUE qwerty', '897 278 9874', 'ffjfjf', 'admin', '', 'test@gmail.com', 'qwer1234', '2025-04-20 21:53:49', '2025-04-20 21:53:49');

-- --------------------------------------------------------

--
-- Structure de la table `ventes`
--

DROP TABLE IF EXISTS `ventes`;
CREATE TABLE IF NOT EXISTS `ventes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `montant_total` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ventes`
--

INSERT INTO `ventes` (`id`, `montant_total`, `created_at`, `updated_at`) VALUES
(1, '200.00', '2025-04-21 20:26:18', '2025-04-21 20:26:18'),
(2, '300.00', '2025-04-21 20:26:18', '2025-04-21 20:26:18'),
(3, '250.00', '2025-04-21 20:26:47', '2025-04-21 20:26:47'),
(4, '500.00', '2025-04-21 20:26:47', '2025-04-21 20:26:47');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
