-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 11 mars 2026 à 17:59
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `recrutement`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_administrateur` int(11) NOT NULL,
  `nom` varchar(25) DEFAULT NULL,
  `prenom` varchar(25) DEFAULT NULL,
  `anee_naissance` int(11) DEFAULT NULL,
  `sexe` varchar(8) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `email` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id_administrateur`, `nom`, `prenom`, `anee_naissance`, `sexe`, `photo`, `telephone`, `email`) VALUES
(1, 'Kira', 'Steve', 2001, 'Masculin', ' ', '658895572', 'steve.boussa@outlook.com');

-- --------------------------------------------------------

--
-- Structure de la table `candidat`
--

CREATE TABLE `candidat` (
  `id_candidat` int(11) NOT NULL,
  `nom` varchar(25) DEFAULT NULL,
  `prenom` varchar(25) DEFAULT NULL,
  `annee_naissance` date DEFAULT NULL,
  `sexe` varchar(8) DEFAULT NULL,
  `formation` varchar(35) DEFAULT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `dernier_diplome` blob DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `email` varchar(25) DEFAULT NULL,
  `localisation` varchar(30) DEFAULT NULL,
  `CNI` varchar(45) DEFAULT NULL,
  `photo` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `candidat`
--

INSERT INTO `candidat` (`id_candidat`, `nom`, `prenom`, `annee_naissance`, `sexe`, `formation`, `portfolio`, `dernier_diplome`, `telephone`, `email`, `localisation`, `CNI`, `photo`) VALUES
(1, 'metewoue', 'maheva', '0000-00-00', 'feminin', ' ', 'genie logiciel', 0x20, 'baccalaureat', '69834810', 'maheva@237', 'bafoussam', ' ');

-- --------------------------------------------------------

--
-- Structure de la table `candidature`
--

CREATE TABLE `candidature` (
  `id_candidature` int(11) NOT NULL,
  `date_envoie` date DEFAULT NULL,
  `statut` varchar(45) DEFAULT NULL,
  `etat` varchar(45) DEFAULT NULL,
  `offre_id_offre` int(11) NOT NULL,
  `candidat_id_candidat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `candidature`
--

INSERT INTO `candidature` (`id_candidature`, `date_envoie`, `statut`, `etat`, `offre_id_offre`, `candidat_id_candidat`) VALUES
(5, '0000-00-00', 'actif', 'en cours', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `identreprise` int(11) NOT NULL,
  `secteur` varchar(45) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `telephone` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `localisation` varchar(45) DEFAULT NULL,
  `logo` varchar(45) DEFAULT NULL,
  `siteweb` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  `nomdirigeant` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `administrateur_id_administrateur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`identreprise`, `secteur`, `nom`, `telephone`, `email`, `localisation`, `logo`, `siteweb`, `type`, `nomdirigeant`, `description`, `administrateur_id_administrateur`) VALUES
(1, 'informatique', 'nmc', '698344810', 'nmc@gmail.com', 'bafoussam', ' ', ' ', 'dfg', 'grace', 'offre les services informatiques', 1);

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

CREATE TABLE `offre` (
  `id_offre` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `delai` varchar(100) DEFAULT NULL,
  `cible` varchar(105) DEFAULT NULL,
  `entreprise_identreprise` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `offre`
--

INSERT INTO `offre` (`id_offre`, `description`, `date_publication`, `delai`, `cible`, `entreprise_identreprise`) VALUES
(1, 'secretaire de bureau', '0000-00-00', '30jours', 'informaticien', 1);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `idphoto` int(11) NOT NULL,
  `datepublication` date DEFAULT NULL,
  `image` varchar(45) DEFAULT NULL,
  `entreprise_identreprise` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`idphoto`, `datepublication`, `image`, `entreprise_identreprise`) VALUES
(1, '2022-12-01', ' ', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_administrateur`);

--
-- Index pour la table `candidat`
--
ALTER TABLE `candidat`
  ADD PRIMARY KEY (`id_candidat`);

--
-- Index pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD PRIMARY KEY (`id_candidature`),
  ADD KEY `fk_candidature_offre_idx` (`offre_id_offre`),
  ADD KEY `fk_candidature_candidat1_idx` (`candidat_id_candidat`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`identreprise`),
  ADD KEY `fk_entreprise_administrateur1_idx` (`administrateur_id_administrateur`);

--
-- Index pour la table `offre`
--
ALTER TABLE `offre`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `fk_offre_entreprise1_idx` (`entreprise_identreprise`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`idphoto`),
  ADD KEY `fk_photo_entreprise1_idx` (`entreprise_identreprise`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_administrateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `candidat`
--
ALTER TABLE `candidat`
  MODIFY `id_candidat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `candidature`
--
ALTER TABLE `candidature`
  MODIFY `id_candidature` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `identreprise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `offre`
--
ALTER TABLE `offre`
  MODIFY `id_offre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `idphoto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `fk_candidature_candidat1` FOREIGN KEY (`candidat_id_candidat`) REFERENCES `candidat` (`id_candidat`),
  ADD CONSTRAINT `fk_candidature_offre` FOREIGN KEY (`offre_id_offre`) REFERENCES `offre` (`id_offre`);

--
-- Contraintes pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD CONSTRAINT `fk_entreprise_administrateur1` FOREIGN KEY (`administrateur_id_administrateur`) REFERENCES `administrateur` (`id_administrateur`);

--
-- Contraintes pour la table `offre`
--
ALTER TABLE `offre`
  ADD CONSTRAINT `fk_offre_entreprise1` FOREIGN KEY (`entreprise_identreprise`) REFERENCES `entreprise` (`identreprise`);

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `fk_photo_entreprise1` FOREIGN KEY (`entreprise_identreprise`) REFERENCES `entreprise` (`identreprise`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
